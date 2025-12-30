# VISHVAVIRAT SECURITY - Hostinger Deployment Guide

## 📋 Table of Contents

1. [Pre-Deployment Checklist](#pre-deployment-checklist)
2. [Hostinger Setup](#hostinger-setup)
3. [File Upload](#file-upload)
4. [Database Configuration](#database-configuration)
5. [Backend Configuration](#backend-configuration)
6. [SSL Certificate Setup](#ssl-certificate-setup)
7. [Domain Connection](#domain-connection)
8. [Email Configuration](#email-configuration)
9. [Testing & Verification](#testing-verification)
10. [Security Hardening](#security-hardening)
11. [Performance Optimization](#performance-optimization)
12. [Admin Panel Access](#admin-panel-access)
13. [Maintenance & Updates](#maintenance-updates)
14. [Troubleshooting](#troubleshooting)

---

## 🔍 Pre-Deployment Checklist

Before deploying, ensure you have:

- [ ] Hostinger hosting account (Business or Premium plan recommended)
- [ ] Domain name configured (www.viratsecurity.com)
- [ ] Email account set up (viratagencies770@gmail.com)
- [ ] All website files ready
- [ ] Database schema file
- [ ] FTP/SFTP credentials from Hostinger
- [ ] cPanel access credentials

---

## 🌐 Hostinger Setup

### Step 1: Access Your Hostinger Control Panel

1. Login to Hostinger at https://hpanel.hostinger.com
2. Select your hosting plan
3. Navigate to the "Website" section

### Step 2: Access cPanel

1. Click on "Advanced" → "cPanel"
2. This will open the cPanel interface
3. Familiarize yourself with the layout

---

## 📤 File Upload

### Method 1: File Manager (Recommended for Beginners)

1. **Open File Manager**
   - In cPanel, find "Files" section
   - Click "File Manager"

2. **Navigate to public_html**
   - Double-click `public_html` folder
   - Delete any default files (index.html, etc.)

3. **Upload Files**
   - Click "Upload" button
   - Drag and drop your project files OR
   - Click "Select File" and choose files
   - Upload all files maintaining the folder structure:
     ```
     public_html/
     ├── index.html
     ├── about.html
     ├── contact.html
     ├── industries.html
     ├── services.html
     ├── why-choose-us.html
     ├── css/
     │   └── style.css
     ├── js/
     │   └── main.js
     ├── images/
     │   └── (your images)
     ├── includes/
     │   ├── header.php
     │   └── footer.php
     ├── services/
     │   ├── personal-bouncer.html
     │   ├── government-security-guard.html
     │   ├── driver.html
     │   ├── housekeeping.html
     │   ├── gardener.html
     │   └── maid.html
     ├── api/
     │   ├── config.php
     │   ├── contact.php
     │   ├── database.php
     │   └── security.php
     └── admin/
         └── (admin panel files)
     ```

4. **Set Permissions**
   - Right-click `api` folder → Permissions
   - Set to `755` (rwxr-xr-x)
   - Right-click `api/config.php` → Permissions
   - Set to `600` (rw-------)

### Method 2: FTP Upload (Recommended for Large Files)

1. **Get FTP Credentials**
   - In Hostinger hPanel
   - Go to "Files" → "FTP Accounts"
   - Note your FTP hostname, username, and password

2. **Use FTP Client (FileZilla)**
   - Download FileZilla from https://filezilla-project.org
   - Open FileZilla
   - Enter:
     - Host: ftp.viratsecurity.com (or from Hostinger)
     - Username: (your FTP username)
     - Password: (your FTP password)
     - Port: 21
   - Click "Quickconnect"

3. **Upload Files**
   - Navigate to `public_html` on the right panel
   - Drag files from left panel (your computer) to right panel (server)

---

## 🗄️ Database Configuration

### Step 1: Create Database

1. **Open MySQL Databases**
   - In cPanel, find "Databases" section
   - Click "MySQL Databases"

2. **Create New Database**
   - Under "Create New Database"
   - Enter database name: `vishvavirat_security`
   - Click "Create Database"
   - Note the full database name (usually prefixed): `username_vishvavirat_security`

3. **Create Database User**
   - Scroll to "MySQL Users" section
   - Enter username: `vishvavirat_user`
   - Generate a strong password (click "Password Generator")
   - **IMPORTANT**: Save this password securely
   - Click "Create User"

4. **Add User to Database**
   - Scroll to "Add User to Database"
   - Select the user you created
   - Select the database you created
   - Click "Add"
   - Check "ALL PRIVILEGES"
   - Click "Make Changes"

### Step 2: Import Database Schema

1. **Open phpMyAdmin**
   - In cPanel, find "Databases" section
   - Click "phpMyAdmin"

2. **Select Database**
   - In left sidebar, click on your database name
   - `username_vishvavirat_security`

3. **Import SQL File**
   - Click "Import" tab
   - Click "Choose File"
   - Select `database_schema.sql` from your computer
   - Scroll down and click "Go"
   - Wait for success message: "Import has been successfully finished"

4. **Verify Tables**
   - Click on your database in left sidebar
   - You should see tables:
     - contact_submissions
     - admin_users
     - activity_log
   - Click "Structure" to verify columns

---

## ⚙️ Backend Configuration

### Step 1: Configure Database Connection

1. **Edit config.php**
   - In File Manager, navigate to `public_html/api/`
   - Right-click `config.php` → Edit

2. **Update Database Credentials**
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'username_vishvavirat_security');  // Your full database name
   define('DB_USER', 'username_vishvavirat_user');      // Your database username
   define('DB_PASS', 'your_secure_password');           // Your database password
   ```

3. **Update Email Configuration**
   ```php
   define('ADMIN_EMAIL', 'viratagencies770@gmail.com');
   define('FROM_EMAIL', 'noreply@viratsecurity.com');
   define('FROM_NAME', 'VISHVAVIRAT SECURITY');
   ```

4. **Update Site URL**
   ```php
   define('SITE_URL', 'https://www.viratsecurity.com');
   ```

5. **Set Debug Mode to False**
   ```php
   define('DEBUG_MODE', false);
   ```

6. **Save Changes**

### Step 2: Set File Permissions

1. **Set config.php Permissions**
   - Right-click `api/config.php`
   - Click "Permissions"
   - Enter `600` or check: Owner: Read + Write, Group: None, Public: None
   - Click "Change Permissions"

2. **Set API Directory Permissions**
   - Right-click `api` folder
   - Permissions: `755`

---

## 🔒 SSL Certificate Setup

### Step 1: Enable SSL

1. **Access SSL/TLS Manager**
   - In Hostinger hPanel (not cPanel)
   - Go to "SSL" section
   - Or in cPanel: "Security" → "SSL/TLS"

2. **Install Free SSL (Let's Encrypt)**
   - Hostinger provides free SSL
   - Click "Install SSL" or it may be automatic
   - Select your domain: www.viratsecurity.com
   - Click "Install"
   - Wait 5-10 minutes for activation

### Step 2: Force HTTPS

1. **Create/Edit .htaccess**
   - In File Manager, go to `public_html`
   - If `.htaccess` exists, edit it
   - If not, create new file named `.htaccess`

2. **Add HTTPS Redirect**
   ```apache
   # Force HTTPS
   RewriteEngine On
   RewriteCond %{HTTPS} off
   RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

   # Force WWW
   RewriteCond %{HTTP_HOST} !^www\.
   RewriteRule ^(.*)$ https://www.%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   ```

3. **Save File**

---

## 🌍 Domain Connection

### If Domain is Already with Hostinger

1. **Point to Your Hosting**
   - In hPanel, go to "Domains"
   - Your domain should already be pointed to your hosting
   - Verify nameservers are Hostinger's

### If Domain is with Another Registrar

1. **Get Hostinger Nameservers**
   - In hPanel → Hosting → Manage
   - Note the nameservers:
     - ns1.hostinger.com
     - ns2.hostinger.com

2. **Update at Your Domain Registrar**
   - Login to your domain registrar (GoDaddy, Namecheap, etc.)
   - Find DNS/Nameserver settings
   - Replace existing nameservers with Hostinger's
   - Save changes
   - **Note**: DNS propagation takes 24-48 hours

3. **Add Domain in Hostinger**
   - hPanel → Domains → Add Domain
   - Enter: www.viratsecurity.com
   - Select your hosting account
   - Click "Add Domain"

---

## 📧 Email Configuration

### Step 1: Create Email Account

1. **Access Email Accounts**
   - cPanel → "Email" section
   - Click "Email Accounts"

2. **Create Account**
   - Email: `noreply@viratsecurity.com`
   - Password: Generate strong password
   - Mailbox Quota: 250 MB (sufficient)
   - Click "Create"

3. **Verify Existing Email**
   - Confirm `viratagencies770@gmail.com` is accessible
   - This will receive all inquiries

### Step 2: Test Email Sending

1. **Create Test Script**
   - Create file: `public_html/test-email.php`
   ```php
   <?php
   $to = 'viratagencies770@gmail.com';
   $subject = 'Test Email from Vishvavirat Security';
   $message = 'This is a test email from your website.';
   $headers = 'From: noreply@viratsecurity.com';

   if(mail($to, $subject, $message, $headers)) {
       echo 'Email sent successfully!';
   } else {
       echo 'Email sending failed.';
   }
   ?>
   ```

2. **Run Test**
   - Visit: https://www.viratsecurity.com/test-email.php
   - Check if email arrives at viratagencies770@gmail.com
   - Check spam folder if not in inbox

3. **Delete Test File**
   - After successful test, delete `test-email.php`

### Step 3: Configure SPF and DKIM (Important for Deliverability)

1. **Access DNS Zone Editor**
   - cPanel → "Domains" → "Zone Editor"

2. **Add SPF Record**
   - Click "Manage" next to your domain
   - Add TXT record:
     - Name: `@` or `viratsecurity.com`
     - Value: `v=spf1 include:_spf.hostinger.com ~all`
   - TTL: 14400
   - Click "Add Record"

3. **Enable DKIM**
   - cPanel → "Email" → "Email Deliverability"
   - Click "Manage" next to your domain
   - Install DKIM if not present
   - Verify all checkmarks are green

---

## ✅ Testing & Verification

### 1. Homepage Test

- Visit: https://www.viratsecurity.com
- Check:
  - [ ] Page loads without errors
  - [ ] Navigation menu works
  - [ ] All links are functional
  - [ ] Mobile responsiveness
  - [ ] HTTPS is active (padlock icon)

### 2. Service Pages Test

- Visit each service page:
  - /services/personal-bouncer.html
  - /services/government-security-guard.html
  - /services/driver.html
  - /services/housekeeping.html
  - /services/gardener.html
  - /services/maid.html
- Verify forms load correctly

### 3. Contact Form Test

1. **Test Submission**
   - Go to contact page
   - Fill out form with real data
   - Submit
   - Should see success message

2. **Verify Database**
   - phpMyAdmin → Select database
   - Click `contact_submissions` table
   - Click "Browse"
   - Verify your test submission appears

3. **Verify Email**
   - Check viratagencies770@gmail.com
   - Should receive notification email
   - Check spam folder if not in inbox

### 4. Cross-Browser Testing

Test on:
- [ ] Chrome
- [ ] Firefox
- [ ] Safari
- [ ] Edge
- [ ] Mobile browsers (iOS Safari, Chrome Mobile)

### 5. Mobile Testing

- Use https://www.responsinator.com
- Enter your URL
- Verify layout on different devices

---

## 🛡️ Security Hardening

### 1. Protect Configuration Files

**Add to .htaccess in public_html:**
```apache
# Protect config files
<FilesMatch "^(config\.php)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Protect .htaccess itself
<Files .htaccess>
    Order allow,deny
    Deny from all
</Files>

# Disable directory browsing
Options -Indexes

# Prevent access to hidden files
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>
```

### 2. Secure Headers

**Add to .htaccess:**
```apache
# Security Headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "DENY"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
    Header set Permissions-Policy "geolocation=(), microphone=(), camera=()"
</IfModule>
```

### 3. Hide PHP Version

**Add to .htaccess:**
```apache
# Hide PHP version
Header unset X-Powered-By
ServerSignature Off
```

### 4. Rate Limiting

**Already implemented in backend (api/security.php)**
- 3 submissions per hour per session
- Prevents spam and abuse

### 5. Regular Updates

- Keep PHP version updated (via Hostinger)
- Monitor security logs
- Update passwords quarterly

---

## ⚡ Performance Optimization

### 1. Enable Gzip Compression

**Add to .htaccess:**
```apache
# Enable Gzip Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
</IfModule>
```

### 2. Browser Caching

**Add to .htaccess:**
```apache
# Browser Caching
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType text/html "access plus 0 seconds"
</IfModule>
```

### 3. Image Optimization

1. **Optimize images before upload:**
   - Use TinyPNG.com or similar
   - Max width: 1920px for full-width images
   - Use WebP format for modern browsers

2. **Implement lazy loading** (already in main.js)

### 4. Enable Cloudflare (Optional but Recommended)

1. **Sign up for Cloudflare** (free plan)
   - Visit cloudflare.com
   - Add your website

2. **Update Nameservers**
   - Cloudflare will provide nameservers
   - Update at your domain registrar
   - Or use Cloudflare as proxy (keep Hostinger NS)

3. **Configure Settings**
   - Enable "Auto Minify" for JS, CSS, HTML
   - Enable "Brotli" compression
   - Set SSL to "Full"
   - Enable "Always Use HTTPS"

---

## 👨‍💼 Admin Panel Access

### Viewing Submissions

**Method 1: phpMyAdmin (Basic)**

1. Login to cPanel → phpMyAdmin
2. Select database
3. Click `contact_submissions` table
4. Click "Browse" to view all submissions
5. Use "Search" tab to filter by service, date, etc.

**Method 2: Export to Excel**

1. In phpMyAdmin, browse `contact_submissions`
2. Click "Export" tab
3. Select format: "CSV for MS Excel"
4. Click "Go"
5. Open in Excel/Google Sheets

**Method 3: SQL Queries**

View pending inquiries:
```sql
SELECT * FROM contact_submissions
WHERE status = 'new'
ORDER BY created_at DESC;
```

View by service:
```sql
SELECT * FROM contact_submissions
WHERE service = 'Personal Bouncer'
ORDER BY created_at DESC;
```

Service-wise stats:
```sql
SELECT * FROM service_inquiry_stats;
```

### Update Submission Status

```sql
UPDATE contact_submissions
SET status = 'contacted',
    notes = 'Called on 2025-01-15'
WHERE id = 123;
```

---

## 🔧 Maintenance & Updates

### Daily Tasks

- Check email for new inquiries
- Review contact_submissions table
- Update inquiry status

### Weekly Tasks

- Backup database (cPanel → Backup)
- Review submission stats
- Check website functionality

### Monthly Tasks

- Update PHP version if available
- Review security logs
- Clean up old "archived" submissions
- Test all forms

### Database Backup

1. **Automatic Backups (Hostinger)**
   - Hostinger creates daily backups
   - Restore from hPanel → Backups

2. **Manual Backup**
   - phpMyAdmin → Select database
   - Click "Export"
   - Format: SQL
   - Click "Go"
   - Save file with date: `backup_2025_01_15.sql`

### File Backup

1. **Using File Manager**
   - Select all files in public_html
   - Click "Compress"
   - Format: ZIP
   - Download to your computer

2. **Schedule Regular Backups**
   - Weekly backups recommended
   - Store off-site (Google Drive, Dropbox)

---

## 🔍 Troubleshooting

### Issue: Forms Not Submitting

**Check:**
1. Browser console for JavaScript errors (F12)
2. Check `api/error.log` for PHP errors
3. Verify database credentials in config.php
4. Test email sending with test script
5. Check file permissions (api folder: 755, config.php: 600)

**Solution:**
- Enable DEBUG_MODE in config.php temporarily
- Check error.log
- Fix issue
- Disable DEBUG_MODE

### Issue: Emails Not Receiving

**Check:**
1. Spam folder
2. Email quota not full
3. SPF/DKIM records configured
4. FROM_EMAIL uses your domain (@viratsecurity.com)

**Solution:**
- Use email from your domain for FROM_EMAIL
- Configure SPF record
- Test with test-email.php script

### Issue: Database Connection Failed

**Check:**
1. Database name is correct (with prefix)
2. Database user has permissions
3. Password is correct (no extra spaces)
4. Database server is 'localhost'

**Solution:**
- Copy-paste credentials carefully
- Verify in cPanel → MySQL Databases
- Re-create user if needed

### Issue: 500 Internal Server Error

**Check:**
1. .htaccess syntax errors
2. PHP version compatibility
3. File permissions
4. Error logs (cPanel → Errors)

**Solution:**
- Rename .htaccess to .htaccess.bak temporarily
- Check if site works
- Fix .htaccess syntax
- Restore file

### Issue: CSS/JS Not Loading

**Check:**
1. File paths are correct
2. Files uploaded to correct folders
3. File permissions
4. Clear browser cache

**Solution:**
- Verify paths in HTML: `/css/style.css` not `css/style.css`
- Hard refresh: Ctrl+Shift+R (Windows) or Cmd+Shift+R (Mac)

---

## 📞 Support Contacts

### Hostinger Support
- Live Chat: Available 24/7 in hPanel
- Email: support@hostinger.com
- Knowledge Base: https://support.hostinger.com

### Emergency Contacts
- Database Issues: Check phpMyAdmin
- Email Issues: Check cPanel → Email
- Domain Issues: Check DNS settings

---

## 🎯 Go-Live Checklist

Before announcing the website:

- [ ] All pages load correctly
- [ ] Forms submit successfully
- [ ] Emails are received
- [ ] SSL certificate active (HTTPS)
- [ ] Mobile responsive on all devices
- [ ] Cross-browser tested
- [ ] Contact information accurate
- [ ] All services pages complete
- [ ] Privacy policy and terms added
- [ ] Google Analytics added (optional)
- [ ] Sitemap.xml created (optional)
- [ ] robots.txt configured (optional)
- [ ] 404 error page created
- [ ] Backup completed
- [ ] Security headers active
- [ ] Performance optimized
- [ ] DEBUG_MODE = false in config.php

---

## 📈 Future Enhancements

### Short-term (1-3 months)
- Add Google reCAPTCHA to forms
- Create custom admin dashboard
- Implement WhatsApp inquiry button
- Add testimonials section
- Create blog for SEO

### Long-term (3-6 months)
- Multi-language support (English, Hindi, Kannada)
- Online quote calculator
- Staff scheduling system
- Client portal
- Mobile app

---

## 📝 Additional Notes

1. **Phone Number**: Update `+91-XXXXXXXXXX` with actual phone number throughout the site
2. **Images**: Replace placeholder emoji icons with professional images
3. **Content**: Review all content for accuracy
4. **Legal**: Add Privacy Policy and Terms & Conditions pages
5. **SEO**: Submit sitemap to Google Search Console
6. **Analytics**: Set up Google Analytics for tracking

---

**Deployment completed successfully!**

For any issues or questions during deployment, refer to this guide or contact Hostinger support.
