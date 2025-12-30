# Complete Hostinger Deployment Guide for Beginners

## What You'll Need

- ✅ Hostinger account (with hosting plan activated)
- ✅ Your website files (already on your computer)
- ✅ About 30-45 minutes of time

---

## Part 1: Login to Hostinger

### Step 1: Go to Hostinger
1. Open your web browser
2. Go to: **https://hpanel.hostinger.com**
3. You'll see the login page

### Step 2: Login
1. Enter your email address (the one you used to sign up)
2. Enter your password
3. Click **"Log In"**

### Step 3: Access Your Hosting
1. After login, you'll see the **hPanel** dashboard
2. Look for **"Hosting"** or **"Websites"** section
3. Click on your domain name (or the hosting plan you want to use)
4. If you see multiple websites, choose the one where you want to deploy

---

## Part 2: Upload Your Website Files

### Step 1: Open File Manager
1. In your hosting dashboard, look for **"File Manager"** button
2. Click on **"File Manager"**
3. A new window/tab will open showing your files

### Step 2: Navigate to public_html
1. You'll see a list of folders on the left side
2. Click on the folder named **"public_html"**
3. This is where your website files go
4. **IMPORTANT:** If you see any files inside (like `index.html`, `default.html`, or `coming-soon.html`), delete them:
   - Select each file (click checkbox)
   - Click **"Delete"** button at the top
   - Confirm deletion

### Step 3: Upload Your Files

#### Option A: Upload Individual Files (Easier)

1. Click the **"Upload Files"** button (usually at top right)
2. A upload dialog will appear

**Upload HTML files:**
3. Click **"Select Files"** or drag and drop
4. Navigate to your project folder on your computer
5. Select ALL HTML files:
   - `index.html`
   - `about.html`
   - `contact.html`
   - `industries.html`
   - `services.html`
   - `why-choose-us.html`
