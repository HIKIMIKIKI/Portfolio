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

$connection = get_db_connection();
$statement = $connection->prepare('DELETE FROM projects WHERE id = ?');
$statement->bind_param('i', $id);
$statement->execute();

json_response([
    'success' => true,
    'message' => 'Project deleted successfully.'
]);
