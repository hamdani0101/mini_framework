# mini_framework

Minimal plain-PHP application skeleton (no Laravel / no full-stack framework).

Stack: **PHP ≥ 8.1 · nikic/fast-route · twig/twig · laminas/laminas-diactoros (PSR-7, available for future use) · PDO**

## Structure

```text
app/            Controllers/ Models/ Services/ Repositories/ Middleware/ Helpers/ Config/ Core/
config/         app.php database.php services.php bootstrap.php
public/         index.php (only web entry point = document root), assets/, uploads/
routes/         web.php
views/          layouts/ components/ pages/ errors/   (Twig)
database/       migrations/ seeders/
storage/        logs/ cache/ sessions/
tests/          Unit/ Feature/   (PHPUnit)
.env            local secrets (never committed) — see .env.example
```

Layer rules: Controllers = HTTP glue only · Services = business logic ·
Repositories = PDO with prepared statements · Models = plain data objects ·
Middleware = request filtering · Views = presentation only (no SQL, no business logic).

`app/Core/` holds framework glue (Env, Config, Database, View, Router, Logger).
`app/Config/` is reserved for project-specific config builder classes.

## Setup

```bash
cp .env.example .env        # edit DB_* / APP_* values
composer install
```

## Run

Document root **must** be `public/`:

```bash
# local dev
php -S localhost:8000 -t public

# apache: point VirtualHost DocumentRoot to <project>/public
```

Routes: `GET /` (welcome page), `GET /health` (JSON). Add routes in `routes/web.php`.

## Tests

```bash
vendor/bin/phpunit
```

## Notes

- `config/bootstrap.php` is included by `public/index.php`; it loads
  Composer autoload, `.env` (never overrides real env vars), `config/*.php`,
  and starts the session.
- DB credentials come from `.env` via `config/database.php`. No credentials
  in source. `.env` is git-ignored and unreachable from the web (it lives
  outside `public/`).
- Errors are logged to `storage/logs/YYYY-MM-DD.log`; generic messages are
  shown when `APP_DEBUG=false`.
- `vendor/` is currently committed in this repo (legacy); new installs only
  need `composer install`. Consider `git rm -r --cached vendor` as follow-up.
