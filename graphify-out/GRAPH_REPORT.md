# Graph Report - laradocker  (2026-09-26)

## Corpus Check
- 133 files · ~78,036 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 33 file(s) not represented in the graph (top: (none) 19, .example 4, .conf 3)

## Summary
- 760 nodes · 1149 edges · 107 communities (43 shown, 64 thin omitted)
- Extraction: 96% EXTRACTED · 4% INFERRED · 0% AMBIGUOUS · INFERRED: 49 edges (avg confidence: 0.89)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `7b3502b1`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- package.json
- Laravel-проєкти без домену
- Authentication, Admin, and Developer Areas
- Розгортання на VPS
- image-resize.js
- AuthValidationRules
- tailadmin/package.json
- Illuminate\Database\Schema\Blueprint
- require-dev
- fortify.php
- Laravel-проєкти з доменами
- bootstrap/app.php
- webpack.config.js
- TailAdmin - Free Tailwind Admin Dashboard Template
- logging.php
- artisan
- console.php
- laravel-boost
- UserPolicy
- entrypoint.sh
- FortifyServiceProvider.php
- index.js
- devDependencies
- calendar-init.js
- User Management in Admin and Developer Areas
- UserFactory.php
- dependencies
- scripts
- config
- psr-4
- require
- autoload-dev
- extra
- UserManagementActionsTest.php
- UserRole
- DeveloperUserManagementTest.php
- User
- User.php
- ApplicationActionsTest.php
- author
- scripts
- TestCase
- ref_dropzone_dist_dropzone_css
- ref_flatpickr_dist_flatpickr_min_css
- ref_fullcalendar_daygrid
- ref_fullcalendar_interaction
- ref_fullcalendar_multimonth
- ref_fullcalendar_skeleton_css
- ref_fullcalendar_themes_classic
- ref_fullcalendar_themes_classic_palette_css
- ref_fullcalendar_themes_classic_theme_css
- ref_fullcalendar_timegrid
- ref_jsvectormap_dist_jsvectormap_min_css
- ref_jsvectormap_dist_maps_world
- ref_laravel_vite_plugin_fonts
- i18n.js
- AuthenticationFlowTest.php
- Illuminate\Foundation\Http\FormRequest
- ResolveUserLandingRoute
- AppServiceProvider
- RoleAccessTest.php

## God Nodes (most connected - your core abstractions)
1. `User` - 150 edges
2. `UserRole` - 84 edges
3. `AuthValidationRules` - 20 edges
4. `Розгортання на VPS` - 13 edges
5. `UserController` - 11 edges
6. `UserController` - 11 edges
7. `require-dev` - 11 edges
8. `User Management in Admin and Developer Areas` - 11 edges
9. `UserPolicy` - 10 edges
10. `Laravel-проєкти з доменами` - 10 edges

## Surprising Connections (you probably didn't know these)
- `Global Constraints` --references--> `UserRole`  [INFERRED]
  docs/superpowers/plans/2026-09-22-auth-admin-developer-areas.md → app/Enums/UserRole.php
- `Roles and access` --references--> `UserRole`  [INFERRED]
  docs/superpowers/specs/2026-09-22-auth-admin-developer-design.md → app/Enums/UserRole.php
- `Global Constraints` --references--> `User`  [INFERRED]
  docs/superpowers/plans/2026-09-26-user-management.md → app/Models/User.php
- `Authentication, Admin, and Developer Areas Implementation Plan` --references--> `UserRole`  [INFERRED]
  docs/superpowers/plans/2026-09-22-auth-admin-developer-areas.md → app/Enums/UserRole.php
- `Task 1: Add the role domain model and database column` --references--> `UserRole`  [INFERRED]
  docs/superpowers/plans/2026-09-22-auth-admin-developer-areas.md → app/Enums/UserRole.php

## Import Cycles
- None detected.

## Communities (107 total, 64 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 1 - "package.json"
Cohesion: 0.09
Nodes (23): dependencies, alpinejs, devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite (+15 more)

### Community 2 - "Laravel-проєкти без домену"
Cohesion: 0.12
Nodes (16): 1. Схема портів, 2. Локальний запуск, 3. Production-запуск на VPS, 4.1. Конфігурація ladockervps1, 4.2. Конфігурація ladockervps2, 4.3. Активація sites-enabled, 4. Мінімальний reverse proxy Nginx, 5. Видалення непотрібного проєкту (+8 more)

### Community 3 - "Authentication, Admin, and Developer Areas"
Cohesion: 0.25
Nodes (7): Application areas, Authentication, Authentication, Admin, and Developer Areas, Goal, Roles and access, UI structure, Verification

