# Graph Report - ladocker  (2026-09-23)

## Corpus Check
- 81 files · ~16,326 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 29 file(s) not represented in the graph (top: (none) 16, .example 4, .conf 3)

## Summary
- 351 nodes · 445 edges · 45 communities (14 shown, 31 thin omitted)
- Extraction: 98% EXTRACTED · 2% INFERRED · 0% AMBIGUOUS · INFERRED: 7 edges (avg confidence: 0.95)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `619612c8`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- package.json
- Laravel-проєкти без домену
- UserRole
- Розгортання на VPS
- Laravel Docker Application
- User
- Authentication, Admin, and Developer Areas
- 0001_01_01_000000_create_users_table.php
- require-dev
- fortify.php
- Laravel-проєкти з доменами
- FortifyServiceProvider.php
- EnsureUserHasRole.php
- Pest.php
- logging.php
- artisan
- console.php
- laravel-boost
- Controller.php
- entrypoint.sh
- Illuminate\Support\Facades\Route

## God Nodes (most connected - your core abstractions)
1. `User` - 40 edges
2. `AuthValidationRules` - 20 edges
3. `UserRole` - 17 edges
4. `Розгортання на VPS` - 13 edges
5. `require-dev` - 11 edges
6. `Laravel-проєкти з доменами` - 10 edges
7. `SetUserPassword` - 9 edges
8. `scripts` - 9 edges
9. `CreateNewUser` - 8 edges
10. `ResetUserPassword` - 8 edges

## Surprising Connections (you probably didn't know these)
- `Roles and access` --references--> `UserRole`  [INFERRED]
  docs/superpowers/specs/2026-09-22-auth-admin-developer-design.md → app/Enums/UserRole.php
- `Global Constraints` --references--> `UserRole`  [INFERRED]
  docs/superpowers/plans/2026-09-22-auth-admin-developer-areas.md → app/Enums/UserRole.php
- `Task 1: Add the role domain model and database column` --references--> `User`  [INFERRED]
  docs/superpowers/plans/2026-09-22-auth-admin-developer-areas.md → app/Models/User.php
- `Task 2: Install and configure Fortify with Blade authentication views` --references--> `User`  [INFERRED]
  docs/superpowers/plans/2026-09-22-auth-admin-developer-areas.md → app/Models/User.php
- `Authentication, Admin, and Developer Areas Implementation Plan` --references--> `UserRole`  [INFERRED]
  docs/superpowers/plans/2026-09-22-auth-admin-developer-areas.md → app/Enums/UserRole.php

## Import Cycles
- None detected.

## Communities (45 total, 31 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.05
Nodes (40): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+32 more)

### Community 1 - "package.json"
Cohesion: 0.09
Nodes (21): devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, optionalDependencies, @laravel/multiplex (+13 more)

### Community 2 - "Laravel-проєкти без домену"
Cohesion: 0.12
Nodes (16): 1. Схема портів, 2. Локальний запуск, 3. Production-запуск на VPS, 4.1. Конфігурація laradockervps1, 4.2. Конфігурація laradockervps2, 4.3. Активація sites-enabled, 4. Мінімальний reverse proxy Nginx, 5. Видалення непотрібного проєкту (+8 more)

### Community 3 - "UserRole"
Cohesion: 0.09
Nodes (21): UserRole, UserFactory, DatabaseSeeder, UserSeeder, Authentication, Admin, and Developer Areas Implementation Plan, Global Constraints, Review Focus, Task 1: Add the role domain model and database column (+13 more)

### Community 4 - "Розгортання на VPS"
Cohesion: 0.07
Nodes (23): Development mounts and production volumes, Docker architecture, Images, Multiple projects on one VPS, Networks and ports, 1. Підготовка сервера, 2. Клонування проєкту та production-конфігурація, 3. Побудова та запуск production-стека (+15 more)

### Community 5 - "Laravel Docker Application"
Cohesion: 0.14
Nodes (14): Composer, npm і shell, Laravel Docker Application, Laravel і база даних, Vite, Безпека та збереження даних, Встановлення проєкту на VPS, Документація, Основні команди (+6 more)

### Community 6 - "User"
Cohesion: 0.08
Nodes (23): RegisterUser, SetUserPassword, UpdateUserProfile, CreateNewUser, ResetUserPassword, UpdateUserPassword, UpdateUserProfileInformation, User (+15 more)

### Community 7 - "Authentication, Admin, and Developer Areas"
Cohesion: 0.25
Nodes (7): Application areas, Authentication, Authentication, Admin, and Developer Areas, Goal, Roles and access, UI structure, Verification

### Community 8 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.19
Nodes (3): Illuminate\Database\Migrations\Migration, Illuminate\Database\Schema\Blueprint, Illuminate\Support\Facades\Schema

### Community 9 - "require-dev"
Cohesion: 0.18
Nodes (11): require-dev, fakerphp/faker, fruitcake/laravel-debugbar, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery (+3 more)

### Community 11 - "Laravel-проєкти з доменами"
Cohesion: 0.12
Nodes (16): 1. Схема доменів і портів, 2. Локальний запуск, 3. Підготовка доменів, Cloudflare і сертифікатів, 4. Production-запуск на VPS, 5.1. Конфігурація example1.com, 5.2. Конфігурація example2.com, 5. Системний Nginx як reverse proxy, 6. Активація Nginx і firewall (+8 more)

### Community 14 - "FortifyServiceProvider.php"
Cohesion: 0.09
Nodes (13): ResolveUserLandingRoute, LoginResponse, AppServiceProvider, FortifyServiceProvider, Illuminate\Cache\RateLimiting\Limit, Illuminate\Support\Facades\RateLimiter, Illuminate\Support\ServiceProvider, Illuminate\Support\Str (+5 more)

### Community 15 - "EnsureUserHasRole.php"
Cohesion: 0.27
Nodes (7): EnsureUserHasRole, Closure, Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware, Illuminate\Http\Request, Symfony\Component\HttpFoundation\Response

### Community 17 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

## Knowledge Gaps
- **120 isolated node(s):** `php`, `Controller`, `$schema`, `name`, `type` (+115 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 196 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **31 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User` to `UserRole`, `FortifyServiceProvider.php`, `EnsureUserHasRole.php`?**
  _High betweenness centrality (0.071) - this node is a cross-community bridge._
- **Why does `UserRole` connect `UserRole` to `EnsureUserHasRole.php`, `Authentication, Admin, and Developer Areas`, `User`, `FortifyServiceProvider.php`?**
  _High betweenness centrality (0.037) - this node is a cross-community bridge._
- **Why does `Roles and access` connect `Authentication, Admin, and Developer Areas` to `UserRole`?**
  _High betweenness centrality (0.015) - this node is a cross-community bridge._
- **Are the 2 inferred relationships involving `User` (e.g. with `Task 1: Add the role domain model and database column` and `Task 2: Install and configure Fortify with Blade authentication views`) actually correct?**
  _`User` has 2 INFERRED edges - model-reasoned connections that need verification._
- **Are the 4 inferred relationships involving `UserRole` (e.g. with `Authentication, Admin, and Developer Areas Implementation Plan` and `Global Constraints`) actually correct?**
  _`UserRole` has 4 INFERRED edges - model-reasoned connections that need verification._
- **What connects `php`, `Controller`, `$schema` to the rest of the system?**
  _120 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.04878048780487805 - nodes in this community are weakly interconnected._