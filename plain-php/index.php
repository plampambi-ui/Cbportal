<?php
/* C'est Bon Portal - standalone Hostinger bootstrap.
 * This intentionally has no Laravel/Filament dependency.
 * Configure DB values in config.php (copy config.example.php).
 */
$cfgFile = __DIR__ . '/config.php';
if (!is_file($cfgFile)) {
    http_response_code(503);
    echo '<h1>C\'est Bon Portal</h1><p>Setup required: copy <code>config.example.php</code> to <code>config.php</code> and enter your Hostinger database details.</p>';
    exit;
}
require $cfgFile;
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

function db(): PDO {
    static $pdo;
    global $CB_DB;
    if ($pdo) return $pdo;
    $pdo = new PDO(
        'mysql:host=' . $CB_DB['host'] . ';dbname=' . $CB_DB['name'] . ';charset=utf8mb4',
        $CB_DB['user'], $CB_DB['pass'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
    return $pdo;
}
function h($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function logged_in(): bool { return !empty($_SESSION['cb_user']); }

if (($_GET['action'] ?? '') === 'logout') { session_destroy(); header('Location: ./'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
    try {
        $s = db()->prepare('SELECT id, name, email, password, active, administrator FROM users WHERE email = ? LIMIT 1');
        $s->execute([trim($_POST['email'] ?? '')]);
        $u = $s->fetch();
        if ($u && (int)$u['active'] === 1 && password_verify($_POST['password'] ?? '', $u['password'])) {
            session_regenerate_id(true); unset($u['password']); $_SESSION['cb_user'] = $u;
            header('Location: ./'); exit;
        }
        $error = 'Invalid email or password.';
    } catch (Throwable $e) { $error = 'Database connection failed. Check config.php.'; }
}
?><!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>C'est Bon Portal</title>
<style>body{margin:0;background:#f5f7fa;color:#172033;font:16px system-ui,-apple-system,sans-serif}.wrap{max-width:1100px;margin:0 auto;padding:32px}.brand{font-size:28px;font-weight:800}.card{background:#fff;border:1px solid #e4e8ef;border-radius:18px;padding:28px;box-shadow:0 8px 30px #1720330d}input,button{width:100%;box-sizing:border-box;padding:13px;border-radius:10px;border:1px solid #ccd3df;font:inherit}button{background:#014786;color:white;border:0;font-weight:700;cursor:pointer}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px}.muted{color:#687386}.err{background:#fff0f0;color:#a22;padding:12px;border-radius:10px;margin-bottom:15px}a{color:#014786;text-decoration:none}</style></head><body><div class="wrap"><div class="brand">C'est Bon</div><div class="muted">Client &amp; Business Portal</div><br>
<?php if (!logged_in()): ?><div class="card" style="max-width:420px;margin:50px auto"><h1>Sign in</h1><?php if($error): ?><div class="err"><?=h($error)?></div><?php endif; ?><form method="post"><input type="hidden" name="action" value="login"><label>Email address</label><br><input name="email" type="email" required autocomplete="username"><br><br><label>Password</label><br><input name="password" type="password" required autocomplete="current-password"><br><br><button>Sign in</button></form></div>
<?php else: $u=$_SESSION['cb_user']; ?><div class="card"><div style="display:flex;justify-content:space-between;gap:15px;align-items:center"><div><h1>Welcome, <?=h($u['name'])?></h1><div class="muted"><?=h($u['email'])?></div></div><a href="?action=logout">Sign out</a></div><hr><div class="grid"><div class="card"><b>Clients</b><p class="muted">Client management</p></div><div class="card"><b>Projects</b><p class="muted">Projects and work</p></div><div class="card"><b>Tasks</b><p class="muted">Tasks and assignments</p></div><div class="card"><b>Announcements</b><p class="muted">Portal updates</p></div></div></div><?php endif; ?></div></body></html>