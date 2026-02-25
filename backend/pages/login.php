<?php
// CareBox - Login Page with your design
require_once __DIR__ . '/../includes/functions.php';

// If already logged in, redirect
if (isDonorLoggedIn()) {
    header("Location: donor_dashboard.php");
    exit();
}
if (isAdminLoggedIn()) {
    header("Location: admin_dashboard.php");
    exit();
}

$role = isset($_GET['role']) ? $_GET['role'] : 'donor';
$role = in_array($role, ['donor', 'admin']) ? $role : 'donor';
$roleText = $role === 'admin' ? 'Admin' : 'Donor';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? cleanInput($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if ($role === 'admin') {
        $memberId = isset($_POST['member_id']) ? cleanInput($_POST['member_id']) : '';
        $result = loginAdmin($memberId, $email, $password);
        if ($result['success']) {
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = $result['message'];
        }
    } else {
        $result = loginDonor($email, $password);
        if ($result['success']) {
            header("Location: donor_dashboard.php");
            exit();
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CareBox</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Error Message -->
    <?php if ($error): ?>
    <div id="error-box" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-2xl shadow-2xl max-w-sm w-full">
            <h3 class="text-xl font-bold mb-3 text-red-600">Login Failed</h3>
            <p class="text-gray-700 mb-4"><?php echo htmlspecialchars($error); ?></p>
            <button onclick="document.getElementById('error-box').style.display='none'" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">Okay</button>
        </div>
    </div>
    <?php endif; ?>

    <div class="flex items-center justify-center w-full min-h-screen p-4 sm:p-6">
        <div class="flex flex-col md:flex-row max-w-4xl w-full bg-white rounded-xl shadow-2xl overflow-hidden">
            <!-- Login Form -->
            <div class="w-full md:w-1/2 p-8 sm:p-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Login as <?php echo $roleText; ?></h2>
                <p class="text-gray-600 mb-8">Enter your credentials to continue your journey.</p>
                
                <form method="POST" action="">
                    <?php if ($role === 'admin'): ?>
                    <div class="mb-4">
                        <label for="member_id" class="block text-sm font-medium text-gray-700">Member ID (8 alphanumeric characters)</label>
                        <input type="text" id="member_id" name="member_id" placeholder="Enter your 8-digit Member ID" required maxlength="8"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email (e.g., user@email.com)" required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                    
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                    
                    <div class="mt-2 text-right">
                        <a href="#" onclick="alert('Contact support to reset your password')" class="text-xs text-blue-600 hover:text-blue-800 transition">Forgot Password?</a>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg shadow-md hover:bg-blue-700 transition transform hover:scale-[1.01] mb-4">
                        Login
                    </button>
                    
                    <button type="button" onclick="alert('Google login coming soon!')" class="w-full flex items-center justify-center space-x-2 border border-gray-300 bg-white text-gray-700 font-semibold py-3 rounded-lg shadow-sm hover:bg-gray-50 transition mb-4">
                        <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M21.78 11.23c0-.7-.06-1.37-.2-2.01h-9.58v3.6h5.4c-.23 1.13-.88 2.08-1.78 2.76v2.33h2.99c1.74-1.59 2.74-3.92 2.74-6.68z"/><path fill="#34A853" d="M12 22c3.2 0 5.86-1.07 7.82-2.91l-2.99-2.33c-.83.56-1.92.89-3.73.89-2.87 0-5.3-1.93-6.18-4.5h-3.13v2.41c1.55 3.09 5.06 5.14 9.22 5.14z"/><path fill="#FBBC05" d="M5.82 14.5c-.24-.68-.37-1.4-.37-2.15s.13-1.47.37-2.15v-2.41h-3.13c-.63 1.25-.99 2.65-.99 4.56s.36 3.31.99 4.56l3.13-2.41z"/><path fill="#EA4335" d="M12 5.04c1.77 0 3.3.62 4.54 1.78l2.66-2.66C17.86 2.12 15.2 1 12 1 7.84 1 4.33 3.05 2.78 6.14l3.13 2.41c.88-2.57 3.31-4.5 6.18-4.5z"/></svg>
                        <span>Continue with Google</span>
                    </button>
                    
                    <div class="text-center mt-6 space-y-2">
                        <a href="index.php" class="text-sm text-blue-600 hover:text-blue-800 transition block">← Back to Home</a>
                        <a href="login.php?role=<?php echo $role === 'admin' ? 'donor' : 'admin'; ?>" class="text-sm text-gray-600 hover:text-gray-800 transition block">
                            Login as <?php echo $role === 'admin' ? 'Donor' : 'Admin'; ?> instead
                        </a>
                    </div>
                </form>
            </div>
            
            <!-- Right Side Image -->
            <div class="hidden md:block md:w-1/2 bg-pink-50 p-8 flex flex-col items-center justify-center text-center">
                <h3 class="text-2xl font-bold text-pink-800 mb-4">Welcome to CareBox</h3>
                <p class="text-pink-600 mb-6">Login to continue making an impact. Your care clicks here!</p>
                <img src="https://placehold.co/400x500/FEE2E2/9F1239?text=Care+That+Clicks" alt="CareBox Welcome" class="max-h-full w-auto object-contain rounded-xl shadow-lg">
            </div>
        </div>
    </div>
</body>
</html>
