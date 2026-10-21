# Graph Report - laradocker  (2026-10-21)

## Corpus Check
- 214 files · ~55,480 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 32 file(s) not represented in the graph (top: (none) 16, .example 6, .conf 3)

## Summary
- 872 nodes · 1296 edges · 162 communities (29 shown, 133 thin omitted)
- Extraction: 94% EXTRACTED · 6% INFERRED · 0% AMBIGUOUS · INFERRED: 74 edges (avg confidence: 0.88)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `7d3b7bd1`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- User Community
- Illuminate Database Schema Blueprint
- view Community
- AuthenticationFlowTest php
- ProfilePageTest php
- package json
- Docker Architecture
- UserRole Community
- Illuminate Foundation Http FormRequest
- LoginActivityService Community
- AdminUserManagementTest php
- Illuminate Http Request
- DeviceDetectionService Community
- MariaDB Service
- UserManagementActionsTest php
- require-dev Community
- Flowbite View Reference Rule
- EnsureUserHasRole php
- DeveloperUserManagementTest php
- composer json
- scripts Community
- RoleAccessTest php
- AppServiceProvider Community
- bootstrap app php
- UserSeeder php
- Policy-Centered User Authorization
- record Community
- config Community
- Focused Authentication Services
- require Community
- Pest php
- psr-4 Community
- Compose Project Isolation
- autoload-dev Community
- extra Community
- laravel-boost Community
- Development Application Service
- entrypoint sh
- Authentication Admin and Developer Areas
- User Management Implementation Plan
- Authentication Services Refactor Implementation Plan
- Canonical Environment and Nginx Templates
- Documentation Restructure Implementation Plan
- Composer Tool Service
- Fortify Blade Authentication
- Web Crawler Policy

## God Nodes (most connected - your core abstractions)
1. `UserRole` - 88 edges
2. `User` - 75 edges
3. `UserSessionService` - 26 edges
4. `LogoutReason` - 20 edges
5. `AuthValidationRules` - 20 edges
6. `LoginActivityService` - 18 edges
7. `DeviceDetectionService` - 13 edges
8. `UserController` - 11 edges
9. `UserController` - 11 edges
10. `LoginHistory` - 11 edges