### Community 4 - "Розгортання на VPS"
Cohesion: 0.05
Nodes (37): Development mounts and production volumes, Docker architecture, Images, Multiple projects on one VPS, Networks and ports, 1. Підготовка сервера, 2. Клонування проєкту та production-конфігурація, 3. Побудова та запуск production-стека (+29 more)

### Community 5 - "image-resize.js"
Cohesion: 0.33
Nodes (12): animate(), calc(), canMove(), hintHide(), onDown(), onMouseDown(), onMove(), onTouchDown() (+4 more)

### Community 6 - "AuthValidationRules"
Cohesion: 0.11
Nodes (13): RegisterUser, SetUserPassword, UpdateUserProfile, CreateNewUser, ResetUserPassword, UpdateUserPassword, UpdateUserProfileInformation, AuthValidationRules (+5 more)

### Community 7 - "tailadmin/package.json"
Cohesion: 0.08
Nodes (25): @babel/core, babel-loader, @babel/preset-env, css-loader, file-loader, html-loader, postcss, postcss-loader (+17 more)

### Community 8 - "Illuminate\Database\Schema\Blueprint"
Cohesion: 0.13
Nodes (13): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+5 more)

### Community 9 - "require-dev"
Cohesion: 0.18
Nodes (11): require-dev, fakerphp/faker, fruitcake/laravel-debugbar, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery (+3 more)

### Community 11 - "Laravel-проєкти з доменами"
Cohesion: 0.12
Nodes (16): 1. Схема доменів і портів, 2. Локальний запуск, 3. Підготовка доменів, Cloudflare і сертифікатів, 4. Production-запуск на VPS, 5.1. Конфігурація example1.com, 5.2. Конфігурація example2.com, 5. Системний Nginx як reverse proxy, 6. Активація Nginx і firewall (+8 more)

