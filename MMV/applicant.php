<?php
/*
|--------------------------------------------------------------------------
| MMV - RJC Corporate Center
| Applicant-Only Recruitment Portal
|--------------------------------------------------------------------------
| FRONTEND PROTOTYPE ONLY
|
| Flow:
| 1. Open Job Postings
| 2. View Job Requirements
| 3. Click Apply
| 4. Applicant Registration
| 5. Applicant Portal
| 6. View Profile / Applied Position / Status
| 7. Submit Job Requirements
| 8. Track Recruitment Stages + Assessment
|
| Current storage: browser localStorage.
| Backend/MySQL/OCR/SVM will be connected later.
|--------------------------------------------------------------------------
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MMV | RJC Corporate Center Applicant Portal</title>
<style>
:root{
    --navy:#0b1f3a;
    --navy2:#102b4b;
    --blue:#1d5fd1;
    --blue2:#eaf2ff;
    --bg:#f4f7fb;
    --white:#fff;
    --text:#172033;
    --muted:#687386;
    --line:#e2e7ef;
    --green:#15803d;
    --green-bg:#eaf8ef;
    --orange:#b45309;
    --orange-bg:#fff6df;
    --red:#b42318;
    --red-bg:#fff0ef;
    --shadow:0 8px 30px rgba(15,31,58,.07);
    --radius:14px;
}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{
    margin:0;
    font-family:Segoe UI,Inter,Arial,sans-serif;
    color:var(--text);
    background:var(--bg);
}
button,input,select,textarea{font:inherit}
button{cursor:pointer}
a{text-decoration:none;color:inherit}

.topbar{
    height:72px;
    background:#fff;
    border-bottom:1px solid var(--line);
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 6%;
    position:sticky;
    top:0;
    z-index:30;
}
.brand{
    display:flex;
    align-items:center;
    gap:11px;
}
.logo{
    width:42px;
    height:42px;
    background:var(--navy);
    color:#fff;
    border-radius:10px;
    display:grid;
    place-items:center;
    font-weight:800;
    font-size:12px;
}
.brand strong{font-size:15px}
.brand small{
    display:block;
    color:var(--muted);
    font-size:9px;
    margin-top:2px;
}
.top-actions{
    display:flex;
    gap:9px;
    align-items:center;
}
.btn{
    border:0;
    border-radius:9px;
    padding:10px 15px;
    font-weight:700;
    font-size:12px;
}
.btn-primary{background:var(--blue);color:#fff}
.btn-primary:hover{background:#174fae}
.btn-secondary{background:#fff;color:var(--text);border:1px solid var(--line)}
.btn-secondary:hover{background:#f7f9fc}
.btn-danger{background:var(--red-bg);color:var(--red)}

.page{
    max-width:1250px;
    margin:auto;
    padding:35px 6% 70px;
}

.view{display:none}
.view.active{display:block}

.hero{
    background:linear-gradient(120deg,var(--navy),#173d68);
    color:#fff;
    border-radius:18px;
    padding:42px;
    margin-bottom:28px;
    box-shadow:var(--shadow);
}
.hero .eyebrow{
    font-size:10px;
    text-transform:uppercase;
    letter-spacing:1.5px;
    opacity:.7;
    font-weight:700;
}
.hero h1{
    margin:9px 0 10px;
    font-size:32px;
    max-width:700px;
}
.hero p{
    margin:0;
    max-width:690px;
    line-height:1.7;
    font-size:13px;
    color:#dbe7f6;
}
.hero-note{
    display:inline-flex;
    margin-top:20px;
    padding:8px 11px;
    background:#ffffff16;
    border:1px solid #ffffff1f;
    border-radius:8px;
    font-size:10px;
}

.heading{
    display:flex;
    justify-content:space-between;
    align-items:flex-end;
    gap:15px;
    margin-bottom:17px;
}
.heading h2{
    font-size:22px;
    margin:0 0 5px;
}
.heading p{
    margin:0;
    color:var(--muted);
    font-size:12px;
}

.job-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:17px;
}
.job-card{
    background:#fff;
    border:1px solid var(--line);
    border-radius:var(--radius);
    padding:20px;
    box-shadow:var(--shadow);
}
.job-card:hover{
    border-color:#b9cceb;
}
.job-top{
    display:flex;
    justify-content:space-between;
    gap:15px;
}
.job-card h3{
    margin:0 0 5px;
    font-size:17px;
}
.company{
    color:var(--muted);
    font-size:11px;
}
.open-tag{
    height:max-content;
    background:var(--green-bg);
    color:var(--green);
    border-radius:999px;
    padding:5px 9px;
    font-size:9px;
    font-weight:800;
}
.meta{
    display:flex;
    flex-wrap:wrap;
    gap:7px;
    margin:16px 0;
}
.meta span{
    background:#f5f7fa;
    color:#596579;
    border-radius:7px;
    padding:7px 9px;
    font-size:10px;
}
.job-summary{
    color:var(--muted);
    font-size:11px;
    line-height:1.6;
    min-height:52px;
}
.job-actions{
    display:flex;
    gap:8px;
    margin-top:16px;
}
.job-actions .btn{flex:1}

.card{
    background:#fff;
    border:1px solid var(--line);
    border-radius:var(--radius);
    box-shadow:var(--shadow);
}
.card-head{
    padding:18px 20px;
    border-bottom:1px solid var(--line);
}
.card-head h3{margin:0;font-size:15px}
.card-body{padding:20px}

.form-wrap{
    max-width:850px;
    margin:auto;
}
.form-card{
    background:#fff;
    border:1px solid var(--line);
    border-radius:16px;
    box-shadow:var(--shadow);
    overflow:hidden;
}
.form-header{
    background:var(--navy);
    color:#fff;
    padding:25px;
}
.form-header h2{margin:0 0 6px;font-size:22px}
.form-header p{margin:0;color:#dbe7f6;font-size:11px}
.form-body{padding:25px}
.form-section{
    margin-bottom:25px;
}
.form-section:last-child{margin-bottom:0}
.form-section-title{
    font-size:12px;
    font-weight:800;
    text-transform:uppercase;
    letter-spacing:.6px;
    margin-bottom:14px;
}
.form-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:15px;
}
.full{grid-column:1/-1}
label{
    display:block;
    font-size:11px;
    font-weight:700;
    margin-bottom:6px;
}
input,select,textarea{
    width:100%;
    padding:11px 12px;
    border:1px solid #d9e0e9;
    border-radius:8px;
    outline:none;
    background:#fff;
    color:var(--text);
}
input:focus,select:focus,textarea:focus{
    border-color:#80a8e9;
    box-shadow:0 0 0 3px var(--blue2);
}
.required{color:var(--red)}
.help{
    margin-top:5px;
    font-size:9px;
    color:var(--muted);
}
.form-footer{
    border-top:1px solid var(--line);
    padding:17px 25px;
    display:flex;
    justify-content:flex-end;
    gap:8px;
}
.selected-job{
    background:#f5f8ff;
    border:1px solid #dce7fa;
    border-radius:10px;
    padding:14px;
    margin-bottom:22px;
}
.selected-job strong{font-size:13px}
.selected-job small{
    display:block;
    color:var(--muted);
    margin-top:4px;
}

.portal-grid{
    display:grid;
    grid-template-columns:320px 1fr;
    gap:18px;
}
.profile-card{
    padding:22px;
}
.profile-avatar{
    width:75px;
    height:75px;
    border-radius:50%;
    background:var(--navy);
    color:#fff;
    display:grid;
    place-items:center;
    font-weight:800;
    font-size:21px;
    margin-bottom:15px;
}
.profile-card h3{margin:0 0 4px;font-size:17px}
.profile-card .role{font-size:10px;color:var(--muted);margin-bottom:20px}
.profile-row{
    border-top:1px solid var(--line);
    padding:11px 0;
}
.profile-row span{
    display:block;
    color:var(--muted);
    font-size:9px;
    text-transform:uppercase;
    letter-spacing:.5px;
    margin-bottom:4px;
}
.profile-row b{
    font-size:11px;
    font-weight:600;
    word-break:break-word;
}
.portal-main{
    display:flex;
    flex-direction:column;
    gap:18px;
}
.application-banner{
    background:linear-gradient(120deg,var(--navy),#173d68);
    color:#fff;
    border-radius:14px;
    padding:22px;
}
.application-banner .label{
    color:#b9c9dd;
    font-size:9px;
    text-transform:uppercase;
    letter-spacing:1px;
}
.application-banner h2{
    font-size:21px;
    margin:6px 0;
}
.application-banner p{
    margin:0;
    color:#dbe7f6;
    font-size:11px;
}
.status-pill{
    display:inline-flex;
    margin-top:14px;
    padding:6px 10px;
    border-radius:999px;
    background:var(--orange-bg);
    color:var(--orange);
    font-size:9px;
    font-weight:800;
}

.requirement-list{
    display:flex;
    flex-direction:column;
    gap:10px;
}
.requirement{
    border:1px solid var(--line);
    border-radius:10px;
    padding:13px;
    display:flex;
    justify-content:space-between;
    gap:12px;
    align-items:center;
}
.requirement-main{
    display:flex;
    gap:11px;
    align-items:flex-start;
}
.req-icon{
    width:32px;
    height:32px;
    border-radius:8px;
    background:#f0f4fa;
    display:grid;
    place-items:center;
    font-size:13px;
}
.requirement strong{
    display:block;
    font-size:11px;
}
.requirement small{
    display:block;
    color:var(--muted);
    font-size:9px;
    margin-top:3px;
}
.req-status{
    white-space:nowrap;
    border-radius:999px;
    padding:5px 8px;
    font-size:8px;
    font-weight:800;
}
.req-pending{background:var(--orange-bg);color:var(--orange)}
.req-submitted{background:var(--green-bg);color:var(--green)}
.req-review{background:var(--blue2);color:var(--blue)}

.timeline{
    position:relative;
    margin:10px 0 0 8px;
    padding-left:31px;
}
.timeline:before{
    content:"";
    position:absolute;
    left:7px;
    top:9px;
    bottom:10px;
    width:2px;
    background:#dfe5ed;
}
.stage{
    position:relative;
    padding-bottom:25px;
}
.stage:last-child{padding-bottom:0}
.stage-dot{
    position:absolute;
    left:-31px;
    top:0;
    width:17px;
    height:17px;
    border-radius:50%;
    background:#dce2ea;
    border:3px solid #fff;
    box-shadow:0 0 0 1px #dce2ea;
}
.stage.done .stage-dot{
    background:var(--green);
    box-shadow:0 0 0 1px var(--green);
}
.stage.current .stage-dot{
    background:var(--blue);
    box-shadow:0 0 0 3px #dbe9ff;
}
.stage b{font-size:11px}
.stage small{
    display:block;
    color:var(--muted);
    font-size:9px;
    line-height:1.5;
    margin-top:4px;
}
.stage .stage-tag{
    display:inline-block;
    margin-top:6px;
    font-size:8px;
    font-weight:800;
    padding:4px 7px;
    border-radius:999px;
}
.stage.done .stage-tag{background:var(--green-bg);color:var(--green)}
.stage.current .stage-tag{background:var(--blue2);color:var(--blue)}
.stage.pending .stage-tag{background:#f3f5f8;color:#7b8492}

.alert{
    padding:12px 14px;
    border-radius:9px;
    font-size:10px;
    line-height:1.5;
    margin-bottom:15px;
}
.alert-info{background:#f3f7ff;border:1px solid #dce8ff;color:#315a9d}
.alert-success{background:var(--green-bg);border:1px solid #cfead8;color:var(--green)}

.empty{
    text-align:center;
    padding:35px 15px;
    color:var(--muted);
    font-size:11px;
}
.empty strong{display:block;color:var(--text);margin-bottom:5px}

.modal{
    position:fixed;
    inset:0;
    background:#06142680;
    z-index:100;
    display:none;
    align-items:center;
    justify-content:center;
    padding:20px;
}
.modal.show{display:flex}
.modal-box{
    width:min(780px,100%);
    max-height:90vh;
    overflow:auto;
    background:#fff;
    border-radius:16px;
    box-shadow:0 25px 70px #0004;
}
.modal-head{
    padding:19px 21px;
    border-bottom:1px solid var(--line);
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.modal-head h3{margin:0;font-size:17px}
.close{
    width:34px;height:34px;border:1px solid var(--line);
    background:#fff;border-radius:8px;font-size:18px;
}
.modal-body{padding:21px}
.modal-footer{
    border-top:1px solid var(--line);
    padding:15px 21px;
    display:flex;
    justify-content:flex-end;
    gap:8px;
}
.requirements-box{
    background:#f8faff;
    border:1px solid #e2eaf8;
    border-radius:10px;
    padding:15px;
    margin-top:15px;
}
.requirements-box h4{font-size:11px;margin:0 0 9px}
.requirements-box ul{margin:0;padding-left:18px}
.requirements-box li{
    font-size:10px;
    color:var(--muted);
    margin:6px 0;
}

.toast{
    position:fixed;
    right:25px;
    bottom:25px;
    background:var(--navy2);
    color:#fff;
    border-radius:9px;
    padding:12px 16px;
    font-size:10px;
    display:none;
    z-index:200;
    box-shadow:var(--shadow);
}
.toast.show{display:block}

@media(max-width:900px){
    .portal-grid{grid-template-columns:1fr}
    .job-grid{grid-template-columns:1fr}
}
@media(max-width:650px){
    .topbar{padding:0 15px}
    .topbar .brand strong{font-size:13px}
    .page{padding:25px 15px 50px}
    .hero{padding:28px 22px}
    .hero h1{font-size:25px}
    .form-grid{grid-template-columns:1fr}
    .full{grid-column:auto}
    .job-actions{flex-direction:column}
    .form-footer{padding:15px;flex-direction:column}
    .form-footer .btn{width:100%}
}
</style>
</head>

<body>

<header class="topbar">
    <div class="brand">
        <div class="logo">MMV</div>
        <div>
            <strong>RJC Corporate Center</strong>
            <small>Applicant Recruitment Portal</small>
        </div>
    </div>

    <div class="top-actions">
        <button class="btn btn-secondary" id="homeButton" onclick="showView('jobs')">Open Positions</button>
        <button class="btn btn-secondary" id="loginButton" onclick="location.href='applicant_login.php'">Sign In</button>
        <button class="btn btn-primary" id="signupButton" onclick="location.href='applicant_signup.php'">Create Account</button>
        <button class="btn btn-primary" id="portalButton" style="display:none" onclick="showView('portal')">Applicant Portal</button>
    </div>
</header>

<main class="page">

<!-- =========================================================
     VIEW 1: OPEN JOB POSTINGS
========================================================= -->
<section id="jobsView" class="view active">

    <div class="hero">
        <div class="eyebrow">RJC Corporate Center Recruitment</div>
        <h1>Find an opportunity to join our security team.</h1>
        <p>
            View the current open positions, review the qualifications and
            requirements for each position, and apply for the opportunity
            that matches your qualifications.
        </p>
        <div class="hero-note">Open positions are available for qualified applicants.</div>
    </div>

    <div class="heading">
        <div>
            <h2>Open Job Postings</h2>
            <p>Select a position to view its complete job requirements.</p>
        </div>
    </div>

    <div class="job-grid" id="publicJobs"></div>

</section>


<!-- =========================================================
     VIEW 2: REGISTRATION
========================================================= -->
<section id="registrationView" class="view">

    <div class="form-wrap">
        <div class="form-card">

            <div class="form-header">
                <h2>Applicant Registration</h2>
                <p>Create your applicant account to continue with your application.</p>
            </div>

            <div class="form-body">

                <div class="selected-job">
                    <strong id="registrationJobTitle">Selected Position</strong>
                    <small id="registrationJobCompany">RJC Corporate Center</small>
                </div>

                <div class="alert alert-info">
                    Please enter your basic information accurately. This information
                    will be used to create your applicant profile and application record.
                </div>

                <div class="form-section">
                    <div class="form-section-title">Personal Information</div>

                    <div class="form-grid">

                        <div>
                            <label>Surname <span class="required">*</span></label>
                            <input id="regSurname" type="text" placeholder="Enter surname">
                        </div>

                        <div>
                            <label>First Name <span class="required">*</span></label>
                            <input id="regFirstName" type="text" placeholder="Enter first name">
                        </div>

                        <div>
                            <label>Middle Name</label>
                            <input id="regMiddleName" type="text" placeholder="Enter middle name">
                        </div>

                        <div>
                            <label>Date of Birth <span class="required">*</span></label>
                            <input id="regDob" type="date" onchange="calculateRegistrationAge()">
                        </div>

                        <div>
                            <label>Age</label>
                            <input id="regAge" type="text" readonly placeholder="Automatically calculated">
                        </div>

                        <div>
                            <label>Sex <span class="required">*</span></label>
                            <select id="regSex">
                                <option value="">Select sex</option>
                                <option>Male</option>
                                <option>Female</option>
                            </select>
                        </div>

                        <div class="full">
                            <label>Address <span class="required">*</span></label>
                            <textarea id="regAddress" rows="3" placeholder="Complete residential address"></textarea>
                        </div>

                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">Contact Information</div>

                    <div class="form-grid">

                        <div>
                            <label>Phone Number <span class="required">*</span></label>
                            <input id="regPhone" type="tel" placeholder="09XXXXXXXXX">
                            <div class="help">Example: 09171234567</div>
                        </div>

                        <div>
                            <label>Email Address <span class="required">*</span></label>
                            <input id="regEmail" type="email" placeholder="you@example.com">
                        </div>

                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">Educational Attainment</div>

                    <div class="form-grid">

                        <div>
                            <label>Educational Attainment <span class="required">*</span></label>
                            <select id="regEducation">
                                <option value="">Select</option>
                                <option>Elementary</option>
                                <option>High School</option>
                                <option>Senior High School</option>
                                <option>Vocational</option>
                                <option>Associate</option>
                                <option>Bachelor's</option>
                                <option>Master's</option>
                                <option>Doctorate</option>
                                <option>Other</option>
                            </select>
                        </div>

                        <div>
                            <label>Course / Program</label>
                            <input id="regCourse" type="text" placeholder="Course or program">
                        </div>

                        <div>
                            <label>School / Institution</label>
                            <input id="regSchool" type="text" placeholder="School name">
                        </div>

                        <div>
                            <label>Year Graduated</label>
                            <input id="regYear" type="number" placeholder="YYYY">
                        </div>

                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">Work Experience</div>

                    <div class="form-grid">
                        <div>
                            <label>Has Previous Work Experience?</label>
                            <select id="regHasExperience" onchange="toggleExperience()">
                                <option value="No">No</option>
                                <option value="Yes">Yes</option>
                            </select>
                        </div>
                        <div>
                            <label>Total Years of Relevant Experience <span class="required">*</span></label>
                            <input id="regYearsExperience" type="number" min="0" max="80" step="0.5" value="0" placeholder="e.g. 2.5">
                            <div class="help">Enter 0 if you have no relevant experience.</div>
                        </div>
                    </div>

                    <div id="experienceFields" style="display:none;margin-top:15px">
                        <div class="form-grid">
                            <div>
                                <label>Previous Employer</label>
                                <input id="regEmployer" type="text" placeholder="Company / employer">
                            </div>
                            <div>
                                <label>Previous Position</label>
                                <input id="regPosition" type="text" placeholder="Position held">
                            </div>
                            <div>
                                <label>Start Date</label>
                                <input id="regWorkStart" type="month">
                            </div>
                            <div>
                                <label>End Date</label>
                                <input id="regWorkEnd" type="month">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">Skills and Certifications</div>
                    <div class="form-grid">
                        <div class="full">
                            <label>Relevant Skills</label>
                            <input id="regSkills" type="text" placeholder="Separate skills with commas (e.g. Access Control, Incident Reporting)">
                        </div>
                        <div class="full">
                            <label>Certifications / Licenses</label>
                            <input id="regCertifications" type="text" placeholder="Separate certifications or licenses with commas">
                        </div>
                    </div>
                </div>

            </div>

            <div class="form-footer">
                <button class="btn btn-secondary" onclick="showView('jobs')">Back to Job Postings</button>
                <button class="btn btn-primary" onclick="registerApplicant()">Create Applicant Profile & Continue</button>
            </div>

        </div>
    </div>

</section>


<!-- =========================================================
     VIEW 3: APPLICANT PORTAL
========================================================= -->
<section id="portalView" class="view">

    <div class="heading">
        <div>
            <h2>Applicant Portal</h2>
            <p>View your profile, application status, requirements and recruitment stages.</p>
        </div>
        <button class="btn btn-secondary" onclick="logoutApplicant()">Return to Public Job Postings</button>
    </div>

    <div class="portal-grid">

        <!-- PROFILE -->
        <aside class="card profile-card">

            <div class="profile-avatar" id="portalInitials">AP</div>

            <h3 id="portalName">Applicant Name</h3>
            <div class="role">Registered Applicant</div>

            <div class="profile-row">
                <span>Email Address</span>
                <b id="portalEmail">—</b>
            </div>

            <div class="profile-row">
                <span>Phone Number</span>
                <b id="portalPhone">—</b>
            </div>

            <div class="profile-row">
                <span>Date of Birth</span>
                <b id="portalDob">—</b>
            </div>

            <div class="profile-row">
                <span>Age</span>
                <b id="portalAge">—</b>
            </div>

            <div class="profile-row">
                <span>Sex</span>
                <b id="portalSex">—</b>
            </div>

            <div class="profile-row">
                <span>Address</span>
                <b id="portalAddress">—</b>
            </div>

            <div class="profile-row">
                <span>Educational Attainment</span>
                <b id="portalEducation">—</b>
            </div>

        </aside>


        <div class="portal-main">

            <!-- APPLIED JOB -->
            <div class="application-banner">
                <div class="label">Current Application</div>
                <h2 id="portalJob">—</h2>
                <p>RJC Corporate Center</p>
                <div class="status-pill" id="applicationStatus">Pending</div>
            </div>


            <!-- REQUIREMENTS -->
            <div class="card">

                <div class="card-head">
                    <h3>Required Application Documents</h3>
                </div>

                <div class="card-body">

                    <div class="alert alert-info">
                        Submit the requirements requested for your selected job position.
                        Requirements will be reviewed by the recruitment team before
                        the application proceeds to the next stage.
                    </div>

                    <div class="requirement-list" id="requirementsList"></div>

                </div>

            </div>


            <!-- APPLICATION STAGES -->
            <div class="card">

                <div class="card-head">
                    <h3>Application Process</h3>
                </div>

                <div class="card-body">

                    <div class="alert alert-info">
                        Your application moves through the following recruitment stages.
                        The assessment stage will only be required when configured for
                        the selected position.
                    </div>

                    <div class="timeline" id="applicationTimeline"></div>

                </div>

            </div>

        </div>

    </div>

</section>

</main>


<!-- =========================================================
     JOB DETAILS MODAL
========================================================= -->
<div class="modal" id="jobModal">

    <div class="modal-box">

        <div class="modal-head">
            <h3 id="modalTitle">Job Details</h3>
            <button class="close" onclick="closeJobModal()">×</button>
        </div>

        <div class="modal-body">

            <div class="meta" id="modalMeta"></div>

            <p id="modalDescription"
               style="font-size:11px;line-height:1.7;color:var(--muted)">
            </p>

            <div class="requirements-box">
                <h4>Job Requirements</h4>
                <ul id="modalRequirements"></ul>
            </div>

            <div class="requirements-box">
                <h4>Recruitment Information</h4>
                <p id="modalAssessment"
                   style="font-size:10px;color:var(--muted);line-height:1.6;margin:0">
                </p>
            </div>

        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeJobModal()">Close</button>
            <button class="btn btn-primary" onclick="applyFromModal()">Apply for this Position</button>
        </div>

    </div>

</div>


<!-- =========================================================
     REQUIREMENT UPLOAD MODAL
========================================================= -->
<div class="modal" id="requirementModal">

    <div class="modal-box" style="max-width:560px">

        <div class="modal-head">
            <h3 id="requirementModalTitle">Submit Requirement</h3>
            <button class="close" onclick="closeRequirementModal()">×</button>
        </div>

        <div class="modal-body">

            <div class="alert alert-info">
                Frontend prototype: selecting a file will mark this requirement
                as submitted. Actual server-side file storage, OCR and document
                verification will be connected later.
            </div>

            <label>Select File</label>
            <input id="requirementFile" type="file">

        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeRequirementModal()">Cancel</button>
            <button class="btn btn-primary" onclick="submitRequirement()">Submit Requirement</button>
        </div>

    </div>

</div>


<div class="toast" id="toast"></div>


<script>

/*
|--------------------------------------------------------------------------
| SAMPLE OPEN JOB POSTINGS
|--------------------------------------------------------------------------
| These will later come from the HR Job Posting module + MySQL.
|--------------------------------------------------------------------------
*/

