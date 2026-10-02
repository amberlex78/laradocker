# Розгортання на Ubuntu VPS

Цей гайд описує один production-процес для одного або кількох Laravel-проєктів.
Docker stack завжди залишається приватним на `127.0.0.1`, а публічний трафік
приймає системний Nginx.

Доступ можна налаштувати двома способами:

- домен зі звичайним Let’s Encrypt HTTPS — рекомендований production-варіант;
- IP-адреса й окремий HTTP-порт — тимчасово для демо або тестування.

Cloudflare не потрібен для базового deployment. Якщо він потрібен, завершіть
спільні кроки й перейдіть до [окремого Cloudflare-гайда](cloudflare.md).

## Вимоги

- Ubuntu VPS із користувачем, який має `sudo`;
- Docker Engine із Compose plugin;
- Git і GNU Make;
- відкритий SSH-доступ;
- для HTTPS — домен, DNS якого можна спрямувати на VPS.

Перевірте Docker:

```bash
docker --version
docker compose version
```

Встановіть спільні host-пакети:

```bash
sudo apt update
sudo apt install -y git make nginx ufw
sudo systemctl enable --now nginx
sudo nginx -t
```

Перед увімкненням UFW спочатку дозвольте фактичний SSH-порт. `OpenSSH`
відповідає стандартному port `22`; якщо `sshd` слухає інший port, замініть
правило на `sudo ufw allow SSH_PORT/tcp` і не закривайте поточну SSH-сесію,
доки не перевірите нове підключення в окремому terminal:

```bash
sudo ufw allow OpenSSH
sudo ufw enable
sudo ufw status verbose
```

Firewall у панелі VPS provider або security group також має дозволяти SSH і
лише ті web-порти, які ви оберете нижче. Не відкривайте `PROD_HTTP_PORT`.

Не встановлюйте PHP, Composer, Node.js або MariaDB безпосередньо на VPS: вони
входять до Docker images.

## Клонування та production-конфігурація

```bash
git clone https://github.com/amberlex78/laradocker.git my-project
cd my-project
cp .env.prod.example .env.prod
```

Створіть унікальний Laravel application key:

```bash
openssl rand -base64 32
```

Додайте до результату префікс `base64:` і запишіть значення в
`APP_KEY`. Мінімальна конфігурація першого проєкту:

```dotenv
COMPOSE_PROJECT_NAME=project-a
IMAGE_TAG=prod
APP_NAME=ProjectA
APP_ENV=production
APP_KEY=base64:UNIQUE_GENERATED_KEY
APP_DEBUG=false
APP_URL=https://example.com
TRUSTED_PROXIES=REMOTE_ADDR

PROD_HTTP_PORT=18080

DB_CONNECTION=mariadb
DB_HOST=mariadb
DB_PORT=3306
DB_DATABASE=project_a
DB_USERNAME=project_a
DB_PASSWORD=REPLACE_WITH_STRONG_PASSWORD
DB_ROOT_PASSWORD=REPLACE_WITH_DIFFERENT_STRONG_PASSWORD
```

Для тимчасового доступу без домену замість HTTPS URL використовуйте:

```dotenv
APP_URL=http://VPS_IP:8081
```

`APP_URL` — адреса, яку відкриває користувач. `PROD_HTTP_PORT=18080` —
окремий приватний порт між системним і Docker Nginx; його не додають до
`APP_URL` і не відкривають у firewall.

Кожен production-проєкт повинен мати власні `COMPOSE_PROJECT_NAME`,
`PROD_HTTP_PORT`, `APP_KEY`, database name, user і passwords.

## Перша побудова та запуск

Перевірте Compose interpolation:

```bash
make config-prod
```

Стандартний deployment:

```bash
make deploy
```

`make deploy` навмисно вимагає:

- поточну гілку `main`;
- чистий Git working tree;
- доступний remote `origin/main`;
- наявний файл `.env.prod`.

Команда сама виконує `git pull --ff-only origin main`, перевіряє Compose,
збирає images, запускає сервіси з очікуванням healthchecks, виконує production
migrations і кешує Laravel configuration, routes та views. Не запускайте
окремий pull перед нею.

Якщо ця Git-політика не підходить, використовуйте нижчі targets явно:

```bash
make prod-build
make prod-up
make prod-migrate
make prod-optimize
```

## Перевірка приватного Docker stack

Спочатку перевірте стан без DNS, TLS і зовнішнього Nginx:

```bash
make prod-ps
curl -I http://127.0.0.1:18080/up
```

