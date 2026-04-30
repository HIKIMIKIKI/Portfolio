<?php
require_once __DIR__ . '/../config.php';
require_admin_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response([
        'success' => false,
        'message' => 'Invalid request method.'
    ], 405);
}

$id = (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    json_response([
        'success' => false,
        'message' => 'Invalid project ID.'
    ], 422);
}

$response = supabase_request('DELETE', 'projects?id=eq.' . $id);

if ($response['status'] >= 400) {
    json_response([
        'success' => false,
        'message' => 'Could not delete project.',
        'details' => $response['data']
    ], $response['status']);
}

json_response([
    'success' => true,
    'message' => 'Project deleted successfully.'
]);