### Community 14 - "bootstrap/app.php"
Cohesion: 0.19
Nodes (10): {closure#1}(), EnsureUserHasRole, {closure#1}(), {closure#2}(), {closure#3}(), Closure, Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions (+2 more)

### Community 15 - "webpack.config.js"
Cohesion: 0.15
Nodes (13): copy-webpack-plugin, glob, html-webpack-plugin, mini-css-extract-plugin, ref_path, CopyWebpackPlugin, generateHTMLPlugins(), { globSync } (+5 more)

### Community 16 - "TailAdmin - Free Tailwind Admin Dashboard Template"
Cohesion: 0.07
Nodes (26): Breaking Changes, Cloning the Repository, Components, Demos, Feature Comparison, Free Version, Installation, License (+18 more)

### Community 17 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 21 - "UserPolicy"
Cohesion: 0.16
Nodes (9): UserPolicy, Global Constraints, Review Focus, Task 1: Add and test the shared user authorization policy, Task 2: Add shared user-management persistence actions and request validation, Task 3: Add the admin and developer CRUD controllers and route boundaries, Task 4: Build the separated user-management UI and navigation, Task 5: Run the complete verification pass and update the code graph (+1 more)

### Community 23 - "FortifyServiceProvider.php"
Cohesion: 0.06
Nodes (25): CreateUser, DeleteUser, UpdateUser, UserController, Controller, UserController, StoreUserRequest, {closure#1}() (+17 more)

### Community 29 - "index.js"
Cohesion: 0.14
Nodes (13): ref_alpinejs, @alpinejs/persist, apexcharts, dropzone, flatpickr, jsvectormap, reference_tailadmin_src_css_style, chart01() (+5 more)

### Community 45 - "devDependencies"
Cohesion: 0.09
Nodes (22): devDependencies, @babel/core, babel-loader, @babel/preset-env, copy-webpack-plugin, css-loader, file-loader, glob (+14 more)

### Community 46 - "calendar-init.js"
Cohesion: 0.26
Nodes (8): fullcalendar, closeModal(), handleAddOrUpdateEvent(), handleDateSelect(), handleEventClick(), handleOpenAddModal(), openModal(), resetModalFields()

### Community 47 - "User Management in Admin and Developer Areas"
Cohesion: 0.17
Nodes (11): Authorization architecture, Confirmed requirements, Error handling and security, Goal, Out of scope, Role and visibility matrix, Route boundaries and namespaces, Testing strategy (+3 more)

### Community 48 - "UserFactory.php"
Cohesion: 0.08
Nodes (18): UserFactory, DatabaseSeeder, UserSeeder, Authentication, Admin, and Developer Areas Implementation Plan, Global Constraints, Review Focus, Task 1: Add the role domain model and database column, Task 2: Install and configure Fortify with Blade authentication views (+10 more)

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

### Community 69 - "UserManagementActionsTest.php"
Cohesion: 0.13
Nodes (14): Illuminate\Routing\Route, Illuminate\Support\Facades\Validator, {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}() (+6 more)

### Community 70 - "UserRole"
Cohesion: 0.12
Nodes (24): UserRole, {closure#1}(), {closure#10}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#2}(), {closure#3}() (+16 more)

### Community 71 - "DeveloperUserManagementTest.php"
Cohesion: 0.22
Nodes (6): {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}()

### Community 72 - "User"
Cohesion: 0.12
Nodes (19): User, {closure#1}(), {closure#2}(), {closure#10}(), {closure#11}(), {closure#12}(), {closure#5}(), {closure#6}() (+11 more)

### Community 73 - "User.php"
Cohesion: 0.25
Nodes (6): Illuminate\Contracts\Auth\MustVerifyEmail, Illuminate\Database\Eloquent\Attributes\Fillable, Illuminate\Database\Eloquent\Attributes\Hidden, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable

### Community 75 - "ApplicationActionsTest.php"
Cohesion: 0.15
Nodes (10): Illuminate\Auth\Notifications\VerifyEmail, Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Support\Facades\Notification, {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#1}() (+2 more)

### Community 79 - "author"
Cohesion: 0.50
Nodes (4): author, email, name, url

### Community 80 - "scripts"
Cohesion: 0.50
Nodes (4): scripts, build, sort, start

### Community 101 - "i18n.js"
Cohesion: 0.24
Nodes (8): i18next, i18next-browser-languagedetector, reference_tailadmin_src_images_flag_flag_us, applyHtmlLanguageAttributes(), isRTL(), setupI18n(), supportedLocales, reference_tailadmin_src_locales_en_common

### Community 102 - "AuthenticationFlowTest.php"
Cohesion: 0.22
Nodes (7): Illuminate\Foundation\Http\Middleware\PreventRequestForgery, {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}()

### Community 103 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.19
Nodes (6): StoreUserRequest, UpdateUserRequest, UpdateUserRequest, Illuminate\Foundation\Http\FormRequest, Illuminate\Validation\Rule, Illuminate\Validation\Rules\Password

### Community 104 - "ResolveUserLandingRoute"
Cohesion: 0.33
Nodes (4): ResolveUserLandingRoute, LoginResponse, Laravel\Fortify\Contracts\LoginResponse, Symfony\Component\HttpFoundation\RedirectResponse

### Community 105 - "AppServiceProvider"
Cohesion: 0.28
Nodes (3): AppServiceProvider, FortifyServiceProvider, Illuminate\Support\ServiceProvider

### Community 106 - "RoleAccessTest.php"
Cohesion: 0.25
Nodes (5): {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}()

## Knowledge Gaps
- **221 isolated node(s):** `php`, `name`, `version`, `description`, `main` (+216 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 365 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **64 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User` to `UserManagementActionsTest.php`, `AuthValidationRules`, `Illuminate\Foundation\Http\FormRequest`, `ResolveUserLandingRoute`, `User.php`, `AuthenticationFlowTest.php`, `ApplicationActionsTest.php`, `RoleAccessTest.php`, `UserRole`, `bootstrap/app.php`, `User Management in Admin and Developer Areas`, `UserFactory.php`, `UserPolicy`, `FortifyServiceProvider.php`, `DeveloperUserManagementTest.php`?**
  _High betweenness centrality (0.116) - this node is a cross-community bridge._
- **Why does `UserRole` connect `UserRole` to `Authentication, Admin, and Developer Areas`, `UserManagementActionsTest.php`, `AuthValidationRules`, `Illuminate\Foundation\Http\FormRequest`, `ResolveUserLandingRoute`, `User.php`, `AuthenticationFlowTest.php`, `ApplicationActionsTest.php`, `RoleAccessTest.php`, `User`, `bootstrap/app.php`, `User Management in Admin and Developer Areas`, `UserFactory.php`, `UserPolicy`, `FortifyServiceProvider.php`, `DeveloperUserManagementTest.php`?**
  _High betweenness centrality (0.047) - this node is a cross-community bridge._
- **Why does `Validation and persistence` connect `User Management in Admin and Developer Areas` to `User`, `UserRole`?**
  _High betweenness centrality (0.013) - this node is a cross-community bridge._
- **Are the 7 inferred relationships involving `User` (e.g. with `Task 1: Add the role domain model and database column` and `Task 2: Install and configure Fortify with Blade authentication views`) actually correct?**
  _`User` has 7 INFERRED edges - model-reasoned connections that need verification._
- **Are the 7 inferred relationships involving `UserRole` (e.g. with `Authentication, Admin, and Developer Areas Implementation Plan` and `Global Constraints`) actually correct?**
  _`UserRole` has 7 INFERRED edges - model-reasoned connections that need verification._
- **What connects `php`, `name`, `version` to the rest of the system?**
  _221 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.08666666666666667 - nodes in this community are weakly interconnected._