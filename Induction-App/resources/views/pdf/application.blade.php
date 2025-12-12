<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Application Form - {{ $application->post->title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2e7d32;
            padding-bottom: 20px;
        }
        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #2e7d32;
            margin: 0;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background-color: #f0f7f0;
            color: #2e7d32;
            padding: 8px 15px;
            font-size: 14px;
            font-weight: bold;
            border-left: 4px solid #2e7d32;
            margin-bottom: 15px;
        }
        .row {
            width: 100%;
            margin-bottom: 10px;
        }
        .col {
            float: left;
            width: 50%;
        }
        .label {
            font-weight: bold;
            color: #555;
            font-size: 11px;
            text-transform: uppercase;
        }
        .value {
            margin-top: 2px;
            font-size: 12px;
            color: #000;
        }
        .photo-box {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 120px;
            height: 150px;
            border: 1px solid #ddd;
            padding: 5px;
            background: #fff;
        }
        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f9f9f9;
            font-weight: bold;
            color: #333;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title">FGEI Induction Portal</div>
            <div class="subtitle">Candidate Application Form</div>
            <div style="margin-top: 10px;">
                <span class="status-badge">{{ $application->status ?? 'Pending' }}</span>
            </div>
        </div>

        @if($application->user->candidateProfile && $application->user->candidateProfile->photo_path)
            <div class="photo-box">
                <img src="{{ public_path('storage/' . $application->user->candidateProfile->photo_path) }}" alt="Candidate Photo">
            </div>
        @endif

        <div class="section">
            <div class="section-title">Job Information</div>
            <div class="row clearfix">
                <div class="col">
                    <div class="label">Job Title</div>
                    <div class="value">{{ $application->post->title }}</div>
                </div>
                <div class="col">
                    <div class="label">Post Abbreviation</div>
                    <div class="value">{{ $application->post->abbreviation ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col">
                    <div class="label">BPS Scale</div>
                    <div class="value">{{ $application->post->bps ?? 'N/A' }}</div>
                </div>
                <div class="col">
                    <div class="label">Application ID</div>
                    <div class="value">#{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Personal Information</div>
            <div class="row clearfix">
                <div class="col">
                    <div class="label">Full Name</div>
                    <div class="value">{{ $application->user->name }}</div>
                </div>
                <div class="col">
                    <div class="label">Father's Name</div>
                    <div class="value">{{ $application->user->candidateProfile->father_name ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col">
                    <div class="label">CNIC</div>
                    <div class="value">{{ $application->user->candidateProfile->cnic ?? 'N/A' }}</div>
                </div>
                <div class="col">
                    <div class="label">Date of Birth</div>
                    <div class="value">{{ $application->user->candidateProfile->date_of_birth ? \Carbon\Carbon::parse($application->user->candidateProfile->date_of_birth)->format('d M, Y') : 'N/A' }}</div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col">
                    <div class="label">Gender</div>
                    <div class="value">{{ $application->user->candidateProfile->gender ?? 'N/A' }}</div>
                </div>
                <div class="col">
                    <div class="label">Religion</div>
                    <div class="value">{{ $application->user->candidateProfile->religion ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col">
                    <div class="label">Marital Status</div>
                    <div class="value">{{ $application->user->candidateProfile->marital_status ?? 'N/A' }}</div>
                </div>
                <div class="col">
                    <div class="label">Disability Status</div>
                    <div class="value">{{ $application->user->candidateProfile->disability ?? 'No' }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Contact Information</div>
            <div class="row clearfix">
                <div class="col">
                    <div class="label">Email Address</div>
                    <div class="value">{{ $application->user->email }}</div>
                </div>
                <div class="col">
                    <div class="label">Mobile Number</div>
                    <div class="value">{{ $application->user->userContact->mobile ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col" style="width: 100%;">
                    <div class="label">Postal Address</div>
                    <div class="value">{{ $application->user->userContact->postal_address ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="row clearfix" style="margin-top: 10px;">
                <div class="col" style="width: 100%;">
                    <div class="label">Permanent Address</div>
                    <div class="value">{{ $application->user->userContact->permanent_address ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        @if($application->user->qualifications && $application->user->qualifications->count() > 0)
        <div class="section">
            <div class="section-title">Academic Qualifications</div>
            <table>
                <thead>
                    <tr>
                        <th>Degree</th>
                        <th>Field of Study</th>
                        <th>Institution</th>
                        <th>Year</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($application->user->qualifications as $qualification)
                    <tr>
                        <td>{{ $qualification->degreeType->name ?? 'N/A' }}</td>
                        <td>{{ $qualification->degree_name }}</td>
                        <td>{{ $qualification->institute_name }}</td>
                        <td>{{ $qualification->passing_year }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div class="footer">
            <p>This is a computer-generated document. No signature is required.</p>
            <p>Generated on: {{ now()->format('d M, Y h:i A') }}</p>
        </div>
    </div>
</body>
</html>
