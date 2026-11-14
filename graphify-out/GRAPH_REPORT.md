# Graph Report - laradocker  (2026-11-14)

## Corpus Check
- 97 files · ~21,568 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 32 file(s) not represented in the graph (top: (none) 14, .example 6, .conf 4)

## Summary
- 693 nodes · 1067 edges · 56 communities (29 shown, 27 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 10 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `38618fa6`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Розгортання на Ubuntu VPS
- User
- package.json
- EditProfile
- UserResource.php
- UserResource.php
- UserForm.php
- Filament\Schemas\Schema
- UserFactory.php
- ActiveSessionsTable.php
- LoginSession
- LoginSessionTrackingTest.php
- ActiveSessionsTest.php
- Filament\Tables\Table
- console.php
- Filament\Schemas\Schema
- Controller.php
- Illuminate\Support\Facades\Route
- Filament\Facades\Filament
- UserForm.php
- Filament\Schemas\Schema
- require-dev
- LoginSessionStatusContext
- queue.php
- ActiveSessionsTable.php
- EditProfile.php
- Q: Чи є у Filament віджети або картки для Dashboard як на скриншоті?
- date_formats.php
- Pest.php
- require
- entrypoint.sh
- Q: Зробити кастомний Dashboard у стилі карток замість стандартного Filament Dashboard
- Filament\Tables\Table
- LoginHistoryTable.php
- SystemOverview.php
- brand-logo.blade.php
- Docker networking: звичайна та ізольована схема
- ActiveSessionsTable

## God Nodes (most connected - your core abstractions)
1. `UserRole` - 53 edges
2. `User` - 34 edges
3. `LoginSession` - 27 edges
4. `LoginSessionStatus` - 22 edges
5. `UserResource` - 14 edges
6. `scripts` - 13 edges
7. `EditProfile` - 12 edges
8. `require-dev` - 12 edges
9. `DeviceDetectionService` - 11 edges
10. `StartLoginSession` - 10 edges

## Surprising Connections (you probably didn't know these)
- `{closure#1}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/AdminPanelTest.php → app/Enums/UserRole.php
- `{closure#10}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/AdminPanelTest.php → app/Enums/UserRole.php
- `{closure#11}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/AdminPanelTest.php → app/Enums/UserRole.php
- `{closure#2}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/AdminPanelTest.php → app/Enums/UserRole.php
- `{closure#3}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/AdminPanelTest.php → app/Enums/UserRole.php

## Import Cycles
- None detected.

## Communities (56 total, 27 thin omitted)

### Community 0 - "Розгортання на Ubuntu VPS"
Cohesion: 0.05
Nodes (6): User, UserPolicy, LoginSessionFactory, UserFactory, DatabaseSeeder, {closure#2}()

### Community 1 - "User"
Cohesion: 0.07
Nodes (12): RedirectGuestsToSiteLogin, TrackLoginSession, AdminPanelProvider, {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), OperatorPanelProvider (+4 more)

### Community 2 - "package.json"
Cohesion: 0.06
Nodes (31): UserRole, Admin, Developer, Operator, User, {closure#1}(), {closure#10}(), {closure#11}() (+23 more)

### Community 3 - "EditProfile"
Cohesion: 0.05
Nodes (39): Compose-файли, Docker-архітектура, Nginx і forwarded headers, Внутрішні та host-порти, Дані та файлові системи, Мережі, Сервіси та образи, Шляхи HTTP-запиту (+31 more)

### Community 4 - "UserResource.php"
Cohesion: 0.09
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+5 more)

### Community 5 - "UserResource.php"
Cohesion: 0.10
Nodes (11): StartLoginSession, RecordLogin, DeviceDetectionService, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#1}() (+3 more)

### Community 6 - "UserForm.php"
Cohesion: 0.07
Nodes (30): dependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, vite-plus, optionalDependencies (+22 more)

### Community 7 - "Filament\Schemas\Schema"
Cohesion: 0.12
Nodes (3): DeveloperAccountWidget, DeveloperFilamentInfoWidget, SystemOverview

### Community 8 - "UserFactory.php"
Cohesion: 0.15
Nodes (4): FilamentLoginResponse, FilamentLogoutResponse, AppServiceProvider, {closure#3}()

### Community 9 - "ActiveSessionsTable.php"
Cohesion: 0.27
Nodes (5): TerminateOtherSessions, DeviceColumn, {closure#1}(), {closure#2}(), {closure#4}()

### Community 10 - "LoginSession"
Cohesion: 0.24
Nodes (7): {closure#1}(), {closure#10}(), {closure#2}(), {closure#3}(), {closure#6}(), {closure#7}(), {closure#8}()

### Community 14 - "Filament\Tables\Table"
Cohesion: 0.14
Nodes (14): 1. Конфігурація Docker Compose, 2. Контейнери та healthchecks, 3. Laravel, 4. Приватний upstream, 5. Системний Nginx, 6. Firewall, DNS і TLS, Видалення одного проєкту, Відновлення MariaDB (+6 more)

### Community 15 - "console.php"
Cohesion: 0.20
Nodes (8): LoginSessionStatus, Active, Current, Expired, SignedOut, Terminated, {closure#11}(), {closure#12}()

### Community 16 - "Filament\Schemas\Schema"
Cohesion: 0.19
Nodes (4): CreateUser, {closure#1}(), {closure#2}(), UserResource

### Community 17 - "Controller.php"
Cohesion: 0.15
Nodes (13): scripts, ci:check, dev, lint, lint:check, post-autoload-dump, post-create-project-cmd, post-root-package-install (+5 more)

### Community 18 - "Illuminate\Support\Facades\Route"
Cohesion: 0.17
Nodes (11): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+3 more)

### Community 19 - "Filament\Facades\Filament"
Cohesion: 0.17
Nodes (12): require-dev, fakerphp/faker, larastan/larastan, laravel/boost, laravel/pail, laravel/pao, laravel/pint, laravel/sail (+4 more)

### Community 21 - "Filament\Schemas\Schema"
Cohesion: 0.21
Nodes (3): {closure#1}(), {closure#2}(), LoginSession

### Community 22 - "require-dev"
Cohesion: 0.18
Nodes (3): LoginSessionEndReason, LoggedOut, Terminated

### Community 24 - "queue.php"
Cohesion: 0.21
Nodes (3): EditUser, UsersTable, {closure#1}()

### Community 26 - "EditProfile.php"
Cohesion: 0.33
Nodes (5): {closure#3}(), {closure#9}(), LoginSessionStatusContext, LoginSessionStatusResolver, {closure#16}()

### Community 27 - "Q: Чи є у Filament віджети або картки для Dashboard як на скриншоті?"
Cohesion: 0.25
Nodes (8): require, filament/filament, laravel/framework, laravel/tinker, livewire/blaze, livewire/livewire, matomo/device-detector, php

### Community 28 - "date_formats.php"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 30 - "require"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 32 - "entrypoint.sh"
Cohesion: 0.40
Nodes (4): Answer, Outcome, Q: Чи є у Filament віджети або картки для Dashboard як на скриншоті?, Source Nodes

### Community 33 - "Q: Зробити кастомний Dashboard у стилі карток замість стандартного Filament Dashboard"
Cohesion: 0.40
Nodes (4): Answer, Outcome, Q: Зробити кастомний Dashboard у стилі карток замість стандартного Filament Dashboard, Source Nodes

### Community 37 - "LoginHistoryTable.php"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 54 - "Docker networking: звичайна та ізольована схема"
Cohesion: 0.13
Nodes (14): DBeaver через SSH, Docker networking: звичайна та ізольована схема, Важливий trade-off, Варіант 1: одна звичайна `default` network, Варіант 2: розділені мережі та приватний backend, Додаткові матеріали, Недоліки, Особливість host port publishing (+6 more)

### Community 55 - "ActiveSessionsTable"
Cohesion: 0.22
Nodes (3): ActiveSessionsTable, LoginHistoryTable, {closure#17}()

## Knowledge Gaps
- **18 isolated node(s):** `LoggedOut`, `Terminated`, `Current`, `Active`, `Expired` (+13 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 324 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **27 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Work-memory lessons

**Preferred sources** — corroborated by past sessions; start here.
- `AdminPanelProvider.php` (2× useful, score=1.799570691) _(code changed — re-verify)_
- `Filament\Pages\Dashboard` (2× useful, score=1.799570691)

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `Розгортання на Ubuntu VPS` to `User`, `package.json`, `Filament\Schemas\Schema`, `User`, `LoginSessionTrackingTest.php`, `Filament\Schemas\Schema`, `Filament\Schemas\Schema`?**
  _High betweenness centrality (0.088) - this node is a cross-community bridge._
- **What connects `LoggedOut`, `Terminated`, `Current` to the rest of the system?**
  _18 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Розгортання на Ubuntu VPS` be split into smaller, more focused modules?**
  _Cohesion score 0.0517120894479385 - nodes in this community are weakly interconnected._
- **Why does `UserRole` connect `package.json` to `Розгортання на Ubuntu VPS`, `User`, `Filament\Schemas\Schema`, `UserFactory.php`, `Filament\Schemas\Schema`, `UserForm.php`?**
  _High betweenness centrality (0.082) - this node is a cross-community bridge._
- **Should `User` be split into smaller, more focused modules?**
  _Cohesion score 0.06704260651629072 - nodes in this community are weakly interconnected._
- **Why does `LoginSession` connect `Filament\Schemas\Schema` to `Розгортання на Ubuntu VPS`, `UserResource.php`, `ActiveSessionsTable.php`, `LoginSession`, `User`, `LoginSessionTrackingTest.php`, `console.php`, `require-dev`, `EditProfile.php`?**
  _High betweenness centrality (0.061) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.059506531204644414 - nodes in this community are weakly interconnected._