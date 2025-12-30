-- ============================================
-- VISHVAVIRAT SECURITY Database Schema
-- ============================================
--
-- IMPORTANT: Before importing this file:
-- 1. Create database in Hostinger (e.g., u876271999_vv_leads)
-- 2. Open phpMyAdmin
-- 3. SELECT your database from the left sidebar
-- 4. THEN import this file
--
-- This file will create the tables in the selected database
-- ============================================

-- Contact Submissions Table
CREATE TABLE IF NOT EXISTS contact_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    location VARCHAR(255) DEFAULT NULL,
    message TEXT NOT NULL,
    service_type VARCHAR(100) DEFAULT 'General Inquiry',
    source_page VARCHAR(500) DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    status ENUM('new', 'contacted', 'converted', 'closed') DEFAULT 'new',
    notes TEXT DEFAULT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_phone (phone),
    INDEX idx_created_at (created_at),
    INDEX idx_status (status),
    INDEX idx_service_type (service_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create a view for quick stats
CREATE OR REPLACE VIEW submission_stats AS
SELECT 
    COUNT(*) as total_submissions,
    COUNT(CASE WHEN status = 'new' THEN 1 END) as new_leads,
    COUNT(CASE WHEN status = 'contacted' THEN 1 END) as contacted_leads,
    COUNT(CASE WHEN status = 'converted' THEN 1 END) as converted_leads,
    COUNT(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 END) as last_7_days,
    COUNT(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 END) as last_30_days,
    service_type,
    COUNT(*) as service_count
FROM contact_submissions
GROUP BY service_type;

-- Insert sample/test data (optional - remove in production)
-- INSERT INTO contact_submissions 
-- (name, email, phone, location, message, service_type, source_page, ip_address, created_at)
-- VALUES 
-- ('Test User', 'test@example.com', '9876543210', 'Mumbai', 'This is a test inquiry', 'Personal Bouncer', '/services/personal-bouncer.html', '127.0.0.1', NOW());
