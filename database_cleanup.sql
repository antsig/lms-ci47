-- =====================================================
-- LMS Database Fresh Setup Script
-- =====================================================
-- This script will drop all existing tables and prepare
-- for fresh migration run
-- =====================================================

-- Disable foreign key checks temporarily
SET FOREIGN_KEY_CHECKS = 0;

-- Drop all tables if they exist
DROP TABLE IF EXISTS `wishlists`;
DROP TABLE IF EXISTS `ratings`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `enrollments`;
DROP TABLE IF EXISTS `lessons`;
DROP TABLE IF EXISTS `sections`;
DROP TABLE IF EXISTS `courses`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `migrations`;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- Show success message
SELECT 'All tables dropped successfully! Now run: php spark migrate' AS Status;
