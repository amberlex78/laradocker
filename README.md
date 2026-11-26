# Laravel Docker Starter

Шаблон Laravel-застосунку, у якому локальна розробка та production deployment
працюють через Docker Compose. Репозиторій можна взяти за основу нового
проєкту, запустити кілька копій одночасно або розгорнути на Ubuntu VPS.

Стек: Laravel 13, PHP-FPM 8.5, Nginx, MariaDB, Node.js/Vite і Composer.

Для адміністративної панелі проєкт використовує Filament.

## Вимоги

- Git;
- Docker Engine із Docker Compose plugin;
- GNU Make.

PHP, Composer, Node.js, Nginx і MariaDB на host встановлювати не потрібно.

## Швидкий старт

```bash
git clone git@github.com:amberlex78/laradocker.git
cd laradocker
```

Після клонування перейдіть у каталог проєкту та виконайте:

```bash
cp .env.example .env
make install
make up
make migrate
make db-seed
```

Після запуску доступні:

| Сервіс | Адреса |
| --- | --- |
| Laravel | <http://localhost:8000> |
| Health endpoint | <http://localhost:8000/up> |
| Vite HMR | <http://localhost:5173> |
| MariaDB для host-клієнта | `127.0.0.1:3306` |
| Mailpit web-інтерфейс | <http://localhost:8025> |
| Mailpit SMTP для host-клієнта | `127.0.0.1:1025` |
| Adminer | <http://localhost:8090> |

Compose складається зі спільного файла та окремих development/production
overlay. Контейнери використовують сталі внутрішні порти, а конфлікти між
копіями усуваються зміною host-портів. Для запуску кількох development-копій
дивіться [окрему інструкцію](docs/development.md#кілька-проєктів-одночасно), а
схема мереж описана в [Docker-архітектурі](docs/architecture.md).

## Основні команди

```bash
make                 # показати всі доступні targets

make up              # запустити development stack
make down            # зупинити його без видалення даних
make ps              # стан контейнерів
make logs            # логи development stack
make laravel-logs    # Laravel-логи development через Pail
make test            # запустити Laravel тести
make pint            # запустити Laravel Pint
make phpstan         # запустити PHPStan (статичний аналізатор коду)

make composer CMD="-V"
make artisan CMD="about"
make npm CMD="run build"
```

## Що ви хочете зробити?

| Завдання | Інструкція |
| --- | --- |
| Запустити один проєкт локально | [Локальна розробка](docs/development.md) |
| Запустити кілька проєктів локально | [Кілька проєктів одночасно](docs/development.md#кілька-проєктів-одночасно) |
| Перевірити production-образ локально | [Production-образ локально](docs/development.md#production-образ-локально) |
| Розгорнути на VPS без домену | [Доступ без домену](docs/deployment.md#доступ-без-домену) |
| Розгорнути з доменом і звичайним HTTPS | [Домен і звичайний HTTPS](docs/deployment.md#домен-і-звичайний-https) |
| Додати Cloudflare | [Необов'язкова інтеграція Cloudflare](docs/cloudflare.md) |
| Оновлювати, діагностувати, робити backup | [Production operations](docs/operations.md) |
| Зрозуміти сервіси, мережі та порти | [Docker-архітектура](docs/architecture.md) |

Звичайний HTTPS через Let's Encrypt є основним production-маршрутом.
Cloudflare описаний окремо як необов'язкова заміна DNS/TLS-рівня.

## Новий проєкт на базі цього репозиторію

### Створити проєкт

Клонуйте шаблон під назвою нового проєкту, створіть порожній репозиторій на
GitHub і налаштуйте адреси:

```bash
git clone git@github.com:amberlex78/laradocker.git new-project
cd new-project
git remote rename origin template
git remote add origin git@github.com:amberlex78/new-project.git
git push -u origin main
```

`origin` вказує на робочий репозиторій `new-project`, а `template` — на цей шаблон.

### Переглянути оновлення шаблону

Коли в шаблоні з’являться нові зміни, отримайте їх і перегляньте різницю:

```bash
git fetch template
git diff --stat HEAD..template/main
git diff HEAD..template/main
```

### Застосувати оновлення

Після перегляду злийте зміни в робочу гілку та надішліть їх у свій репозиторій:

```bash
git merge template/main
git push origin main
```

Якщо виникнуть конфлікти, розв’яжіть їх перед push.
