# Hostinger Deployment Guide - VISHVAVIRAT SECURITY

This guide will help you deploy your website to Hostinger and set up the lead capture system.

## Prerequisites

- Hostinger account with hosting plan
- Access to cPanel
- Domain configured (if you have one)

---

## Step 1: Upload Files to Hostinger

### Option A: Using File Manager (Recommended for beginners)

1. **Login to Hostinger**
   - Go to https://hpanel.hostinger.com
   - Login with your credentials

2. **Open File Manager**
   - Click on "File Manager" in the hosting dashboard
   - Navigate to `public_html` folder

3. **Upload Files**
   - Delete any default files in `public_html` (like index.html)
   - Upload ALL your website files to `public_html`:
     - All HTML files (index.html, about.html, services.html, etc.)
     - css/ folder
     - js/ folder
     - images/ folder
     - api/ folder
     - admin/ folder

### Option B: Using FTP (Recommended for larger files)

1. **Get FTP Credentials**
   - In Hostinger panel, go to Files → FTP Accounts
   - Note down: FTP Host, Username, Password, Port (usually 21)

2. **Use FTP Client** (FileZilla recommended)
   - Download FileZilla: https://filezilla-project.org/
   - Connect using your FTP credentials
   - Upload all files to `public_html` folder

---

## Step 2: Create MySQL Database

1. **Go to MySQL Databases**
   - In Hostinger panel, click "MySQL Databases"
   - Or search for "MySQL" in the search bar

2. **Create New Database**
   - Click "Create New Database"
   - Enter database name: `vishvavirat_leads` (or your preferred name)
   - Click "Create"
   - **Note down the database name** - you'll need it later

3. **Create Database User**
   - Scroll to "MySQL Users" section
   - Click "Create New User"
   - Enter username (e.g., `vishvavirat_admin`)
   - Enter a strong password
   - Click "Create"
   - **Note down username and password**

4. **Add User to Database**
   - Scroll to "Add User to Database"
   - Select the user and database you just created
   - Click "Add"
   - Select "ALL PRIVILEGES"
   - Click "Make Changes"

5. **Import Database Schema**
   - Click "phpMyAdmin" button (next to your database)
   - Select your database from left sidebar
   - Click "Import" tab
   - Click "Choose File" and select `api/database.sql` from your computer
   - Click "Go" at the bottom
   - You should see "Import has been successfully finished"

---

## Step 3: Configure Database Connection

1. **Open File Manager**
   - Navigate to `public_html/api/` folder
   - Find the file `config.sample.php`

2. **Create Configuration File**
   - **Rename** `config.sample.php` to `config.php`
   - Right-click on `config.php` and select "Edit"

3. **Update Database Credentials**
   Replace the sample values with your actual credentials:

   ```php
   // Database Configuration
   $db_host = 'localhost';                    // Keep as 'localhost'
   $db_user = 'your_actual_username';         // Username from Step 2
   $db_pass = 'your_actual_password';         // Password from Step 2
   $db_name = 'your_actual_database_name';    // Database name from Step 2

   // Email Configuration
   $notification_email = 'viratagencies770@gmail.com';  // Your actual email
   $site_name = 'VISHVAVIRAT SECURITY';

   // Admin Panel Security
   define('ADMIN_USERNAME', 'admin');                   // Change this!
   define('ADMIN_PASSWORD', 'your_secure_password');    // Change this to a strong password!

   // Site Settings
   define('SITE_URL', 'https://yourdomain.com');        // Your actual domain
   ```

4. **Save the File**
   - Click "Save Changes"

---

## Step 4: Test the Contact Form

