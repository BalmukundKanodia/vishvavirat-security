# Troubleshooting Guide

## How to Check If Something is Wrong

### ✅ Everything Working Correctly Looks Like:

1. **Form Submission:**
   - Fill out form → Click Submit
   - See green success message: "Thank you! Your inquiry has been submitted..."
   - Page stays on same page (doesn't refresh to blank page)

2. **Email Notification:**
   - Check inbox within 1-2 minutes
   - Email subject: "New [Service Name] Inquiry - VISHVAVIRAT SECURITY"
   - Email contains all form data

3. **Admin Panel:**
   - Go to /admin/ → See login page
   - Login → See dashboard with statistics
   - Click on a lead → See details popup

---

## Problem 1: Form Shows Error After Submitting

### Symptoms:
- Click "Send Message" or "Submit"
- See red error message
- Form doesn't submit

### Possible Errors & Solutions:

#### Error: "Please provide a valid email address"
**Cause:** Email format incorrect
**Solution:** 
- Make sure email has @ symbol and domain
- Example: user@example.com ✅
- Example: userexample.com ❌

#### Error: "Please provide a valid 10-digit phone number"
**Cause:** Phone number format incorrect
**Solution:**
- Use 10 digits starting with 6-9
- Example: 9876543210 ✅
- Example: 1234567890 ❌ (can't start with 1)

#### Error: "An error occurred while processing your request"
**Cause:** Database connection problem
**Solution:**
1. Check config.php has correct database credentials
2. Go to phpMyAdmin and verify database exists
3. Check error logs (see "How to Check Logs" below)

---

## Problem 2: No Email Received After Form Submission

### Symptoms:
- Form submits successfully (green message)
- But no email arrives
- Lead IS in database (check phpMyAdmin)

### Solutions:

#### Step 1: Check Spam Folder
- Most common reason!
- Look in Gmail/Outlook spam folder
- If found, mark as "Not Spam"

#### Step 2: Verify Email Address
1. Open File Manager → api/config.php
2. Find line: `$notification_email = '...'`
3. Make sure email is correct: `viratagencies770@gmail.com`
4. Save if you made changes

#### Step 3: Test with Different Email
1. Change notification email to a different address
2. Submit test form
3. If this email works, problem is with original email

#### Step 4: Check Email Logs (Hostinger)
1. Go to hPanel
2. Look for "Email" or "Email Logs"
3. Check if email was sent by server
4. If shows "Failed" → Contact Hostinger support

---

## Problem 3: Database Connection Failed

### Symptoms:
- Form shows error about database
- Console shows: "Failed to save submission"
- Admin panel won't load

### Solutions:

#### Step 1: Verify Credentials
1. Open File Manager → api/config.php
2. Check these lines match EXACTLY:

```php
$db_host = 'localhost';                    // Always 'localhost' for Hostinger
$db_user = 'u123456_vishvavirat_admin';   // Must match database user
$db_pass = 'your_password';                // Must match password
$db_name = 'u123456_vishvavirat_leads';   // Must match database name
```

#### Step 2: Check Database Name Prefix
- Hostinger adds prefix to database names
- Example: You created `vishvavirat_leads`
- Actual name: `u123456_vishvavirat_leads`
- **Use the FULL name** with prefix in config.php

#### Step 3: Verify User Permissions
1. Go to Databases in hPanel
2. Scroll to "Add User to Database"
3. Make sure your user is listed under your database
4. Click "Manage" → Should show "ALL PRIVILEGES"
5. If not, add user again with ALL PRIVILEGES

#### Step 4: Test Database Connection
Create a test file to verify:

1. In File Manager, create: `public_html/test-db.php`
2. Add this code:

```php
<?php
require_once 'api/config.php';

$db = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($db->connect_error) {
    echo "❌ Connection failed: " . $db->connect_error;
} else {
    echo "✅ Database connected successfully!";
}
$db->close();
?>
```

3. Visit: `https://yourdomain.com/test-db.php`
4. Should show: "✅ Database connected successfully!"
5. **Delete this file after testing** (security)

---

## Problem 4: Can't Login to Admin Panel

### Symptoms:
- Go to /admin/
- Enter username and password
- Shows "Invalid credentials"

### Solutions:

#### Step 1: Verify Credentials
1. Open File Manager → api/config.php
2. Find these lines:

```php
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'your_password');
```

3. These are your EXACT login credentials
4. **Username and password are CASE-SENSITIVE**
   - "Admin" ≠ "admin"
   - "Password" ≠ "password"

#### Step 2: Reset Credentials
1. Edit config.php
2. Change to new credentials:

```php
define('ADMIN_USERNAME', 'newusername');
define('ADMIN_PASSWORD', 'NewPassword123');
```

3. Save file
4. Try logging in with new credentials

#### Step 3: Clear Browser Data
1. Clear browser cookies and cache
2. Close browser completely
3. Reopen and try again

---

## Problem 5: 500 Internal Server Error

### Symptoms:
- Website shows: "500 Internal Server Error"
- Or "Something went wrong"
- Can't access any pages

### Solutions:

#### Step 1: Check config.php Exists
1. File Manager → public_html/api/
2. Look for file named **config.php** (NOT config.sample.php)
3. If missing:
   - Rename config.sample.php to config.php
   - Add your credentials

#### Step 2: Check File Permissions
1. Right-click on config.php → Properties/Permissions
2. Should be: **644**
3. If different, change to 644

#### Step 3: Check for PHP Errors
1. File Manager → public_html/api/contact.php
2. Look at first line - should be: `<?php`
3. Make sure there's no space or characters before `<?php`

#### Step 4: Check Error Logs
1. hPanel → Advanced → Error Logs
2. Look for recent errors
3. Last error will tell you what's wrong

---

## Problem 6: Admin Panel Loads But Shows "No leads found"

### Symptoms:
- Can login to admin panel
- See statistics show 0
- Table says "No leads found"
- But you submitted test form

### Solutions:

#### Step 1: Check phpMyAdmin
1. Go to Databases → phpMyAdmin
2. Select your database
3. Click "contact_submissions" table
4. Click "Browse"
5. Do you see data?

If YES (data exists):
- Problem is with PHP displaying data
- Check error logs
- Make sure database name in config.php is correct

If NO (no data):
- Forms aren't saving to database
- Follow "Problem 3: Database Connection Failed"

#### Step 2: Check Database Table Name
1. In phpMyAdmin, verify table is named: **contact_submissions**
2. If different name, forms won't work
3. Re-import database.sql file

---

## Problem 7: Images Not Loading

### Symptoms:
- Website loads but images show broken icon
- Or images missing

### Solutions:

#### Step 1: Check Images Uploaded
1. File Manager → public_html/images/
2. Verify all images are there:
   - VISHVAVIRAT.svg
   - favicon.svg
   - All .jpg files

#### Step 2: Check Image Paths
1. Images should be referenced as: `/images/filename.jpg`
2. NOT: `images/filename.jpg` (missing leading slash)

#### Step 3: Check File Names
- File names are case-sensitive
- `Logo.jpg` ≠ `logo.jpg`
- Make sure HTML matches actual filename

#### Step 4: Clear Browser Cache
1. Hard refresh: Ctrl+F5 (Windows) or Cmd+Shift+R (Mac)
2. Or clear browser cache completely

---

## Problem 8: SSL Certificate Not Working

### Symptoms:
- Browser shows "Not Secure"
- Can't access via https://

### Solutions:

#### Step 1: Install SSL
1. hPanel → SSL
2. Find your domain
3. Click "Install SSL" or "Activate"
4. Choose "Free SSL"
5. **Wait 10-15 minutes** for installation

#### Step 2: Verify Installation
1. Go to SSL section again
2. Check status next to your domain
3. Should show: "Active" or green checkmark

#### Step 3: Force HTTPS
Create `.htaccess` file in public_html:

```apache
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

---

## How to Check Logs

### PHP Error Logs

**Method 1: Via hPanel**
1. hPanel → Advanced → Error Logs
2. View recent errors
3. Click on log to see details

**Method 2: Via File Manager**
1. File Manager → public_html/logs/
2. Look for `php-errors.log`
3. View file contents

### Email Logs

1. hPanel → Email Section
2. Look for "Email Logs" or "Mail Logs"
3. Check if emails were sent or failed

### Access Logs

1. hPanel → Advanced → Access Logs
2. See all requests to your website
3. Look for 404 or 500 errors

---

## How to Test Each Component

### Test 1: Website Files
```
Visit: https://yourdomain.com
Expected: Homepage loads with images and styling
```

### Test 2: Database Connection
```
Visit: https://yourdomain.com/admin/
Expected: Login page loads (not error page)
```

### Test 3: Contact Form
```
Visit: https://yourdomain.com/contact.html
Fill form → Submit
Expected: Green success message
```

### Test 4: Email System
```
Submit form → Wait 2 minutes
Expected: Email in inbox
```

### Test 5: Admin Panel
```
Login to /admin/
Expected: See dashboard with stats
```

### Test 6: Database Storage
```
phpMyAdmin → contact_submissions → Browse
Expected: See submitted leads
```

---

## Getting Help

### Self-Help
1. Read error message carefully
2. Check this troubleshooting guide
3. Check error logs
4. Google the exact error message

### Hostinger Support
1. Click chat icon in hPanel (bottom right)
2. Available 24/7
3. Provide them:
   - Your domain name
   - Exact error message
   - What you were trying to do
   - Screenshots if possible

### What to Share with Support
- ✅ Error messages
- ✅ Screenshots
- ✅ Log excerpts
- ❌ DO NOT share passwords
- ❌ DO NOT share config.php contents

---

## Prevention Checklist

Before you face problems, do this:

```
□ Save all credentials in a password manager
□ Backup database weekly (phpMyAdmin → Export)
□ Backup files monthly (File Manager → Download)
□ Test forms after any changes
□ Keep admin password strong
□ Monitor error logs weekly
□ Update config.php carefully (make backup first)
```

---

## Emergency: Reset Everything

If nothing works and you want to start fresh:

### 1. Backup First
- Export database (phpMyAdmin → Export)
- Download all files (File Manager → Compress → Download)

### 2. Delete Database
- Databases → Delete database
- Delete user

### 3. Recreate Database
- Create new database
- Create new user
- Import database.sql

### 4. Update config.php
- Update with new credentials
- Save file

### 5. Test Again
- Submit test form
- Check if working

---

**Still stuck? Contact Hostinger support - they're very helpful!**
