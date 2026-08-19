-- MMV / RJC Corporate Center fresh database schema
CREATE DATABASE IF NOT EXISTS mmv_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mmv_db;

CREATE TABLE IF NOT EXISTS hr_users (
  hr_user_id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(50) NOT NULL UNIQUE,
  full_name VARCHAR(150) NOT NULL, email VARCHAR(100) NOT NULL UNIQUE, password_hash VARCHAR(255) NOT NULL,
  role ENUM('HR Admin','HR Personnel') NOT NULL DEFAULT 'HR Personnel',
  status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS applicants (
  applicant_id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(50) NULL UNIQUE,
  password_hash VARCHAR(255) NULL, surname VARCHAR(100) NOT NULL, first_name VARCHAR(100) NOT NULL,
  middle_name VARCHAR(100) NULL, date_of_birth DATE NULL, sex ENUM('Male','Female','Other','Prefer not to say') NULL,
  address TEXT NULL, phone VARCHAR(30) NULL, email VARCHAR(100) NOT NULL UNIQUE,
  education_level ENUM('Elementary','High School','Senior High School','Vocational','Associate','Bachelor''s','Master''s','Doctorate','Other') NULL,
  years_experience DECIMAL(5,2) NULL, skills TEXT NULL, certifications TEXT NULL,
  status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS job_postings (
  job_id INT AUTO_INCREMENT PRIMARY KEY, job_title VARCHAR(150) NOT NULL,
  employment_type VARCHAR(50) NULL, location VARCHAR(150) NULL, job_description TEXT NOT NULL,
  required_skills TEXT NULL, min_education_level ENUM('None','Elementary','High School','Senior High School','Vocational','Associate','Bachelor''s','Master''s','Doctorate','Other') NOT NULL DEFAULT 'None',
  min_years_experience DECIMAL(5,2) NOT NULL DEFAULT 0, required_certifications TEXT NULL,
  required_documents TEXT NULL, assessment_name VARCHAR(150) NULL,
  status ENUM('Active','Inactive','Closed') NOT NULL DEFAULT 'Active', posted_by INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_posting_hr FOREIGN KEY (posted_by) REFERENCES hr_users(hr_user_id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS job_applications (
  application_id INT AUTO_INCREMENT PRIMARY KEY, applicant_id INT NOT NULL, job_id INT NOT NULL,
  status ENUM('Submitted','Under Review','Screening','For Interview','Accepted','Rejected') NOT NULL DEFAULT 'Submitted',
  applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_application (applicant_id,job_id), KEY idx_application_status(status),
  CONSTRAINT fk_application_applicant FOREIGN KEY (applicant_id) REFERENCES applicants(applicant_id) ON DELETE CASCADE,
  CONSTRAINT fk_application_job FOREIGN KEY (job_id) REFERENCES job_postings(job_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS applicant_documents (
  document_id INT AUTO_INCREMENT PRIMARY KEY, applicant_id INT NOT NULL, application_id INT NULL,
  document_type VARCHAR(100) NOT NULL, original_name VARCHAR(255) NOT NULL, stored_name VARCHAR(255) NOT NULL,
  mime_type VARCHAR(100) NULL, file_size_bytes INT UNSIGNED NULL, ocr_text LONGTEXT NULL,
  verification_status ENUM('Pending','Complete','Needs Review','Incomplete','Unreadable') NOT NULL DEFAULT 'Pending',
  verification_notes TEXT NULL, checked_at DATETIME NULL, uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY idx_document_applicant(applicant_id), KEY idx_document_application(application_id),
  CONSTRAINT fk_document_applicant FOREIGN KEY (applicant_id) REFERENCES applicants(applicant_id) ON DELETE CASCADE,
  CONSTRAINT fk_document_application FOREIGN KEY (application_id) REFERENCES job_applications(application_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS screening_results (
  screening_result_id INT AUTO_INCREMENT PRIMARY KEY, application_id INT NOT NULL UNIQUE,
  classification ENUM('Qualified','Potentially Qualified','Rejected') NOT NULL,
  confidence DECIMAL(5,4) NULL, eligibility_passed TINYINT(1) NOT NULL DEFAULT 0,
  eligibility_notes TEXT NULL, feature_snapshot JSON NULL, model_version VARCHAR(100) NULL,
  screened_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_result_application FOREIGN KEY (application_id) REFERENCES job_applications(application_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS audit_logs (
  audit_id BIGINT AUTO_INCREMENT PRIMARY KEY, hr_user_id INT NULL, action VARCHAR(150) NOT NULL,
  entity_type VARCHAR(80) NOT NULL, entity_id INT NULL, details JSON NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_audit_hr FOREIGN KEY (hr_user_id) REFERENCES hr_users(hr_user_id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS assessments (
  assessment_id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(150) NOT NULL,
  assessment_type VARCHAR(80) NOT NULL DEFAULT 'Multiple Choice', question_count INT UNSIGNED NOT NULL DEFAULT 0,
  status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active', created_by INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_assessment_hr FOREIGN KEY (created_by) REFERENCES hr_users(hr_user_id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS system_settings (
  setting_key VARCHAR(100) PRIMARY KEY, setting_value TEXT NOT NULL, updated_by INT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_setting_hr FOREIGN KEY (updated_by) REFERENCES hr_users(hr_user_id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO system_settings (setting_key,setting_value) VALUES
  ('organization_name','RJC Corporate Center'),('default_application_status','Submitted'),
  ('document_check_required','Yes'),('screening_enabled','Yes'),('assessment_enabled','Yes')
ON DUPLICATE KEY UPDATE setting_value=setting_value;

-- OCR/document status checks record completeness, consistency, and document quality only.
-- They do not establish or verify legal authenticity.
