-- CareBox Database Schema
-- Run this in phpMyAdmin SQL tab

-- Drop tables if they exist (for fresh start)
DROP TABLE IF EXISTS donations;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS campaigns;
DROP TABLE IF EXISTS donors;
DROP TABLE IF EXISTS admins;

-- ============================================
-- DONORS TABLE
-- ============================================
CREATE TABLE donors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- ADMINS TABLE
-- ============================================
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    member_id VARCHAR(8) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- CAMPAIGNS TABLE
-- ============================================
CREATE TABLE campaigns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    goal_amount DECIMAL(12,2) DEFAULT 0,
    raised_amount DECIMAL(12,2) DEFAULT 0,
    donors_count INT DEFAULT 0,
    image VARCHAR(255),
    status ENUM('active', 'completed', 'pending', 'critical') DEFAULT 'active',
    days_left INT DEFAULT 30,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- PRODUCTS/KITS TABLE
-- ============================================
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    obtained INT DEFAULT 0,
    required INT DEFAULT 0,
    category VARCHAR(50),
    campaign_id INT,
    FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE SET NULL
);

-- ============================================
-- DONATIONS TABLE
-- ============================================
CREATE TABLE donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT NOT NULL,
    campaign_id INT,
    amount DECIMAL(10,2) NOT NULL,
    donation_type ENUM('general', 'micro', 'urgent', 'campaign') DEFAULT 'general',
    item_name VARCHAR(100),
    status ENUM('completed', 'in_progress', 'pending', 'cancelled') DEFAULT 'pending',
    feedback TEXT,
    feedback_available BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (donor_id) REFERENCES donors(id) ON DELETE CASCADE,
    FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE SET NULL
);

-- ============================================
-- RECENT DONATIONS TABLE (Public Display)
-- ============================================
CREATE TABLE recent_donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_name VARCHAR(100) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    product_name VARCHAR(100),
    campaign_name VARCHAR(200),
    donation_time VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- INSERT SAMPLE DATA
-- ============================================

-- Insert default admin (password will be hashed as 'admin123')
-- You should change this password after first login
INSERT INTO admins (name, email, password, phone, member_id) VALUES 
('System Admin', 'admin@carebox.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543210', 'ADMIN001');

-- Insert sample campaigns
INSERT INTO campaigns (title, description, goal_amount, raised_amount, donors_count, image, status, days_left) VALUES
('Support Education for 500 Underprivileged Children', 'Provide quality education resources to underprivileged children across rural communities', 500000, 250000, 100, 'education.jpg', 'active', 7),
('Life-Saving Supplies for Rural Medical Camps', 'Provide life-saving supplies and essential medicines to remote communities', 300000, 50000, 45, 'medical.jpg', 'active', 14),
('Emergency Relief for Chennai Flooding Victims', 'Immediate assistance to families affected by devastating floods', 750000, 650000, 520, 'critical', 2),
('Feed the Needy', 'Help feed those in need - provide a meal today!', 2500000, 1590523, 1156, 'feed.jpg', 'active', 30),
('Support a Child', 'Help orphaned children find love and care', 6000000, 2890450, 2156, 'orphan.jpg', 'active', 45),
('Save Aravalli', 'Support environmental initiatives for clean air', 5000000, 2245780, 892, 'aravalli.jpg', 'active', 60);

-- Insert sample products for campaigns
INSERT INTO products (name, description, price, image, obtained, required, category, campaign_id) VALUES
('School Supply Kit', 'Notebooks, pens, pencils, backpack, and basic learning materials', 800, 'school_kit.jpg', 320, 500, 'education', 1),
('Digital Learning Tablet', 'Preloaded with educational content for remote learning access', 4500, 'tablet.jpg', 110, 200, 'education', 1),
('Medical Emergency Kit', 'Basic medical supplies for rural camps', 2500, 'medical_kit.jpg', 180, 300, 'medical', 2),
('Flood Relief Kit', 'Emergency supplies for flood-affected families', 1200, 'relief_kit.jpg', 650, 1000, 'relief', 3),
('Rice 10Kg', 'High-quality rice that can feed a family for a week', 575, 'rice.jpg', 850, 1500, 'food', 4),
('Education Support Kit', 'Tuition fees, books, tutoring for one child', 1500, 'edu_kit.jpg', 2100, 3000, 'education', 5);

-- Insert sample donor (for testing)
-- Password: donor123
INSERT INTO donors (name, email, password, phone) VALUES 
('Test Donor', 'donor@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543210');

-- Insert sample donations
INSERT INTO donations (donor_id, campaign_id, amount, donation_type, item_name, status, feedback, feedback_available) VALUES
(1, 1, 2500, 'campaign', 'Education Support', 'completed', 'Pencils, notebooks, and school bags were successfully delivered to the local school on 2024-10-17. The children were thrilled!', TRUE),
(1, 3, 10000, 'urgent', 'Flood Relief', 'completed', 'Funds used to purchase essential medicines and clean water. Your impact helped 12 families immediately.', TRUE),
(1, NULL, 50, 'micro', 'Set of Crayons', 'completed', 'Crayons used in an art therapy session. The recipient drew a picture of their family!', TRUE);

-- Insert sample recent donations (for public display)
INSERT INTO recent_donations (donor_name, amount, product_name, campaign_name, donation_time) VALUES
('Hope Foundation', 50000, 'Education Support Kit', 'Support a Child', '5 hours ago'),
('Amit Verma', 7500, 'Nutrition & Food Kit', 'Support a Child', '12 hours ago'),
('Sneha Reddy', 4500, 'School Supplies Kit', 'Support a Child', '1 day ago'),
('Anonymous', 25000, 'Healthcare & Medical Kit', 'Support a Child', '2 days ago'),
('Priya Sharma', 2500, 'Rice 10Kg', 'Feed the Needy', '1 hour ago'),
('Rohan Mehta', 1500, 'Fruit Kit', 'Feed the Needy', '2 hours ago'),
('Green Earth Foundation', 25000, 'Tree Sapling Kit', 'Save Aravalli', '2 hours ago'),
('Rajesh Mehta', 5000, 'Forest Guard Kit', 'Save Aravalli', '5 hours ago');
