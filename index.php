<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax'
    ]);
    session_start();
}
require_once __DIR__ . '/includes/db.php';

if(isset($_POST['login'])){
    $usuario = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario=? LIMIT 1");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1){
        $row = $result->fetch_assoc();

        if(password_verify($password, $row['contrasena'])){
            session_regenerate_id(true);
            $_SESSION['usuario'] = $row['usuario'];
            $_SESSION['rol'] = $row['rol'];
            header("Location: menu.php");
            exit;
        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "Usuario no encontrado";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login - DDE El Progreso</title>
<style>
    body {
        background-color: #d4edda;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    h1 {
        text-align: center;
        color: #155724;
        margin-top: 50px;
        font-size: 36px;
        font-weight: bold;
    }

    .login-container {
        background-color: #f8f9fa;
        width: 100%;
        max-width: 400px;
        margin: 40px auto;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        text-align: center;
    }

    .logo-container {
        margin-bottom: 20px;
    }

    .logo-container img {
        max-width: 150px;
        height: auto;
        display: inline-block;
    }

    h2 {
        color: #155724;
        margin-bottom: 20px;
    }

    label {
        display: block;
        font-weight: bold;
        color: #155724;
        margin-bottom: 5px;
        text-align: left;
    }

    input[type="text"], input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
    }

    button {
        width: 100%;
        background-color: #28a745;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #218838;
    }

    .error {
        color: #d9534f;
        text-align: center;
        margin-bottom: 15px;
        font-weight: bold;
    }

    footer {
        text-align: center;
        padding: 20px;
        background-color: #c3e6cb;
        font-size: 16px;
        color: #155724;
        position: fixed;
        width: 100%;
        bottom: 0;
    }
</style>
</head>
<body>

<h1>Dirección Distrital de Educación - El Progreso</h1>

<div class="login-container">
    <div class="logo-container">
        <img src="imgs/logo.jpg" alt="Logo DDE El Progreso">
    </div>

    <h2>Iniciar Sesión</h2>

    <?php if(isset($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Usuario:</label>
        <input type="text" name="usuario" required>

        <label>Contraseña:</label>
        <input type="password" name="password" required>

        <button type="submit" name="login">Ingresar</button>
    </form>
</div>

<footer>
    &copy; Jose Enrique Pineda Rivera
</footer>

</body>
</html>
