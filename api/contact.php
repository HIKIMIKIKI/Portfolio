<?php
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response([
        'success' => false,
        'message' => 'Invalid request method.'
    ], 405);
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$reason = trim($_POST['reason'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if (strlen($name) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL) || $reason === '' || strlen($subject) < 3 || strlen($message) < 10) {
    json_response([
        'success' => false,
        'message' => 'Please fill in all fields correctly.'
    ], 422);
}

$response = supabase_request('POST', 'messages', [[
    'name' => $name,
    'email' => $email,
    'reason' => $reason,
    'subject' => $subject,
    'message' => $message
]]);

if ($response['status'] >= 400) {
    json_response([
        'success' => false,
        'message' => 'Could not save your message to Supabase.',
        'details' => $response['data']
    ], $response['status']);
}

json_response([
    'success' => true,
    'message' => 'Your message was saved successfully.'
]);
