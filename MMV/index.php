<?php
require_once __DIR__ . '/auth.php';
require_hr_page('hr_login.php');
$hrName = htmlspecialchars((string) ($_SESSION['hr_name'] ?? 'HR User'), ENT_QUOTES, 'UTF-8');
$hrRole = htmlspecialchars((string) $_SESSION['hr_role'], ENT_QUOTES, 'UTF-8');
$hrInitials = htmlspecialchars(strtoupper(implode('', array_map(static fn($part) => substr($part, 0, 1), array_filter(explode(' ', (string) ($_SESSION['hr_name'] ?? 'HR User')))))), ENT_QUOTES, 'UTF-8');
/*
/*
 * MMV - RJC Corporate Center Recruitment Platform
 * Frontend prototype / single-file PHP application.
 *
 * No database is required for this prototype.
 * Data is stored in JavaScript arrays/localStorage so the UI can be tested immediately.
 *
 * Suggested future stack:
 *   Frontend: HTML/CSS/JavaScript
 *   Backend: PHP + MySQL/MariaDB
 *   OCR/Document Verification: Python service/API
 *   SVM Screening: Python/scikit-learn service/API
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MMV | RJC Corporate Center Recruitment</title>
<style>
:root{
  --navy:#0b1f3a; --navy2:#102a4c; --blue:#1d5fd1; --blue2:#eaf2ff;
  --bg:#f5f7fb; --card:#fff; --text:#172033; --muted:#697386;
  --line:#e6eaf0; --green:#15803d; --green-bg:#eaf8ef;
  --orange:#b45309; --orange-bg:#fff5df; --red:#b42318; --red-bg:#fff0ef;
  --shadow:0 8px 28px rgba(15,31,58,.07); --radius:14px;
}
/* Visual refinement pass: stronger hierarchy, quieter surfaces, and clearer actions. */
body{background:linear-gradient(180deg,#f8faff 0,#f3f6fb 100%)}
.sidebar{width:278px;background:linear-gradient(180deg,#071a31 0%,#0b274a 55%,#0b1f3a 100%);box-shadow:12px 0 35px rgba(11,31,58,.08)}
.brand{padding:25px 22px}.brand-mark{width:43px;height:43px;border-radius:13px;background:linear-gradient(145deg,#fff,#dce8ff);box-shadow:0 8px 22px #020d1c44}.brand h1{font-size:15px;letter-spacing:-.02em}.nav{padding:20px 14px}.nav-section{padding:16px 10px 8px;font-size:9px;letter-spacing:1.5px}.nav button{padding:12px 13px;border:1px solid transparent;transition:.18s ease}.nav button:hover{background:#ffffff12;transform:translateX(2px)}.nav button.active{border-color:#ffffff18;background:linear-gradient(90deg,#4168bf,#284f99);box-shadow:0 8px 20px #06152c33}.sidebar-footer{padding:17px}.sidebar-footer .btn{background:#ffffff12;border-color:#ffffff20;color:#fff}.sidebar-footer .btn:hover{background:#ffffff20}
.main{margin-left:278px}.topbar{height:76px;padding:0 38px;background:#fffffff0;backdrop-filter:blur(14px)}.content{padding:34px 38px 60px;max-width:1600px}.page-title h2{font-size:27px;letter-spacing:-.04em}.page-title p{font-size:13px}.btn{border-radius:10px;padding:10px 16px;transition:.18s ease}.btn-primary{background:linear-gradient(135deg,#3766d3,#1d4fb5);box-shadow:0 8px 18px #1d5fd126}.btn-primary:hover{transform:translateY(-1px);box-shadow:0 13px 24px #1d5fd134}.btn-secondary{background:#fff}.cards{gap:18px}.stat{border:1px solid #e5eaf2;border-radius:18px;padding:20px;box-shadow:0 8px 25px rgba(16,42,76,.045)}.stat:hover{box-shadow:0 14px 30px rgba(16,42,76,.08)}.stat h3{font-size:30px;letter-spacing:-.045em}.stat-icon{width:39px;height:39px;border-radius:12px}.panel{border-color:#e5eaf2;border-radius:18px;box-shadow:0 8px 25px rgba(16,42,76,.045)}.panel-head{padding:19px 21px}.panel-body{padding:21px}.quick{border-radius:14px;padding:18px;transition:.18s ease}.quick:hover{border-color:#a9c1ee;box-shadow:0 10px 24px #1d5fd112;transform:translateY(-3px)}.quick .qicon{width:38px;height:38px;display:grid;place-items:center;border-radius:11px;background:#eef4ff;color:#2760c8}.table th{background:#f8faff;padding:13px 15px}.table td{padding:14px 15px}.table tbody tr{transition:.15s}.table tbody tr:hover{background:#f7faff}.field,textarea,select,input{border-radius:10px;border-color:#d9e1ec}.field:focus,textarea:focus,select:focus,input:focus{border-color:#6d8ee0;box-shadow:0 0 0 4px #1d5fd11a}.modal{backdrop-filter:blur(3px)}.modal-box{border-radius:20px;box-shadow:0 28px 75px #071a314d}.modal-head{padding:20px 22px}.modal-body{padding:22px}.modal-foot{padding:17px 22px}.toast{border-radius:12px;box-shadow:0 18px 35px #071a3140}
*{box-sizing:border-box} body{margin:0;font-family:Inter,Segoe UI,Arial,sans-serif;background:var(--bg);color:var(--text)}
button,input,select,textarea{font:inherit} button{cursor:pointer}
.app{display:flex;min-height:100vh}.sidebar{width:260px;background:var(--navy);color:#fff;position:fixed;left:0;top:0;bottom:0;display:flex;flex-direction:column;z-index:10}
.brand{padding:22px 20px;border-bottom:1px solid rgba(255,255,255,.1);display:flex;align-items:center;gap:12px}
.brand-mark{width:40px;height:40px;border-radius:10px;background:#fff;color:var(--navy);display:grid;place-items:center;font-weight:800}
.brand h1{font-size:16px;margin:0}.brand small{opacity:.65;font-size:11px}
.nav{padding:16px 12px;overflow:auto}.nav-section{font-size:10px;text-transform:uppercase;letter-spacing:1.2px;opacity:.45;padding:14px 10px 7px}
.nav button{width:100%;background:none;border:0;color:#dbe6f7;text-align:left;padding:11px 12px;border-radius:9px;margin:2px 0;display:flex;gap:11px;align-items:center}
.nav button:hover,.nav button.active{background:rgba(255,255,255,.11);color:#fff}.icon{width:20px;text-align:center}
.sidebar-footer{margin-top:auto;padding:14px;border-top:1px solid rgba(255,255,255,.1)}
.user-mini{display:flex;align-items:center;gap:10px}.avatar{width:36px;height:36px;border-radius:50%;background:#dbeafe;color:var(--navy);display:grid;place-items:center;font-weight:700}
.user-mini b{display:block;font-size:13px}.user-mini span{font-size:11px;opacity:.6}
.main{margin-left:260px;flex:1;min-width:0}.topbar{height:70px;background:#fff;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;padding:0 30px;position:sticky;top:0;z-index:5}
.breadcrumb{font-size:13px;color:var(--muted)}.breadcrumb b{color:var(--text)}.top-actions{display:flex;align-items:center;gap:12px}
.icon-btn{border:1px solid var(--line);background:#fff;width:38px;height:38px;border-radius:9px}.content{padding:28px 30px 50px;max-width:1500px;margin:auto}
.page-title{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:22px}.page-title h2{margin:0 0 5px;font-size:25px}.page-title p{margin:0;color:var(--muted);font-size:13px}
.btn{border:0;border-radius:9px;padding:10px 15px;font-weight:600;display:inline-flex;align-items:center;gap:7px}.btn-primary{background:var(--blue);color:#fff}.btn-secondary{background:#fff;border:1px solid var(--line);color:var(--text)}.btn-danger{background:#fff1f0;color:var(--red)}.btn-success{background:var(--green);color:#fff}
.btn-sm{padding:7px 10px;font-size:12px}
.cards{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:22px}.stat{background:#fff;border:1px solid var(--line);border-radius:var(--radius);padding:18px;box-shadow:var(--shadow)}.stat-top{display:flex;justify-content:space-between;align-items:center}.stat-label{font-size:12px;color:var(--muted)}.stat-icon{width:35px;height:35px;border-radius:9px;background:var(--blue2);color:var(--blue);display:grid;place-items:center}.stat h3{font-size:27px;margin:13px 0 4px}.stat small{font-size:11px;color:var(--muted)}
.grid-2{display:grid;grid-template-columns:1.5fr 1fr;gap:18px}.panel{background:#fff;border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden}.panel-head{padding:17px 19px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between}.panel-head h3{margin:0;font-size:15px}.panel-body{padding:18px}
.quick-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}.quick{border:1px solid var(--line);border-radius:11px;padding:16px;text-align:left;background:#fff;transition:.15s}.quick:hover{border-color:#b7c9e8;transform:translateY(-1px)}.quick .qicon{font-size:22px;margin-bottom:10px}.quick b{display:block;font-size:13px}.quick span{font-size:11px;color:var(--muted)}
.table-wrap{overflow:auto}.table{width:100%;border-collapse:collapse;min-width:700px}.table th,.table td{padding:12px 15px;border-bottom:1px solid var(--line);text-align:left;font-size:12px}.table th{font-size:10px;text-transform:uppercase;letter-spacing:.6px;color:var(--muted);background:#fafbfc}.table tr:last-child td{border-bottom:0}.status{display:inline-flex;padding:5px 8px;border-radius:999px;font-size:10px;font-weight:700}.status.active,.status.passed{background:var(--green-bg);color:var(--green)}.status.pending{background:var(--orange-bg);color:var(--orange)}.status.failed,.status.inactive{background:var(--red-bg);color:var(--red)}.status.review{background:var(--blue2);color:var(--blue)}
.empty{padding:35px;text-align:center;color:var(--muted);font-size:13px}.empty strong{display:block;color:var(--text);margin-bottom:5px}
.toolbar{display:flex;gap:9px;flex-wrap:wrap;margin-bottom:15px}.search{min-width:240px;flex:1}.field,textarea,select,input{border:1px solid #dce2ea;border-radius:8px;padding:10px 11px;outline:none;background:#fff;color:var(--text)}input:focus,select:focus,textarea:focus{border-color:#7aa3e8;box-shadow:0 0 0 3px #eaf2ff}.field-label{font-size:11px;font-weight:700;margin-bottom:6px;display:block}.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:15px}.form-group.full{grid-column:1/-1}.check-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:9px}.check{border:1px solid var(--line);border-radius:8px;padding:9px;font-size:12px}.check input{margin-right:7px}
.progress{height:7px;background:#edf0f4;border-radius:99px;overflow:hidden}.progress i{display:block;height:100%;background:var(--blue);border-radius:99px}
.bar-row{display:grid;grid-template-columns:150px 1fr 45px;align-items:center;gap:10px;margin:13px 0;font-size:12px}.bar{height:9px;background:#edf0f4;border-radius:99px;overflow:hidden}.bar i{display:block;height:100%;background:var(--blue)}
.modal{display:none;position:fixed;inset:0;background:rgba(5,18,37,.45);z-index:50;align-items:center;justify-content:center;padding:20px}.modal.show{display:flex}.modal-box{width:min(720px,100%);max-height:90vh;overflow:auto;background:#fff;border-radius:15px;box-shadow:0 25px 70px rgba(0,0,0,.2)}.modal-head{padding:18px 20px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between}.modal-head h3{margin:0;font-size:17px}.modal-body{padding:20px}.modal-foot{padding:15px 20px;border-top:1px solid var(--line);display:flex;justify-content:flex-end;gap:9px}
.notice{padding:12px 14px;border-radius:9px;background:#f5f8ff;border:1px solid #dce8ff;color:#315a9d;font-size:12px;margin-bottom:15px}
.section{display:none}.section.active{display:block}.actions{display:flex;gap:5px}.muted{color:var(--muted)}.mini-list{display:flex;flex-direction:column;gap:11px}.mini-item{display:flex;justify-content:space-between;align-items:center;padding-bottom:11px;border-bottom:1px solid var(--line)}.mini-item:last-child{border:0;padding-bottom:0}.mini-item b{font-size:12px}.mini-item span{font-size:10px;color:var(--muted)}
.toast{position:fixed;right:25px;bottom:25px;background:#102a4c;color:#fff;padding:12px 15px;border-radius:9px;box-shadow:var(--shadow);display:none;z-index:100;font-size:12px}.toast.show{display:block}
@media(max-width:1000px){.sidebar{width:76px}.brand div:not(.brand-mark),.nav span:not(.icon),.sidebar-footer .user-mini div{display:none}.main{margin-left:76px}.cards{grid-template-columns:repeat(2,1fr)}.grid-2{grid-template-columns:1fr}}
@media(max-width:650px){.topbar{padding:0 15px}.content{padding:20px 15px}.cards,.form-grid,.quick-grid,.check-grid{grid-template-columns:1fr}.page-title{gap:10px;flex-direction:column}}
.status.active,.status.qualified,.status.accepted{background:var(--green-bg);color:var(--green)}.status.pending,.status.submitted{background:var(--orange-bg);color:var(--orange)}.status.rejected,.status.inactive{background:var(--red-bg);color:var(--red)}.status.review,.status.screening,.status.potentially-qualified,.status.for-interview{background:var(--blue2);color:var(--blue)}
</style>
</head>
<body>
<div class="app">
<aside class="sidebar">
  <div class="brand"><div class="brand-mark">MMV</div><div><h1>RJC Recruitment</h1><small>HR Management Portal</small></div></div>
  <nav class="nav">
    <div class="nav-section">Main</div>
    <button class="nav-btn active" data-section="dashboard"><span class="icon">▦</span><span>HR Dashboard</span></button>
    <div class="nav-section">Recruitment</div>
    <button class="nav-btn" data-section="jobs"><span class="icon">▤</span><span>Job Postings</span></button>
    <button class="nav-btn" data-section="applicants"><span class="icon">♙</span><span>Applicant Management</span></button>
    <button class="nav-btn" data-section="assessments"><span class="icon">✓</span><span>Assessments</span></button>
    <div class="nav-section">Reports & Data</div>
    <button class="nav-btn" data-section="analytics"><span class="icon">◒</span><span>Data Analytics</span></button>
    <button class="nav-btn" data-section="database"><span class="icon">▥</span><span>Database Management</span></button>
    <div class="nav-section">Administration</div>
    <button class="nav-btn" data-section="users"><span class="icon">♙</span><span>User Management</span></button>
    <button class="nav-btn" data-section="settings"><span class="icon">⚙</span><span>Settings</span></button>
  </nav>
  <div class="sidebar-footer"><div class="user-mini"><div class="avatar"><?= $hrInitials ?: 'HR' ?></div><div><b><?= $hrName ?></b><span><?= $hrRole ?></span></div></div><button class="btn btn-secondary" style="width:100%;justify-content:center;margin-top:12px" onclick="logoutHr()">Sign out</button></div>
</aside>

<main class="main">
<header class="topbar"><div class="breadcrumb">RJC Corporate Center / <b id="crumb">HR Dashboard</b></div><div class="top-actions"><button class="icon-btn" onclick="showToast('No new notifications')">🔔</button><button class="icon-btn" onclick="showToast('Profile menu is a frontend placeholder')">HR</button></div></header>

<div class="content">
<section id="dashboard" class="section active">
  <div class="page-title"><div><h2>HR Dashboard</h2><p>Recruitment overview and current screening activity.</p></div><button class="btn btn-primary" onclick="openJobModal()">＋ Create Job Posting</button></div>
  <div class="cards">
    <div class="stat"><div class="stat-top"><span class="stat-label">Active Job Postings</span><span class="stat-icon">▤</span></div><h3 id="statJobs">3</h3><small>Currently accepting applications</small></div>
    <div class="stat"><div class="stat-top"><span class="stat-label">Total Applicants</span><span class="stat-icon">♙</span></div><h3 id="statApplicants">24</h3><small>Across all job postings</small></div>
    <div class="stat"><div class="stat-top"><span class="stat-label">Pending Screenings</span><span class="stat-icon">◷</span></div><h3 id="statPending">8</h3><small>Awaiting HR review</small></div>
    <div class="stat"><div class="stat-top"><span class="stat-label">Passed Screening</span><span class="stat-icon">✓</span></div><h3 id="statPassed">11</h3><small>Eligible for next stage</small></div>
  </div>
  <div class="grid-2">
    <div class="panel"><div class="panel-head"><h3>Recruitment Modules</h3></div><div class="panel-body"><div class="quick-grid">
      <button class="quick" onclick="go('jobs')"><div class="qicon">▤</div><b>Job Posting</b><span>Create and manage vacancies</span></button>
      <button class="quick" onclick="go('applicants')"><div class="qicon">♙</div><b>Applicant Management</b><span>Review and screen applications</span></button>
      <button class="quick" onclick="go('analytics')"><div class="qicon">◒</div><b>Data Analytics</b><span>View recruitment statistics</span></button>
      <button class="quick" onclick="go('database')"><div class="qicon">▥</div><b>Database Management</b><span>View stored recruitment data</span></button>
      <button class="quick" onclick="go('assessments')"><div class="qicon">✓</div><b>Assessment Management</b><span>Manage screening assessments</span></button>
      <button class="quick" onclick="go('users')"><div class="qicon">♙</div><b>User Management</b><span>Manage HR accounts</span></button>
    </div></div></div>
    <div class="panel"><div class="panel-head"><h3>Screening Summary</h3><button class="btn btn-sm btn-secondary" onclick="go('analytics')">View Analytics</button></div><div class="panel-body">
      <div class="bar-row"><span>Passed</span><div class="bar"><i style="width:46%"></i></div><b>11</b></div>
      <div class="bar-row"><span>Pending</span><div class="bar"><i style="width:33%"></i></div><b>8</b></div>
      <div class="bar-row"><span>Failed</span><div class="bar"><i style="width:21%"></i></div><b>5</b></div>
      <div class="notice">SVM screening results shown here are placeholders for the frontend prototype. The production version should receive results from the SVM screening service.</div>
    </div></div>
  </div>
  <div class="panel" style="margin-top:18px"><div class="panel-head"><h3>Recent Applications</h3><button class="btn btn-sm btn-secondary" onclick="go('applicants')">Manage Applicants</button></div><div class="table-wrap"><table class="table"><thead><tr><th>Applicant</th><th>Position</th><th>Application Date</th><th>Screening Result</th><th>Status</th></tr></thead><tbody id="recentApplicants"></tbody></table></div></div>
</section>

<section id="jobs" class="section">
  <div class="page-title"><div><h2>Job Posting Management</h2><p>Create, update, activate, deactivate, and delete recruitment postings.</p></div><button class="btn btn-primary" onclick="openJobModal()">＋ New Job Posting</button></div>
  <div class="panel"><div class="panel-head"><h3>Job Postings</h3><div class="toolbar" style="margin:0"><input class="field" id="jobSearch" placeholder="Search job title..." oninput="renderJobs()"></div></div><div class="table-wrap"><table class="table"><thead><tr><th>Job Title</th><th>Requirements</th><th>Assessment</th><th>Applications</th><th>Status</th><th>Actions</th></tr></thead><tbody id="jobsTable"></tbody></table></div></div>
</section>

<section id="applicants" class="section">
  <div class="page-title"><div><h2>Applicant Management</h2><p>Review application records and manage the screening workflow.</p></div></div>
  <div class="toolbar"><button class="btn btn-secondary filter-app active" onclick="setApplicantFilter('all',this)">All</button><button class="btn btn-secondary filter-app" onclick="setApplicantFilter('Screening',this)">In Screening</button><button class="btn btn-secondary filter-app" onclick="setApplicantFilter('Completed Screening',this)">Classified</button><select id="appJobFilter" onchange="renderApplicants()"><option value="">All Job Postings</option></select><select id="appClassificationFilter" onchange="renderApplicants()"><option value="">All Classifications</option><option>Qualified</option><option>Potentially Qualified</option><option>Rejected</option></select><select id="appDocumentFilter" onchange="renderApplicants()"><option value="">All Document Checks</option><option>Complete</option><option>Needs Review</option><option>Incomplete</option><option>Unreadable</option><option>Pending</option></select><input class="field search" id="appSearch" placeholder="Search applicant..." oninput="renderApplicants()"></div>
  <div class="panel"><div class="table-wrap"><table class="table"><thead><tr><th>Applicant</th><th>Position</th><th>Date</th><th>Documents</th><th>Classification</th><th>Status</th><th>Action</th></tr></thead><tbody id="applicantsTable"></tbody></table></div></div>
</section>

<section id="analytics" class="section">
  <div class="page-title"><div><h2>Data Analytics</h2><p>Recruitment performance indicators and applicant screening results.</p></div><button class="btn btn-primary" onclick="printReport()">🖨 Print / Save PDF</button></div>
  <div class="cards"><div class="stat"><span class="stat-label">Total Applicants</span><h3>24</h3><small>All applications</small></div><div class="stat"><span class="stat-label">Passed</span><h3>11</h3><small>45.8% of applicants</small></div><div class="stat"><span class="stat-label">Pending</span><h3>8</h3><small>33.3% of applicants</small></div><div class="stat"><span class="stat-label">Failed</span><h3>5</h3><small>20.8% of applicants</small></div></div>
  <div class="grid-2"><div class="panel"><div class="panel-head"><h3>Screening Results</h3><select onchange="showToast('Analytics filter: '+this.value)"><option>All dates</option><option>Today</option><option>This week</option><option>This month</option></select></div><div class="panel-body"><div class="bar-row"><span>Passed</span><div class="bar"><i style="width:46%"></i></div><b>11</b></div><div class="bar-row"><span>Pending</span><div class="bar"><i style="width:33%"></i></div><b>8</b></div><div class="bar-row"><span>Failed</span><div class="bar"><i style="width:21%"></i></div><b>5</b></div></div></div>
  <div class="panel"><div class="panel-head"><h3>Applicants per Job Posting</h3></div><div class="panel-body"><div class="bar-row"><span>Security Guard</span><div class="bar"><i style="width:100%"></i></div><b>14</b></div><div class="bar-row"><span>Security Officer</span><div class="bar"><i style="width:50%"></i></div><b>7</b></div><div class="bar-row"><span>Shift Supervisor</span><div class="bar"><i style="width:21%"></i></div><b>3</b></div></div></div></div>
  <div class="panel" style="margin-top:18px"><div class="panel-head"><h3>Screening Summary</h3><div><select onchange="sortAnalytics(this.value)"><option value="date">Sort by Date</option><option value="job">Sort by Job Posting</option><option value="name">Sort by Name</option></select></div></div><div class="table-wrap"><table class="table"><thead><tr><th>Applicant</th><th>Job Posting</th><th>Date</th><th>Screening Classification</th><th>Status</th></tr></thead><tbody id="analyticsTable"></tbody></table></div></div>
</section>

<section id="database" class="section">
  <div class="page-title"><div><h2>Database Management</h2><p>Frontend representation of the recruitment database entities.</p></div></div>
  <div class="cards">
    <div class="stat"><span class="stat-label">Applicant Profiles</span><h3 id="dbApplicants">—</h3><small>Personal and contact data</small></div>
    <div class="stat"><span class="stat-label">Job Postings</span><h3 id="dbJobs">3</h3><small>Vacancy records</small></div>
    <div class="stat"><span class="stat-label">Applications</span><h3 id="dbApplications">—</h3><small>Application records</small></div>
    <div class="stat"><span class="stat-label">Documents</span><h3 id="dbDocuments">—</h3><small>Uploaded requirements</small></div>
  </div>
  <div class="panel"><div class="panel-head"><h3>Stored Data Entities</h3></div><div class="panel-body"><div class="quick-grid">
    <button class="quick" onclick="showToast('Applicant profiles selected')"><b>Applicant Profiles</b><span>Personal information, contact details, profile records</span></button>
    <button class="quick" onclick="showToast('Job postings selected')"><b>Job Postings</b><span>Positions, requirements, status, assessment settings</span></button>
    <button class="quick" onclick="showToast('Applications selected')"><b>Applications</b><span>Applicant-to-position application records</span></button>
    <button class="quick" onclick="showToast('Qualifications selected')"><b>Applicant Qualifications</b><span>Education, experience, skills, licenses</span></button>
    <button class="quick" onclick="showToast('Documents selected')"><b>Uploaded Documents</b><span>Requirements and document metadata</span></button>
    <button class="quick" onclick="showToast('Document check results selected')"><b>OCR / Document Check Results</b><span>Document extraction, completeness, consistency, and quality checks</span></button>
    <button class="quick" onclick="showToast('Screening results selected')"><b>Screening Results</b><span>Features, confidence, classification, model version</span></button>
    <button class="quick" onclick="showToast('Application statuses selected')"><b>Application Statuses</b><span>Submitted, Under Review, Screening, For Interview, Accepted, Rejected</span></button>
    <button class="quick" onclick="showToast('Recruitment records selected')"><b>Recruitment Records</b><span>Historical recruitment activity and decisions</span></button>
  </div></div></div>
</section>

<section id="assessments" class="section">
  <div class="page-title"><div><h2>Assessment Management</h2><p>Create and maintain optional applicant assessments.</p></div><button class="btn btn-primary" onclick="openAssessmentModal()">＋ Create Assessment</button></div>
  <div class="panel"><div class="panel-head"><h3>Assessments</h3></div><div class="table-wrap"><table class="table"><thead><tr><th>Assessment</th><th>Questions</th><th>Type</th><th>Status</th><th>Actions</th></tr></thead><tbody id="assessmentTable"></tbody></table></div></div>
</section>

<section id="users" class="section">
  <div class="page-title"><div><h2>User Management</h2><p>Manage HR accounts and access status.</p></div><button class="btn btn-primary" onclick="openUserModal()">＋ Add HR Account</button></div>
  <div class="panel"><div class="panel-head"><h3>HR Accounts</h3></div><div class="table-wrap"><table class="table"><thead><tr><th>Name</th><th>Username</th><th>Role</th><th>Last Login</th><th>Status</th><th>Actions</th></tr></thead><tbody id="usersTable"></tbody></table></div></div>
</section>

<section id="settings" class="section">
  <div class="page-title"><div><h2>Settings</h2><p>Configure system-level recruitment preferences.</p></div><button class="btn btn-primary" onclick="saveSettings()">Save Changes</button></div>
  <div class="panel"><div class="panel-body"><div class="form-grid">
    <div class="form-group"><label class="field-label">Organization Name</label><input id="orgName" value="RJC Corporate Center"></div>
    <div class="form-group"><label class="field-label">Default Application Status</label><select id="defaultStatus"><option>Submitted</option><option>Under Review</option></select></div>
    <div class="form-group"><label class="field-label">Screening Model</label><input value="Three-class SVM classification" readonly></div>
    <div class="form-group"><label class="field-label">Document Check Required</label><select id="documentCheckRequired"><option>Yes</option><option>No</option></select></div>
    <div class="form-group full"><label class="field-label">Recruitment Workflow</label><div class="check-grid"><label class="check"><input id="screeningEnabled" type="checkbox" checked> SVM classification enabled</label><label class="check"><input id="assessmentEnabled" type="checkbox" checked> Optional assessment</label></div></div>
  </div></div></div>
</section>
</div>
</main>
</div>

<div class="modal" id="jobModal"><div class="modal-box"><div class="modal-head"><h3 id="jobModalTitle">Create Job Posting</h3><button class="icon-btn" onclick="closeModal('jobModal')">×</button></div><div class="modal-body">
<form id="jobForm"><input type="hidden" id="jobId"><div class="form-grid">
<div class="form-group"><label class="field-label">Job Title *</label><input id="jobTitle" required placeholder="e.g. Security Guard"></div>
<div class="form-group"><label class="field-label">Employment Type</label><select id="jobType"><option>Full-time</option><option>Probationary</option><option>Reliever</option></select></div>
<div class="form-group full"><label class="field-label">Job Requirements *</label><textarea id="jobRequirements" rows="4" required placeholder="Education, license, experience, required qualifications..."></textarea></div>
<div class="form-group"><label class="field-label">Minimum Education</label><select id="jobMinEducation"><option value="None">No minimum</option><option>Elementary</option><option>High School</option><option>Senior High School</option><option>Vocational</option><option>Associate</option><option>Bachelor's</option><option>Master's</option><option>Doctorate</option><option>Other</option></select></div>
<div class="form-group"><label class="field-label">Minimum Relevant Experience (Years)</label><input id="jobMinExperience" type="number" min="0" max="80" step="0.5" value="0"></div>
<div class="form-group full"><label class="field-label">Required Certifications / Licenses</label><input id="jobRequiredCertifications" placeholder="Separate required certifications or licenses with commas"></div>
<div class="form-group full"><label class="field-label">Required Technical / Professional Skills</label><div class="check-grid"><label class="check"><input type="checkbox" value="Security License"> Valid Security License</label><label class="check"><input type="checkbox" value="First Aid"> First Aid</label><label class="check"><input type="checkbox" value="Crowd Control"> Crowd Control</label><label class="check"><input type="checkbox" value="Incident Reporting"> Incident Reporting</label><label class="check"><input type="checkbox" value="Access Control"> Access Control</label><label class="check"><input type="checkbox" value="Emergency Response"> Emergency Response</label></div></div>
<div class="form-group"><label class="field-label">Optional Assessment</label><select id="jobAssessment"><option value="None">None</option><option value="Security Readiness Assessment">Security Readiness Assessment</option><option value="Basic Security Knowledge Test">Basic Security Knowledge Test</option></select></div>
<div class="form-group"><label class="field-label">Initial Status</label><select id="jobStatus"><option>Active</option><option>Inactive</option></select></div>
</div></form>
</div><div class="modal-foot"><button class="btn btn-secondary" onclick="closeModal('jobModal')">Cancel</button><button class="btn btn-primary" onclick="saveJob()">Save Job Posting</button></div></div></div>

<div class="modal" id="assessmentModal"><div class="modal-box"><div class="modal-head"><h3 id="assessmentModalTitle">Create Assessment</h3><button class="icon-btn" onclick="closeModal('assessmentModal')">×</button></div><div class="modal-body"><div class="form-grid"><div class="form-group"><label class="field-label">Assessment Name</label><input id="assessmentName" placeholder="Assessment title"></div><div class="form-group"><label class="field-label">Type</label><select id="assessmentType"><option>Multiple Choice</option><option>Mixed</option><option>Document-based</option></select></div><div class="form-group"><label class="field-label">Number of Questions</label><input id="assessmentQuestions" type="number" value="20"></div><div class="form-group"><label class="field-label">Status</label><select id="assessmentStatus"><option>Active</option><option>Inactive</option></select></div></div></div><div class="modal-foot"><button class="btn btn-secondary" onclick="closeModal('assessmentModal')">Cancel</button><button class="btn btn-primary" onclick="saveAssessment()">Save Assessment</button></div></div></div>

<div class="modal" id="userModal"><div class="modal-box"><div class="modal-head"><h3 id="userModalTitle">Create HR Account</h3><button class="icon-btn" onclick="closeModal('userModal')">×</button></div><div class="modal-body"><input type="hidden" id="userId"><div class="form-grid"><div class="form-group"><label class="field-label">Full Name</label><input id="userName" required></div><div class="form-group"><label class="field-label">Username</label><input id="userUsername" required></div><div class="form-group full"><label class="field-label">Email</label><input id="userEmail" type="email" required></div><div class="form-group"><label class="field-label">Password <span class="muted">(required for new account)</span></label><input id="userPassword" type="password" minlength="8"></div><div class="form-group"><label class="field-label">Role</label><select id="userRole"><option value="HR Personnel">HR Personnel</option><option value="HR Admin">HR Admin</option></select></div><div class="form-group"><label class="field-label">Status</label><select id="userStatus"><option>Active</option><option>Inactive</option></select></div></div></div><div class="modal-foot"><button class="btn btn-secondary" onclick="closeModal('userModal')">Cancel</button><button class="btn btn-primary" onclick="saveUser()">Save HR Account</button></div></div></div>

<div class="modal" id="appModal"><div class="modal-box"><div class="modal-head"><h3>Applicant Screening Review</h3><button class="icon-btn" onclick="closeModal('appModal')">×</button></div><div class="modal-body" id="appDetail"></div><div class="modal-foot"><button class="btn btn-secondary" onclick="closeModal('appModal')">Close</button><button class="btn btn-danger" onclick="setApplicantStatus('Rejected')">Reject</button><button class="btn btn-secondary" onclick="setApplicantStatus('For Interview')">For Interview</button><button class="btn btn-success" onclick="setApplicantStatus('Accepted')">Accept</button></div></div></div>

<div class="toast" id="toast"></div>

<script>
const state = {
  jobs: JSON.parse(localStorage.getItem('mmv_jobs') || 'null') || [
    {id:1,title:'Security Guard',type:'Full-time',requirements:'High school graduate; valid security license; physically and mentally fit; required clearances.',assessment:'Security Readiness Assessment',status:'Active',applications:14},
    {id:2,title:'Security Officer',type:'Full-time',requirements:'College graduate; security experience; valid license; supervisory and reporting skills.',assessment:'Basic Security Knowledge Test',status:'Active',applications:7},
    {id:3,title:'Shift Supervisor',type:'Probationary',requirements:'Relevant security experience; leadership capability; valid license; incident reporting.',assessment:'None',status:'Active',applications:3}
  ],
  applicants: [
    {id:1,name:'Juan Dela Cruz',job:'Security Guard',date:'2026-08-15',docs:'Complete',classification:'Qualified',status:'For Interview'},
    {id:2,name:'Mark Santos',job:'Security Guard',date:'2026-08-15',docs:'Complete',classification:'Potentially Qualified',status:'Screening'},
    {id:3,name:'Ramon Garcia',job:'Security Officer',date:'2026-08-14',docs:'Needs Review',classification:'Potentially Qualified',status:'Under Review'},
    {id:4,name:'Pedro Reyes',job:'Security Guard',date:'2026-08-13',docs:'Complete',classification:'Qualified',status:'For Interview'},
    {id:5,name:'Carlo Mendoza',job:'Shift Supervisor',date:'2026-08-12',docs:'Incomplete',classification:'Rejected',status:'Rejected'},
    {id:6,name:'Jose Navarro',job:'Security Officer',date:'2026-08-12',docs:'Complete',classification:'Qualified',status:'Accepted'}
  ],
  assessments: [
    {id:1,name:'Security Readiness Assessment',questions:30,type:'Multiple Choice',status:'Active'},
    {id:2,name:'Basic Security Knowledge Test',questions:20,type:'Mixed',status:'Active'}
  ],
  users: [
    {id:1,name:'HR Administrator',username:'hradmin',role:'HR Administrator',login:'Today 08:42 AM',status:'Active'},
    {id:2,name:'Maria Santos',username:'msantos',role:'HR Staff',login:'Yesterday 04:16 PM',status:'Active'}
  ],
  applicantFilter:'all', selectedApplicant:null
};
function persist(){localStorage.setItem('mmv_jobs',JSON.stringify(state.jobs))}
function esc(v){return String(v??'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]))}
function showToast(msg){const t=document.getElementById('toast');t.textContent=msg;t.classList.add('show');setTimeout(()=>t.classList.remove('show'),2300)}
async function logoutHr(){
  try { await fetch('api/hr_logout.php',{method:'POST',credentials:'same-origin'}); }
  finally { window.location.replace('hr_login.php'); }
}
function go(id){
  document.querySelectorAll('.section').forEach(s=>s.classList.remove('active'));
  document.getElementById(id).classList.add('active');
  document.querySelectorAll('.nav-btn').forEach(b=>b.classList.toggle('active',b.dataset.section===id));
  const label=document.querySelector(`[data-section="${id}"] span:last-child`)?.textContent || id;
  document.getElementById('crumb').textContent=label;
  if(id==='jobs')renderJobs(); if(id==='applicants')renderApplicants(); if(id==='analytics')renderAnalytics(); if(id==='assessments')loadAssessments(); if(id==='users')loadUsers(); if(id==='database')loadDatabaseSummary(); if(id==='settings')loadSettings(); updateStats();
  window.scrollTo({top:0,behavior:'smooth'});
}
document.querySelectorAll('.nav-btn').forEach(b=>b.addEventListener('click',()=>go(b.dataset.section)));

function updateStats(){
 loadDashboardData();
}
async function loadDashboardData(){
 try{const response=await fetch('api/hr_analytics.php');const data=await response.json();if(!data.ok)return;const count=(group,label)=>Number(group?.[label]||0), pending=count(data.by_status,'Submitted')+count(data.by_status,'Under Review');document.getElementById('statJobs').textContent=data.active_job_postings;document.getElementById('statApplicants').textContent=data.total_applications;document.getElementById('statPending').textContent=pending;document.getElementById('statPassed').textContent=count(data.by_classification,'Qualified');document.getElementById('dbJobs').textContent=data.active_job_postings;const qualifiedLabel=document.getElementById('statPassed').closest('.stat').querySelector('.stat-label');if(qualifiedLabel)qualifiedLabel.textContent='Qualified';const summary=document.querySelector('#dashboard .grid-2 .panel:last-child .panel-body');if(summary){const rows=[['Qualified',count(data.by_classification,'Qualified')],['Potentially Qualified',count(data.by_classification,'Potentially Qualified')],['Rejected',count(data.by_classification,'Rejected')]],maximum=Math.max(data.total_applications,1);summary.innerHTML=rows.map(([label,total])=>`<div class="bar-row"><span>${esc(label)}</span><div class="bar"><i style="width:${Math.round(total/maximum*100)}%"></i></div><b>${total}</b></div>`).join('')+'<div class="notice">Live screening classifications from stored application records.</div>';}}catch(error){console.warn('Live dashboard statistics are unavailable.');}
}
function renderRecent(){
 document.getElementById('recentApplicants').innerHTML=state.applicants.slice(0,5).map(a=>`<tr><td><b>${esc(a.name)}</b></td><td>${esc(a.job)}</td><td>${esc(a.date)}</td><td>${badge(a.classification)}</td><td>${badge(a.status)}</td></tr>`).join('');
}
function badge(s){const c=String(s||'Pending').toLowerCase().replace(/\s+/g,'-');return `<span class="status ${c}">${esc(s||'Pending')}</span>`}

function openJobModal(id=null){
 document.getElementById('jobModalTitle').textContent=id?'Update Job Posting':'Create Job Posting';
 document.getElementById('jobId').value=id||'';
 const j=state.jobs.find(x=>x.id==id);
 document.getElementById('jobTitle').value=j?.title||'';
 document.getElementById('jobType').value=j?.type||'Full-time';
 document.getElementById('jobRequirements').value=j?.requirements||'';
 document.getElementById('jobMinEducation').value=j?.minEducation||'None';
 document.getElementById('jobMinExperience').value=j?.minExperience??0;
 document.getElementById('jobRequiredCertifications').value=j?.requiredCertifications||'';
 document.getElementById('jobAssessment').value=j?.assessment||'None';
 document.getElementById('jobStatus').value=j?.status||'Active';
 document.querySelectorAll('#jobForm .check-grid input').forEach(x=>x.checked=false);
 document.getElementById('jobModal').classList.add('show');
}
async function saveJob(){
 const title=document.getElementById('jobTitle').value.trim(), req=document.getElementById('jobRequirements').value.trim();
 if(!title||!req){showToast('Please complete required fields');return}
 const id=document.getElementById('jobId').value;
 const requiredSkills=[...document.querySelectorAll('#jobForm .check-grid input:checked')].map(x=>x.value).join(', ');
 const obj={title,type:document.getElementById('jobType').value,requirements:req,requiredSkills,minEducation:document.getElementById('jobMinEducation').value,minExperience:Number(document.getElementById('jobMinExperience').value||0),requiredCertifications:document.getElementById('jobRequiredCertifications').value.trim(),assessment:document.getElementById('jobAssessment').value,status:document.getElementById('jobStatus').value,applications:0};
 try {
  const response=await fetch('api/jobs.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({job_id:id||undefined,job_title:obj.title,employment_type:obj.type,job_description:obj.requirements,required_skills:obj.requiredSkills,min_education_level:obj.minEducation,min_years_experience:obj.minExperience,required_certifications:obj.requiredCertifications,assessment_name:obj.assessment,status:obj.status})});
  const payload=await response.json();
  if(!payload.ok){showToast(payload.message||'Job posting could not be saved.');return;}
  obj.id=Number(payload.job_id);
 } catch(error){showToast('Job posting service is unavailable.');return;}
 closeModal('jobModal');await loadJobs();updateStats();showToast(id?'Job posting updated':'Job posting created');
}
async function deleteJob(id){if(!confirm('Delete this job posting? This also removes its application records.'))return;try{const r=await fetch('api/jobs.php',{method:'DELETE',headers:{'Content-Type':'application/json'},body:JSON.stringify({job_id:id})}),d=await r.json();if(!d.ok)throw new Error(d.message);await loadJobs();showToast('Job posting deleted');}catch(e){showToast(e.message||'Job posting could not be deleted.')}}
async function toggleJob(id){const j=state.jobs.find(x=>x.id===id);if(!j)return;try{const r=await fetch('api/jobs.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({job_id:id,job_title:j.title,employment_type:j.type,job_description:j.requirements,required_skills:j.requiredSkills||'',min_education_level:j.minEducation||'None',min_years_experience:j.minExperience||0,required_certifications:j.requiredCertifications||'',assessment_name:j.assessment||'None',status:j.status==='Active'?'Inactive':'Active'})}),d=await r.json();if(!d.ok)throw new Error(d.message);await loadJobs();showToast('Job posting updated');}catch(e){showToast(e.message||'Job posting could not be updated.')}}
function renderJobs(){
 const q=(document.getElementById('jobSearch')?.value||'').toLowerCase();
 const rows=state.jobs.filter(j=>j.title.toLowerCase().includes(q));
 document.getElementById('jobsTable').innerHTML=rows.map(j=>`<tr><td><b>${esc(j.title)}</b><br><small class="muted">${esc(j.type)}</small></td><td>${esc(j.requirements.slice(0,75))}${j.requirements.length>75?'…':''}</td><td>${esc(j.assessment)}</td><td>${j.applications}</td><td>${badge(j.status)}</td><td><div class="actions"><button class="btn btn-sm btn-secondary" onclick="openJobModal(${j.id})">Edit</button><button class="btn btn-sm btn-secondary" onclick="toggleJob(${j.id})">${j.status==='Active'?'Deactivate':'Activate'}</button><button class="btn btn-sm btn-danger" onclick="deleteJob(${j.id})">Delete</button></div></td></tr>`).join('') || `<tr><td colspan="6"><div class="empty"><strong>No job postings found</strong>Try another search or create a new posting.</div></td></tr>`;
}

function populateJobFilter(){const s=document.getElementById('appJobFilter');if(!s)return;s.innerHTML='<option value="">All Job Postings</option>'+state.jobs.map(j=>`<option>${esc(j.title)}</option>`).join('')}
function setApplicantFilter(f,btn){state.applicantFilter=f;document.querySelectorAll('.filter-app').forEach(x=>x.classList.remove('active'));btn?.classList.add('active');renderApplicants()}
function renderApplicants(){
 populateJobFilter();
 const q=(document.getElementById('appSearch')?.value||'').toLowerCase(), job=document.getElementById('appJobFilter')?.value||'', classification=document.getElementById('appClassificationFilter')?.value||'', documentStatus=document.getElementById('appDocumentFilter')?.value||'';
 let rows=state.applicants.filter(a=>(state.applicantFilter==='all'||(state.applicantFilter==='Screening'&&a.status==='Screening')||(state.applicantFilter==='Completed Screening'&&['Qualified','Potentially Qualified','Rejected'].includes(a.classification)))&&a.name.toLowerCase().includes(q)&&(!job||a.job===job)&&(!classification||a.classification===classification)&&(!documentStatus||a.docs===documentStatus));
 document.getElementById('applicantsTable').innerHTML=rows.map(a=>`<tr><td><b>${esc(a.name)}</b></td><td>${esc(a.job)}</td><td>${esc(a.date)}</td><td>${badge(a.docs)}</td><td>${badge(a.classification)}</td><td>${badge(a.status)}</td><td><div class="actions"><button class="btn btn-sm btn-secondary" onclick="openApplicant(${a.id})">Review</button><button class="btn btn-sm btn-primary" onclick="runScreening(${a.id})">Run Screening</button></div></td></tr>`).join('')||`<tr><td colspan="7"><div class="empty"><strong>No applicants found</strong>Change the selected category or search term.</div></td></tr>`;
}
function openApplicant(id){state.selectedApplicant=id;const a=state.applicants.find(x=>x.id===id);document.getElementById('appDetail').innerHTML=`<div class="notice">Document checks assess completeness, internal consistency, and document quality.</div><div class="form-grid"><div><label class="field-label">Applicant</label><input value="${esc(a.name)}" readonly></div><div><label class="field-label">Applied Position</label><input value="${esc(a.job)}" readonly></div><div><label class="field-label">Document Check</label><input value="${esc(a.docs)}" readonly></div><div><label class="field-label">Screening Classification</label><input value="${esc(a.classification)}" readonly></div><div class="form-group full"><label class="field-label">Current Application Status</label><div>${badge(a.status)}</div></div></div>`;document.getElementById('appModal').classList.add('show')}
async function setApplicantStatus(status){const a=state.applicants.find(x=>x.id===state.selectedApplicant);if(!a)return;try{const response=await fetch('api/hr_application_status.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({application_id:a.id,status})});const payload=await response.json();if(!payload.ok){showToast(payload.message||'Status could not be updated.');return;}a.status=payload.status;closeModal('appModal');renderApplicants();renderRecent();renderAnalytics();updateStats();showToast(`Applicant marked ${status.toLowerCase()}`);await loadApplicationsFromDatabase();}catch(error){showToast('Application-status service is unavailable.');}}
async function runScreening(applicationId){
 try { const response=await fetch('api/screen_application.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({application_id:applicationId})}); const payload=await response.json(); if(!payload.ok){showToast(payload.message||'Screening could not be completed.');return;} showToast(`Screening complete: ${payload.screening.classification}`); await loadApplicationsFromDatabase(); }
 catch(error){showToast('Screening service is unavailable.');}
}

function openAssessmentModal(){document.getElementById('assessmentName').value='';document.getElementById('assessmentQuestions').value=20;document.getElementById('assessmentType').value='Multiple Choice';document.getElementById('assessmentStatus').value='Active';document.getElementById('assessmentModal').classList.add('show')}
async function saveAssessment(){const name=document.getElementById('assessmentName').value.trim();if(!name){showToast('Enter an assessment name');return}try{const r=await fetch('api/assessments.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({name,question_count:Number(document.getElementById('assessmentQuestions').value||0),assessment_type:document.getElementById('assessmentType').value,status:document.getElementById('assessmentStatus').value})}),d=await r.json();if(!d.ok)throw new Error(d.message);closeModal('assessmentModal');await loadAssessments();showToast('Assessment saved');}catch(e){showToast(e.message||'Assessment could not be saved.')}}
async function deleteAssessment(id){if(!confirm('Delete this assessment?'))return;try{const r=await fetch('api/assessments.php',{method:'DELETE',headers:{'Content-Type':'application/json'},body:JSON.stringify({assessment_id:id})}),d=await r.json();if(!d.ok)throw new Error(d.message);await loadAssessments();showToast('Assessment deleted');}catch(e){showToast(e.message||'Assessment could not be deleted.')}}
function renderAssessments(){document.getElementById('assessmentTable').innerHTML=state.assessments.map(a=>`<tr><td><b>${esc(a.name)}</b></td><td>${a.questions}</td><td>${esc(a.type)}</td><td>${badge(a.status)}</td><td><button class="btn btn-sm btn-danger" onclick="deleteAssessment(${a.id})">Delete</button></td></tr>`).join('')||'<tr><td colspan="5" class="empty">No assessments have been created.</td></tr>'}
async function loadAssessments(){try{const r=await fetch('api/assessments.php'),d=await r.json();if(!d.ok)throw new Error(d.message);state.assessments=d.assessments.map(a=>({id:Number(a.assessment_id),name:a.name,questions:Number(a.question_count),type:a.assessment_type,status:a.status}));renderAssessments();}catch(e){showToast(e.message||'Assessments could not be loaded.');}}

function openUserModal(user=null){document.getElementById('userModalTitle').textContent=user?'Edit HR Account':'Create HR Account';document.getElementById('userId').value=user?.id||'';document.getElementById('userName').value=user?.name||'';document.getElementById('userUsername').value=user?.username||'';document.getElementById('userEmail').value=user?.email||'';document.getElementById('userPassword').value='';document.getElementById('userRole').value=user?.role||'HR Personnel';document.getElementById('userStatus').value=user?.status||'Active';document.getElementById('userModal').classList.add('show')}
async function saveUser(){const id=Number(document.getElementById('userId').value||0),full_name=document.getElementById('userName').value.trim(),username=document.getElementById('userUsername').value.trim(),email=document.getElementById('userEmail').value.trim(),password=document.getElementById('userPassword').value;if(!full_name||!username||!email||(!id&&!password)){showToast('Complete the account fields and password.');return}try{const r=await fetch('api/hr_users.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({hr_user_id:id||undefined,full_name,username,email,password,role:document.getElementById('userRole').value,status:document.getElementById('userStatus').value})}),d=await r.json();if(!d.ok)throw new Error(d.message);closeModal('userModal');await loadUsers();showToast('HR account saved');}catch(e){showToast(e.message||'HR account could not be saved.')}}
async function toggleUser(id){const u=state.users.find(x=>x.id===id);if(u)openUserModal({...u,status:u.status==='Active'?'Inactive':'Active'})}
async function deleteUser(id){if(!confirm('Delete this HR account?'))return;try{const r=await fetch('api/hr_users.php',{method:'DELETE',headers:{'Content-Type':'application/json'},body:JSON.stringify({hr_user_id:id})}),d=await r.json();if(!d.ok)throw new Error(d.message);await loadUsers();showToast('HR account deleted');}catch(e){showToast(e.message||'HR account could not be deleted.')}}
function renderUsers(){document.getElementById('usersTable').innerHTML=state.users.map(u=>`<tr><td><b>${esc(u.name)}</b></td><td>${esc(u.username)}</td><td>${esc(u.role)}</td><td>${esc(u.login)}</td><td>${badge(u.status)}</td><td><div class="actions"><button class="btn btn-sm btn-secondary" onclick="openUserModal(state.users.find(x=>x.id===${u.id}))">Edit</button><button class="btn btn-sm btn-secondary" onclick="toggleUser(${u.id})">${u.status==='Active'?'Deactivate':'Activate'}</button><button class="btn btn-sm btn-danger" onclick="deleteUser(${u.id})">Delete</button></div></td></tr>`).join('')||'<tr><td colspan="6" class="empty">No HR accounts found.</td></tr>'}
async function loadUsers(){try{const r=await fetch('api/hr_users.php'),d=await r.json();if(!d.ok)throw new Error(d.message);state.users=d.users.map(u=>({id:Number(u.hr_user_id),name:u.full_name,username:u.username,email:u.email,role:u.role,login:u.created_at||'—',status:u.status}));renderUsers();}catch(e){showToast(e.message||'HR accounts could not be loaded.');}}

function renderAnalytics(){
 let rows=[...state.applicants];document.getElementById('analyticsTable').innerHTML=rows.map(a=>`<tr><td>${esc(a.name)}</td><td>${esc(a.job)}</td><td>${esc(a.date)}</td><td>${badge(a.classification||'Pending')}</td><td>${badge(a.status)}</td></tr>`).join('')
 loadAnalyticsData();
}
async function loadAnalyticsData(){
 try{const response=await fetch('api/hr_analytics.php');const data=await response.json();if(!data.ok)return;let panel=document.getElementById('liveAnalytics');if(!panel){panel=document.createElement('div');panel.id='liveAnalytics';panel.className='panel';panel.style.marginBottom='18px';const firstCards=document.querySelector('#analytics .cards');firstCards.before(panel);}const count=(group,label)=>Number(group?.[label]||0), total=Math.max(Number(data.total_applications),1), classes=['Qualified','Potentially Qualified','Rejected','Pending'];const classRows=classes.map(label=>({label,total:count(data.by_classification,label)}));const jobMax=Math.max(...data.by_job.map(row=>Number(row.total)),1);panel.innerHTML=`<div class="panel-head"><h3>Live Recruitment Snapshot</h3><button class="btn btn-sm btn-secondary" onclick="loadAnalyticsData()">Refresh</button></div><div class="panel-body"><div class="cards" style="margin-bottom:0"><div class="stat"><span class="stat-label">Applications</span><h3>${data.total_applications}</h3><small>All stored applications</small></div><div class="stat"><span class="stat-label">Qualified</span><h3>${count(data.by_classification,'Qualified')}</h3><small>Completed screening</small></div><div class="stat"><span class="stat-label">Potentially Qualified</span><h3>${count(data.by_classification,'Potentially Qualified')}</h3><small>Completed screening</small></div><div class="stat"><span class="stat-label">Document Checks Complete</span><h3>${count(data.by_document_status,'Complete')}</h3><small>Across submitted applications</small></div></div><div class="grid-2" style="margin-top:18px"><div><h3 style="font-size:13px">Screening Classifications</h3>${classRows.map(row=>`<div class="bar-row"><span>${esc(row.label)}</span><div class="bar"><i style="width:${Math.round(row.total/total*100)}%"></i></div><b>${row.total}</b></div>`).join('')}</div><div><h3 style="font-size:13px">Applications by Posting</h3>${data.by_job.map(row=>`<div class="bar-row"><span>${esc(row.label)}</span><div class="bar"><i style="width:${Math.round(Number(row.total)/jobMax*100)}%"></i></div><b>${row.total}</b></div>`).join('')||'<div class="empty">No job postings found.</div>'}</div></div></div>`;}catch(error){console.warn('Live analytics are unavailable.');}
}
function sortAnalytics(type){let rows=[...state.applicants];if(type==='name')rows.sort((a,b)=>a.name.localeCompare(b.name));if(type==='job')rows.sort((a,b)=>a.job.localeCompare(b.job));if(type==='date')rows.sort((a,b)=>b.date.localeCompare(a.date));document.getElementById('analyticsTable').innerHTML=rows.map(a=>`<tr><td>${esc(a.name)}</td><td>${esc(a.job)}</td><td>${esc(a.date)}</td><td>${badge(a.classification||'Pending')}</td><td>${badge(a.status)}</td></tr>`).join('')}
function printReport(){window.print()}
async function loadSettings(){try{const r=await fetch('api/settings.php'),d=await r.json();if(!d.ok)throw new Error(d.message);const s=d.settings;document.getElementById('orgName').value=s.organization_name||'RJC Corporate Center';document.getElementById('defaultStatus').value=s.default_application_status||'Submitted';document.getElementById('documentCheckRequired').value=s.document_check_required||'Yes';document.getElementById('screeningEnabled').checked=(s.screening_enabled||'Yes')==='Yes';document.getElementById('assessmentEnabled').checked=(s.assessment_enabled||'Yes')==='Yes';}catch(e){showToast(e.message||'Settings could not be loaded.');}}
async function saveSettings(){try{const r=await fetch('api/settings.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({organization_name:document.getElementById('orgName').value.trim(),default_application_status:document.getElementById('defaultStatus').value,document_check_required:document.getElementById('documentCheckRequired').value,screening_enabled:document.getElementById('screeningEnabled').checked?'Yes':'No',assessment_enabled:document.getElementById('assessmentEnabled').checked?'Yes':'No'})}),d=await r.json();if(!d.ok)throw new Error(d.message);showToast('Settings saved');}catch(e){showToast(e.message||'Settings could not be saved.')}}
async function loadDatabaseSummary(){try{const r=await fetch('api/hr_database_summary.php'),d=await r.json();if(!d.ok)throw new Error(d.message);const c=d.counts;document.getElementById('dbApplicants').textContent=c.applicants;document.getElementById('dbJobs').textContent=c.job_postings;document.getElementById('dbApplications').textContent=c.job_applications;document.getElementById('dbDocuments').textContent=c.applicant_documents;}catch(e){showToast(e.message||'Database summary could not be loaded.');}}
async function loadJobs(){try{const r=await fetch('api/jobs.php?admin=1'),d=await r.json();if(!d.ok)throw new Error(d.message);state.jobs=d.jobs.map(j=>({id:Number(j.job_id),title:j.job_title,type:j.employment_type||'Full-time',requirements:j.job_description,requiredSkills:j.required_skills||'',minEducation:j.min_education_level||'None',minExperience:Number(j.min_years_experience||0),requiredCertifications:j.required_certifications||'',assessment:j.assessment_name||'None',status:j.status,applications:0}));renderJobs();}catch(e){console.warn('Job postings could not be loaded.',e);}}
function closeModal(id){document.getElementById(id).classList.remove('show')}
document.querySelectorAll('.modal').forEach(m=>m.addEventListener('click',e=>{if(e.target===m)m.classList.remove('show')}));

renderRecent();renderJobs();renderApplicants();renderAnalytics();updateStats();loadJobs();loadAssessments();loadUsers();loadSettings();loadDatabaseSummary();
async function loadApplicationsFromDatabase(){
 try{
  const response=await fetch('api/hr_applications.php'); const payload=await response.json();
  if(!payload.ok || !Array.isArray(payload.applications)) return;
  state.applicants=payload.applications.map(a=>({id:Number(a.application_id),name:a.applicant_name,job:a.job_title,date:String(a.applied_at).slice(0,10),docs:a.document_status,classification:a.classification||'Pending',status:a.status,confidence:a.confidence}));
  renderRecent();renderApplicants();renderAnalytics();updateStats();
 }catch(error){console.warn('Using prototype HR data because the database is unavailable.');}
}
loadApplicationsFromDatabase();
</script>
</body>
</html>
