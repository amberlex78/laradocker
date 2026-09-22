# Authentication, Admin, and Developer Areas

## Goal

Build the first application shell for a Laravel monolith using Blade, Alpine, and Tailwind: a public home page, authentication, an account area, a shared business back-office at `/admin`, and a separate technical area at `/developer`.

## Roles and access

The application has one `users.role` value represented by a backed `UserRole` enum:

- `developer` — may access both `/developer` and `/admin`;
- `admin` — may access `/admin`, but not `/developer`;
- `operator` — may access the permitted parts of `/admin`, but not `/developer`;
- `user` — may access the account area, but not `/admin` or `/developer`.

The developer role is the highest role and is separate from the normal business administration workflow. A developer is redirected to `/developer` after login, with a deliberate path into `/admin` available from the developer area. An admin or operator is redirected to `/admin`; a regular user is redirected to `/account`.

Route access is enforced server-side with authentication and role middleware. The UI may hide links, but hidden navigation is not used as authorization. Resource-specific permissions will use policies when those resources are introduced.

## Application areas

- `/` uses a public layout and replaces the Laravel welcome page.
- `/account` uses an authenticated user layout and is available to regular users.
- `/admin` uses a business back-office layout and is available to admin and operator roles, with developer access inherited.
- `/developer` uses a separate technical layout and is available only to developer users.

The first release contains empty dashboard shells only. It does not add technical commands, scheduled tasks, jobs, configurable panel paths, or domain CRUD.

## Authentication

Use Laravel Fortify as the headless authentication backend and provide Blade views styled with Tailwind. Use the normal web/session guard; no separate admin guard or API authentication package is needed for this monolithic web application.

## UI structure

Create separate layouts for public, authentication, account, admin, and developer concerns where the markup meaningfully differs. Alpine is reserved for small client-side interactions such as navigation menus; no SPA or Livewire layer is introduced.

## Verification

Feature tests must prove the role access matrix, unauthenticated redirects, post-login destinations, and that a regular user cannot enter either privileged area. The narrowest relevant Pest tests must pass before completion, followed by Pint for modified PHP files and the frontend build when frontend assets change.