let jobs = [

    {
        id: 1,
        title: "Security Guard",
        employment: "Full-time",
        location: "Metro Manila",
        salary: "₱18,000 – ₱22,000",
        description:
            "Responsible for protecting assigned premises, personnel and property. The position includes access control, patrol duties, incident reporting and maintaining security procedures.",
        requirements: [
            "At least Senior High School graduate",
            "Valid security license / required security credentials",
            "Physically and mentally fit",
            "Able to work shifting schedules",
            "Required employment clearances and documents"
        ],
        assessment: "Security Readiness Assessment",
        documents: [
            "Valid Government-Issued ID",
            "Resume / Curriculum Vitae",
            "Educational Document",
            "Security License / Required Credential",
            "Required Employment Clearance"
        ]
    },

    {
        id: 2,
        title: "Security Officer",
        employment: "Full-time",
        location: "Metro Manila",
        salary: "₱22,000 – ₱28,000",
        description:
            "Responsible for implementing security procedures, preparing incident reports, coordinating security personnel and assisting with site security operations.",
        requirements: [
            "College graduate preferred",
            "Relevant security experience",
            "Valid security license / required security credentials",
            "Good incident reporting skills",
            "Able to work shifting schedules"
        ],
        assessment: "Basic Security Knowledge Test",
        documents: [
            "Valid Government-Issued ID",
            "Resume / Curriculum Vitae",
            "Educational Document",
            "Security License / Required Credential",
            "Employment / Experience Documents"
        ]
    },

    {
        id: 3,
        title: "Shift Supervisor",
        employment: "Probationary",
        location: "Metro Manila",
        salary: "₱28,000 – ₱35,000",
        description:
            "Supervises security personnel during assigned shifts and ensures compliance with operational security procedures and reporting requirements.",
        requirements: [
            "Relevant security supervisory experience",
            "Valid security license / required security credentials",
            "Leadership and team coordination experience",
            "Incident reporting capability",
            "Required employment clearances and documents"
        ],
        assessment: "Supervisor Assessment",
        documents: [
            "Valid Government-Issued ID",
            "Resume / Curriculum Vitae",
            "Educational Document",
            "Security License / Required Credential",
            "Employment / Experience Documents"
        ]
    }

];


