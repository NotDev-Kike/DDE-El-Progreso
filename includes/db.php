<?php
/**
 * Conexión local a MariaDB/MySQL.
 *
 * Los valores se pueden sobrescribir mediante variables de entorno:
 * DDE_DB_HOST, DDE_DB_USER, DDE_DB_PASS y DDE_DB_NAME.
 */
$host = getenv('DDE_DB_HOST') ?: '127.0.0.1';
$user = getenv('DDE_DB_USER') ?: 'root';
$pass = getenv('DDE_DB_PASS') ?: '';
$db   = getenv('DDE_DB_NAME') ?: 'dde_progreso';
$port = (int) (getenv('DDE_DB_PORT') ?: 3306);

mysqli_report(MYSQLI_REPORT_OFF);
$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_errno) {
    http_response_code(503);
    die('No se pudo conectar a la base de datos local. Verifique que MySQL/MariaDB esté iniciado y que exista la base de datos "' . htmlspecialchars($db, ENT_QUOTES, 'UTF-8') . '".');
}

$conn->set_charset('utf8mb4');

// Base URL del proyecto, compatible con XAMPP/WAMP aunque esté dentro de una subcarpeta.
if (!defined('BASE_URL')) {
    $documentRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '') ?: '';
    $projectRoot = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..') ?: dirname(__DIR__);
    $projectRoot = str_replace('\\', '/', $projectRoot);
    $documentRoot = str_replace('\\', '/', $documentRoot);
    $basePath = $documentRoot !== '' && strpos(strtolower($projectRoot), strtolower($documentRoot)) === 0
        ? substr($projectRoot, strlen($documentRoot))
        : '';
    define('BASE_URL', rtrim('/' . ltrim(str_replace('//', '/', $basePath), '/'), '/'));
}
?>
