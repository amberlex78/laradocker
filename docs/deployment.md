# Розгортання на VPS

Production-образи збираються безпосередньо на VPS із поточної версії Git. Окремий Docker Registry не потрібен. PHP, Composer, Node.js і MariaDB не потрібно встановлювати безпосередньо на сервері — вони працюють у Docker.

Ця інструкція описує повне розгортання на Ubuntu 24.04 із Cloudflare та доменом `esp32.xyz`.

## Як розділений production-стек

У production використовуються два Nginx із різними завданнями:

- Nginx-контейнер обслуговує Laravel і зібраний frontend на внутрішньому Docker-порті `8080`;
- системний Nginx на VPS приймає HTTPS-запити та проксіює їх на `127.0.0.1:8080`.

Node.js використовується лише під час побудови Docker-образу. Команди `npm ci` і `npm run build` виконуються у тимчасовому frontend-builder stage, а готові файли копіюються в Nginx image. Тому `node_modules` не повинен існувати на VPS.

Composer-залежності встановлюються в PHP image. У checkout-каталозі на сервері також не обов’язково має бути папка `vendor/`.

## 1. Підготовка сервера

На сервері мають бути Docker Engine із Compose plugin, GNU Make і SSH-доступ. Перевір доступні інструменти:

```bash
docker --version
docker compose version
make --version
```

Встанови системний Nginx на Ubuntu:

```bash
sudo apt update
sudo apt install -y nginx
sudo systemctl enable --now nginx
```

Перевір, що він запущений:

```bash
sudo systemctl status nginx --no-pager
sudo nginx -t
curl -I http://127.0.0.1
```

Якщо увімкнений UFW, дозволь HTTP та HTTPS:

```bash
sudo ufw allow 'Nginx Full'
sudo ufw status
```

## 2. Клонування проєкту та production-конфігурація

```bash
git clone git@github.com:amberlex78/laradocker.git
cd laradocker
cp .env.prod.example .env.prod
```

Згенеруй ключ застосунку:

```bash
openssl rand -base64 32
```

Скопіюй згенероване значення та відкрий production-файл оточення:

```bash
nano .env.prod
```

Мінімально потрібно замінити всі placeholder-значення на реальні:

```dotenv
COMPOSE_PROJECT_NAME=laradockervps
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:скопійований-ключ
APP_URL=http://127.0.0.1:8080
PROD_HTTP_PORT=8080
DB_CONNECTION=mariadb
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=сильний-пароль-для-бази
DB_ROOT_PASSWORD=інший-сильний-пароль
```

Початковий `APP_URL` підходить для локальної перевірки застосунку безпосередньо на VPS. Після налаштування Cloudflare заміни його на:

```dotenv
APP_URL=https://esp32.xyz
```

Файл `.env.prod` виключений із Docker build context. `APP_KEY` і паролі бази даних не вбудовуються в Docker-образ. Надалі використовуй той самий `APP_KEY` під час наступних деплоїв.

## 3. Побудова та запуск production-стека

Перевір конфігурацію Compose:

```bash
make config-prod
```

Якщо конфігурація правильна, ця команда зазвичай нічого не виводить. Будь-який вивід із помилкою означає, що конфігурацію потрібно виправити перед деплоєм.

Запусти деплой:

```bash
make prod-deploy
```

`prod-deploy` виконує таку безпечну послідовність:

1. Збирає PHP-, Nginx- і MariaDB-образи.
2. Встановлює Composer-залежності в PHP image.
3. Встановлює Node-залежності та збирає frontend у Nginx image.
4. Запускає або оновлює production-сервіси.
5. Виконує `php artisan migrate --force`.
6. Кешує конфігурацію, маршрути та view.
7. Перевіряє Laravel endpoint `/up` через локальний production-порт.

Команда не виконує `migrate:fresh`, не видаляє volumes і не видаляє дані бази.

Перевір статус сервісів:

```bash
make prod-status
```

