# Laravel-проєкти без домену

Коротка інструкція для запуску копій репозиторію
https://github.com/amberlex78/ladocker:

- локально: /home/lex/Projects/;
- на VPS: /home/esp32/sites/;
- без доменів і HTTPS;
- production-подібний Docker-запуск для тестування та демо.

Передбачається, що Docker, Docker Compose, GNU Make і системний Nginx уже
встановлені.

## 1. Схема портів

| Проєкт | Каталог | Адреса | Docker/VPS host port |
| --- | --- | --- | --- |
| ladocker1 | /home/lex/Projects/ladocker1 | http://localhost:8081 | DEV_HTTP_PORT=8081 |
| ladocker2 | /home/lex/Projects/ladocker2 | http://localhost:8082 | DEV_HTTP_PORT=8082 |
| ladockervps1 | /home/esp32/sites/ladockervps1 | http://IP_VPS:8081 | PROD_HTTP_PORT=18080 |
| ladockervps2 | /home/esp32/sites/ladockervps2 | http://IP_VPS:8082 | PROD_HTTP_PORT=28080 |

На VPS трафік проходить так:

```text
IP_VPS:8081 -> VPS Nginx:8081 -> 127.0.0.1:18080 -> Docker Nginx
IP_VPS:8082 -> VPS Nginx:8082 -> 127.0.0.1:28080 -> Docker Nginx
```

Порти 18080 і 28080 не відкриваються назовні. Відкриваються тільки 8081 і
8082. Внутрішні Docker-порти 8080, 5173 і 3306 не змінюються.

## 2. Локальний запуск

### Проєкт ladocker1

```bash
cd /home/lex/Projects
git clone https://github.com/amberlex78/ladocker.git ladocker1
cd /home/lex/Projects/ladocker1
cp .env.example .env
```

У .env змініть:

```dotenv
COMPOSE_PROJECT_NAME=ladocker1
APP_NAME=LaDocker1
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

### Проєкт ladocker2

```bash
cd /home/lex/Projects
git clone https://github.com/amberlex78/ladocker.git ladocker2
cd /home/lex/Projects/ladocker2
cp .env.example .env
```

У .env змініть:

```dotenv
COMPOSE_PROJECT_NAME=ladocker2
APP_NAME=LaDocker2
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

VITE_PORT=5173 і DB_PORT=3306 не змінюються: це внутрішні Docker-порти.
Змінюються VITE_FORWARD_PORT, VITE_HMR_PORT і DB_FORWARD_PORT.

## 3. Production-запуск на VPS

Для кожної копії використовуйте окремий каталог, Compose project name,
APP_KEY, базу даних і PROD_HTTP_PORT.

### ladockervps1

```bash
cd /home/esp32/sites
git clone https://github.com/amberlex78/ladocker.git ladockervps1
cd /home/esp32/sites/ladockervps1
cp .env.prod.example .env.prod
```

У .env.prod:

```dotenv
COMPOSE_PROJECT_NAME=ladockervps1
IMAGE_TAG=prod1
APP_NAME=LaDockerVPS1
APP_ENV=production
APP_KEY=base64:УНІКАЛЬНИЙ_КЛЮЧ
APP_DEBUG=false
APP_URL=http://IP_VPS:8081
PROD_HTTP_PORT=18080

DB_DATABASE=ladockervps1
DB_USERNAME=ladockervps1
DB_PASSWORD=СИЛЬНИЙ_ПАРОЛЬ
DB_ROOT_PASSWORD=ІНШИЙ_СИЛЬНИЙ_ПАРОЛЬ
```

Значення DB_HOST=mariadb і DB_PORT=3306 залишаються без змін.
APP_URL — це адреса для браузера, а не внутрішній порт 18080.

### ladockervps2

```bash
cd /home/esp32/sites
git clone https://github.com/amberlex78/ladocker.git ladockervps2
cd /home/esp32/sites/ladockervps2
cp .env.prod.example .env.prod
```

У .env.prod:

```dotenv
COMPOSE_PROJECT_NAME=ladockervps2
IMAGE_TAG=prod2
APP_NAME=LaDockerVPS2
APP_ENV=production
APP_KEY=base64:ІНШИЙ_УНІКАЛЬНИЙ_КЛЮЧ
APP_DEBUG=false
APP_URL=http://IP_VPS:8082
PROD_HTTP_PORT=28080

DB_DATABASE=ladockervps2
DB_USERNAME=ladockervps2
DB_PASSWORD=СИЛЬНИЙ_ПАРОЛЬ
DB_ROOT_PASSWORD=ІНШИЙ_СИЛЬНИЙ_ПАРОЛЬ
```

Для кожного production-проєкту генеруйте власний ключ:

```bash
openssl rand -base64 32
```

До результату додайте префікс base64:. Не використовуйте один APP_KEY для
різних проєктів.

### Запуск обох production-проєктів

У кожному каталозі окремо:

```bash
make config-prod
make deploy
make prod-ps
```

Перевірка напряму до Docker:

```bash
curl -I http://127.0.0.1:18080/up
curl -I http://127.0.0.1:28080/up
```

