# Graph Report - la-filament  (2026-10-25)

## Corpus Check
- 58 files · ~9,179 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 12 file(s) not represented in the graph (top: (none) 8, .example 1, .neon 1)

## Summary
- 388 nodes · 534 edges · 30 communities (12 shown, 18 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 1 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `605f67b3`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- UserResource.php
- User
- package.json
- composer.json
- OperatorPanelProvider.php
- Illuminate\Database\Schema\Blueprint
- UserForm.php
- scripts
- UserFactory.php
- AppServiceProvider.php
- UserRole
- bootstrap/app.php
- SystemOverview
- Pest.php
- Controller.php
- Q: Чи є у Filament віджети або картки для Dashboard як на скриншоті?
- Q: Зробити кастомний Dashboard у стилі карток замість стандартного Filament Dashboard

## God Nodes (most connected - your core abstractions)
1. `UserRole` - 33 edges
2. `User` - 20 edges
3. `UserResource` - 13 edges
4. `scripts` - 13 edges
5. `require-dev` - 12 edges
6. `EditProfile` - 11 edges
7. `SystemOverview` - 9 edges
8. `require` - 7 edges
9. `AppServiceProvider` - 6 edges
10. `RedirectGuestsToSiteLogin` - 5 edges

## Surprising Connections (you probably didn't know these)
- `{closure#1}()` --references--> `UserRole`  [EXTRACTED]
  tests/Feature/PanelAccessTest.php → app/Enums/UserRole.php
- `{closure#10}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/PanelAccessTest.php → app/Enums/UserRole.php
- `{closure#12}()` --references--> `UserRole`  [EXTRACTED]
  tests/Feature/PanelAccessTest.php → app/Enums/UserRole.php
- `{closure#16}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/PanelAccessTest.php → app/Enums/UserRole.php
- `{closure#17}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/PanelAccessTest.php → app/Enums/UserRole.php

## Import Cycles
- None detected.

## Communities (30 total, 18 thin omitted)

### Community 0 - "UserResource.php"
Cohesion: 0.08
Nodes (8): CreateUser, EditUser, ListUsers, ViewUser, UsersTable, {closure#1}(), {closure#2}(), UserResource

### Community 1 - "User"
Cohesion: 0.08
Nodes (3): User, DatabaseSeeder, {closure#2}()

### Community 2 - "package.json"
Cohesion: 0.07
Nodes (28): dependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, vite-plus, optionalDependencies (+20 more)

### Community 3 - "composer.json"
Cohesion: 0.04
Nodes (45): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+37 more)

### Community 4 - "OperatorPanelProvider.php"
Cohesion: 0.08
Nodes (7): RedirectGuestsToSiteLogin, AdminPanelProvider, {closure#1}(), {closure#1}(), OperatorPanelProvider, {closure#1}(), SitePanelProvider

### Community 5 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.11
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 7 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, ci:check, dev, lint, lint:check, post-autoload-dump, post-create-project-cmd, post-root-package-install (+5 more)

### Community 9 - "AppServiceProvider.php"
Cohesion: 0.16
Nodes (4): FilamentLoginResponse, FilamentLogoutResponse, AppServiceProvider, {closure#1}()

### Community 10 - "UserRole"
Cohesion: 0.09
Nodes (15): UserRole, Admin, Operator, User, {closure#1}(), {closure#10}(), {closure#12}(), {closure#16}() (+7 more)

### Community 11 - "bootstrap/app.php"
Cohesion: 0.31
Nodes (4): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}()

### Community 27 - "Q: Чи є у Filament віджети або картки для Dashboard як на скриншоті?"
Cohesion: 0.40
Nodes (4): Answer, Outcome, Q: Чи є у Filament віджети або картки для Dashboard як на скриншоті?, Source Nodes

### Community 28 - "Q: Зробити кастомний Dashboard у стилі карток замість стандартного Filament Dashboard"
Cohesion: 0.40
Nodes (4): Answer, Outcome, Q: Зробити кастомний Dashboard у стилі карток замість стандартного Filament Dashboard, Source Nodes

## Knowledge Gaps
- **10 isolated node(s):** `User`, `Operator`, `Admin`, `concurrently`, `tailwindcss` (+5 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 189 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **18 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `UserRole` connect `UserRole` to `User`, `OperatorPanelProvider.php`, `UserForm.php`, `UserFactory.php`, `AppServiceProvider.php`, `SystemOverview`?**
  _High betweenness centrality (0.120) - this node is a cross-community bridge._
- **What connects `User`, `Operator`, `Admin` to the rest of the system?**
  _10 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `UserResource.php` be split into smaller, more focused modules?**
  _Cohesion score 0.07965860597439545 - nodes in this community are weakly interconnected._
- **Why does `User` connect `User` to `UserResource.php`, `OperatorPanelProvider.php`, `UserFactory.php`, `UserRole`, `SystemOverview`?**
  _High betweenness centrality (0.088) - this node is a cross-community bridge._
- **Should `User` be split into smaller, more focused modules?**
  _Cohesion score 0.08387096774193549 - nodes in this community are weakly interconnected._
- **Why does `EditProfile` connect `UserForm.php` to `UserRole`, `OperatorPanelProvider.php`?**
  _High betweenness centrality (0.030) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.07126436781609195 - nodes in this community are weakly interconnected._