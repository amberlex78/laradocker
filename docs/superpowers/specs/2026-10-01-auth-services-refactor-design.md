# Authentication Services Refactor Design

## Goal

Make authentication, device detection, login activity, account sessions, and
session termination easier to understand by moving domain responsibilities out
of controllers and Fortify response adapters while preserving the current
observable behavior.

## Current state

Authentication behavior is split between `app/Actions/Auth`, a Fortify login
response, and account controllers. The existing implementation works, but
several classes know too much about neighboring concerns:

- `RecordUserLogin` detects device information, updates the latest login fields,
  and creates login history.
- `ResolveDeviceInformation` owns User-Agent parsing, client hints, and browser
  fallback rules.
- `AccountController` queries the `sessions` table, orders sessions, maps
  User-Agent data, and loads login history for the view.
- `TerminateOtherSessions` directly performs session cleanup.
- `LoginResponse` coordinates login recording and role-based redirection.

## Proposed architecture

Introduce focused services under `app/Services/Auth`:

### `DeviceDetectionService`

Owns all device information resolution. It supports both request-based login
data and stored User-Agent data used by the account page. It preserves the
existing client hints, tablet fallback, unknown-value normalization, and
device-detector integration.

### `LoginActivityService`

Owns recording a successful login. It resolves the request's device and IP
information, updates the user's latest login columns, and creates a
`login_histories` record. The two persistence operations should execute as one
transaction so a partial login record cannot be stored.

### `UserSessionService`

Owns active-session lookup and session termination. It returns only active
sessions for the requested user, caps the result at ten, maps device data, and
places the current session first.

The current-session ordering will not use a raw SQL `CASE` expression. The
service will load the current session separately, load up to nine other
sessions with ordinary ordering, and merge them in application code. If the
current session is absent from the session table, the service returns up to
ten other sessions.

## Responsibilities after refactoring

- `AccountController` only obtains the authenticated user, calls services, and
  passes prepared data to the view.
- `AccountSessionController` only delegates termination and redirects with the
  existing status message.
- `LoginResponse` remains the Fortify adapter and delegates login activity and
  landing-route resolution.
- `ResolveUserLandingRoute` remains a focused action because role-to-route
  resolution is already a single responsibility.
- Blade views and route behavior remain unchanged.

## Explicitly out of scope

- No new authentication features.
- No changes to Fortify configuration or login routes.
- No repository layer over Eloquent or the query builder.
- No single catch-all `AuthenticationService`.
- No speculative database index migration. The existing session indexes will be
  left unchanged unless a measured query-plan problem is found.
- No changes to the public shape of data passed to the account view.

## Preserved behavior

The refactor must preserve:

- role-based login redirects;
- latest-login fields on `users`;
- login-history records and trusted-proxy IP behavior;
- User-Agent, client-hints, browser fallback, and unknown-device behavior;
- active-session filtering by user and session lifetime;
- current-session-first ordering and the ten-session display limit;
- exclusion of sessions belonging to other users;
- termination of all other sessions while keeping the current session;
- login-history pagination and reverse chronological ordering.

## Testing strategy

Existing feature tests remain the primary behavior contract. Add focused tests
only for behavior introduced by the service boundaries, especially:

- active sessions place the current session first without relying on raw SQL
  ordering;
- an absent current session still returns the allowed number of other sessions;
- login activity persists the latest user details and history together;
- session termination affects only the authenticated user's other sessions.

Run the narrow authentication feature tests first, then the complete test
suite, Laravel Pint, and `graphify update .` after code changes.

## Risks and trade-offs

The active-session lookup will use two simple queries instead of one query with
a `CASE` ordering expression. This adds one small database round trip but makes
the business rule explicit, keeps the query easy to inspect, and remains
bounded by ten rows. Device detection remains in-process and does not add
database queries.
