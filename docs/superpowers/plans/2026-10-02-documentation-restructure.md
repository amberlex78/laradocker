# Documentation Restructure Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Перебудувати українську документацію Laravel Docker starter так, щоб кожне правило мало одне канонічне місце, а локальні й VPS-сценарії для одного або кількох проєктів відповідали фактичній Compose/Nginx-конфігурації.

**Architecture:** Спочатку створюються канонічні env- і Nginx-шаблони, після чого документація переписується за відповідальністю: architecture, development, deployment, Cloudflare та operations. README стає коротким маршрутизатором, а два дубльовані scenario guides видаляються лише після перенесення унікальних застережень.

**Tech Stack:** Markdown, Docker Compose, Nginx 1.30.4 Alpine, Ubuntu VPS, Certbot, Cloudflare, GNU Make, Graphify.

**Spec:** `docs/superpowers/specs/2026-10-02-documentation-restructure-design.md`

## Global Constraints

- Уся користувацька документація має бути українською; команди, змінні, шляхи й назви технологій залишаються оригінальними.
- VPS-інструкції підтримують Ubuntu, системний Nginx, UFW і Certbot; інші дистрибутиви поза scope.
- Звичайний доменний HTTPS через Let’s Encrypt/Certbot є основним шляхом; Cloudflare є необов'язковою альтернативою.
- Внутрішні порти не змінюються: Docker Nginx `8080`, PHP-FPM `9000`, MariaDB `3306`, Vite `5173`.
- Канонічний production upstream example: перший проєкт `127.0.0.1:18080`, другий `127.0.0.1:18081`.
- Production upstream завжди прив'язаний до `127.0.0.1`; назовні відкривається лише системний Nginx.
- `APP_URL` є публічною адресою, а не приватним upstream.
- Не змінювати application logic, Compose behavior, Git-політику `make deploy` або залежності.
- Не створювати новий validation script: використовувати наявні Make targets і одноразові read-only shell checks.
- Після кожного змістовного етапу робити окремий commit; не включати сторонні зміни користувача.

## Review Focus

- Другий локальний стек не повинен конфліктувати через Adminer: Task 3 перевіряє `ADMINER_FORWARD_PORT=8091` разом з усіма іншими host ports.
- `APP_URL` не повинен помилково містити `18080`: Task 4 перевіряє окремо публічні domain/no-domain URL і приватний upstream.
- Certbot bootstrap має проходити `nginx -t` до отримання сертифіката: Task 1 перевіряє domain template без TLS directives, Task 4 документує порядок активації.
- Cloudflare client IP не можна довіряти з довільного джерела: Task 1 звіряє allowlist з офіційними IPv4/IPv6 списками, Task 5 перевіряє `real_ip_header CF-Connecting-IP` і заборону `TRUSTED_PROXIES=*`.
- Після видалення scenario guides не повинно лишитися битих маршрутів: Task 7 і Task 8 перевіряють усі локальні Markdown links і згадки старих назв файлів.

---

### Task 1: Канонічні env- і Nginx-шаблони

**Files:**
- Modify: `.env.example:1-22`
- Modify: `.env.prod.example:1-39`
- Modify: `docker/nginx/host/dev.conf.example` (translate explanatory comments only)
- Create: `docker/nginx/host/prod-domain.conf.example`
- Create: `docker/nginx/host/prod-no-domain.conf.example`
- Create: `docker/nginx/host/prod-cloudflare.conf.example`
- Delete: `docker/nginx/host/prod.conf.example`

**Interfaces:**
- Consumes: port invariants from `docker-compose.yml`, `docker-compose.dev.yml`, `docker-compose.prod.yml`; trust behavior from `bootstrap/app.php`.
- Produces: canonical examples consumed verbatim by Tasks 2-7: `PROD_HTTP_PORT=18080`, local second-project host ports `8001/5174/3307/8091`, and the three explicit production proxy modes.

- [ ] **Step 1: Record the expected pre-change failures**

Run:

```bash
test -f docker/nginx/host/prod-domain.conf.example
test -f docker/nginx/host/prod-no-domain.conf.example
test -f docker/nginx/host/prod-cloudflare.conf.example
rg -n 'laradocer|laradocer\.top|127\.0\.0\.1:8080' .env.prod.example docker/nginx/host/prod.conf.example
```

Expected: the three `test -f` commands fail; `rg` finds the personal values and legacy `8080` upstream.

- [ ] **Step 2: Normalize `.env.example` and `.env.prod.example`**

