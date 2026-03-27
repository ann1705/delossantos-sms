@extends('layouts.app')

@section('content')
<form action="{{ route('admin.applications.update', $application->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="max-w-5xl mx-auto my-12 px-8 pb-24 print:m-0 print:p-0">

        <div class="flex justify-between items-center mb-6 print:hidden">
            <a href="{{ route('student.dashboard') }}" class="text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-black flex items-center gap-2 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Dashboard
            </a>

            <div class="flex gap-4">
                @if(auth()->user()->role == 'admin')
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-blue-700 transition shadow-lg">
                        Commit Admin Updates
                    </button>
                @endif
                <a href="{{ auth()->user()->role == 'admin' ? route('admin.applications.pdf', $application->id) : route('applications.view_form.pdf') }}" class="bg-gray-900 text-white px-6 py-2 rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-black transition shadow-lg">
                    Download PDF
                </a>
            </div>
        </div>

        <div class="bg-white border-[1px] border-gray-400 p-12 shadow-sm print:border-none print:p-0">

            <div class="flex justify-between items-center border-b-2 border-black pb-6 mb-8 text-center">
                <img src="{{ asset('images/ched.png') }}" class="w-20 h-20 object-contain">
                <div>
                    <h1 class="text-xl font-bold leading-none text-gray-900 uppercase">Commission on Higher Education</h1>
                    <p class="text-sm font-medium text-gray-700 uppercase mt-1">Unified Student Financial Assistance System for Tertiary Education</p>
                    <p class="text-[11px] font-black text-blue-800 uppercase tracking-widest mt-1">Tulong Dunong Program (TDP-TES)</p>
                    <p class="text-sm font-black text-gray-900 mt-2 uppercase">Form Control No. #{{ str_pad($application->id, 8, '0', STR_PAD_LEFT) }}</p>
                </div>
                <img src="{{ asset('images/unifast.png') }}" class="w-20 h-20 object-contain">
            </div>

            <div class="flex justify-between mb-8">
                <div class="space-y-1">
                    <p class="text-[10px] font-bold uppercase italic text-gray-500">Current Status: <span class="text-blue-700">{{ strtoupper($applicantData->application_status ?? 'PENDING') }}</span></p>
                    <p class="text-[10px] font-bold uppercase italic text-gray-500">Date Filed: {{ $applicantData->date_accomplished }}</p>
                </div>
                <div class="w-36 h-36 border-2 border-black bg-gray-50 flex items-center justify-center overflow-hidden">
                    @if($applicantData->applicant_photo)
                        <img src="{{ asset($applicantData->applicant_photo) }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-[9px] text-gray-400 font-black uppercase">NO PHOTO</span>
                    @endif
                </div>
            </div>

            <div class="mb-8">
                <h2 class="bg-gray-200 border-x border-t border-black px-4 py-1 text-xs font-black uppercase tracking-widest">I. Personal Information</h2>
                <table class="w-full border-collapse border border-black text-[10px]">
                    <tr>
                        <td class="border border-black p-2 w-1/3"><span class="form-label">Surname</span><br><b class="form-val">{{ $applicantData->last_name }}</b></td>
                        <td class="border border-black p-2 w-1/3"><span class="form-label">First Name</span><br><b class="form-val">{{ $applicantData->first_name }}</b></td>
                        <td class="border border-black p-2 w-1/3"><span class="form-label">Middle Name</span><br><b class="form-val">{{ $applicantData->middle_name ?? 'N/A' }}</b></td>
                    </tr>
                    <tr>
                        <td class="border border-black p-2"><span class="form-label">Date of Birth</span><br><b class="form-val">{{ $applicantData->dob }}</b></td>
                        <td class="border border-black p-2"><span class="form-label">Sex</span><br><b class="form-val uppercase">{{ $applicantData->sex }}</b></td>
                        <td class="border border-black p-2"><span class="form-label">Citizenship</span><br><b class="form-val uppercase">{{ $applicantData->citizenship }}</b></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="border border-black p-2"><span class="form-label">Place of Birth</span><br><b class="form-val uppercase">{{ $applicantData->place_of_birth }}</b></td>
                        <td class="border border-black p-2"><span class="form-label">Birthplace Zip Code</span><br><b class="form-val">{{ $applicantData->pob_zip_code }}</b></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="border border-black p-2"><span class="form-label">Permanent Address</span><br><b class="form-val uppercase">{{ $applicantData->permanent_address }}</b></td>
                        <td class="border border-black p-2"><span class="form-label">Address Zip Code</span><br><b class="form-val">{{ $applicantData->zip_code }}</b></td>
                    </tr>
                    <tr>
                        <td class="border border-black p-2"><span class="form-label">Mobile Number</span><br><b class="form-val">{{ $applicantData->mobile_number }}</b></td>
                        <td class="border border-black p-2"><span class="form-label">Email Address</span><br><b class="form-val italic">{{ $applicantData->email_address ?? 'N/A' }}</b></td>
                        <td class="border border-black p-2"><span class="form-label">Tribal Membership</span><br><b class="form-val uppercase">{{ $applicantData->tribal_membership ?? 'NONE' }}</b></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="border border-black p-2"><span class="form-label">Disability Type (If applicable)</span><br><b class="form-val uppercase">{{ $applicantData->disability_type ?? 'NONE' }}</b></td>
                    </tr>
                </table>
            </div>

            <div class="mb-8">
                <h2 class="bg-gray-200 border-x border-t border-black px-4 py-1 text-xs font-black uppercase tracking-widest">II. Academic Profile</h2>
                <table class="w-full border-collapse border border-black text-[10px]">
                    <tr>
                        <td colspan="2" class="border border-black p-2"><span class="form-label">Higher Education Institution (HEI)</span><br><b class="form-val uppercase">{{ $applicantData->school_name }}</b></td>
                        <td class="border border-black p-2"><span class="form-label">School ID No.</span><br><b class="form-val">{{ $applicantData->school_id_number ?? 'N/A' }}</b></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="border border-black p-2"><span class="form-label">School Address</span><br><b class="form-val uppercase">{{ $applicantData->school_address }}</b></td>
                        <td class="border border-black p-2"><span class="form-label">School Sector</span><br><b class="form-val uppercase">{{ $applicantData->school_sector }}</b></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="border border-black p-2"><span class="form-label">Course / Program</span><br><b class="form-val text-blue-800 uppercase">{{ $applicantData->course ?? $application->course }}</b></td>
                        <td class="border border-black p-2"><span class="form-label">Year Level</span><br><b class="form-val uppercase">{{ $applicantData->year_level }}</b></td>
                    </tr>
                </table>
            </div>

            <div class="mb-10">
                <h2 class="bg-gray-200 border-x border-t border-black px-4 py-1 text-xs font-black uppercase tracking-widest">III. Family Background</h2>
                <table class="w-full border-collapse border border-black text-[10px]">
                    <tr>
                        <td class="border border-black p-2 w-1/4 bg-gray-50"><span class="form-label">Father Status</span><br><b class="form-val italic">{{ $applicantData->father_status }}</b></td>
                        <td class="border border-black p-2 w-1/2"><span class="form-label">Father Full Name</span><br><b class="form-val">{{ $applicantData->father_name ?? 'N/A' }}</b></td>
                        <td class="border border-black p-2 w-1/4"><span class="form-label">Occupation</span><br><b class="form-val uppercase">{{ $applicantData->father_occupation ?? 'N/A' }}</b></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="border border-black p-2"><span class="form-label">Father Address</span><br><b class="form-val uppercase text-[9px]">{{ $applicantData->father_address ?? 'SAME AS PERMANENT' }}</b></td>
                    </tr>
                    <tr>
                        <td class="border border-black p-2 bg-gray-50"><span class="form-label">Mother Status</span><br><b class="form-val italic">{{ $applicantData->mother_status }}</b></td>
                        <td class="border border-black p-2"><span class="form-label">Mother Full Name</span><br><b class="form-val">{{ $applicantData->mother_name ?? 'N/A' }}</b></td>
                        <td class="border border-black p-2"><span class="form-label">Occupation</span><br><b class="form-val uppercase">{{ $applicantData->mother_occupation ?? 'N/A' }}</b></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="border border-black p-2"><span class="form-label">Mother Address</span><br><b class="form-val uppercase text-[9px]">{{ $applicantData->mother_address ?? 'SAME AS PERMANENT' }}</b></td>
                    </tr>
                    <tr>
                        <td class="border border-black p-2"><span class="form-label">Annual Gross Income</span><br><b class="form-val text-blue-800">₱{{ number_format($applicantData->total_income, 2) }}</b></td>
                        <td class="border border-black p-2"><span class="form-label">No. of Siblings</span><br><b class="form-val">{{ $applicantData->siblings_count }}</b></td>
                        <td class="border border-black p-2"><span class="form-label">Receiving other assistance?</span><br><b class="form-val uppercase">{{ $applicantData->has_assistance ? 'YES' : 'NO' }}</b></td>
                    </tr>
                </table>
            </div>

            <div class="border-2 border-black p-6 mb-10 bg-gray-50">
                <h2 class="text-center font-black text-gray-500 mb-6 tracking-widest uppercase text-xs italic">(Do Not Fill-Out This Portion For Chedro Use Only)</h2>
                <div class="grid grid-cols-2 gap-10">
                    <div class="space-y-5">
                        <p class="text-[10px] font-black text-gray-800 underline uppercase italic">Official Evaluation Checklist:</p>

                        <label class="flex items-center gap-3 cursor-pointer">
                            @if(auth()->user()->role == 'admin')
                                <input type="checkbox" name="admin_check_cor" value="1" {{ $applicantData->admin_check_cor ? 'checked' : '' }} class="w-4 h-4 accent-black border-black">
                            @else
                                <div class="w-4 h-4 border border-black flex items-center justify-center {{ $applicantData->admin_check_cor ? 'bg-black' : '' }}">
                                    @if($applicantData->admin_check_cor) <span class="text-white text-[10px]">✓</span> @endif
                                </div>
                            @endif
                            <span class="text-[10px] font-bold uppercase">COR/COE Verified</span>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer">
                            @if(auth()->user()->role == 'admin')
                                <input type="checkbox" name="admin_check_indigency" value="1" {{ $applicantData->admin_check_indigency ? 'checked' : '' }} class="w-4 h-4 accent-black border-black">
                            @else
                                <div class="w-4 h-4 border border-black flex items-center justify-center {{ $applicantData->admin_check_indigency ? 'bg-black' : '' }}">
                                    @if($applicantData->admin_check_indigency) <span class="text-white text-[10px]">✓</span> @endif
                                </div>
                            @endif
                            <span class="text-[10px] font-bold uppercase">Indigency Certificate Verified</span>
                        </label>

                        <div class="mt-4">
                            <p class="text-[10px] font-black text-blue-600 underline uppercase italic mb-1">Set Application Status:</p>
                            @if(auth()->user()->role == 'admin')
                                <select name="application_status" class="w-full p-2 border border-black font-black text-xs uppercase bg-white focus:ring-0">
                                    <option value="pending" {{ $applicantData->application_status == 'pending' ? 'selected' : '' }}>PENDING</option>
                                    <option value="approved" {{ $applicantData->application_status == 'approved' ? 'selected' : '' }}>APPROVED</option>
                                    <option value="rejected" {{ $applicantData->application_status == 'rejected' ? 'selected' : '' }}>REJECTED</option>
                                </select>
                            @else
                                <b class="text-xs uppercase">{{ $applicantData->application_status }}</b>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 text-center">
                        <div>
                            @if(auth()->user()->role == 'admin')
                                <input type="text" name="evaluated_by" value="{{ $applicantData->evaluated_by }}" placeholder="Name of Evaluator" class="text-xs font-black border-b border-black text-center bg-transparent w-full focus:outline-none uppercase">
                            @else
                                <p class="text-xs font-black border-b border-black inline-block px-10">{{ $applicantData->evaluated_by ?? '..............................' }}</p>
                            @endif
                            <p class="text-[9px] font-bold uppercase text-gray-400 mt-1">Evaluated / Processed By</p>
                        </div>

                    </div>
                </div>

                <div class="mt-6">
                    <p class="text-[10px] font-black text-gray-800 underline uppercase italic mb-1">Official Remarks:</p>
                    @if(auth()->user()->role == 'admin')
                        <textarea name="admin_remarks" rows="2" class="w-full p-2 border border-black text-[10px] font-black uppercase focus:outline-none" placeholder="ENTER EVALUATION NOTES...">{{ $applicantData->admin_remarks }}</textarea>
                    @else
                        <p class="text-[10px] uppercase italic text-gray-600">{{ $applicantData->admin_remarks ?? 'NO REMARKS PROVIDED' }}</p>
                    @endif
                </div>
            </div>

            <div class="print:hidden mb-12 border-t-2 border-dashed border-gray-200 pt-8">
                <p class="text-[10px] font-black text-gray-400 uppercase mb-4 italic">Digitally Uploaded Attachments:</p>
                <div class="flex gap-4">
                    @php
                        $docs = [
                            ['path' => $applicantData->enrollment_proof, 'label' => 'VIEW ATTACHED COR'],
                            ['path' => $applicantData->indigency_certificate, 'label' => 'VIEW ATTACHED INDIGENCY']
                        ];
                    @endphp

                    @foreach($docs as $doc)
                        @if($doc['path'])
                            <a href="{{ asset($doc['path']) }}" target="_blank" class="flex-1 text-center text-[10px] font-black text-blue-600 border-2 border-blue-600 px-4 py-3 hover:bg-blue-600 hover:text-white transition-all uppercase rounded-xl">
                                {{ $doc['label'] }}
                            </a>
                        @else
                            <button type="button" onclick="alert('Document not found in record.')" class="flex-1 text-center text-[10px] font-black text-red-300 border-2 border-red-100 px-4 py-3 cursor-not-allowed uppercase rounded-xl">
                                {{ $doc['label'] }} (MISSING)
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col items-end pt-12">
                <div class="text-center">
                    @if($applicantData->signature_path)
                        <img src="{{ asset($applicantData->signature_path) }}" class="h-16 mx-auto mb-1 grayscale" alt="Signature">
                    @else
                        <div class="h-16 flex items-center justify-center text-[8px] text-gray-300 uppercase italic">Signature missing</div>
                    @endif
                    <div class="w-64 border-t-2 border-black pt-2">
                        <p class="text-xs font-black uppercase">{{ $applicantData->first_name }} {{ $applicantData->middle_name ?? '' }} {{ $applicantData->last_name }}</p>
                        <p class="text-[9px] font-bold text-gray-500 uppercase">Signature of Applicant Over Printed Name</p>
                    </div>
                    <p class="text-[8px] text-gray-400 mt-2 uppercase tracking-tighter">Accomplished: {{ $applicantData->date_accomplished }}</p>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    .form-label { @apply text-[8px] font-black uppercase text-gray-400 leading-none; }
    .form-val { @apply text-gray-900 font-bold uppercase tracking-tight; }

    @media print {
        @page { size: portrait; margin: 1cm; }
        body { background: white !important; }
        .print\:hidden { display: none !important; }
        .bg-gray-50 { background-color: #f9fafb !important; -webkit-print-color-adjust: exact; }
        .bg-gray-200 { background-color: #e5e7eb !important; -webkit-print-color-adjust: exact; }
        input[type="text"], select, textarea { border: none !important; border-bottom: 1px solid black !important; }
    }
</style>
@endsection
