# Graph Report - laradocker  (2026-10-02)

## Corpus Check
- 213 files · ~54,528 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 32 file(s) not represented in the graph (top: (none) 16, .example 6, .conf 3)

## Summary
- 915 nodes · 1486 edges · 164 communities (45 shown, 119 thin omitted)
- Extraction: 95% EXTRACTED · 5% INFERRED · 0% AMBIGUOUS · INFERRED: 72 edges (avg confidence: 0.92)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `df492b72`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- UserRole
- User.php
- .view
- UserSessionService
- package.json
- UserFactory.php
- Illuminate\Database\Schema\Blueprint
- User
- PageRenderingTest.php
- DeviceDetectionService
- Дизайн реструктуризації документації
- LoginActivityService
- Illuminate\Http\Request
- User Management in Admin and Developer Areas
- require-dev
- Review Focus
- composer.json
- scripts
- AuthenticationFlowTest.php
- DatabaseSessionLifecycleTest.php
- UserPolicy
- Authentication Services Refactor Design
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
- EnsureUserHasRole.php
- Розгортання на Ubuntu VPS
- UserManagementActionsTest.php
- Authentication Services Refactor Implementation Plan
- DeveloperUserManagementTest.php
- FortifyActionsTest.php
- bootstrap/app.php
- Необов'язкова інтеграція Cloudflare
- Pest.php
- UserSessionServiceTest.php
- Локальна розробка
- Docker-архітектура
- Діагностика
- Експлуатація production-середовища
- Laravel Docker Starter

## God Nodes (most connected - your core abstractions)
1. `User` - 225 edges
2. `UserRole` - 85 edges
3. `UserSessionService` - 30 edges
4. `LoginHistory` - 24 edges
5. `LoginActivityService` - 22 edges
6. `AuthValidationRules` - 20 edges
7. `DeviceDetectionService` - 18 edges
8. `LogoutReason` - 16 edges
9. `Дизайн реструктуризації документації` - 13 edges
10. `UserController` - 11 edges

## Surprising Connections (you probably didn't know these)
- `Global Constraints` --references--> `User`  [INFERRED]
  docs/superpowers/plans/2026-09-26-user-management.md → app/Models/User.php
- `Task 2: Extract device detection` --references--> `DeviceDetectionService`  [INFERRED]
  docs/superpowers/plans/2026-10-01-auth-services-refactor.md → app/Services/Auth/DeviceDetectionService.php
- ``DeviceDetectionService`` --references--> `DeviceDetectionService`  [INFERRED]
  docs/superpowers/specs/2026-10-01-auth-services-refactor-design.md → app/Services/Auth/DeviceDetectionService.php
- ``LoginActivityService`` --references--> `LoginActivityService`  [INFERRED]
  docs/superpowers/specs/2026-10-01-auth-services-refactor-design.md → app/Services/Auth/LoginActivityService.php
- ``UserSessionService`` --references--> `UserSessionService`  [INFERRED]
  docs/superpowers/specs/2026-10-01-auth-services-refactor-design.md → app/Services/Auth/UserSessionService.php

## Import Cycles
- None detected.

## Communities (164 total, 119 thin omitted)

