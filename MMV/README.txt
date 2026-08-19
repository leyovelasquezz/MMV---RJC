MMV - RJC Corporate Center Recruitment Platform
==================================================

Frontend prototype focused on the HR module.

RUN:
1. Put the MMV folder inside XAMPP/htdocs (or another PHP server directory).
2. Start Apache.
3. Open: http://localhost/MMV/

CURRENTLY FUNCTIONAL:
- HR dashboard and module navigation
- Job posting create/update/delete
- Activate/deactivate job postings
- Applicant categories and search
- Applicant review modal
- Pass/fail screening action
- Assessment creation/deletion
- HR user creation, activation/deactivation, deletion
- Analytics sorting
- Print/Save as PDF through browser print dialog
- Settings saved to localStorage
- Mock database entity dashboard
- Browser localStorage for prototype job/settings data

NOT YET CONNECTED:
- MySQL/MariaDB
- Authentication
- Real OCR/document verification
- Real SVM model
- File uploads
- Server-side PDF generation
- Role-based access control
- Audit logs

Suggested production architecture:
Browser -> PHP API -> MySQL/MariaDB
                         -> Python OCR service
                         -> Python SVM screening service
