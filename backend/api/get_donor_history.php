<?php
// Get Donor Donation History API
require_once __DIR__ . '/../includes/functions.php';

// Check if donor is logged in
if (!isDonorLoggedIn()) {
    sendJsonResponse(false, 'Please login as donor to view history');
}

$donorId = $_SESSION['donor_id'];

// Get donation history
$donations = getDonorDonations($donorId);

// Get total donations
$totalDonated = getTotalDonationsByDonor($donorId);

sendJsonResponse(true, 'Donation history retrieved', [
    'donations' => $donations,
    'total_donated' => $totalDonated
]);
?>