/*
|--------------------------------------------------------------------------
| LOCAL FRONTEND STATE
|--------------------------------------------------------------------------
*/

let selectedJob = null;
let selectedRequirementIndex = null;

let applicant =
    JSON.parse(localStorage.getItem("mmv_applicant")) || null;

let application =
    JSON.parse(localStorage.getItem("mmv_application")) || null;

let applicantAuthenticated = false;


/*
|--------------------------------------------------------------------------
| VIEW NAVIGATION
|--------------------------------------------------------------------------
*/

function showView(view) {

    document.querySelectorAll(".view")
        .forEach(v => v.classList.remove("active"));

    document.getElementById(view + "View")
        .classList.add("active");

    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });

    if (view === "jobs") {
        renderPublicJobs();
    }

    if (view === "registration") {
        loadRegistrationJob();
    }

    if (view === "portal") {
        renderPortal();
    }

    updateHeader();
}


function updateHeader() {

    const portalButton =
        document.getElementById("portalButton");

    const homeButton =
        document.getElementById("homeButton");

    document.getElementById("loginButton").style.display = applicantAuthenticated ? "none" : "block";
    document.getElementById("signupButton").style.display = applicantAuthenticated ? "none" : "block";

    if (applicant && application) {

        portalButton.style.display = "block";

    } else {

        portalButton.style.display = "none";

    }

}


