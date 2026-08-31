<?php
session_start();
require __DIR__ . '/config.php';
function h($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function csrf(): string { if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(24)); return $_SESSION['csrf']; }
if (!empty($_SESSION['user'])) { header('Location: ./'); exit; }
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { $error='Your session expired. Please refresh and try again.'; }
    else {
        $email=trim($_POST['email']??''); $pass=$_POST['password']??''; $pdo=db();
        $s=$pdo->prepare("SELECT id,first_name,last_name,name,email,password,organisation_id,administrator FROM users WHERE email=? AND deleted_at IS NULL AND active=1 LIMIT 1"); $s->execute([$email]); $u=$s->fetch();
        if (!$u) { $s=$pdo->prepare("SELECT id,first_name,last_name,name,email,password,1 organisation_id,1 administrator FROM admins WHERE email=? AND deleted_at IS NULL AND active=1 LIMIT 1"); $s->execute([$email]); $u=$s->fetch(); }
        if ($u && password_verify($pass,$u['password'])) { session_regenerate_id(true); $_SESSION['user']=['id'=>(int)$u['id'],'name'=>$u['name'] ?: trim($u['first_name'].' '.$u['last_name']),'email'=>$u['email'],'org'=>(int)$u['organisation_id'],'admin'=>(bool)$u['administrator']]; header('Location: ./'); exit; }
        $error='Invalid email or password.';
    }
}
?><!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Sign in · C’est Bon</title><link rel="stylesheet" href="style.css"></head><body class="login"><div class="login-card"><div class="brand">C’est Bon<span>Portal</span></div><div class="eyebrow">Client & Business Portal</div><h1>Sign in</h1><?php if($error): ?><div class="error"><?=h($error)?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf" value="<?=h(csrf())?>"><label>Email address<input type="email" name="email" autocomplete="username" required></label><label>Password<input type="password" name="password" autocomplete="current-password" required></label><button class="btn full">Sign in</button></form><p class="muted small">Secure portal access</p></div></body></html>
