# C'est Bon Portal — Hostinger edition

This folder is a standalone PHP/MySQL edition. It does **not** require Laravel, Composer, Node, Artisan, Vite, or Termux.

## Deploy
1. In Hostinger, create/select the MySQL database that already contains the C'est Bon tables.
2. Upload the **contents of this `hostinger/` folder** into the site's document root (`public_html`).
3. Open `config.php` and replace `YOUR_DATABASE`, `YOUR_DATABASE_USER`, and `YOUR_DATABASE_PASSWORD`. Leave `CB_DB_HOST` as `localhost` unless Hostinger shows a different database host.
4. Visit `/setup.php` once to create an administrator, then **delete `setup.php` from the server**.
5. Visit `/login`.

## What is included
- C'est Bon branded responsive portal shell
- Admin/client authentication against the existing `users` / `admins` tables
- Dashboard counts
- Clients, projects, tasks, tickets and calendar views
- Admin creation of clients and projects
- CSRF protection and password verification
- MySQL prepared statements
- No framework runtime dependency

The original Laravel application remains in the repository as the reference/source implementation. This folder is the simplified deployment target.
