# Graph Report - laradocker  (2026-11-09)

## Corpus Check
- 91 files · ~19,496 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 32 file(s) not represented in the graph (top: (none) 14, .example 6, .conf 4)

## Summary
- 637 nodes · 988 edges · 47 communities (22 shown, 25 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 12 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `c2642974`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Розгортання на Ubuntu VPS
- User
- package.json
- composer.json
- ViewUser.php
- Illuminate\Database\Schema\Blueprint
- UserForm.php
- EditProfile
- UserFactory.php
- scripts
- ActiveSessionsTest.php
- StartLoginSession
- Pest.php
- console.php
- artisan
- Feature/ExampleTest.php
- Експлуатація production-середовища
- composer.json
- entrypoint.sh
- UserResource.php
- UserForm.php
- Filament\Tables\Table
- require-dev
- LoginSession
- DeviceDetectionService
- require
- config
- Filament\Facades\Filament
- psr-4
- extra
- Pages/ActiveSessions.php

## God Nodes (most connected - your core abstractions)
1. `UserRole` - 51 edges
2. `User` - 33 edges
3. `LoginSession` - 28 edges
4. `LoginSessionStatus` - 19 edges
5. `UserResource` - 14 edges
6. `scripts` - 13 edges
7. `EditProfile` - 12 edges
8. `require-dev` - 12 edges
9. `DeviceDetectionService` - 10 edges
10. `Розгортання на Ubuntu VPS` - 10 edges

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

## Communities (47 total, 25 thin omitted)

### Community 0 - "Розгортання на Ubuntu VPS"
Cohesion: 0.05
Nodes (39): Compose-файли, Docker-архітектура, Nginx і forwarded headers, Внутрішні та host-порти, Дані та файлові системи, Мережі, Сервіси та образи, Шляхи HTTP-запиту (+31 more)

### Community 1 - "User"
Cohesion: 0.08
Nodes (4): User, UserPolicy, DatabaseSeeder, {closure#2}()

### Community 2 - "package.json"
Cohesion: 0.06
Nodes (11): RedirectGuestsToSiteLogin, AdminPanelProvider, {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), OperatorPanelProvider, {closure#2}() (+3 more)

### Community 3 - "composer.json"
Cohesion: 0.06
Nodes (31): UserRole, Admin, Developer, Operator, User, {closure#1}(), {closure#10}(), {closure#11}() (+23 more)

### Community 5 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.07
Nodes (28): dependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, vite-plus, optionalDependencies (+20 more)

### Community 6 - "UserForm.php"
Cohesion: 0.10
Nodes (11): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+3 more)

### Community 8 - "UserFactory.php"
Cohesion: 0.16
Nodes (4): FilamentLoginResponse, FilamentLogoutResponse, AppServiceProvider, {closure#1}()

### Community 10 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, ci:check, dev, lint, lint:check, post-autoload-dump, post-create-project-cmd, post-root-package-install (+5 more)

### Community 11 - "ActiveSessionsTest.php"
Cohesion: 0.08
Nodes (7): EndLoginSession, TerminateOtherSessions, LoginSessionEndReason, LoggedOut, Terminated, RecordLogout, {closure#2}()

### Community 12 - "StartLoginSession"
Cohesion: 0.13
Nodes (7): StartLoginSession, TrackLoginSession, RecordLogin, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}()

### Community 15 - "console.php"
Cohesion: 0.40
Nodes (4): Answer, Outcome, Q: Чи є у Filament віджети або картки для Dashboard як на скриншоті?, Source Nodes

### Community 16 - "artisan"
Cohesion: 0.40
Nodes (4): Answer, Outcome, Q: Зробити кастомний Dashboard у стилі карток замість стандартного Filament Dashboard, Source Nodes

### Community 30 - "Експлуатація production-середовища"
Cohesion: 0.14
Nodes (14): 1. Конфігурація Docker Compose, 2. Контейнери та healthchecks, 3. Laravel, 4. Приватний upstream, 5. Системний Nginx, 6. Firewall, DNS і TLS, Видалення одного проєкту, Відновлення MariaDB (+6 more)

### Community 31 - "composer.json"
Cohesion: 0.17
Nodes (11): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+3 more)

### Community 36 - "UserResource.php"
Cohesion: 0.21
Nodes (4): CreateUser, {closure#1}(), {closure#2}(), UserResource

### Community 41 - "require-dev"
Cohesion: 0.17
Nodes (12): require-dev, fakerphp/faker, larastan/larastan, laravel/boost, laravel/pail, laravel/pao, laravel/pint, laravel/sail (+4 more)

### Community 42 - "LoginSession"
Cohesion: 0.06
Nodes (21): LoginSessionStatus, Active, Current, Expired, SignedOut, Terminated, DeviceColumn, ActiveSessionsTable (+13 more)

### Community 43 - "DeviceDetectionService"
Cohesion: 0.08
Nodes (8): {closure#1}(), {closure#2}(), DeviceDetectionService, LoginSessionFactory, UserFactory, {closure#1}(), {closure#2}(), {closure#3}()

### Community 45 - "require"
Cohesion: 0.25
Nodes (8): require, filament/filament, laravel/framework, laravel/tinker, livewire/blaze, livewire/livewire, matomo/device-detector, php

### Community 46 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 49 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 50 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

## Knowledge Gaps
- **18 isolated node(s):** `LoggedOut`, `Terminated`, `Current`, `Active`, `Expired` (+13 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 294 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **25 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Work-memory lessons

**Preferred sources** — corroborated by past sessions; start here.
- `AdminPanelProvider.php` (2× useful, score=1.799570691) _(code changed — re-verify)_
- `Filament\Pages\Dashboard` (2× useful, score=1.799570691)

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `UserRole` connect `composer.json` to `User`, `package.json`, `UserResource.php`, `UserForm.php`, `UserFactory.php`, `DeviceDetectionService`, `Filament\Facades\Filament`?**
  _High betweenness centrality (0.089) - this node is a cross-community bridge._
- **What connects `LoggedOut`, `Terminated`, `Current` to the rest of the system?**
  _18 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Розгортання на Ubuntu VPS` be split into smaller, more focused modules?**
  _Cohesion score 0.051515151515151514 - nodes in this community are weakly interconnected._
- **Why does `User` connect `User` to `package.json`, `composer.json`, `UserResource.php`, `LoginSession`, `DeviceDetectionService`, `ActiveSessionsTest.php`, `Filament\Facades\Filament`?**
  _High betweenness centrality (0.087) - this node is a cross-community bridge._
- **Should `User` be split into smaller, more focused modules?**
  _Cohesion score 0.07957957957957958 - nodes in this community are weakly interconnected._
- **Why does `LoginSession` connect `LoginSession` to `DeviceDetectionService`, `ActiveSessionsTest.php`, `StartLoginSession`, `User`?**
  _High betweenness centrality (0.068) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.06493506493506493 - nodes in this community are weakly interconnected._