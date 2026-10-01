# Authentication Services Refactor Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans (recommended) to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Move device detection, login activity, active-session lookup, and session termination into focused auth services without changing observable behavior.

**Architecture:** Add `DeviceDetectionService`, `LoginActivityService`, and `UserSessionService` under `app/Services/Auth`. Keep `LoginResponse` and the account controllers as thin framework adapters that delegate to those services. Replace the raw current-session ordering expression with explicit current-session and other-session queries.

**Tech Stack:** PHP 8.5, Laravel 13.32, Fortify 1.40, Eloquent, query builder, Pest 5, Laravel Pint.

**Spec:** `docs/superpowers/specs/2026-10-01-auth-services-refactor-design.md`

## Global Constraints

- Preserve role-based login redirects, login metadata, login history, trusted-proxy IP behavior, device detection, active-session filtering, current-session-first ordering, the ten-session limit, and session isolation.
- Do not change Fortify configuration, routes, Blade views, database schema, or dependencies.
- Do not introduce a repository layer or a catch-all `AuthenticationService`.
- Keep device detection in-process and active-session results bounded to ten rows.
- Use constructor injection for service dependencies and explicit PHP return types.
- Use existing feature tests as the primary behavior contract and Pest conventions used by `tests/Feature/Authentication`.
- Run the repository Makefile targets for tests and formatting where available.

## Review Focus

- A missing current session still returns up to ten other active sessions — covered by `UserSessionServiceTest`.
- A session belonging to another user is never returned or deleted — covered by existing `ProfilePageTest` behavior.
- Expired sessions remain excluded — covered by existing active-session rendering tests.
- Missing or masked User-Agent data retains the existing unknown/tablet fallback behavior — covered by existing `AuthenticationFlowTest` and `ProfilePageTest` behavior.
- A successful login still updates the latest user fields and creates one history record — covered by existing login-flow tests and the focused `LoginActivityServiceTest`.

---

### Task 1: Add focused service-contract tests

**Files:**
- Create: `tests/Feature/Authentication/LoginActivityServiceTest.php`
- Create: `tests/Feature/Authentication/UserSessionServiceTest.php`

**Interfaces:**
- Consumes the planned `LoginActivityService::record(User $user, Request $request): void` interface.
- Consumes the planned `UserSessionService::activeFor(User $user, string $currentSessionId): Collection` interface.

- [ ] **Step 1: Write the failing login activity service test**

  Create a user and a request with a known User-Agent and IP, call
  `LoginActivityService::record`, and assert the user's latest login fields and
  one `login_histories` record contain the detected values. The test should
  fail because the service class does not exist yet.

- [ ] **Step 2: Run the focused test and verify the expected failure**

  Run: `make test CMD="tests/Feature/Authentication/LoginActivityServiceTest.php"`

  Expected: failure caused by the missing `LoginActivityService`, not a test
  syntax or environment error.

- [ ] **Step 3: Write the failing active-session edge-case test**

  Create ten active sessions for one user, call
  `UserSessionService::activeFor($user, 'missing-current-session')`, and assert
  that ten sessions are returned and none is marked current. Keep another
  user's session in the fixture and assert it is not returned.

- [ ] **Step 4: Run the focused test and verify the expected failure**

  Run: `make test CMD="tests/Feature/Authentication/UserSessionServiceTest.php"`

  Expected: failure caused by the missing `UserSessionService`.

### Task 2: Extract device detection

**Files:**
- Create: `app/Services/Auth/DeviceDetectionService.php`
- Test: `tests/Feature/Authentication/DeviceDetectionServiceTest.php`

**Interfaces:**
- Produces `DeviceDetectionService::fromRequest(Request $request): array`.
- Produces `DeviceDetectionService::fromUserAgent(?string $userAgent): array`.
- Preserves the existing device-information array shape:
  `device_type`, `device_model`, `operating_system`, `browser`, and
  `browser_version`.

- [ ] **Step 1: Write the failing device-detection service test**

  Assert the known iPhone User-Agent returns `smartphone`, iOS, Safari, and
  version `17.0`, and an empty User-Agent returns `unknown` with null operating
  system, browser, and browser version. The test should fail because the
  service class does not exist yet.

- [ ] **Step 2: Run the focused test and verify the expected failure**

  Run: `make test CMD="tests/Feature/Authentication/DeviceDetectionServiceTest.php"`

  Expected: failure caused by the missing `DeviceDetectionService`.

- [ ] **Step 3: Move the existing detection implementation into the service**

  Copy the existing client-hints normalization, device-type fallback,
  tablet-model fallback, and unknown-value normalization into
  `DeviceDetectionService`. Keep the public methods and return shape explicit;
  do not add new detection behavior. Leave the old action in place until all
  consumers are migrated in the next task.

- [ ] **Step 4: Run the focused device-detection tests**

  Run: `make test CMD="tests/Feature/Authentication/DeviceDetectionServiceTest.php"`

  Expected: the service returns the existing smartphone/browser details for the
  known iPhone User-Agent and the existing unknown/null values for an empty
  User-Agent.

