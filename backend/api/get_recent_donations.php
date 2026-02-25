<?php
// Get Recent Donations API (Public - no login required)
require_once __DIR__ . '/../includes/functions.php';

// Get limit from request (default 10)
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
if ($limit > 50) $limit = 50; // Max 50 records

// Get recent donations
$donations = getRecentDonations($limit);

sendJsonResponse(true, 'Recent donations retrieved successfully', $donations);
?>
