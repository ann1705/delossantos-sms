<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Application PDF</title>
    <style>
        @page { size: A4 portrait; margin: 15mm; }
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; color: #111827; }
        .page { padding: 0; width: 100%; box-sizing: border-box; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 0.75rem; }
        .header-table td { vertical-align: middle; }
        .header-logo { width: 90px; }
        .header-logo img { max-width: 90px; height: auto; display: block; }
        .header-title { text-align: center; }
        .header-title h1 { margin: 0 0 4px; font-size: 16px; font-weight: 900; text-transform: uppercase; }
        .header-title p { margin: 2px 0; font-size: 8px; line-height: 1.1; text-transform: uppercase; }
        .header-separator { border-top: 1px solid #000; margin: 0.5rem 0; }
        .info-table { width: 100%; border-collapse: collapse; font-size: 10px; margin-bottom: 1rem; }
        .info-table td { border: 1px solid #000; padding: 6px 8px; vertical-align: top; }
        .info-label { display: block; color: #4b5563; font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em; margin-bottom: 3px; }
        .info-value { font-weight: 700; text-transform: uppercase; color: #111827; }
        .section-title { background: #f3f4f6; border: 1px solid #000; padding: 5px 8px; font-size: 10px; font-weight: 900; text-transform: uppercase; }
        .data-row td { border-top: none; }
        .photo-block { width: 110px; height: 110px; border: 1px solid #000; display: inline-flex; align-items: center; justify-content: center; background: #f9fafb; }
        .photo-block img { max-width: 100%; max-height: 100%; display: block; }
        .photo-placeholder { font-size: 8px; font-weight: 900; color: #9ca3af; text-transform: uppercase; text-align: center; padding: 0 4px; }
        .signature-area { width: 100%; display: flex; justify-content: flex-end; margin-top: 1rem; font-size: 9px; }
        .signature-panel { display: inline-block; width: 280px; text-align: center; }
        .signature-image { max-height: 60px; width: auto; display: block; margin: 0 auto 0.25rem; }
        .signature-line { display: inline-block; border-top: 1px solid #000; padding-top: 4px; width: 100%; box-sizing: border-box; }
        .signature-name { font-weight: 900; text-transform: uppercase; font-size: 10px; margin: 0; }
        .signature-note { margin: 2px 0 0; font-size: 7.5px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.02em; }
        .small-note { font-size: 7.5px; color: #6b7280; text-transform: uppercase; }
        .eval-table { width: 100%; border-collapse: collapse; font-size: 9px; margin-bottom: 1rem; }
        .eval-table td { border: none; padding: 3px 4px; vertical-align: top; }
        .checkbox.checked { background-color: #000; }
        .eval-label { font-size: 7.5px; font-weight: 900; text-transform: uppercase; color: #374151; margin-bottom: 4px; display: block; }
        .checkbox { display: inline-block; width: 12px; height: 12px; border: 1px solid #000; margin-right: 6px; vertical-align: middle; text-align: center; line-height: 12px; font-size: 10px; font-weight: bold; }
        .signature-area { width: 100%; text-align: right; font-size: 9px; }
        .signature-line { display: inline-block; border-top: 1px solid #000; padding-top: 4px; width: 260px; }
        .signature-name { font-weight: 900; text-transform: uppercase; font-size: 10px; margin: 0; }
        .signature-note { margin: 2px 0 0; font-size: 7.5px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.02em; }
        .small-note { font-size: 7.5px; color: #6b7280; text-transform: uppercase; }
        .docs-row td { padding: 4px; }
        .docs-box { border: 1px solid #2563eb; color: #2563eb; font-size: 8.5px; font-weight: 900; text-transform: uppercase; text-align: center; padding: 8px; }
        .docs-box.missing { border-color: #f87171; color: #b91c1c; }
    </style>
</head>
<body>
<div class="page">
    @php
        $chedFile = public_path('images/ched.png');
        $unifastFile = public_path('images/unifast.png');
        $photoRelative = $applicantData->applicant_photo ? ltrim($applicantData->applicant_photo, '/') : null;
        $signatureRelative = $applicantData->signature_path ? ltrim($applicantData->signature_path, '/') : null;
        $photoFile = $photoRelative ? public_path($photoRelative) : null;
        $signatureFile = $signatureRelative ? public_path($signatureRelative) : null;

        $imageMime = function ($path) {
            if (! $path || ! file_exists($path)) {
                return null;
            }
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $mime = match ($ext) {
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                default => 'image/png',
            };
            return 'data:'.$mime.';base64,'.base64_encode(file_get_contents($path));
        };

        $chedLogo = $imageMime($chedFile);
        $unifastLogo = $imageMime($unifastFile);
        $photoPath = $imageMime($photoFile);
        $signaturePath = $imageMime($signatureFile);
    @endphp

    <table class="header-table">
        <tr>
            <td class="header-logo"><img src="{{ $chedLogo }}" alt="CHED"></td>
            <td class="header-title">
                <h1>Commission on Higher Education</h1>
                <p>Unified Student Financial Assistance System for Tertiary Education</p>
                <p>Tulong Dunong Program (TDP-TES)</p>
                <p>Form Control No. #{{ str_pad($applicantData->id, 8, '0', STR_PAD_LEFT) }}</p>
            </td>
            <td class="header-logo" style="text-align:right;"><img src="{{ $unifastLogo }}" alt="UniFAST"></td>
        </tr>
    </table>

    <div class="header-separator"></div>

    <table class="info-table">
        <tr>
            <td style="border-right:none; width: 65%;">
                <span class="info-label">Current Status</span>
                <span class="info-value">{{ strtoupper($applicantData->application_status ?? 'PENDING') }}</span>
            </td>
            <td style="border-left:none; width: 35%;">
                <span class="info-label">Date Filed</span>
                <span class="info-value">{{ $applicantData->date_accomplished }}</span>
            </td>
        </tr>
    </table>

    <div style="margin-bottom:1rem;">
        <div class="photo-block">
            @if($photoPath)
                <img src="{{ $photoPath }}" alt="Applicant Photo">
            @else
                <div class="photo-placeholder">Applicant Photo</div>
            @endif
        </div>
    </div>

    <div class="section-title">I. Personal Information</div>
    <table class="info-table">
        <tr>
            <td><span class="info-label">Surname</span><span class="info-value">{{ $applicantData->last_name }}</span></td>
            <td><span class="info-label">First Name</span><span class="info-value">{{ $applicantData->first_name }}</span></td>
            <td><span class="info-label">Middle Name</span><span class="info-value">{{ $applicantData->middle_name ?? 'N/A' }}</span></td>
        </tr>
        <tr>
            <td><span class="info-label">Date of Birth</span><span class="info-value">{{ $applicantData->dob }}</span></td>
            <td><span class="info-label">Sex</span><span class="info-value">{{ strtoupper($applicantData->sex) }}</span></td>
            <td><span class="info-label">Citizenship</span><span class="info-value">{{ strtoupper($applicantData->citizenship) }}</span></td>
        </tr>
        <tr>
            <td colspan="2"><span class="info-label">Place of Birth</span><span class="info-value">{{ strtoupper($applicantData->place_of_birth) }}</span></td>
            <td><span class="info-label">Birthplace Zip Code</span><span class="info-value">{{ $applicantData->pob_zip_code }}</span></td>
        </tr>
        <tr>
            <td colspan="2"><span class="info-label">Permanent Address</span><span class="info-value">{{ strtoupper($applicantData->permanent_address) }}</span></td>
            <td><span class="info-label">Address Zip Code</span><span class="info-value">{{ $applicantData->zip_code }}</span></td>
        </tr>
        <tr>
            <td><span class="info-label">Mobile Number</span><span class="info-value">{{ $applicantData->mobile_number }}</span></td>
            <td><span class="info-label">Email Address</span><span class="info-value" style="font-style:italic;">{{ $applicantData->email_address ?? 'N/A' }}</span></td>
            <td><span class="info-label">Tribal Membership</span><span class="info-value">{{ strtoupper($applicantData->tribal_membership ?? 'NONE') }}</span></td>
        </tr>
        <tr>
            <td colspan="3"><span class="info-label">Disability Type (If applicable)</span><span class="info-value">{{ strtoupper($applicantData->disability_type ?? 'NONE') }}</span></td>
        </tr>
    </table>

    <div class="section-title">II. Academic Profile</div>
    <table class="info-table">
        <tr>
            <td colspan="2"><span class="info-label">Higher Education Institution (HEI)</span><span class="info-value">{{ strtoupper($applicantData->school_name) }}</span></td>
            <td><span class="info-label">School ID No.</span><span class="info-value">{{ $applicantData->school_id_number ?? 'N/A' }}</span></td>
        </tr>
        <tr>
            <td colspan="2"><span class="info-label">School Address</span><span class="info-value">{{ strtoupper($applicantData->school_address) }}</span></td>
            <td><span class="info-label">School Sector</span><span class="info-value">{{ strtoupper($applicantData->school_sector) }}</span></td>
        </tr>
        <tr>
            <td colspan="2"><span class="info-label">Course / Program</span><span class="info-value">{{ strtoupper($applicantData->course) }}</span></td>
            <td><span class="info-label">Year Level</span><span class="info-value">{{ strtoupper($applicantData->year_level ?? 'N/A') }}</span></td>
        </tr>
    </table>

    <div class="section-title">III. Family Background</div>
    <table class="info-table">
        <tr>
            <td><span class="info-label">Father Status</span><span class="info-value">{{ $applicantData->father_status }}</span></td>
            <td><span class="info-label">Father Full Name</span><span class="info-value">{{ $applicantData->father_name ?? 'N/A' }}</span></td>
            <td><span class="info-label">Occupation</span><span class="info-value">{{ strtoupper($applicantData->father_occupation ?? 'N/A') }}</span></td>
        </tr>
        <tr>
            <td colspan="3"><span class="info-label">Father Address</span><span class="info-value">{{ strtoupper($applicantData->father_address ?? 'SAME AS PERMANENT') }}</span></td>
        </tr>
        <tr>
            <td><span class="info-label">Mother Status</span><span class="info-value">{{ $applicantData->mother_status }}</span></td>
            <td><span class="info-label">Mother Full Name</span><span class="info-value">{{ $applicantData->mother_name ?? 'N/A' }}</span></td>
            <td><span class="info-label">Occupation</span><span class="info-value">{{ strtoupper($applicantData->mother_occupation ?? 'N/A') }}</span></td>
        </tr>
        <tr>
            <td colspan="3"><span class="info-label">Mother Address</span><span class="info-value">{{ strtoupper($applicantData->mother_address ?? 'SAME AS PERMANENT') }}</span></td>
        </tr>
        <tr>
            <td><span class="info-label">Annual Gross Income</span><span class="info-value">PHP {{ number_format($applicantData->total_income, 2) }}</span></td>
            <td><span class="info-label">No. of Siblings</span><span class="info-value">{{ $applicantData->siblings_count }}</span></td>
            <td><span class="info-label">Receiving other assistance?</span><span class="info-value">{{ strtoupper($applicantData->has_assistance ? 'YES' : 'NO') }}</span></td>
        </tr>
    </table>

    @php $review = $applicantData->latestReview; @endphp
    <table class="eval-table">
        <tr>
            <td class="eval-box" style="width:50%;">
                <span class="eval-label">Official Evaluation Checklist:</span>
                <div><span class="checkbox {{ optional($review)->admin_check_cor ? 'checked' : '' }}"></span>COR/COE Verified</div>
                <div style="margin-top:4px;"><span class="checkbox {{ optional($review)->admin_check_indigency ? 'checked' : '' }}"></span>Indigency Certificate Verified</div>
                <div style="margin-top:8px;"><span class="eval-label">Set Application Status:</span><span class="info-value">{{ strtoupper($applicantData->application_status) }}</span></div>
            </td>
            <td class="eval-box" style="width:50%;">
                <div style="text-align:center; padding-top:12px;">
                    <span class="info-value">{{ strtoupper(optional($review)->evaluated_by ?? '..............................') }}</span>
                    <div class="signature-note">Evaluated / Processed By</div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="eval-box">
                <span class="eval-label">Official Remarks:</span>
                <div style="font-size:9px; text-transform:uppercase; color:#374151;">{{ optional($review)->admin_remarks ?? 'No remarks provided' }}</div>
            </td>
        </tr>
    </table>

    <table class="info-table docs-row">
        <tr>
            <td class="docs-box" style="width:50%;">{{ $applicantData->enrollment_proof ? 'VIEW ATTACHED COR' : 'VIEW ATTACHED COR (MISSING)' }}</td>
            <td class="docs-box {{ $applicantData->indigency_certificate ? '' : 'missing' }}" style="width:50%;">{{ $applicantData->indigency_certificate ? 'VIEW ATTACHED INDIGENCY' : 'VIEW ATTACHED INDIGENCY (MISSING)' }}</td>
        </tr>
    </table>

    <div class="signature-area">
        <div class="signature-panel">
            @if($signaturePath)
                <img src="{{ $signaturePath }}" class="signature-image" alt="Signature">
            @endif
            <div class="signature-line">
                <p class="signature-name">{{ strtoupper(trim($applicantData->first_name.' '.$applicantData->middle_name.' '.$applicantData->last_name)) }}</p>
                <p class="signature-note">Signature of applicant over printed name</p>
                <p class="small-note">Accomplished: {{ $applicantData->date_accomplished }}</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
