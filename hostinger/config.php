<?php
// C'est Bon standalone Hostinger configuration.
// Copy this folder into public_html and edit these four values.
const CB_DB_HOST = 'localhost';
const CB_DB_NAME = 'YOUR_DATABASE';
const CB_DB_USER = 'YOUR_DATABASE_USER';
const CB_DB_PASS = 'YOUR_DATABASE_PASSWORD';
const CB_APP_NAME = "C'est Bon";
const CB_BASE = '';

function db(): PDO {
    static $pdo;
    if (!$pdo) {
        $pdo = new PDO('mysql:host=' . CB_DB_HOST . ';dbname=' . CB_DB_NAME . ';charset=utf8mb4', CB_DB_USER, CB_DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    return $pdo;
}
