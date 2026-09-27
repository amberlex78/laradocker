# Laravel-проєкти з доменами

Коротка інструкція для запуску копій репозиторію
https://github.com/amberlex78/laradocker:

- локально: `/home/{user}/projects/`;
- на VPS: `/home/{user}/projects/`;
- production-доступ через окремі домени та HTTPS;
- Cloudflare працює у режимі `Full (strict)`;
- системний Nginx маршрутизує домени до різних Docker-стеків.

Передбачається, що Docker, Docker Compose, GNU Make і системний Nginx уже
встановлені.

## 1. Схема доменів і портів

Локальна і серверна копії використовують однакові назви папок. Змінюється
лише значення `{user}` у шляху.

| Домен | Папка | Локальний порт | Production host port |
| --- | --- | --- | --- |
| `example1.com` | `laradocker1` | `DEV_HTTP_PORT=8081` | `PROD_HTTP_PORT=18080` |
| `example2.com` | `laradocker2` | `DEV_HTTP_PORT=8082` | `PROD_HTTP_PORT=28080` |

На VPS трафік проходить так:

```text
Cloudflare -> VPS Nginx:443 -> example1.com -> 127.0.0.1:18080 -> Docker Nginx:8080
Cloudflare -> VPS Nginx:443 -> example2.com -> 127.0.0.1:28080 -> Docker Nginx:8080
Docker Nginx:8080 -> project app:9000
```

Порти `18080` і `28080` прив'язані лише до `127.0.0.1` і не відкриваються
назовні. У production через домени назовні відкриті тільки `80/tcp` і
`443/tcp`. Порти `8081` і `8082` у цій інструкції стосуються лише локальної
розробки, а не production-доступу.

Кожен production-проєкт повинен мати власні:

- `COMPOSE_PROJECT_NAME`;
- `APP_KEY`;
- базу даних і credentials;
- `PROD_HTTP_PORT`;
- домен;
- SSL-сертифікат або сертифікат, у якому цей домен є SAN.

## 2. Локальний запуск

### Проєкт laradocker1

```bash
cd /home/{user}/projects
git clone https://github.com/amberlex78/laradocker.git laradocker1
cd /home/{user}/projects/laradocker1
cp .env.example .env
```

У `.env` змініть:

```dotenv
COMPOSE_PROJECT_NAME=laradocker1
APP_NAME=LaraDocker1
APP_URL=http://localhost:8081

DEV_HTTP_PORT=8081
VITE_FORWARD_PORT=5174
VITE_PORT=5173
VITE_HMR_HOST=localhost
VITE_HMR_PORT=5174
DB_FORWARD_PORT=3307
```

Запуск:

```bash
make install
make up
make migrate
curl -I http://127.0.0.1:8081/up
```

### Проєкт laradocker2

```bash
cd /home/{user}/projects
git clone https://github.com/amberlex78/laradocker.git laradocker2
cd /home/{user}/projects/laradocker2
cp .env.example .env
```

У `.env` змініть:

```dotenv
COMPOSE_PROJECT_NAME=laradocker2
APP_NAME=LaraDocker2
APP_URL=http://localhost:8082

DEV_HTTP_PORT=8082
VITE_FORWARD_PORT=5175
VITE_PORT=5173
VITE_HMR_HOST=localhost
VITE_HMR_PORT=5175
DB_FORWARD_PORT=3308
```

Запуск:

```bash
make install
make up
make migrate
curl -I http://127.0.0.1:8082/up
```

`VITE_PORT=5173` і `DB_PORT=3306` не змінюються: це внутрішні Docker-порти.
Змінюються `VITE_FORWARD_PORT`, `VITE_HMR_PORT` і `DB_FORWARD_PORT`.

## 3. Підготовка доменів, Cloudflare і сертифікатів

Для кожного домену створи DNS `A`-запис на IP-адресу VPS і ввімкни Proxy —
помаранчеву хмаринку:

