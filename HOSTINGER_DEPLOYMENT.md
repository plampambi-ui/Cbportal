# C’est Bon Portal — Hostinger deployment

## Requirements

- Hostinger PHP 8.2+ hosting
- MySQL database
- HTTPS enabled
- SSH access recommended

## Deployment

1. Upload the repository to the Hostinger application directory.
2. Set the domain document root to the project's `public` directory.
3. Copy `.env.example` to `.env`.
4. Set `APP_KEY` with `php artisan key:generate` (SSH), or generate it before upload.
5. Set the MySQL values in `.env`.
6. Set `APP_URL` to the real HTTPS domain.
7. Run `composer install --no-dev --optimize-autoloader` if Composer is available through SSH.
8. Run `php artisan migrate --force` only when the database needs the application's migrations.
9. Run `php artisan storage:link` if media/file links are required.
10. Run `php artisan optimize` after configuration is complete.

## Critical safety rule

This project must never run `migrate:fresh --seed` automatically on a schedule. That command destroys existing tables/data and is intentionally not part of the production bootstrap.

## Local testing

For local development use HTTP, for example `http://127.0.0.1:8090`. HTTPS is forced only when the Laravel environment is `production`.

## Primary application panels

- Client/business application: `/app`
- Administration: `/admin`
