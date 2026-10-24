# Graph Report - la-filament  (2026-10-25)

## Corpus Check
- 54 files · ~11,571 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 12 file(s) not represented in the graph (top: (none) 8, .example 1, .neon 1)

## Summary
- 355 nodes · 493 edges · 27 communities (11 shown, 16 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 1 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `c34bf6c2`
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
- config
- require-dev
- AppServiceProvider.php
- UserRole
- bootstrap/app.php
- Pest.php
- Controller.php

## God Nodes (most connected - your core abstractions)
1. `UserRole` - 31 edges
2. `User` - 19 edges
3. `UserResource` - 13 edges
4. `scripts` - 13 edges
5. `require-dev` - 12 edges
6. `EditProfile` - 7 edges
7. `require` - 7 edges
8. `AppServiceProvider` - 6 edges
9. `RedirectGuestsToSiteLogin` - 5 edges
10. `config` - 5 edges

## Surprising Connections (you probably didn't know these)
- `{closure#1}()` --references--> `UserRole`  [EXTRACTED]
  tests/Feature/PanelAccessTest.php → app/Enums/UserRole.php
- `{closure#10}()` --references--> `UserRole`  [EXTRACTED]
  tests/Feature/PanelAccessTest.php → app/Enums/UserRole.php
- `{closure#11}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/PanelAccessTest.php → app/Enums/UserRole.php
- `{closure#12}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/PanelAccessTest.php → app/Enums/UserRole.php
- `{closure#13}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/PanelAccessTest.php → app/Enums/UserRole.php

## Import Cycles
- None detected.

## Communities (27 total, 16 thin omitted)

### Community 0 - "UserResource.php"
Cohesion: 0.07
Nodes (8): CreateUser, EditUser, ListUsers, ViewUser, UsersTable, {closure#1}(), {closure#2}(), UserResource

### Community 1 - "User"
Cohesion: 0.08
Nodes (4): User, UserFactory, DatabaseSeeder, {closure#2}()

### Community 2 - "package.json"
Cohesion: 0.07
Nodes (28): dependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, vite-plus, optionalDependencies (+20 more)

### Community 3 - "composer.json"
Cohesion: 0.05
Nodes (39): autoload, autoload-dev, psr-4, psr-4, description, extra, laravel, keywords (+31 more)

### Community 4 - "OperatorPanelProvider.php"
Cohesion: 0.07
Nodes (7): RedirectGuestsToSiteLogin, AdminPanelProvider, {closure#1}(), {closure#1}(), OperatorPanelProvider, {closure#1}(), SitePanelProvider

### Community 5 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.11
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 7 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 8 - "require-dev"
Cohesion: 0.17
Nodes (12): require-dev, fakerphp/faker, larastan/larastan, laravel/boost, laravel/pail, laravel/pao, laravel/pint, laravel/sail (+4 more)

### Community 9 - "AppServiceProvider.php"
Cohesion: 0.16
Nodes (4): FilamentLoginResponse, FilamentLogoutResponse, AppServiceProvider, {closure#1}()

### Community 10 - "UserRole"
Cohesion: 0.10
Nodes (14): UserRole, Admin, Operator, User, {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}() (+6 more)

### Community 11 - "bootstrap/app.php"
Cohesion: 0.31
Nodes (4): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}()

## Knowledge Gaps
- **10 isolated node(s):** `User`, `Operator`, `Admin`, `concurrently`, `tailwindcss` (+5 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 169 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **16 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `UserRole` connect `UserRole` to `UserResource.php`, `User`, `OperatorPanelProvider.php`, `UserForm.php`, `AppServiceProvider.php`?**
  _High betweenness centrality (0.134) - this node is a cross-community bridge._
- **What connects `User`, `Operator`, `Admin` to the rest of the system?**
  _10 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `UserResource.php` be split into smaller, more focused modules?**
  _Cohesion score 0.07195121951219512 - nodes in this community are weakly interconnected._
- **Why does `User` connect `User` to `UserResource.php`, `UserRole`, `OperatorPanelProvider.php`?**
  _High betweenness centrality (0.072) - this node is a cross-community bridge._
- **Should `User` be split into smaller, more focused modules?**
  _Cohesion score 0.08143939393939394 - nodes in this community are weakly interconnected._
- **Why does `UserResource` connect `UserResource.php` to `UserForm.php`?**
  _High betweenness centrality (0.020) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.07126436781609195 - nodes in this community are weakly interconnected._