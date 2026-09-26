# User Management Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add protected user CRUD to separate admin and developer namespaces with the exact role visibility and mutation rules approved in the user-management spec.

**Architecture:** Keep the existing single web/session guard and role middleware. Add a shared `UserPolicy` for actor/target authorization, namespace-specific admin and developer controllers/requests/views, and focused shared actions for persistence. Admin user routes use `role:admin`; developer user routes use `role:developer`, while the existing `/admin` dashboard boundary remains unchanged.

**Tech Stack:** Laravel 13.32, PHP 8.5, Fortify 1.40, Blade, Alpine, Tailwind CSS 4, Vite, Pest 5.

**Spec:** `docs/superpowers/specs/2026-09-26-user-management-design.md`

## Global Constraints

- Use the existing Laravel 13/PHP 8.5 application patterns and do not add dependencies.
- Keep one web/session guard; do not add an admin guard, API layer, Livewire, or an admin-panel package.
- Keep `developer` behind `auth` and `role:developer` and expose developer user management only under `/developer/users`.
- Keep admin user management behind `auth` and `role:admin` and never expose `developer` in the admin list, forms, labels, or routes.
- Enforce permissions server-side through `App\Policies\UserPolicy`; UI visibility is not authorization.
- Create passwords are required and confirmed; edit passwords are optional and unchanged when omitted.
- Use the existing `User` model `hashed` cast and escaped Blade output.
- Follow current backoffice slate/indigo dark-mode conventions and adapt, rather than import, the local TailAdmin reference.
- Use Pest feature tests and policy tests; run Pint for modified PHP files and Vite build for modified frontend assets.

## Review Focus

- A crafted admin request naming a developer must resolve to `404`, not reveal the developer through `403`; test in the admin endpoint task.
- A crafted admin payload must not demote an existing admin or change the current admin's role; test role transitions in the policy and admin endpoint tasks.
- An empty edit password must preserve the existing password while a supplied password must be hashed; test persistence in the shared action/endpoint task.
- An unexpected request key must not be mass-assigned to `User`; test the endpoint with a sentinel attribute in the validation/security task.
- Dangerous names/emails must be escaped in the rendered table and forms; test the page output in the UI task.

---

### Task 1: Add and test the shared user authorization policy

**Files:**
- Create: `app/Policies/UserPolicy.php`
- Test: `tests/Feature/Authorization/UserPolicyTest.php`

**Interfaces:**
- Produces `viewAny(User $actor): bool` for admin/developer list access.
- Produces `view(User $actor, User $target): bool` for target visibility.
- Produces `create(User $actor, UserRole $role): bool` for role-aware creation.
- Produces `update(User $actor, User $target): bool` for profile updates.
- Produces `changeRole(User $actor, User $target, UserRole $role): bool` for role transitions.
- Produces `delete(User $actor, User $target): bool` for deletion.

- [ ] **Step 1: Write the failing policy matrix tests**

  Bind the policy through Laravel's standard model-policy discovery. Test each distinct decision with Pest datasets where setup and assertions remain identical:

  - only `Admin` and `Developer` return true from `viewAny`;
  - an admin may view `Admin`, `Operator`, and `User`, but not `Developer`;
  - a developer may view every role;
  - an admin may create `Admin`, `Operator`, and `User`, but not `Developer`;
  - a developer may create every role;
  - an admin may update another admin's profile fields, but not their own role through `changeRole`;
  - an admin may change `User`/`Operator` to any business role, but may not lower `Admin`;
  - an admin may delete `User` and `Operator`, but no `Admin` or `Developer`;
  - a developer may change roles and delete every target, including another developer.

- [ ] **Step 2: Run the policy test to verify it fails**

  Run: `php artisan test --compact tests/Feature/Authorization/UserPolicyTest.php`

  Expected: FAIL because `UserPolicy` and its authorization methods do not exist.

- [ ] **Step 3: Implement `UserPolicy` in `app/Policies/UserPolicy.php`**

  Use strict `UserRole` comparisons. Keep actor role checks separate from target checks, and make `changeRole` compare the requested role with both the actor and target so a crafted request cannot bypass the admin restrictions. Rely on Laravel's `User`/`UserPolicy` naming convention rather than adding a new provider.

