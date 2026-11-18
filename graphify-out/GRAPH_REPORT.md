# Graph Report - laradocker  (2026-11-18)

## Corpus Check
- 110 files · ~24,046 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 32 file(s) not represented in the graph (top: (none) 14, .example 6, .conf 4)

## Summary
- 774 nodes · 1233 edges · 69 communities (30 shown, 39 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 14 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `06863e60`
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
- LoginSessionTrackingTest.php
- PasswordResetTest.php
- ActiveSessionsTable.php
- Docker networking: звичайна та ізольована схема
- LoginSession
- LoginHistoryTable.php
- Експлуатація production-середовища
- LoginSessionStatus
- Filament\Facades\Filament
- LoginSessionStatusContext
- scripts
- LoginSessionFactory.php
- composer.json
- require-dev
- UserResource.php
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
- Filament\Schemas\Schema
- ViewUser.php
- UsersTable.php
- BackedEnum
- ViewProfile
- .request
- {closure#22}

## God Nodes (most connected - your core abstractions)
1. `UserRole` - 68 edges
2. `User` - 45 edges
3. `LoginSession` - 29 edges
4. `LoginSessionStatus` - 22 edges
5. `UserResource` - 14 edges
6. `ViewProfile` - 13 edges
7. `scripts` - 13 edges
8. `LoginSessionEndReason` - 12 edges
9. `DeviceDetectionService` - 12 edges
10. `require-dev` - 12 edges

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

## Communities (69 total, 39 thin omitted)

### Community 0 - "PanelAccessTest.php"
Cohesion: 0.09
Nodes (9): {closure#1}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#23}(), {closure#28}(), {closure#29}(), {closure#30}() (+1 more)

### Community 1 - "User"
Cohesion: 0.16
Nodes (5): {closure#1}(), {closure#2}(), User, UserPolicy, {closure#2}()

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
Cohesion: 0.08
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+5 more)

### Community 6 - "AppServiceProvider.php"
Cohesion: 0.14
Nodes (5): FilamentLoginResponse, FilamentLogoutResponse, AppServiceProvider, {closure#1}(), {closure#3}()

### Community 7 - "package.json"
Cohesion: 0.07
Nodes (30): dependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, vite-plus, optionalDependencies (+22 more)

### Community 8 - "Filament\Facades\Filament"
Cohesion: 0.12
Nodes (3): DeveloperAccountWidget, DeveloperFilamentInfoWidget, SystemOverview

### Community 9 - "LoginSessionTrackingTest.php"
Cohesion: 0.06
Nodes (8): EndLoginSession, TerminateOtherSessions, LoginSessionEndReason, LoggedOut, Terminated, RecordLogout, TerminateSessionsAfterPasswordReset, {closure#2}()

### Community 10 - "PasswordResetTest.php"
Cohesion: 0.07
Nodes (5): RequestPasswordReset, {closure#3}(), {closure#3}(), {closure#4}(), insertDatabaseSession()

### Community 12 - "ActiveSessionsTable.php"
Cohesion: 0.19
Nodes (4): ActiveSessionsTable, {closure#1}(), LoginHistoryTable, {closure#18}()

### Community 13 - "Docker networking: звичайна та ізольована схема"
Cohesion: 0.13
Nodes (14): DBeaver через SSH, Docker networking: звичайна та ізольована схема, Важливий trade-off, Варіант 1: одна звичайна `default` network, Варіант 2: розділені мережі та приватний backend, Додаткові матеріали, Недоліки, Особливість host port publishing (+6 more)

### Community 14 - "LoginSession"
Cohesion: 0.28
Nodes (6): {closure#1}(), {closure#2}(), DeviceColumn, {closure#4}(), {closure#13}(), LoginSession

### Community 15 - "LoginHistoryTable.php"
Cohesion: 0.24
Nodes (7): {closure#1}(), {closure#11}(), {closure#2}(), {closure#3}(), {closure#7}(), {closure#8}(), {closure#9}()

### Community 16 - "Експлуатація production-середовища"
Cohesion: 0.14
Nodes (14): 1. Конфігурація Docker Compose, 2. Контейнери та healthchecks, 3. Laravel, 4. Приватний upstream, 5. Системний Nginx, 6. Firewall, DNS і TLS, Видалення одного проєкту, Відновлення MariaDB (+6 more)

### Community 17 - "LoginSessionStatus"
Cohesion: 0.17
Nodes (11): LoginSessionStatus, Active, Current, Expired, SignedOut, Terminated, {closure#3}(), {closure#10}() (+3 more)

### Community 21 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, ci:check, dev, lint, lint:check, post-autoload-dump, post-create-project-cmd, post-root-package-install (+5 more)

### Community 23 - "composer.json"
Cohesion: 0.17
Nodes (11): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+3 more)

### Community 24 - "require-dev"
Cohesion: 0.17
Nodes (12): require-dev, fakerphp/faker, larastan/larastan, laravel/boost, laravel/pail, laravel/pao, laravel/pint, laravel/sail (+4 more)

### Community 26 - "UserResource.php"
Cohesion: 0.21
Nodes (3): CreateUser, UsersTable, UserResource

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
Nodes (12): UserRole, Admin, Developer, Operator, User, {closure#17}(), {closure#21}(), {closure#26}() (+4 more)

## Knowledge Gaps
- **18 isolated node(s):** `LoggedOut`, `Terminated`, `Current`, `Active`, `Expired` (+13 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 355 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **39 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Work-memory lessons

**Preferred sources** — corroborated by past sessions; start here.
- `AdminPanelProvider.php` (2× useful, score=1.223979915) _(code changed — re-verify)_
- `Filament\Pages\Dashboard` (2× useful, score=1.223979915)

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User` to `PanelAccessTest.php`, `SitePanelProvider.php`, `ExistingUserEmailVerificationMigrationTest.php`, `DatabaseSeeder.php`, `Filament\Facades\Filament`, `LoginSessionTrackingTest.php`, `PasswordResetTest.php`, `ActiveSessionsTest.php`, `Filament\Facades\Filament`, `User.php`, `LoginSessionFactory.php`, `AdminPanelTest.php`, `UserResource.php`, `UserFactory.php`, `UsersTable.php`?**
  _High betweenness centrality (0.109) - this node is a cross-community bridge._
- **What connects `LoggedOut`, `Terminated`, `Current` to the rest of the system?**
  _18 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `PanelAccessTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.09057971014492754 - nodes in this community are weakly interconnected._
- **Why does `UserRole` connect `UserRole` to `PanelAccessTest.php`, `User`, `SitePanelProvider.php`, `.request`, `DatabaseSeeder.php`, `{closure#22}`, `AppServiceProvider.php`, `Filament\Facades\Filament`, `PasswordResetTest.php`, `Filament\Facades\Filament`, `User.php`, `AdminPanelTest.php`, `UserResource.php`, `Filament\Support\Colors\Color`, `UserFactory.php`, `Filament\Schemas\Schema`, `UsersTable.php`?**
  _High betweenness centrality (0.093) - this node is a cross-community bridge._
- **Should `SitePanelProvider.php` be split into smaller, more focused modules?**
  _Cohesion score 0.06493506493506493 - nodes in this community are weakly interconnected._
- **Why does `LoginSession` connect `LoginSession` to `DeviceDetectionService`, `LoginSessionTrackingTest.php`, `PasswordResetTest.php`, `ActiveSessionsTest.php`, `ActiveSessionsTable.php`, `LoginHistoryTable.php`, `LoginSessionStatus`, `LoginSessionStatusContext`, `User.php`, `LoginSessionFactory.php`?**
  _High betweenness centrality (0.058) - this node is a cross-community bridge._
- **Should `Розгортання на Ubuntu VPS` be split into smaller, more focused modules?**
  _Cohesion score 0.051515151515151514 - nodes in this community are weakly interconnected._