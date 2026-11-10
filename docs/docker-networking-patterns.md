# Docker networking: звичайна та ізольована схема

Цей документ пояснює два поширені підходи до мереж у Docker Compose:

1. проста схема з однією `default` network;
2. розділена production-схема з окремими frontend і backend мережами.

Обидві схеми валідні. Перша простіша для локальної розробки. Друга краще
контролює, які сервіси можуть бачити базу даних і які порти доступні з host.

## Як працюють імена та порти в Compose

Контейнери в одній Compose network знаходять один одного за іменем сервісу.
Наприклад, Laravel підключається до MariaDB так:

```dotenv
DB_HOST=mariadb
DB_PORT=3306
```

Це внутрішня адреса Docker і вона не залежить від host-порту.

Якщо в Compose написано:

```yaml
ports:
  - "127.0.0.1:13306:3306"
```

це означає:

```text
host VPS 127.0.0.1:13306  ->  container:3306
```

Порт `13306` потрібен host-клієнтам, наприклад DBeaver через SSH tunnel. Laravel
усередині Docker все одно використовує `mariadb:3306`.

## Варіант 1: одна звичайна `default` network

Це типовий і найпростіший Compose-підхід. Мережу можна описати явно:

```yaml
services:
  nginx:
    networks:
      - default

  app:
    networks:
      - default

  mariadb:
    networks:
      - default

networks:
  default:
```

Або не вказувати `networks` зовсім: Compose автоматично створить одну
звичайну `default` network і підключить до неї всі сервіси.

Типова схема виглядає так:

```text
nginx ── default ── app ── mariadb
```

### Переваги

- мінімум YAML;
- просто запускати й пояснювати;
- port publishing зазвичай працює без додаткової мережі;
- зручно для локальної розробки та невеликих internal tools.

### Недоліки

- усі сервіси знаходяться в одній плоскій мережі;
- Nginx, debug-сервіс або випадково доданий контейнер потенційно бачить
  MariaDB;
- складніше явно перевірити межі між web, application і database шарами;
- звичайна bridge network має зовнішній gateway, тому сервіси можуть мати
  вихідний трафік, якщо це не обмежено окремо.

У development це часто прийнятний компроміс:

```yaml
mariadb:
  ports:
    - "127.0.0.1:${DB_FORWARD_PORT:-3306}:3306"
```

У production базу зазвичай взагалі не публікують, якщо немає потреби в
зовнішньому database-клієнті.

## Варіант 2: розділені мережі та приватний backend

У більш ізольованій схемі кожен сервіс підключається лише до потрібних мереж:

```yaml
services:
  nginx:
    networks:
      - frontend

  app:
    networks:
      - frontend
      - backend

  mariadb:
    networks:
      - backend

networks:
  frontend:
  backend:
    internal: true
```

Схема доступу:

```text
nginx ── frontend ── app ── backend ── mariadb
```

`app` є єдиним сервісом, який має доступ до обох шарів. Nginx не підключений
до `backend`, тому він не має прямого мережевого доступу до MariaDB.

### Що робить `internal: true`

`internal: true` створює мережу без звичайного зовнішнього gateway. Сервіси,
підключені до цієї мережі, можуть спілкуватися один з одним, але мережа не
призначена для зовнішнього доступу.

Це не замінює:

- authentication у MariaDB;
- Laravel authorization;
- firewall host;
- правильне налаштування `ports`;
- обмеження доступу до SSH.

Це саме мережевий шар ізоляції між Compose-сервісами.

### Особливість host port publishing

Контейнер, який підключений лише до `internal` network, може прийняти
конфігурацію:

```yaml
ports:
  - "127.0.0.1:13306:3306"
```

але не мати фактичного host binding. Симптоми:

```text
docker port ...
no public port published
```

або:

```json
{"3306/tcp": null}
```

і:

```text
nc: connect to 127.0.0.1 port 13306 failed: Connection refused
```

