-- Tạo tài khoản cho tất cả 6 roles
-- Mật khẩu: webie@2025

-- 1. User (Lead) - Role ID: 1
INSERT INTO users (role_name, role_id, full_name, email, mobile, password, status, created_at, updated_at) 
VALUES ('user', 1, 'Webie User', 'webie.user@demo.com', '0900000001', '$2y$10$KWJcbLuAnPAgf2w3OFax8./W652/NVoW1wFjztn6S..F5LnIFlWoy', 'active', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- 2. Student - Role ID: 2
INSERT INTO users (role_name, role_id, full_name, email, mobile, password, status, created_at, updated_at) 
VALUES ('student', 2, 'Webie Student', 'webie.student@demo.com', '0900000002', '$2y$10$KWJcbLuAnPAgf2w3OFax8./W652/NVoW1wFjztn6S..F5LnIFlWoy', 'active', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- 3. Teacher - Role ID: 3
INSERT INTO users (role_name, role_id, full_name, email, mobile, password, status, created_at, updated_at) 
VALUES ('teacher', 3, 'Webie Teacher', 'webie.teacher@demo.com', '0900000003', '$2y$10$KWJcbLuAnPAgf2w3OFax8./W652/NVoW1wFjztn6S..F5LnIFlWoy', 'active', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- 4. Admin - Role ID: 4
INSERT INTO users (role_name, role_id, full_name, email, mobile, password, status, created_at, updated_at) 
VALUES ('admin', 4, 'Webie Admin', 'webie.admin@demo.com', '0900000004', '$2y$10$KWJcbLuAnPAgf2w3OFax8./W652/NVoW1wFjztn6S..F5LnIFlWoy', 'active', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- 5. Manager - Role ID: 5
INSERT INTO users (role_name, role_id, full_name, email, mobile, password, status, created_at, updated_at) 
VALUES ('manager', 5, 'Webie Manager', 'webie.manager@demo.com', '0900000005', '$2y$10$KWJcbLuAnPAgf2w3OFax8./W652/NVoW1wFjztn6S..F5LnIFlWoy', 'active', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- 6. CEO - Role ID: 6
INSERT INTO users (role_name, role_id, full_name, email, mobile, password, status, created_at, updated_at) 
VALUES ('ceo', 6, 'Webie CEO', 'webie.ceo@demo.com', '0900000006', '$2y$10$KWJcbLuAnPAgf2w3OFax8./W652/NVoW1wFjztn6S..F5LnIFlWoy', 'active', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- ====================================
-- THÔNG TIN ĐĂNG NHẬP
-- ====================================
-- Mật khẩu cho TẤT CẢ tài khoản: webie@2025
--
-- User (Lead):      webie.user@demo.com
-- Student:          webie.student@demo.com
-- Teacher:          webie.teacher@demo.com
-- Admin:            webie.admin@demo.com
-- Manager:          webie.manager@demo.com
-- CEO:              webie.ceo@demo.com