```text
example1.com -> IP_VPS
www.example1.com -> IP_VPS
example2.com -> IP_VPS
www.example2.com -> IP_VPS
```

У Cloudflare встанови режим SSL/TLS:

```text
Full (strict)
```

Згенеруй окремі Cloudflare Origin Certificates для доменів або один сертифікат
із усіма потрібними доменами. На VPS встанови файли з правильними правами:

```bash
sudo install -d -m 700 /etc/nginx/certs/cloudflare

sudo install -m 644 ~/example1.com.origin.crt \
    /etc/nginx/certs/cloudflare/example1.com.origin.crt
sudo install -m 600 ~/example1.com.origin.key \
    /etc/nginx/certs/cloudflare/example1.com.origin.key

sudo install -m 644 ~/example2.com.origin.crt \
    /etc/nginx/certs/cloudflare/example2.com.origin.crt
sudo install -m 600 ~/example2.com.origin.key \
    /etc/nginx/certs/cloudflare/example2.com.origin.key
```

Перевір файли:

```bash
sudo ls -l /etc/nginx/certs/cloudflare
```

Після перевірки видали тимчасові копії сертифікатів і ключів із домашнього
каталогу сервера. Приватні ключі не додавай у Git.

## 4. Production-запуск на VPS

Для кожної копії використовуй окремий каталог, Compose project name, APP_KEY,
базу даних і `PROD_HTTP_PORT`.

### laradocker1 — example1.com

```bash
cd /home/{user}/projects
git clone https://github.com/amberlex78/laradocker.git laradocker1
cd /home/{user}/projects/laradocker1
cp .env.prod.example .env.prod
```

У `.env.prod`:

```dotenv
COMPOSE_PROJECT_NAME=laradocker1
IMAGE_TAG=prod1
APP_NAME=LaraDocker1
APP_ENV=production
APP_KEY=base64:УНІКАЛЬНИЙ_КЛЮЧ_ПЕРШОГО_ПРОЄКТУ
APP_DEBUG=false
APP_URL=https://example1.com
PROD_HTTP_PORT=18080

DB_DATABASE=laradocker1
DB_USERNAME=laradocker1
DB_PASSWORD=СИЛЬНИЙ_ПАРОЛЬ
DB_ROOT_PASSWORD=ІНШИЙ_СИЛЬНИЙ_ПАРОЛЬ
```

### laradocker2 — example2.com

```bash
cd /home/{user}/projects
git clone https://github.com/amberlex78/laradocker.git laradocker2
cd /home/{user}/projects/laradocker2
cp .env.prod.example .env.prod
```

У `.env.prod`:

```dotenv
COMPOSE_PROJECT_NAME=laradocker2
IMAGE_TAG=prod2
APP_NAME=LaraDocker2
APP_ENV=production
APP_KEY=base64:УНІКАЛЬНИЙ_КЛЮЧ_ДРУГОГО_ПРОЄКТУ
APP_DEBUG=false
APP_URL=https://example2.com
PROD_HTTP_PORT=28080

DB_DATABASE=laradocker2
DB_USERNAME=laradocker2
DB_PASSWORD=ІНШИЙ_СИЛЬНИЙ_ПАРОЛЬ
DB_ROOT_PASSWORD=ЩЕ_ІНШИЙ_СИЛЬНИЙ_ПАРОЛЬ
```

Для кожного production-проєкту генеруй власний ключ:

```bash
openssl rand -base64 32
```

До результату додай префікс `base64:`. Не використовуй один `APP_KEY` для
різних проєктів.

У кожному каталозі окремо:

```bash
make config-prod
make deploy
make prod-ps
make prod-optimize
```

Перевір Docker Nginx напряму з VPS:

```bash
curl -I http://127.0.0.1:18080/up
curl -I http://127.0.0.1:28080/up
```

`APP_URL` має бути публічним HTTPS-доменом. Не вказуй у ньому `18080` або
`28080`: це локальні host-порти для reverse proxy.

