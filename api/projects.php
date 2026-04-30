<?php
require_once __DIR__ . '/../config.php';

$connection = get_db_connection();
$result = $connection->query('SELECT id, title, category, description, technologies, project_link, image_url FROM projects ORDER BY created_at DESC');

$projects = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
}

json_response([
    'success' => true,
    'projects' => $projects
]);