Очікувано сервіси `app`, `mariadb` і `nginx` мають бути в статусі `Up` та `(healthy)`. Для Nginx має бути приблизно таке port mapping:

```text
127.0.0.1:8080->8080/tcp
```

В image tag може відображатися `:local`. Це лише значення за замовчуванням для `IMAGE_TAG` і не означає, що запущено development Compose stack. Щоб використовувати зрозуміліший production tag, перед повторною збіркою додай у `.env.prod`:

```dotenv
IMAGE_TAG=prod
```

## 4. Перевірка production-образів

Frontend знаходиться всередині Nginx-контейнера, а не в checkout-каталозі на сервері. Перевір його так:

```bash
docker compose --env-file .env.prod \
  -f docker-compose.yml \
  -f docker-compose.prod.yml \
  exec -T nginx \
  sh -lc 'find /var/www/html/public -maxdepth 2 -type f | sort | head -30'
```

Очікуваний результат:

```text
/var/www/html/public/.htaccess
/var/www/html/public/build/fonts-manifest.json
/var/www/html/public/build/manifest.json
/var/www/html/public/favicon.ico
/var/www/html/public/index.php
/var/www/html/public/robots.txt
```

Composer-залежності знаходяться всередині PHP-контейнера. Перевір їх так:

```bash
docker compose --env-file .env.prod \
  -f docker-compose.yml \
  -f docker-compose.prod.yml \
  exec -T app \
  sh -lc 'test -f vendor/autoload.php && echo vendor-ok'
```

Очікуваний результат:

```text
vendor-ok
```

Перевір Laravel через Docker Nginx port:

```bash
curl -i http://127.0.0.1:8080/up
```

Очікуваний результат:

```text
HTTP/1.1 200 OK
Server: nginx
Content-Type: text/html; charset=utf-8
Connection: keep-alive
Cache-Control: no-cache, private
```

## 5. Встановлення Cloudflare Origin Certificate

Cloudflare Origin Certificate складається з двох файлів:

- `origin.crt` — сертифікат;
- `origin.key` — приватний ключ.

Не додавай жоден із цих файлів у Git. На локальному комп’ютері скопіюй їх у домашню папку користувача на сервері:

```bash
scp /path/to/origin.crt /path/to/origin.key USER@SERVER_IP:~/
```

На сервері встанови їх із правильними правами доступу:

```bash
sudo install -d -m 700 /etc/nginx/ssl/cloudflare

sudo install -m 644 ~/origin.crt \
  /etc/nginx/ssl/cloudflare/esp32.xyz.origin.crt

sudo install -m 600 ~/origin.key \
  /etc/nginx/ssl/cloudflare/esp32.xyz.origin.key
```

Перевір встановлені файли:

```bash
sudo ls -l /etc/nginx/ssl/cloudflare
```

Після перевірки видали лише тимчасові копії з домашньої папки сервера. Захищену резервну копію ключа збережи в іншому безпечному місці:

```bash
rm ~/origin.crt ~/origin.key
```

## 6. Налаштування системного Nginx як reverse proxy

У репозиторії є готовий production-шаблон системного Nginx:

```text
docker/nginx/host/prod.conf.example
```

Скопіюй його в конфігурацію Nginx на сервері:

```bash
sudo cp docker/nginx/host/prod.conf.example \
  /etc/nginx/sites-available/esp32.xyz
```

Ubuntu після встановлення Nginx зазвичай має увімкнений стандартний сайт. Він також використовує `default_server` на порту `80`, тому його потрібно вимкнути перед активацією production-конфігурації:

```bash
sudo unlink /etc/nginx/sites-enabled/default
```

Це видаляє лише symlink із `sites-enabled`; стандартний файл у `sites-available` не видаляється.

Якщо розгортаєш інший домен, відредагуй скопійований файл і заміни:

- `esp32.xyz` і `www.esp32.xyz` на свої домени;
- шлях до `ssl_certificate`, якщо сертифікат має іншу назву або розташування;
- шлях до `ssl_certificate_key`, якщо ключ має іншу назву або розташування.

