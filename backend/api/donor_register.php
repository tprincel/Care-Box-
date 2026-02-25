<?php
// Donor Registration API
require_once __DIR__ . '/../includes/functions.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Invalid request method');
}

// Get POST data
$name = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';

// Validate inputs
if (empty($name) || empty($email) || empty($password) || empty($phone)) {
    sendJsonResponse(false, 'All fields are required');
}

if (!isValidEmail($email)) {
    sendJsonResponse(false, 'Invalid email format');
}

if (!isValidPhone($phone)) {
    sendJsonResponse(false, 'Phone number must be exactly 10 digits');
}

if (strlen($password) < 6) {
    sendJsonResponse(false, 'Password must be at least 6 characters long');
}

// Register donor
$result = registerDonor($name, $email, $password, $phone);

if ($result['success']) {
    sendJsonResponse(true, $result['message']);
} else {
    sendJsonResponse(false, $result['message']);
}
?>
