# Graph Report - ladocker  (2026-09-24)

## Corpus Check
- 106 files · ~70,006 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 33 file(s) not represented in the graph (top: (none) 19, .example 4, .conf 3)

## Summary
- 542 nodes · 665 edges · 69 communities (27 shown, 42 thin omitted)
- Extraction: 97% EXTRACTED · 3% INFERRED · 0% AMBIGUOUS · INFERRED: 17 edges (avg confidence: 0.89)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `d8c62085`
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
- tailadmin/package.json
- 0001_01_01_000000_create_users_table.php
- require-dev
- fortify.php
- Laravel-проєкти з доменами
- FortifyServiceProvider.php
- EnsureUserHasRole.php
- TailAdmin - Free Tailwind Admin Dashboard Template
- logging.php
- artisan
- console.php
- laravel-boost
- Controller.php
- entrypoint.sh
- Illuminate\Support\Facades\Route
- index.js
- devDependencies
- calendar-init.js
- image-resize.js
- UserFactory.php
- dependencies
- scripts
- config
- psr-4
- require
- autoload-dev
- extra

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
- `Global Constraints` --references--> `UserRole`  [INFERRED]
  docs/superpowers/plans/2026-09-22-auth-admin-developer-areas.md → app/Enums/UserRole.php
- `Roles and access` --references--> `UserRole`  [INFERRED]
  docs/superpowers/specs/2026-09-22-auth-admin-developer-design.md → app/Enums/UserRole.php
- `Task 1: Add the role domain model and database column` --references--> `User`  [INFERRED]
  docs/superpowers/plans/2026-09-22-auth-admin-developer-areas.md → app/Models/User.php
- `Task 2: Install and configure Fortify with Blade authentication views` --references--> `User`  [INFERRED]
  docs/superpowers/plans/2026-09-22-auth-admin-developer-areas.md → app/Models/User.php
- `Task 2: Install and configure Fortify with Blade authentication views` --references--> `UserFactory`  [INFERRED]
  docs/superpowers/plans/2026-09-22-auth-admin-developer-areas.md → database/factories/UserFactory.php

## Import Cycles
- None detected.

## Communities (69 total, 42 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 1 - "package.json"
Cohesion: 0.08
Nodes (24): dependencies, alpinejs, devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite (+16 more)

### Community 2 - "Laravel-проєкти без домену"
Cohesion: 0.12
Nodes (16): 1. Схема портів, 2. Локальний запуск, 3. Production-запуск на VPS, 4.1. Конфігурація laradockervps1, 4.2. Конфігурація laradockervps2, 4.3. Активація sites-enabled, 4. Мінімальний reverse proxy Nginx, 5. Видалення непотрібного проєкту (+8 more)

### Community 3 - "UserRole"
Cohesion: 0.07
Nodes (24): UserRole, DatabaseSeeder, UserSeeder, Authentication, Admin, and Developer Areas Implementation Plan, Global Constraints, Review Focus, Task 1: Add the role domain model and database column, Task 2: Install and configure Fortify with Blade authentication views (+16 more)

### Community 4 - "Розгортання на VPS"
Cohesion: 0.07
Nodes (23): Development mounts and production volumes, Docker architecture, Images, Multiple projects on one VPS, Networks and ports, 1. Підготовка сервера, 2. Клонування проєкту та production-конфігурація, 3. Побудова та запуск production-стека (+15 more)

### Community 5 - "Laravel Docker Application"
Cohesion: 0.14
Nodes (14): Composer, npm і shell, Laravel Docker Application, Laravel і база даних, Vite, Безпека та збереження даних, Встановлення проєкту на VPS, Документація, Основні команди (+6 more)

### Community 6 - "User"
Cohesion: 0.08
Nodes (26): RegisterUser, SetUserPassword, UpdateUserProfile, CreateNewUser, ResetUserPassword, UpdateUserPassword, UpdateUserProfileInformation, User (+18 more)