- [ ] **Step 4: Run the policy test to verify it passes**

  Run: `php artisan test --compact tests/Feature/Authorization/UserPolicyTest.php`

  Expected: PASS for the complete authorization matrix.

- [ ] **Step 5: Format the modified PHP files**

  Run: `vendor/bin/pint --dirty --format agent`

- [ ] **Step 6: Commit the policy slice**

  ```bash
  git add app/Policies/UserPolicy.php tests/Feature/Authorization/UserPolicyTest.php
  git commit -m "feat: authorize user management by role"
  ```

### Task 2: Add shared user-management persistence actions and request validation

**Files:**
- Create: `app/Actions/UserManagement/CreateUser.php`
- Create: `app/Actions/UserManagement/UpdateUser.php`
- Create: `app/Actions/UserManagement/DeleteUser.php`
- Create: `app/Http/Requests/Admin/StoreUserRequest.php`
- Create: `app/Http/Requests/Admin/UpdateUserRequest.php`
- Create: `app/Http/Requests/Developer/StoreUserRequest.php`
- Create: `app/Http/Requests/Developer/UpdateUserRequest.php`
- Test: `tests/Feature/UserManagement/UserManagementActionsTest.php`

**Interfaces:**
- `CreateUser::handle(array $attributes): User` creates a user from validated `name`, `email`, `password`, and `role` values.
- `UpdateUser::handle(User $user, array $attributes): User` updates validated profile fields and only replaces the password when a non-empty password is present.
- `DeleteUser::handle(User $user): void` deletes the supplied user.
- Admin store/update requests validate only business roles: `user`, `operator`, `admin`.
- Developer store/update requests validate all `UserRole` values, including `developer`.

- [ ] **Step 1: Scaffold the request and action classes**

  Use `php artisan make:class` for the three actions and `php artisan make:request` for the four request classes, with `--no-interaction`. Keep the request classes responsible for input shape and role enum validation; keep actor/target permission decisions in the policy.

- [ ] **Step 2: Write failing action and validation tests**

  Cover:

  - creation persists a user with the requested role and a password that passes `Hash::check`;
  - update persists name/email/role and hashes a supplied password;
  - update with an empty password preserves the original password hash;
  - delete removes the model;
  - duplicate email returns the existing validation message;
  - invalid role and unconfirmed password are rejected;
  - an unexpected `is_admin`/sentinel key does not change a persisted model attribute.

- [ ] **Step 3: Run the focused tests to verify they fail**

  Run: `php artisan test --compact tests/Feature/UserManagement/UserManagementActionsTest.php`

  Expected: FAIL because the actions and requests are not implemented.

- [ ] **Step 4: Implement the actions and requests**

  Use the model's existing `password` hashed cast instead of introducing a second hashing convention. Build update attributes explicitly from validated keys and omit `password` when its value is blank. Use `Rule::enum(UserRole::class)` plus an allow-list for admin role requests, and `Rule::unique('users', 'email')->ignore($user)` for edits.

- [ ] **Step 5: Run the focused tests to verify they pass**

  Run: `php artisan test --compact tests/Feature/UserManagement/UserManagementActionsTest.php`

  Expected: PASS with the database state and password assertions satisfied.

- [ ] **Step 6: Format the modified PHP files**

  Run: `vendor/bin/pint --dirty --format agent`

- [ ] **Step 7: Commit the persistence and validation slice**

  ```bash
  git add app/Actions/UserManagement app/Http/Requests/Admin app/Http/Requests/Developer tests/Feature/UserManagement/UserManagementActionsTest.php
  git commit -m "feat: add user management actions and validation"
  ```

### Task 3: Add the admin and developer CRUD controllers and route boundaries

**Files:**
- Create: `app/Http/Controllers/Admin/UserController.php`
- Create: `app/Http/Controllers/Developer/UserController.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/UserManagement/AdminUserManagementTest.php`
- Test: `tests/Feature/UserManagement/DeveloperUserManagementTest.php`

