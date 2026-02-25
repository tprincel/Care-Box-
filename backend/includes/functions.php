<?php
// Common Functions File
session_start();

// Include database configuration
require_once __DIR__ . '/../config/database.php';

// ============================================
// SECURITY FUNCTIONS
// ============================================

// Clean user input to prevent SQL injection and XSS
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Hash password securely
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Generate unique member ID for admins (8 alphanumeric characters)
function generateMemberId() {
    return strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8));
}

// ============================================
// VALIDATION FUNCTIONS
// ============================================

// Validate email format
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Validate phone number (10 digits)
function isValidPhone($phone) {
    return preg_match('/^[0-9]{10}$/', $phone);
}

// Validate member ID (8 alphanumeric characters)
function isValidMemberId($memberId) {
    return preg_match('/^[a-zA-Z0-9]{8}$/', $memberId);
}

// Check if user is logged in as donor
function isDonorLoggedIn() {
    return isset($_SESSION['donor_id']) && $_SESSION['user_type'] === 'donor';
}

// Check if user is logged in as admin
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && $_SESSION['user_type'] === 'admin';
}

// Redirect to login page if not logged in
function requireDonorLogin() {
    if (!isDonorLoggedIn()) {
        header("Location: ../pages/login.php");
        exit();
    }
}

// Redirect to login page if not admin
function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header("Location: ../pages/login.php");
        exit();
    }
}

// ============================================
// RESPONSE FUNCTIONS
// ============================================

// Send JSON response (for API calls)
function sendJsonResponse($success, $message, $data = null) {
    header('Content-Type: application/json');
    $response = [
        'success' => $success,
        'message' => $message
    ];
    if ($data !== null) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit();
}

// Set flash message
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

// Get and clear flash message
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

// ============================================
// DONOR FUNCTIONS
// ============================================

