<?php
require_once __DIR__ . '/../config.php';

$response = supabase_request(
    'GET',
    'projects?select=id,title,category,description,technologies,project_link,image_url,created_at&order=created_at.desc'
);

if ($response['status'] >= 400) {
    json_response([
        'success' => false,
        'message' => 'Could not load projects from Supabase.',
        'details' => $response['data']
    ], $response['status']);
}

json_response([
    'success' => true,
    'projects' => $response['data']
]);
