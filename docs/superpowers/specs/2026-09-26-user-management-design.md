# User Management in Admin and Developer Areas

## Goal

Add user CRUD to the existing Laravel backoffice while keeping business administration and technical administration separated. Administrators can manage business users and other administrators under protective rules; developers receive a separate, unrestricted user-management namespace.

## Confirmed requirements

- `admin` and `developer` are the only roles that can manage users.
- `operator` has no access to user management.
- `developer` keeps a separate route boundary protected by `auth` and `role:developer`.
- `developer` can create, view, update, change roles, and delete any user.
- `admin` does not know that `developer` exists in the admin user-management workflow.
- An admin can create another admin.
- An admin cannot lower an existing admin to `user` or `operator`.
- An admin cannot delete an admin, including themself.
- An admin cannot change their own role.
- The existing `/admin` dashboard remains available to developers, but user management is not shared through the admin namespace.

## Role and visibility matrix

| Operation | Admin target | Operator target | User target | Developer target |
|---|---:|---:|---:|---:|
| Admin can list | yes | yes | yes | no, including direct lookup |
| Admin can create | n/a | n/a | n/a | no |
| Admin can edit profile fields | yes | yes | yes | no |
| Admin can set target role | `admin` only | `user`, `operator`, `admin` | `user`, `operator`, `admin` | no |
| Admin can delete | no | yes | yes | no |
| Developer can list | yes | yes | yes | yes |
| Developer can create | yes | yes | yes | yes |
| Developer can edit | yes | yes | yes | yes |
| Developer can delete | yes | yes | yes | yes |

An admin's own role is never editable. Since administrators cannot delete any admin, self-deletion is also unavailable. The policy is the authoritative enforcement point; disabled controls and hidden navigation are only presentation details.

## Route boundaries and namespaces

The existing developer boundary remains unchanged in principle:

```php
Route::middleware(['auth', 'role:developer'])
    ->prefix('developer')
    ->name('developer.')
```

User-management routes are split into two resource namespaces:

```text
/admin/users       -> admin.users.*       -> role:admin
/developer/users   -> developer.users.*   -> role:developer
```

The admin user routes deliberately use `role:admin`, not `role:developer,admin`. A developer manages users from the developer namespace only. The existing admin dashboard continues to use its current developer/admin/operator boundary.

Both namespaces expose index, create, store, show, edit, update, and destroy actions. Admin record resolution filters out developer users before policy checks so a direct request for a developer record returns `404` and does not disclose its existence.

## Authorization architecture

`App\Policies\UserPolicy` is the shared authorization contract for both namespaces. It covers:

- listing users;
- viewing a user;
- creating users;
- updating profile fields;
- changing a user's role;
- deleting users.

The policy distinguishes the authenticated actor from the target user and the requested target role. It must reject developer targets from admin operations even if a route or controller is reached directly.

The controllers remain namespace-specific:

```text
app/Http/Controllers/Admin/UserController.php
app/Http/Controllers/Developer/UserController.php
```

The persistence behavior is shared through focused user-management actions:

```text
app/Actions/UserManagement/CreateUser.php
app/Actions/UserManagement/UpdateUser.php
app/Actions/UserManagement/DeleteUser.php
```

No second auth guard, admin package, Livewire layer, or API layer is introduced.

## Validation and persistence

User-management requests use explicit validation for name, unique email, role enum, and password confirmation. The admin request exposes only `user`, `operator`, and `admin` roles; the developer request exposes every `UserRole` case.

Password behavior:

- create: password is required and confirmed;
- edit: password is optional and confirmed only when supplied;
- an empty edit password leaves the existing password unchanged;
- the existing `User` model `hashed` cast is used for persistence.

Role transitions are validated at the request boundary and enforced again by the policy/action boundary. This prevents a crafted request from bypassing UI restrictions or mass-assignment protection.

## UI design

The new pages use the existing `backoffice` layout and current slate/indigo dark-mode conventions, adapted from the local TailAdmin reference:

- a `Users` navigation item in the admin workspace;
- a separate `Users` navigation item in the developer workspace;
- responsive data table with name, email, role badge, verification state, and actions;
- create and edit forms with consistent field components and validation feedback;
- destructive delete action with confirmation;
- success and error alerts after mutations;
- role badges that distinguish business roles and developer role;
- no developer label, row, link, or role option in the admin UI.

The admin list is paginated and filtered to non-developer users. The developer list is paginated across all users. Existing layout components and TailAdmin-inspired markup are reused rather than importing the reference application's build system or dependencies.

## Error handling and security

- Unauthenticated requests redirect to login through the existing `auth` middleware.
- Operator and regular user requests receive `403` at the route boundary.
- Unauthorized record operations receive `403` from policy authorization.
- Admin requests for developer records resolve to `404` to avoid revealing developer existence.
- Validation failures return to the form with field errors and old non-secret input; passwords are never repopulated.
- Delete and role-change actions are enforced server-side, regardless of disabled or hidden controls.
- User-rendered values use escaped Blade output.
- Unexpected request keys are not copied into the model.

## Testing strategy

Feature tests will cover observable HTTP behavior for every route and mutation. Policy tests will cover the complete permission matrix so authorization failures identify the broken rule rather than only the route wiring.

Required coverage includes:

- guest, operator, and regular-user denial;
- admin access to the admin namespace;
- developer access to the developer namespace;
- admin list exclusion of developer users;
- admin `404` for direct developer record access;
- admin creation of users, operators, and additional admins;
- admin profile updates for allowed targets;
- admin rejection of admin demotion;
- admin rejection of own-role changes and admin deletion;
- developer create/update/role-change/delete for every role;
- invalid email, duplicate email, invalid role, and password validation;
- persisted state after successful create/update/delete;
- password hashing and password preservation when an edit omits the password;
- escaped user content in rendered table/form output.

Verification will run the narrowest relevant Pest tests, Pint for modified PHP files, the Vite build for modified frontend assets, and `graphify update .` after code changes.

## Out of scope

- invitation emails or password-reset onboarding;
- bulk actions, CSV import/export, advanced search, or audit logs;
- soft deletion or account suspension;
- changes to the existing Fortify registration flow;
- changing the existing developer/admin dashboard access rules;
- new roles or a separate permissions table.