## 5. Системний Nginx як reverse proxy

Системний Nginx не має доступу до файлів Laravel і не запускає PHP-FPM. Він
приймає HTTP/HTTPS-запити, визначає сайт за доменом і проксіює запит до
відповідного Docker Nginx.

Для кожного домену використовуй окремий файл у
`/etc/nginx/sites-available/`. Не використовуй `default_server` у шаблонах
окремих проєктів: на одному порту може бути лише один default server.

### 5.1. Конфігурація example1.com

Створи файл:

```bash
sudo nano /etc/nginx/sites-available/example1.com
```

Встав:

```nginx
server {
    listen 80;
    listen [::]:80;

    server_name example1.com www.example1.com;

    return 301 https://example1.com$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;

    server_name example1.com www.example1.com;

    ssl_certificate /etc/nginx/certs/cloudflare/example1.com.origin.crt;
    ssl_certificate_key /etc/nginx/certs/cloudflare/example1.com.origin.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_prefer_server_ciphers off;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;

    charset utf-8;

    add_header X-Content-Type-Options nosniff always;
    add_header X-Frame-Options SAMEORIGIN always;
    add_header Referrer-Policy strict-origin-when-cross-origin always;

    proxy_http_version 1.1;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Host $host;
    proxy_set_header X-Forwarded-Proto $scheme;

    location = /favicon.ico {
        access_log off;
        log_not_found off;
        proxy_pass http://127.0.0.1:18080;
    }

    location = /robots.txt {
        access_log off;
        log_not_found off;
        proxy_pass http://127.0.0.1:18080;
    }

    location ~* \.(?:css|js|jpg|jpeg|gif|png|svg|ico|webp|woff|woff2|ttf)$ {
        proxy_pass http://127.0.0.1:18080;
        proxy_hide_header Cache-Control;
        proxy_hide_header Expires;
        expires 7d;
        add_header Cache-Control "public, immutable" always;
        add_header X-Content-Type-Options nosniff always;
        add_header X-Frame-Options SAMEORIGIN always;
        add_header Referrer-Policy strict-origin-when-cross-origin always;
    }

    location = /index.php {
        proxy_pass http://127.0.0.1:18080;
    }

    location / {
        proxy_pass http://127.0.0.1:18080;
    }

    location ~ \.php$ {
        return 404;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 5.2. Конфігурація example2.com

Створи файл:

```bash
sudo nano /etc/nginx/sites-available/example2.com
```

Використай такий самий конфіг, але з доменом `example2.com`, сертифікатом
`example2.com.origin.crt/key` і upstream-портом `28080`:

```nginx
server {
    listen 80;
    listen [::]:80;

    server_name example2.com www.example2.com;

    return 301 https://example2.com$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;

    server_name example2.com www.example2.com;

    ssl_certificate /etc/nginx/certs/cloudflare/example2.com.origin.crt;
    ssl_certificate_key /etc/nginx/certs/cloudflare/example2.com.origin.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_prefer_server_ciphers off;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;

    charset utf-8;

    add_header X-Content-Type-Options nosniff always;
    add_header X-Frame-Options SAMEORIGIN always;
    add_header Referrer-Policy strict-origin-when-cross-origin always;

    proxy_http_version 1.1;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Host $host;
    proxy_set_header X-Forwarded-Proto $scheme;

    location = /favicon.ico {
        access_log off;
        log_not_found off;
        proxy_pass http://127.0.0.1:28080;
    }

    location = /robots.txt {
        access_log off;
        log_not_found off;
        proxy_pass http://127.0.0.1:28080;
    }

    location ~* \.(?:css|js|jpg|jpeg|gif|png|svg|ico|webp|woff|woff2|ttf)$ {
        proxy_pass http://127.0.0.1:28080;
        proxy_hide_header Cache-Control;
        proxy_hide_header Expires;
        expires 7d;
        add_header Cache-Control "public, immutable" always;
        add_header X-Content-Type-Options nosniff always;
        add_header X-Frame-Options SAMEORIGIN always;
        add_header Referrer-Policy strict-origin-when-cross-origin always;
    }

    location = /index.php {
        proxy_pass http://127.0.0.1:28080;
    }

    location / {
        proxy_pass http://127.0.0.1:28080;
    }

    location ~ \.php$ {
        return 404;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 6. Активація Nginx і firewall

Увімкни обидва доменні конфіги:

```bash
sudo ln -s /etc/nginx/sites-available/example1.com \
    /etc/nginx/sites-enabled/example1.com

sudo ln -s /etc/nginx/sites-available/example2.com \
    /etc/nginx/sites-enabled/example2.com

sudo nginx -t
sudo systemctl reload nginx
```

Якщо увімкнений UFW, дозволь лише HTTP та HTTPS:

```bash
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw status numbered
```

Не додавай одночасно профіль `Nginx Full`, оскільки він дублює правила `80` і
`443`. Не відкривай у firewall `18080`, `28080`, `8081` або `8082` для
доменного production-доступу.

## 7. Перевірка

Перед DNS-перевіркою можна перевірити локальний HTTPS virtual host на VPS:

```bash
curl -k --resolve example1.com:443:127.0.0.1 https://example1.com/up
curl -k --resolve example2.com:443:127.0.0.1 https://example2.com/up
```

Очікуваний результат — `HTTP/2 200` або `HTTP/1.1 200` залежно від версії
curl/Nginx.

Після налаштування DNS і Cloudflare перевір публічні адреси:

```bash
curl -I https://example1.com/up
curl -I https://example2.com/up
```

Якщо сайт не працює, перевір послідовно:

```bash
sudo nginx -t
sudo ss -ltnp | grep -E ':(80|443|18080|28080)\b'
curl -I http://127.0.0.1:18080/up
curl -I http://127.0.0.1:28080/up
sudo nginx -T | grep -E 'example1.com|example2.com|18080|28080'
```

## 8. Видалення непотрібного проєкту

Якщо проєкт більше не потрібен, спочатку зупини його production-оточення в
його каталозі:

```bash
cd /home/{user}/projects/laradocker1
make prod-down
```

Для другого проєкту використовуй відповідно:

```bash
cd /home/{user}/projects/laradocker2
make prod-down
```

Після цього перевір, що конфігурація саме цього проєкту більше не потрібна
іншим сервісам:

```bash
sudo nginx -T | grep -E 'example1.com|18080|laradocker1'
```

Для `example2.com` перевір відповідно:

```bash
sudo nginx -T | grep -E 'example2.com|28080|laradocker2'
```

Якщо перевірка підтвердила, що проєкт більше не використовується, видали тільки
його symlink, конфігурацію та каталог. Для `example1.com`:

```bash
sudo rm /etc/nginx/sites-enabled/example1.com
sudo rm /etc/nginx/sites-available/example1.com

sudo nginx -t
sudo systemctl reload nginx

sudo rm -rf /home/{user}/projects/laradocker1
```

Для `example2.com`:

```bash
sudo rm /etc/nginx/sites-enabled/example2.com
sudo rm /etc/nginx/sites-available/example2.com

sudo nginx -t
sudo systemctl reload nginx

sudo rm -rf /home/{user}/projects/laradocker2
```

Перед видаленням каталогу зроби backup бази даних, якщо дані ще потрібні.
DNS-записи й сертифікати видаляй окремо після перевірки, що вони більше не
використовуються іншими доменами.

## 9. Логи та оновлення

У production-каталозі:

```bash
make prod-ps
make prod-logs
make prod-laravel-logs
```

Для оновлення:

```bash
git pull --ff-only
make deploy
make prod-optimize
```

Не видаляй `.env.prod` під час оновлення. Зберігай той самий `APP_KEY` і ті
самі credentials бази даних.