### Community 7 - "tailadmin/package.json"
Cohesion: 0.05
Nodes (46): @babel/core, babel-loader, @babel/preset-env, copy-webpack-plugin, css-loader, file-loader, glob, html-loader (+38 more)

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
Cohesion: 0.12
Nodes (11): ResolveUserLandingRoute, LoginResponse, AppServiceProvider, FortifyServiceProvider, Illuminate\Cache\RateLimiting\Limit, Illuminate\Support\Facades\RateLimiter, Illuminate\Support\ServiceProvider, Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable (+3 more)

### Community 15 - "EnsureUserHasRole.php"
Cohesion: 0.27
Nodes (7): EnsureUserHasRole, Closure, Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware, Illuminate\Http\Request, Symfony\Component\HttpFoundation\Response

### Community 16 - "TailAdmin - Free Tailwind Admin Dashboard Template"
Cohesion: 0.07
Nodes (26): Breaking Changes, Cloning the Repository, Components, Demos, Feature Comparison, Free Version, Installation, License (+18 more)

### Community 17 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 29 - "index.js"
Cohesion: 0.07
Nodes (25): ref_alpinejs, @alpinejs/persist, apexcharts, dropzone, ref_dropzone_dist_dropzone_css, flatpickr, ref_flatpickr_dist_flatpickr_min_css, i18next (+17 more)

### Community 45 - "devDependencies"
Cohesion: 0.09
Nodes (22): devDependencies, @babel/core, babel-loader, @babel/preset-env, copy-webpack-plugin, css-loader, file-loader, glob (+14 more)

### Community 46 - "calendar-init.js"
Cohesion: 0.13
Nodes (16): fullcalendar, ref_fullcalendar_daygrid, ref_fullcalendar_interaction, ref_fullcalendar_multimonth, ref_fullcalendar_skeleton_css, ref_fullcalendar_themes_classic, ref_fullcalendar_themes_classic_palette_css, ref_fullcalendar_themes_classic_theme_css (+8 more)

### Community 47 - "image-resize.js"
Cohesion: 0.33
Nodes (12): animate(), calc(), canMove(), hintHide(), onDown(), onMouseDown(), onMove(), onTouchDown() (+4 more)

### Community 48 - "UserFactory.php"
Cohesion: 0.22
Nodes (5): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Support\Str, Pdo\Mysql, static

### Community 49 - "dependencies"
Cohesion: 0.18
Nodes (11): dependencies, alpinejs, @alpinejs/persist, apexcharts, dropzone, flatpickr, fullcalendar, i18next (+3 more)

### Community 50 - "scripts"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 51 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 52 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 53 - "require"
Cohesion: 0.40
Nodes (5): require, laravel/fortify, laravel/framework, laravel/tinker, php

### Community 54 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 55 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

## Knowledge Gaps
- **211 isolated node(s):** `php`, `name`, `version`, `description`, `main` (+206 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 323 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **42 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User` to `UserFactory.php`, `UserRole`, `FortifyServiceProvider.php`, `EnsureUserHasRole.php`?**
  _High betweenness centrality (0.030) - this node is a cross-community bridge._
- **Why does `devDependencies` connect `devDependencies` to `tailadmin/package.json`?**
  _High betweenness centrality (0.020) - this node is a cross-community bridge._
- **Why does `UserRole` connect `UserRole` to `UserFactory.php`, `EnsureUserHasRole.php`, `User`, `FortifyServiceProvider.php`?**
  _High betweenness centrality (0.015) - this node is a cross-community bridge._
- **Are the 2 inferred relationships involving `User` (e.g. with `Task 1: Add the role domain model and database column` and `Task 2: Install and configure Fortify with Blade authentication views`) actually correct?**
  _`User` has 2 INFERRED edges - model-reasoned connections that need verification._
- **Are the 4 inferred relationships involving `UserRole` (e.g. with `Authentication, Admin, and Developer Areas Implementation Plan` and `Global Constraints`) actually correct?**
  _`UserRole` has 4 INFERRED edges - model-reasoned connections that need verification._
- **What connects `php`, `name`, `version` to the rest of the system?**
  _211 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.08307692307692308 - nodes in this community are weakly interconnected._