# Експлуатація production-середовища

Цей гайд застосовується після успішного
[deployment на Ubuntu VPS](deployment.md). Усі Make-команди запускайте з
каталогу конкретного проєкту, де зберігається його `.env.prod`.

## Статус і логи

Перевірити контейнери та health state:

```bash
make prod-ps
```

Дивитися всі production logs:

```bash
make prod-logs
```

Дивитися лише Laravel log:

```bash
make prod-laravel-logs
```

Поточне використання CPU та пам'яті:

```bash
make prod-stats
```

## Оновлення застосунку

Стандартне оновлення:

```bash
make deploy
```

Не запускайте перед цим окремий `git pull`.
Команда `make deploy` сама перевіряє гілку `main`, вимагає чистий working tree і виконує
`git pull --ff-only origin main`.

Після pull команда перевіряє Compose, перебудовує images, запускає stack із
healthchecks, виконує migrations і Laravel optimization. Вона не змінює
`.env.prod`, `APP_KEY` або database credentials.

Якщо потрібно керувати етапами вручну:

```bash
make prod-build
make prod-up
make prod-migrate
make prod-optimize
```

Повторно застосувати `.env.prod` без повного build:

```bash
make prod-up
make prod-optimize
```

Зупинити stack без видалення database volume:

```bash
make prod-down
```

`prod-down` зупиняє лише Compose project і зберігає `mariadb-data`.

## Міграції

Запустити pending production migrations:

```bash
make prod-migrate
```

Кешувати configuration, routes і views:

```bash
make prod-optimize
```

Не використовуйте `prod-migrate-fresh` як звичайну операційну команду: вона
видаляє всі таблиці та дані. Відновлення production виконується з перевіреної
резервної копії, а не через fresh migration.

## Резервна копія MariaDB

Створіть захищений каталог поза Git checkout:

```bash
mkdir -p ~/backups/project-a
chmod 700 ~/backups/project-a
```

Перевірте checkout, безпечні значення з `.env.prod`, фактичні імена
контейнерів і host binding. `grep` навмисно не виводить passwords:

```bash
pwd
grep -E '^(COMPOSE_PROJECT_NAME|PROD_HTTP_PORT|DB_DATABASE)=' .env.prod
make config-prod
docker compose --env-file .env.prod \
    -f docker-compose.yml \
    -f docker-compose.prod.yml \
    ps
docker compose --env-file .env.prod \
    -f docker-compose.yml \
    -f docker-compose.prod.yml \
    exec -T mariadb \
    sh -lc 'printf "Database: %s\n" "$MARIADB_DATABASE"'
```

У `ps` ім'я контейнера має починатися з потрібного `COMPOSE_PROJECT_NAME`, а
Nginx має публікувати саме `127.0.0.1:PROD_HTTP_PORT->8080/tcp`. Якщо stack ще
не запущений, перевірте ці значення до запуску й повторіть `ps` після нього.

Створіть logical dump без публікації MariaDB на host:

```bash
docker compose --env-file .env.prod \
    -f docker-compose.yml \
    -f docker-compose.prod.yml \
    exec -T mariadb \
    sh -lc 'mariadb-dump --single-transaction -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' \
    > ~/backups/project-a/database.sql
chmod 600 ~/backups/project-a/database.sql
```

Перевірте, що файл існує, не порожній і починається як SQL dump:

```bash
test -s ~/backups/project-a/database.sql
sed -n '1,10p' ~/backups/project-a/database.sql
```

Зберігайте копію за межами VPS. Backup, який ніколи не перевіряли
відновленням, не можна вважати надійним.

## Відновлення MariaDB

Відновлення перезаписує стан цільової бази. Перед виконанням:

1. Зробіть актуальний backup поточної бази.
2. Перевірте каталог проєкту, `.env.prod`, `COMPOSE_PROJECT_NAME` і
   `MARIADB_DATABASE`.
