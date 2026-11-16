# Graph Report - laradocker  (2026-11-16)

## Corpus Check
- 101 files · ~22,700 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 32 file(s) not represented in the graph (top: (none) 14, .example 6, .conf 4)

## Summary
- 727 nodes · 1144 edges · 53 communities (26 shown, 27 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 10 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `b834447e`
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
- Filament\Facades\Filament
- EndLoginSession
- LoginHistoryTable.php
- Docker networking: звичайна та ізольована схема
- LoginSessionEndReason
- Експлуатація production-середовища
- UserResource.php
- scripts
- composer.json
- require-dev
- require
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

## God Nodes (most connected - your core abstractions)
1. `UserRole` - 64 edges
2. `User` - 37 edges
3. `LoginSession` - 27 edges
4. `LoginSessionStatus` - 22 edges
5. `UserResource` - 14 edges
6. `scripts` - 13 edges
7. `ViewProfile` - 12 edges
8. `DeviceDetectionService` - 12 edges
9. `require-dev` - 12 edges
10. `StartLoginSession` - 10 edges

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

## Communities (53 total, 27 thin omitted)

### Community 0 - "PanelAccessTest.php"
Cohesion: 0.08
Nodes (10): {closure#14}(), {closure#17}(), {closure#21}(), {closure#25}(), {closure#27}(), {closure#28}(), {closure#29}(), {closure#30}() (+2 more)

### Community 1 - "User"
Cohesion: 0.05
Nodes (8): {closure#1}(), {closure#2}(), User, UserPolicy, LoginSessionFactory, UserFactory, DatabaseSeeder, {closure#2}()

### Community 2 - "SitePanelProvider.php"
Cohesion: 0.06
Nodes (11): RedirectGuestsToSiteLogin, AdminPanelProvider, {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), OperatorPanelProvider, {closure#2}() (+3 more)

### Community 3 - "Розгортання на Ubuntu VPS"
Cohesion: 0.05
Nodes (39): Compose-файли, Docker-архітектура, Nginx і forwarded headers, Внутрішні та host-порти, Дані та файлові системи, Мережі, Сервіси та образи, Шляхи HTTP-запиту (+31 more)

### Community 4 - "DeviceDetectionService"
Cohesion: 0.09
Nodes (13): StartLoginSession, AdvertiseDeviceClientHints, TrackLoginSession, RecordLogin, DeviceDetectionService, {closure#1}(), {closure#2}(), {closure#3}() (+5 more)

### Community 5 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.09
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+5 more)

### Community 6 - "AppServiceProvider.php"
Cohesion: 0.08
Nodes (8): EditUser, {closure#2}(), UsersTable, FilamentLoginResponse, FilamentLogoutResponse, AppServiceProvider, {closure#1}(), {closure#3}()

### Community 7 - "package.json"
Cohesion: 0.07
Nodes (30): dependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, vite-plus, optionalDependencies (+22 more)

### Community 8 - "Filament\Facades\Filament"
Cohesion: 0.12
Nodes (3): DeveloperAccountWidget, DeveloperFilamentInfoWidget, SystemOverview

### Community 12 - "LoginHistoryTable.php"
Cohesion: 0.05
Nodes (31): TerminateOtherSessions, LoginSessionStatus, Active, Current, Expired, SignedOut, Terminated, {closure#1}() (+23 more)

### Community 13 - "Docker networking: звичайна та ізольована схема"
Cohesion: 0.13
Nodes (14): DBeaver через SSH, Docker networking: звичайна та ізольована схема, Важливий trade-off, Варіант 1: одна звичайна `default` network, Варіант 2: розділені мережі та приватний backend, Додаткові матеріали, Недоліки, Особливість host port publishing (+6 more)

### Community 14 - "LoginSessionEndReason"
Cohesion: 0.40
Nodes (3): LoginSessionEndReason, LoggedOut, Terminated

### Community 16 - "Експлуатація production-середовища"
Cohesion: 0.14
Nodes (14): 1. Конфігурація Docker Compose, 2. Контейнери та healthchecks, 3. Laravel, 4. Приватний upstream, 5. Системний Nginx, 6. Firewall, DNS і TLS, Видалення одного проєкту, Відновлення MariaDB (+6 more)

### Community 18 - "UserResource.php"
Cohesion: 0.05
Nodes (9): CreateUser, ListUsers, ViewUser, UserForm, UserResource, ActiveSessions, EditProfile, SessionHistory (+1 more)

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
Nodes (12): UserRole, Admin, Developer, Operator, User, {closure#1}(), {closure#13}(), {closure#15}() (+4 more)

## Knowledge Gaps
- **18 isolated node(s):** `LoggedOut`, `Terminated`, `Current`, `Active`, `Expired` (+13 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 337 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **27 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Work-memory lessons

**Preferred sources** — corroborated by past sessions; start here.
- `AdminPanelProvider.php` (2× useful, score=1.223979915)
- `Filament\Pages\Dashboard` (2× useful, score=1.223979915)

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User` to `PanelAccessTest.php`, `SitePanelProvider.php`, `AppServiceProvider.php`, `Filament\Facades\Filament`, `LoginSessionTrackingTest.php`, `ActiveSessionsTest.php`, `RememberMeTest.php`, `UserResource.php`, `AdminPanelTest.php`?**
  _High betweenness centrality (0.090) - this node is a cross-community bridge._
- **What connects `LoggedOut`, `Terminated`, `Current` to the rest of the system?**
  _18 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `PanelAccessTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.08333333333333333 - nodes in this community are weakly interconnected._
- **Why does `UserRole` connect `UserRole` to `PanelAccessTest.php`, `User`, `SitePanelProvider.php`, `AppServiceProvider.php`, `Filament\Facades\Filament`, `UserResource.php`, `AdminPanelTest.php`, `Filament\Support\Colors\Color`?**
  _High betweenness centrality (0.088) - this node is a cross-community bridge._
- **Should `User` be split into smaller, more focused modules?**
  _Cohesion score 0.05117845117845118 - nodes in this community are weakly interconnected._
- **Why does `LoginSession` connect `LoginHistoryTable.php` to `User`, `DeviceDetectionService`, `LoginSessionTrackingTest.php`, `ActiveSessionsTest.php`, `LoginSessionEndReason`, `TerminateOtherSessions.php`?**
  _High betweenness centrality (0.057) - this node is a cross-community bridge._
- **Should `SitePanelProvider.php` be split into smaller, more focused modules?**
  _Cohesion score 0.06493506493506493 - nodes in this community are weakly interconnected._