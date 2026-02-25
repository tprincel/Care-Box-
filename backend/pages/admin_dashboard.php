<?php
// Admin Dashboard Page
require_once __DIR__ . '/../includes/functions.php';

// Check if admin is logged in
requireAdminLogin();

$adminName = $_SESSION['admin_name'];
$adminEmail = $_SESSION['admin_email'];
$adminMemberId = $_SESSION['admin_member_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CareBox</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-red-700">CareBox Admin</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Admin: <strong><?php echo htmlspecialchars($adminName); ?></strong></span>
                    <button onclick="logout()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Admin Panel</h2>
            <p class="text-gray-600 mt-2">Manage campaigns, donors, and donations</p>
        </div>

        <!-- Admin Info -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Admin Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Name</p>
                    <p class="font-semibold"><?php echo htmlspecialchars($adminName); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="font-semibold"><?php echo htmlspecialchars($adminEmail); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Member ID</p>
                    <p class="font-semibold text-red-600"><?php echo htmlspecialchars($adminMemberId); ?></p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-yellow-100 rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                <p class="text-sm font-medium text-yellow-700">Pending Verification</p>
                <p class="text-3xl font-bold text-yellow-900 mt-1">14</p>
            </div>
            <div class="bg-green-100 rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <p class="text-sm font-medium text-green-700">Today's Donations</p>
                <p class="text-3xl font-bold text-green-900 mt-1">₹45,210</p>
            </div>
            <div class="bg-red-100 rounded-xl shadow-lg p-6 border-l-4 border-red-500">
                <p class="text-sm font-medium text-red-700">Critical Campaigns</p>
                <p class="text-3xl font-bold text-red-900 mt-1">2</p>
            </div>
            <div class="bg-blue-100 rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <p class="text-sm font-medium text-blue-700">Active Campaigns</p>
                <p class="text-3xl font-bold text-blue-900 mt-1" id="activeCampaigns">0</p>
            </div>
        </div>

        <!-- Campaigns Management -->
        <h3 class="text-2xl font-bold text-gray-800 mb-4">Campaign Management</h3>
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Campaign</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Goal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Raised</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="campaignsTable" class="divide-y divide-gray-200">
                        <!-- Campaigns will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Admin Actions -->
        <h3 class="text-2xl font-bold text-gray-800 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h4 class="font-bold text-gray-800 mb-2">➕ Launch New Campaign</h4>
                <p class="text-sm text-gray-600 mb-4">Create a new urgent campaign</p>
                <button onclick="alert('Create campaign functionality coming soon')" 
                    class="w-full bg-red-600 text-white font-semibold py-2 rounded-lg hover:bg-red-700 transition">
                    Start Now
                </button>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h4 class="font-bold text-gray-800 mb-2">👤 Manage Donors</h4>
                <p class="text-sm text-gray-600 mb-4">View and manage donor database</p>
                <button onclick="alert('Donor management coming soon')" 
                    class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition">
                    Go to List
                </button>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h4 class="font-bold text-gray-800 mb-2">💰 Financial Reports</h4>
                <p class="text-sm text-gray-600 mb-4">View donation reports and audits</p>
                <button onclick="alert('Reports coming soon')" 
                    class="w-full bg-green-600 text-white font-semibold py-2 rounded-lg hover:bg-green-700 transition">
                    View Data
                </button>
            </div>
        </div>
    </div>

    <script>
        // Load campaigns on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCampaigns();
        });

        // Load campaigns from API
        async function loadCampaigns() {
            try {
                const response = await fetch('../api/get_campaigns.php');
                const data = await response.json();

                if (data.success) {
                    displayCampaigns(data.data);
                    document.getElementById('activeCampaigns').textContent = data.data.length;
                }
            } catch (error) {
                console.error('Error loading campaigns:', error);
            }
        }

        // Display campaigns in table
        function displayCampaigns(campaigns) {
            const tbody = document.getElementById('campaignsTable');
            tbody.innerHTML = '';

            campaigns.forEach(campaign => {
                const row = document.createElement('tr');
                
                const progressPercent = Math.min(100, (campaign.raised_amount / campaign.goal_amount) * 100);
                
                const statusColors = {
                    'active': 'bg-green-100 text-green-800',
                    'completed': 'bg-blue-100 text-blue-800',
                    'pending': 'bg-yellow-100 text-yellow-800',
                    'critical': 'bg-red-100 text-red-800'
                };
                
                row.innerHTML = `
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">${campaign.title}</div>
                        <div class="text-sm text-gray-500">${campaign.description.substring(0, 50)}...</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">₹${parseFloat(campaign.goal_amount).toLocaleString()}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-green-600">₹${parseFloat(campaign.raised_amount).toLocaleString()}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="w-full bg-gray-200 rounded-full h-2 w-24">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: ${progressPercent}%"></div>
                        </div>
                        <span class="text-xs text-gray-600">${progressPercent.toFixed(0)}%</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusColors[campaign.status] || 'bg-gray-100 text-gray-800'}">
                            ${campaign.status.toUpperCase()}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="editCampaign(${campaign.id})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                        <button onclick="viewCampaign(${campaign.id})" class="text-green-600 hover:text-green-900">View</button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        // Edit campaign
        function editCampaign(campaignId) {
            alert('Edit campaign ' + campaignId + ' - Coming soon');
        }

        // View campaign
        function viewCampaign(campaignId) {
            alert('View campaign ' + campaignId + ' - Coming soon');
        }

        // Logout function
        async function logout() {
            try {
                const response = await fetch('../api/logout.php');
                const data = await response.json();
                
                if (data.success) {
                    window.location.href = 'login.php';
                }
            } catch (error) {
                console.error('Error logging out:', error);
            }
        }
    </script>
</body>
</html>
