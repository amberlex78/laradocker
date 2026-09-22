# Authentication, Admin, and Developer Areas Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add Fortify authentication and a role-protected public, account, admin, and developer application shell.

**Architecture:** Use one web/session guard and a backed `UserRole` enum stored on `users.role`. Protect `/admin` and `/developer` with authentication plus a role middleware; give developer access to both areas while keeping the developer area unavailable to admins and operators. Keep the initial pages as small Blade dashboard shells with separate layouts.

**Tech Stack:** Laravel 13.17, PHP 8.3+, Fortify, Blade, Alpine, Tailwind CSS 4, Vite, Pest.

**Spec:** `docs/superpowers/specs/2026-09-22-auth-admin-developer-design.md`

## Global Constraints

- Use the existing Laravel 13.17 project conventions and Docker/Make workflow.
- Do not add Livewire, SPA tooling, an admin panel package, Sanctum, or a second auth guard.
- Use a backed `UserRole` enum with `developer`, `admin`, `operator`, and `user` values.
- Keep `/developer` and `/admin` as fixed routes in this first release.
- Do not implement cron, jobs, technical actions, configurable paths, or domain CRUD in this change.
- Use server-side authorization; navigation visibility is not an access control mechanism.

## Review Focus

- A guest requesting `/admin`, `/developer`, or `/account` is redirected to authentication — covered by route access tests.
- A regular user cannot reach either privileged area even if they type the URL directly — covered by the role matrix test.
- An admin cannot reach `/developer`, while a developer can reach both privileged areas — covered by the role matrix test.
- An operator can enter `/admin` but not `/developer` — covered by the role matrix test.
- A successful login redirects each role to its intended landing area — covered by authentication response tests.

---

### Task 1: Add the role domain model and database column

**Files:**
- Create: `app/Enums/UserRole.php`
- Create: `database/migrations/2026_09_22_000003_add_role_to_users_table.php`
- Modify: `app/Models/User.php`
- Modify: `database/factories/UserFactory.php`
- Test: `tests/Feature/Authentication/RoleAccessTest.php`

**Interfaces:**
- Produces `App\Enums\UserRole` with `Developer`, `Admin`, `Operator`, and `User` cases.
- Produces `User::$role` cast to `UserRole` with `user` as the database default.

- [ ] **Step 1: Write failing enum and cast assertions**

  Add Pest expectations that the enum contains the four string values and that a persisted user exposes `$user->role` as `UserRole::User` by default.

- [ ] **Step 2: Run the focused test and verify it fails**

  Run: `make test CMD="tests/Feature/Authentication/RoleAccessTest.php"`

  Expected: FAIL because the enum, column, and cast do not exist.

- [ ] **Step 3: Add the enum, migration, model cast, and factory state**

  Use a string `role` column with a database default of `user`, cast it through `protected function casts(): array`, and make the factory generate regular users unless a test explicitly assigns another role.

- [ ] **Step 4: Run the focused test and verify it passes**

  Run: `make test CMD="tests/Feature/Authentication/RoleAccessTest.php"`

  Expected: PASS for the enum and persistence assertions.

- [ ] **Step 5: Format modified PHP files**

  Run: `make pint`

### Task 2: Install and configure Fortify with Blade authentication views

**Files:**
- Modify: `composer.json`
- Modify: `composer.lock`
- Create or modify: `config/fortify.php`
- Create: `app/Providers/FortifyServiceProvider.php`
- Create: `resources/views/auth/login.blade.php`
- Create: `resources/views/auth/register.blade.php`
- Create: `resources/views/auth/forgot-password.blade.php`
- Create: `resources/views/auth/reset-password.blade.php`
- Create: `resources/views/auth/verify-email.blade.php`
- Create: `resources/views/layouts/auth.blade.php`
- Test: `tests/Feature/Authentication/AuthenticationFlowTest.php`

**Interfaces:**
- Produces Fortify routes for login, registration, logout, password reset, and email verification according to the installed Fortify version.
- Produces a post-login redirect resolver that maps roles to `/developer`, `/admin`, and `/account`.

- [ ] **Step 1: Install and publish Fortify resources**

  Run `make composer CMD="require laravel/fortify"`, then run `make artisan CMD="fortify:install --no-interaction"`. Confirm that the generated `config/fortify.php`, `app/Providers/FortifyServiceProvider.php`, and Fortify migrations match the installed package.

- [ ] **Step 2: Write failing authentication flow tests**

  Cover registration, login, logout, and role-specific post-login redirects using Pest feature tests and the existing `UserFactory`.

