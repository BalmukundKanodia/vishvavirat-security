-- ============================================
-- VISHVAVIRAT SECURITY - Database Schema
-- ============================================
--
-- This SQL file creates the necessary database
-- tables for the contact form system
--
-- Usage:
-- 1. Create a database in Hostinger cPanel
-- 2. Import this file through phpMyAdmin or CLI
-- ============================================

-- Set charset and collation
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- ============================================
-- TABLE: contact_submissions
-- Stores all contact form submissions
-- ============================================

CREATE TABLE IF NOT EXISTS `contact_submissions` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `service` VARCHAR(255) NOT NULL,
    `organization` VARCHAR(255) DEFAULT NULL,
    `location` VARCHAR(255) DEFAULT NULL,
    `duration` VARCHAR(100) DEFAULT NULL,
    `message` TEXT DEFAULT NULL,
    `source_page` VARCHAR(500) DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` VARCHAR(500) DEFAULT NULL,
    `status` ENUM('new', 'contacted', 'in_progress', 'completed', 'archived') DEFAULT 'new',
    `notes` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_service` (`service`),
    INDEX `idx_status` (`status`),
    INDEX `idx_created_at` (`created_at`),
    INDEX `idx_email` (`email`),
    INDEX `idx_phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: admin_users (Optional - for future admin panel)
-- ============================================

CREATE TABLE IF NOT EXISTS `admin_users` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'manager', 'viewer') DEFAULT 'viewer',
    `is_active` BOOLEAN DEFAULT TRUE,
    `last_login` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_username` (`username`),
    INDEX `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: activity_log (Optional - for audit trail)
-- ============================================

CREATE TABLE IF NOT EXISTS `activity_log` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `admin_user_id` INT(11) UNSIGNED DEFAULT NULL,
    `action` VARCHAR(255) NOT NULL,
    `entity_type` VARCHAR(100) DEFAULT NULL,
    `entity_id` INT(11) UNSIGNED DEFAULT NULL,
    `details` TEXT DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_admin_user_id` (`admin_user_id`),
    INDEX `idx_entity` (`entity_type`, `entity_id`),
    INDEX `idx_created_at` (`created_at`),
    FOREIGN KEY (`admin_user_id`) REFERENCES `admin_users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- INSERT SAMPLE DATA (Optional - for testing)
-- ============================================

-- Sample submission (REMOVE THIS IN PRODUCTION)
-- INSERT INTO `contact_submissions` (
--     `name`, `phone`, `email`, `service`, `location`, `message`
-- ) VALUES (
--     'Test User', '9876543210', 'test@example.com',
--     'Personal Bouncer', 'Bangalore', 'This is a test inquiry'
-- );

-- ============================================
-- VIEWS FOR REPORTING
-- ============================================

-- View for service-wise inquiry count
CREATE OR REPLACE VIEW `service_inquiry_stats` AS
SELECT
    `service`,
    COUNT(*) as `total_inquiries`,
    SUM(CASE WHEN `status` = 'new' THEN 1 ELSE 0 END) as `new_inquiries`,
    SUM(CASE WHEN `status` = 'contacted' THEN 1 ELSE 0 END) as `contacted`,
    SUM(CASE WHEN `status` = 'completed' THEN 1 ELSE 0 END) as `completed`
FROM `contact_submissions`
GROUP BY `service`
ORDER BY `total_inquiries` DESC;

-- View for daily submissions
CREATE OR REPLACE VIEW `daily_submissions` AS
SELECT
    DATE(`created_at`) as `date`,
    COUNT(*) as `total_submissions`,
    GROUP_CONCAT(DISTINCT `service` SEPARATOR ', ') as `services`
FROM `contact_submissions`
GROUP BY DATE(`created_at`)
ORDER BY `date` DESC;

-- View for recent unprocessed inquiries
CREATE OR REPLACE VIEW `pending_inquiries` AS
SELECT
    `id`,
    `name`,
    `phone`,
    `email`,
    `service`,
    `location`,
    `created_at`
FROM `contact_submissions`
WHERE `status` = 'new'
ORDER BY `created_at` DESC;

-- ============================================
-- STORED PROCEDURES (Optional - for common operations)
-- ============================================

-- Procedure to update submission status
DELIMITER $$

CREATE PROCEDURE `update_submission_status`(
    IN submission_id INT,
    IN new_status VARCHAR(50),
    IN admin_notes TEXT
)
BEGIN
    UPDATE `contact_submissions`
    SET
        `status` = new_status,
        `notes` = CONCAT(IFNULL(`notes`, ''), '\n', NOW(), ': ', admin_notes),
        `updated_at` = NOW()
    WHERE `id` = submission_id;
END$$

DELIMITER ;

-- ============================================
-- GRANTS AND PERMISSIONS
-- ============================================

-- Note: Grant appropriate permissions to your database user
-- Example (replace 'your_db_user' with actual username):
-- GRANT SELECT, INSERT, UPDATE ON vishvavirat_security.* TO 'your_db_user'@'localhost';
-- FLUSH PRIVILEGES;

-- ============================================
-- INDEXES FOR PERFORMANCE OPTIMIZATION
-- ============================================

-- Additional indexes for common queries
ALTER TABLE `contact_submissions`
    ADD INDEX `idx_service_status` (`service`, `status`),
    ADD INDEX `idx_created_status` (`created_at`, `status`);

-- ============================================
-- CLEANUP ROUTINE (Optional - for old data)
-- ============================================

-- Event to archive old completed submissions (runs monthly)
-- Uncomment and adjust as needed

-- CREATE EVENT IF NOT EXISTS `archive_old_submissions`
-- ON SCHEDULE EVERY 1 MONTH
-- DO
-- UPDATE `contact_submissions`
-- SET `status` = 'archived'
-- WHERE `status` = 'completed'
-- AND `updated_at` < DATE_SUB(NOW(), INTERVAL 6 MONTH);

-- ============================================
-- COMPLETION MESSAGE
-- ============================================

SELECT 'Database schema created successfully!' as 'Status';
SELECT 'Remember to update api/config.php with your database credentials' as 'Next Step';