/*
|--------------------------------------------------------------------------
| PUBLIC JOB POSTINGS
|--------------------------------------------------------------------------
*/

function renderPublicJobs() {

    const container =
        document.getElementById("publicJobs");

    if (!jobs.length) {
        container.innerHTML = '<div class="empty"><strong>No active postings are available.</strong>Please check back later or contact RJC Corporate Center recruitment.</div>';
        return;
    }

    container.innerHTML = jobs.map(job => {

        return `

        <article class="job-card">

            <div class="job-top">

                <div>

                    <h3>${escapeHtml(job.title)}</h3>

                    <div class="company">
                        RJC Corporate Center
                    </div>

                </div>

                <span class="open-tag">
                    OPEN
                </span>

            </div>

            <div class="meta">

                <span>📍 ${escapeHtml(job.location)}</span>

                <span>${escapeHtml(job.employment)}</span>

                <span>${escapeHtml(job.salary)}</span>

            </div>

            <div class="job-summary">
                ${escapeHtml(job.description)}
            </div>

            <div class="job-actions">

                <button
                    class="btn btn-secondary"
                    onclick="viewJob(${job.id})">

                    View Requirements

                </button>

                <button
                    class="btn btn-primary"
                    onclick="startApplication(${job.id})">

                    Apply

                </button>

            </div>

        </article>

        `;

    }).join("");

}