1. **Visit Your Website**
   - Go to your domain (e.g., https://yourdomain.com)
   - Or use the temporary domain provided by Hostinger

2. **Test Form Submission**
   - Go to any service page (e.g., /services/personal-bouncer.html)
   - Fill out the contact form with test data
   - Click "Submit"
   - You should see a success message

3. **Check Email**
   - You should receive an email notification at `viratagencies770@gmail.com`
   - If you don't receive it within a few minutes, check spam folder

4. **Verify Database**
   - Go back to phpMyAdmin
   - Click on `contact_submissions` table
   - Click "Browse" - you should see your test submission

---

## Step 5: Access Admin Panel

1. **Open Admin Panel**
   - Go to: https://yourdomain.com/admin/
   - You'll see a login page

2. **Login**
   - Username: `admin` (or whatever you set in config.php)
   - Password: (the password you set in config.php)

3. **View Leads**
   - You should see your test submission
   - You can:
     - Filter by status, service type
     - Search leads
     - View details
     - Update status and add notes

---

## Step 6: Security Measures (IMPORTANT!)

### Protect the Admin Panel

Create a file `admin/.htaccess` with this content to add extra security:

```apache
# Prevent directory browsing
Options -Indexes

# Add additional password protection (optional but recommended)
# AuthType Basic
# AuthName "Restricted Area"
# AuthUserFile /path/to/.htpasswd
# Require valid-user

# Prevent access to config files
<FilesMatch "^(config\.php)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

### Secure Config File

Create `api/.htaccess`:

```apache
# Prevent direct access to config file
<FilesMatch "^(config\.php|config\.sample\.php)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

### Change Default Credentials

**IMPORTANT:** Change the admin username and password in `api/config.php`:

```php
define('ADMIN_USERNAME', 'your_unique_username');
define('ADMIN_PASSWORD', 'your_very_strong_password_here');
```

---

## Step 7: Email Configuration (If emails not working)

If you're not receiving email notifications:

### Option 1: Use Hostinger's SMTP

Update the `sendEmailNotification()` function in `api/contact.php` to use SMTP instead of PHP mail().

1. **Get SMTP Details from Hostinger**
   - Go to Emails section in Hostinger panel
   - Create an email account (e.g., noreply@yourdomain.com)
   - Note SMTP settings

2. **Install PHPMailer** (optional, for better email delivery)
   - Or use Hostinger's built-in mail() function

### Option 2: Use Gmail SMTP (Alternative)

If using Gmail:
- Enable "Less secure app access" or use App Password
- Update SMTP settings in contact.php

---

## Step 8: SSL Certificate (HTTPS)

1. **Enable SSL**
   - In Hostinger panel, go to "SSL"
   - Click "Install SSL Certificate"
   - Choose "Free SSL" (Let's Encrypt)
   - Wait 5-10 minutes for installation

2. **Force HTTPS**
   Create `.htaccess` in `public_html` root:

   ```apache
   # Force HTTPS
   RewriteEngine On
   RewriteCond %{HTTPS} off
   RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   ```

---

## Troubleshooting

### Forms Not Submitting

1. **Check JavaScript Console**
   - Open browser DevTools (F12)
   - Go to Console tab
   - Try submitting form
   - Look for errors

2. **Check if PHP files are accessible**
   - Visit: https://yourdomain.com/api/contact.php
   - You should see: `{"success":false,"message":"Method not allowed"}`
   - This means the file is accessible but requires POST

3. **Check file permissions**
   - In File Manager, right-click api/contact.php
   - Click "Permissions"
   - Should be 644

### Database Connection Errors

1. **Verify credentials in config.php**
2. **Check database name** - it might have a prefix (e.g., `u123456_vishvavirat_leads`)
3. **Test connection** - Create a test file to verify

### Emails Not Sending

1. **Check spam folder**
2. **Verify email address in config.php**
3. **Check PHP mail logs** in cPanel
4. **Consider using SMTP** instead of PHP mail()

### Admin Panel Not Loading

1. **Check if session is enabled** on server
2. **Verify config.php exists** in api/ folder
3. **Check browser console** for JavaScript errors

---

## Maintenance

### Regular Tasks

1. **Check leads daily**
   - Login to admin panel
   - Review new leads
   - Update status as you contact them

2. **Backup database weekly**
   - phpMyAdmin → Export
   - Download SQL file
   - Store securely

3. **Monitor email notifications**
   - Ensure you're receiving notifications
   - Check spam folder periodically

4. **Update admin password quarterly**
   - Change password in config.php
   - Use strong passwords

---

## Quick Reference

| Item | Location | Purpose |
|------|----------|---------|
| Website Files | public_html/ | All HTML, CSS, JS, images |
| Contact Handler | public_html/api/contact.php | Processes form submissions |
| Configuration | public_html/api/config.php | Database & email settings |
| Admin Panel | public_html/admin/ | View and manage leads |
| Database | phpMyAdmin | Stores all lead data |

### Important URLs

- Website: https://yourdomain.com
- Admin Panel: https://yourdomain.com/admin/
- phpMyAdmin: Access via Hostinger panel
- File Manager: Access via Hostinger panel

---

## Support

If you need help:

1. **Hostinger Support**
   - 24/7 Live Chat
   - Email: support@hostinger.com
   - Knowledge Base: https://support.hostinger.com

2. **Check Logs**
   - cPanel → Error Logs
   - public_html/logs/php-errors.log

---

## Next Steps After Deployment

1. ✅ Test all forms on all pages
2. ✅ Verify email notifications working
3. ✅ Test admin panel functionality
4. ✅ Change default admin credentials
5. ✅ Set up SSL certificate
6. ✅ Add domain to config.php
7. ✅ Set up regular database backups
8. ✅ Test on mobile devices
9. ✅ Share admin panel credentials with team (securely)
10. ✅ Monitor first few lead submissions

---

## Contact Form Locations

All these pages have contact forms that will now work:

- Home page: index.html
- All service pages:
  - /services/personal-bouncer.html
  - /services/government-security-guard.html
  - /services/driver.html
  - /services/housekeeping.html
  - /services/gardener.html
  - /services/maid.html

---

**Congratulations! Your website is now live with a fully functional lead capture system!** 🎉
