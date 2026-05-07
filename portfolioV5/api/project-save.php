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

$payload = [
    'title' => $title,
    'category' => $category,
    'description' => $description,
    'technologies' => $technologies,
    'project_link' => $projectLink,
    'image_url' => $imageUrl
];

if ($id > 0) {
    $response = supabase_request('PATCH', 'projects?id=eq.' . $id, $payload);

    if ($response['status'] >= 400) {
        json_response([
            'success' => false,
            'message' => 'Could not update project.',
            'details' => $response['data']
        ], $response['status']);
    }

    json_response([
        'success' => true,
        'message' => 'Project updated successfully.'
    ]);
}

$response = supabase_request('POST', 'projects', [$payload]);

if ($response['status'] >= 400) {
    json_response([
        'success' => false,
        'message' => 'Could not add project.',
        'details' => $response['data']
    ], $response['status']);
}

json_response([
    'success' => true,
    'message' => 'Project added successfully.'
]);