In `.env.example`, add short comments that identify `DEV_HTTP_PORT`, `VITE_FORWARD_PORT`, `VITE_HMR_PORT`, `DB_FORWARD_PORT`, and `ADMINER_FORWARD_PORT` as host-facing values; state that `VITE_PORT=5173`, `DB_HOST=mariadb`, and `DB_PORT=3306` are container-side values.

Keep all explanatory comments in the env and Nginx example files in Ukrainian so the templates match the public documentation language.

In `.env.prod.example`, use these exact generic values:

```dotenv
COMPOSE_PROJECT_NAME=laravel-app
APP_NAME=Laravel
APP_URL=https://example.com
TRUSTED_PROXIES=REMOTE_ADDR
PROD_HTTP_PORT=18080
DB_DATABASE=laravel
DB_USERNAME=laravel
```

Retain strong-password placeholders and explain that `APP_URL` is public while the host Nginx proxies to `127.0.0.1:${PROD_HTTP_PORT}`.

- [ ] **Step 3: Replace the ambiguous production Nginx template**

Create:

- `prod-domain.conf.example`: HTTP `server_name example.com www.example.com`, proxy to `127.0.0.1:18080`, preserve proxy/security headers, allow `/.well-known/acme-challenge/`, and contain no certificate path so `nginx -t` works before `certbot --nginx`.
- `prod-no-domain.conf.example`: public listener `8081`, `server_name _`, proxy to `127.0.0.1:18080`, and explicitly label the route temporary/unencrypted.
- `prod-cloudflare.conf.example`: `80` redirect plus `443 ssl`, Cloudflare Origin Certificate paths, proxy to `127.0.0.1:18080`, official Cloudflare IPv4/IPv6 `set_real_ip_from` entries, `real_ip_header CF-Connecting-IP`, and `X-Forwarded-For $remote_addr`.

Delete `prod.conf.example`. Keep equivalent favicon, robots, static asset, hidden-file and PHP-path protections where applicable.

- [ ] **Step 4: Verify Cloudflare networks against primary sources**

Run:

```bash
curl --retry 2 -fsSL https://www.cloudflare.com/ips-v4
curl --retry 2 -fsSL https://www.cloudflare.com/ips-v6
```

Expected: every returned CIDR appears once as `set_real_ip_from` in `prod-cloudflare.conf.example`; no additional network is trusted.

- [ ] **Step 5: Validate env interpolation and template invariants**

Run:

```bash
make config-dev
cp .env.prod.example .env.prod
make config-prod
rg -n 'PROD_HTTP_PORT=18080|APP_URL=https://example\.com|TRUSTED_PROXIES=REMOTE_ADDR' .env.prod.example
rg -n '127\.0\.0\.1:18080' docker/nginx/host/prod-*.conf.example
test ! -e docker/nginx/host/prod.conf.example
```

Expected: both Compose validations exit `0`; all three new production templates use upstream `18080`; the old template is absent.

- [ ] **Step 6: Validate Nginx syntax**

Run:

```bash
nginx_test_dir=$(mktemp -d)
mkdir -p "$nginx_test_dir/conf.d" "$nginx_test_dir/certs/cloudflare"

for template in dev prod-domain prod-no-domain; do
    cp "docker/nginx/host/${template}.conf.example" "$nginx_test_dir/conf.d/default.conf"
    docker run --rm \
        -v "$nginx_test_dir/conf.d/default.conf:/etc/nginx/conf.d/default.conf:ro" \
        nginx:1.30.4-alpine nginx -t
done

openssl req -x509 -nodes -newkey rsa:2048 -days 1 \
    -subj '/CN=example.com' \
    -keyout "$nginx_test_dir/certs/cloudflare/example.com.origin.key" \
    -out "$nginx_test_dir/certs/cloudflare/example.com.origin.crt"
cp docker/nginx/host/prod-cloudflare.conf.example "$nginx_test_dir/conf.d/default.conf"
docker run --rm \
    -v "$nginx_test_dir/conf.d/default.conf:/etc/nginx/conf.d/default.conf:ro" \
    -v "$nginx_test_dir/certs/cloudflare:/etc/nginx/certs/cloudflare:ro" \
    nginx:1.30.4-alpine nginx -t
```

Expected: `dev`, `prod-domain`, `prod-no-domain`, and `prod-cloudflare` each report `syntax is ok` and `test is successful`. The temporary directory may remain under `/tmp`; do not use a broad recursive cleanup command.

