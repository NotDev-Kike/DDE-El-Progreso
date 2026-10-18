<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/db.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax'
    ]);
    session_start();
}

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function respond(array $data, int $status = 200): never {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function body(): array {
    $raw = file_get_contents('php://input') ?: '{}';
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function requireAuth(): void {
    if (empty($_SESSION['usuario'])) {
        respond(['ok' => false, 'message' => 'Sesión no autorizada'], 401);
    }
}

$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
$action = $_GET['action'] ?? '';

if ($method === 'POST' && $action === 'login') {
    $input = body();
    $username = trim((string)($input['usuario'] ?? ''));
    $password = (string)($input['password'] ?? '');

    $stmt = $conn->prepare('SELECT usuario, contrasena, nombre_completo, rol FROM usuarios WHERE usuario = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user || !password_verify($password, $user['contrasena'])) {
        respond(['ok' => false, 'message' => 'Usuario o contraseña incorrectos'], 422);
    }

    session_regenerate_id(true);
    $_SESSION['usuario'] = $user['usuario'];
    $_SESSION['nombre_completo'] = $user['nombre_completo'];
    $_SESSION['rol'] = $user['rol'];
    respond(['ok' => true, 'user' => [
        'usuario' => $user['usuario'],
        'nombre' => $user['nombre_completo'],
        'rol' => $user['rol']
    ]]);
}

if ($method === 'POST' && $action === 'logout') {
    $_SESSION = [];
    session_destroy();
    respond(['ok' => true]);
}

if ($method === 'GET' && $action === 'session') {
    if (empty($_SESSION['usuario'])) {
        respond(['ok' => false, 'message' => 'Sesión no autorizada'], 401);
    }
    respond(['ok' => true, 'user' => [
        'usuario' => $_SESSION['usuario'],
        'nombre' => $_SESSION['nombre_completo'] ?? $_SESSION['usuario'],
        'rol' => $_SESSION['rol'] ?? 'usuario'
    ]]);
}

requireAuth();

if ($method !== 'GET') {
    respond(['ok' => false, 'message' => 'Operación no disponible todavía'], 405);
}

$resource = $_GET['resource'] ?? 'dashboard';
$queries = [
    'docentes' => 'SELECT id_docente AS id, nombre_docente AS nombre, identidad FROM docentes ORDER BY nombre_docente',
    'estructuras' => 'SELECT e.id_estructura AS id, e.codigo, e.nivel, e.tipo_plaza, e.asignada, d.nombre_docente AS docente FROM estructuras e LEFT JOIN docentes d ON d.id_docente = e.id_docente ORDER BY e.codigo',
    'nombramientos' => 'SELECT id_nombramiento AS id, nombre_nombramiento AS nombre FROM nombramientos ORDER BY nombre_nombramiento',
    'usuarios' => 'SELECT id_usuario AS id, usuario, nombre_completo AS nombre, CASE WHEN rol = "admin" THEN "Administrador" ELSE "Usuario" END AS rol FROM usuarios ORDER BY usuario',
    'nombramiento-basica' => 'SELECT id, nombre_docente AS nombre, identidad, nivel_educativo, fecha_inicio, numero_horas FROM formato_nombramiento_basica ORDER BY id DESC',
    'nombramiento-media' => 'SELECT id, nombre_docente AS nombre, identidad, tipo_nombramiento, fecha_inicio, numero_horas FROM formato_nombramiento_media ORDER BY id DESC',
    'traslado-basica' => 'SELECT id, nombre_docente AS nombre, identidad, nivel_educativo, fecha_inicio FROM formato_traslado_basica ORDER BY id DESC',
    'traslado-media' => 'SELECT id, nombre_docente AS nombre, identidad, tipo_nombramiento, fecha_inicio FROM formato_traslado_media ORDER BY id DESC',
    'cancelaciones' => 'SELECT id, nombre_propuesto AS nombre, no_identidad AS identidad, tipo_accion, nombre_centro_educativo AS centro_educativo, nivel_educativo, fecha FROM formato_cancelacion ORDER BY id DESC'
];

if ($resource === 'dashboard') {
    $counts = [];
    foreach (['docentes', 'estructuras', 'nombramientos', 'usuarios'] as $table) {
        $result = $conn->query("SELECT COUNT(*) AS total FROM `$table`");
        $counts[$table] = (int)$result->fetch_assoc()['total'];
    }
    respond(['ok' => true, 'data' => $counts]);
}

if (!isset($queries[$resource])) {
    respond(['ok' => false, 'message' => 'Recurso no encontrado'], 404);
}

$result = $conn->query($queries[$resource]);
if ($result === false) {
    respond(['ok' => false, 'message' => 'No se pudo consultar este módulo. Verifique la estructura de la base de datos.'], 500);
}
$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
respond(['ok' => true, 'data' => $rows]);