/*
|--------------------------------------------------------------------------
| JOB DETAILS
|--------------------------------------------------------------------------
*/

function viewJob(id) {

    selectedJob =
        jobs.find(job => job.id === id);

    if (!selectedJob) return;

    document.getElementById("modalTitle")
        .textContent = selectedJob.title;

    document.getElementById("modalMeta")
        .innerHTML = `

        <span>📍 ${escapeHtml(selectedJob.location)}</span>

        <span>${escapeHtml(selectedJob.employment)}</span>

        <span>${escapeHtml(selectedJob.salary)}</span>

        `;

    document.getElementById("modalDescription")
        .textContent =
        selectedJob.description;

    document.getElementById("modalRequirements")
        .innerHTML =
        selectedJob.requirements
            .map(req => `<li>${escapeHtml(req)}</li>`)
            .join("");

    document.getElementById("modalAssessment")
        .innerHTML =
        `<b>Assessment:</b>
         ${escapeHtml(selectedJob.assessment)}
         <br><br>
         Applicants will be informed through the portal when an assessment
         is required and available.`;

    document.getElementById("jobModal")
        .classList.add("show");
}


function closeJobModal() {

    document.getElementById("jobModal")
        .classList.remove("show");

}


function applyFromModal() {

    if (!selectedJob) return;

    closeJobModal();

    startApplication(selectedJob.id);

}