Очікується успішний HTTP response, а `app`, `mariadb` і `nginx` мають
бути healthy. Якщо приватний endpoint не працює, зовнішній reverse proxy ще не
налаштовуйте — дивіться [діагностику](operations.md#діагностика).

## Вибір публічного доступу

| Варіант | Публічні порти | TLS | Рекомендація |
| --- | --- | --- | --- |
| Домен | `80/443` | Let’s Encrypt | Production |
| Без домену | окремий `8081+` | Немає | Тимчасове демо |

В обох варіантах Docker upstream `127.0.0.1:18080` залишається приватним.

## Доступ без домену

> Цей маршрут передає credentials і session cookies через незашифрований HTTP.
> Не використовуйте його як постійний production-доступ.

Скопіюйте готовий шаблон:

```bash
sudo cp docker/nginx/host/prod-no-domain.conf.example \
    /etc/nginx/sites-available/project-a-no-domain
sudo ln -s /etc/nginx/sites-available/project-a-no-domain \
    /etc/nginx/sites-enabled/project-a-no-domain
```

За замовчуванням
[prod-no-domain.conf.example](../docker/nginx/host/prod-no-domain.conf.example)
слухає публічний порт `8081` і проксіює до `127.0.0.1:18080`. Для іншого
проєкту змініть обидва значення.

Перевірте й активуйте конфігурацію:

```bash
sudo nginx -t
sudo systemctl reload nginx
sudo ufw allow 8081/tcp
```

Не відкривайте `18080/tcp`. Перевірка має йти від приватного шару до
публічного:

```bash
curl -I http://127.0.0.1:18080/up
sudo nginx -t
curl -I http://VPS_IP:8081/up
```

## Домен і звичайний HTTPS

Створіть DNS `A`-записи для `example.com` і, за потреби,
`www.example.com`, які напряму вказують на IPv4-адресу VPS. Для IPv6 додайте
`AAAA` лише якщо сервер справді доступний через IPv6.

У `.env.prod` встановіть:

```dotenv
APP_URL=https://example.com
```

Скопіюйте HTTP bootstrap:

```bash
sudo cp docker/nginx/host/prod-domain.conf.example \
    /etc/nginx/sites-available/example.com
sudo nano /etc/nginx/sites-available/example.com
```

У [prod-domain.conf.example](../docker/nginx/host/prod-domain.conf.example)
замініть `example.com`, `www.example.com` і upstream-порт, якщо він
відрізняється від `18080`.

Активуйте сайт. Якщо стандартний Ubuntu site більше не потрібен, видаліть лише
його symlink:

```bash
sudo unlink /etc/nginx/sites-enabled/default
sudo ln -s /etc/nginx/sites-available/example.com \
    /etc/nginx/sites-enabled/example.com
sudo nginx -t
sudo systemctl reload nginx
```

Відкрийте тільки стандартні web-порти:

```bash
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw status numbered
```

Перед TLS перевірте HTTP virtual host:

```bash
curl -I http://example.com/up
```

Встановіть Certbot і Nginx plugin:

```bash
sudo apt install -y certbot python3-certbot-nginx
```

Отримайте сертифікат і дозвольте Certbot додати HTTPS та redirect:

```bash
sudo certbot --nginx -d example.com -d www.example.com
```

Якщо `www.example.com` не використовується, не додавайте його до команди й
`server_name`. Перевірте автоматичне renewal:

```bash
sudo certbot renew --dry-run
sudo nginx -t
curl -I https://example.com/up
```

Certbot змінює активний файл у `/etc/nginx/sites-available/`, а repository
template залишається повторно використовуваним HTTP bootstrap.

## Кілька проєктів на одному VPS

Приклад розподілу:

| Значення | Project A | Project B |
| --- | --- | --- |
| Каталог | `~/sites/project-a` | `~/sites/project-b` |
| `COMPOSE_PROJECT_NAME` | `project-a` | `project-b` |
| `PROD_HTTP_PORT` | `18080` | `18081` |
| `APP_KEY` | унікальний A | унікальний B |
| Database/user/passwords | окремі A | окремі B |
| Domain URL | `https://example-a.com` | `https://example-b.com` |
| No-domain URL | `http://VPS_IP:8081` | `http://VPS_IP:8082` |

Для доменів усі system Nginx virtual hosts спільно слухають `80/443` і
вибираються за `server_name`. Їхні `proxy_pass` ведуть на різні приватні
upstreams `18080` і `18081`.

Без доменів кожному проєкту потрібен окремий публічний listener, наприклад
`8081` і `8082`. У firewall відкривають лише ці listeners, а не production
upstreams.

Для третього проєкту не потрібна нова інструкція: виберіть нові
`COMPOSE_PROJECT_NAME`, `PROD_HTTP_PORT`, credentials і public identity та
повторіть той самий параметризований процес.

## Наступні кроки

- Оновлення, логи, backup, діагностика й видалення:
  [production operations](operations.md).
- Cloudflare замість стандартного DNS/TLS:
  [необов'язкова інтеграція Cloudflare](cloudflare.md).
- Внутрішні мережі та порти:
  [Docker-архітектура](architecture.md).
