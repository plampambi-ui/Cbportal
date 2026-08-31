# C'est Bon Portal — standalone PHP

This folder is a dependency-free PHP/MySQL launch path. It does not require Laravel, Filament, Livewire, Node, or Composer.

## Hostinger
1. Upload the contents of `plain-php/` into the domain's `public_html`.
2. Copy `config.example.php` to `config.php`.
3. Put the Hostinger MySQL database name, username, password, and host into `config.php`.
4. Import the existing C'est Bon database into that MySQL database. The login expects the existing `users` table and Laravel-compatible password hashes.
5. Open the domain. The standalone login page is `index.php`.

Do not commit `config.php` because it contains database credentials.
