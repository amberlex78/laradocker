# Необов'язкова інтеграція Cloudflare

Cloudflare замінює тільки DNS/TLS-частину базового deployment. Docker stack,
`.env.prod`, приватний `PROD_HTTP_PORT` і system Nginx залишаються такими
самими.

Використовуйте цей варіант, якщо потрібні Cloudflare Proxy, CDN, WAF або інші
edge-функції. Для звичайного домену без Cloudflare використовуйте
[Let’s Encrypt і Certbot](deployment.md#домен-і-звичайний-https).

## Передумови

Спочатку виконайте спільні кроки з [VPS deployment](deployment.md):

1. Підготуйте Ubuntu, Docker і системний Nginx.
2. Створіть `.env.prod` із `APP_URL=https://example.com`.
3. Запустіть production stack.
4. Переконайтеся, що `curl -I http://127.0.0.1:18080/up` працює.

Не запускайте Certbot і не активуйте одночасно `prod-domain.conf.example` та
Cloudflare-шаблон для того самого hostname.

## DNS і SSL/TLS mode

У Cloudflare створіть proxied DNS records — із помаранчевою хмаринкою:

```text
example.com     A       VPS_IP
www.example.com CNAME   example.com
```

У **SSL/TLS → Overview** встановіть режим:

```text
Full (strict)
```

`Flexible` не використовуйте: між Cloudflare і VPS також має бути HTTPS.

## Cloudflare Origin Certificate

У **SSL/TLS → Origin Server** створіть Origin Certificate для потрібних
hostnames, наприклад `example.com` і `*.example.com`. Збережіть окремо:

- certificate у файл `example.com.origin.crt`;
- private key у файл `example.com.origin.key`.

Private key показується під час створення. Не додавайте його або certificate до
Git і не зберігайте в каталозі checkout.

Скопіюйте файли в домашній каталог користувача VPS захищеним каналом, а потім
встановіть їх для системного Nginx:

```bash
sudo install -d -m 700 /etc/nginx/certs/cloudflare
sudo install -m 644 ~/example.com.origin.crt \
    /etc/nginx/certs/cloudflare/example.com.origin.crt
sudo install -m 600 ~/example.com.origin.key \
    /etc/nginx/certs/cloudflare/example.com.origin.key
sudo ls -l /etc/nginx/certs/cloudflare
```

Після перевірки видаліть лише тимчасові копії з домашнього каталогу:

```bash
rm ~/example.com.origin.crt ~/example.com.origin.key
```

Захищену резервну копію private key зберігайте поза VPS і репозиторієм.

Origin Certificate довіряє Cloudflare, але не звичайний браузер. Пряме
відкриття origin server повз Cloudflare покаже TLS warning — це очікувано для
цього типу сертифіката.

## Nginx reverse proxy

Скопіюйте окремий шаблон:

```bash
sudo cp docker/nginx/host/prod-cloudflare.conf.example \
    /etc/nginx/sites-available/example.com-cloudflare
sudo nano /etc/nginx/sites-available/example.com-cloudflare
```

У [prod-cloudflare.conf.example](../docker/nginx/host/prod-cloudflare.conf.example)
замініть:

- `example.com` і `www.example.com`;
- certificate і private-key paths;
- `127.0.0.1:18080`, якщо проєкт має інший `PROD_HTTP_PORT`.

Активуйте тільки цей virtual host для домену:

```bash
sudo ln -s /etc/nginx/sites-available/example.com-cloudflare \
    /etc/nginx/sites-enabled/example.com-cloudflare
sudo nginx -t
sudo systemctl reload nginx
```

У firewall відкрийте web-порти системного Nginx, але не Docker upstream:

```bash
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
```

Порт `18080` залишається доступним тільки як `127.0.0.1:18080`.

## Cloudflare networks і client IP

Заголовок `CF-Connecting-IP` можна приймати лише від адрес Cloudflare.
Шаблон містить `set_real_ip_from` для офіційних мереж і:

```nginx
real_ip_header CF-Connecting-IP;
real_ip_recursive on;
```

Актуальні списки публікуються окремо:

- <https://www.cloudflare.com/ips-v4>;
- <https://www.cloudflare.com/ips-v6>.

Періодично звіряйте їх із `prod-cloudflare.conf.example`, особливо перед новим
deployment. Невідома мережа не повинна автоматично отримувати довіру.

Request проходить так:

```text
відвідувач
    -> Cloudflare
    -> системний Nginx перевіряє source network і CF-Connecting-IP
    -> Docker Nginx на 127.0.0.1:${PROD_HTTP_PORT}
    -> Laravel
```

Системний Nginx нормалізує forwarding chain і передає перевірену адресу.
Laravel довіряє лише безпосередньому Docker proxy, тому в `.env.prod`
залишається:

```dotenv
TRUSTED_PROXIES=REMOTE_ADDR
```

Не використовуйте `TRUSTED_PROXIES=*`.

## Перевірка

Спочатку перевірте origin virtual host локально на VPS. `-k` потрібен лише
тому, що локальний curl не використовує Cloudflare trust chain:

```bash
curl -kI --resolve example.com:443:127.0.0.1 \
    https://example.com/up
```

Потім перевірте публічний маршрут через Cloudflare без `-k`:

```bash
curl -I https://example.com/up
```

Додатково перевірте:

```bash
sudo nginx -t
make prod-ps
curl -I http://127.0.0.1:18080/up
```

Якщо private upstream працює, а public URL — ні, перевіряйте DNS Proxy status,
`Full (strict)`, certificate hostnames, Nginx logs і firewall.

## Повернення до звичайного HTTPS

Перехід потребує короткого maintenance window: HTTP bootstrap не слухає
`443`, а два active virtual hosts з однаковими hostname не можна вмикати
одночасно.

1. Поки Cloudflare Proxy увімкнений, підготуйте
   `prod-domain.conf.example` у `/etc/nginx/sites-available/example.com` для
   того самого upstream, але ще не активуйте його.
2. В узгоджене maintenance window атомарно замініть active symlink і
   перезавантажте Nginx лише після успішної перевірки:

```bash
sudo unlink /etc/nginx/sites-enabled/example.com-cloudflare
sudo ln -s /etc/nginx/sites-available/example.com \
    /etc/nginx/sites-enabled/example.com
sudo nginx -t
sudo systemctl reload nginx
```

3. Не вимикаючи Cloudflare Proxy, одразу отримайте публічно довірений
   сертифікат через `certbot --nginx`. HTTP-01 challenge пройде через port
   `80`; до завершення issuance звичайні HTTPS-запити через Cloudflare можуть
   бути тимчасово недоступні.
4. Перевірте `sudo nginx -t`, `https://example.com/up` і certificate issuer.
5. Лише після цього переведіть DNS records у режим **DNS only**, дочекайтеся
   оновлення DNS і повторно перевірте HTTPS уже напряму до VPS.

Не видаляйте Origin Certificate або DNS-конфігурацію до завершення переходу.

Повна послідовність налаштування Let's Encrypt наведена в розділі
[«Домен і звичайний HTTPS»](deployment.md#домен-і-звичайний-https).

Оновлення, backup і видалення deployment описані в
[production operations](operations.md).