### Community 0 - "UserRole"
Cohesion: 0.19
Nodes (15): UserRole, {closure#1}(), {closure#1}(), {closure#10}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}() (+7 more)

### Community 1 - "User.php"
Cohesion: 0.18
Nodes (8): Illuminate\Database\Eloquent\Attributes\Fillable, Illuminate\Database\Eloquent\Attributes\Hidden, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Model, Illuminate\Database\Eloquent\Relations\BelongsTo, Illuminate\Database\Eloquent\Relations\HasMany, Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable

### Community 2 - ".view"
Cohesion: 0.05
Nodes (26): CreateUser, DeleteUser, UpdateUser, AccountController, AccountSessionController, UserController, Controller, UserController (+18 more)

### Community 4 - "UserSessionService"
Cohesion: 0.06
Nodes (21): CreateNewUser, ResetUserPassword, UpdateUserPassword, UpdateUserProfileInformation, {closure#1}(), LogoutReason, {closure#3}(), {closure#4}() (+13 more)

### Community 5 - "package.json"
Cohesion: 0.07
Nodes (32): dependencies, alpinejs, flowbite, devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite (+24 more)

### Community 6 - "UserFactory.php"
Cohesion: 0.12
Nodes (11): UserFactory, DatabaseSeeder, UserSeeder, Task 2: Install and configure Fortify with Blade authentication views, Illuminate\Database\Console\Seeds\WithoutModelEvents, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Database\Seeder, Illuminate\Support\Facades\Hash (+3 more)

### Community 7 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.06
Nodes (26): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+18 more)

### Community 8 - "User"
Cohesion: 0.14
Nodes (22): User, {closure#1}(), {closure#2}(), {closure#1}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}() (+14 more)

### Community 9 - "PageRenderingTest.php"
Cohesion: 0.07
Nodes (19): {closure#11}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#17}(), {closure#18}(), {closure#19}() (+11 more)

### Community 10 - "DeviceDetectionService"
Cohesion: 0.26
Nodes (4): DeviceDetectionService, DeviceDetector, DeviceDetector\ClientHints, DeviceDetector\DeviceDetector

### Community 12 - "Дизайн реструктуризації документації"
Cohesion: 0.10
Nodes (19): `docs/architecture.md`, `docs/cloudflare.md`, `docs/deployment.md`, `docs/development.md`, `docs/operations.md`, Env-шаблони, Nginx-шаблони, `README.md` (+11 more)

### Community 13 - "LoginActivityService"
Cohesion: 0.22
Nodes (7): LoginResponse, RecordLogoutActivity, LoginActivityService, Task 3: Extract login activity recording, Illuminate\Auth\Events\Logout, Laravel\Fortify\Contracts\LoginResponse, Symfony\Component\HttpFoundation\RedirectResponse

### Community 14 - "Illuminate\Http\Request"
Cohesion: 0.20
Nodes (12): RegisterResponse, {closure#4}(), {closure#6}(), {closure#7}(), {closure#8}(), Illuminate\Cache\RateLimiting\Limit, Illuminate\Http\JsonResponse, Illuminate\Http\Request (+4 more)

### Community 15 - "User Management in Admin and Developer Areas"
Cohesion: 0.08
Nodes (25): Authentication, Admin, and Developer Areas Implementation Plan, Global Constraints, Review Focus, Task 1: Add the role domain model and database column, Task 3: Add role middleware and route boundaries, Task 4: Build the public, account, admin, and developer Blade shells, Task 5: Run the complete verification pass, Application areas (+17 more)

### Community 16 - "require-dev"
Cohesion: 0.18
Nodes (11): require-dev, fakerphp/faker, fruitcake/laravel-debugbar, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery (+3 more)

### Community 17 - "Review Focus"
Cohesion: 0.17
Nodes (11): Documentation Restructure Implementation Plan, Global Constraints, Review Focus, Task 1: Канонічні env- і Nginx-шаблони, Task 2: Переписати Docker architecture reference, Task 3: Переписати local development guide, Task 4: Переписати Ubuntu VPS deployment guide, Task 5: Додати optional Cloudflare guide (+3 more)

### Community 18 - "composer.json"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 19 - "scripts"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 20 - "AuthenticationFlowTest.php"
Cohesion: 0.11
Nodes (15): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#16}(), {closure#17}(), {closure#18}(), {closure#2}() (+7 more)

### Community 21 - "DatabaseSessionLifecycleTest.php"
Cohesion: 0.10
Nodes (16): RecordRememberedLoginActivity, AppServiceProvider, FortifyServiceProvider, Illuminate\Auth\Events\Login, Illuminate\Foundation\Http\Middleware\PreventRequestForgery, Illuminate\Session\DatabaseSessionHandler, Illuminate\Session\Store, Illuminate\Support\Facades\Auth (+8 more)

### Community 22 - "UserPolicy"
Cohesion: 0.16
Nodes (9): UserPolicy, Global Constraints, Review Focus, Task 1: Add and test the shared user authorization policy, Task 2: Add shared user-management persistence actions and request validation, Task 3: Add the admin and developer CRUD controllers and route boundaries, Task 4: Build the separated user-management UI and navigation, Task 5: Run the complete verification pass and update the code graph (+1 more)

### Community 23 - "Authentication Services Refactor Design"
Cohesion: 0.17
Nodes (11): Authentication Services Refactor Design, Current state, `DeviceDetectionService`, Explicitly out of scope, Goal, `LoginActivityService`, Preserved behavior, Proposed architecture (+3 more)

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

### Community 147 - "EnsureUserHasRole.php"
Cohesion: 0.31
Nodes (5): AdvertiseClientHints, {closure#1}(), EnsureUserHasRole, Closure, Symfony\Component\HttpFoundation\Response

### Community 148 - "Розгортання на Ubuntu VPS"
Cohesion: 0.20
Nodes (10): Вибір публічного доступу, Вимоги, Домен і звичайний HTTPS, Доступ без домену, Клонування та production-конфігурація, Кілька проєктів на одному VPS, Наступні кроки, Перевірка приватного Docker stack (+2 more)

### Community 149 - "UserManagementActionsTest.php"
Cohesion: 0.18
Nodes (11): Illuminate\Routing\Route, {closure#1}(), {closure#10}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+3 more)

### Community 150 - "Authentication Services Refactor Implementation Plan"
Cohesion: 0.29
Nodes (6): Authentication Services Refactor Implementation Plan, Global Constraints, Review Focus, Task 1: Add focused service-contract tests, Task 2: Extract device detection, Task 5: Remove transition leftovers and verify the refactor

### Community 151 - "DeveloperUserManagementTest.php"
Cohesion: 0.22
Nodes (6): {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}()

### Community 152 - "FortifyActionsTest.php"
Cohesion: 0.25
Nodes (7): Illuminate\Auth\Notifications\VerifyEmail, Illuminate\Support\Facades\Notification, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 153 - "bootstrap/app.php"
Cohesion: 0.32
Nodes (6): {closure#1}(), {closure#2}(), {closure#3}(), Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware

### Community 154 - "Необов'язкова інтеграція Cloudflare"
Cohesion: 0.25
Nodes (8): Cloudflare networks і client IP, Cloudflare Origin Certificate, DNS і SSL/TLS mode, Nginx reverse proxy, Необов'язкова інтеграція Cloudflare, Перевірка, Передумови, Повернення до звичайного HTTPS

### Community 156 - "UserSessionServiceTest.php"
Cohesion: 0.33
Nodes (5): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}()

### Community 158 - "Локальна розробка"
Cohesion: 0.25
Nodes (8): Production-образ локально, Вимоги, Діагностика, Кілька проєктів одночасно, Локальна розробка, Перший запуск, Створення нового проєкту, Щоденні команди

### Community 159 - "Docker-архітектура"
Cohesion: 0.29
Nodes (7): Compose-файли, Docker-архітектура, Внутрішні та host-порти, Дані та файлові системи, Мережі, Сервіси та образи, Шляхи HTTP-запиту

### Community 160 - "Діагностика"
Cohesion: 0.29
Nodes (7): 1. Конфігурація Docker Compose, 2. Контейнери та healthchecks, 3. Laravel, 4. Приватний upstream, 5. Системний Nginx, 6. Firewall, DNS і TLS, Діагностика

### Community 161 - "Експлуатація production-середовища"
Cohesion: 0.29
Nodes (7): Видалення одного проєкту, Відновлення MariaDB, Експлуатація production-середовища, Міграції, Оновлення застосунку, Резервна копія MariaDB, Статус і логи

### Community 162 - "Laravel Docker Starter"
Cohesion: 0.40
Nodes (5): Laravel Docker Starter, Вимоги, Основні команди, Швидкий старт, Що ви хочете зробити?

## Knowledge Gaps
- **158 isolated node(s):** `php`, `savedTheme`, `$schema`, `name`, `type` (+153 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 399 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **119 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User` to `UserRole`, `User.php`, `.view`, `UserSessionService`, `UserFactory.php`, `PageRenderingTest.php`, `LoginActivityService`, `User Management in Admin and Developer Areas`, `AdminUserManagementTest.php`, `ProfilePageTest.php`, `EnsureUserHasRole.php`, `AuthenticationFlowTest.php`, `DatabaseSessionLifecycleTest.php`, `UserPolicy`, `DeveloperUserManagementTest.php`, `FortifyActionsTest.php`, `UserManagementActionsTest.php`, `UserSessionServiceTest.php`, `Illuminate\Foundation\Testing\RefreshDatabase`?**
  _High betweenness centrality (0.259) - this node is a cross-community bridge._
- **Why does `UserRole` connect `UserRole` to `User.php`, `.view`, `UserSessionService`, `UserFactory.php`, `User`, `PageRenderingTest.php`, `User Management in Admin and Developer Areas`, `AdminUserManagementTest.php`, `EnsureUserHasRole.php`, `AuthenticationFlowTest.php`, `UserManagementActionsTest.php`, `UserPolicy`, `DeveloperUserManagementTest.php`, `Illuminate\Foundation\Testing\RefreshDatabase`, `.workspace`?**
  _High betweenness centrality (0.043) - this node is a cross-community bridge._
- **Why does `UserSessionService` connect `UserSessionService` to `.view`, `DeviceDetectionService`, `Authentication Services Refactor Implementation Plan`, `Authentication Services Refactor Design`, `UserSessionServiceTest.php`?**
  _High betweenness centrality (0.031) - this node is a cross-community bridge._
- **Are the 7 inferred relationships involving `User` (e.g. with `Task 1: Add the role domain model and database column` and `Task 2: Install and configure Fortify with Blade authentication views`) actually correct?**
  _`User` has 7 INFERRED edges - model-reasoned connections that need verification._
- **Are the 7 inferred relationships involving `UserRole` (e.g. with `Authentication, Admin, and Developer Areas Implementation Plan` and `Global Constraints`) actually correct?**
  _`UserRole` has 7 INFERRED edges - model-reasoned connections that need verification._
- **Are the 4 inferred relationships involving `UserSessionService` (e.g. with `Authentication Services Refactor Implementation Plan` and `Task 1: Add focused service-contract tests`) actually correct?**
  _`UserSessionService` has 4 INFERRED edges - model-reasoned connections that need verification._
- **What connects `php`, `savedTheme`, `$schema` to the rest of the system?**
  _158 weakly-connected nodes found - possible documentation gaps or missing edges._