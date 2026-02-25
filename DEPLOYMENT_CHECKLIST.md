# CareBox Deployment Checklist

## Before Pushing to Git

- [x] Main entry point is `index.html`
- [x] Database config has instructions for InfinityFree
- [x] `.htaccess` file created for server configuration
- [x] `.gitignore` file created
- [x] Test files removed
- [x] README.md created with instructions

## Git Setup

1. Initialize Git repository:
   ```bash
   git init
   ```

2. Add all files:
   ```bash
   git add .
   ```

3. Commit:
   ```bash
   git commit -m "Initial commit - CareBox charity donation platform"
   ```

4. Add remote (replace with your GitHub repo):
   ```bash
   git remote add origin https://github.com/yourusername/carebox.git
   ```

5. Push:
   ```bash
   git push -u origin main
   ```

## InfinityFree Deployment Steps

### 1. Sign Up
- Go to https://infinityfree.net
- Create free account
- Create new hosting account

### 2. Create Database
- Go to Control Panel → MySQL Databases
- Create new database
- Save these details:
  - Database Host (e.g., `sql123.epizy.com`)
  - Database Name (e.g., `epiz_12345678_carebox`)
  - Database Username (e.g., `epiz_12345678`)
  - Database Password

### 3. Update Config File
Edit `backend/config/database.php`:
```php
define('DB_HOST', 'sql123.epizy.com');
define('DB_USERNAME', 'epiz_12345678');
define('DB_PASSWORD', 'your_actual_password');
define('DB_NAME', 'epiz_12345678_carebox');
```

### 4. Upload Files
Option A: File Manager
- Go to Control Panel → Online File Manager
- Navigate to `htdocs/`
- Upload all files (zip and extract)

Option B: FTP
- Use FTP client (FileZilla)
- Host: `ftpupload.net`
- Username: your InfinityFree username
- Password: your InfinityFree password
- Upload to `htdocs/` folder

### 5. Import Database
- Go to Control Panel → phpMyAdmin
- Select your database
- Import tab → Choose File → `database/carebox_db.sql`
- Click Go

### 6. Test Website
- Visit: `http://yourdomain.epizy.com`
- Test login with admin credentials
- Test donor registration
- Test donations

## Troubleshooting

### "Could not connect to server" error
- Check database credentials in `backend/config/database.php`
- Make sure database is created and imported

### "404 Not Found" error
- Check that `index.html` is in the root folder
- Check `.htaccess` file is uploaded

### "Access denied" error
- Check database username/password
- Make sure database user has all privileges

## Post-Deployment

- [ ] Change default admin password
- [ ] Add your own NGO admins
- [ ] Update campaign information
- [ ] Test all donation flows
- [ ] Set up custom domain (optional)