- [ ] **Step 7: Commit the canonical examples**

```bash
git add .env.example .env.prod.example docker/nginx/host
git commit -m "docs: add explicit production proxy templates"
```

### Task 2: Переписати Docker architecture reference

**Files:**
- Modify: `docs/architecture.md`

**Interfaces:**
- Consumes: canonical values and template names from Task 1; actual Compose networks, builds, mounts and healthchecks.
- Produces: conceptual model linked by README and all procedural guides; owns the distinction between container ports, host ports and public listeners.

- [ ] **Step 1: Confirm the current file mixes language and scenario guidance**

Run:

```bash
rg -n '^# Docker architecture|Multiple projects on one VPS|PROD_HTTP_PORT' docs/architecture.md
```

Expected: English headings and scenario-level VPS examples are found.

- [ ] **Step 2: Rewrite `docs/architecture.md` in Ukrainian**

Include only:

- roles of `docker-compose.yml`, `docker-compose.dev.yml`, and `docker-compose.prod.yml`;
- PHP, Nginx, MariaDB, Composer and Node build/runtime responsibilities;
- `frontend`, internal `backend`, and Adminer network boundaries;
- fixed container ports `8080/9000/3306/5173`;
- host publishing in dev and production;
- bind mounts versus production images/volumes;
- isolation by `COMPOSE_PROJECT_NAME`;
- request paths for local, domain HTTPS, and no-domain access.

Do not include clone, firewall, Certbot, `make deploy`, or per-project setup instructions. Link to `development.md` and `deployment.md` for procedures.

- [ ] **Step 3: Verify architecture boundaries**

Run:

```bash
rg -n '8080|9000|3306|5173|COMPOSE_PROJECT_NAME|frontend|backend' docs/architecture.md
rg -n 'git clone|sudo ufw|certbot|make deploy' docs/architecture.md
```

Expected: the first command finds every invariant; the second returns no matches.

- [ ] **Step 4: Commit**

```bash
git add docs/architecture.md
git commit -m "docs: rewrite Docker architecture reference"
```

### Task 3: Переписати local development guide

**Files:**
- Modify: `docs/development.md`

**Interfaces:**
- Consumes: `.env.example`, Make targets and architecture terminology from Tasks 1-2.
- Produces: the only local setup and multi-project procedure; README links directly to its stable headings.

- [ ] **Step 1: Confirm the known multi-project defects**

Run:

```bash
rg -n 'VITE_PORT=5174|ADMINER_FORWARD_PORT' docs/development.md
```

Expected: `VITE_PORT=5174` is found and `ADMINER_FORWARD_PORT` is absent from the second-project recipe.

- [ ] **Step 2: Rewrite `docs/development.md` in Ukrainian**

Use these stable sections:

1. `Вимоги`.
2. `Створення нового проєкту` — clone into a chosen directory, copy `.env.example`, replace starter identity values.
3. `Перший запуск` — `make install`, `make up`, `make migrate`.
4. `Щоденні команди` — lifecycle, Laravel, tests, Composer/npm and shells.
5. `Кілька проєктів одночасно` — one rule plus a Project A/Project B table.
6. `Production-образ локально` — separate `.env.prod`, Compose name and `PROD_HTTP_PORT`.
7. `Діагностика`.

The second-project example must use exactly:

```dotenv
COMPOSE_PROJECT_NAME=project-b
DEV_HTTP_PORT=8001
VITE_FORWARD_PORT=5174
VITE_PORT=5173
VITE_HMR_PORT=5174
DB_FORWARD_PORT=3307
ADMINER_FORWARD_PORT=8091
```

State that `DB_HOST=mariadb` and `DB_PORT=3306` remain unchanged.

- [ ] **Step 3: Verify the multi-project contract**

Run:

```bash
rg -n 'DEV_HTTP_PORT=8001|VITE_FORWARD_PORT=5174|VITE_PORT=5173|VITE_HMR_PORT=5174|DB_FORWARD_PORT=3307|ADMINER_FORWARD_PORT=8091' docs/development.md
! rg -n 'VITE_PORT=5174' docs/development.md
make config-dev
```

Expected: every required host-port value is found, the invalid internal Vite value is absent, and Compose validation passes.

- [ ] **Step 4: Commit**

```bash
git add docs/development.md
git commit -m "docs: consolidate local development guidance"
```

### Task 4: Переписати Ubuntu VPS deployment guide

