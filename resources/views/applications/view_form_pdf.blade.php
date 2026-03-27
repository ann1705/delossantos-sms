<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Application PDF</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; color: #1f2937; }
        .page { width: 100%; margin: 0 auto; padding: 1cm; box-sizing: border-box; }
        .header, .section { margin-bottom: 1rem; }
        .header .top { display: flex; justify-content: space-between; align-items: center; }
        .header img { width: 80px; height: auto; }
        .title { text-align: center; margin-bottom: .5rem; }
        h1 { font-size: 20px; font-weight: bold; }
        h2 { font-size: 12px; background: #e5e7eb; padding: .25rem .5rem; border: 1px solid #000; margin: 0; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        td { border: 1px solid #000; padding: 4px; vertical-align: top; }
        .strong { font-weight: bold; text-transform: uppercase; }
        .section-title { font-size: 10px; text-transform: uppercase; font-weight: 900; }
        .quiet { font-size: 9px; color: #4b5563; font-style: italic; }
        .checkbox { width: 12px; height: 12px; border: 1px solid #000; display: inline-block; margin-right: 4px; vertical-align: middle; text-align: center; }
        .checked { background: #000; color: #fff; font-size: 10px; line-height: 11px; }
        .doc-links a { color: #1d4ed8; text-decoration: none; }
    </style>
</head>
<body>
<div class="page">
    <div class="header">
        <div class="top">
            <img src="{{ asset('images/ched.png') }}" alt="CHED">
            <div class="title">
                <h1>Commission on Higher Education</h1>
                <p style="margin:0;font-size:11px;font-weight:700;text-transform:uppercase;">Unified Student Financial Assistance System for Tertiary Education</p>
                <p style="margin:0;font-size:10px;font-weight:900;text-transform:uppercase;">Tulong Dunong Program (TDP-TES)</p>
                <p style="margin:0;font-size:10px;font-weight:900;text-transform:uppercase;">Form Control No. #{{ str_pad($application->id, 8, '0', STR_PAD_LEFT) }}</p>
            </div>
            <img src="{{ asset('images/unifast.png') }}" alt="UniFAST">
        </div>
    </div>

    <div class="section">
        <div style="display:flex; justify-content:space-between; margin-bottom:.4rem;">
            <span class="quiet">Current Status: {{ strtoupper($applicantData->application_status ?? 'PENDING') }}</span>
            <span class="quiet">Date Filed: {{ $applicantData->date_accomplished }}</span>
        </div>
        <div style="width:90px; height:90px; border:1px solid #000; text-align:center; line-height:90px;">
            @if($applicantData->applicant_photo)
                <img src="{{ asset($applicantData->applicant_photo) }}" style="width:100%;height:100%;object-fit:cover;" alt="applicant photo" />
            @else
                NO PHOTO
            @endif
        </div>
    </div>

    <div class="section">
        <h2>I. Personal Information</h2>
        <table>
            <tr>
                <td><span class="strong">Surname</span><br>{{ $applicantData->last_name }}</td>
                <td><span class="strong">First Name</span><br>{{ $applicantData->first_name }}</td>
                <td><span class="strong">Middle Name</span><br>{{ $applicantData->middle_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><span class="strong">Date of Birth</span><br>{{ $applicantData->dob }}</td>
                <td><span class="strong">Sex</span><br>{{ ucfirst($applicantData->sex) }}</td>
                <td><span class="strong">Citizenship</span><br>{{ strtoupper($applicantData->citizenship) }}</td>
            </tr>
            <tr>
                <td colspan="2"><span class="strong">Place of Birth</span><br>{{ strtoupper($applicantData->place_of_birth) }}</td>
                <td><span class="strong">Birthplace Zip Code</span><br>{{ $applicantData->pob_zip_code }}</td>
            </tr>
            <tr>
                <td colspan="2"><span class="strong">Permanent Address</span><br>{{ strtoupper($applicantData->permanent_address) }}</td>
                <td><span class="strong">Address Zip Code</span><br>{{ $applicantData->zip_code }}</td>
            </tr>
            <tr>
                <td><span class="strong">Mobile Number</span><br>{{ $applicantData->mobile_number }}</td>
                <td><span class="strong">Email</span><br>{{ $applicantData->email_address ?? 'N/A' }}</td>
                <td><span class="strong">Tribal Membership</span><br>{{ strtoupper($applicantData->tribal_membership ?? 'NONE') }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>II. Academic Profile</h2>
        <table>
            <tr>
                <td><span class="strong">HEI</span><br>{{ strtoupper($applicantData->school_name) }}</td>
                <td><span class="strong">School ID No.</span><br>{{ $applicantData->school_id_number ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><span class="strong">School Address</span><br>{{ strtoupper($applicantData->school_address) }}</td>
                <td><span class="strong">School Sector</span><br>{{ strtoupper($applicantData->school_sector) }}</td>
            </tr>
            <tr>
                <td><span class="strong">Course / Program</span><br>{{ strtoupper($applicantData->course ?? $application->course) }}</td>
                <td><span class="strong">Year Level</span><br>{{ strtoupper($applicantData->year_level) }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>III. Family Background</h2>
        <table>
            <tr>
                <td><span class="strong">Father Status</span><br>{{ ucfirst($applicantData->father_status ?? 'N/A') }}</td>
                <td><span class="strong">Father Name</span><br>{{ $applicantData->father_name ?? 'N/A' }}</td>
                <td><span class="strong">Occupation</span><br>{{ strtoupper($applicantData->father_occupation ?? 'N/A') }}</td>
            </tr>
            <tr>
                <td colspan="3"><span class="strong">Father Address</span><br>{{ strtoupper($applicantData->father_address ?? 'N/A') }}</td>
            </tr>
            <tr>
                <td><span class="strong">Mother Status</span><br>{{ ucfirst($applicantData->mother_status ?? 'N/A') }}</td>
                <td><span class="strong">Mother Name</span><br>{{ $applicantData->mother_name ?? 'N/A' }}</td>
                <td><span class="strong">Occupation</span><br>{{ strtoupper($applicantData->mother_occupation ?? 'N/A') }}</td>
            </tr>
            <tr>
                <td colspan="3"><span class="strong">Mother Address</span><br>{{ strtoupper($applicantData->mother_address ?? 'N/A') }}</td>
            </tr>
            <tr>
                <td><span class="strong">Total Income</span><br>₱{{ number_format($applicantData->total_income ?? 0, 2) }}</td>
                <td><span class="strong">No. of Siblings</span><br>{{ $applicantData->siblings_count ?? 0 }}</td>
                <td><span class="strong">Receiving assistance?</span><br>{{ $applicantData->has_assistance ? 'YES':'NO' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>(Do Not Fill-Out This Portion For Chedro Use Only)</h2>
        <table>
            <tr>
                <td><span class="strong">COR/COE Verified</span></td>
                <td><div class="checkbox {{ $applicantData->admin_check_cor ? 'checked' : '' }}">{{ $applicantData->admin_check_cor ? '✓' : '' }}</div></td>
                <td><span class="strong">Indigency Certificate Verified</span></td>
                <td><div class="checkbox {{ $applicantData->admin_check_indigency ? 'checked' : '' }}">{{ $applicantData->admin_check_indigency ? '✓' : '' }}</div></td>
            </tr>
            <tr>
                <td colspan="4"><span class="strong">Set Application Status</span><br>{{ strtoupper($applicantData->application_status) }}</td>
            </tr>
            <tr>
                <td colspan="4"><span class="strong">Evaluated by</span><br>{{ strtoupper($applicantData->evaluated_by ?? 'N/A') }}</td>
            </tr>
            <tr>
                <td colspan="4"><span class="strong">Regional Coordinator</span><br>{{ strtoupper($applicantData->regional_coordinator ?? 'N/A') }}</td>
            </tr>
            <tr>
                <td colspan="4"><span class="strong">Official Remarks</span><br>{{ $applicantData->admin_remarks ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Digitally Uploaded Attachments</h2>
        <p class="doc-links">Cor: {{ $applicantData->enrollment_proof ? 'Yes' : 'No' }} • Indigency: {{ $applicantData->indigency_certificate ? 'Yes' : 'No' }}</p>
    </div>

    <div class="section">
        <h2>Signature</h2>
        <p>{{ strtoupper($applicantData->first_name . ' ' .($applicantData->middle_name ?? '') . ' ' . $applicantData->last_name) }}</p>
        @if($applicantData->signature_path)
            <img src="{{ asset($applicantData->signature_path) }}" alt="Signature" style="width: 120px; height:auto;" />
        @else
            <p>N/A</p>
        @endif
        <p style="margin-top:5px; font-size:9px;">Accomplished: {{ $applicantData->date_accomplished }}</p>
    </div>
</div>
</body>
</html>
