<?php
// Donor Login API
require_once __DIR__ . '/../includes/functions.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Invalid request method');
}

// Get POST data
$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Validate inputs
if (empty($email) || empty($password)) {
    sendJsonResponse(false, 'Email and password are required');
}

if (!isValidEmail($email)) {
    sendJsonResponse(false, 'Invalid email format');
}

// Attempt login
$result = loginDonor($email, $password);

if ($result['success']) {
    sendJsonResponse(true, $result['message'], [
        'user_type' => 'donor',
        'name' => $_SESSION['donor_name'],
        'email' => $_SESSION['donor_email']
    ]);
} else {
    sendJsonResponse(false, $result['message']);
}
?>
