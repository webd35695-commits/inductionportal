# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Overview

- Stack: Laravel 12 (PHP 8.2), Inertia.js, Vue 3, Vite, Tailwind CSS, Ziggy, Spatie Permission, Pusher/Laravel Echo.
- Architecture:
  - HTTP layer: routes in `routes/*.php` map to controllers in `app/Http/Controllers/*`.
  - Validation via FormRequest classes in `app/Http/Requests/*`.
  - Business logic encapsulated in services `app/Services/*` and repositories `app/Repositories/*`.
  - Async work via jobs in `app/Jobs/*`; real-time/events in `app/Events/*` (broadcast via Pusher/Echo).
  - Frontend SPA via Inertia: Vue pages in `resources/js/Pages/**`, shared components in `resources/js/Components/**`, layouts in `resources/js/Layouts/**`.
  - Blade entry-point: `resources/views/app.blade.php` boots Inertia app defined in `resources/js/app.js`.
  - Styling via Tailwind (`tailwind.config.js`); Vite alias `@` -> `resources/js` (see `vite.config.js`).
  - Auth/UI scaffolding from Breeze; roles/permissions managed via Spatie.

## Setup

Run from repository root:

- Install dependencies
  - PHP: `composer install`
  - Node: `npm install`
- Environment
  - Copy env and key: `copy .env.example .env` (Windows) or `cp .env.example .env` (Unix)
  - Generate key: `php artisan key:generate`
- Database (default dev uses SQLite)
  - Create/migrate/seed: `php artisan migrate --seed`

## Development

- Full stack (PHP server, queue listener, logs, Vite) in one command:
  - `composer run dev`
- Alternatively, run separately:
  - Start PHP server: `php artisan serve`
  - Start queue worker: `php artisan queue:listen --tries=1`
  - Tail app logs: `php artisan pail --timeout=0`
  - Vite dev server (HMR): `npm run dev`

Notes
- Frontend entry: `resources/js/app.js` with Vue 3 + Inertia; pages under `resources/js/Pages/**`.
- Route names are available client-side via Ziggy.

## Build

- Production assets: `npm run build` (Vite)
- Cache/config optimize (optional when preparing deploys):
  - `php artisan optimize`

## Testing

- Run full test suite: `composer test`
- Run a single test file: `php artisan test tests/Feature/ExampleTest.php`
- Filter by class/method: `php artisan test --filter=AuthenticationTest` or `php artisan test --filter="AuthenticationTest::test_users_can_authenticate_using_the_login_screen"`

Testing environment
- PHPUnit is configured in `phpunit.xml` to use in-memory SQLite and array drivers for cache/session/mail.

## Linting / Formatting

- PHP code style (Laravel Pint):
  - Check: `php vendor/bin/pint --test`
  - Fix: `php vendor/bin/pint`

## Useful application paths

- Controllers: `app/Http/Controllers/**` (Admin/Candidate domains split across files)
- Services: `app/Services/**` (e.g., `AgeRelaxationService`, `CenterAllocationService`)
- Repositories: `app/Repositories/**`
- Domain models: `app/Models/**`
- Requests/Validation: `app/Http/Requests/**`
- Events/Jobs: `app/Events/**`, `app/Jobs/**`
- Routes: `routes/web.php`, `routes/admin.php`, `routes/candidate.php`, `routes/api.php`, etc.
- Frontend: `resources/js/{Pages,Components,Layouts}/**`

## Real-time & Queues

- Broadcasting uses Pusher (server: `pusher/pusher-php-server`, client: `pusher-js` via Laravel Echo). Configure Pusher keys in `.env` when enabling real-time features.
- Queues default to `database` in `.env.example`. For local development, keep a listener running (`php artisan queue:listen`).