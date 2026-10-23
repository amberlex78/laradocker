# Graph Report - larafilament  (2026-10-24)

## Corpus Check
- 47 files · ~10,893 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 12 file(s) not represented in the graph (top: (none) 8, .example 1, .neon 1)

## Summary
- 297 nodes · 359 edges · 27 communities (9 shown, 18 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `e4849f17`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- UserResource.php
- UserRole
- package.json
- composer.json
- AdminPanelProvider.php
- Illuminate\Database\Schema\Blueprint
- UserResourceTest.php
- scripts
- require-dev
- AppServiceProvider.php
- UserRole
- bootstrap/app.php
- Pest.php
- Controller.php

## God Nodes (most connected - your core abstractions)
1. `UserRole` - 22 edges
2. `User` - 17 edges
3. `scripts` - 13 edges
4. `require-dev` - 12 edges
5. `UserResource` - 10 edges
6. `require` - 7 edges
7. `AppServiceProvider` - 6 edges
8. `EditUser` - 5 edges
9. `ListUsers` - 5 edges
10. `config` - 5 edges

## Surprising Connections (you probably didn't know these)
- `{closure#1}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/AdminPanelAccessTest.php → app/Enums/UserRole.php
- `{closure#2}()` --references--> `UserRole`  [EXTRACTED]
  tests/Feature/AdminPanelAccessTest.php → app/Enums/UserRole.php
- `{closure#3}()` --references--> `UserRole`  [EXTRACTED]
  tests/Feature/AdminPanelAccessTest.php → app/Enums/UserRole.php
- `{closure#4}()` --references--> `UserRole`  [EXTRACTED]
  tests/Feature/AdminPanelAccessTest.php → app/Enums/UserRole.php
- `{closure#5}()` --references--> `UserRole`  [EXTRACTED]
  tests/Feature/AdminPanelAccessTest.php → app/Enums/UserRole.php

## Import Cycles
- None detected.

## Communities (27 total, 18 thin omitted)

### Community 0 - "UserResource.php"
Cohesion: 0.08
Nodes (5): CreateUser, ListUsers, UserForm, UsersTable, UserResource

### Community 1 - "UserRole"
Cohesion: 0.08
Nodes (4): User, UserFactory, DatabaseSeeder, {closure#2}()

### Community 2 - "package.json"
Cohesion: 0.07
Nodes (28): dependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, vite-plus, optionalDependencies (+20 more)

### Community 3 - "composer.json"
Cohesion: 0.06
Nodes (33): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+25 more)

### Community 5 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.13
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 7 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, ci:check, dev, lint, lint:check, post-autoload-dump, post-create-project-cmd, post-root-package-install (+5 more)

### Community 8 - "require-dev"
Cohesion: 0.17
Nodes (12): require-dev, fakerphp/faker, larastan/larastan, laravel/boost, laravel/pail, laravel/pao, laravel/pint, laravel/sail (+4 more)

### Community 10 - "UserRole"
Cohesion: 0.16
Nodes (10): UserRole, Admin, Developer, Operator, User, {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 11 - "bootstrap/app.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

## Knowledge Gaps
- **11 isolated node(s):** `User`, `Operator`, `Admin`, `Developer`, `concurrently` (+6 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 170 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **18 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `UserRole` connect `UserRole` to `UserResource.php`, `UserRole`, `UserResourceTest.php`?**
  _High betweenness centrality (0.076) - this node is a cross-community bridge._
- **What connects `User`, `Operator`, `Admin` to the rest of the system?**
  _11 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `UserResource.php` be split into smaller, more focused modules?**
  _Cohesion score 0.07657657657657657 - nodes in this community are weakly interconnected._
- **Why does `User` connect `UserRole` to `UserResource.php`, `UserRole`, `AdminPanelProvider.php`, `UserResourceTest.php`?**
  _High betweenness centrality (0.052) - this node is a cross-community bridge._
- **Should `UserRole` be split into smaller, more focused modules?**
  _Cohesion score 0.08143939393939394 - nodes in this community are weakly interconnected._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.07126436781609195 - nodes in this community are weakly interconnected._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.058823529411764705 - nodes in this community are weakly interconnected._