**Files:**
- Modify: `docs/deployment.md`
- Reference: `docker/nginx/host/prod-domain.conf.example`
- Reference: `docker/nginx/host/prod-no-domain.conf.example`

**Interfaces:**
- Consumes: production env and Nginx templates from Task 1; operational commands remain owned by Task 6.
- Produces: one parameterized Ubuntu deployment flow with standard HTTPS, no-domain access and multi-project allocation.

- [ ] **Step 1: Confirm site-specific and Cloudflare-coupled content**

Run:

```bash
rg -n 'esp32\.xyz|Cloudflare Origin|CF-Connecting-IP|127\.0\.0\.1:8080|git pull' docs/deployment.md
```

Expected: site-specific values, Cloudflare-specific TLS, the legacy upstream and duplicate pull instructions are found.

- [ ] **Step 2: Rewrite the shared Ubuntu production flow**

Use stable sections for prerequisites, server preparation, clone/configure, `make config-prod`, first deployment, private upstream verification, and public access selection. Explain the exact `make deploy` preconditions and sequence without claiming it performs a host-side `curl`.

Show `APP_URL=https://example.com` for domain mode and `APP_URL=http://VPS_IP:8081` for no-domain mode. Keep `PROD_HTTP_PORT=18080` private in both cases.

- [ ] **Step 3: Document no-domain access as a temporary branch**

Reference `prod-no-domain.conf.example`, expose only system Nginx port `8081`, keep `18080` closed, and verify in this order: private `/up`, `nginx -t`, public `http://VPS_IP:8081/up`.

- [ ] **Step 4: Document standard domain HTTPS as the default branch**

Reference `prod-domain.conf.example`, require DNS to point directly to the VPS, activate the HTTP bootstrap configuration, open `80/443`, run `certbot --nginx -d example.com -d www.example.com`, verify renewal, and then verify the public HTTPS `/up` endpoint.

Link to `cloudflare.md` as an alternative to this TLS/DNS branch, not as an additional prerequisite.

- [ ] **Step 5: Document multiple projects by allocation table**

Show Project A/B with unique `COMPOSE_PROJECT_NAME`, `PROD_HTTP_PORT`, `APP_KEY`, DB credentials and domain/no-domain public identity. State that domain virtual hosts share `80/443`, while no-domain projects require unique public listeners.

- [ ] **Step 6: Verify deployment invariants**

Run:

```bash
rg -n 'PROD_HTTP_PORT=18080|127\.0\.0\.1:18080|APP_URL=https://example\.com|APP_URL=http://VPS_IP:8081' docs/deployment.md
rg -n 'prod-domain\.conf\.example|prod-no-domain\.conf\.example|certbot --nginx|80/443' docs/deployment.md
! rg -n 'esp32\.xyz|laradocer|Cloudflare Origin Certificate|127\.0\.0\.1:8080' docs/deployment.md
```

Expected: public/private examples and both template routes are present; personal, Cloudflare-specific and legacy upstream values are absent.

- [ ] **Step 7: Commit**

```bash
git add docs/deployment.md
git commit -m "docs: rebuild Ubuntu VPS deployment guide"
```

### Task 5: Додати optional Cloudflare guide

**Files:**
- Create: `docs/cloudflare.md`
- Reference: `docker/nginx/host/prod-cloudflare.conf.example`

**Interfaces:**
- Consumes: completed base deployment through private upstream verification from Task 4; Cloudflare template and allowlist from Task 1.
- Produces: an isolated replacement for the standard DNS/TLS branch, linked from deployment and README.

- [ ] **Step 1: Verify the guide does not exist**

Run: `test -f docs/cloudflare.md`

Expected: exit status is non-zero.

- [ ] **Step 2: Write `docs/cloudflare.md`**

Document:

- when to choose Cloudflare instead of the standard Certbot route;
- proxied DNS records and `Full (strict)`;
- Origin Certificate installation permissions and cleanup of temporary key copies;
- copying and parameterizing `prod-cloudflare.conf.example`;
- official IPv4/IPv6 source lists and periodic allowlist maintenance;
- the client-IP chain from Cloudflare through host Nginx and Docker Nginx to Laravel;
- keeping `TRUSTED_PROXIES=REMOTE_ADDR`;
- local `curl --resolve` verification before public DNS and public verification afterward;
- migration back to ordinary HTTPS at a high level.

State explicitly that Origin Certificates are not browser-trusted when accessed directly and that Docker upstream ports remain private.