// Register new donor
function registerDonor($name, $email, $password, $phone) {
    $conn = getDBConnection();
    
    // Clean inputs
    $name = cleanInput($name);
    $email = cleanInput($email);
    $phone = cleanInput($phone);
    
    // Check if email already exists
    $checkStmt = $conn->prepare("SELECT id FROM donors WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();
    
    if ($checkStmt->num_rows > 0) {
        $checkStmt->close();
        closeDBConnection($conn);
        return ['success' => false, 'message' => 'Email already registered'];
    }
    $checkStmt->close();
    
    // Hash password
    $hashedPassword = hashPassword($password);
    
    // Insert donor
    $stmt = $conn->prepare("INSERT INTO donors (name, email, password, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashedPassword, $phone);
    
    if ($stmt->execute()) {
        $donorId = $stmt->insert_id;
        $stmt->close();
        closeDBConnection($conn);
        return ['success' => true, 'message' => 'Registration successful', 'donor_id' => $donorId];
    } else {
        $stmt->close();
        closeDBConnection($conn);
        return ['success' => false, 'message' => 'Registration failed: ' . $conn->error];
    }
}

// Login donor
function loginDonor($email, $password) {
    $conn = getDBConnection();
    
    $email = cleanInput($email);
    
    $stmt = $conn->prepare("SELECT id, name, email, password, phone FROM donors WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $donor = $result->fetch_assoc();
        if (verifyPassword($password, $donor['password'])) {
            // Set session variables
            $_SESSION['donor_id'] = $donor['id'];
            $_SESSION['donor_name'] = $donor['name'];
            $_SESSION['donor_email'] = $donor['email'];
            $_SESSION['donor_phone'] = $donor['phone'];
            $_SESSION['user_type'] = 'donor';
            
            $stmt->close();
            closeDBConnection($conn);
            return ['success' => true, 'message' => 'Login successful'];
        }
    }
    
    $stmt->close();
    closeDBConnection($conn);
    return ['success' => false, 'message' => 'Invalid email or password'];
}

// Get donor details
function getDonorDetails($donorId) {
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("SELECT id, name, email, phone, created_at FROM donors WHERE id = ?");
    $stmt->bind_param("i", $donorId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $donor = $result->fetch_assoc();
        $stmt->close();
        closeDBConnection($conn);
        return $donor;
    }
    
    $stmt->close();
    closeDBConnection($conn);
    return null;
}

// ============================================
// ADMIN FUNCTIONS
// ============================================

// Register new admin
function registerAdmin($name, $email, $password, $phone, $memberId) {
    $conn = getDBConnection();
    
    // Clean inputs
    $name = cleanInput($name);
    $email = cleanInput($email);
    $phone = cleanInput($phone);
    $memberId = cleanInput($memberId);
    
    // Check if email already exists
    $checkStmt = $conn->prepare("SELECT id FROM admins WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();
    
    if ($checkStmt->num_rows > 0) {
        $checkStmt->close();
        closeDBConnection($conn);
        return ['success' => false, 'message' => 'Email already registered'];
    }
    $checkStmt->close();
    
    // Check if member ID already exists
    $checkStmt2 = $conn->prepare("SELECT id FROM admins WHERE member_id = ?");
    $checkStmt2->bind_param("s", $memberId);
    $checkStmt2->execute();
    $checkStmt2->store_result();
    
    if ($checkStmt2->num_rows > 0) {
        $checkStmt2->close();
        closeDBConnection($conn);
        return ['success' => false, 'message' => 'Member ID already exists'];
    }
    $checkStmt2->close();
    
    // Hash password
    $hashedPassword = hashPassword($password);
    
    // Insert admin
    $stmt = $conn->prepare("INSERT INTO admins (name, email, password, phone, member_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $hashedPassword, $phone, $memberId);
    
    if ($stmt->execute()) {
        $adminId = $stmt->insert_id;
        $stmt->close();
        closeDBConnection($conn);
        return ['success' => true, 'message' => 'Admin registration successful', 'admin_id' => $adminId];
    } else {
        $stmt->close();
        closeDBConnection($conn);
        return ['success' => false, 'message' => 'Registration failed: ' . $conn->error];
    }
}

// Login admin
function loginAdmin($memberId, $email, $password) {
    $conn = getDBConnection();
    
    $memberId = cleanInput($memberId);
    $email = cleanInput($email);
    
    $stmt = $conn->prepare("SELECT id, name, email, password, phone, member_id FROM admins WHERE member_id = ? AND email = ?");
    $stmt->bind_param("ss", $memberId, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (verifyPassword($password, $admin['password'])) {
            // Set session variables
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_phone'] = $admin['phone'];
            $_SESSION['admin_member_id'] = $admin['member_id'];
            $_SESSION['user_type'] = 'admin';
            
            $stmt->close();
            closeDBConnection($conn);
            return ['success' => true, 'message' => 'Admin login successful'];
        }
    }
    
    $stmt->close();
    closeDBConnection($conn);
    return ['success' => false, 'message' => 'Invalid credentials'];
}

// ============================================
// CAMPAIGN FUNCTIONS
// ============================================

// Get all campaigns
function getAllCampaigns() {
    $conn = getDBConnection();
    
    $result = $conn->query("SELECT * FROM campaigns ORDER BY created_at DESC");
    $campaigns = [];
    
    while ($row = $result->fetch_assoc()) {
        $campaigns[] = $row;
    }
    
    closeDBConnection($conn);
    return $campaigns;
}

// Get campaign by ID
function getCampaignById($campaignId) {
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("SELECT * FROM campaigns WHERE id = ?");
    $stmt->bind_param("i", $campaignId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $campaign = $result->fetch_assoc();
        $stmt->close();
        closeDBConnection($conn);
        return $campaign;
    }
    
    $stmt->close();
    closeDBConnection($conn);
    return null;
}

// ============================================
// DONATION FUNCTIONS
// ============================================

// Get donor's donation history
function getDonorDonations($donorId) {
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("
        SELECT d.*, c.title as campaign_title 
        FROM donations d 
        LEFT JOIN campaigns c ON d.campaign_id = c.id 
        WHERE d.donor_id = ? 
        ORDER BY d.created_at DESC
    ");
    $stmt->bind_param("i", $donorId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $donations = [];
    while ($row = $result->fetch_assoc()) {
        $donations[] = $row;
    }
    
    $stmt->close();
    closeDBConnection($conn);
    return $donations;
}

// Get total donations by donor
function getTotalDonationsByDonor($donorId) {
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("SELECT SUM(amount) as total FROM donations WHERE donor_id = ? AND status = 'completed'");
    $stmt->bind_param("i", $donorId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $stmt->close();
    closeDBConnection($conn);
    return $row['total'] ?? 0;
}

// Get donor stats (total donated and count)
function getDonorStats($donorId) {
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("SELECT SUM(amount) as total_donated, COUNT(*) as total_donations FROM donations WHERE donor_id = ?");
    $stmt->bind_param("i", $donorId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $stmt->close();
    closeDBConnection($conn);
    return [
        'total_donated' => $row['total_donated'] ?? 0,
        'total_donations' => $row['total_donations'] ?? 0
    ];
}

// Get donor donation history (alias for getDonorDonations)
function getDonorDonationHistory($donorId) {
    return getDonorDonations($donorId);
}

// ============================================
// RECENT DONATIONS FUNCTIONS (Public Display)
// ============================================

// Get all recent donations for public display
function getRecentDonations($limit = 10) {
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("SELECT * FROM recent_donations ORDER BY created_at DESC LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $donations = [];
    while ($row = $result->fetch_assoc()) {
        $donations[] = $row;
    }
    
    $stmt->close();
    closeDBConnection($conn);
    return $donations;
}

// Add a new recent donation (called when someone donates)
function addRecentDonation($donorName, $amount, $productName, $campaignName) {
    $conn = getDBConnection();
    
    // Clean inputs
    $donorName = cleanInput($donorName);
    $productName = cleanInput($productName);
    $campaignName = cleanInput($campaignName);
    
    // Set donation time as "Just now"
    $donationTime = "Just now";
    
    $stmt = $conn->prepare("INSERT INTO recent_donations (donor_name, amount, product_name, campaign_name, donation_time) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsss", $donorName, $amount, $productName, $campaignName, $donationTime);
    
    $success = $stmt->execute();
    $stmt->close();
    closeDBConnection($conn);
    
    return $success;
}

// Update donation times (convert "Just now" to "X minutes/hours ago")
function updateDonationTimes() {
    $conn = getDBConnection();
    
    // Update donations older than 1 hour
    $conn->query("UPDATE recent_donations SET donation_time = '1 hour ago' WHERE donation_time = 'Just now' AND created_at < DATE_SUB(NOW(), INTERVAL 1 HOUR)");
    
    closeDBConnection($conn);
}

// Logout function
function logout() {
    session_destroy();
    header("Location: ../pages/login.php");
    exit();
}
?>
