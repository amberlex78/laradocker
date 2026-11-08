# Graph Report - laradocker  (2026-11-08)

## Corpus Check
- 83 files · ~18,791 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 32 file(s) not represented in the graph (top: (none) 14, .example 6, .conf 4)

## Summary
- 600 nodes · 901 edges · 40 communities (16 shown, 24 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 11 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `d4e07509`
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
- LoginSession
- Експлуатація production-середовища
- composer.json
- entrypoint.sh
- TerminateOtherSessions.php
- EditProfile.php
- UserResource.php
- EditUser.php

## God Nodes (most connected - your core abstractions)
1. `UserRole` - 50 edges
2. `User` - 33 edges
3. `LoginSession` - 28 edges
4. `UserResource` - 14 edges
5. `EditProfile` - 14 edges
6. `DeviceDetectionService` - 13 edges
7. `scripts` - 13 edges
8. `require-dev` - 12 edges
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

## Communities (40 total, 24 thin omitted)

### Community 0 - "Розгортання на Ubuntu VPS"
Cohesion: 0.05
Nodes (39): Compose-файли, Docker-архітектура, Nginx і forwarded headers, Внутрішні та host-порти, Дані та файлові системи, Мережі, Сервіси та образи, Шляхи HTTP-запиту (+31 more)

### Community 1 - "User"
Cohesion: 0.05
Nodes (6): User, UserPolicy, LoginSessionFactory, UserFactory, DatabaseSeeder, {closure#2}()

### Community 2 - "package.json"
Cohesion: 0.07
Nodes (9): AdminPanelProvider, {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), OperatorPanelProvider, {closure#2}(), {closure#5}() (+1 more)

### Community 3 - "composer.json"
Cohesion: 0.07
Nodes (30): UserRole, Admin, Developer, Operator, User, {closure#1}(), {closure#10}(), {closure#11}() (+22 more)

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

### Community 12 - "StartLoginSession"
Cohesion: 0.13
Nodes (7): StartLoginSession, TrackLoginSession, RecordLogin, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}()

### Community 15 - "console.php"
Cohesion: 0.40
Nodes (4): Answer, Outcome, Q: Чи є у Filament віджети або картки для Dashboard як на скриншоті?, Source Nodes

### Community 16 - "artisan"
Cohesion: 0.40
Nodes (4): Answer, Outcome, Q: Зробити кастомний Dashboard у стилі карток замість стандартного Filament Dashboard, Source Nodes

### Community 28 - "LoginSession"
Cohesion: 0.08
Nodes (19): UsersTable, ActiveSessions, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#1}() (+11 more)

### Community 30 - "Експлуатація production-середовища"
Cohesion: 0.14
Nodes (14): 1. Конфігурація Docker Compose, 2. Контейнери та healthchecks, 3. Laravel, 4. Приватний upstream, 5. Системний Nginx, 6. Firewall, DNS і TLS, Видалення одного проєкту, Відновлення MariaDB (+6 more)

### Community 31 - "composer.json"
Cohesion: 0.04
Nodes (46): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+38 more)

### Community 34 - "TerminateOtherSessions.php"
Cohesion: 0.17
Nodes (4): LoginSessionEndReason, LoggedOut, Terminated, RecordLogout

### Community 36 - "UserResource.php"
Cohesion: 0.21
Nodes (4): CreateUser, {closure#1}(), {closure#2}(), UserResource

## Knowledge Gaps
- **13 isolated node(s):** `LoggedOut`, `Terminated`, `User`, `Operator`, `Admin` (+8 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 280 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **24 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Work-memory lessons

**Preferred sources** — corroborated by past sessions; start here.
- `AdminPanelProvider.php` (2× useful, score=1.799570691) _(code changed — re-verify)_
- `Filament\Pages\Dashboard` (2× useful, score=1.799570691)

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `UserRole` connect `composer.json` to `User`, `package.json`, `UserResource.php`, `UserForm.php`, `UserFactory.php`, `ActiveSessionsTest.php`?**
  _High betweenness centrality (0.093) - this node is a cross-community bridge._
- **What connects `LoggedOut`, `Terminated`, `User` to the rest of the system?**
  _13 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Розгортання на Ubuntu VPS` be split into smaller, more focused modules?**
  _Cohesion score 0.051515151515151514 - nodes in this community are weakly interconnected._
- **Why does `User` connect `User` to `package.json`, `composer.json`, `UserResource.php`, `ActiveSessionsTest.php`, `LoginSession`?**
  _High betweenness centrality (0.082) - this node is a cross-community bridge._
- **Should `User` be split into smaller, more focused modules?**
  _Cohesion score 0.05297532656023222 - nodes in this community are weakly interconnected._
- **Why does `LoginSession` connect `LoginSession` to `User`, `TerminateOtherSessions.php`, `ActiveSessionsTest.php`, `StartLoginSession`?**
  _High betweenness centrality (0.063) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.0693815987933635 - nodes in this community are weakly interconnected._