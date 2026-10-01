# Graph Report - laradocker  (2026-10-01)

## Corpus Check
- 197 files · ~42,845 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 30 file(s) not represented in the graph (top: (none) 16, .example 4, .conf 3)

## Summary
- 765 nodes · 1172 edges · 144 communities (29 shown, 115 thin omitted)
- Extraction: 96% EXTRACTED · 4% INFERRED · 0% AMBIGUOUS · INFERRED: 47 edges (avg confidence: 0.91)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `880a5f8c`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- UserRole
- FortifyServiceProvider.php
- .view
- Розгортання на VPS
- AuthValidationRules
- package.json
- UserFactory.php
- Illuminate\Database\Schema\Blueprint
- User
- PageRenderingTest.php
- .password
- Laravel-проєкти без домену
- Laravel-проєкти з доменами
- RoleAccessTest.php
- User Management in Admin and Developer Areas
- require-dev
- composer.json
- scripts
- AuthenticationFlowTest.php
- Illuminate\Foundation\Testing\RefreshDatabase
- User.php
- config
- psr-4
- require
- logging.php
- AuthValidationRulesTest.php
- console.php
- Views
- artisan
- autoload-dev
- extra
- laravel-boost
- index.md
- ui-icons.md
- fortify.php
- entrypoint.sh
- ProfilePageTest.php
- TestCase

## God Nodes (most connected - your core abstractions)
1. `User` - 194 edges
2. `UserRole` - 86 edges
3. `AuthValidationRules` - 20 edges
4. `Розгортання на VPS` - 13 edges
5. `UserController` - 11 edges
6. `UserController` - 11 edges
7. `require-dev` - 11 edges
8. `User Management in Admin and Developer Areas` - 11 edges
9. `LoginHistory` - 10 edges
10. `UserPolicy` - 10 edges

## Surprising Connections (you probably didn't know these)
- `Global Constraints` --references--> `User`  [INFERRED]
  docs/superpowers/plans/2026-09-26-user-management.md → app/Models/User.php
- `Authentication, Admin, and Developer Areas Implementation Plan` --references--> `UserRole`  [INFERRED]
  docs/superpowers/plans/2026-09-22-auth-admin-developer-areas.md → app/Enums/UserRole.php
- `Global Constraints` --references--> `UserRole`  [INFERRED]
  docs/superpowers/plans/2026-09-22-auth-admin-developer-areas.md → app/Enums/UserRole.php
- `Task 1: Add the role domain model and database column` --references--> `UserRole`  [INFERRED]
  docs/superpowers/plans/2026-09-22-auth-admin-developer-areas.md → app/Enums/UserRole.php
- `Roles and access` --references--> `UserRole`  [INFERRED]
  docs/superpowers/specs/2026-09-22-auth-admin-developer-design.md → app/Enums/UserRole.php

## Import Cycles
- None detected.

## Communities (144 total, 115 thin omitted)

### Community 0 - "UserRole"
Cohesion: 0.05
Nodes (50): UserRole, UserPolicy, Global Constraints, Review Focus, Task 1: Add and test the shared user authorization policy, Task 2: Add shared user-management persistence actions and request validation, Task 3: Add the admin and developer CRUD controllers and route boundaries, Task 4: Build the separated user-management UI and navigation (+42 more)

