<?php
// Save Donation API
require_once __DIR__ . '/../includes/functions.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Invalid request method');
}

// Get POST data
$donorName = isset($_POST['donor_name']) ? cleanInput($_POST['donor_name']) : '';
$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
$productName = isset($_POST['product_name']) ? cleanInput($_POST['product_name']) : '';
$campaignName = isset($_POST['campaign_name']) ? cleanInput($_POST['campaign_name']) : '';
$donorId = isset($_POST['donor_id']) ? intval($_POST['donor_id']) : null;

// Validate inputs
if (empty($donorName) || $amount <= 0) {
    sendJsonResponse(false, 'Donor name and amount are required');
}

$conn = getDBConnection();

// If donor_id is provided, save to donations table
if ($donorId) {
    $stmt = $conn->prepare("INSERT INTO donations (donor_id, amount, item_name, status, donation_type) VALUES (?, ?, ?, 'completed', 'general')");
    $stmt->bind_param("ids", $donorId, $amount, $productName);
    $stmt->execute();
    $stmt->close();
}

// Always save to recent_donations for public display
$donationTime = "Just now";
$stmt2 = $conn->prepare("INSERT INTO recent_donations (donor_name, amount, product_name, campaign_name, donation_time) VALUES (?, ?, ?, ?, ?)");
$stmt2->bind_param("sdsss", $donorName, $amount, $productName, $campaignName, $donationTime);

if ($stmt2->execute()) {
    $stmt2->close();
    closeDBConnection($conn);
    sendJsonResponse(true, 'Donation saved successfully');
} else {
    $stmt2->close();
    closeDBConnection($conn);
    sendJsonResponse(false, 'Failed to save donation: ' . $conn->error);
}
?>
