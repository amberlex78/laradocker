# Graph Report - laradocker  (2026-10-29)

## Corpus Check
- 74 files · ~17,442 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 31 file(s) not represented in the graph (top: (none) 14, .example 6, .ini 4)

## Summary
- 532 nodes · 763 edges · 35 communities (16 shown, 19 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 5 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `ef554f96`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Розгортання на Ubuntu VPS
- User
- package.json
- composer.json
- OperatorPanelProvider.php
- Illuminate\Database\Schema\Blueprint
- UserForm.php
- scripts
- UserFactory.php
- composer.json
- SystemOverviewTest.php
- SystemOverview
- Pest.php
- console.php
- artisan
- Feature/ExampleTest.php
- ActiveSessions.php
- Експлуатація production-середовища
- require-dev
- entrypoint.sh

## God Nodes (most connected - your core abstractions)
1. `UserRole` - 49 edges
2. `User` - 30 edges
3. `UserResource` - 14 edges
4. `EditProfile` - 14 edges
5. `UserSession` - 14 edges
6. `scripts` - 13 edges
7. `require-dev` - 12 edges
8. `DeviceDetectionService` - 10 edges
9. `Розгортання на Ubuntu VPS` - 10 edges
10. `SystemOverview` - 9 edges

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

## Communities (35 total, 19 thin omitted)

### Community 0 - "Розгортання на Ubuntu VPS"
Cohesion: 0.05
Nodes (38): Compose-файли, Docker-архітектура, Внутрішні та host-порти, Дані та файлові системи, Мережі, Сервіси та образи, Шляхи HTTP-запиту, Cloudflare networks і client IP (+30 more)

### Community 1 - "User"
Cohesion: 0.07
Nodes (5): User, UserPolicy, UserFactory, DatabaseSeeder, {closure#2}()

### Community 2 - "package.json"
Cohesion: 0.07
Nodes (7): RedirectGuestsToSiteLogin, AdminPanelProvider, {closure#1}(), {closure#1}(), OperatorPanelProvider, {closure#4}(), SitePanelProvider

### Community 3 - "composer.json"
Cohesion: 0.07
Nodes (29): UserRole, Admin, Developer, Operator, User, {closure#1}(), {closure#10}(), {closure#11}() (+21 more)

### Community 4 - "OperatorPanelProvider.php"
Cohesion: 0.08
Nodes (8): CreateUser, EditUser, ListUsers, ViewUser, UsersTable, {closure#1}(), {closure#2}(), UserResource

### Community 5 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.07
Nodes (28): dependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, vite-plus, optionalDependencies (+20 more)

### Community 6 - "UserForm.php"
Cohesion: 0.11
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 7 - "scripts"
Cohesion: 0.07
Nodes (3): TerminateOtherSessions, UserForm, EditProfile

### Community 8 - "UserFactory.php"
Cohesion: 0.16
Nodes (4): FilamentLoginResponse, FilamentLogoutResponse, AppServiceProvider, {closure#1}()

### Community 10 - "composer.json"
Cohesion: 0.04
Nodes (47): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+39 more)

### Community 12 - "SystemOverview"
Cohesion: 0.31
Nodes (4): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}()

### Community 15 - "console.php"
Cohesion: 0.40
Nodes (4): Answer, Outcome, Q: Чи є у Filament віджети або картки для Dashboard як на скриншоті?, Source Nodes

### Community 16 - "artisan"
Cohesion: 0.40
Nodes (4): Answer, Outcome, Q: Зробити кастомний Dashboard у стилі карток замість стандартного Filament Dashboard, Source Nodes

### Community 28 - "ActiveSessions.php"
Cohesion: 0.08
Nodes (12): ActiveSessions, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), UserSession, DeviceDetectionService (+4 more)

### Community 30 - "Експлуатація production-середовища"
Cohesion: 0.14
Nodes (14): 1. Конфігурація Docker Compose, 2. Контейнери та healthchecks, 3. Laravel, 4. Приватний upstream, 5. Системний Nginx, 6. Firewall, DNS і TLS, Видалення одного проєкту, Відновлення MariaDB (+6 more)

### Community 31 - "require-dev"
Cohesion: 0.17
Nodes (12): require-dev, fakerphp/faker, larastan/larastan, laravel/boost, laravel/pail, laravel/pao, laravel/pint, laravel/sail (+4 more)

## Knowledge Gaps
- **11 isolated node(s):** `User`, `Operator`, `Admin`, `Developer`, `concurrently` (+6 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 258 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **19 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Work-memory lessons

**Preferred sources** — corroborated by past sessions; start here.
- `AdminPanelProvider.php` (2× useful, score=1.799570691)
- `Filament\Pages\Dashboard` (2× useful, score=1.799570691)

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `UserRole` connect `composer.json` to `User`, `package.json`, `OperatorPanelProvider.php`, `scripts`, `UserFactory.php`, `SystemOverviewTest.php`?**
  _High betweenness centrality (0.116) - this node is a cross-community bridge._
- **What connects `User`, `Operator`, `Admin` to the rest of the system?**
  _11 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Розгортання на Ubuntu VPS` be split into smaller, more focused modules?**
  _Cohesion score 0.052854122621564484 - nodes in this community are weakly interconnected._
- **Why does `User` connect `User` to `composer.json`, `SystemOverviewTest.php`, `OperatorPanelProvider.php`, `ActiveSessions.php`?**
  _High betweenness centrality (0.060) - this node is a cross-community bridge._
- **Should `User` be split into smaller, more focused modules?**
  _Cohesion score 0.06553911205073996 - nodes in this community are weakly interconnected._
- **Why does `EditProfile` connect `scripts` to `package.json`, `composer.json`, `ActiveSessions.php`?**
  _High betweenness centrality (0.031) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.0693815987933635 - nodes in this community are weakly interconnected._