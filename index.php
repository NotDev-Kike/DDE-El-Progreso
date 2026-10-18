<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params(['httponly' => true, 'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off', 'samesite' => 'Lax']);
    session_start();
}
require_once __DIR__ . '/includes/db.php';
if (isset($_SESSION['usuario'])) { header('Location: menu.php'); exit; }
$error = null;
if (isset($_POST['login'])) {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = (string) ($_POST['password'] ?? '');
    $stmt = $conn->prepare("SELECT usuario, rol, contrasena FROM usuarios WHERE usuario=? LIMIT 1");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row && password_verify($password, $row['contrasena'])) {
        $stmt->close(); session_regenerate_id(true);
        $_SESSION['usuario'] = $row['usuario']; $_SESSION['rol'] = $row['rol'];
        header('Location: menu.php'); exit;
    }
    $stmt->close(); $error = 'Revisa el usuario y la contraseña e inténtalo de nuevo.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="theme-color" content="#0f766e">
<title>Acceso | DDE El Progreso</title><link rel="stylesheet" href="css/style.css">
</head>
<body class="login-page">
<main class="login-layout">
    <section class="login-intro">
        <span class="brand-mark">DDE</span><p class="eyebrow">Dirección Distrital de Educación</p>
        <h1>Gestión educativa en un solo lugar.</h1>
        <p>Administra docentes, nombramientos, estructuras y reportes de El Progreso de forma clara y ordenada.</p>
        <div class="login-points"><span>✓ Información centralizada</span><span>✓ Acceso seguro por usuario</span><span>✓ Reportes listos para consultar</span></div>
    </section>
    <section class="login-card" aria-labelledby="login-title">
        <div class="login-card-heading"><p class="eyebrow">Bienvenido</p><h2 id="login-title">Iniciar sesión</h2><p>Ingresa tus credenciales para continuar.</p></div>
        <?php if ($error): ?><div class="alert error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
        <form method="post" autocomplete="on">
            <label for="usuario">Usuario</label><input id="usuario" type="text" name="usuario" autocomplete="username" required autofocus>
            <label for="password">Contraseña</label><input id="password" type="password" name="password" autocomplete="current-password" required>
            <button class="login-submit" type="submit" name="login">Ingresar <span aria-hidden="true">→</span></button>
        </form>
        <p class="login-help">Si no puedes ingresar, solicita apoyo al administrador del sistema.</p>
    </section>
</main>
<footer class="login-footer">DDE El Progreso · Gestión educativa</footer>
<style>
.login-page { min-height: 100vh; background: radial-gradient(circle at 12% 10%, #d9fffa 0, transparent 32%), var(--canvas); }
.login-layout { display: grid; grid-template-columns: minmax(0, 1fr) minmax(360px, 460px); align-items: center; gap: clamp(2rem, 8vw, 8rem); width: min(100% - 3rem, 1120px); min-height: calc(100vh - 70px); margin: auto; }
.login-intro { max-width: 600px; } .login-intro .brand-mark { margin-bottom: 2.5rem; } .login-intro h1 { max-width: 560px; margin: .5rem 0 1rem; font-size: clamp(2.3rem, 5vw, 4.5rem); }
.login-intro > p:not(.eyebrow) { max-width: 520px; color: var(--muted); font-size: 1.1rem; } .login-points { display: flex; flex-wrap: wrap; gap: .75rem 1.25rem; margin-top: 2rem; color: var(--brand-dark); font-weight: 700; font-size: .9rem; }
.login-card { padding: clamp(1.5rem, 4vw, 2.5rem); border: 1px solid var(--line); border-radius: 1.25rem; background: rgba(255,255,255,.94); box-shadow: 0 25px 60px rgba(24,49,47,.12); }
.login-card-heading h2 { margin: 0; font-size: 1.8rem; } .login-card-heading p:last-child { margin: .45rem 0 1.5rem; color: var(--muted); }
.login-card form { max-width: none; } .login-card label { margin-top: 1rem; } .login-card label:first-of-type { margin-top: 0; } .login-submit { width: 100%; margin-top: .5rem; justify-content: space-between; padding-inline: 1.1rem; }
.login-help { margin: 1.4rem 0 0; color: var(--muted); font-size: .8rem; text-align: center; } .login-footer { padding: 1rem; color: var(--muted); text-align: center; font-size: .8rem; }
@media (max-width: 800px) { .login-layout { grid-template-columns: 1fr; width: min(100% - 1.5rem, 520px); padding: 2.5rem 0; } .login-intro { text-align: center; } .login-intro .brand-mark { margin-bottom: 1.5rem; } .login-points { justify-content: center; } }
</style>
</body>
</html>