## 4. Мінімальний reverse proxy Nginx

Це конфігурація саме для демо без домену. Системний Nginx не має доступу до
файлів Laravel і не запускає PHP-FPM: він лише проксіює до Docker Nginx.

Конфігурація використовує базові security headers і блокує приховані файли.
Кешування static assets, robots.txt і SSL тут навмисно не налаштовуються.

Laravel також рекомендує не віддавати застосунок із кореня проєкту та
блокувати приховані файли. Офіційна конфігурація Laravel для прямого Nginx
обслуговування відрізняється від цієї proxy-схеми:
https://laravel.com/framework/docs/13.x/deployment#server-configuration

### 4.1. Конфігурація ladockervps1

Створіть файл, не каталог:

```bash
sudo nano /etc/nginx/sites-available/ladockervps1
```

Вставте:

```nginx
server {
    listen 8081;
    listen [::]:8081;

    server_name _;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    proxy_http_version 1.1;
    proxy_set_header Host $http_host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Host $http_host;
    proxy_set_header X-Forwarded-Proto $scheme;

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

### 4.2. Конфігурація ladockervps2

Створіть файл:

```bash
sudo nano /etc/nginx/sites-available/ladockervps2
```

Скопіюйте попередній конфіг і замініть у ньому:

```nginx
listen 8082;
listen [::]:8082;
proxy_pass http://127.0.0.1:28080;
```

Заміна proxy_pass потрібна в обох location-блоках.

### 4.3. Активація sites-enabled

```bash
sudo ln -s /etc/nginx/sites-available/ladockervps1 \
    /etc/nginx/sites-enabled/ladockervps1

sudo ln -s /etc/nginx/sites-available/ladockervps2 \
    /etc/nginx/sites-enabled/ladockervps2

sudo nginx -t
sudo systemctl reload nginx
```

У браузері:

- http://IP_VPS:8081 — ladockervps1;
- http://IP_VPS:8082 — ladockervps2.

Відкрийте порти у firewall:

```bash
sudo ufw allow 8081/tcp
sudo ufw allow 8082/tcp
sudo ufw status numbered
```

Порти 18080 і 28080 у firewall відкривати не потрібно.

## 5. Видалення непотрібного проєкту

Якщо проєкт більше не потрібен, спочатку зупиніть його production-оточення
в його каталозі:

```bash
make prod-down
```

Після цього можна видалити каталог проєкту, наприклад:

```bash
sudo rm -rf /home/esp32/sites/ladockervps1
sudo rm -rf /home/esp32/sites/ladockervps2
```

`make prod-down` зупиняє тільки Docker Compose-проєкт. Він не видаляє системний
Nginx і не змінює файли в `/etc/nginx`.

Конфігурації та symlink-и видаляються окремо. Перед цим перевірте, що вони
належать саме проєктам, які видаляються, і не використовуються іншими сервісами:

```bash
sudo nginx -T | grep -E '8081|8082|18080|28080|ladockervps1|ladockervps2'
```

Якщо перевірка підтвердила, що порти більше не потрібні, видаліть symlink-и та
конфігурації:

```bash
sudo rm /etc/nginx/sites-enabled/ladockervps1
sudo rm /etc/nginx/sites-enabled/ladockervps2

sudo rm /etc/nginx/sites-available/ladockervps1
sudo rm /etc/nginx/sites-available/ladockervps2

sudo nginx -t
sudo systemctl reload nginx
```

Не видаляйте весь каталог `/etc/nginx/sites-enabled` — видаляйте тільки файли
та symlink-и конкретних непотрібних проєктів. Якщо порти 8081 і 8082 були
відкриті у UFW і більше не використовуються, закрийте їх:

```bash
sudo ufw delete allow 8081/tcp
sudo ufw delete allow 8082/tcp
```

Залишені конфігурації Nginx зазвичай не впливають на інші проєкти, але будуть
продовжувати займати порти 8081 і 8082 та повертати `502 Bad Gateway`, якщо
відповідні Docker-контейнери вже зупинені. Тому для повного очищення краще
видаляти їх разом із проєктом.

## 6. Логи та оновлення

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
```

Не видаляйте .env.prod під час оновлення.

## 7. Третій проєкт

Для наступного проєкту збільшуйте порти за схемою:

| Значення | Проєкт 3 |
| --- | --- |
| Локальний каталог | /home/lex/Projects/ladocker3 |
| VPS каталог | /home/esp32/sites/ladockervps3 |
| Compose names | ladocker3 / ladockervps3 |
| Локальна адреса | http://localhost:8083 |
| VPS адреса | http://IP_VPS:8083 |
| DEV_HTTP_PORT | 8083 |
| PROD_HTTP_PORT | 38080 |
| VPS Nginx listen | 8083 |
| Vite host port | 5176 |
| MariaDB host port | 3309 |

Для VPS Nginx третього проєкту:

```nginx
listen 8083;
listen [::]:8083;
proxy_pass http://127.0.0.1:38080;
```

Не забудьте створити symlink, перевірити nginx -t і відкрити 8083 у firewall.