Конфігурація використовує `proxy_pass http://127.0.0.1:8080`. Вона не використовує `root /var/www/html/public` і не підключається напряму до `app:9000`, оскільки це адреси та шляхи Docker-контейнерів, а не системного Nginx на VPS.

Шаблон також містить security headers, заборону доступу до прихованих файлів, вимкнення зайвого логування для `favicon.ico` і `robots.txt`, а також кешування frontend-ресурсів на 7 днів. Для статичних файлів використовується `proxy_pass`, а не `try_files`, оскільки системний Nginx не має доступу до `/var/www/html/public` усередині Docker-образу.

Активуй сайт і перезавантаж Nginx:

```bash
sudo ln -s /etc/nginx/sites-available/esp32.xyz \
  /etc/nginx/sites-enabled/esp32.xyz

sudo nginx -t
sudo systemctl reload nginx
```

Системний Nginx має проксіювати запити на `127.0.0.1:8080`. Не використовуй checkout-каталог Git як `root`, оскільки production frontend і Composer-залежності зберігаються в Docker-образах.

Для локального development-оточення є окремий шаблон:

```text
docker/nginx/host/dev.conf.example
```

Він проксіює системний Nginx на dev-порт Docker `127.0.0.1:8000` і не потребує SSL.

## 7. Налаштування Cloudflare та фінального Laravel URL

У Cloudflare:

1. Створи `A`-запис для `esp32.xyz`, який вказує на IP-адресу VPS.
2. Увімкни Proxy для цього запису — помаранчеву хмаринку.
3. Встанови режим SSL/TLS `Full (strict)`.

Зміни `.env.prod` на публічний HTTPS URL:

```dotenv
APP_URL=https://esp32.xyz
```

Застосуй змінене оточення та перебудуй кеш конфігурації Laravel:

```bash
make prod-up
make prod-optimize
```

До налаштування DNS перевір HTTPS virtual host локально на VPS:

```bash
curl -k --resolve esp32.xyz:443:127.0.0.1 https://esp32.xyz/up
```

Очікуваний результат:

```text
HTTP/1.1 200 OK
```

Після налаштування DNS і Cloudflare перевір публічний endpoint:

```bash
curl -I https://esp32.xyz/up
```

## 8. Деплой нової версії

У каталозі проєкту на VPS:

```bash
git pull
make prod-deploy
```

Наявний volume `mariadb-data` зберігається. У `.env.prod` мають залишатися той самий `APP_KEY` і ті самі облікові дані бази даних.

## Reverse proxy для іншого проєкту

Для другого Laravel-проєкту використовуй інший hostname і host port, наприклад `app-two.example.com` → `127.0.0.1:8081`. Два Compose-проєкти мають використовувати різні значення `COMPOSE_PROJECT_NAME` і різні host-порти.

## Резервне копіювання та відновлення

Створи логічний backup без відкриття MariaDB назовні:

```bash
docker compose --env-file .env.prod -f docker-compose.yml -f docker-compose.prod.yml exec -T mariadb \
    sh -lc 'mariadb-dump -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' > backup.sql
```

Відновлюй backup лише після перевірки цільової бази даних:

```bash
cat backup.sql | docker compose --env-file .env.prod -f docker-compose.yml -f docker-compose.prod.yml exec -T mariadb \
    sh -lc 'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"'
```

Використовуй password manager або захищене shell-оточення для credentials backup. Зберігай backup за межами каталогу проєкту та періодично перевіряй відновлення.

## Діагностика

- `make config-prod` — перевіряє інтерполяцію Compose і конфігурацію сервісів;
- `make prod-status` — показує статус і health state контейнерів;
- `make prod-logs` — показує логи production-сервісів;
- `docker compose ... exec app php artisan about --only=environment` — підтверджує активне Laravel environment;
- якщо `/up` не працює, спочатку перевір health MariaDB, потім логи PHP-FPM і Nginx.