6. Click **"Open"** - files will start uploading
7. Wait for upload to complete (you'll see green checkmarks)

**Upload folders:**
8. Click **"Upload Files"** again
9. Click **"Select Folder"** or similar option
10. Select the **"css"** folder from your project
11. Upload completes
12. Repeat for:
    - **"js"** folder
    - **"images"** folder
    - **"services"** folder (contains all service pages)
    - **"api"** folder
    - **"admin"** folder
    - **"logs"** folder

#### Option B: Upload as ZIP (Faster for Large Projects)

1. On your computer, create a ZIP file:
   - Select ALL your website files and folders
   - Right-click → "Compress" or "Send to → Compressed folder"
   - Name it `website.zip`

2. In File Manager, click **"Upload Files"**
3. Select your `website.zip` file
4. Wait for upload to complete
5. After upload, you'll see `website.zip` in file manager
6. Right-click on `website.zip`
7. Click **"Extract"** or **"Unzip"**
8. Extract to `public_html`
9. After extraction, delete the `website.zip` file

### Step 4: Verify Files Uploaded
After upload, your `public_html` folder should contain:
```
public_html/
├── index.html
├── about.html
├── contact.html
├── industries.html
├── services.html
├── why-choose-us.html
├── css/
├── js/
├── images/
├── services/
├── api/
├── admin/
└── logs/
```

**Check:** Click on folders to make sure files are inside them.

---

## Part 3: Create MySQL Database

### Step 1: Go to Database Section
1. Go back to your Hostinger main dashboard (hPanel)
2. Look for **"Databases"** or **"MySQL Databases"**
3. Click on it

### Step 2: Create Database
1. You'll see **"Create New Database"** button
2. Click on it
3. Enter a database name: `vishvavirat_leads`
   - **Note:** Hostinger might add a prefix like `u123456_vishvavirat_leads`
   - That's normal! Write down the FULL database name
4. Click **"Create"**

### Step 3: Create Database User
1. Scroll down to **"MySQL Users"** section
2. Click **"Create New User"**
3. Enter username: `vishvavirat_admin`
   - Again, Hostinger might add a prefix: `u123456_vishvavirat_admin`
   - Write down the FULL username
4. Enter a strong password
   - Use at least 12 characters
   - Mix of letters, numbers, symbols
   - **IMPORTANT:** Write this password down somewhere safe!
5. Click **"Create"**

### Step 4: Link User to Database
1. Scroll to **"Add User To Database"** section
2. Select your database: `u123456_vishvavirat_leads`
3. Select your user: `u123456_vishvavirat_admin`
4. Click **"Add"**
5. A permissions screen appears
6. Select **"ALL PRIVILEGES"** (or check all boxes)
7. Click **"Make Changes"** or **"Save"**

### Step 5: Note Down Your Credentials

**WRITE THESE DOWN - YOU'LL NEED THEM:**

```
Database Host: localhost
Database Name: u123456_vishvavirat_leads (your actual name)
Database Username: u123456_vishvavirat_admin (your actual username)
Database Password: (the password you created)
```

---

## Part 4: Import Database Schema

### Step 1: Open phpMyAdmin
1. Still in the Databases section
2. Find your database in the list
3. Click the **"phpMyAdmin"** button next to it
4. A new window opens with phpMyAdmin

### Step 2: Select Your Database
1. On the left sidebar, you'll see your database name
2. Click on it
3. It should be highlighted/selected

### Step 3: Import SQL File
1. Click the **"Import"** tab at the top
2. Click **"Choose File"** button
3. Navigate to your project folder on your computer
4. Go to the **"api"** folder
5. Select the file **"database.sql"**
6. Click **"Open"**
7. Scroll down and click **"Go"** button at the bottom
8. Wait a few seconds
9. You should see a green message: **"Import has been successfully finished"**

### Step 4: Verify Table Created
1. Click on your database name in left sidebar
2. You should see a table named **"contact_submissions"**
3. Click on it
4. Click **"Structure"** tab - you'll see all the columns (name, email, phone, etc.)
5. ✅ Database is ready!

---

## Part 5: Configure Database Connection

### Step 1: Open File Manager
1. Go back to Hostinger hPanel
2. Open **File Manager** again
3. Navigate to **public_html/api/**

### Step 2: Find Configuration File
1. Look for the file **"config.sample.php"**
2. Right-click on it
3. Select **"Rename"**
4. Change the name to: **"config.php"** (remove ".sample")
5. Click **"Rename"** or **"Save"**

### Step 3: Edit Configuration File
1. Find **"config.php"** in the api folder
2. Right-click on it
3. Select **"Edit"**
4. A code editor will open

### Step 4: Update Database Settings

Find these lines (around line 10-13):

```php
$db_host = 'localhost';
$db_user = 'your_database_user';
$db_pass = 'your_database_password';
$db_name = 'your_database_name';
```

**Replace with YOUR actual values:**

```php
$db_host = 'localhost';                              // Keep as 'localhost'
$db_user = 'u123456_vishvavirat_admin';             // YOUR username from Part 3
$db_pass = 'your_actual_password_here';              // YOUR password from Part 3
$db_name = 'u123456_vishvavirat_leads';             // YOUR database name from Part 3
```

### Step 5: Update Email Settings

Find this line (around line 16):

```php
$notification_email = 'viratagencies770@gmail.com';
```

**Make sure it's your correct email** - this is where you'll receive lead notifications.

### Step 6: Change Admin Panel Credentials

**IMPORTANT FOR SECURITY!**

Find these lines (around line 19-20):

```php
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'change_this_password');
```

**Change to secure credentials:**

```php
define('ADMIN_USERNAME', 'yourusername');        // Choose your own username
define('ADMIN_PASSWORD', 'YourStr0ngP@ssw0rd');  // Choose a STRONG password
```

**WRITE THESE DOWN - YOU'LL USE THESE TO LOGIN TO ADMIN PANEL!**

### Step 7: Update Site URL

Find this line (around line 23):

```php
define('SITE_URL', 'https://yourdomain.com');
```

**Replace with your actual domain:**

```php
define('SITE_URL', 'https://vishvaviratsecurity.com');  // Your actual domain
```

Or if using Hostinger temporary domain:

```php
define('SITE_URL', 'https://your-temp-domain.hostingersite.com');
```

### Step 8: Save the File
1. Click **"Save Changes"** or **"Save File"** button
2. Close the editor
3. ✅ Configuration complete!

---

## Part 6: Test Your Website

### Step 1: Visit Your Website
1. Open a new browser tab
2. Go to your domain: `https://yourdomain.com`
   - Or use the temporary domain Hostinger provided
3. Your website should load!

### Step 2: Test Contact Form
1. Go to the contact page: `https://yourdomain.com/contact.html`
2. Fill out the form with test data:
   - Name: Test User
   - Email: your-email@gmail.com (use your real email)
   - Phone: 9876543210
   - Service: Any service
   - Location: Mumbai
   - Message: This is a test submission
3. Click **"Send Message"**
4. You should see a **SUCCESS message**: "Thank you! Your inquiry has been submitted..."

### Step 3: Check Email
1. Check your email inbox: `viratagencies770@gmail.com`
2. You should receive an email with subject: **"New General Inquiry - VISHVAVIRAT SECURITY"**
3. If not in inbox, **check spam folder**
4. Email should contain all the test data you entered

### Step 4: Verify in Database
1. Go back to phpMyAdmin
2. Select your database
3. Click on **"contact_submissions"** table
4. Click **"Browse"** tab
5. You should see your test submission!
6. ✅ Forms are working!

---

## Part 7: Access Admin Panel

### Step 1: Go to Admin Panel
1. In your browser, go to: `https://yourdomain.com/admin/`
2. You'll see a login page with:
   - VISHVAVIRAT SECURITY logo
   - Username field
   - Password field

### Step 2: Login
1. Enter the **ADMIN_USERNAME** you set in config.php
2. Enter the **ADMIN_PASSWORD** you set in config.php
3. Click **"Login"**

### Step 3: View Dashboard
After login, you'll see:
- **Statistics boxes** showing:
  - Total Leads
  - New Leads
  - Contacted
  - Converted
  - Last 7 Days
  - Last 30 Days
- **Filter section** (filter by status, service, search)
- **Leads table** showing all submissions

### Step 4: View Lead Details
1. Find your test submission in the table
2. Click the **"View"** button
3. A popup opens showing:
   - All lead details
   - Contact information
   - Message
   - Form to update status
   - Field to add notes

### Step 5: Test Updating Status
1. Change status to **"Contacted"**
2. Add a note: "Test note - contacted customer"
3. Click **"Update Lead"**
4. Page reloads
5. Status should now show as "Contacted"
6. ✅ Admin panel working!

---

## Part 8: Test All Service Forms

Test each service page form to make sure all are working:

1. **Personal Bouncer**: `https://yourdomain.com/services/personal-bouncer.html`
2. **Government Security**: `https://yourdomain.com/services/government-security-guard.html`
3. **Driver**: `https://yourdomain.com/services/driver.html`
4. **Housekeeping**: `https://yourdomain.com/services/housekeeping.html`
5. **Gardener**: `https://yourdomain.com/services/gardener.html`
6. **Maid**: `https://yourdomain.com/services/maid.html`

For each page:
- Fill out the form
- Submit
- Check email
- Check admin panel

---

## Part 9: Enable SSL Certificate (HTTPS)

### Step 1: Go to SSL Section
1. In Hostinger hPanel
2. Look for **"SSL"** or **"Security"**
3. Click on it

### Step 2: Install SSL
1. Find your domain in the list
2. Click **"Install SSL"** or **"Activate SSL"**
3. Choose **"Free SSL"** (Let's Encrypt)
4. Click **"Install"**
5. Wait 5-10 minutes for installation

### Step 3: Verify HTTPS
1. Go to: `https://yourdomain.com` (with https://)
2. You should see a padlock icon in browser address bar
3. Click the padlock - should say "Connection is secure"
4. ✅ SSL working!

### Step 4: Force HTTPS (Optional)
To automatically redirect HTTP to HTTPS:

1. In File Manager, go to `public_html`
2. Create a new file named **".htaccess"** (include the dot at start)
3. Edit the file and add:

```apache
# Force HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Prevent directory browsing
Options -Indexes
```

4. Save the file
5. Now typing `http://yourdomain.com` will redirect to `https://yourdomain.com`

---

## Part 10: Final Checklist

Go through this checklist to make sure everything is working:

- [ ] Website loads at your domain
- [ ] All pages load correctly (home, about, contact, services, industries)
- [ ] All images are showing
- [ ] Navigation menu works
- [ ] Contact form submits successfully
- [ ] All 6 service forms submit successfully
- [ ] Email notifications arrive at viratagencies770@gmail.com
- [ ] Leads appear in database (check phpMyAdmin)
- [ ] Admin panel loads at /admin/
- [ ] Can login to admin panel
- [ ] Can view leads in admin panel
- [ ] Can update lead status
- [ ] SSL certificate is active (padlock in browser)
- [ ] Mobile responsive (test on phone)

---

## Common Issues & Solutions

### Issue 1: Forms Submit But No Email Received

**Solution:**
1. Check spam folder
2. Verify `$notification_email` in config.php is correct
3. Try a different email address to test
4. Check Hostinger email logs in hPanel

### Issue 2: Database Connection Error

**Solution:**
1. Verify credentials in config.php match exactly
2. Check if database name has prefix (like `u123456_`)
3. Make sure user is added to database with ALL PRIVILEGES
4. Try recreating database user

### Issue 3: Admin Panel Won't Login

**Solution:**
1. Verify username and password in config.php
2. Make sure you're using exact credentials (case-sensitive)
3. Clear browser cookies and try again
4. Check if config.php was saved properly

### Issue 4: "500 Internal Server Error"

**Solution:**
1. Check file permissions
   - Most files should be 644
   - Folders should be 755
2. Check .htaccess for errors
3. Check error logs in hPanel
4. Make sure config.php exists in api/ folder

### Issue 5: Images Not Loading

**Solution:**
1. Check if images folder uploaded correctly
2. Verify image paths in HTML are correct
3. Check if images have correct file extensions
4. Clear browser cache

### Issue 6: Can't Find Database in phpMyAdmin

**Solution:**
1. Make sure you clicked on the database name in left sidebar
2. Refresh phpMyAdmin page
3. Check if database was actually created in Databases section
4. Try logging out and back into phpMyAdmin

---

## How to Get Your Hostinger Domain

If you don't have a domain yet:

### Option 1: Use Hostinger Temporary Domain
1. Hostinger gives you a free temporary domain
2. Format: `something.hostingersite.com`
3. Find it in your hosting dashboard
4. Use this for testing before buying a real domain

### Option 2: Buy Domain from Hostinger
1. In hPanel, go to **"Domains"**
2. Click **"Register New Domain"**
3. Search for your desired domain (e.g., `vishvaviratsecurity.com`)
4. Purchase it
5. It will be automatically linked to your hosting

### Option 3: Transfer Existing Domain
1. If you bought domain elsewhere
2. Go to **"Domains"** in hPanel
3. Click **"Point Domain"**
4. Follow instructions to update nameservers

---

## Daily Usage - How to Check Leads

### Every Day:
1. Go to `https://yourdomain.com/admin/`
2. Login
3. Look at **"New Leads"** count
4. Click on each new lead to view details
5. Contact the customer
6. Update status to "Contacted"
7. Add notes about the conversation
8. If they become a customer, mark as "Converted"

### Weekly:
1. Export database backup from phpMyAdmin
2. Review all "Contacted" leads
3. Follow up with pending leads
4. Check email notifications are working

---

## Important Security Notes

### DO:
✅ Use strong admin password
✅ Keep database credentials secret
✅ Regularly backup database
✅ Update admin password every few months
✅ Check for suspicious submissions in database

### DON'T:
❌ Share admin panel credentials publicly
❌ Use "admin" and "password" as credentials
❌ Commit config.php to GitHub
❌ Leave default credentials unchanged
❌ Forget to backup database

---

## Need Help?

### Hostinger Support
- **24/7 Live Chat**: Available in hPanel
- **Email**: support@hostinger.com
- **Knowledge Base**: https://support.hostinger.com
- **Phone**: Check Hostinger website for number

### Where to Find Logs
1. **PHP Errors**: hPanel → Advanced → Error Logs
2. **Email Logs**: hPanel → Email → Email Logs
3. **Access Logs**: hPanel → Advanced → Access Logs

---

## What You've Achieved! 🎉

After completing this guide, you now have:

✅ Professional website deployed and live
✅ Working contact forms on all pages (7 forms total)
✅ Automatic email notifications for every lead
✅ Database storing all lead information
✅ Admin panel to manage and track leads
✅ SSL certificate (secure HTTPS)
✅ Fully functional lead management system

**You're ready to start receiving and managing customer inquiries!**

---

## Quick Reference Card

**Save This Information:**

| Item | Details |
|------|---------|
| **Website URL** | https://yourdomain.com |
| **Admin Panel** | https://yourdomain.com/admin/ |
| **Admin Username** | (what you set in config.php) |
| **Admin Password** | (what you set in config.php) |
| **Notification Email** | viratagencies770@gmail.com |
| **Database Host** | localhost |
| **Database Name** | u123456_vishvavirat_leads |
| **Database User** | u123456_vishvavirat_admin |
| **Database Password** | (what you created) |
| **phpMyAdmin** | Access via Databases in hPanel |
| **Hostinger Support** | 24/7 Live Chat in hPanel |

---

**Congratulations on deploying your website! 🚀**

Now start receiving leads and growing your business!