**Interfaces:**
- Admin routes are named `admin.users.index`, `admin.users.create`, `admin.users.store`, `admin.users.show`, `admin.users.edit`, `admin.users.update`, and `admin.users.destroy`.
- Developer routes use the equivalent `developer.users.*` names.
- Admin controller lists and resolves only non-developer users; developer controller lists and resolves every user.
- Both controllers call the shared actions and policy abilities rather than mutating `User` directly.

- [ ] **Step 1: Scaffold both resource controllers**

  Run `php artisan make:controller Admin/UserController --resource --no-interaction` and the equivalent developer command. Keep method return types explicit and follow the existing closure-free controller style.

- [ ] **Step 2: Register the two resource route groups**

  Add the admin resource under `Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')` and the developer resource under `Route::middleware(['auth', 'role:developer'])->prefix('developer')->name('developer.')`. Preserve the existing dashboard route groups and route ordering.

- [ ] **Step 3: Write failing admin endpoint tests**

  Cover:

  - guest redirect to login;
  - operator and regular user `403` for `/admin/users`;
  - admin list includes admin/operator/user and excludes developer;
  - admin create persists user/operator/admin records;
  - admin updates allowed profile fields;
  - admin cannot demote an existing admin;
  - admin cannot change their own role;
  - admin cannot delete an admin or access a developer by direct ID (`404`);
  - admin can delete user/operator;
  - invalid form data returns to the form with validation errors.

- [ ] **Step 4: Write failing developer endpoint tests**

  Cover:

  - guest, admin, operator, and regular user are rejected from `/developer/users`;
  - developer list contains every role, including developers;
  - developer creates users in every role;
  - developer updates profile fields and role transitions;
  - developer deletes every target role;
  - successful mutations redirect to the namespace index and persist state.

- [ ] **Step 5: Run endpoint tests to verify they fail**

  Run: `php artisan test --compact tests/Feature/UserManagement/AdminUserManagementTest.php tests/Feature/UserManagement/DeveloperUserManagementTest.php`

  Expected: FAIL because the controllers, routes, and views do not exist.

- [ ] **Step 6: Implement the controllers and route-aware record resolution**

  Use policy authorization for list/create/view/update/changeRole/delete. In the admin controller, resolve record IDs with a query constrained to `role != developer` before authorization so developer IDs produce `404`. In both controllers, compare the requested role with the current role and call `changeRole` only for an actual role transition. Pass only validated data to the shared actions, flash a success message after mutations, and redirect to the namespace index.

- [ ] **Step 7: Run endpoint tests to verify they pass**

  Run: `php artisan test --compact tests/Feature/UserManagement/AdminUserManagementTest.php tests/Feature/UserManagement/DeveloperUserManagementTest.php`

  Expected: PASS for both namespace boundaries and all CRUD mutation branches.

- [ ] **Step 8: Format the modified PHP files**

  Run: `vendor/bin/pint --dirty --format agent`

- [ ] **Step 9: Commit the backend CRUD slice**

  ```bash
  git add app/Http/Controllers/Admin/UserController.php app/Http/Controllers/Developer/UserController.php routes/web.php tests/Feature/UserManagement/AdminUserManagementTest.php tests/Feature/UserManagement/DeveloperUserManagementTest.php
  git commit -m "feat: add admin and developer user CRUD routes"
  ```

### Task 4: Build the separated user-management UI and navigation

**Files:**
- Create: `resources/views/components/admin/user-form.blade.php`
- Create: `resources/views/components/admin/user-role-badge.blade.php`
- Create: `resources/views/admin/users/index.blade.php`
- Create: `resources/views/admin/users/create.blade.php`
- Create: `resources/views/admin/users/edit.blade.php`
- Create: `resources/views/admin/users/show.blade.php`
- Create: `resources/views/developer/users/index.blade.php`
- Create: `resources/views/developer/users/create.blade.php`
- Create: `resources/views/developer/users/edit.blade.php`
- Create: `resources/views/developer/users/show.blade.php`
- Modify: `resources/views/components/layouts/admin.blade.php`
- Modify: `resources/views/components/layouts/developer.blade.php`
- Test: `tests/Feature/Pages/UserManagementPageRenderingTest.php`

**Interfaces:**
- The shared form accepts a user/null model, action URL, HTTP method, available roles, and whether the role control is editable.
- The role badge renders role-specific accessible text and styling without exposing developer roles in admin views.
- Admin and developer views use their own named route namespace and supplied paginated users.

