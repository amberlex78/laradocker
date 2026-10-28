# Graph Report - la-filament  (2026-10-28)

## Corpus Check
- 65 files · ~11,383 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 12 file(s) not represented in the graph (top: (none) 8, .example 1, .neon 1)

## Summary
- 462 nodes · 686 edges · 30 communities (14 shown, 16 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 5 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `f0f51671`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- require-dev
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

## God Nodes (most connected - your core abstractions)
1. `UserRole` - 49 edges
2. `User` - 30 edges
3. `UserResource` - 14 edges
4. `EditProfile` - 14 edges
5. `UserSession` - 14 edges
6. `scripts` - 13 edges
7. `require-dev` - 12 edges
8. `DeviceDetectionService` - 10 edges
9. `SystemOverview` - 9 edges
10. `UserPolicy` - 9 edges

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

## Communities (30 total, 16 thin omitted)

### Community 0 - "require-dev"
Cohesion: 0.17
Nodes (12): require-dev, fakerphp/faker, larastan/larastan, laravel/boost, laravel/pail, laravel/pao, laravel/pint, laravel/sail (+4 more)

### Community 1 - "User"
Cohesion: 0.07
Nodes (5): User, UserPolicy, UserFactory, DatabaseSeeder, {closure#2}()

### Community 2 - "package.json"
Cohesion: 0.08
Nodes (7): RedirectGuestsToSiteLogin, AdminPanelProvider, {closure#1}(), {closure#1}(), OperatorPanelProvider, {closure#1}(), SitePanelProvider

### Community 3 - "composer.json"
Cohesion: 0.07
Nodes (29): UserRole, Admin, Developer, Operator, User, {closure#1}(), {closure#10}(), {closure#11}() (+21 more)

### Community 4 - "OperatorPanelProvider.php"
Cohesion: 0.09
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

## Knowledge Gaps
- **11 isolated node(s):** `User`, `Operator`, `Admin`, `Developer`, `concurrently` (+6 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 203 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **16 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `UserRole` connect `composer.json` to `User`, `package.json`, `OperatorPanelProvider.php`, `scripts`, `UserFactory.php`, `SystemOverviewTest.php`?**
  _High betweenness centrality (0.149) - this node is a cross-community bridge._
- **What connects `User`, `Operator`, `Admin` to the rest of the system?**
  _11 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `User` be split into smaller, more focused modules?**
  _Cohesion score 0.06553911205073996 - nodes in this community are weakly interconnected._
- **Why does `User` connect `User` to `composer.json`, `SystemOverviewTest.php`, `OperatorPanelProvider.php`, `ActiveSessions.php`?**
  _High betweenness centrality (0.079) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.07801418439716312 - nodes in this community are weakly interconnected._
- **Why does `EditProfile` connect `scripts` to `package.json`, `composer.json`, `ActiveSessions.php`?**
  _High betweenness centrality (0.039) - this node is a cross-community bridge._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.06956521739130435 - nodes in this community are weakly interconnected._