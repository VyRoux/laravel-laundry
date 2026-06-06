# Laundry Ibu — Agent Guide

## Stack
- **Laravel 13** (PHP 8.3+), Blade + Alpine.js, Tailwind CSS v4 (CDN), Vite
- **MySQL** on InfinityFree (`.env` uses `mysql`, not default `sqlite`)

## Setup & Dev
```bash
composer setup          # full install: deps, .env, key, migrate, npm, build
composer dev            # concurrent: artisan serve + queue:listen + pail + vite
composer test           # config:clear → artisan test (PHPUnit, not Pest)
```

## Architecture
- **Auth**: username/password (not email), roles: `admin`, `kasir`, `owner`
- **Role middleware**: `role:admin,kasir` pattern — registered as alias in `bootstrap/app.php:15`
- **Routes**: all under `/dashboard` prefix, grouped by role:
  - `admin,kasir` → member, paket, transaksi CRUD + trash/restore
  - `admin` → outlet, user CRUD + force-delete on all entities
  - `owner` → placeholder (no routes active)
- **All models** use `SoftDeletes` + custom `tbl_` table prefix (tbl_outlet, tbl_user, tbl_member, tbl_paket, tbl_transaksi, tbl_detail_transaksi)
- **Views**: Blade only, extends `layouts.main`, all admin views under `resources/views/admin/`
- **Invoice**: auto-generated as `INV/YYYYMMDD/00001` (sequential per day)

## DB & Config
- **Timezone**: `config/app.php:68` — `Asia/Jakarta`
- **Locale**: `id` (Indonesian)
- **Queue**: `database` driver; **Cache**: `database` driver; **Session**: `file` driver
- **Seeder**: creates outlet "Laundry Pusat" + admin user (`admin` / `admin123`)
- **Tests**: in-memory SQLite (`phpunit.xml`)
- **Migrations**: 9 files covering all tables + soft deletes

## Style
- **Laravel Pint** for formatting (default rules)
- **EditorConfig**: spaces, 4-space indent, LF endings, UTF-8