/*
|--------------------------------------------------------------------------
| APPLY -> REGISTRATION
|--------------------------------------------------------------------------
*/

async function startApplication(id) {

    selectedJob =
        jobs.find(job => job.id === id);

    if (!selectedJob) return;

    if (!applicantAuthenticated) {
        showToast("Please sign in or create an applicant account before applying.");
        setTimeout(() => { location.href = "applicant_login.php?next=applicant.php"; }, 700);
        return;
    }

    if (applicant && application) {

        if (application.jobId === selectedJob.id) {

            showView("portal");

            return;

        }

        showToast(
            "An applicant account already exists in this prototype."
        );

        showView("portal");

        return;
    }

    showView("registration");

}


/*
|--------------------------------------------------------------------------
| REGISTRATION
|--------------------------------------------------------------------------
*/

function loadRegistrationJob() {

    if (!selectedJob) {

        selectedJob =
            jobs.find(job =>
                application &&
                job.id === application.jobId
            );

    }

    if (!selectedJob) return;

    document.getElementById("registrationJobTitle")
        .textContent =
        selectedJob.title;

    document.getElementById("registrationJobCompany")
        .textContent =
        `RJC Corporate Center • ${selectedJob.location}`;

}


function calculateRegistrationAge() {

    const value =
        document.getElementById("regDob").value;

    if (!value) return;

    const birth =
        new Date(value + "T00:00:00");

    const today =
        new Date();

    let age =
        today.getFullYear() -
        birth.getFullYear();

    const month =
        today.getMonth() -
        birth.getMonth();

    if (
        month < 0 ||
        (
            month === 0 &&
            today.getDate() < birth.getDate()
        )
    ) {

        age--;

    }

    document.getElementById("regAge")
        .value =
        age >= 0 ? age : "";

}


function toggleExperience() {

    const value =
        document.getElementById("regHasExperience")
            .value;

    document.getElementById("experienceFields")
        .style.display =
        value === "Yes"
            ? "block"
            : "none";

}


async function registerApplicant() {

    const required = {

        surname:
            document.getElementById("regSurname").value.trim(),

        firstName:
            document.getElementById("regFirstName").value.trim(),

        dob:
            document.getElementById("regDob").value,

        sex:
            document.getElementById("regSex").value,

        address:
            document.getElementById("regAddress").value.trim(),

        phone:
            document.getElementById("regPhone").value.trim(),

        email:
            document.getElementById("regEmail").value.trim(),

        education:
            document.getElementById("regEducation").value

    };


    for (const key in required) {

        if (!required[key]) {

            showToast(
                "Please complete all required fields."
            );

            return;

        }

    }


    if (!selectedJob) {

        showToast(
            "No job position was selected."
        );

        showView("jobs");

        return;

    }


    applicant = {

        surname:
            required.surname,

        firstName:
            required.firstName,

        middleName:
            document.getElementById("regMiddleName")
                .value.trim(),

        dob:
            required.dob,

        age:
            document.getElementById("regAge")
                .value,

        sex:
            required.sex,

        address:
            required.address,

        phone:
            required.phone,

        email:
            required.email,

        education:
            required.education,

        yearsExperience:
            Number(document.getElementById("regYearsExperience").value || 0),

        skills:
            document.getElementById("regSkills").value.trim(),

        certifications:
            document.getElementById("regCertifications").value.trim(),

        course:
            document.getElementById("regCourse")
                .value.trim(),

        school:
            document.getElementById("regSchool")
                .value.trim(),

        yearGraduated:
            document.getElementById("regYear")
                .value,

        experience:
            document.getElementById("regHasExperience")
                .value === "Yes"
                ? {

                    employer:
                        document.getElementById("regEmployer")
                            .value.trim(),

                    position:
                        document.getElementById("regPosition")
                            .value.trim(),

                    start:
                        document.getElementById("regWorkStart")
                            .value,

                    end:
                        document.getElementById("regWorkEnd")
                            .value

                }
                : null

    };


    application = {

        id:
            Date.now(),

        jobId:
            selectedJob.id,

        jobTitle:
            selectedJob.title,

        appliedDate:
            new Date()
                .toISOString()
                .slice(0,10),

        status:
            "Pending Requirements",

        currentStage:
            1,

        requirements:
            selectedJob.documents.map(document => ({

                name:
                    document,

                status:
                    "Pending",

                file:
                    null

            }))

    };

    try {
        const response = await fetch('api/applications.php', {
            method: 'POST', headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                surname: applicant.surname, first_name: applicant.firstName,
                middle_name: applicant.middleName, email: applicant.email, phone: applicant.phone,
                address: applicant.address, education_level: applicant.education,
                years_experience: applicant.yearsExperience, skills: applicant.skills,
                certifications: applicant.certifications, job_id: application.jobId
            })
        });
        const payload = await response.json();
        if (!payload.ok) { showToast(payload.message || 'Registration could not be submitted.'); return; }
        application.id = payload.application_id;
    } catch (error) {
        showToast('Database is unavailable. Your application was not submitted.');
        return;
    }


    localStorage.setItem(
        "mmv_applicant",
        JSON.stringify(applicant)
    );

    localStorage.setItem(
        "mmv_application",
        JSON.stringify(application)
    );


    showToast(
        "Registration completed successfully."
    );


    setTimeout(() => {

        showView("portal");

    }, 600);

}


