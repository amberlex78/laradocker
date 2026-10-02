# Дизайн реструктуризації документації

## Мета

Перетворити репозиторій на зрозумілий багаторазовий Laravel Docker starter,
який пояснює один раз кожне правило й допомагає вибрати потрібний маршрут:

- один або кілька локальних проєктів;
- production-подібний запуск локально;
- один або кілька production-проєктів на Ubuntu VPS;
- тимчасовий доступ до VPS без домену;
- звичайний HTTPS із доменом;
- необов'язкова інтеграція Cloudflare.

Уся користувацька документація буде українською. Назви команд, змінних,
файлів і технологій залишаться оригінальними.

## Поточний стан

README і п'ять основних файлів у `docs/` повторюють локальний запуск,
production-конфігурацію, Nginx, порти, оновлення та видалення. Окремі
інструкції для одного, двох і трьох проєктів відрізняються переважно
підставленими назвами й номерами портів.

Через дублювання вже з'явилися фактичні розбіжності:

- `docs/development.md` змінює внутрішній `VITE_PORT`, хоча він має залишатися
  `5173`;
- приклади кількох локальних проєктів не змінюють `ADMINER_FORWARD_PORT`;
- README, `.env.prod.example` і production Nginx-шаблон використовують різні
  upstream-порти без єдиного пояснення;
- документація приписує `make deploy` окрему host-side перевірку `/up`, якої в
  Makefile немає;
- deployment-інструкції дублюють `git pull`, хоча `make deploy` уже виконує
  `git pull --ff-only origin main`;
- Cloudflare-приклади не всюди відповідають безпечнішій схемі перевірки
  Cloudflare-мереж і `CF-Connecting-IP`;
- публічні інструкції містять персональні шляхи, домени й серверні значення;
- документація змішує українську та англійську мови.

## Принцип структури

Документація буде організована за відповідальністю, а не як набір повністю
самодостатніх сценаріїв. README допомагає вибрати шлях, а кожен факт має одне
канонічне місце.

```text
README.md
docs/
├── development.md
├── deployment.md
├── cloudflare.md
├── operations.md
└── architecture.md
```

Після перенесення унікального змісту видаляються:

- `docs/projects-no-domains.md`;
- `docs/projects-with-domains.md`.

## Відповідальність файлів

### `README.md`

Головна сторінка репозиторію містить:

- короткий опис starter-проєкту та стеку;
- вимоги до локального середовища;
- швидкий старт одного локального проєкту;
- стислий перелік основних Make-команд;
- таблицю «що ви хочете зробити?» з посиланнями на точні розділи в `docs/`.

README не дублює повні таблиці змінних, Nginx-конфігурації або серверні
процедури.

### `docs/development.md`

Єдине джерело для локальної роботи:

- створення нового проєкту на основі starter;
- перший запуск і щоденні команди;
- призначення локальних `.env`-змінних;
- запуск кількох проєктів;
- таблиця внутрішніх і зовнішніх портів;
- production-подібний локальний запуск;
- локальна діагностика.

### `docs/deployment.md`

Основний production-гайд для Ubuntu VPS:

1. Підготовка Docker, Compose, Make, системного Nginx і firewall.
2. Клонування проєкту та створення `.env.prod`.
3. Побудова і запуск production-стека.
4. Перевірка приватного upstream на `127.0.0.1:${PROD_HTTP_PORT}`.
5. Вибір публічного доступу:
   - тимчасовий HTTP-доступ без домену;
   - домен зі звичайним Let’s Encrypt HTTPS через Certbot;
   - кілька проєктів на одному VPS.

Гайд використовує параметризований приклад і таблиці значень замість окремих
інструкцій для `project1`, `project2` і `project3`.

### `docs/cloudflare.md`

Необов'язкова альтернатива стандартній TLS-частині deployment:

- DNS Proxy;
- режим `Full (strict)`;
- Cloudflare Origin Certificate;
- окремий Nginx-шаблон;
- перевірка Cloudflare source networks;
- нормалізація client IP через `CF-Connecting-IP`;
- точний перелік кроків базового deployment, які замінює Cloudflare.

Cloudflare не є передумовою звичайного production deployment.

### `docs/operations.md`

Єдине місце для повторюваних операцій:

- повторний deployment;
- статус, healthchecks і логи;
- міграції та optimization caches;
- backup і перевірка restore;
- діагностика;
- безпечне видалення одного проєкту;
- очищення Nginx, firewall, DNS і сертифікатів.

### `docs/architecture.md`

Пояснює модель без покрокових deployment-процедур:

- призначення трьох Compose-файлів;
- images і build stages;
- мережі `frontend` та `backend`;
- внутрішні порти і host-порти;
- bind mounts і persistent volumes;
- ізоляцію ресурсів через `COMPOSE_PROJECT_NAME`;
- шлях запиту від браузера до PHP-FPM.

## Модель портів і змінних

Документація чітко розділяє внутрішні Docker-порти та опубліковані host-порти.

Внутрішні значення не змінюються між проєктами:

| Значення | Призначення |
| --- | --- |
| Docker Nginx `8080` | HTTP усередині Compose |
| PHP-FPM `9000` | FastCGI усередині мережі `frontend` |
| MariaDB `3306` | База даних усередині мережі `backend` |
| Vite `5173` | Dev server усередині контейнера |
| `DB_HOST=mariadb` | Compose service name бази даних |
| `DB_PORT=3306` | Внутрішній порт бази даних |
| `VITE_PORT=5173` | Внутрішній порт Vite |

Для одночасної локальної роботи кожен проєкт має унікальні:

