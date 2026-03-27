@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-indigo-50/50 py-10 px-4 mt-10">
    <div class="max-w-4xl mx-auto">

        @php
            $editing = isset($application) && $application;
            $applicantData = $applicantData ?? null;
            $currentStatus = $applicantData?->application_status ?? $application?->status ?? 'pending';
            $canEdit = !$editing || $currentStatus === 'pending';
        @endphp

        @if($errors->has('error') || session('error'))
            <div class="max-w-4xl mx-auto mb-6 bg-red-50 border-2 border-red-300 rounded-2xl p-6">
                <div class="flex items-start gap-4">
                    <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <div>
                        <h3 class="font-black text-red-700 uppercase tracking-tight mb-1">Access Denied</h3>
                        <p class="text-sm text-red-600">{{ $errors->first('error') ?? session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($editing && !$canEdit)
            <div class="max-w-4xl mx-auto mb-6 bg-red-50 border-2 border-red-300 rounded-2xl p-6">
                <div class="flex items-start gap-4">
                    <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <div>
                        <h3 class="font-black text-red-700 uppercase tracking-tight mb-1">Application Status: {{ ucfirst($currentStatus) }}</h3>
                        <p class="text-sm text-red-600">Your application has been {{ $currentStatus }}. You can no longer edit or update this application. Please contact the CHED Regional Office if you have any inquiries.</p>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ $canEdit ? ($editing ? route('applications.update', $application->id) : route('applications.store')) : 'javascript:void(0)' }}" method="POST" enctype="multipart/form-data" {{ !$canEdit ? 'onsubmit="event.preventDefault(); alert(\'Your application status is ' . ucfirst($currentStatus) . ' and cannot be modified.\');"' : '' }}>
            @csrf
            @if($editing && $canEdit)
                @method('PUT')
            @endif

            <div class="bg-white rounded-t-xl shadow-md border-t-[10px] border-blue-700 p-8 mb-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-6">
                    <div class="w-24 h-24 flex items-center justify-center">
                        <img src="{{ asset('images/ched.png') }}" alt="CHED" class="max-h-full max-w-full object-contain">
                    </div>

                    <div class="text-center flex-1">
                        <h1 class="text-3xl font-black text-gray-900 mb-1 uppercase tracking-tighter">Scholarship Application Form</h1>
                        <p class="text-sm text-blue-700 font-bold uppercase tracking-tight">UniFAST Tertiary Education Subsidy (TDP) Program</p>
                        <p class="text-[11px] text-gray-500 italic mt-3 max-w-lg mx-auto leading-relaxed">
                            Instructions: Read General and Documentary Requirements. Fill in all the required information. Do not leave an item blank. If an item is not applicable, indicate "NA".
                        </p>
                    </div>

                    <div class="w-24 h-24 flex items-center justify-center">
                        <img src="{{ asset('images/unifast.png') }}" alt="UniFAST" class="max-h-full max-w-full object-contain">
                    </div>
                </div>

                <div class="mt-4 flex items-center text-sm font-bold text-gray-600">
                    {{ Auth::user()->email }} <span class="ml-2 text-blue-600 font-normal underline">Logged in</span>
                </div>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200 mb-6 space-y-6">
                <h2 class="text-xl font-black text-blue-900 border-b-2 border-blue-50 pb-2 uppercase tracking-tighter">Personal Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center bg-gray-50 overflow-hidden relative">
                        <input type="file" name="applicant_photo" @if(!$editing) required @endif class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="previewImage(this)">
                        @if($editing && $applicantData && $applicantData->applicant_photo)
                            <img id="photo_preview" src="{{ asset($applicantData->applicant_photo) }}" class="absolute inset-0 w-full h-full object-cover">
                        @else
                            <img id="photo_preview" class="hidden absolute inset-0 w-full h-full object-cover">
                        @endif
                        <div id="photo_placeholder" class="text-center p-2">
                            <svg class="w-8 h-8 mx-auto text-gray-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="text-[9px] font-black uppercase text-gray-400">Upload 2x2 Photo @if(!$editing)*@endif</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Last Name *</label>
                        <input type="text" name="last_name" required value="{{ old('last_name', $applicantData->last_name ?? '') }}" class="w-full border-b border-gray-300 focus:border-blue-600 outline-none py-2 text-gray-800">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">First Name *</label>
                        <input type="text" name="first_name" required value="{{ old('first_name', $applicantData->first_name ?? '') }}" class="w-full border-b border-gray-300 focus:border-blue-600 outline-none py-2 text-gray-800">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name', $applicantData->middle_name ?? '') }}" class="w-full border-b border-gray-300 focus:border-blue-600 outline-none py-2 text-gray-800">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase text-blue-700">Maiden Name (for married women)</label>
                        <input type="text" name="maiden_name" placeholder="NA" value="{{ old('maiden_name', $applicantData->maiden_name ?? '') }}" class="w-full border-b border-gray-300 focus:border-blue-600 outline-none py-2 text-gray-800">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Date of Birth *</label>
                        <input type="date" name="dob" required value="{{ old('dob', $applicantData->dob ?? '') }}" class="w-full border-b border-gray-300 focus:border-blue-600 outline-none py-2 text-gray-800">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Sex *</label>
                        <div class="flex gap-4 mt-2">
                            <label class="text-sm"><input type="radio" name="sex" value="male" required {{ old('sex', $applicantData->sex ?? '') == 'male' ? 'checked' : '' }} class="mr-1"> Male</label>
                            <label class="text-sm"><input type="radio" name="sex" value="female" required {{ old('sex', $applicantData->sex ?? '') == 'female' ? 'checked' : '' }} class="mr-1"> Female</label>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 pt-4">
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Place of Birth *</label>
                        <input type="text" name="place_of_birth" placeholder="Town/City, Province" required value="{{ old('place_of_birth', $applicantData->place_of_birth ?? '') }}" class="w-full border-b border-gray-300 focus:border-blue-600 outline-none py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">POB Zip Code *</label>
                        <input type="text" name="pob_zip_code" required value="{{ old('pob_zip_code', $applicantData->pob_zip_code ?? '') }}" class="w-full border-b border-gray-300 focus:border-blue-600 outline-none py-2">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Permanent Address *</label>
                        <input type="text" name="permanent_address" placeholder="Street/Brgy, Town/City, Province" required value="{{ old('permanent_address', $applicantData->permanent_address ?? '') }}" class="w-full border-b border-gray-300 focus:border-blue-600 outline-none py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Zip Code *</label>
                        <input type="text" name="zip_code" required value="{{ old('zip_code', $applicantData->zip_code ?? '') }}" class="w-full border-b border-gray-300 focus:border-blue-600 outline-none py-2">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Citizenship *</label>
                        <input type="text" name="citizenship" required value="{{ old('citizenship', $applicantData->citizenship ?? 'Filipino') }}" class="w-full border-b border-gray-300 focus:border-blue-600 outline-none py-2">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Tribal Membership (if any)</label>
                        <input type="text" name="tribal_membership" placeholder="Indicate Tribe or NA" value="{{ old('tribal_membership', $applicantData->tribal_membership ?? '') }}" class="w-full border-b border-gray-300 focus:border-blue-600 outline-none py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Disability Type (if any)</label>
                        <input type="text" name="disability_type" placeholder="Indicate Disability or NA" value="{{ old('disability_type', $applicantData->disability_type ?? '') }}" class="w-full border-b border-gray-300 focus:border-blue-600 outline-none py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Mobile Number *</label>
                        <input type="text" name="mobile_number" required value="{{ old('mobile_number', $applicantData->mobile_number ?? '') }}" class="w-full border-b border-gray-300 focus:border-blue-600 outline-none py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Email Address</label>
                        <input type="email" name="email_address" value="{{ old('email_address', $applicantData->email_address ?? '') }}" class="w-full border-b border-gray-300 focus:border-blue-600 outline-none py-2">
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200 mb-6 space-y-6">
                <h2 class="text-xl font-black text-blue-900 border-b-2 border-blue-50 pb-2 uppercase tracking-tighter">Academic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Name of School Attended *</label>
                        <input type="text" name="school_name" required value="{{ old('school_name', $applicantData->school_name ?? '') }}" class="w-full border-b border-gray-300 focus:border-blue-600 outline-none py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">School ID Number</label>
                        <input type="text" name="school_id_number" value="{{ old('school_id_number', $applicantData->school_id_number ?? '') }}" class="w-full border-b border-gray-300 focus:border-blue-600 outline-none py-2">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Course / Program *</label>
                        <input type="text" name="course" placeholder="e.g. BS in Information Technology" required value="{{ old('course', $applicantData->course ?? $application->course ?? '') }}" class="w-full border-b border-gray-300 focus:border-blue-600 outline-none py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">School Sector *</label>
                        <div class="flex gap-4 mt-2">
                            <label class="text-sm"><input type="radio" name="school_sector" value="public" required {{ old('school_sector', $applicantData->school_sector ?? '') == 'public' ? 'checked' : '' }} class="mr-1"> Public</label>
                            <label class="text-sm"><input type="radio" name="school_sector" value="private" required {{ old('school_sector', $applicantData->school_sector ?? '') == 'private' ? 'checked' : '' }} class="mr-1"> Private</label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Year Level *</label>
                        <select name="year_level" required class="w-full border-b border-gray-300 focus:border-blue-600 outline-none py-2">
                            <option value="1" {{ old('year_level', $applicantData->year_level ?? $application->year_level ?? '') == '1' ? 'selected' : '' }}>1st Year</option>
                            <option value="2" {{ old('year_level', $applicantData->year_level ?? $application->year_level ?? '') == '2' ? 'selected' : '' }}>2nd Year</option>
                            <option value="3" {{ old('year_level', $applicantData->year_level ?? $application->year_level ?? '') == '3' ? 'selected' : '' }}>3rd Year</option>
                            <option value="4" {{ old('year_level', $applicantData->year_level ?? $application->year_level ?? '') == '4' ? 'selected' : '' }}>4th Year</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase">School Address *</label>
                        <input type="text" name="school_address" required value="{{ old('school_address', $applicantData->school_address ?? '') }}" class="w-full border-b border-gray-300 focus:border-blue-600 outline-none py-2">
                    </div>
                </div>

                <div class="pt-6 border-t border-blue-50">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-3">Are you enjoying other educational financial assistance? *</label>
                    @php $assistance = old('has_other_assistance', optional($applicantData)->has_assistance ? 'yes' : 'no'); @endphp
                    <div class="flex gap-6 mb-4">
                        <label class="text-sm font-bold flex items-center cursor-pointer text-gray-700">
                            <input type="radio" name="has_other_assistance" value="yes" class="mr-2 h-4 w-4 text-blue-600" onclick="toggleAssistance(true)" {{ $assistance == 'yes' ? 'checked' : '' }}> Yes
                        </label>
                        <label class="text-sm font-bold flex items-center cursor-pointer text-gray-700">
                            <input type="radio" name="has_other_assistance" value="no" class="mr-2 h-4 w-4 text-blue-600" onclick="toggleAssistance(false)" {{ $assistance == 'no' ? 'checked' : '' }}> No
                        </label>
                    </div>

                    <div id="assistance_details" class="{{ $assistance == 'yes' ? 'block' : 'hidden' }} space-y-4 bg-blue-50/50 p-4 rounded-lg border border-blue-100">
                        <p class="text-[10px] font-black text-blue-800 uppercase mb-2 italic">If yes, please specify:</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="text" name="other_assistance_1" placeholder="1. Name of Scholarship/Grant" value="{{ old('other_assistance_1', $applicantData->other_assistance_1 ?? '') }}" class="w-full border-b border-gray-300 focus:border-blue-600 outline-none py-2 text-sm bg-transparent">
                            <input type="text" name="other_assistance_2" placeholder="2. Name of Scholarship/Grant" value="{{ old('other_assistance_2', $applicantData->other_assistance_2 ?? '') }}" class="w-full border-b border-gray-300 focus:border-blue-600 outline-none py-2 text-sm bg-transparent">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200 mb-6 space-y-6">
                <h2 class="text-xl font-black text-blue-900 border-b-2 border-blue-50 pb-2 uppercase tracking-tighter">Family Background</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-4">
                        <p class="text-sm font-bold text-gray-800 underline decoration-blue-500 uppercase">Father's Info</p>
                        @php $fatherStatus = old('father_status', $applicantData->father_status ?? 'living'); @endphp
                        <div class="flex gap-4">
                            <label class="text-xs font-bold"><input type="radio" name="father_status" value="living" class="mr-1" {{ $fatherStatus == 'living' ? 'checked' : '' }}> Living</label>
                            <label class="text-xs font-bold"><input type="radio" name="father_status" value="deceased" class="mr-1" {{ $fatherStatus == 'deceased' ? 'checked' : '' }}> Deceased</label>
                        </div>
                        <input type="text" name="father_name" placeholder="Full Name" value="{{ old('father_name', $applicantData->father_name ?? '') }}" class="w-full border-b py-2 text-sm outline-none focus:border-blue-600">
                        <input type="text" name="father_occupation" placeholder="Occupation" value="{{ old('father_occupation', $applicantData->father_occupation ?? '') }}" class="w-full border-b py-2 text-sm outline-none focus:border-blue-600">
                        <input type="text" name="father_address" placeholder="Permanent Address" value="{{ old('father_address', $applicantData->father_address ?? '') }}" class="w-full border-b py-2 text-sm outline-none focus:border-blue-600">
                    </div>
                    <div class="space-y-4">
                        <p class="text-sm font-bold text-gray-800 underline decoration-blue-500 uppercase">Mother's Info</p>
                        @php $motherStatus = old('mother_status', $applicantData->mother_status ?? 'living'); @endphp
                        <div class="flex gap-4">
                            <label class="text-xs font-bold"><input type="radio" name="mother_status" value="living" class="mr-1" {{ $motherStatus == 'living' ? 'checked' : '' }}> Living</label>
                            <label class="text-xs font-bold"><input type="radio" name="mother_status" value="deceased" class="mr-1" {{ $motherStatus == 'deceased' ? 'checked' : '' }}> Deceased</label>
                        </div>
                        <input type="text" name="mother_name" placeholder="Full Name" value="{{ old('mother_name', $applicantData->mother_name ?? '') }}" class="w-full border-b py-2 text-sm outline-none focus:border-blue-600">
                        <input type="text" name="mother_occupation" placeholder="Occupation" value="{{ old('mother_occupation', $applicantData->mother_occupation ?? '') }}" class="w-full border-b py-2 text-sm outline-none focus:border-blue-600">
                        <input type="text" name="mother_address" placeholder="Permanent Address" value="{{ old('mother_address', $applicantData->mother_address ?? '') }}" class="w-full border-b py-2 text-sm outline-none focus:border-blue-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-blue-50">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Total Parents Gross Income *</label>
                        <input type="number" name="total_income" required value="{{ old('total_income', $applicantData->total_income ?? '') }}" class="w-full border-b border-gray-300 py-2 outline-none focus:border-blue-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Number of Siblings *</label>
                        <input type="number" name="siblings_count" required value="{{ old('siblings_count', $applicantData->siblings_count ?? '') }}" class="w-full border-b border-gray-300 py-2 outline-none focus:border-blue-600">
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200 mb-6 space-y-6">
                <h2 class="text-xl font-black text-blue-900 border-b-2 border-blue-50 pb-2 uppercase tracking-tighter">Documentary Requirements</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="p-4 border rounded-lg bg-blue-50/30">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Certificate of Indigency @if(!$editing)*@endif</label>
                        <input type="file" name="indigency_certificate" @if(!$editing) required @endif class="text-xs file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-700 file:text-white cursor-pointer">
                        @if($editing && $applicantData && $applicantData->indigency_certificate)
                            <p class="text-[10px] text-green-700 mt-2">Existing file: <a href="{{ asset($applicantData->indigency_certificate) }}" target="_blank" class="underline">View</a></p>
                        @endif
                    </div>
                    <div class="p-4 border rounded-lg bg-blue-50/30">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">CORs / COEs @if(!$editing)*@endif</label>
                        <input type="file" name="enrollment_proof" @if(!$editing) required @endif class="text-xs file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-700 file:text-white cursor-pointer">
                        @if($editing && $applicantData && $applicantData->enrollment_proof)
                            <p class="text-[10px] text-green-700 mt-2">Existing file: <a href="{{ asset($applicantData->enrollment_proof) }}" target="_blank" class="underline">View</a></p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200 mb-6">

                <h2 class="text-xl font-black text-blue-900 border-b-2 border-blue-50 pb-2 uppercase mb-6 tracking-tighter">Certification</h2>
                <label class="block text-center font-bold text-gray-500 uppercase mb-4">I hereby certify that foregoing statements are true and correct</label>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

                    <div>

                        <label class="block text-xs font-bold text-gray-500 uppercase mb-4">Upload Signature @if(!$editing)*@endif</label>
                        <input type="file" name="signature_file" @if(!$editing) required @endif class="text-xs file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-50 file:text-blue-700">
                        @if($editing && $applicantData && $applicantData->signature_path)
                            <p class="text-[10px] text-green-700 mt-2">Existing signature image uploaded.</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Date Accomplished</label>
                        <div class="w-full border-b border-gray-100 py-2 text-gray-400 font-bold bg-gray-50 px-2 rounded">
                            {{ date('F d, Y') }}
                        </div>
                        <input type="hidden" name="date_accomplished" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
            </div>
