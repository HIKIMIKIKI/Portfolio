<?php
session_start();

define('SUPABASE_URL', 'https://ylrobivedifuvnwhnatp.supabase.co');
define('SUPABASE_SERVICE_ROLE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Inlscm9iaXZlZGlmdXZud2huYXRwIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc3NzU1MDMzMywiZXhwIjoyMDkzMTI2MzMzfQ.aU7Op2A5qbGOk1iB0etRx9gcOs7Ze20skfSa1SPm1A4');

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

function supabase_request(string $method, string $path, ?array $payload = null): array
{
    $url = rtrim(SUPABASE_URL, '/') . '/rest/v1/' . ltrim($path, '/');

    $headers = [
        'apikey: ' . SUPABASE_SERVICE_ROLE_KEY,
        'Authorization: Bearer ' . SUPABASE_SERVICE_ROLE_KEY,
        'Content-Type: application/json',
        'Prefer: return=representation'
    ];

    $options = [
        'http' => [
            'method' => $method,
            'header' => implode("\r\n", $headers),
            'ignore_errors' => true,
            'timeout' => 20
        ]
    ];

    if ($payload !== null) {
        $options['http']['content'] = json_encode($payload);
    }

    $context = stream_context_create($options);
    $responseBody = @file_get_contents($url, false, $context);
    $responseHeaders = $http_response_header ?? [];
    $statusCode = 500;

    if (isset($responseHeaders[0]) && preg_match('/\s(\d{3})\s/', $responseHeaders[0], $matches)) {
        $statusCode = (int) $matches[1];
    }

    $decoded = $responseBody !== false && $responseBody !== ''
        ? json_decode($responseBody, true)
        : [];

    return [
        'status' => $statusCode,
        'data' => is_array($decoded) ? $decoded : [],
        'raw' => $responseBody === false ? '' : $responseBody
    ];
}