- [ ] **Step 1: Inspect the local TailAdmin table, buttons, badges, alerts, header, and sidebar partials**

  Reuse only markup and utility-class ideas from `.reference/tailadmin/src/partials/`; do not copy its build configuration, dependencies, or assets into the application.

- [ ] **Step 2: Write failing page-rendering tests**

  Assert:

  - admin navigation exposes `Users` and links to `admin.users.index`;
  - developer navigation exposes `Users` and links to `developer.users.index`;
  - admin index renders names/emails/business role badges but does not render the developer name, email, or role label;
  - developer index renders developer records and developer role badge;
  - create/edit forms render the correct action, role options, password fields, validation errors, and delete controls;
  - admin forms contain no developer role option;
  - dangerous user names/emails render escaped HTML.

- [ ] **Step 3: Run page-rendering tests to verify they fail**

  Run: `php artisan test --compact tests/Feature/Pages/UserManagementPageRenderingTest.php`

  Expected: FAIL because the views, navigation links, and components do not exist.

- [ ] **Step 4: Implement the shared form and role badge components**

  Keep form fields escaped and avoid repopulating password inputs. Render role options from the controller-provided allow-list, make the role control unavailable for the current admin and every existing admin target in the admin namespace, and preserve the current dark-mode and spacing conventions used by existing `admin` components.

- [ ] **Step 5: Implement admin and developer index/create/edit/show views**

  Use responsive Tailwind table markup adapted from the TailAdmin reference. Include paginated `links`, empty states, success/error alerts from the session, edit/show actions, and a confirmation step for deletion. Keep the admin view copy entirely business-oriented; do not mention developer or technical roles there.

- [ ] **Step 6: Add namespace-specific navigation items**

  Add `Users` to the admin navigation with `admin.users.index` and to the developer navigation with `developer.users.index`. Do not make the developer link part of admin navigation.

- [ ] **Step 7: Run page-rendering tests to verify they pass**

  Run: `php artisan test --compact tests/Feature/Pages/UserManagementPageRenderingTest.php`

  Expected: PASS for navigation, role visibility, forms, escaping, and responsive shell contracts.

- [ ] **Step 8: Build the frontend assets**

  Run: `npm run build`

  Expected: Vite completes successfully and the generated manifest remains available to Blade.

- [ ] **Step 9: Commit the UI slice**

  ```bash
  git add resources/views/admin/users resources/views/developer/users resources/views/components/admin/user-form.blade.php resources/views/components/admin/user-role-badge.blade.php resources/views/components/layouts/admin.blade.php resources/views/components/layouts/developer.blade.php tests/Feature/Pages/UserManagementPageRenderingTest.php
  git commit -m "feat: add separated user management screens"
  ```

### Task 5: Run the complete verification pass and update the code graph

**Files:**
- Modify: graphify output generated by `graphify update .`

**Interfaces:**
- All user-management routes, policies, actions, requests, controllers, views, and tests are represented in the current repository graph.

- [ ] **Step 1: Run all focused user-management and related authentication/page tests**

  Run: `php artisan test --compact tests/Feature/Authorization/UserPolicyTest.php tests/Feature/UserManagement tests/Feature/Authentication tests/Feature/Pages`

  Expected: PASS with no regressions in existing authentication, role access, or page rendering behavior.

- [ ] **Step 2: Run Pint on all dirty PHP files**

  Run: `vendor/bin/pint --dirty --format agent`

  Expected: Pint completes without leaving formatting changes.

- [ ] **Step 3: Run the frontend build**

  Run: `npm run build`

  Expected: Vite completes successfully.

- [ ] **Step 4: Update graphify output**

  Run: `graphify update .`

  Expected: graphify updates `graphify-out/` without API calls or source changes outside generated graph output.

- [ ] **Step 5: Run the full test suite**

  Run: `php artisan test --compact`

  Expected: PASS for the complete project suite.

- [ ] **Step 6: Review the final diff and commit the verification slice**

  Run: `git diff --check` and inspect `git diff --stat` plus the staged changes. Commit only the intended user-management and graphify changes.
