# Graph Report - larafilament  (2026-10-23)

## Corpus Check
- 43 files · ~10,435 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 12 file(s) not represented in the graph (top: (none) 8, .example 1, .neon 1)

## Summary
- 272 nodes · 311 edges · 28 communities (9 shown, 19 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `133ec336`
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
- require-dev
- AppServiceProvider.php
- UserForm.php
- bootstrap/app.php
- config
- Pest.php
- Controller.php

## God Nodes (most connected - your core abstractions)
1. `User` - 14 edges
2. `scripts` - 13 edges
3. `require-dev` - 12 edges
4. `UserResource` - 10 edges
5. `require` - 7 edges
6. `AppServiceProvider` - 6 edges
7. `EditUser` - 5 edges
8. `ListUsers` - 5 edges
9. `config` - 5 edges
10. `UserFactory` - 5 edges

## Surprising Connections (you probably didn't know these)
- None detected - all connections are within the same source files.

## Import Cycles
- None detected.

## Communities (28 total, 19 thin omitted)

### Community 0 - "UserResource.php"
Cohesion: 0.10
Nodes (5): CreateUser, EditUser, ListUsers, UsersTable, UserResource

### Community 1 - "User"
Cohesion: 0.09
Nodes (3): User, UserFactory, DatabaseSeeder

### Community 2 - "package.json"
Cohesion: 0.07
Nodes (28): dependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, vite-plus, optionalDependencies (+20 more)

### Community 3 - "composer.json"
Cohesion: 0.07
Nodes (26): autoload, autoload-dev, psr-4, psr-4, description, extra, laravel, keywords (+18 more)

### Community 5 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.16
Nodes (8): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}()

### Community 7 - "scripts"
Cohesion: 0.15
Nodes (13): scripts, ci:check, dev, lint, lint:check, post-autoload-dump, post-create-project-cmd, post-root-package-install (+5 more)

### Community 8 - "require-dev"
Cohesion: 0.17
Nodes (12): require-dev, fakerphp/faker, larastan/larastan, laravel/boost, laravel/pail, laravel/pao, laravel/pint, laravel/sail (+4 more)

### Community 11 - "bootstrap/app.php"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 12 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

## Knowledge Gaps
- **7 isolated node(s):** `concurrently`, `tailwindcss`, `vite`, `@laravel/multiplex`, `@rollup/rollup-linux-x64-gnu` (+2 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 161 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **19 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User` to `UserResource.php`, `AdminPanelProvider.php`, `UserResourceTest.php`?**
  _High betweenness centrality (0.074) - this node is a cross-community bridge._
- **What connects `concurrently`, `tailwindcss`, `vite` to the rest of the system?**
  _7 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `UserResource.php` be split into smaller, more focused modules?**
  _Cohesion score 0.09655172413793103 - nodes in this community are weakly interconnected._
- **Why does `AppServiceProvider` connect `AppServiceProvider.php` to `AdminPanelProvider.php`?**
  _High betweenness centrality (0.030) - this node is a cross-community bridge._
- **Should `User` be split into smaller, more focused modules?**
  _Cohesion score 0.0896551724137931 - nodes in this community are weakly interconnected._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.07126436781609195 - nodes in this community are weakly interconnected._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.07407407407407407 - nodes in this community are weakly interconnected._