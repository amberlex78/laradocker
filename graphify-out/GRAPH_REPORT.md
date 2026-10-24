# Graph Report - la-filament  (2026-10-24)

## Corpus Check
- 49 files · ~10,828 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 12 file(s) not represented in the graph (top: (none) 8, .example 1, .neon 1)

## Summary
- 301 nodes · 386 edges · 28 communities (9 shown, 19 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `f6b1c7e8`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- UserResource.php
- User
- package.json
- composer.json
- AdminPanelProvider.php
- Illuminate\Database\Schema\Blueprint
- scripts
- config
- AppServiceProvider.php
- UserRole
- bootstrap/app.php
- DatabaseSeeder.php
- Pest.php
- Controller.php
- UsersTable.php

## God Nodes (most connected - your core abstractions)
1. `UserRole` - 21 edges
2. `User` - 19 edges
3. `UserResource` - 13 edges
4. `scripts` - 13 edges
5. `require-dev` - 12 edges
6. `require` - 7 edges
7. `AppServiceProvider` - 6 edges
8. `config` - 5 edges
9. `UserFactory` - 5 edges
10. `EditUser` - 4 edges

## Surprising Connections (you probably didn't know these)
- `{closure#1}()` --references--> `UserRole`  [EXTRACTED]
  tests/Feature/PanelAccessTest.php → app/Enums/UserRole.php
- `{closure#2}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/PanelAccessTest.php → app/Enums/UserRole.php
- `{closure#3}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/PanelAccessTest.php → app/Enums/UserRole.php
- `{closure#2}()` --references--> `User`  [EXTRACTED]
  tests/Feature/DatabaseSeederTest.php → app/Models/User.php
- `{closure#1}()` --references--> `User`  [EXTRACTED]
  app/Filament/Resources/Users/UserResource.php → app/Models/User.php

## Import Cycles
- None detected.

## Communities (28 total, 19 thin omitted)

### Community 0 - "UserResource.php"
Cohesion: 0.07
Nodes (8): CreateUser, ListUsers, ViewUser, UserForm, UsersTable, {closure#1}(), {closure#2}(), UserResource

### Community 1 - "User"
Cohesion: 0.09
Nodes (3): User, UserFactory, {closure#2}()

### Community 2 - "package.json"
Cohesion: 0.07
Nodes (28): dependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, vite-plus, optionalDependencies (+20 more)

### Community 3 - "composer.json"
Cohesion: 0.05
Nodes (38): autoload, autoload-dev, psr-4, psr-4, description, extra, laravel, keywords (+30 more)

### Community 5 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.11
Nodes (10): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+2 more)

### Community 6 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, ci:check, dev, lint, lint:check, post-autoload-dump, post-create-project-cmd, post-root-package-install (+5 more)

### Community 7 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 10 - "UserRole"
Cohesion: 0.14
Nodes (7): UserRole, Admin, Operator, User, {closure#1}(), {closure#2}(), {closure#3}()

### Community 11 - "bootstrap/app.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

## Knowledge Gaps
- **10 isolated node(s):** `User`, `Operator`, `Admin`, `concurrently`, `tailwindcss` (+5 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 147 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **19 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `UserRole` connect `UserRole` to `UserResource.php`, `User`, `DatabaseSeeder.php`, `UsersTable.php`?**
  _High betweenness centrality (0.104) - this node is a cross-community bridge._
- **What connects `User`, `Operator`, `Admin` to the rest of the system?**
  _10 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `UserResource.php` be split into smaller, more focused modules?**
  _Cohesion score 0.07435897435897436 - nodes in this community are weakly interconnected._
- **Why does `User` connect `User` to `UserResource.php`, `UserRole`, `DatabaseSeeder.php`, `UsersTable.php`?**
  _High betweenness centrality (0.063) - this node is a cross-community bridge._
- **Should `User` be split into smaller, more focused modules?**
  _Cohesion score 0.08602150537634409 - nodes in this community are weakly interconnected._
- **Why does `AppServiceProvider` connect `AppServiceProvider.php` to `AdminPanelProvider.php`?**
  _High betweenness centrality (0.030) - this node is a cross-community bridge._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.07126436781609195 - nodes in this community are weakly interconnected._