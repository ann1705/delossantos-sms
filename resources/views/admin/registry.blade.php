@extends('layouts.app')

@section('content')
<div class="p-8 min-h-screen mt-12 bg-gray-50/50">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Application Registry</h1>
                <p class="text-sm font-bold text-blue-600 uppercase tracking-widest mt-1 italic">TDP-TES Scholarship Master List</p>
            </div>
            <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100 text-center">
                <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Database Records</span>
                <span class="text-2xl font-black text-gray-900">{{ count($applications) }}</span>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100">
            <table class="w-full text-left border-collapse">
                <thead class="bg-blue-50/50 border-b border-gray-100">
                    <tr>
                        <th class="p-6 text-xs font-black text-blue-900 uppercase tracking-widest">Applicant</th>
                        <th class="p-6 text-xs font-black text-blue-900 uppercase tracking-widest">School & Course</th>
                        <th class="p-6 text-xs font-black text-blue-900 uppercase tracking-widest text-center">Status</th>
                        <th class="p-6 text-xs font-black text-blue-900 uppercase tracking-widest text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($applications as $app)
                    <tr class="hover:bg-blue-50/20 transition-all group">
                        <td class="p-6">
                            <div class="flex items-center gap-4">
                                @if($app->user && $app->user->profile_photo)
                                    <img src="{{ asset($app->user->profile_photo) }}" alt="Profile" class="w-12 h-12 rounded-2xl object-cover shadow-lg border-2 border-white">
                                @else
                                    <div class="w-12 h-12 rounded-2xl bg-gray-900 flex items-center justify-center text-white font-black shadow-lg uppercase">
                                        {{ substr($app->user->name ?? '?', 0, 1) }}
                                    </div>
                                @endif
                                <div class="flex flex-col">
                                    <span class="font-black text-gray-800 uppercase tracking-tight">{{ $app->user->name ?? 'N/A' }}</span>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider italic">ID: {{ str_pad($app->id, 6, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-6">
                            <span class="font-bold text-gray-600 uppercase text-xs block">{{ $app->applicantData->school_name ?? 'N/A' }}</span>
                            <div class="text-[10px] text-blue-500 font-black uppercase tracking-widest">{{ $app->applicantData->course ?? $app->course ?? 'N/A' }} - Year {{ $app->applicantData->year_level ?? $app->year_level ?? '' }}</div>
                        </td>
                        <td class="p-6 text-center">
                            <span class="px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm
                                {{ ($app->applicantData->application_status ?? 'pending') == 'approved' ? 'bg-green-100 text-green-700' : (($app->applicantData->application_status ?? 'pending') == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                {{ $app->applicantData->application_status ?? 'Pending' }}
                            </span>
                        </td>
                        <td class="p-6">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" onclick="openViewModal({{ json_encode($app->load('applicantData')) }})"
                                    class="p-3 bg-blue-600 text-white rounded-xl hover:bg-gray-900 transition shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="3" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-width="3" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                <form action="{{ route('admin.applications.destroy', $app->id) }}" method="POST" onsubmit="return confirm('Delete record permanently?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-3 bg-white border border-red-100 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="p-20 text-center text-gray-400 font-bold uppercase italic">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="viewFormModal" class="fixed inset-0 z-[150] hidden flex items-center justify-center bg-blue-950/60 backdrop-blur-lg p-4 transition-all">
    <div class="bg-white rounded-[2rem] w-full max-w-6xl h-[95vh] overflow-hidden shadow-2xl flex flex-col border-4 border-white">

        <div class="p-4 bg-gray-50 border-b flex justify-between items-center print:hidden">
            <p class="text-[10px] font-black uppercase tracking-widest text-blue-600 ml-4 italic">Official CHED Evaluation Mode</p>
            <div class="flex gap-2">
                <button type="button" onclick="window.print()" class="bg-gray-800 text-white px-6 py-2 rounded font-bold text-xs uppercase hover:bg-black">Print Document</button>
                <button type="button" onclick="document.getElementById('viewFormModal').classList.add('hidden')" class="p-2 bg-red-50 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-12 bg-white print:p-0">
            <form id="adminDecisionForm" method="POST" action="">
                @csrf @method('PATCH')

                <div class="flex justify-between items-center border-b-2 border-black pb-6 mb-8 text-center">
                    <img src="{{ asset('images/ched.png') }}" class="w-20 h-20 object-contain">
                    <div>
                        <h1 class="text-xl font-bold leading-none text-gray-900 uppercase">Commission on Higher Education</h1>
                        <p class="text-xs font-medium text-gray-700 uppercase mt-1">Unified Student Financial Assistance System for Tertiary Education</p>
                        <p class="text-[11px] font-black text-blue-800 uppercase tracking-widest mt-1">Tulong Dunong Program (TDP-TES)</p>
                        <p class="text-sm font-black text-gray-900 mt-2 uppercase">Application ID: #<span id="modalControlNo"></span></p>
                    </div>
                    <img src="{{ asset('images/unifast.png') }}" class="w-20 h-20 object-contain">
                </div>

                <div class="flex justify-between mb-8">
                    <div class="space-y-1">
                        <p class="text-xs font-bold uppercase italic text-gray-500">Current Status: <span id="modalHeaderStatus" class="text-blue-800"></span></p>
                        <p class="text-xs font-bold uppercase italic text-gray-500">Date Accomplished: <span id="modalDateFiled"></span></p>
                    </div>
                    <div class="w-36 h-36 border-2 border-black bg-gray-50 flex items-center justify-center overflow-hidden">
                        <img id="modalPhoto" src="" class="w-full h-full object-cover">
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="bg-gray-200 border-x border-t border-black px-4 py-1 text-xs font-black uppercase tracking-widest">1. Personal Information</h2>
                    <table class="w-full border-collapse border border-black text-[10px]">
                        <tr>
                            <td class="border border-black p-2 w-1/3"><span class="form-label">Surname</span><br><b id="modalLastName" class="form-val"></b></td>
                            <td class="border border-black p-2 w-1/3"><span class="form-label">First Name</span><br><b id="modalFirstName" class="form-val"></b></td>
                            <td class="border border-black p-2 w-1/3"><span class="form-label">Middle Name</span><br><b id="modalMiddleName" class="form-val"></b></td>
                        </tr>
                        <tr>
                            <td class="border border-black p-2"><span class="form-label">Date of Birth</span><br><b id="modalDob" class="form-val"></b></td>
                            <td class="border border-black p-2"><span class="form-label">Sex</span><br><b id="modalSex" class="form-val"></b></td>
                            <td class="border border-black p-2"><span class="form-label">Citizenship</span><br><b id="modalCitizenship" class="form-val"></b></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="border border-black p-2"><span class="form-label">Place of Birth</span><br><b id="modalPob" class="form-val uppercase"></b></td>
                            <td class="border border-black p-2"><span class="form-label">Birthplace Zip Code</span><br><b id="modalPobZip" class="form-val"></b></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="border border-black p-2"><span class="form-label">Permanent Address</span><br><b id="modalAddress" class="form-val uppercase"></b></td>
                            <td class="border border-black p-2"><span class="form-label">Address Zip Code</span><br><b id="modalZip" class="form-val"></b></td>
                        </tr>
                        <tr>
                            <td class="border border-black p-2"><span class="form-label">Mobile Number</span><br><b id="modalMobile" class="form-val"></b></td>
                            <td class="border border-black p-2"><span class="form-label">Email Address</span><br><b id="modalEmail" class="form-val"></b></td>
                            <td class="border border-black p-2"><span class="form-label">Tribal Membership</span><br><b id="modalTribe" class="form-val"></b></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="border border-black p-2"><span class="form-label">Disability Type (If applicable)</span><br><b id="modalDisability" class="form-val"></b></td>
                        </tr>
                    </table>
                </div>

                <div class="mb-8">
                    <h2 class="bg-gray-200 border-x border-t border-black px-4 py-1 text-xs font-black uppercase tracking-widest">2. Academic Information</h2>
                    <table class="w-full border-collapse border border-black text-[10px]">
                        <tr>
                            <td colspan="2" class="border border-black p-2"><span class="form-label">Institution (HEI)</span><br><b id="modalSchool" class="form-val uppercase"></b></td>
                            <td class="border border-black p-2"><span class="form-label">School ID Number</span><br><b id="modalSchoolIdNo" class="form-val"></b></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="border border-black p-2"><span class="form-label">Institution Address</span><br><b id="modalSchoolAddress" class="form-val uppercase"></b></td>
                            <td class="border border-black p-2"><span class="form-label">School Sector</span><br><b id="modalSector" class="form-val"></b></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="border border-black p-2"><span class="form-label">Course / Program</span><br><b id="modalCourse" class="form-val text-blue-800"></b></td>
                            <td class="border border-black p-2"><span class="form-label">Year Level</span><br><b id="modalYear" class="form-val"></b></td>
                        </tr>
                    </table>
                </div>

                <div class="mb-8">
                    <h2 class="bg-gray-200 border-x border-t border-black px-4 py-1 text-xs font-black uppercase tracking-widest">3. Family Background</h2>
                    <table class="w-full border-collapse border border-black text-[10px]">
                        <tr>
                            <td class="border border-black p-2"><span class="form-label">Father Status</span><br><b id="modalFatherStatus" class="form-val"></b></td>
                            <td class="border border-black p-2"><span class="form-label">Father Full Name</span><br><b id="modalFatherName" class="form-val"></b></td>
                            <td class="border border-black p-2"><span class="form-label">Father Occupation</span><br><b id="modalFatherJob" class="form-val"></b></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="border border-black p-2"><span class="form-label">Father Address</span><br><b id="modalFatherAddress" class="form-val"></b></td>
                        </tr>
                        <tr>
                            <td class="border border-black p-2"><span class="form-label">Mother Status</span><br><b id="modalMotherStatus" class="form-val"></b></td>
                            <td class="border border-black p-2"><span class="form-label">Mother Full Name</span><br><b id="modalMotherName" class="form-val"></b></td>
                            <td class="border border-black p-2"><span class="form-label">Mother Occupation</span><br><b id="modalMotherJob" class="form-val"></b></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="border border-black p-2"><span class="form-label">Mother Address</span><br><b id="modalMotherAddress" class="form-val"></b></td>
                        </tr>
                        <tr>
                            <td class="border border-black p-2"><span class="form-label">Household Monthly Income</span><br><b id="modalIncome" class="form-val text-blue-700"></b></td>
                            <td class="border border-black p-2"><span class="form-label">No. of Siblings</span><br><b id="modalSiblings" class="form-val"></b></td>
                            <td class="border border-black p-2"><span class="form-label">Has Other Assistance?</span><br><b id="modalAssistance" class="form-val"></b></td>
                        </tr>
                    </table>
                </div>

                <div class="mb-10 flex justify-end">
                    <div class="w-64 text-center">
                        <div class="h-20 flex items-end justify-center border-b border-black mb-1">
                            <img id="modalSignature" src="" class="max-h-full object-contain" alt="Signature">
                        </div>
                        <p class="text-[9px] font-black uppercase text-gray-500">Signature of Applicant</p>
                    </div>
                </div>

                <div class="mb-10 print:hidden">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-[2px] bg-blue-600 flex-1"></div>
                        <h2 class="text-[10px] font-black uppercase tracking-widest text-blue-600 italic">Requirement Verification Portal</h2>
                        <div class="h-[2px] bg-blue-600 flex-1"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <a id="btnEnrollment" href="javascript:void(0)" class="doc-card group">
                            <div class="flex items-center gap-4 p-4 bg-white border-2 border-gray-100 rounded-2xl shadow-sm group-hover:border-blue-600 transition-all">
                                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div class="flex-1">
                                    <span class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">Enrollment Proof</span>
                                    <span class="block text-xs font-black text-gray-800 uppercase group-hover:text-blue-600">View COR / Certificate</span>
                                </div>
                            </div>
                        </a>

                        <a id="btnIndigency" href="javascript:void(0)" class="doc-card group">
                            <div class="flex items-center gap-4 p-4 bg-white border-2 border-gray-100 rounded-2xl shadow-sm group-hover:border-blue-600 transition-all">
                                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <div class="flex-1">
                                    <span class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">Financial Proof</span>
                                    <span class="block text-xs font-black text-gray-800 uppercase group-hover:text-blue-600">View Indigency Cert</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="border-2 border-black p-6 mb-10 bg-gray-50 shadow-inner">
                    <h2 class="text-center font-black text-gray-500 mb-6 tracking-widest uppercase text-[10px] italic">(Do Not Fill-Out This Portion For Chedro Use Only)</h2>
                    <div class="grid grid-cols-2 gap-10">
                        <div class="space-y-4">
                            <p class="text-[10px] font-black text-gray-800 underline uppercase italic">Decision & Evaluation:</p>

                            <div class="bg-white border border-black p-3 space-y-2 mb-4">
                                <p class="text-[9px] font-black text-gray-400 uppercase mb-1">Requirement Checklist:</p>
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="hidden" name="admin_check_cor" value="0">
                                    <input type="checkbox" name="admin_check_cor" id="modalCorCheck" value="1" class="w-3 h-3 border border-black rounded-none checked:bg-blue-600 focus:ring-0">
                                    <span class="text-[9px] font-black text-gray-600 uppercase group-hover:text-blue-600">CORs / COEs Verified</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="hidden" name="admin_check_indigency" value="0">
                                    <input type="checkbox" name="admin_check_indigency" id="modalIndigencyCheck" value="1" class="w-3 h-3 border border-black rounded-none checked:bg-blue-600 focus:ring-0">
                                    <span class="text-[9px] font-black text-gray-600 uppercase group-hover:text-blue-600">Indigency Certificate Verified</span>
                                </label>
                            </div>

                            <div class="">
                                <p class="text-[9px] font-black text-blue-600 uppercase mb-1">Update Status:</p>
                                <select name="application_status" id="modalStatusSelect" class="w-full p-2 border border-black font-black text-xs uppercase bg-white focus:ring-0">
                                    <option value="pending">PENDING</option>
                                    <option value="approved">APPROVED</option>
                                    <option value="rejected">REJECTED</option>
                                </select>
                            </div>
                            <div class="mt-4">
                                <p class="text-[9px] font-black text-gray-800 uppercase mb-1">Official Remarks:</p>
                                <textarea name="admin_remarks" id="modalAdminRemarks" rows="3" class="w-full p-2 border border-black text-[10px] font-black uppercase focus:outline-none" placeholder="REASON FOR ACTION..."></textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 text-center">
                            <div class="mt-auto">
                                <input type="text" name="evaluated_by" id="modalEvaluatedBy" class="text-xs font-black border-b border-black text-center bg-transparent w-full focus:outline-none uppercase" placeholder="NAME OF EVALUATOR">
                                <p class="text-[9px] font-bold uppercase text-gray-400 mt-1">Evaluated / Processed By</p>
                            </div>
                            <div class="pt-10 print:hidden">
                                <button type="submit" class="w-full bg-blue-600 text-white py-4 px-6 rounded font-black text-[10px] uppercase tracking-widest hover:bg-black transition-all shadow-xl">
                                    Commmit to Official Record
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
        </div>
    </div>
</div>

<style>
    .form-label { @apply text-[8px] font-black uppercase text-gray-400 leading-none; }
    .form-val { @apply text-gray-900 font-bold uppercase tracking-tight; }
    .doc-card { @apply no-underline transition-all active:scale-95 block; }
    .doc-card.disabled { @apply opacity-40 grayscale; }
    @media print {
        @page { size: portrait; margin: 0.5cm; }
        .print\:hidden { display: none !important; }
        .bg-gray-200 { background-color: #f3f4f6 !important; -webkit-print-color-adjust: exact; }
    }
</style>

<script>
    function openViewModal(app) {
        const data = app.applicant_data;
        if (!data) return alert("System Error: applicant_data missing.");

        // Header Data
        document.getElementById('modalControlNo').innerText = app.id.toString().padStart(6, '0');
        document.getElementById('modalHeaderStatus').innerText = (data.application_status || 'PENDING').toUpperCase();
        document.getElementById('modalDateFiled').innerText = data.date_accomplished || 'N/A';
        document.getElementById('modalPhoto').src = data.applicant_photo ? `/${data.applicant_photo}` : '/images/default.png';

        // 1. Personal
        document.getElementById('modalLastName').innerText = data.last_name;
        document.getElementById('modalFirstName').innerText = data.first_name;
        document.getElementById('modalMiddleName').innerText = data.middle_name || 'N/A';
        document.getElementById('modalDob').innerText = data.dob;
        document.getElementById('modalSex').innerText = data.sex;
        document.getElementById('modalCitizenship').innerText = data.citizenship;
        document.getElementById('modalPob').innerText = data.place_of_birth;
        document.getElementById('modalPobZip').innerText = data.pob_zip_code;
        document.getElementById('modalAddress').innerText = data.permanent_address;
        document.getElementById('modalZip').innerText = data.zip_code;
        document.getElementById('modalMobile').innerText = data.mobile_number;
        document.getElementById('modalEmail').innerText = data.email_address || 'N/A';
        document.getElementById('modalTribe').innerText = data.tribal_membership || 'NONE';
        document.getElementById('modalDisability').innerText = data.disability_type || 'NONE';

        // 2. Academic
        document.getElementById('modalSchool').innerText = data.school_name;
        document.getElementById('modalSchoolIdNo').innerText = data.school_id_number || 'N/A';
        document.getElementById('modalSchoolAddress').innerText = data.school_address;
        document.getElementById('modalSector').innerText = data.school_sector;
        document.getElementById('modalCourse').innerText = data.course || 'N/A';
        document.getElementById('modalYear').innerText = data.year_level;

        // 3. Family
        document.getElementById('modalFatherStatus').innerText = data.father_status;
        document.getElementById('modalFatherName').innerText = data.father_name || 'N/A';
        document.getElementById('modalFatherJob').innerText = data.father_occupation || 'N/A';
        document.getElementById('modalFatherAddress').innerText = data.father_address || 'SAME AS PERMANENT';

        document.getElementById('modalMotherStatus').innerText = data.mother_status;
        document.getElementById('modalMotherName').innerText = data.mother_name || 'N/A';
        document.getElementById('modalMotherJob').innerText = data.mother_occupation || 'N/A';
        document.getElementById('modalMotherAddress').innerText = data.mother_address || 'SAME AS PERMANENT';

        document.getElementById('modalIncome').innerText = `PHP ${parseFloat(data.total_income).toLocaleString()}`;
        document.getElementById('modalSiblings').innerText = data.siblings_count;
        document.getElementById('modalAssistance').innerText = data.has_assistance ? 'YES' : 'NO';

        // SIGNATURE
        document.getElementById('modalSignature').src = data.signature_path ? `/${data.signature_path}` : '/images/no-sig.png';

        // ATTACHMENT LOGIC
        const handleDoc = (btnId, path, label) => {
            const btn = document.getElementById(btnId);
            if (path && path.trim() !== "") {
                btn.onclick = () => window.open(`/${path}`, '_blank');
                btn.classList.remove('disabled');
            } else {
                btn.onclick = (e) => {
                    e.preventDefault();
                    alert(`Requirement missing: No ${label} found.`);
                };
                btn.classList.add('disabled');
            }
        };

        handleDoc('btnEnrollment', data.enrollment_proof, 'Enrollment Proof');
        handleDoc('btnIndigency', data.indigency_certificate, 'Indigency Certificate');

        // ADMIN DECISION & CHECKBOXES
        // Map the boolean database values (0 or 1) to the checkboxes
        document.getElementById('modalCorCheck').checked = (data.admin_check_cor == 1 || data.admin_check_cor === true);
        document.getElementById('modalIndigencyCheck').checked = (data.admin_check_indigency == 1 || data.admin_check_indigency === true);

        document.getElementById('modalStatusSelect').value = data.application_status || 'pending';
        document.getElementById('modalAdminRemarks').value = data.admin_remarks || '';
        document.getElementById('modalEvaluatedBy').value = data.evaluated_by || '';

        // Update target URL (must match admin route /admin/applications/{id}/status)
        document.getElementById('adminDecisionForm').action = `/admin/applications/${app.id}/status`;
        document.getElementById('viewFormModal').classList.remove('hidden');
    }
</script>
@endsection