## Surprising Connections (you probably didn't know these)
- `{closure#1}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/Authentication/RoleAccessTest.php → app/Enums/UserRole.php
- `{closure#2}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/Authentication/RoleAccessTest.php → app/Enums/UserRole.php
- `{closure#4}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/Authentication/RoleAccessTest.php → app/Enums/UserRole.php
- `{closure#5}()` --references--> `UserRole`  [EXTRACTED]
  tests/Feature/Authentication/RoleAccessTest.php → app/Enums/UserRole.php
- `{closure#6}()` --references_constant--> `UserRole`  [EXTRACTED]
  tests/Feature/Authentication/RoleAccessTest.php → app/Enums/UserRole.php

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Canonical Documentation System** — readme_laravel_docker_starter, docs_architecture_docker_architecture, docs_development_local_development, docs_deployment_ubuntu_vps_deployment, docs_cloudflare_optional_cloudflare_integration, docs_operations_production_operations [EXTRACTED 1.00]
- **Docker Runtime Topology** — docker_compose_nginx_service, docker_compose_app_service, docker_compose_mariadb_service, docs_architecture_service_topology [EXTRACTED 1.00]
- **Role-Protected Application Areas** — docs_superpowers_specs_2026_09_22_auth_admin_developer_design_role_access_matrix, docs_superpowers_specs_2026_09_22_auth_admin_developer_design_application_areas, docs_superpowers_specs_2026_09_22_auth_admin_developer_design_server_side_authorization, docs_superpowers_plans_2026_09_22_auth_admin_developer_areas_role_route_boundaries [INFERRED 0.95]

## Communities (162 total, 133 thin omitted)

### Community 0 - "User Community"
Cohesion: 0.05
Nodes (21): CreateNewUser, {closure#1}(), ResetUserPassword, {closure#1}(), UpdateUserPassword, UpdateUserProfileInformation, {closure#1}(), {closure#1}() (+13 more)

### Community 1 - "Illuminate Database Schema Blueprint"
Cohesion: 0.05
Nodes (24): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#3}() (+16 more)

### Community 2 - "view Community"
Cohesion: 0.07
Nodes (14): CreateUser, DeleteUser, UpdateUser, AccountController, AccountSessionController, UserController, Controller, UserController (+6 more)

### Community 3 - "AuthenticationFlowTest php"
Cohesion: 0.05
Nodes (7): LoginHistory, {closure#1}(), {closure#3}(), {closure#4}(), createDatabaseSession(), resetSessionRuntime(), {closure#5}()

### Community 5 - "package json"
Cohesion: 0.07
Nodes (32): dependencies, alpinejs, flowbite, devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite (+24 more)

### Community 6 - "Docker Architecture"
Cohesion: 0.12
Nodes (20): Base Compose Configuration, Development Compose Overlay, Production Compose Overlay, Docker Architecture, Cloudflare Deployment Verification, Cloudflare Full Strict TLS, Optional Cloudflare Integration, Cloudflare Origin Certificate (+12 more)

### Community 8 - "UserRole Community"
Cohesion: 0.13
Nodes (20): UserRole, Admin, Developer, Operator, User, {closure#1}(), {closure#1}(), {closure#10}() (+12 more)

### Community 10 - "Illuminate Foundation Http FormRequest"
Cohesion: 0.16
Nodes (4): StoreUserRequest, UpdateUserRequest, StoreUserRequest, UpdateUserRequest

### Community 11 - "LoginActivityService Community"
Cohesion: 0.18
Nodes (3): RecordLogoutActivity, RecordRememberedLoginActivity, LoginActivityService

### Community 12 - "AdminUserManagementTest php"
Cohesion: 0.12
Nodes (14): {closure#10}(), {closure#11}(), {closure#12}(), {closure#13}(), {closure#14}(), {closure#15}(), {closure#16}(), {closure#3}() (+6 more)

### Community 13 - "Illuminate Http Request"
Cohesion: 0.20
Nodes (5): RegisterResponse, {closure#4}(), {closure#6}(), {closure#7}(), {closure#8}()

### Community 15 - "MariaDB Service"
Cohesion: 0.20
Nodes (14): Application Service, Adminer Service, Vite Development Service, Frontend Network, Internal Backend Network, MariaDB Data Volume, MariaDB Service, Docker Nginx Service (+6 more)

### Community 17 - "UserManagementActionsTest php"
Cohesion: 0.18
Nodes (8): {closure#1}(), {closure#10}(), {closure#2}(), {closure#3}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}()

### Community 18 - "require-dev Community"
Cohesion: 0.18
Nodes (11): require-dev, fakerphp/faker, fruitcake/laravel-debugbar, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery (+3 more)

### Community 19 - "Flowbite View Reference Rule"
Cohesion: 0.22
Nodes (7): Project Rules Index, Public Account Admin and Developer Shells, Role Domain Model, Role Route Boundaries, Separated User Management UI, Application Areas, Role Access Matrix

### Community 20 - "EnsureUserHasRole php"
Cohesion: 0.31
Nodes (3): AdvertiseClientHints, {closure#1}(), EnsureUserHasRole

### Community 21 - "DeveloperUserManagementTest php"
Cohesion: 0.20
Nodes (7): {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}(), {closure#9}()

### Community 22 - "composer json"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 23 - "scripts Community"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 24 - "RoleAccessTest php"
Cohesion: 0.22
Nodes (7): {closure#1}(), {closure#2}(), {closure#4}(), {closure#5}(), {closure#6}(), {closure#7}(), {closure#8}()

### Community 26 - "bootstrap app php"
Cohesion: 0.32
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 28 - "Policy-Centered User Authorization"
Cohesion: 0.25
Nodes (6): Admin and Developer CRUD Namespaces, User Management Persistence Actions, Shared User Authorization Policy, User Management Role and Visibility Matrix, Admin and Developer Route Namespaces, User Validation and Persistence Contract

### Community 30 - "config Community"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 31 - "Focused Authentication Services"
Cohesion: 0.29
Nodes (5): Device Detection Service, Login Activity Service, User Session Service, Focused Authentication Services, Preserved Authentication Behavior

### Community 32 - "require Community"
Cohesion: 0.33
Nodes (6): require, laravel/fortify, laravel/framework, laravel/tinker, matomo/device-detector, php

### Community 34 - "psr-4 Community"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 36 - "Compose Project Isolation"
Cohesion: 0.50
Nodes (5): Compose Project Isolation, Internal and Host Port Model, Multiple Projects on One VPS, Multiple Local Projects, Docker Port Invariants

### Community 42 - "autoload-dev Community"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 43 - "extra Community"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

## Knowledge Gaps
- **50 isolated node(s):** `php`, `Logout`, `PasswordChanged`, `PasswordReset`, `Terminated` (+45 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 440 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **133 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User Community` to `Illuminate Database Schema Blueprint`, `view Community`, `AuthenticationFlowTest php`, `ProfilePageTest php`, `PageRenderingTest php`, `UserRole Community`, `UserManagementPageRenderingTest php`, `Illuminate Foundation Http FormRequest`, `LoginActivityService Community`, `AdminUserManagementTest php`, `Illuminate Foundation Testing RefreshDatabase`, `UserManagementActionsTest php`, `EnsureUserHasRole php`, `DeveloperUserManagementTest php`, `RoleAccessTest php`, `UserSeeder php`, `record Community`?**
  _High betweenness centrality (0.179) - this node is a cross-community bridge._
- **What connects `php`, `Logout`, `PasswordChanged` to the rest of the system?**
  _50 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `User Community` be split into smaller, more focused modules?**
  _Cohesion score 0.05253077975376197 - nodes in this community are weakly interconnected._
- **Why does `UserRole` connect `UserRole Community` to `User Community`, `view Community`, `AuthenticationFlowTest php`, `ProfilePageTest php`, `PageRenderingTest php`, `UserManagementPageRenderingTest php`, `Illuminate Foundation Http FormRequest`, `AdminUserManagementTest php`, `Illuminate Foundation Testing RefreshDatabase`, `UserManagementActionsTest php`, `EnsureUserHasRole php`, `DeveloperUserManagementTest php`, `RoleAccessTest php`, `UserSeeder php`?**
  _High betweenness centrality (0.072) - this node is a cross-community bridge._
- **Should `Illuminate Database Schema Blueprint` be split into smaller, more focused modules?**
  _Cohesion score 0.050203527815468114 - nodes in this community are weakly interconnected._
- **Why does `UserSessionService` connect `User Community` to `Illuminate Database Schema Blueprint`, `view Community`, `DeviceDetectionService Community`?**
  _High betweenness centrality (0.020) - this node is a cross-community bridge._
- **Should `view Community` be split into smaller, more focused modules?**
  _Cohesion score 0.07467532467532467 - nodes in this community are weakly interconnected._