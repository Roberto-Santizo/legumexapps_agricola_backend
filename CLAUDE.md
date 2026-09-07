# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

JSON REST API (Laravel 13 / PHP 8.4) for LegumexApps' agricultural operations module ("agricola"): fincas, lotes, crops, CDPs (plantation controls), weekly plans and the tasks/employees/supplies attached to them, plus Excel reports. Deployed to AWS Lambda via Laravel Vapor. There is no meaningful frontend — Vite/Tailwind are leftover skeleton.

Domain vocabulary is Spanish and leaks into DB tables and columns while class names are English. Expect mismatches: `Task` → table `tareas`, `WeeklyPlanTask` → `task_weekly_plans`, `WeeklyPlanTaskInsumo` → `task_insumos`, `Cdp` → `plantation_controls`. Always check `$table` and `#[Fillable]` on the model rather than inferring. User-facing messages (API `message`, validation `messages()`) are Spanish.

## Commands

```bash
composer setup            # install, .env, key, migrate, npm build
composer dev              # serve + queue:listen + vite concurrently
composer test             # config:clear then php artisan test (Pest)
php artisan test --filter=SomeTest    # single test
./vendor/bin/pest tests/Feature/X.php # single file
./vendor/bin/pint          # format (no pint.json; Laravel preset)
php artisan migrate
```

Tests run on in-memory SQLite (`phpunit.xml`); local/prod is MySQL. `RefreshDatabase` is commented out in `tests/Pest.php`. The suite is currently only the Laravel skeleton examples.

Deploy: push to `main` triggers `.github/workflows/vapor-deploy.yml` → `vapor deploy production`. `vapor.yml` defines `production` and `staging`.

## Architecture

**Controller → Interface → Service → Eloquent.** Every domain entity has this four-part set, and adding an entity means adding all four plus registration:

1. `app/Interfaces/<Area>/XServiceInterface.php`
2. `app/Services/<Area>/XService.php` — implements it, marks overrides with `#[Override]`, throws `App\Errors\*`
3. `app/Providers/<Area>/XServiceProvider.php` — a whole provider class whose only job is one `$this->app->bind(Interface::class, Service::class)`
4. register the provider in `bootstrap/providers.php`

Services are injected **as method parameters on controller actions**, not via constructor:

```php
public function index(Request $request, FincaServiceInterface $service) { ... }
```

Areas are `Agricola` (the bulk), `Auth`, `Users`, `Permissions`, `Employees`.

**Controllers stay thin and uniform.** Every action is `try { ... return ResponseHandler::success($data, 'Mensaje', 200); } catch (\Throwable $th) { return ResponseHandler::error($th); }`. No business logic in controllers.

**Errors → status codes.** Services throw `App\Errors\{NotFoundError, BadRequestError, NotAcceptable}`, all extending the abstract `ApiException` with `getStatusCode()`. `ResponseHandler::error()` reads that code; anything else becomes 500. To add a status code, add an `ApiException` subclass — don't return responses from services.

**Response envelope** is always `{statusCode, message, data}`. `ResponseHandler::success()` has one special case: if the resolved resource is an array containing a `data` key plus other keys, those siblings are hoisted to the top level. That's how paginated endpoints work — `PaginatedXResource` returns `['data' => ..., 'total', 'currentPage', 'lastPage']` and those land next to `data`, not inside it. Pagination is opt-in per request via `?limit=`; controllers pick `PaginatedXResource` vs `XResource::collection()` on that.

**Multi-step domain operations live in `app/Actions/`** as single-`execute()` classes (`CloseWeeklyPlanTaskAction`, `ExplodeCdpTasksAction`, `ConfirmDraftWeeklyPlan`). Single-action controllers (`__invoke`) exist too: `RecalculateTask`, `GetFincaEmployees`.

## Auth & authorization

JWT via `tymon/jwt-auth`, not Sanctum (Sanctum is installed but unused). Custom claims (`id`, `name`, `username`, `role`) are embedded in the token by `User::getJWTCustomClaims()`.

Two layers, both applied in route files:
- `jwt.auth` (package middleware) wraps essentially every route
- `admin` (`IsAdmin`) and `administrate_agricola` (`IsAdminagricola`) — aliased in `bootstrap/app.php`, they read `auth()->user()->role` and throw `UnauthorizedException`. Roles: `admin`, `adminagricola`.

There is also a per-user `UserPermission` model/table consumed by the frontend; it is **not** enforced by middleware. Some services additionally branch on `role` internally (e.g. `WeeklyPlanTaskService::getWeeklyPlanTasksByCdp`), so role checks are not only in middleware.

## Routes

`routes/api.php` is just `require_once` of `auth.php`, `users.php`, `permissions.php`, `agricola.php`, `reports.php`. All are under the `/api` prefix.

`agricola.php` has two blocks: `apiResource` CRUD at the top, then a "FUNCTIONALITYS" block of verb-style endpoints (`/weekly-plans/uploadTasks/{id}`, `/cdps/explodeTasks/{id}`, `/weekly-plan-tasks/closeTask/{id}`). Note that CRUD-restricted-to-admin vs open-to-any-authenticated-user is decided by which nested `administrate_agricola` group a route sits in — read the group nesting before adding a route.

## External integrations

`BIOMETRICO_URL` + `BIOMETRICO_APP_KEY` — an external biometric attendance API queried with `Http::withHeaders(['Authorization' => env(...)])`. Used by `EmployeesService` (roster per `finca.department_id`) and by task-closing actions to pull real clock-in entries for payment calculation. Note `FLS` finca is special-cased to also pull `department_id=7`.

Excel via `maatwebsite/excel`: `app/Imports/Agricola/*` (bulk task/guideline/supply upload endpoints) and `app/Exports/Agricola/*` (the three `/reports/*` endpoints).

## Gotchas

- `bootstrap/providers.php` contains three entries with no matching class (`RecipeProvider`, `TaskGuidelinesProvider`, `TaskServiceInterface`). The app boots anyway; don't copy those names as a pattern.
- Models use PHP attributes (`#[Fillable([...])]`, `#[Hidden([...])]`) rather than `protected $fillable`.
- Config is read with `env()` directly inside services, so `php artisan config:cache` would break those reads.
