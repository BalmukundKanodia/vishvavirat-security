# Hostinger Deployment Checklist

Use this checklist to ensure everything is set up correctly.

## Pre-Deployment

- [ ] Have Hostinger account credentials
- [ ] Have access to cPanel/hPanel
- [ ] Have domain configured (or know temporary domain)
- [ ] Have FTP credentials (optional)

## Upload Files

- [ ] Uploaded all HTML files to `public_html`
- [ ] Uploaded `css/` folder
- [ ] Uploaded `js/` folder
- [ ] Uploaded `images/` folder
- [ ] Uploaded `api/` folder
- [ ] Uploaded `admin/` folder
- [ ] Uploaded `.htaccess` files (if any)

## Database Setup

- [ ] Created MySQL database
- [ ] Created database user
- [ ] Added user to database with ALL PRIVILEGES
- [ ] Noted down database name
- [ ] Noted down username
- [ ] Noted down password
- [ ] Imported `api/database.sql` via phpMyAdmin
- [ ] Verified table `contact_submissions` exists

## Configuration

- [ ] Renamed `api/config.sample.php` to `api/config.php`
- [ ] Updated `$db_host` in config.php (usually 'localhost')
- [ ] Updated `$db_user` with actual username
- [ ] Updated `$db_pass` with actual password
- [ ] Updated `$db_name` with actual database name
- [ ] Updated `$notification_email` to `viratagencies770@gmail.com`
- [ ] Changed `ADMIN_USERNAME` to secure username
- [ ] Changed `ADMIN_PASSWORD` to strong password
- [ ] Updated `SITE_URL` with actual domain
- [ ] Saved config.php

## Testing

- [ ] Visited website - loads correctly
- [ ] Tested form submission on one service page
- [ ] Saw success message after form submission
- [ ] Received email notification at `viratagencies770@gmail.com`
- [ ] Checked phpMyAdmin - submission appears in database
- [ ] Visited `/admin/` - login page loads
- [ ] Logged into admin panel successfully
- [ ] Can see test submission in admin panel
- [ ] Can view lead details
- [ ] Can update lead status
- [ ] Tested form on ALL service pages

## Security

- [ ] Changed default admin username
- [ ] Changed default admin password  (strong password)
- [ ] Verified `api/.htaccess` exists (protects config.php)
- [ ] Config.php NOT accessible via browser
- [ ] Deleted or kept `config.sample.php` for reference

## SSL Certificate

- [ ] Installed SSL certificate (Free Let's Encrypt)
- [ ] Website accessible via HTTPS
- [ ] HTTP automatically redirects to HTTPS
- [ ] No mixed content warnings

## Email Configuration

- [ ] Email notifications working
- [ ] Emails NOT going to spam
- [ ] Email format looks professional
- [ ] Reply-to address is lead's email

## Final Checks

- [ ] All images loading correctly
- [ ] All CSS styles applying correctly
- [ ] All navigation links working
- [ ] Mobile responsive design working
- [ ] Forms working on all pages:
  - [ ] Personal Bouncer page
  - [ ] Government Security Guard page
  - [ ] Driver page
  - [ ] Housekeeping page
  - [ ] Gardener page
  - [ ] Maid page
- [ ] Admin panel accessible and functional
- [ ] Error logging working (check `logs/php-errors.log`)

## Documentation

- [ ] Read DEPLOYMENT_GUIDE.md
- [ ] Read README_LEAD_SYSTEM.md
- [ ] Saved admin panel credentials securely
- [ ] Saved database credentials securely
- [ ] Noted phpMyAdmin URL

## Backup

- [ ] Backed up database
- [ ] Backed up all files
- [ ] Set up automated backups in cPanel (optional)

## Training

- [ ] Know how to access admin panel
- [ ] Know how to view leads
- [ ] Know how to update lead status
- [ ] Know how to add notes to leads
- [ ] Know how to backup database
- [ ] Know how to contact Hostinger support

## Go Live

- [ ] Website is live
- [ ] DNS propagated (if new domain)
- [ ] Announced website to team
- [ ] Shared admin credentials with authorized personnel
- [ ] Monitoring lead submissions daily

---

## Quick Reference

**Admin Panel:** https://yourdomain.com/admin/  
**Admin Username:** (what you set in config.php)  
**Admin Password:** (what you set in config.php)  

**Email for Leads:** viratagencies770@gmail.com  

**Database:** Access via phpMyAdmin in Hostinger panel  

---

## After Deployment

- [ ] Test receive lead notification via email
- [ ] Respond to first real lead within 24 hours
- [ ] Check admin panel daily for new leads
- [ ] Backup database weekly
- [ ] Review lead statuses weekly
- [ ] Track conversion rates monthly

---

**Ready to go live? Double-check everything above! ✅**