- [ ] **Step 3: Verify Cloudflare trust boundaries**

Run:

```bash
rg -n 'Full \(strict\)|Origin Certificate|prod-cloudflare\.conf\.example|CF-Connecting-IP|TRUSTED_PROXIES=REMOTE_ADDR|ips-v4|ips-v6' docs/cloudflare.md
! rg -n 'TRUSTED_PROXIES=\*' docs/cloudflare.md docker/nginx/host/prod-cloudflare.conf.example
```

Expected: every required trust element is present and wildcard proxy trust is absent.

- [ ] **Step 4: Commit**

```bash
git add docs/cloudflare.md
git commit -m "docs: add optional Cloudflare deployment guide"
```

### Task 6: Додати operations and lifecycle guide

**Files:**
- Create: `docs/operations.md`
- Reference: `Makefile`

**Interfaces:**
- Consumes: running production stack from Task 4 and optional proxy state from Task 5.
- Produces: the only recurring operations, backup/restore, diagnosis and retirement procedures; deployment and README link here instead of duplicating commands.

- [ ] **Step 1: Verify the guide does not exist**

Run: `test -f docs/operations.md`

Expected: exit status is non-zero.

- [ ] **Step 2: Write routine operations**

Document exact Make targets for `prod-ps`, `prod-logs`, `prod-laravel-logs`, `prod-stats`, `prod-migrate`, `prod-optimize`, `prod-up`, `prod-down`, and `deploy`. Explain that `make deploy` performs its own fast-forward pull and must not be preceded by a redundant pull.

- [ ] **Step 3: Write backup and restore procedures**

Use the production Compose files and `.env.prod` to run `mariadb-dump` and restore through stdin without publishing MariaDB. Require an explicit target database check, off-repository backup location, protected permissions, and a restore test. Do not present `migrate:fresh` as an operational recovery step.

- [ ] **Step 4: Write layered diagnosis and project retirement**

Diagnosis order: Compose config, service health, Laravel logs, private upstream, `nginx -t`, listeners/firewall, DNS/TLS/Cloudflare. Retirement order: backup, resolve exact Compose project, `make prod-down`, inspect Nginx references, remove only the selected site/symlink, validate/reload Nginx, adjust UFW/DNS/certificates, then remove the selected checkout. Warn that volumes survive `prod-down` and that destructive volume removal is a separate explicit decision.

- [ ] **Step 5: Verify operational accuracy**

Run:

```bash
rg -n 'make prod-ps|make prod-logs|make prod-laravel-logs|make prod-stats|make deploy|mariadb-dump|nginx -t|prod-down' docs/operations.md
! rg -n 'git pull.*make deploy|migrate:fresh.*backup|rm -rf /home|docker compose down -v' docs/operations.md
```

Expected: all supported operations are present; redundant pull and unsafe generic destructive recipes are absent.

- [ ] **Step 6: Commit**

```bash
git add docs/operations.md
git commit -m "docs: add production operations guide"
```

### Task 7: Перебудувати README та прибрати дубльовані guides

**Files:**
- Modify: `README.md`
- Delete: `docs/projects-no-domains.md`
- Delete: `docs/projects-with-domains.md`
- Modify: `docs/deployment.md` only if final cross-links need adjustment
- Modify: `docs/development.md` only if final cross-links need adjustment

**Interfaces:**
- Consumes: final stable headings and responsibilities from Tasks 2-6.
- Produces: the public repository landing page and complete “choose your path” navigation with no stale scenario guides.

- [ ] **Step 1: Confirm the current navigation gap**

Run:

```bash
rg -n '^## Документація|docs/' README.md
test -f docs/projects-no-domains.md
test -f docs/projects-with-domains.md
```

Expected: README lists only part of the documentation; both duplicated guides exist.

- [ ] **Step 2: Rewrite `README.md` as a concise landing page**

Include:

- project purpose and stack;
- requirements;
- four-command local quick start;
- resulting local URLs, including Adminer;
- a short architecture summary linked to `docs/architecture.md`;
- a compact common-command section;
- a “Що ви хочете зробити?” table linking to exact sections for one/multiple local projects, production-like local run, VPS without domain, VPS with standard HTTPS, Cloudflare, operations and architecture.

Do not repeat full second-project env blocks or full VPS instructions.

- [ ] **Step 3: Add contextual cross-links**

At the end of each canonical guide, add only relevant previous/next links. Ensure deployment points to operations and Cloudflare, development points to architecture/deployment, and Cloudflare points back to the standard deployment route.

