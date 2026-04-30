<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_NAME', 'student_portfolio');
define('DB_USER', 'root');
define('DB_PASS', '');

function get_db_connection(): mysqli
{
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($connection->connect_error) {
        die('Database connection failed: ' . $connection->connect_error);
    }

    $connection->set_charset('utf8mb4');
    return $connection;
}

function json_response(array $data, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function is_admin_logged_in(): bool
{
    return isset($_SESSION['admin_id'], $_SESSION['admin_username']);
}

function require_admin_login(): void
{
    if (!is_admin_logged_in()) {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';

        if (strpos($requestUri, '/api/') !== false || strpos($requestUri, '\\api\\') !== false) {
            json_response([
                'success' => false,
                'message' => 'Your session has expired. Please log in again.'
            ], 401);
        }

        header('Location: login.php');
        exit;
    }
}

function escape_html(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
