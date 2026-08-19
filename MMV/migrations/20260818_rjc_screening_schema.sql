-- RJC Corporate Center / MMV screening extension for an existing MAMOVA database.
-- Back up the database first. This migration assumes the existing tables are named
-- applicants, job_postings, and job_applications, as used by this application.

ALTER TABLE applicants
  ADD COLUMN IF NOT EXISTS education_level ENUM('Elementary','High School','Senior High School','Vocational','Associate','Bachelor''s','Master''s','Doctorate','Other') NULL,
  ADD COLUMN IF NOT EXISTS years_experience DECIMAL(5,2) NULL,
  ADD COLUMN IF NOT EXISTS certifications TEXT NULL;

ALTER TABLE job_postings
  ADD COLUMN IF NOT EXISTS min_education_level ENUM('None','Elementary','High School','Senior High School','Vocational','Associate','Bachelor''s','Master''s','Doctorate','Other') NOT NULL DEFAULT 'None',
  ADD COLUMN IF NOT EXISTS min_years_experience DECIMAL(5,2) NOT NULL DEFAULT 0,
  ADD COLUMN IF NOT EXISTS required_certifications TEXT NULL,
  ADD COLUMN IF NOT EXISTS required_documents TEXT NULL,
  ADD COLUMN IF NOT EXISTS assessment_name VARCHAR(150) NULL;

ALTER TABLE job_applications
  MODIFY COLUMN status ENUM('Submitted','Under Review','Screening','For Interview','Accepted','Rejected') NOT NULL DEFAULT 'Submitted';

CREATE TABLE IF NOT EXISTS applicant_documents (
  document_id INT AUTO_INCREMENT PRIMARY KEY,
  applicant_id INT NOT NULL,
  application_id INT NULL,
  document_type VARCHAR(100) NOT NULL,
  original_name VARCHAR(255) NOT NULL,
  stored_name VARCHAR(255) NOT NULL,
  mime_type VARCHAR(100) NULL,
  file_size_bytes INT UNSIGNED NULL,
  ocr_text LONGTEXT NULL,
  verification_status ENUM('Pending','Complete','Needs Review','Incomplete','Unreadable') NOT NULL DEFAULT 'Pending',
  verification_notes TEXT NULL,
  checked_at DATETIME NULL,
  uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY idx_document_applicant (applicant_id),
  KEY idx_document_application (application_id),
  CONSTRAINT fk_document_applicant FOREIGN KEY (applicant_id) REFERENCES applicants(applicant_id) ON DELETE CASCADE,
  CONSTRAINT fk_document_application FOREIGN KEY (application_id) REFERENCES job_applications(application_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS screening_results (
  screening_result_id INT AUTO_INCREMENT PRIMARY KEY,
  application_id INT NOT NULL UNIQUE,
  classification ENUM('Qualified','Potentially Qualified','Rejected') NOT NULL,
  confidence DECIMAL(5,4) NULL,
  eligibility_passed TINYINT(1) NOT NULL DEFAULT 0,
  eligibility_notes TEXT NULL,
  feature_snapshot JSON NULL,
  model_version VARCHAR(100) NULL,
  screened_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_result_application FOREIGN KEY (application_id) REFERENCES job_applications(application_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS assessments (
  assessment_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  assessment_type VARCHAR(80) NOT NULL DEFAULT 'Multiple Choice',
  question_count INT UNSIGNED NOT NULL DEFAULT 0,
  status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active',
  created_by INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_assessment_hr FOREIGN KEY (created_by) REFERENCES hr_users(hr_user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS system_settings (
  setting_key VARCHAR(100) PRIMARY KEY,
  setting_value TEXT NOT NULL,
  updated_by INT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_setting_hr FOREIGN KEY (updated_by) REFERENCES hr_users(hr_user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO system_settings (setting_key,setting_value) VALUES
  ('organization_name','RJC Corporate Center'),('default_application_status','Submitted'),
  ('document_check_required','Yes'),('screening_enabled','Yes'),('assessment_enabled','Yes')
ON DUPLICATE KEY UPDATE setting_value=setting_value;

-- Document checks assess completeness, internal consistency, and document quality
-- only. They do not establish or verify legal authenticity.
