# Graph Report - laradocker  (2026-11-26)

## Corpus Check
- 112 files · ~24,870 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 32 file(s) not represented in the graph (top: (none) 14, .example 6, .conf 4)

## Summary
- 811 nodes · 1308 edges · 74 communities (33 shown, 41 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 16 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `8abee736`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- PanelAccessTest.php
- User
- SitePanelProvider.php
- Розгортання на Ubuntu VPS
- DeviceDetectionService
- Illuminate\Database\Schema\Blueprint
- AppServiceProvider.php
- package.json
- UsersTable.php
- LoginSessionTrackingTest.php
- PasswordResetTest.php
- ActiveSessionsTable.php
- Docker networking: звичайна та ізольована схема
- ActiveSessionsTable.php
- LoginHistoryTable.php
- Діагностика
- LoginSessionStatus
- LoginSessionEndReason
- LoginSessionStatusContext
- scripts
- LoginSessionFactory.php
- composer.json
- require-dev
- ViewProfile
- require
- UserFactory.php
- config
- Pest.php
- psr-4
- Q: Чи є у Filament віджети або картки для Dashboard як на скриншоті?
- Q: Зробити кастомний Dashboard у стилі карток замість стандартного Filament Dashboard
- Q: How reliably does StartLoginSession detect Blackview Tab 16 and OSCAL Pad 100, and why can Quetta report Desktop?
- extra
- Controller.php
- entrypoint.sh
- AdminPanelTest.php
- UserRole
- DatabaseSeeder.php
- RequestPasswordReset.php
- UserForm.php
- Laravel Docker Starter
- UserResource.php
- Filament\Schemas\Schema
- Docker-архітектура
- .request
- Необов'язкова інтеграція Cloudflare
- {closure#22}
- Локальна розробка
- Експлуатація production-середовища
- ViewUser.php
- ListUsers

## God Nodes (most connected - your core abstractions)
1. `UserRole` - 68 edges
2. `User` - 48 edges
3. `LoginSession` - 31 edges
4. `LoginSessionStatus` - 22 edges
5. `LoginSessionEndReason` - 21 edges
6. `UserResource` - 14 edges
7. `EditProfile` - 13 edges
8. `ViewProfile` - 13 edges
9. `scripts` - 13 edges
10. `DeviceDetectionService` - 12 edges

## Surprising Connections (you probably didn't know these)
- `{closure#1}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/AdminPanelTest.php → app/Enums/UserRole.php
- `{closure#10}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/AdminPanelTest.php → app/Enums/UserRole.php
- `{closure#11}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/AdminPanelTest.php → app/Enums/UserRole.php
- `{closure#12}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/AdminPanelTest.php → app/Enums/UserRole.php
- `{closure#13}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/AdminPanelTest.php → app/Enums/UserRole.php

## Import Cycles
- None detected.

## Communities (74 total, 41 thin omitted)

### Community 0 - "PanelAccessTest.php"
Cohesion: 0.09
Nodes (9): {closure#1}(), {closure#13}(), {closure#2}(), {closure#23}(), {closure#29}(), {closure#30}(), {closure#31}(), {closure#6}() (+1 more)

### Community 2 - "SitePanelProvider.php"
Cohesion: 0.06
Nodes (12): SystemOverview, RedirectGuestsToSiteLogin, AdminPanelProvider, {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), OperatorPanelProvider (+4 more)

### Community 3 - "Розгортання на Ubuntu VPS"
Cohesion: 0.20
Nodes (10): Вибір публічного доступу, Вимоги, Домен і звичайний HTTPS, Доступ без домену, Клонування та production-конфігурація, Кілька проєктів на одному VPS, Наступні кроки, Перевірка приватного Docker stack (+2 more)

### Community 4 - "DeviceDetectionService"
Cohesion: 0.09
Nodes (13): StartLoginSession, AdvertiseDeviceClientHints, TrackLoginSession, RecordLogin, DeviceDetectionService, {closure#1}(), {closure#2}(), {closure#3}() (+5 more)

### Community 5 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.07
Nodes (16): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+8 more)

### Community 6 - "AppServiceProvider.php"
Cohesion: 0.14
Nodes (5): FilamentLoginResponse, FilamentLogoutResponse, AppServiceProvider, {closure#1}(), {closure#3}()

### Community 7 - "package.json"
Cohesion: 0.07
Nodes (30): dependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, vite-plus, optionalDependencies (+22 more)

### Community 10 - "PasswordResetTest.php"
Cohesion: 0.06
Nodes (6): DeveloperAccountWidget, DeveloperFilamentInfoWidget, {closure#3}(), {closure#3}(), {closure#4}(), insertDatabaseSession()

### Community 12 - "ActiveSessionsTable.php"
Cohesion: 0.20
Nodes (4): {closure#1}(), {closure#2}(), DeviceColumn, {closure#21}()

### Community 13 - "Docker networking: звичайна та ізольована схема"
Cohesion: 0.13
Nodes (14): DBeaver через SSH, Docker networking: звичайна та ізольована схема, Важливий trade-off, Варіант 1: одна звичайна `default` network, Варіант 2: розділені мережі та приватний backend, Додаткові матеріали, Недоліки, Особливість host port publishing (+6 more)

### Community 14 - "ActiveSessionsTable.php"
Cohesion: 0.29
Nodes (4): ActiveSessionsTable, {closure#1}(), {closure#4}(), LoginHistoryTable

### Community 15 - "LoginHistoryTable.php"
Cohesion: 0.24
Nodes (7): {closure#10}(), {closure#12}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#8}(), {closure#9}()

### Community 16 - "Діагностика"
Cohesion: 0.29
Nodes (7): 1. Конфігурація Docker Compose, 2. Контейнери та healthchecks, 3. Laravel, 4. Приватний upstream, 5. Системний Nginx, 6. Firewall, DNS і TLS, Діагностика

### Community 17 - "LoginSessionStatus"
Cohesion: 0.16
Nodes (14): LoginSessionStatus, Active, Current, Expired, SignedOut, Terminated, {closure#3}(), {closure#1}() (+6 more)

### Community 18 - "LoginSessionEndReason"
Cohesion: 0.06
Nodes (12): TerminateOtherSessions, TerminateUserSessions, {closure#1}(), {closure#2}(), LoginSessionEndReason, LoggedOut, ManuallyTerminated, PasswordChanged (+4 more)

### Community 21 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, ci:check, dev, lint, lint:check, post-autoload-dump, post-create-project-cmd, post-root-package-install (+5 more)

### Community 23 - "composer.json"
Cohesion: 0.17
Nodes (11): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+3 more)

### Community 24 - "require-dev"
Cohesion: 0.17
Nodes (12): require-dev, fakerphp/faker, larastan/larastan, laravel/boost, laravel/pail, laravel/pao, laravel/pint, laravel/sail (+4 more)

### Community 27 - "require"
Cohesion: 0.25
Nodes (8): require, filament/filament, laravel/framework, laravel/tinker, livewire/blaze, livewire/livewire, matomo/device-detector, php

### Community 29 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 31 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 33 - "Q: Чи є у Filament віджети або картки для Dashboard як на скриншоті?"
Cohesion: 0.40
Nodes (4): Answer, Outcome, Q: Чи є у Filament віджети або картки для Dashboard як на скриншоті?, Source Nodes

### Community 34 - "Q: Зробити кастомний Dashboard у стилі карток замість стандартного Filament Dashboard"
Cohesion: 0.40
Nodes (4): Answer, Outcome, Q: Зробити кастомний Dashboard у стилі карток замість стандартного Filament Dashboard, Source Nodes

### Community 35 - "Q: How reliably does StartLoginSession detect Blackview Tab 16 and OSCAL Pad 100, and why can Quetta report Desktop?"
Cohesion: 0.40
Nodes (4): Answer, Outcome, Q: How reliably does StartLoginSession detect Blackview Tab 16 and OSCAL Pad 100, and why can Quetta report Desktop?, Source Nodes

### Community 38 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 56 - "AdminPanelTest.php"
Cohesion: 0.10
Nodes (18): {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}() (+10 more)

### Community 57 - "UserRole"
Cohesion: 0.13
Nodes (12): UserRole, Admin, Developer, Operator, User, {closure#14}(), {closure#17}(), {closure#21}() (+4 more)

### Community 62 - "Laravel Docker Starter"
Cohesion: 0.22
Nodes (9): Laravel Docker Starter, Вимоги, Застосувати оновлення, Новий проєкт на базі цього репозиторію, Основні команди, Переглянути оновлення шаблону, Створити проєкт, Швидкий старт (+1 more)

### Community 63 - "UserResource.php"
Cohesion: 0.17
Nodes (5): CreateUser, UsersTable, {closure#1}(), {closure#2}(), UserResource

### Community 65 - "Docker-архітектура"
Cohesion: 0.25
Nodes (8): Compose-файли, Docker-архітектура, Nginx і forwarded headers, Внутрішні та host-порти, Дані та файлові системи, Мережі, Сервіси та образи, Шляхи HTTP-запиту

### Community 67 - "Необов'язкова інтеграція Cloudflare"
Cohesion: 0.25
Nodes (8): Cloudflare networks і client IP, Cloudflare Origin Certificate, DNS і SSL/TLS mode, Nginx reverse proxy, Необов'язкова інтеграція Cloudflare, Перевірка, Передумови, Повернення до звичайного HTTPS

### Community 69 - "Локальна розробка"
Cohesion: 0.25
Nodes (8): Production-образ локально, Вимоги, Діагностика, Кілька проєктів одночасно, Локальна розробка, Перший запуск, Створення нового проєкту, Щоденні команди

### Community 70 - "Експлуатація production-середовища"
Cohesion: 0.29
Nodes (7): Видалення одного проєкту, Відновлення MariaDB, Експлуатація production-середовища, Міграції, Оновлення застосунку, Резервна копія MariaDB, Статус і логи

## Knowledge Gaps
- **20 isolated node(s):** `LoggedOut`, `ManuallyTerminated`, `PasswordReset`, `PasswordChanged`, `Current` (+15 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 374 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **41 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Work-memory lessons

**Preferred sources** — corroborated by past sessions; start here.
- `AdminPanelProvider.php` (2× useful, score=1.223979915) _(code changed — re-verify)_
- `Filament\Pages\Dashboard` (2× useful, score=1.223979915)

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User` to `PanelAccessTest.php`, `SitePanelProvider.php`, `DatabaseSeeder.php`, `UsersTable.php`, `LoginSessionTrackingTest.php`, `PasswordResetTest.php`, `ActiveSessionsTest.php`, `LoginSessionEndReason`, `User.php`, `LoginSessionFactory.php`, `AdminPanelTest.php`, `ViewProfile`, `UserFactory.php`, `UserResource.php`?**
  _High betweenness centrality (0.102) - this node is a cross-community bridge._
- **What connects `LoggedOut`, `ManuallyTerminated`, `PasswordReset` to the rest of the system?**
  _20 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `PanelAccessTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.08666666666666667 - nodes in this community are weakly interconnected._
- **Why does `UserRole` connect `UserRole` to `PanelAccessTest.php`, `User`, `SitePanelProvider.php`, `.request`, `{closure#22}`, `AppServiceProvider.php`, `UsersTable.php`, `PasswordResetTest.php`, `LoginSessionEndReason`, `User.php`, `AdminPanelTest.php`, `DatabaseSeeder.php`, `UserFactory.php`, `UserForm.php`, `UserResource.php`?**
  _High betweenness centrality (0.096) - this node is a cross-community bridge._
- **Should `SitePanelProvider.php` be split into smaller, more focused modules?**
  _Cohesion score 0.05501165501165501 - nodes in this community are weakly interconnected._
- **Why does `LoginSession` connect `LoginSessionStatus` to `PanelAccessTest.php`, `DeviceDetectionService`, `LoginSessionTrackingTest.php`, `PasswordResetTest.php`, `ActiveSessionsTest.php`, `ActiveSessionsTable.php`, `ActiveSessionsTable.php`, `LoginHistoryTable.php`, `LoginSessionEndReason`, `LoginSessionStatusContext`, `User.php`, `LoginSessionFactory.php`?**
  _High betweenness centrality (0.068) - this node is a cross-community bridge._
- **Should `DeviceDetectionService` be split into smaller, more focused modules?**
  _Cohesion score 0.09446693657219973 - nodes in this community are weakly interconnected._