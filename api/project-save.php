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
$title = trim($_POST['title'] ?? '');
$category = trim($_POST['category'] ?? '');
$description = trim($_POST['description'] ?? '');
$technologies = trim($_POST['technologies'] ?? '');
$projectLink = trim($_POST['project_link'] ?? '');
$imageUrl = trim($_POST['image_url'] ?? '');

if ($title === '' || $category === '' || $description === '' || $technologies === '' || $projectLink === '' || $imageUrl === '') {
    json_response([
        'success' => false,
        'message' => 'Please complete all project fields.'
    ], 422);
}

$connection = get_db_connection();

if ($id > 0) {
    $statement = $connection->prepare('UPDATE projects SET title = ?, category = ?, description = ?, technologies = ?, project_link = ?, image_url = ? WHERE id = ?');
    $statement->bind_param('ssssssi', $title, $category, $description, $technologies, $projectLink, $imageUrl, $id);
    $statement->execute();

    json_response([
        'success' => true,
        'message' => 'Project updated successfully.'
    ]);
}

$statement = $connection->prepare('INSERT INTO projects (title, category, description, technologies, project_link, image_url) VALUES (?, ?, ?, ?, ?, ?)');
$statement->bind_param('ssssss', $title, $category, $description, $technologies, $projectLink, $imageUrl);
$statement->execute();

json_response([
    'success' => true,
    'message' => 'Project added successfully.'
]);
