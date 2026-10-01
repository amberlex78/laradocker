# Graph Report - laradocker  (2026-10-01)

## Corpus Check
- 210 files · ~48,991 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 30 file(s) not represented in the graph (top: (none) 16, .example 4, .conf 3)

## Summary
- 898 nodes · 1463 edges · 150 communities (33 shown, 117 thin omitted)
- Extraction: 95% EXTRACTED · 5% INFERRED · 0% AMBIGUOUS · INFERRED: 72 edges (avg confidence: 0.92)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `397420c5`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- UserRole
- User.php
- .view
- Розгортання на VPS
- Illuminate\Http\Request
- package.json
- DatabaseSessionLifecycleTest.php
- Illuminate\Database\Schema\Blueprint
- User
- PageRenderingTest.php
- UserSessionService
- Laravel-проєкти без домену
- Laravel-проєкти з доменами
- User Management in Admin and Developer Areas
- require-dev
- composer.json
- scripts
- AuthenticationFlowTest.php
- LoginActivityService
- UserPolicy
- config
- psr-4
- require
- logging.php
- Illuminate\Foundation\Testing\RefreshDatabase
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
- AdminUserManagementTest.php
- ProfilePageTest.php
- UserManagementActionsTest.php
- DeveloperUserManagementTest.php
- FortifyActionsTest.php
- Pest.php
- UserSessionServiceTest.php

## God Nodes (most connected - your core abstractions)
1. `User` - 225 edges
2. `UserRole` - 85 edges
3. `UserSessionService` - 30 edges
4. `LoginHistory` - 24 edges
5. `LoginActivityService` - 22 edges
6. `AuthValidationRules` - 20 edges
7. `DeviceDetectionService` - 18 edges
8. `LogoutReason` - 16 edges
9. `Розгортання на VPS` - 13 edges
10. `UserController` - 11 edges

## Surprising Connections (you probably didn't know these)
- `Global Constraints` --references--> `User`  [INFERRED]
  docs/superpowers/plans/2026-09-26-user-management.md → app/Models/User.php
- ``DeviceDetectionService`` --references--> `DeviceDetectionService`  [INFERRED]
  docs/superpowers/specs/2026-10-01-auth-services-refactor-design.md → app/Services/Auth/DeviceDetectionService.php
- ``UserSessionService`` --references--> `UserSessionService`  [INFERRED]
  docs/superpowers/specs/2026-10-01-auth-services-refactor-design.md → app/Services/Auth/UserSessionService.php
- `Authentication, Admin, and Developer Areas Implementation Plan` --references--> `UserRole`  [INFERRED]
  docs/superpowers/plans/2026-09-22-auth-admin-developer-areas.md → app/Enums/UserRole.php
- `Global Constraints` --references--> `UserRole`  [INFERRED]
  docs/superpowers/plans/2026-09-22-auth-admin-developer-areas.md → app/Enums/UserRole.php

## Import Cycles
- None detected.

## Communities (150 total, 117 thin omitted)

