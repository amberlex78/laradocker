# Graph Report - laradocker  (2026-11-10)

## Corpus Check
- 94 files · ~20,237 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 32 file(s) not represented in the graph (top: (none) 14, .example 6, .conf 4)

## Summary
- 668 nodes · 1040 edges · 58 communities (26 shown, 32 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 10 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `c059ffac`
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
- ViewUser.php
- ActiveSessionsTest.php
- ActiveSessionsTest.php
- EditUser.php
- console.php
- Filament\Schemas\Schema
- Controller.php
- Illuminate\Support\Facades\Route
- Filament\Tables\Table
- UserForm.php
- Filament\Schemas\Schema
- require-dev
- LoginSessionStatusContext
- queue.php
- services.php
- app.js
- Q: Чи є у Filament віджети або картки для Dashboard як на скриншоті?
- require
- composer.json
- entrypoint.sh
- ProxySchemeTest.php
- Filament\Tables\Table
- TerminateOtherSessions.php
- ActiveSessionsTable.php
- LoginSessionStatusContext
- SystemOverview.php
- brand-logo.blade.php
- DeviceDetectionService
- require
- config

## God Nodes (most connected - your core abstractions)
1. `UserRole` - 53 edges
2. `User` - 33 edges
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

## Communities (58 total, 32 thin omitted)

### Community 0 - "Розгортання на Ubuntu VPS"
Cohesion: 0.06
Nodes (11): RedirectGuestsToSiteLogin, AdminPanelProvider, {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), OperatorPanelProvider, {closure#2}() (+3 more)

### Community 1 - "User"
Cohesion: 0.06
Nodes (31): UserRole, Admin, Developer, Operator, User, {closure#1}(), {closure#10}(), {closure#11}() (+23 more)

### Community 2 - "package.json"
Cohesion: 0.05
Nodes (39): Compose-файли, Docker-архітектура, Nginx і forwarded headers, Внутрішні та host-порти, Дані та файлові системи, Мережі, Сервіси та образи, Шляхи HTTP-запиту (+31 more)

### Community 3 - "EditProfile"
Cohesion: 0.09
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+5 more)

### Community 4 - "UserResource.php"
Cohesion: 0.10
Nodes (12): StartLoginSession, TrackLoginSession, RecordLogin, DeviceDetectionService, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+4 more)

### Community 5 - "UserResource.php"
Cohesion: 0.07
Nodes (30): dependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, vite-plus, optionalDependencies (+22 more)

### Community 6 - "UserForm.php"
Cohesion: 0.11
Nodes (3): DeveloperAccountWidget, DeveloperFilamentInfoWidget, SystemOverview

### Community 7 - "Filament\Schemas\Schema"
Cohesion: 0.16
Nodes (4): FilamentLoginResponse, FilamentLogoutResponse, AppServiceProvider, {closure#1}()

### Community 8 - "UserFactory.php"
Cohesion: 0.08
Nodes (7): EndLoginSession, TerminateOtherSessions, LoginSessionEndReason, LoggedOut, Terminated, RecordLogout, {closure#2}()

### Community 9 - "ViewUser.php"
Cohesion: 0.20
Nodes (8): LoginSessionStatus, Active, Current, Expired, SignedOut, Terminated, {closure#10}(), {closure#11}()

### Community 11 - "ActiveSessionsTest.php"
Cohesion: 0.19
Nodes (3): User, UserPolicy, {closure#2}()

### Community 13 - "ActiveSessionsTest.php"
Cohesion: 0.14
Nodes (14): 1. Конфігурація Docker Compose, 2. Контейнери та healthchecks, 3. Laravel, 4. Приватний upstream, 5. Системний Nginx, 6. Firewall, DNS і TLS, Видалення одного проєкту, Відновлення MariaDB (+6 more)

### Community 15 - "console.php"
Cohesion: 0.19
Nodes (4): CreateUser, {closure#1}(), {closure#2}(), UserResource

### Community 17 - "Controller.php"
Cohesion: 0.20
Nodes (4): DeviceColumn, ActiveSessionsTable, LoginHistoryTable, {closure#15}()

### Community 18 - "Illuminate\Support\Facades\Route"
Cohesion: 0.15
Nodes (13): scripts, ci:check, dev, lint, lint:check, post-autoload-dump, post-create-project-cmd, post-root-package-install (+5 more)

### Community 20 - "UserForm.php"
Cohesion: 0.26
Nodes (6): {closure#1}(), {closure#2}(), {closure#3}(), {closure#6}(), {closure#7}(), {closure#8}()

### Community 21 - "Filament\Schemas\Schema"
Cohesion: 0.17
Nodes (11): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+3 more)

### Community 22 - "require-dev"
Cohesion: 0.17
Nodes (12): require-dev, fakerphp/faker, larastan/larastan, laravel/boost, laravel/pail, laravel/pao, laravel/pint, laravel/sail (+4 more)

### Community 23 - "LoginSessionStatusContext"
Cohesion: 0.33
Nodes (4): {closure#9}(), LoginSessionStatusContext, LoginSessionStatusResolver, {closure#14}()

### Community 25 - "services.php"
Cohesion: 0.25
Nodes (6): {closure#1}(), {closure#2}(), {closure#1}(), {closure#3}(), {closure#4}(), LoginSession

### Community 30 - "require"
Cohesion: 0.25
Nodes (8): require, filament/filament, laravel/framework, laravel/tinker, livewire/blaze, livewire/livewire, matomo/device-detector, php

### Community 32 - "entrypoint.sh"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 36 - "ActiveSessionsTable.php"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 39 - "SystemOverview.php"
Cohesion: 0.40
Nodes (4): Answer, Outcome, Q: Чи є у Filament віджети або картки для Dashboard як на скриншоті?, Source Nodes

### Community 40 - "brand-logo.blade.php"
Cohesion: 0.40
Nodes (4): Answer, Outcome, Q: Зробити кастомний Dashboard у стилі карток замість стандартного Filament Dashboard, Source Nodes

### Community 43 - "DeviceDetectionService"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

## Knowledge Gaps
- **18 isolated node(s):** `LoggedOut`, `Terminated`, `Current`, `Active`, `Expired` (+13 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 307 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **32 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Work-memory lessons

**Preferred sources** — corroborated by past sessions; start here.
- `AdminPanelProvider.php` (2× useful, score=1.799570691) _(code changed — re-verify)_
- `Filament\Pages\Dashboard` (2× useful, score=1.799570691)

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `UserRole` connect `User` to `Розгортання на Ubuntu VPS`, `ProxySchemeTest.php`, `UserForm.php`, `Filament\Schemas\Schema`, `LoginSessionStatusContext`, `scripts`, `ActiveSessionsTest.php`, `console.php`, `Q: Чи є у Filament віджети або картки для Dashboard як на скриншоті?`?**
  _High betweenness centrality (0.087) - this node is a cross-community bridge._
- **What connects `LoggedOut`, `Terminated`, `Current` to the rest of the system?**
  _18 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Розгортання на Ubuntu VPS` be split into smaller, more focused modules?**
  _Cohesion score 0.06493506493506493 - nodes in this community are weakly interconnected._
- **Why does `User` connect `ActiveSessionsTest.php` to `Розгортання на Ubuntu VPS`, `ProxySchemeTest.php`, `User`, `UserForm.php`, `LoginSessionStatusContext`, `UserFactory.php`, `scripts`, `LoginSessionTrackingTest.php`, `console.php`, `app.js`?**
  _High betweenness centrality (0.086) - this node is a cross-community bridge._
- **Should `User` be split into smaller, more focused modules?**
  _Cohesion score 0.06009783368273934 - nodes in this community are weakly interconnected._
- **Why does `LoginSession` connect `services.php` to `UserResource.php`, `UserFactory.php`, `ViewUser.php`, `scripts`, `LoginSessionTrackingTest.php`, `UserForm.php`, `LoginSessionStatusContext`, `app.js`?**
  _High betweenness centrality (0.064) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.051515151515151514 - nodes in this community are weakly interconnected._