<div class="bg-gray-100 p-8 rounded-xl border-2 border-dashed border-gray-300 mb-6 select-none cursor-not-allowed">
                <h2 class="text-center font-black text-gray-400 mb-8 tracking-widest uppercase text-sm">(Do Not Fill-Out This Portion For Chedro Use Only)</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 opacity-50">
                    <div class="space-y-3">
                        <p class="text-[10px] font-black text-gray-600 underline uppercase">Documents Attached:</p>
                        <label class="flex items-center text-xs font-bold text-gray-500">
                            <input type="checkbox" disabled class="mr-2"> CORs/COEs
                        </label>
                        <label class="flex items-center text-xs font-bold text-gray-500">
                            <input type="checkbox" disabled class="mr-2"> Certificate of Indigency
                        </label>
                    </div>
                    <div class="space-y-6 pt-4">
                        <div class="text-center">
                            <p class="text-[9px] font-black text-gray-400 uppercase">Evaluated / Processed By:</p>
                        </div>
                        <div class="text-center">
                            <div class="border-b border-gray-400 h-6 w-full mb-1"></div>
                            <p class="text-[9px] font-black text-gray-400 uppercase">UniFAST Regional Coordinator</p>
                        </div>
                    </div>

                </div>
            </div>
               <div class="bg-blue-900 p-6 rounded-xl text-white text-[10px] uppercase tracking-widest leading-relaxed mb-10">
                <p class="font-black border-b border-blue-700 pb-2 mb-2">Qualification Requirements ( per Section 1 of the Memorandom Circular No._s. 2022)</p>
                <p>Applicant must be a Filipino citizen with a combined household (parents/guardian) gross Income which shall not exceed Four Hundred Thousand Pesos<b>PhP 400,000.00</b>and maybe classified as one of the following:</p>
                <br>
                <p> 5.1 New TDP-TES Grantee must be enrolled in any first undergraduate degree in SUCs, CHED-Recognized LUCs and Private HEIs that are in the CHED Registry of Programs and Institutions.</p>
            </div>
             <div class="bg-blue-900 p-6 rounded-xl text-white text-[10px] uppercase tracking-widest leading-relaxed mb-10">
                <p class="font-black border-b border-blue-700 pb-2 mb-2">Documentary Requirements ( per Section 3 of the Memorandom Circular No._s. 2022. 6.2.1 a. For new applicants)</p>
                <p>  Participating higher education institutions (HEIs) must submit, to the respective CHED Regional Offices, a certified true copy or electronically-generated copy of the list of enrolled student - applicants with total number of units enrolled (Annex 5), with the attached certified electronically generated Certificate of Registration/Enrollment(CORs/COEs) as proff of enrollment.</p>
                <br>
                <p> 6.2.2 (Income Requirment) New applicants and continuing grantess shall submit a Certificate of Indigency as a proof of income, duly issued by the punong Barangay where the applicant resides.</p>
             </div>
            <button type="submit" class="w-full bg-blue-700 text-white py-5 rounded-2xl font-black uppercase tracking-widest {{ !$canEdit ? 'opacity-50 cursor-not-allowed bg-gray-400' : 'hover:bg-blue-800 active:scale-95 transform' }} transition shadow-2xl" {{ !$canEdit ? 'disabled' : '' }}>
                {{ !$canEdit ? 'Application Locked - Status: ' . ucfirst($currentStatus) : ($editing ? 'Update Application' : 'Submit Application') }}
            </button>
        </form>
    </div>
</div>

<script>
    // Toggle Financial Assistance Inputs
    function toggleAssistance(show) {
        const details = document.getElementById('assistance_details');
        if (show) {
            details.classList.remove('hidden');
        } else {
            details.classList.add('hidden');
            // Clear inputs if they select 'No'
            const inputs = details.querySelectorAll('input');
            inputs.forEach(input => input.value = '');
        }
    }

    // Image Preview for 2x2 Photo
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photo_preview').src = e.target.result;
                document.getElementById('photo_preview').classList.remove('hidden');
                document.getElementById('photo_placeholder').classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Disable form inputs if application cannot be edited
    @if(!$canEdit)
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('input, textarea, select, button[type="submit"]');
            inputs.forEach(input => {
                if (input.type !== 'hidden') {
                    input.disabled = true;
                }
            });
            form.style.opacity = '0.7';
        });
    @endif
</script>
@endsection
