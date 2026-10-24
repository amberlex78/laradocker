# Graph Report - larafilament  (2026-10-24)

## Corpus Check
- 50 files · ~11,484 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 12 file(s) not represented in the graph (top: (none) 8, .example 1, .neon 1)

## Summary
- 330 nodes · 420 edges · 31 communities (10 shown, 21 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `bfc2cb06`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- UserResource.php
- User
- package.json
- composer.json
- AdminPanelProvider.php
- Illuminate\Database\Schema\Blueprint
- UserResourceTest.php
- config
- require-dev
- AppServiceProvider.php
- UserRole
- bootstrap/app.php
- DatabaseSeeder.php
- Pest.php
- Controller.php
- UserFactory.php
- UsersTable.php
- require

## God Nodes (most connected - your core abstractions)
1. `User` - 31 edges
2. `UserRole` - 25 edges
3. `UserResource` - 14 edges
4. `scripts` - 13 edges
5. `require-dev` - 12 edges
6. `UserPolicy` - 11 edges
7. `require` - 7 edges
8. `AppServiceProvider` - 6 edges
9. `EditUser` - 5 edges
10. `ListUsers` - 5 edges

## Surprising Connections (you probably didn't know these)
- `{closure#1}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/AdminPanelAccessTest.php → app/Enums/UserRole.php
- `{closure#2}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/AdminPanelAccessTest.php → app/Enums/UserRole.php
- `{closure#3}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/AdminPanelAccessTest.php → app/Enums/UserRole.php
- `{closure#4}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/AdminPanelAccessTest.php → app/Enums/UserRole.php
- `{closure#5}()` --references--> `UserRole`  [EXTRACTED]
  tests/Feature/AdminPanelAccessTest.php → app/Enums/UserRole.php

## Import Cycles
- None detected.

## Communities (31 total, 21 thin omitted)

### Community 0 - "UserResource.php"
Cohesion: 0.09
Nodes (6): CreateUser, UserForm, UsersTable, {closure#1}(), {closure#2}(), UserResource

### Community 1 - "User"
Cohesion: 0.12
Nodes (3): User, UserPolicy, {closure#2}()

### Community 2 - "package.json"
Cohesion: 0.07
Nodes (28): dependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, vite-plus, optionalDependencies (+20 more)

### Community 3 - "composer.json"
Cohesion: 0.06
Nodes (32): autoload, autoload-dev, psr-4, psr-4, description, extra, laravel, keywords (+24 more)

### Community 5 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.11
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 7 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 8 - "require-dev"
Cohesion: 0.17
Nodes (12): require-dev, fakerphp/faker, larastan/larastan, laravel/boost, laravel/pail, laravel/pao, laravel/pint, laravel/sail (+4 more)

### Community 10 - "UserRole"
Cohesion: 0.14
Nodes (10): UserRole, Admin, Operator, User, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}() (+2 more)

### Community 11 - "bootstrap/app.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 30 - "require"
Cohesion: 0.29
Nodes (7): require, filament/filament, laravel/framework, laravel/tinker, livewire/blaze, livewire/livewire, php

## Knowledge Gaps
- **10 isolated node(s):** `User`, `Operator`, `Admin`, `concurrently`, `tailwindcss` (+5 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 176 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **21 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `UserRole` connect `UserRole` to `UserResource.php`, `User`, `UserResourceTest.php`, `DatabaseSeeder.php`, `UserFactory.php`, `UsersTable.php`?**
  _High betweenness centrality (0.135) - this node is a cross-community bridge._
- **What connects `User`, `Operator`, `Admin` to the rest of the system?**
  _10 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `UserResource.php` be split into smaller, more focused modules?**
  _Cohesion score 0.09462365591397849 - nodes in this community are weakly interconnected._
- **Why does `User` connect `User` to `UserResource.php`, `AdminPanelProvider.php`, `UserResourceTest.php`, `UserRole`, `DatabaseSeeder.php`, `UserFactory.php`, `UsersTable.php`?**
  _High betweenness centrality (0.093) - this node is a cross-community bridge._
- **Should `User` be split into smaller, more focused modules?**
  _Cohesion score 0.1168091168091168 - nodes in this community are weakly interconnected._
- **Why does `UserResource` connect `UserResource.php` to `UsersTable.php`, `UserResourceTest.php`?**
  _High betweenness centrality (0.028) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.07126436781609195 - nodes in this community are weakly interconnected._