### Community 0 - "UserRole"
Cohesion: 0.19
Nodes (15): UserRole, {closure#1}(), {closure#1}(), {closure#10}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}() (+7 more)

### Community 1 - "User.php"
Cohesion: 0.18
Nodes (8): Illuminate\Database\Eloquent\Attributes\Fillable, Illuminate\Database\Eloquent\Attributes\Hidden, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Model, Illuminate\Database\Eloquent\Relations\BelongsTo, Illuminate\Database\Eloquent\Relations\HasMany, Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable

### Community 2 - ".view"
Cohesion: 0.05
Nodes (26): CreateUser, DeleteUser, UpdateUser, AccountController, AccountSessionController, UserController, Controller, UserController (+18 more)

### Community 3 - "Розгортання на VPS"
Cohesion: 0.05
Nodes (37): Development mounts and production volumes, Docker architecture, Images, Multiple projects on one VPS, Networks and ports, 1. Підготовка сервера, 2. Клонування проєкту та production-конфігурація, 3. Побудова та запуск production-стека (+29 more)

### Community 4 - "Illuminate\Http\Request"
Cohesion: 0.06
Nodes (31): CreateNewUser, ResetUserPassword, UpdateUserPassword, UpdateUserProfileInformation, AdvertiseClientHints, {closure#1}(), EnsureUserHasRole, {closure#4}() (+23 more)

### Community 5 - "package.json"
Cohesion: 0.07
Nodes (32): dependencies, alpinejs, flowbite, devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite (+24 more)

### Community 6 - "DatabaseSessionLifecycleTest.php"
Cohesion: 0.09
Nodes (18): UserFactory, DatabaseSeeder, UserSeeder, Task 2: Install and configure Fortify with Blade authentication views, Illuminate\Database\Console\Seeds\WithoutModelEvents, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Database\Seeder, Illuminate\Session\DatabaseSessionHandler (+10 more)

### Community 7 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.06
Nodes (26): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+18 more)

### Community 8 - "User"
Cohesion: 0.14
Nodes (22): User, {closure#1}(), {closure#2}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+14 more)

### Community 9 - "PageRenderingTest.php"
Cohesion: 0.08
Nodes (19): {closure#10}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#17}(), {closure#18}() (+11 more)

### Community 10 - "UserSessionService"
Cohesion: 0.07
Nodes (20): {closure#1}(), LogoutReason, DeviceDetectionService, {closure#3}(), {closure#4}(), UserSessionService, DeviceDetector, DeviceDetector\ClientHints (+12 more)

### Community 12 - "Laravel-проєкти без домену"
Cohesion: 0.12
Nodes (16): 1. Схема портів, 2. Локальний запуск, 3. Production-запуск на VPS, 4.1. Конфігурація laradockervps1, 4.2. Конфігурація laradockervps2, 4.3. Активація sites-enabled, 4. Мінімальний reverse proxy Nginx, 5. Видалення непотрібного проєкту (+8 more)

### Community 13 - "Laravel-проєкти з доменами"
Cohesion: 0.12
Nodes (16): 1. Схема доменів і портів, 2. Локальний запуск, 3. Підготовка доменів, Cloudflare і сертифікатів, 4. Production-запуск на VPS, 5.1. Конфігурація example1.com, 5.2. Конфігурація example2.com, 5. Системний Nginx як reverse proxy, 6. Активація Nginx і firewall (+8 more)

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
Cohesion: 0.11
Nodes (15): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#18}(), {closure#2}() (+7 more)

### Community 21 - "LoginActivityService"
Cohesion: 0.06
Nodes (29): LoginResponse, RegisterResponse, RecordLogoutActivity, RecordRememberedLoginActivity, AppServiceProvider, FortifyServiceProvider, LoginActivityService, Task 3: Extract login activity recording (+21 more)

### Community 22 - "UserPolicy"
Cohesion: 0.16
Nodes (9): UserPolicy, Global Constraints, Review Focus, Task 1: Add and test the shared user authorization policy, Task 2: Add shared user-management persistence actions and request validation, Task 3: Add the admin and developer CRUD controllers and route boundaries, Task 4: Build the separated user-management UI and navigation, Task 5: Run the complete verification pass and update the code graph (+1 more)

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

### Community 28 - "Illuminate\Foundation\Testing\RefreshDatabase"
Cohesion: 0.11
Nodes (11): Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Support\Facades\Validator, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#4}() (+3 more)

### Community 33 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 34 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 144 - "AdminUserManagementTest.php"
Cohesion: 0.15
Nodes (10): {closure#10}(), {closure#11}(), {closure#12}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}() (+2 more)

### Community 146 - "ProfilePageTest.php"
Cohesion: 0.07
Nodes (30): LoginHistory, Illuminate\Database\QueryException, {closure#1}(), {closure#3}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+22 more)

### Community 149 - "UserManagementActionsTest.php"
Cohesion: 0.18
Nodes (11): Illuminate\Routing\Route, {closure#1}(), {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+3 more)

### Community 151 - "DeveloperUserManagementTest.php"
Cohesion: 0.20
Nodes (7): Illuminate\Foundation\Http\Middleware\PreventRequestForgery, {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}()

### Community 152 - "FortifyActionsTest.php"
Cohesion: 0.25
Nodes (7): Illuminate\Auth\Notifications\VerifyEmail, Illuminate\Support\Facades\Notification, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 156 - "UserSessionServiceTest.php"
Cohesion: 0.33
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

## Knowledge Gaps
- **142 isolated node(s):** `php`, `savedTheme`, `$schema`, `name`, `type` (+137 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 381 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **117 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User` to `UserRole`, `User.php`, `.view`, `Illuminate\Http\Request`, `DatabaseSessionLifecycleTest.php`, `PageRenderingTest.php`, `UserSessionService`, `User Management in Admin and Developer Areas`, `AdminUserManagementTest.php`, `UserSessionServiceTest.php`, `ProfilePageTest.php`, `AuthenticationFlowTest.php`, `LoginActivityService`, `UserPolicy`, `DeveloperUserManagementTest.php`, `FortifyActionsTest.php`, `UserManagementActionsTest.php`, `Illuminate\Foundation\Testing\RefreshDatabase`?**
  _High betweenness centrality (0.268) - this node is a cross-community bridge._
- **Why does `UserRole` connect `UserRole` to `User.php`, `.view`, `Illuminate\Http\Request`, `DatabaseSessionLifecycleTest.php`, `User`, `PageRenderingTest.php`, `UserSessionService`, `User Management in Admin and Developer Areas`, `AdminUserManagementTest.php`, `AuthenticationFlowTest.php`, `UserManagementActionsTest.php`, `UserPolicy`, `DeveloperUserManagementTest.php`, `Illuminate\Foundation\Testing\RefreshDatabase`, `.workspace`?**
  _High betweenness centrality (0.045) - this node is a cross-community bridge._
- **Why does `UserSessionService` connect `UserSessionService` to `UserSessionServiceTest.php`, `.view`, `Illuminate\Http\Request`, `LoginActivityService`?**
  _High betweenness centrality (0.032) - this node is a cross-community bridge._
- **Are the 7 inferred relationships involving `User` (e.g. with `Task 1: Add the role domain model and database column` and `Task 2: Install and configure Fortify with Blade authentication views`) actually correct?**
  _`User` has 7 INFERRED edges - model-reasoned connections that need verification._
- **Are the 7 inferred relationships involving `UserRole` (e.g. with `Authentication, Admin, and Developer Areas Implementation Plan` and `Global Constraints`) actually correct?**
  _`UserRole` has 7 INFERRED edges - model-reasoned connections that need verification._
- **Are the 4 inferred relationships involving `UserSessionService` (e.g. with `Authentication Services Refactor Implementation Plan` and `Task 1: Add focused service-contract tests`) actually correct?**
  _`UserSessionService` has 4 INFERRED edges - model-reasoned connections that need verification._
- **What connects `php`, `savedTheme`, `$schema` to the rest of the system?**
  _142 weakly-connected nodes found - possible documentation gaps or missing edges._