/*
|--------------------------------------------------------------------------
| APPLICANT PORTAL
|--------------------------------------------------------------------------
*/

function renderPortal() {

    if (!applicant || !application) {

        showView("jobs");

        return;

    }


    const fullName =
        [
            applicant.firstName,
            applicant.middleName,
            applicant.surname
        ]
        .filter(Boolean)
        .join(" ");


    document.getElementById("portalName")
        .textContent =
        fullName;

    document.getElementById("portalEmail")
        .textContent =
        applicant.email;

    document.getElementById("portalPhone")
        .textContent =
        applicant.phone;

    document.getElementById("portalDob")
        .textContent =
        formatDate(applicant.dob);

    document.getElementById("portalAge")
        .textContent =
        applicant.age || "—";

    document.getElementById("portalSex")
        .textContent =
        applicant.sex;

    document.getElementById("portalAddress")
        .textContent =
        applicant.address;

    document.getElementById("portalEducation")
        .textContent =
        applicant.education;


    const initials =
        (
            (applicant.firstName || "A")[0] +
            (applicant.surname || "P")[0]
        )
        .toUpperCase();


    document.getElementById("portalInitials")
        .textContent =
        initials;


    document.getElementById("portalJob")
        .textContent =
        application.jobTitle;


    document.getElementById("applicationStatus")
        .textContent =
        application.status;


    renderRequirements();

    renderTimeline();

}


/*
|--------------------------------------------------------------------------
| REQUIREMENT SUBMISSION
|--------------------------------------------------------------------------
*/

function renderRequirements() {

    const container =
        document.getElementById("requirementsList");

    container.innerHTML =
        application.requirements
            .map((req, index) => {

                let statusClass =
                    "req-pending";

                if (req.status === "Submitted")
                    statusClass = "req-submitted";

                if (req.status === "Under Review")
                    statusClass = "req-review";


                let action = "";

                if (req.status === "Pending") {

                    action = `

                        <button
                            class="btn btn-primary"
                            onclick="openRequirementModal(${index})">

                            Submit

                        </button>

                    `;

                } else if (req.status === "Submitted") {

                    action = `

                        <button
                            class="btn btn-secondary"
                            onclick="openRequirementModal(${index})">

                            Replace

                        </button>

                    `;

                } else {

                    action = `
                        <span
                            class="req-status req-review">
                            Under Review
                        </span>
                    `;

                }


                return `

                <div class="requirement">

                    <div class="requirement-main">

                        <div class="req-icon">
                            📄
                        </div>

                        <div>

                            <strong>
                                ${escapeHtml(req.name)}
                            </strong>

                            <small>
                                ${req.file
                                    ? escapeHtml(req.file)
                                    : "Document has not been submitted."}
                            </small>

                        </div>

                    </div>

                    <div style="display:flex;align-items:center;gap:8px">

                        <span
                            class="req-status ${statusClass}">
                            ${escapeHtml(req.status)}
                        </span>

                        ${action}

                    </div>

                </div>

                `;

            })
            .join("");


    updateApplicationStatus();

}


function openRequirementModal(index) {

    selectedRequirementIndex =
        index;

    const requirement =
        application.requirements[index];

    document.getElementById(
        "requirementModalTitle"
    ).textContent =
        `Submit: ${requirement.name}`;

    document.getElementById(
        "requirementFile"
    ).value = "";

    document.getElementById(
        "requirementModal"
    ).classList.add("show");

}


function closeRequirementModal() {

    document.getElementById(
        "requirementModal"
    ).classList.remove("show");

}


async function submitRequirement() {

    const file =
        document.getElementById(
            "requirementFile"
        ).files[0];


    if (!file) {

        showToast(
            "Please select a file."
        );

        return;

    }


    if (!application.id) { showToast("Your application record is unavailable."); return; }
    const formData = new FormData();
    formData.append("application_id", application.id);
    formData.append("document_type", application.requirements[selectedRequirementIndex].name);
    formData.append("document", file);
    try {
        const response = await fetch("api/upload_document.php", {method: "POST", body: formData});
        const payload = await response.json();
        if (!payload.ok) { showToast(payload.message || "Document could not be submitted."); return; }
    } catch (error) { showToast("Upload service is unavailable."); return; }

    application.requirements[selectedRequirementIndex].status = payload.verification_status || "Pending Check";


    application.requirements[
        selectedRequirementIndex
    ].file =
        file.name;


    localStorage.setItem(
        "mmv_application",
        JSON.stringify(application)
    );


    closeRequirementModal();

    renderPortal();

    showToast(
        "Requirement submitted successfully."
    );

}


/*
|--------------------------------------------------------------------------
| APPLICATION STATUS
|--------------------------------------------------------------------------
*/

