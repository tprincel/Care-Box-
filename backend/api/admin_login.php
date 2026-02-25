<?php
// Admin Login API
require_once __DIR__ . '/../includes/functions.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Invalid request method');
}

// Get POST data
$memberId = isset($_POST['member_id']) ? $_POST['member_id'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Validate inputs
if (empty($memberId) || empty($email) || empty($password)) {
    sendJsonResponse(false, 'Member ID, email, and password are required');
}

if (!isValidMemberId($memberId)) {
    sendJsonResponse(false, 'Member ID must be exactly 8 alphanumeric characters');
}

if (!isValidEmail($email)) {
    sendJsonResponse(false, 'Invalid email format');
}

// Attempt login
$result = loginAdmin($memberId, $email, $password);

if ($result['success']) {
    sendJsonResponse(true, $result['message'], [
        'user_type' => 'admin',
        'name' => $_SESSION['admin_name'],
        'email' => $_SESSION['admin_email'],
        'member_id' => $_SESSION['admin_member_id']
    ]);
} else {
    sendJsonResponse(false, $result['message']);
}
?>
