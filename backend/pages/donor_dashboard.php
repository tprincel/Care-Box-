<?php
// CareBox - Donor Dashboard with your design
require_once __DIR__ . '/../includes/functions.php';

// Check if donor is logged in
if (!isDonorLoggedIn()) {
    header("Location: login.php?role=donor");
    exit();
}

$donorId = $_SESSION['donor_id'];
$donorName = $_SESSION['donor_name'];

// Get stats
$stats = getDonorStats($donorId);
$totalDonated = $stats['total_donated'] ?? 0;
$totalDonations = $stats['total_donations'] ?? 0;

// Get campaigns
$campaigns = getAllCampaigns();

// Get donation history
$donationHistory = getDonorDonationHistory($donorId);

// Format member since
$memberSince = date('F Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Dashboard - CareBox</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .mission-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }
        .mission-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border-color: #3b82f6;
        }
        .mission-image {
            height: 160px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .mission-image::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.4), transparent);
        }
        .mission-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px;
            color: white;
            font-weight: bold;
            z-index: 2;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-green-600">CareBox</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Welcome, <?php echo htmlspecialchars($donorName); ?></span>
                    <a href="logout.php" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Dashboard Title -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Donor Dashboard</h2>
            <p class="text-gray-600">Make a difference today!</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-green-500">
                <p class="text-gray-600 text-sm mb-1">Total Donated</p>
                <p class="text-3xl font-bold text-green-600">₹<?php echo number_format($totalDonated); ?></p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-blue-500">
                <p class="text-gray-600 text-sm mb-1">Donations Made</p>
                <p class="text-3xl font-bold text-blue-600"><?php echo $totalDonations; ?></p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-purple-500">
                <p class="text-gray-600 text-sm mb-1">Member Since</p>
                <p class="text-xl font-bold text-purple-600"><?php echo $memberSince; ?></p>
            </div>
        </div>

        <!-- Campaigns Section -->
        <div class="mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Contribute Now</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($campaigns as $campaign): 
                    $progress = $campaign['goal_amount'] > 0 ? ($campaign['raised_amount'] / $campaign['goal_amount']) * 100 : 0;
                    $progress = min(100, $progress);
                    $borderColor = $campaign['status'] === 'critical' ? 'border-red-500' : 'border-blue-500';
                ?>
                <div class="mission-card rounded-xl shadow-lg border-t-4 <?php echo $borderColor; ?> overflow-hidden">
                    <div class="mission-image" style="background-image: url('<?php echo htmlspecialchars($campaign['image'] ?? 'https://placehold.co/400x300'); ?>')">
                        <div class="mission-overlay">
                            <h3 class="text-white text-lg font-bold drop-shadow-md"><?php echo htmlspecialchars($campaign['title']); ?></h3>
                        </div>
                    </div>
                    <div class="p-4">
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2"><?php echo htmlspecialchars($campaign['description'] ?? ''); ?></p>
                        <div class="mb-3">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Raised: ₹<?php echo number_format($campaign['raised_amount']); ?></span>
                                <span class="text-gray-600"><?php echo round($progress); ?>%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo $progress; ?>%"></div>
                            </div>
                        </div>
                        <a href="../../donatenowbutton.html" class="block w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition text-center text-sm">Donate Now</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Donation History -->
        <div class="mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Your Donation History</h3>
            <?php if (empty($donationHistory)): ?>
                <div class="bg-white rounded-xl shadow-md p-8 text-center">
                    <p class="text-gray-600">You haven't made any donations yet.</p>
                    <a href="#campaigns" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">Start donating today!</a>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cause</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($donationHistory as $donation): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($donation['item_name'] ?? 'General Donation'); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-900">₹<?php echo number_format($donation['amount']); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-500"><?php echo date('M d, Y', strtotime($donation['created_at'])); ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $donation['status'] === 'completed' ? 'bg-green-100 text-green-800' : ($donation['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'); ?>">
                                            <?php echo ucfirst($donation['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