- `COMPOSE_PROJECT_NAME`;
- `DEV_HTTP_PORT`;
- `VITE_FORWARD_PORT`;
- `VITE_HMR_PORT`;
- `DB_FORWARD_PORT`;
- `ADMINER_FORWARD_PORT`.

Для кількох production-проєктів кожен має унікальні:

- `COMPOSE_PROJECT_NAME`;
- `PROD_HTTP_PORT`;
- `APP_KEY`;
- назву бази та database credentials;
- публічний домен або публічний no-domain порт.

`APP_URL` завжди містить публічну адресу користувача, а не приватний Docker
upstream. `IMAGE_TAG` залишається необов'язковим і не є механізмом ізоляції.

Єдина прикладна схема production upstream-портів починається з `18080`:

```text
project-a → 127.0.0.1:18080
project-b → 127.0.0.1:18081
```

Для доменів усі virtual hosts використовують спільні публічні `80/443`, а
системний Nginx вибирає upstream за `server_name`. Без доменів кожен проєкт
отримує окремий публічний порт системного Nginx. Приватні `PROD_HTTP_PORT` не
відкриваються у firewall.

## Nginx-шаблони

Цільовий набір шаблонів:

```text
docker/nginx/host/
├── dev.conf.example
├── prod-domain.conf.example
├── prod-no-domain.conf.example
└── prod-cloudflare.conf.example
```

- `prod-domain.conf.example` — основний provider-neutral domain template.
  Спочатку він працює через HTTP, після чого `certbot --nginx` додає Let’s
  Encrypt HTTPS і redirect.
- `prod-no-domain.conf.example` — тимчасовий HTTP-доступ через `VPS_IP:port`.
- `prod-cloudflare.conf.example` — Cloudflare Origin Certificate, перевірені
  Cloudflare-мережі та `CF-Connecting-IP`.
- `dev.conf.example` — необов'язковий локальний reverse proxy.

Поточний неоднозначний `prod.conf.example` замінюється явними production
варіантами. Усі production-шаблони проксіюють до одного узгодженого прикладу
`127.0.0.1:18080`, який користувач замінює відповідно до `.env.prod`.

## Env-шаблони

`.env.example` пояснює різницю між незмінними container ports і унікальними
host ports, включно з `ADMINER_FORWARD_PORT`.

`.env.prod.example`:

- не містить реального домену чи персональних назв;
- використовує нейтральні placeholder-значення;
- використовує `PROD_HTTP_PORT=18080`;
- пояснює, що `APP_URL` є публічною адресою;
- зберігає безпечне значення `TRUSTED_PROXIES=REMOTE_ADDR`.

## Перевірка та діагностика

Production-перевірка завжди рухається від внутрішнього шару до зовнішнього:

1. `make config-prod`.
2. `make prod-up` або `make deploy`.
3. `make prod-ps`.
4. `curl http://127.0.0.1:${PROD_HTTP_PORT}/up`.
5. `sudo nginx -t`.
6. Перевірка публічної HTTP/HTTPS-адреси.

Ця послідовність відокремлює проблеми Compose, application health, host Nginx,
DNS, TLS і Cloudflare.

`make deploy` документується відповідно до Makefile: команда вимагає
`.env.prod`, гілку `main` і чистий working tree, сама виконує
`git pull --ff-only origin main`, збирає та запускає стек, виконує міграції й
Laravel optimization. Вона використовує Compose healthchecks, але не виконує
окремий host-side `curl`.

## Безпекові межі

- No-domain доступ позначається як тимчасовий незашифрований демо-сценарій.
- Production Docker Nginx залишається прив'язаним до `127.0.0.1`.
- Для доменного deployment у firewall відкриваються лише `80/443`.
- Для no-domain deployment відкривається лише обраний порт системного Nginx.
- `APP_KEY` зберігається між deployment.
- `.env.prod`, TLS private keys і backup-файли не потрапляють у Git.
- `TRUSTED_PROXIES=*` не рекомендується.
- Cloudflare Origin Certificate не подається як публічно довірений сертифікат.
- Видалення проєкту починається з перевірки точних Compose/Nginx-цілей і
  резервної копії даних.

## Перевірка змін

Після реалізації потрібно:

- перевірити всі відносні Markdown-посилання;
- переконатися у відсутності посилань на видалені `projects-*.md`;
- знайти й прибрати персональні шляхи, домени та серверні імена;
- звірити приклади портів у README, env-файлах і Nginx-шаблонах;
- провалідувати development і production Compose configuration;
- підставити тестові значення в Nginx-шаблони й виконати `nginx -t`;
- перевірити згадки `VITE_PORT`, `PROD_HTTP_PORT`, `git pull`, Cloudflare і
  Certbot на внутрішню узгодженість;
- виконати `graphify update .`.

Laravel feature tests не є головною перевіркою, оскільки application logic не
змінюється.

## Поза межами змін

- Зміна application logic або Laravel-функціональності.
- Автоматичне керування DNS, `/etc/nginx`, UFW або сертифікатами з Makefile.
- Підтримка VPS-дистрибутивів, відмінних від Ubuntu.
- Локальні custom domains як стандартний dev-сценарій.
- Зміна Git-політики `make deploy`.
- Перетворення Adminer на optional Compose profile.

## Ризики та компроміси

Користувач іноді переходитиме між deployment і operations замість читання
одного великого runbook. Це компенсується явними «наступний крок» посиланнями
та таблицею маршрутів у README.

Certbot змінює активну копію Nginx-конфігурації на VPS, тому repository
template залишається HTTP bootstrap-конфігурацією. Документація має чітко
розділити стан до й після `certbot --nginx`.

Cloudflare source networks змінюються незалежно від репозиторію. Cloudflare
гайд має посилатися на офіційний список і пояснювати потребу періодичного
оновлення allowlist у Nginx-шаблоні.