function updateApplicationStatus() {

    const total =
        application.requirements.length;

    const submitted =
        application.requirements
            .filter(req =>
                req.status !== "Pending"
            ).length;


    if (submitted === 0) {

        application.status =
            "Pending Requirements";

        application.currentStage =
            1;

    }

    else if (submitted < total) {

        application.status =
            "Requirements Incomplete";

        application.currentStage =
            1;

    }

    else {

        application.status =
            "For Document Verification";

        application.currentStage =
            2;

    }


    localStorage.setItem(
        "mmv_application",
        JSON.stringify(application)
    );


    document.getElementById(
        "applicationStatus"
    ).textContent =
        application.status;

}


/*
|--------------------------------------------------------------------------
| APPLICATION TIMELINE
|--------------------------------------------------------------------------
*/

function renderTimeline() {

    const stages = [

        {
            title:
                "Application Registration",

            description:
                "Applicant registration and application information have been submitted."
        },

        {
            title:
                "Requirement Submission",

            description:
                "Submit all documents required for the selected job position."
        },

        {
            title:
                "Document Verification",

            description:
                "Submitted documents will be reviewed and verified by the recruitment team."
        },

        {
            title:
                "Intelligent Applicant Screening",

            description:
                "The applicant's qualifications will be evaluated against the job requirements using the planned SVM-based screening component."
        },

        {
            title:
                "Assessment",

            description:
                "Complete the required assessment configured for the selected position."
        },

        {
            title:
                "HR Review",

            description:
                "HR reviews the applicant information, requirements, screening results and assessment results."
        },

        {
            title:
                "Final Decision",

            description:
                "The recruitment team records the final application decision."
        }

    ];


    const current =
        application.currentStage;


    document.getElementById(
        "applicationTimeline"
    ).innerHTML =

        stages.map((stage,index) => {

            const number =
                index + 1;

            let state =
                "pending";

            let tag =
                "Upcoming";


            if (number < current) {

                state = "done";
                tag = "Completed";

            }

            else if (number === current) {

                state = "current";
                tag = "Current Stage";

            }


            return `

            <div class="stage ${state}">

                <span class="stage-dot"></span>

                <b>
                    ${number}. ${escapeHtml(stage.title)}
                </b>

                <small>
                    ${escapeHtml(stage.description)}
                </small>

                <span class="stage-tag">
                    ${tag}
                </span>

            </div>

            `;

        })
        .join("");

}


/*
|--------------------------------------------------------------------------
| LOGOUT / RESET
|--------------------------------------------------------------------------
| Since this is only a frontend prototype, this simply returns to the
| public job page. Data is retained in localStorage.
|--------------------------------------------------------------------------
*/

async function logoutApplicant() {
    try { await fetch('api/applicant_logout.php',{method:'POST'}); } catch (error) { console.warn('Applicant logout service is unavailable.'); }
    applicantAuthenticated = false;
    applicant = null;
    application = null;
    localStorage.removeItem("mmv_applicant");
    localStorage.removeItem("mmv_application");
    showView("jobs");
    showToast("You have been signed out.");
}


/*
|--------------------------------------------------------------------------
| UTILITIES
|--------------------------------------------------------------------------
*/

function formatDate(value) {

    if (!value) return "—";

    const date =
        new Date(value + "T00:00:00");

    return date.toLocaleDateString(
        "en-PH",
        {
            year:"numeric",
            month:"long",
            day:"numeric"
        }
    );

}


function escapeHtml(value) {

    return String(value ?? "")
        .replace(/[&<>"']/g, character => ({

            "&":"&amp;",
            "<":"&lt;",
            ">":"&gt;",
            '"':"&quot;",
            "'":"&#039;"

        }[character]));

}


function showToast(message) {

    const toast =
        document.getElementById("toast");

    toast.textContent =
        message;

    toast.classList.add("show");

    setTimeout(() => {

        toast.classList.remove("show");

    }, 2500);

}


/*
|--------------------------------------------------------------------------
| MODAL OUTSIDE CLICK
|--------------------------------------------------------------------------
*/

document.getElementById("jobModal")
    .addEventListener("click", event => {

        if (
            event.target.id === "jobModal"
        ) {

            closeJobModal();

        }

    });


document.getElementById("requirementModal")
    .addEventListener("click", event => {

        if (
            event.target.id === "requirementModal"
        ) {

            closeRequirementModal();

        }

    });


/*
|--------------------------------------------------------------------------
| INITIALIZE
|--------------------------------------------------------------------------
*/

async function loadJobsFromDatabase() {
    try {
        const response = await fetch('api/jobs.php');
        const payload = await response.json();
        if (!payload.ok || !Array.isArray(payload.jobs)) return;
        jobs = payload.jobs.map(job => ({
            id: Number(job.job_id), title: job.job_title, company: 'RJC Corporate Center',
            location: job.location || 'To be assigned', employment: job.employment_type || 'Full-time',
            salary: 'To be discussed', description: job.job_description || '',
            requirements: [job.required_skills, job.min_education_level !== 'None' ? `Minimum education: ${job.min_education_level}` : '', Number(job.min_years_experience || 0) > 0 ? `Minimum experience: ${job.min_years_experience} year(s)` : '', job.required_certifications].filter(Boolean),
            documents: (job.required_documents || '').split(',').map(x => x.trim()).filter(Boolean),
            assessment: job.assessment_name || 'No assessment configured'
        }));
        renderPublicJobs();
    } catch (error) { console.warn('Using prototype job postings because the database is unavailable.'); }
}

async function loadApplicantSession() {
    try {
        const response = await fetch('api/applicant_session.php');
        const payload = await response.json();
        applicantAuthenticated = Boolean(payload.authenticated);
        updateHeader();
    } catch (error) { console.warn('Applicant session is unavailable.'); }
}

renderPublicJobs();
loadJobsFromDatabase();
loadApplicantSession();

if (applicant && application) {

    updateHeader();

}

</script>

</body>
</html>