- [ ] **Step 4: Delete the two scenario guides**

Before deletion, confirm the canonical guides cover: loopback-only upstreams, public firewall differences, unique `APP_KEY` and database credentials, Cloudflare certificate cleanup, persistent database volumes, exact Nginx site removal, UFW cleanup, DNS cleanup, logs and updates. Then delete `docs/projects-no-domains.md` and `docs/projects-with-domains.md`.

- [ ] **Step 5: Verify navigation and stale-content removal**

Run:

```bash
rg -n 'docs/(development|deployment|cloudflare|operations|architecture)\.md' README.md
! rg -n 'projects-no-domains|projects-with-domains' README.md docs --glob '*.md'
! rg -n 'esp32\.xyz|laradocer\.top|/home/lex|/home/esp32' README.md docs --glob '*.md' --glob '!docs/superpowers/**'
test ! -e docs/projects-no-domains.md
test ! -e docs/projects-with-domains.md
```

Expected: README links every canonical guide; old names and personal values are absent; both scenario files are gone.

- [ ] **Step 6: Commit**

```bash
git add README.md docs/development.md docs/deployment.md docs/cloudflare.md docs/operations.md docs/projects-no-domains.md docs/projects-with-domains.md
git commit -m "docs: replace scenario guides with canonical navigation"
```

### Task 8: Full documentation and configuration verification

**Files:**
- Verify: `README.md`
- Verify: `docs/{architecture,development,deployment,cloudflare,operations}.md`
- Verify: `.env.example`
- Verify: `.env.prod.example`
- Verify: `docker/nginx/host/*.example`
- Update: `graphify-out/**` as produced by `graphify update .`

**Interfaces:**
- Consumes: all prior task outputs.
- Produces: evidence that documentation, examples, proxy templates and knowledge graph agree as one deliverable.

- [ ] **Step 1: Check formatting and repository state**

Run:

```bash
git diff --check
git status --short
```

Expected: no whitespace errors; only intentional task changes, if any, are listed.

- [ ] **Step 2: Validate both Compose modes**

Run:

```bash
make config-dev
cp .env.prod.example .env.prod
make config-prod
```

Expected: both commands exit `0`.

- [ ] **Step 3: Repeat Nginx syntax validation**

Run the exact temporary-container commands from Task 1 Step 6 for all three production templates and `dev.conf.example`.

Expected: every template reports `syntax is ok` and `test is successful`.

- [ ] **Step 4: Validate local Markdown file links and canonical values**

Run:

```bash
for source in README.md docs/architecture.md docs/development.md docs/deployment.md docs/cloudflare.md docs/operations.md; do
    source_dir=$(dirname "$source")
    grep -oE '\]\([^ )]+\.md(#[^ )]+)?\)' "$source" \
        | sed -E 's/^\]\(([^#)]+).*/\1/' \
        | while IFS= read -r target; do
            test -e "$source_dir/$target" || {
                echo "Broken Markdown link: $source -> $target"
                exit 1
            }
        done || exit 1
done
```

Expected: exit status `0` and no broken-link output.

Then run:

```bash
rg -n 'VITE_PORT=5173|ADMINER_FORWARD_PORT=8091|PROD_HTTP_PORT=18080|127\.0\.0\.1:18080' README.md docs .env.example .env.prod.example docker/nginx/host
! rg -n 'VITE_PORT=5174|projects-no-domains|projects-with-domains|esp32\.xyz|laradocer\.top|127\.0\.0\.1:8080' README.md docs .env.example .env.prod.example docker/nginx/host --glob '!docs/superpowers/**'
```

Expected: canonical values are present in their owning files; contradictory values, stale guides and personal examples are absent.

- [ ] **Step 5: Update and inspect Graphify**

Run:

```bash
graphify update .
graphify query "How do I run one or multiple projects locally and deploy them to Ubuntu with no domain, standard HTTPS, or Cloudflare?"
```

Expected: the query returns README and the five canonical guides, not the deleted scenario guides.

- [ ] **Step 6: Commit generated graph changes if present**

```bash
git add graphify-out
git commit -m "chore(graphify): update documentation graph"
```

Skip the commit if `graphify update .` produces no tracked changes.

- [ ] **Step 7: Final clean-tree check**

Run:

```bash
git status --short
git log --oneline -10
```

Expected: the worktree is clean and the documentation/configuration commits are present in task order.
