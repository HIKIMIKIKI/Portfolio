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

$connection = get_db_connection();
$statement = $connection->prepare('INSERT INTO messages (name, email, reason, subject, message) VALUES (?, ?, ?, ?, ?)');
$statement->bind_param('sssss', $name, $email, $reason, $subject, $message);
$statement->execute();

json_response([
    'success' => true,
    'message' => 'Your message was saved successfully.'
]);
