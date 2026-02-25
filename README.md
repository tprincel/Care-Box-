# CareBox - Charity Donation Platform

A PHP-based charity donation website with donor and admin dashboards.

## Features

- **User Roles**: Donor and Admin login systems
- **Campaigns**: Support a Child, Feed the Needy, Save Aravalli, Make a Difference
- **Donation Tracking**: Recent donations display and donor history
- **Admin Dashboard**: Manage campaigns and view donations
- **Responsive Design**: Works on all devices

## Local Development (XAMPP)

1. Install XAMPP
2. Copy project to `C:\xampp\htdocs\carebox\`
3. Import `database/carebox_db.sql` to phpMyAdmin
4. Access at `http://localhost/carebox/`

## InfinityFree Deployment

### Step 1: Database Setup

1. Sign up at [InfinityFree](https://infinityfree.net)
2. Create a new hosting account
3. Go to "MySQL Databases" in control panel
4. Create a new database
5. Note down:
   - Database Name
   - Database Username
   - Database Password
   - Database Host (usually `sqlXXX.epizy.com`)

### Step 2: Update Database Config

Edit `backend/config/database.php`:

```php
define('DB_HOST', 'sqlXXX.epizy.com');     // Your InfinityFree DB host
define('DB_USERNAME', 'epiz_XXX');         // Your InfinityFree DB username
define('DB_PASSWORD', 'your_password');    // Your InfinityFree DB password
define('DB_NAME', 'epiz_XXX_carebox');     // Your InfinityFree DB name
```

### Step 3: Import Database

1. Go to InfinityFree phpMyAdmin
2. Select your database
3. Import `database/carebox_db.sql`

### Step 4: Upload Files

1. Go to "Online File Manager" in InfinityFree control panel
2. Navigate to `htdocs/`
3. Upload all project files (or use FTP)
4. Make sure `index.html` is in the root

### Step 5: Access Website

Your website will be at: `http://yourdomain.epizy.com`

## Default Login Credentials

### Admin
- Member ID: `ADMIN001`
- Email: `admin@carebox.com`
- Password: `admin123`

### Test Donor
- Email: `donor@test.com`
- Password: `donor123`

## File Structure

```
carebox2.0/
├── index.html              # Main landing page (login)
├── admindashboard.html     # Admin dashboard
├── donerdashboard.html     # Donor dashboard
├── donatebutton.html       # Donation page
├── donatenowbutton.html    # Quick donation
├── supportachild.html      # Campaign page
├── feedtheneedy.html       # Campaign page
├── saveavali.html          # Campaign page
├── makeadifference.html    # Campaign page
├── backend/
│   ├── api/               # API endpoints
│   ├── config/            # Database config
│   └── includes/          # PHP functions
├── database/
│   └── carebox_db.sql     # Database schema
└── .htaccess              # Server configuration
```

## Adding New Admins (Verified NGOs)

Only system owner can add admins. Use this SQL in phpMyAdmin:

```sql
INSERT INTO admins (name, email, password, phone, member_id) VALUES 
('NGO Name', 'ngo@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543210', 'UNIQUEID');
```

Default password will be `admin123`.

## Technologies Used

- HTML5, CSS3, JavaScript
- Tailwind CSS (CDN)
- PHP 8.x
- MySQL/MariaDB
- XAMPP (local development)

## License

This project is for educational purposes.