- [ ] **Step 3: Configure Fortify and create the minimal Blade screens**

  Enable only the authentication features required by the initial shell, use the web/session guard, and render all auth pages through the auth layout with Tailwind classes and CSRF-protected forms.

- [ ] **Step 4: Implement role-aware post-login redirects**

  Return `/developer` for `Developer`, `/admin` for `Admin` and `Operator`, and `/account` for `User`.

- [ ] **Step 5: Run the focused authentication tests**

  Run: `make test CMD="tests/Feature/Authentication/AuthenticationFlowTest.php"`

  Expected: PASS.

- [ ] **Step 6: Format modified PHP files**

  Run: `make pint`

### Task 3: Add role middleware and route boundaries

**Files:**
- Create: `app/Http/Middleware/EnsureUserHasRole.php`
- Modify: `bootstrap/app.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/Authentication/RoleAccessTest.php`

**Interfaces:**
- Produces a middleware alias that accepts one or more allowed role values.
- Produces named routes for `home`, `account`, `admin.dashboard`, and `developer.dashboard`.

- [ ] **Step 1: Write the failing role matrix tests**

  Assert: guests are redirected; users can access `/account` but not `/admin` or `/developer`; operators can access `/admin` but not `/developer`; admins can access `/admin` but not `/developer`; developers can access both.

- [ ] **Step 2: Run the focused test and verify it fails**

  Run: `make test CMD="tests/Feature/Authentication/RoleAccessTest.php"`

  Expected: FAIL because the middleware and protected routes do not exist.

- [ ] **Step 3: Implement the middleware and route groups**

  Register the middleware alias in `bootstrap/app.php`. Group account routes under `auth`; group admin routes under `auth` plus the admin/operator role boundary; group developer routes under `auth` plus the developer role boundary. Ensure developer is explicitly allowed into `/admin`.

- [ ] **Step 4: Run the role matrix tests**

  Run: `make test CMD="tests/Feature/Authentication/RoleAccessTest.php"`

  Expected: PASS.

- [ ] **Step 5: Format modified PHP files**

  Run: `make pint`

### Task 4: Build the public, account, admin, and developer Blade shells

**Files:**
- Modify: `resources/views/welcome.blade.php` or replace it with the public home view
- Create: `resources/views/layouts/public.blade.php`
- Create: `resources/views/layouts/account.blade.php`
- Create: `resources/views/layouts/admin.blade.php`
- Create: `resources/views/layouts/developer.blade.php`
- Create: `resources/views/account/index.blade.php`
- Create: `resources/views/admin/dashboard.blade.php`
- Create: `resources/views/developer/dashboard.blade.php`
- Modify: `resources/css/app.css`
- Modify: `resources/js/app.js` only if Alpine initialization requires it
- Test: `tests/Feature/Pages/PageRenderingTest.php`

**Interfaces:**
- Produces visible page titles and stable named-route links for each application area.
- Produces separate navigation shells; admin navigation does not expose developer navigation.

- [ ] **Step 1: Write failing page rendering tests**

  Assert each permitted role receives the expected page title and that the admin shell contains an admin navigation marker while the developer shell contains a developer marker.

- [ ] **Step 2: Implement the layouts and empty dashboard pages**

  Keep the content intentionally minimal: public home content, account heading, admin dashboard heading, and developer dashboard heading. Add responsive Tailwind layout primitives and small Alpine interactions only where needed for navigation.

- [ ] **Step 3: Run the page rendering tests**

  Run: `make test CMD="tests/Feature/Pages/PageRenderingTest.php"`

  Expected: PASS.

- [ ] **Step 4: Build frontend assets**

  Run: `make npm CMD="run build"`

  Expected: Vite completes successfully and the generated assets are available to Blade through the Vite manifest.

### Task 5: Run the complete verification pass

**Files:**
- Test: `tests/Feature/Authentication/AuthenticationFlowTest.php`
- Test: `tests/Feature/Authentication/RoleAccessTest.php`
- Test: `tests/Feature/Pages/PageRenderingTest.php`

- [ ] **Step 1: Run the focused feature tests together**

  Run: `make test CMD="tests/Feature/Authentication tests/Feature/Pages"`

  Expected: PASS.

- [ ] **Step 2: Run Pint and the frontend build**

  Run: `make pint`

  Run: `make npm CMD="run build"`

  Expected: both commands complete successfully.

- [ ] **Step 3: Run the project test command**

  Run: `make test`

  Expected: the full existing and new test suites pass.
