# Docker-архітектура

Кожен Laravel-проєкт працює як окремий Docker Compose project. Значення
`COMPOSE_PROJECT_NAME` ізолює його контейнери, образи, мережі та named volumes
від інших копій цього starter.

## Compose-файли

Конфігурацію розділено за середовищами:

- `docker-compose.yml` описує спільні сервіси, образи, healthchecks, мережі та
  volume бази даних;
- `docker-compose.dev.yml` додає bind mounts, Vite, Adminer і локальні
  host-порти;
- `docker-compose.prod.yml` перемикає application image на production stage,
  вимикає bind mounts і публікує лише Docker Nginx на loopback-адресі VPS.

Makefile завжди об'єднує спільний файл із потрібним overlay, тому dev- і
production-команди не повинні використовувати один Compose stack.

## Сервіси та образи

```text
браузер або системний Nginx
              |
              v
       Docker Nginx:8080
              |
              v
          app:9000
              |
              v
        mariadb:3306
```

- `app` — PHP-FPM із кодом Laravel. `docker/php/Dockerfile` має окремі `dev` і
  `prod` stages. PHP-FPM слухає порт `9000` лише всередині Docker-мережі.
- `nginx` — віддає `public/` і передає PHP-запити до `app:9000`. Усередині
  контейнера завжди слухає `8080`.
- `mariadb` — зберігає дані в named volume `mariadb-data` і всередині Compose
  доступна як `mariadb:3306`.
- `node` — існує лише в development і запускає Vite на внутрішньому порту
  `5173`.
- `composer` — tools profile для встановлення PHP-залежностей у development.
- `adminer` — локальний web-інтерфейс до MariaDB; у production не запускається.

Production Nginx image збирає frontend у Node stage і копіює готовий каталог
`public/` у runtime image. Production PHP image встановлює Composer-залежності
під час build. Тому на VPS не потрібні host-каталоги `node_modules/` і
`vendor/`.

## Мережі

Compose створює окремі мережі для кожного `COMPOSE_PROJECT_NAME`:

- `frontend` з'єднує Docker Nginx із PHP-FPM;
- `backend` з'єднує PHP-FPM із MariaDB і має `internal: true`, тому база даних
  не використовує цю мережу для зовнішнього доступу;
- `adminer` існує лише в development і дає Adminer доступ до MariaDB без
  підключення бази до `frontend`.

## Внутрішні та host-порти

Внутрішні адреси однакові в кожному Compose project:

| Сервіс | Внутрішня адреса | Змінювати для іншого проєкту |
| --- | --- | --- |
| Docker Nginx | `nginx:8080` | Ні |
| PHP-FPM | `app:9000` | Ні |
| MariaDB | `mariadb:3306` | Ні |
| Vite | `node:5173` | Ні |

Host-порти потрібні лише для входу з операційної системи. У development це:

- `DEV_HTTP_PORT` для застосунку;
- `VITE_FORWARD_PORT` і `VITE_HMR_PORT` для Vite/HMR;
- `DB_FORWARD_PORT` для host-клієнтів MariaDB;
- `ADMINER_FORWARD_PORT` для Adminer.

У production змінна `PROD_HTTP_PORT` публікує Docker Nginx тільки на
`127.0.0.1`, наприклад `127.0.0.1:18080`. Цей порт є приватним upstream для
системного Nginx, а не публічною адресою застосунку.

## Дані та файлові системи

У development checkout bind-mounted у `app`, `nginx` і `node`. `vendor/` та
`node_modules/` залишаються видимими host-редактору, а сервіси працюють із
UID/GID поточного користувача, щоб не створювати root-owned файли.

У production source code та зібрані dependencies входять до immutable images.
Bind mount checkout не використовується. Дані MariaDB переживають перезапуск
і `prod-down`, оскільки зберігаються в `mariadb-data`.

Якщо застосунок зберігатиме user uploads на локальному диску, для
`storage/app` потрібен окремий persistent volume або object storage. Поточний
starter такого volume не створює.

## Шляхи HTTP-запиту

Локальна розробка:

```text
http://localhost:${DEV_HTTP_PORT}
    -> 127.0.0.1:${DEV_HTTP_PORT}
    -> Docker Nginx:8080
    -> app:9000
```

VPS із доменом:

```text
https://example.com:443
    -> системний Nginx
    -> 127.0.0.1:${PROD_HTTP_PORT}
    -> Docker Nginx:8080
    -> app:9000
```

VPS без домену використовує той самий приватний upstream, але системний Nginx
слухає окремий публічний HTTP-порт. Це тимчасовий незашифрований маршрут для
демо або тестування.

Практичні кроки дивіться в [локальній розробці](development.md) та
[розгортанні на Ubuntu VPS](deployment.md).