### Community 1 - "FortifyServiceProvider.php"
Cohesion: 0.06
Nodes (32): RecordUserLogin, ResolveUserLandingRoute, AdvertiseClientHints, {closure#1}(), EnsureUserHasRole, LoginResponse, AppServiceProvider, {closure#5}() (+24 more)

### Community 2 - ".view"
Cohesion: 0.07
Nodes (17): CreateUser, DeleteUser, UpdateUser, AccountController, UserController, Controller, UserController, {closure#1}() (+9 more)

### Community 3 - "Розгортання на VPS"
Cohesion: 0.05
Nodes (37): Development mounts and production volumes, Docker architecture, Images, Multiple projects on one VPS, Networks and ports, 1. Підготовка сервера, 2. Клонування проєкту та production-конфігурація, 3. Побудова та запуск production-стека (+29 more)

### Community 4 - "AuthValidationRules"
Cohesion: 0.12
Nodes (13): RegisterUser, SetUserPassword, UpdateUserProfile, CreateNewUser, ResetUserPassword, UpdateUserPassword, UpdateUserProfileInformation, AuthValidationRules (+5 more)

### Community 5 - "package.json"
Cohesion: 0.07
Nodes (32): dependencies, alpinejs, flowbite, devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite (+24 more)

### Community 6 - "UserFactory.php"
Cohesion: 0.12
Nodes (11): UserFactory, DatabaseSeeder, UserSeeder, Task 2: Install and configure Fortify with Blade authentication views, Illuminate\Database\Console\Seeds\WithoutModelEvents, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Database\Seeder, Illuminate\Support\Facades\Hash (+3 more)

### Community 7 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.08
Nodes (20): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+12 more)

### Community 8 - "User"
Cohesion: 0.14
Nodes (22): User, {closure#1}(), {closure#2}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+14 more)

### Community 9 - "PageRenderingTest.php"
Cohesion: 0.08
Nodes (19): {closure#10}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#17}(), {closure#18}() (+11 more)

### Community 10 - ".password"
Cohesion: 0.13
Nodes (7): StoreUserRequest, UpdateUserRequest, StoreUserRequest, UpdateUserRequest, Illuminate\Foundation\Http\FormRequest, Illuminate\Validation\Rule, Illuminate\Validation\Rules\Password

### Community 12 - "Laravel-проєкти без домену"
Cohesion: 0.12
Nodes (16): 1. Схема портів, 2. Локальний запуск, 3. Production-запуск на VPS, 4.1. Конфігурація laradockervps1, 4.2. Конфігурація laradockervps2, 4.3. Активація sites-enabled, 4. Мінімальний reverse proxy Nginx, 5. Видалення непотрібного проєкту (+8 more)

### Community 13 - "Laravel-проєкти з доменами"
Cohesion: 0.12
Nodes (16): 1. Схема доменів і портів, 2. Локальний запуск, 3. Підготовка доменів, Cloudflare і сертифікатів, 4. Production-запуск на VPS, 5.1. Конфігурація example1.com, 5.2. Конфігурація example2.com, 5. Системний Nginx як reverse proxy, 6. Активація Nginx і firewall (+8 more)

### Community 14 - "RoleAccessTest.php"
Cohesion: 0.25
Nodes (5): {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}()

### Community 15 - "User Management in Admin and Developer Areas"
Cohesion: 0.08
Nodes (25): Authentication, Admin, and Developer Areas Implementation Plan, Global Constraints, Review Focus, Task 1: Add the role domain model and database column, Task 3: Add role middleware and route boundaries, Task 4: Build the public, account, admin, and developer Blade shells, Task 5: Run the complete verification pass, Application areas (+17 more)

### Community 16 - "require-dev"
Cohesion: 0.18
Nodes (11): require-dev, fakerphp/faker, fruitcake/laravel-debugbar, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery (+3 more)

### Community 18 - "composer.json"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 19 - "scripts"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 20 - "AuthenticationFlowTest.php"
Cohesion: 0.12
Nodes (12): {closure#10}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+4 more)

### Community 21 - "Illuminate\Foundation\Testing\RefreshDatabase"
Cohesion: 0.15
Nodes (10): Illuminate\Auth\Notifications\VerifyEmail, Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Support\Facades\Notification, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#1}() (+2 more)

### Community 22 - "User.php"
Cohesion: 0.22
Nodes (6): Illuminate\Contracts\Auth\MustVerifyEmail, Illuminate\Database\Eloquent\Attributes\Hidden, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Relations\HasMany, Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable

### Community 24 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 25 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 26 - "require"
Cohesion: 0.33
Nodes (6): require, laravel/fortify, laravel/framework, laravel/tinker, matomo/device-detector, php

### Community 27 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 28 - "AuthValidationRulesTest.php"
Cohesion: 0.40
Nodes (4): Illuminate\Support\Facades\Validator, {closure#1}(), {closure#2}(), {closure#3}()

### Community 33 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 34 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 146 - "ProfilePageTest.php"
Cohesion: 0.12
Nodes (14): LoginHistory, Illuminate\Database\Eloquent\Attributes\Fillable, Illuminate\Database\Eloquent\Model, Illuminate\Database\Eloquent\Relations\BelongsTo, {closure#1}(), {closure#10}(), {closure#11}(), {closure#3}() (+6 more)

## Knowledge Gaps
- **135 isolated node(s):** `php`, `savedTheme`, `$schema`, `name`, `type` (+130 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 340 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **115 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User` to `UserRole`, `FortifyServiceProvider.php`, `.view`, `AuthValidationRules`, `UserFactory.php`, `PageRenderingTest.php`, `.password`, `RoleAccessTest.php`, `User Management in Admin and Developer Areas`, `ProfilePageTest.php`, `AuthenticationFlowTest.php`, `Illuminate\Foundation\Testing\RefreshDatabase`, `User.php`, `AuthValidationRulesTest.php`?**
  _High betweenness centrality (0.191) - this node is a cross-community bridge._
- **Why does `UserRole` connect `UserRole` to `FortifyServiceProvider.php`, `.view`, `AuthValidationRules`, `UserFactory.php`, `User`, `PageRenderingTest.php`, `.password`, `RoleAccessTest.php`, `User Management in Admin and Developer Areas`, `AuthenticationFlowTest.php`, `Illuminate\Foundation\Testing\RefreshDatabase`, `User.php`?**
  _High betweenness centrality (0.053) - this node is a cross-community bridge._
- **Why does `Validation and persistence` connect `User Management in Admin and Developer Areas` to `UserRole`, `User`?**
  _High betweenness centrality (0.014) - this node is a cross-community bridge._
- **Are the 7 inferred relationships involving `User` (e.g. with `Task 1: Add the role domain model and database column` and `Task 2: Install and configure Fortify with Blade authentication views`) actually correct?**
  _`User` has 7 INFERRED edges - model-reasoned connections that need verification._
- **Are the 7 inferred relationships involving `UserRole` (e.g. with `Authentication, Admin, and Developer Areas Implementation Plan` and `Global Constraints`) actually correct?**
  _`UserRole` has 7 INFERRED edges - model-reasoned connections that need verification._
- **What connects `php`, `savedTheme`, `$schema` to the rest of the system?**
  _135 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `UserRole` be split into smaller, more focused modules?**
  _Cohesion score 0.050724637681159424 - nodes in this community are weakly interconnected._