Тому для Docker Engine 29 host port потрібно публікувати через контейнер,
який має звичайну bridge network.

## Як це зроблено в цьому проєкті

MariaDB підключена до двох логічних шарів доступу:

```yaml
services:
  mariadb:
    networks:
      - backend
      - db-publish

networks:
  backend:
    internal: true
  db-publish:
```

Інші сервіси не підключені до `db-publish`.

Тому маємо таку модель:

```text
app ── backend (internal) ── mariadb

host VPS 127.0.0.1:13306
       │
       └── db-publish ── mariadb:3306
```

Production binding:

```yaml
mariadb:
  ports:
    - "127.0.0.1:${DB_FORWARD_PORT:-13306}:3306"
```

Це дозволяє:

- Laravel працювати через `mariadb:3306`;
- не підключати Nginx до database network;
- підключатися до MariaDB з локального DBeaver через SSH;
- не відкривати `13306` у публічному інтернеті.

### Важливий trade-off

`db-publish` є звичайною bridge network. Через це MariaDB отримує можливість
вихідного трафіку через цю мережу. Це відрізняється від повної outbound-ізоляції
і має бути враховано в production threat model.

Якщо потрібно одночасно:

- залишити MariaDB тільки на `internal` network;
- дозволити SSH/DBeaver-доступ;
- повністю заборонити outbound-трафік MariaDB;

краще використовувати окремий proxy-контейнер. Proxy підключається до
`backend` і звичайної bridge network та публікує `127.0.0.1:13306`, а MariaDB
залишається лише на `backend`.

## DBeaver через SSH

Публікувати `13306` на `0.0.0.0` або відкривати його в UFW не потрібно.

SSH tunnel:

```bash
ssh -N -L 13306:127.0.0.1:13306 user@VPS_IP
```

DBeaver:

```text
Host:     localhost
Port:     13306
Database: DB_DATABASE
User:     DB_USERNAME
Password: DB_PASSWORD
```

Або можна налаштувати SSH tunnel безпосередньо у властивостях DBeaver
з'єднання.

Не змінюйте Laravel-конфігурацію на `DB_HOST=127.0.0.1` або `DB_PORT=13306`.
Ці значення призначені для локального клієнта. Усередині Compose правильні:

```dotenv
DB_HOST=mariadb
DB_PORT=3306
```

## Який варіант обрати

| Сценарій | Рекомендована схема |
| --- | --- |
| Простий локальний проєкт | Одна `default` network |
| Локальна розробка з Adminer | Одна `default` network або окремий dev-шар |
| Production без DBeaver | `frontend` + `backend: internal: true`, без DB host port |
| Production з DBeaver через SSH | `backend: internal: true` + окремий `db-publish` |
| Production зі строгим outbound deny для DB | `backend: internal: true` + proxy-контейнер |
| Кілька сервісів і чіткі security boundaries | Окремі frontend/backend мережі |

## Перевірка після зміни мереж

Перевірте не лише Compose-файл, а й фактичний runtime:

```bash
docker compose config --quiet
docker compose port mariadb 3306
docker port laradocker-mariadb-1 3306/tcp
docker inspect -f '{{json .NetworkSettings.Ports}}' laradocker-mariadb-1
ss -ltnp | grep ':13306'
nc -vz 127.0.0.1 13306
```

Очікуваний фактичний mapping:

```text
127.0.0.1:13306 -> container:3306
```

Якщо Compose показує port, але `NetworkSettings.Ports` має `null`, проблема
знаходиться на рівні Docker network/runtime, а не в Laravel credentials або
DBeaver.

## Додаткові матеріали

- [Docker Compose networking](https://docs.docker.com/compose/how-tos/networking/)
- [Docker port publishing and mapping](https://docs.docker.com/engine/network/port-publishing/)
- [Moby: published ports on internal bridge networks](https://github.com/moby/moby/discussions/53256)
