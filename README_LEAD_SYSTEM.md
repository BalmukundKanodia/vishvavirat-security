# VISHVAVIRAT SECURITY - Lead Management System

## Overview

This lead management system captures all form submissions from your website, stores them in a database, sends email notifications, and provides an admin panel to manage leads.

## Features

✅ **Form Handling**
- Validates all user inputs
- Prevents spam and duplicate submissions
- Sanitizes data for security
- Works with all service inquiry forms

✅ **Email Notifications**
- Instant email alerts for new leads
- Beautifully formatted HTML emails
- Includes all lead details
- Reply directly from email

✅ **Database Storage**
- All leads stored securely in MySQL
- Tracks submission date, source page, IP address
- Maintains lead status and notes
- Easy to export for CRM integration

✅ **Admin Panel**
- Clean, professional interface
- Filter by status, service type
- Search functionality
- Update lead status
- Add internal notes
- View detailed lead information
- Responsive design for mobile access

## System Components

### 1. Frontend (JavaScript)
**File:** `js/main-new.js`
- Handles form submission
- Validates user input before sending
- Shows success/error messages
- Prevents duplicate submissions

### 2. Backend (PHP)
**File:** `api/contact.php`
- Receives form data
- Validates and sanitizes input
- Stores in database
- Sends email notifications
- Returns JSON response

### 3. Configuration
**File:** `api/config.php`
- Database credentials
- Email settings
- Admin panel credentials
- Site configuration

### 4. Database
**File:** `api/database.sql`
- Table structure for storing leads
- Indexes for fast queries
- Views for statistics

### 5. Admin Panel
**File:** `admin/index.php`
- View all leads
- Filter and search
- Update lead status
- Add notes
- Export capabilities (can be added)

## Form Fields Captured

| Field | Required | Description |
|-------|----------|-------------|
| Name | Yes | Lead's full name |
| Email | Yes | Contact email |
| Phone | Yes | 10-digit mobile number |
| Location | No | City/area |
| Message | Yes | Requirements/inquiry details |
| Service Type | Auto | Which service form was submitted from |
| Source Page | Auto | Which page the form was on |
| IP Address | Auto | For spam prevention |
| Timestamp | Auto | When submitted |

## Lead Statuses

- **New** - Fresh submission, not yet contacted
- **Contacted** - You've reached out to the lead
- **Converted** - Lead became a customer
- **Closed** - Lead closed (not interested, etc.)

## Security Features

1. **Input Validation**
   - Email format validation
   - Phone number validation (Indian format)
   - XSS prevention (sanitization)
   - SQL injection prevention (prepared statements)

2. **Spam Prevention**
   - CSRF token validation
   - Duplicate submission blocking (5 min cooldown)
   - IP tracking

3. **Admin Panel Security**
   - Password-protected login
   - Session-based authentication
   - Configurable credentials
   - Optional .htaccess protection

4. **File Security**
   - Config files protected via .htaccess
   - Error logging to files (not displayed)
   - Secure database connections

## Email Notifications

When a lead submits a form:

1. **Immediate Email Sent To:** `viratagencies770@gmail.com`
2. **Email Contains:**
   - Lead name and contact details
   - Service type requested
   - Full message/requirements
   - Location
   - Source page
   - Submission ID for reference
   - Timestamp

3. **Email Format:**
   - Professional HTML template
   - Clickable phone/email links
   - Branded header
   - Easy to forward to team members

## Database Schema

### Table: `contact_submissions`

```sql
- id (Primary Key)
- name
- email
- phone
- location
- message
- service_type
- source_page
- ip_address
- user_agent
- status (new/contacted/converted/closed)
- notes (internal team notes)
- created_at
- updated_at
```

## Admin Panel Features

### Dashboard Stats
- Total leads
- New leads count
- Contacted leads
- Converted leads
- Last 7 days submissions
- Last 30 days submissions

### Filters
- By status
- By service type
- Search by name/email/phone
- Date range filtering (can be added)

### Actions
- View full lead details
- Update status
- Add internal notes
- Click to email
- Click to call

## How to Use the Admin Panel

1. **Access**
   - URL: https://yourdomain.com/admin/
   - Login with credentials from config.php

2. **Daily Workflow**
   - Login to admin panel
   - Check "New" leads
   - Click "View" to see details
   - Contact the lead (email/phone)
   - Update status to "Contacted"
   - Add notes about conversation
   - Mark as "Converted" if they become a customer

3. **Weekly Review**
   - Review "Contacted" leads
   - Follow up with pending leads
   - Close leads that aren't interested
   - Check conversion rate

## Customization Options

### Change Admin Credentials
Edit `api/config.php`:
```php
define('ADMIN_USERNAME', 'your_username');
define('ADMIN_PASSWORD', 'your_password');
```

### Change Notification Email
Edit `api/config.php`:
```php
$notification_email = 'your@email.com';
```

### Add More Status Types
Edit `api/database.sql` and `admin/index.php`:
```sql
ALTER TABLE contact_submissions 
MODIFY status ENUM('new', 'contacted', 'converted', 'closed', 'follow_up');
```

### Export Leads to CSV
You can add an export function in admin panel or use phpMyAdmin:
- Select `contact_submissions` table
- Click "Export"
- Choose CSV format
- Open in Excel/Google Sheets

## Integration with Other Tools

### CRM Integration
Export data as CSV and import to:
- Zoho CRM
- HubSpot
- Salesforce
- Freshsales

### Email Marketing
Export emails to:
- Mailchimp
- SendinBlue
- Constant Contact

### Google Sheets (Advanced)
Can be configured to auto-send leads to Google Sheets using:
- Google Apps Script
- Zapier integration
- Make (formerly Integromat)

## Backup Recommendations

### Database Backup
**Frequency:** Weekly or daily

**Method 1: phpMyAdmin**
1. Login to phpMyAdmin
2. Select database
3. Click Export
4. Download SQL file
5. Store securely

**Method 2: Automated (via cPanel)**
1. Go to "Backup" in cPanel
2. Set up automated backups
3. Configure backup frequency
4. Set backup destination

### Keep Backups For
- 30 days minimum
- Before any system changes
- Before software updates

## Troubleshooting

### Form Not Submitting
- Check browser console for errors
- Verify `api/contact.php` is accessible
- Check database connection in config.php

### Email Not Receiving
- Check spam folder
- Verify email in config.php
- Check server mail logs in cPanel
- Consider SMTP instead of PHP mail()

### Admin Panel Login Issues
- Verify credentials in config.php
- Clear browser cookies
- Check if sessions are enabled on server

### Database Errors
- Verify config.php credentials
- Check if database exists
- Verify table was created (run database.sql)

## Performance Notes

- Database is indexed for fast queries
- Can handle thousands of leads
- Pagination prevents slow page loads
- Consider archiving old leads after 1 year

## Future Enhancements (Optional)

- [ ] Email response tracking
- [ ] Lead scoring
- [ ] Auto-assignment to team members
- [ ] SMS notifications
- [ ] WhatsApp integration
- [ ] Calendar integration for follow-ups
- [ ] Advanced analytics and reports
- [ ] Export to PDF
- [ ] Bulk actions (delete, update status)
- [ ] Lead source tracking
- [ ] Conversion funnel analytics

## Support

For technical issues:
1. Check error logs: `logs/php-errors.log`
2. Check server error logs in cPanel
3. Refer to DEPLOYMENT_GUIDE.md
4. Contact Hostinger support

## License

Proprietary - VISHVAVIRAT SECURITY & FACILITY INDIA PVT LTD

---

**All leads are valuable. Respond within 24 hours for best conversion rates!**
