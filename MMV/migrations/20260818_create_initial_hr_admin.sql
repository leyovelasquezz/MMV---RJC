-- Run after 20260818_mmv_full_schema.sql. Sign in once, then change this password.
INSERT INTO hr_users (username,full_name,email,password_hash,role,status)
VALUES ('hradmin','MMV HR Administrator','hradmin@rjc.local','$2y$10$G0rr7/CWo8ULfIMPgkt7nucSZf74rQ.HHZWAtlpS8lzqxplT7PXuq','HR Admin','Active')
ON DUPLICATE KEY UPDATE full_name=VALUES(full_name), email=VALUES(email), role=VALUES(role), status='Active';
-- Initial password: ChangeMe123!  Change it immediately after first login.