3. Спочатку відновіть dump у staging або тимчасовий окремий Compose project.
4. Перевірте migrations, ключові записи та запуск застосунку.

Перед restore повторіть read-only перевірки `pwd`, `grep`, `make config-prod`,
`docker compose ... ps` і назви бази з розділу про резервну копію. Не
продовжуйте, якщо project name, database або binding не збігаються з ціллю.

Після явної перевірки цілі:

```bash
docker compose --env-file .env.prod \
    -f docker-compose.yml \
    -f docker-compose.prod.yml \
    exec -T mariadb \
    sh -lc 'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' \
    < ~/backups/project-a/database.sql
```

Після restore:

```bash
make prod-migrate
make prod-optimize
make prod-ps
```

Перевірте health endpoint і функції, дані яких були відновлені.

## Діагностика

Перевіряйте шари по черзі й не переходьте назовні, доки внутрішній шар не
працює.

### 1. Конфігурація Docker Compose

```bash
make config-prod
```

Помилка тут означає проблему в `.env.prod` або Compose interpolation.

### 2. Контейнери та healthchecks

```bash
make prod-ps
make prod-logs
```

Якщо MariaDB unhealthy, спочатку перевірте її logs. Якщо `app` або `nginx`
unhealthy, перевірте відповідний сервіс і Laravel log.

### 3. Laravel

```bash
make prod-laravel-logs
```

Перевірте migrations і cached configuration:

```bash
make prod-migrate
make prod-optimize
```

### 4. Приватний upstream

Використовуйте значення `PROD_HTTP_PORT` із `.env.prod`:

```bash
curl -I http://127.0.0.1:18080/up
```

Якщо цей endpoint не працює, проблема ще не в system Nginx, DNS або TLS.

### 5. Системний Nginx

```bash
sudo nginx -t
sudo ss -ltnp
sudo journalctl -u nginx --since '15 minutes ago'
```

Перевірте, що `proxy_pass` збігається з `PROD_HTTP_PORT` потрібного проєкту.

### 6. Firewall, DNS і TLS

```bash
sudo ufw status numbered
```

Для домену перевірте DNS records, certificate hostnames і renewal. Для
Cloudflare додатково перевірте Proxy status, `Full (strict)`, source-network
allowlist і `CF-Connecting-IP`. Для no-domain route перевірте лише вибраний
публічний listener; приватний production upstream не відкривайте.

## Видалення одного проєкту

Видалення має стосуватися лише одного точно визначеного Compose project і
Nginx site.

1. Створіть і перевірте backup.
2. Перейдіть у checkout та перевірте шлях через `pwd`.
3. Повторіть read-only перевірки `pwd`, `grep`, `make config-prod`,
   `docker compose ... ps` і назви бази з розділу про резервну копію.
   Переконайтеся, що container names, database і
   `127.0.0.1:PROD_HTTP_PORT->8080/tcp` належать саме цьому проєкту.
4. Знайдіть посилання на domain, public port і upstream:

```bash
sudo nginx -T | grep -E 'example.com|8081|18080|project-a'
```

5. Зупиніть лише цей stack:

```bash
make prod-down
```

6. Видаліть лише symlink і site-файл цього проєкту, потім перевірте Nginx:

```bash
sudo unlink /etc/nginx/sites-enabled/example.com
sudo nginx -t
sudo systemctl reload nginx
```

7. За потреби окремо приберіть відповідне UFW rule, DNS records і certificate
   files після перевірки, що їх не використовує інший hostname.
8. Лише після цього видаліть перевірений checkout-каталог конкретного проєкту.

`make prod-down` не видаляє named volumes. Видалення database volume є
окремою незворотною операцією; виконуйте її лише після backup і точного
визначення Docker volume, який належить цьому `COMPOSE_PROJECT_NAME`.

Повернутися до [VPS deployment](deployment.md) або переглянути
[Docker-архітектуру](architecture.md).