### Task 3: Extract login activity recording

**Files:**
- Create: `app/Services/Auth/LoginActivityService.php`
- Modify: `app/Http/Responses/LoginResponse.php:5-26`
- Delete: `app/Actions/Auth/RecordUserLogin.php`
- Delete: `app/Actions/Auth/ResolveDeviceInformation.php`
- Test: `tests/Feature/Authentication/LoginActivityServiceTest.php`, `tests/Feature/Authentication/AuthenticationFlowTest.php`

**Interfaces:**
- Consumes `DeviceDetectionService` through constructor injection.
- Produces `LoginActivityService::record(User $user, Request $request): void`.

- [ ] **Step 1: Implement `LoginActivityService::record`**

  Resolve request device details and IP, capture one login timestamp, then use
  `DB::transaction` to update the user's latest-login columns and create the
  related login-history record. Preserve the existing nullable fields and
  database attributes.

- [ ] **Step 2: Replace the Fortify response dependency**

  Inject `LoginActivityService` into `LoginResponse`, call `record` before the
  existing landing-route redirect, and remove the deleted action dependency.

- [ ] **Step 3: Run the focused and flow tests**

  Run: `make test CMD="tests/Feature/Authentication/LoginActivityServiceTest.php tests/Feature/Authentication/AuthenticationFlowTest.php"`

  Expected: the service contract, role redirects, latest-login fields, history
  creation, proxy IP handling, and two-login history behavior pass.

### Task 4: Extract active-session lookup and termination

**Files:**
- Create: `app/Services/Auth/UserSessionService.php`
- Modify: `app/Http/Controllers/AccountController.php:5-49`
- Modify: `app/Http/Controllers/AccountSessionController.php:5-19`
- Delete: `app/Actions/Auth/TerminateOtherSessions.php`
- Test: `tests/Feature/Authentication/UserSessionServiceTest.php`, `tests/Feature/Authentication/ProfilePageTest.php`

**Interfaces:**
- Consumes `DeviceDetectionService` through constructor injection.
- Produces `UserSessionService::activeFor(User $user, string $currentSessionId): Collection`.
- Produces `UserSessionService::terminateOthers(User $user, string $currentSessionId): void`.

- [ ] **Step 1: Implement the bounded active-session query**

  Query only the required session columns for the requested user and active
  lifetime. Fetch the current session separately, fetch up to nine other active
  sessions ordered by `last_activity` and `id`, merge current first, and map
  each row using `DeviceDetectionService`. Capture the current time once for
  the activity cutoff and display timestamps. Do not use `orderByRaw` or a SQL
  `CASE` expression.

- [ ] **Step 2: Implement session termination**

  Delete sessions for the authenticated user whose IDs differ from the passed
  current session ID. Do not accept a session ID from the request body and do
  not affect sessions belonging to another user.

- [ ] **Step 3: Wire `AccountController` to the service**

  Remove `DB`, `ResolveDeviceInformation`, and the inline session query/map.
  Inject `UserSessionService`, call `activeFor`, and leave the existing user
  and paginated login-history view data unchanged.

- [ ] **Step 4: Wire `AccountSessionController` to the service**

  Inject `UserSessionService`, call `terminateOthers`, and preserve the current
  redirect route and `active-sessions-terminated` flash status.

- [ ] **Step 5: Run the session regression tests**

  Run: `make test CMD="tests/Feature/Authentication/UserSessionServiceTest.php tests/Feature/Authentication/ProfilePageTest.php"`

  Expected: current-session ordering, active-session limit, time display,
  unknown values, guest protection, session isolation, and termination behavior
  pass.

### Task 5: Remove transition leftovers and verify the refactor

**Files:**
- Modify only files identified by the final reference search, if any stale imports or references remain.
- No new production files are expected in this task.

- [ ] **Step 1: Search for deleted action references**

  Run: `rg -n "ResolveDeviceInformation|RecordUserLogin|TerminateOtherSessions" app tests routes config`

  Expected: no remaining application or test references.

- [ ] **Step 2: Run the complete test suite**

  Run: `make test`

  Expected: exit code 0 with no failed tests.

- [ ] **Step 3: Run PHP formatting required by the project**

  Run: `vendor/bin/pint --dirty --format agent`

  Expected: Pint completes successfully and reports no remaining formatting
  changes. Use the Makefile's `pint` target first if the environment requires
  the project container.

- [ ] **Step 4: Update the knowledge graph**

  Run: `graphify update .`

  Expected: `graphify-out/` reflects the new service relationships.

- [ ] **Step 5: Review the final diff and commit the implementation**

  Run: `git diff --check && git status --short && git diff --stat`

  Confirm that only the planned services, consumers, tests, and deleted action
  files changed, then commit with:

  ```bash
  git add app tests docs/superpowers/plans graphify-out
  git commit -m "refactor: extract authentication services"
  ```
