<?php
// Get All Campaigns API
require_once __DIR__ . '/../includes/functions.php';

// Get all campaigns
$campaigns = getAllCampaigns();

sendJsonResponse(true, 'Campaigns retrieved successfully', $campaigns);
?>
