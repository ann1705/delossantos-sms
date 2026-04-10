@extends('layouts.app')

@section('content')
<div class="min-h-screen mt-12 px-6 py-8">
    <div class="max-w-7xl mx-auto glass rounded-3xl p-10 md:p-12">
        <div class="flex justify-between items-end mb-6">
            <div>
                <h1 class="text-4xl font-black text-white tracking-tighter uppercase">Application Registry</h1>
                <p class="text-sm font-bold uppercase tracking-widest mt-1 italic" style="color: var(--color-accent);">TDP-TES Scholarship Master List</p>
            </div>
        </div>

        <!-- Search & Status Filter Section -->
        <div class="glass rounded-3xl p-8 mb-8">
            <div class="flex flex-col gap-3 mb-4">
                <p class="text-[11px] text-muted font-semibold" style="color: var(--color-muted);">Find applicants by name, course, or school instantly</p>
            </div>

            <div class="relative">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-muted pointer-events-none" style="color: var(--color-muted);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <input type="text" id="searchFilter" placeholder="🔍 Type to search applicants..."
                    class="w-full pl-12 pr-12 py-4 rounded-2xl border-2 border-gray-300 bg-white text-gray-800 placeholder-gray-500 outline-none focus:ring-4 focus:ring-opacity-30 transition-all duration-300 font-semibold shadow-sm" style="focus-border-color: var(--color-accent); focus-ring-color: var(--color-accent);">

                <button id="clearSearchBtn" type="button"
                    class="absolute right-3 top-1/2 -translate-y-1/2 hidden p-2 text-gray-400 rounded-lg transition-all duration-200" style="hover-text-color: var(--color-accent);"
                    onclick="clearSearch()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Live Search Feedback -->
            <div id="searchFeedback" class="mt-4 flex items-center gap-2 text-sm font-semibold opacity-0 transition-opacity duration-300" style="color: var(--color-muted);">
                <span class="inline-block w-2 h-2 bg-accent rounded-full animate-pulse" style="background-color: var(--color-accent);"></span>
                <span id="feedbackText">Ready to search...</span>
            </div>
        </div>

        <!-- Status Count Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="glass-card px-6 py-4">
                <span class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Total Records</span>
                <span class="text-2xl font-black text-gray-900" id="totalCount">{{ count($applications) }}</span>
            </div>
            <div class="glass-card px-6 py-4">
                <span class="block text-[10px] font-black uppercase tracking-widest" style="color: var(--color-accent);">Pending</span>
                <span class="text-2xl font-black text-gray-900" id="pendingCount">0</span>
            </div>
            <div class="glass-card px-6 py-4">
                <span class="block text-[10px] font-black text-green-600 uppercase tracking-widest">Approved</span>
                <span class="text-2xl font-black text-gray-900" id="approvedCount">0</span>
            </div>
            <div class="glass-card px-6 py-4">
                <span class="block text-[10px] font-black text-red-600 uppercase tracking-widest">Rejected</span>
                <span class="text-2xl font-black text-gray-900" id="rejectedCount">0</span>
            </div>
        </div>

        <div class="glass-card rounded-3xl shadow-2xl overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="border-b" style="border-bottom-color: #FFD600;">
                    <tr>
                        <th class="p-6 text-xs font-black text-gray-900 uppercase tracking-widest">Applicant</th>
                        <th class="p-6 text-xs font-black text-gray-900 uppercase tracking-widest">School & Course</th>
                        <th class="p-6 text-xs font-black text-gray-900 uppercase tracking-widest text-center">Status</th>
                        <th class="p-6 text-xs font-black text-gray-900 uppercase tracking-widest text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="applicationsTableBody">
                    @forelse($applications as $app)
                    <tr class="hover:bg-gray-50 transition-all group applicant-row"
                        data-status="{{ $app->application_status ?? 'pending' }}"
                        data-name="{{ $app->user->name ?? '' }}"
                        data-course="{{ $app->course ?? '' }}"
                        data-school="{{ $app->school_name ?? '' }}">
                        <td class="p-6">
                            <div class="flex items-center gap-4">
                                @if($app->user && $app->user->profile_photo)
                                    <img src="{{ asset($app->user->profile_photo) }}" alt="Profile" class="w-12 h-12 rounded-2xl object-cover shadow-lg border-2 border-white">
                                @else
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white font-black shadow-lg uppercase" style="background-color: var(--color-accent); color: #232B43;">
                                        {{ substr($app->user->name ?? '?', 0, 1) }}
                                    </div>
                                @endif
                                <div class="flex flex-col">
                                    <span class="font-black text-gray-900 uppercase tracking-tight">{{ $app->user->name ?? 'N/A' }}</span>
                                    <span class="text-[10px] text-gray-500 font-bold uppercase tracking-wider italic">ID: {{ str_pad($app->id, 6, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-6">
                            <span class="font-bold text-gray-900 uppercase text-xs block">{{ $app->school_name ?? 'N/A' }}</span>
                            <div class="text-[10px] font-black uppercase tracking-widest" style="color: var(--color-accent);">{{ $app->course ?? 'N/A' }} - Year {{ $app->year_level ?? '' }}</div>
                        </td>
                        <td class="p-6 text-center">
                            <span class="px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm
                                {{ ($app->application_status ?? 'pending') == 'approved' ? 'bg-green-100 text-green-700' : (($app->application_status ?? 'pending') == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}" style="background-color: {{ ($app->application_status ?? 'pending') == 'approved' ? '#dcfce7' : (($app->application_status ?? 'pending') == 'rejected' ? '#fee2e2' : 'rgba(255, 214, 0, 0.1)') }}; color: {{ ($app->application_status ?? 'pending') == 'approved' ? '#15803d' : (($app->application_status ?? 'pending') == 'rejected' ? '#991b1b' : 'var(--color-accent)') }};">
                                {{ $app->application_status ?? 'Pending' }}
                            </span>
                        </td>
                        <td class="p-6">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" data-id="{{ $app->id }}" onclick="openViewModal(event)"
                                    class="p-3 btn btn-accent rounded-xl shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="3" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-width="3" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                <form action="{{ route('admin.applications.destroy', $app->id) }}" method="POST" onsubmit="return confirm('Delete record permanently?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-3 bg-white border-2 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition" style="border-color: var(--color-accent);">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="p-20 text-center text-gray-500 font-bold uppercase italic">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="viewFormModal" class="fixed inset-0 z-[150] hidden flex items-center justify-center backdrop-blur-lg p-4 transition-all" style="background-color: rgba(16, 22, 36, 0.8);">
    <div class="bg-white rounded-3xl w-full max-w-6xl h-[95vh] overflow-hidden shadow-2xl flex flex-col border-2" style="border-color: var(--color-accent);">

        <div class="p-4 bg-gray-50 border-b flex justify-between items-center print:hidden" style="border-bottom-color: #FFD600;">
            <p class="text-[10px] font-black uppercase tracking-widest ml-4 italic" style="color: var(--color-accent);">Official CHED Evaluation Mode</p>
            <div class="flex gap-2">
                <a id="downloadPdfBtn" href="javascript:void(0)" class="btn btn-accent text-xs uppercase font-bold px-6 py-2">Download PDF</a>
                <button type="button" onclick="document.getElementById('viewFormModal').classList.add('hidden')" class="p-2 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition-all">
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
                    <h2 class="bg-gray-400 border-x border-t border-black px-4 py-1 text-xs font-black uppercase tracking-widest">1. Personal Information</h2>
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
                    <h2 class="bg-gray-400 border-x border-t border-black px-4 py-1 text-xs font-black uppercase tracking-widest">2. Academic Information</h2>
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
                    <h2 class="bg-gray-400 border-x border-t border-black px-4 py-1 text-xs font-black uppercase tracking-widest">3. Family Background</h2>
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
                                <p class="text-[9px] font-black text-black uppercase mb-1">Update Status:</p>
                                <select name="application_status" id="modalStatusSelect" class="w-full p-2 border border-black font-black text-xs uppercase bg-white text-black focus:ring-0 focus:outline-none">
                                    <option value="pending">PENDING</option>
                                    <option value="approved">APPROVED</option>
                                    <option value="rejected">REJECTED</option>
                                </select>
                            </div>
                            <div class="mt-4">
                                <p class="text-[9px] font-black text-black uppercase mb-1">Official Remarks:</p>
                                <textarea name="admin_remarks" id="modalAdminRemarks" rows="3" class="w-full p-2 border border-black text-[10px] font-black uppercase focus:outline-none bg-white text-black" placeholder="REASON FOR ACTION..."></textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 text-center">
                            <div class="mt-auto">
                                <input type="text" name="evaluated_by" id="modalEvaluatedBy" class="text-xs font-black border-b border-black text-center bg-transparent w-full focus:outline-none uppercase text-black" placeholder="NAME OF EVALUATOR">
                                <p class="text-[9px] font-bold uppercase text-black mt-1">Evaluated / Processed By</p>
                            </div>
                            <div class="pt-10 print:hidden">
                                <button type="submit" onclick="return validateAdminForm()" class="w-full bg-blue-600 text-white py-4 px-6 rounded font-black text-[10px] uppercase tracking-widest hover:bg-black transition-all shadow-xl">
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
    .form-label {
        font-size: 9px !important;
        font-weight: 900 !important;
        text-transform: uppercase !important;
        color: #000000 !important;
        line-height: 1 !important;
        display: block !important;
        letter-spacing: 0.05em !important;
    }
    .form-val {
        color: #111827 !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: -0.025em !important;
    }
    .doc-card { @apply no-underline transition-all active:scale-95 block; }
    .doc-card.disabled { @apply opacity-40 grayscale; }
    @media print {
        @page { size: portrait; margin: 0.5cm; }
        .print\:hidden { display: none !important; }
        .bg-gray-200 { background-color: #f3f4f6 !important; -webkit-print-color-adjust: exact; }
    }
</style>

<script>
    function openViewModal(event) {
        event.preventDefault();
        const button = event.currentTarget;
        const id = button.dataset.id;

        console.log('=== OPENING MODAL ===');
        console.log('Button clicked:', button);
        console.log('App ID:', id);
        console.log('Current URL:', window.location.href);

        const url = `/admin/applications/${id}/data`;
        console.log('Fetching from:', url);

        // Fetch data via AJAX
        fetch(url)
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response OK:', response.ok);

                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`HTTP ${response.status}: ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('✓ Data received:', data);
                populateModal(data);
                const modal = document.getElementById('viewFormModal');
                console.log('Modal element:', modal);
                if (modal) {
                    modal.classList.remove('hidden');
                    console.log('✓ Modal shown');
                } else {
                    console.error('✗ Modal element not found!');
                }
            })
            .catch(error => {
                console.error('✗ Fetch error:', error);
                alert('Error: ' + error.message);
            });
    }

    function populateModal(data) {
        console.log('=== POPULATING MODAL ===');
        const app = data.app;
        const applicantData = data.data;
        const review = data.review;

        console.log('App:', app);
        console.log('Applicant Data:', applicantData);
        console.log('Review:', review);

        const setElement = (id, value) => {
            const el = document.getElementById(id);
            if (el) {
                el.innerText = value;
                console.log(`✓ Set ${id} = ${value}`);
            } else {
                console.warn(`✗ Element not found: ${id}`);
            }
        };

        setElement('modalControlNo', app.id.toString().padStart(6, '0'));
        setElement('modalHeaderStatus', (applicantData.application_status || 'PENDING').toUpperCase());
        setElement('modalDateFiled', applicantData.date_accomplished || 'N/A');

        const photoEl = document.getElementById('modalPhoto');
        if (photoEl) photoEl.src = applicantData.applicant_photo ? `/${applicantData.applicant_photo}` : '/images/default.png';

        // Personal
        setElement('modalLastName', applicantData.last_name);
        setElement('modalFirstName', applicantData.first_name);
        setElement('modalMiddleName', applicantData.middle_name || 'N/A');
        setElement('modalDob', applicantData.dob);
        setElement('modalSex', applicantData.sex);
        setElement('modalCitizenship', applicantData.citizenship);
        setElement('modalPob', applicantData.place_of_birth);
        setElement('modalPobZip', applicantData.pob_zip_code);
        setElement('modalAddress', applicantData.permanent_address);
        setElement('modalZip', applicantData.zip_code);
        setElement('modalMobile', applicantData.mobile_number);
        setElement('modalEmail', applicantData.email_address || 'N/A');
        setElement('modalTribe', applicantData.tribal_membership || 'NONE');
        setElement('modalDisability', applicantData.disability_type || 'NONE');

        // Academic
        setElement('modalSchool', applicantData.school_name);
        setElement('modalSchoolIdNo', applicantData.school_id_number || 'N/A');
        setElement('modalSchoolAddress', applicantData.school_address);
        setElement('modalSector', applicantData.school_sector);
        setElement('modalCourse', applicantData.course || 'N/A');
        setElement('modalYear', applicantData.year_level);

        // Family
        setElement('modalFatherStatus', applicantData.father_status);
        setElement('modalFatherName', applicantData.father_name || 'N/A');
        setElement('modalFatherJob', applicantData.father_occupation || 'N/A');
        setElement('modalFatherAddress', applicantData.father_address || 'SAME AS PERMANENT');

        setElement('modalMotherStatus', applicantData.mother_status);
        setElement('modalMotherName', applicantData.mother_name || 'N/A');
        setElement('modalMotherJob', applicantData.mother_occupation || 'N/A');
        setElement('modalMotherAddress', applicantData.mother_address || 'SAME AS PERMANENT');

        setElement('modalIncome', `PHP ${parseFloat(applicantData.total_income || 0).toLocaleString()}`);
        setElement('modalSiblings', applicantData.siblings_count);
        setElement('modalAssistance', applicantData.has_assistance ? 'YES' : 'NO');

        const sigEl = document.getElementById('modalSignature');
        if (sigEl) sigEl.src = applicantData.signature_path ? `/${applicantData.signature_path}` : '/images/no-sig.png';

        // Attachments
        const handleDoc = (btnId, path, label) => {
            const btn = document.getElementById(btnId);
            if (!btn) return;
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

        handleDoc('btnEnrollment', applicantData.enrollment_proof, 'Enrollment Proof');
        handleDoc('btnIndigency', applicantData.indigency_certificate, 'Indigency Certificate');

        // Checkboxes
        const corCheckEl = document.getElementById('modalCorCheck');
        if (corCheckEl) corCheckEl.checked = Boolean(review?.admin_check_cor ?? applicantData.admin_check_cor);

        const indCheckEl = document.getElementById('modalIndigencyCheck');
        if (indCheckEl) indCheckEl.checked = Boolean(review?.admin_check_indigency ?? applicantData.admin_check_indigency);

        // Form fields
        const statusEl = document.getElementById('modalStatusSelect');
        if (statusEl) statusEl.value = applicantData.application_status || 'pending';

        const remarksEl = document.getElementById('modalAdminRemarks');
        if (remarksEl) remarksEl.value = review?.admin_remarks ?? applicantData.admin_remarks ?? '';

        const evalEl = document.getElementById('modalEvaluatedBy');
        if (evalEl) evalEl.value = review?.evaluated_by ?? applicantData.evaluated_by ?? '';

        const formEl = document.getElementById('adminDecisionForm');
        if (formEl) formEl.action = `/admin/applications/${app.id}/status`;

        const pdfEl = document.getElementById('downloadPdfBtn');
        if (pdfEl) pdfEl.href = `/admin/applications/${app.id}/pdf`;
    }

    function validateAdminForm() {
        const statusSelect = document.getElementById('modalStatusSelect').value;
        const remarks = document.getElementById('modalAdminRemarks').value.trim();
        const evaluatedBy = document.getElementById('modalEvaluatedBy').value.trim();

        // Validation
        if (!statusSelect || statusSelect === '') {
            alert('Please select an application status');
            return false;
        }
        if (!remarks) {
            alert('Please provide official remarks explaining the decision');
            return false;
        }
        if (!evaluatedBy) {
            alert('Please enter the name of the evaluator/processor');
            return false;
        }
        return true;
    }

    // LIVE SEARCH FILTER with Enhanced Feedback
    const searchInput = document.getElementById('searchFilter');
    const clearBtn = document.getElementById('clearSearchBtn');
    const tableBody = document.getElementById('applicationsTableBody');
    const totalCountEl = document.getElementById('totalCount');
    const pendingCountEl = document.getElementById('pendingCount');
    const approvedCountEl = document.getElementById('approvedCount');
    const rejectedCountEl = document.getElementById('rejectedCount');
    const searchFeedback = document.getElementById('searchFeedback');
    const feedbackText = document.getElementById('feedbackText');

    function clearSearch() {
        searchInput.value = '';
        searchInput.focus();
        clearBtn.classList.add('hidden');
        updateFiltersAndCounts();
    }

    function updateFiltersAndCounts() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const rows = tableBody.querySelectorAll('.applicant-row');
        let visibleCount = 0;
        let pendingCount = 0;
        let approvedCount = 0;
        let rejectedCount = 0;

        // Toggle clear button visibility
        if (searchTerm.length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }

        rows.forEach(row => {
            const name = row.getAttribute('data-name').toLowerCase();
            const course = row.getAttribute('data-course').toLowerCase();
            const school = row.getAttribute('data-school').toLowerCase();
            const status = row.getAttribute('data-status').toLowerCase();

            const matches = name.includes(searchTerm) || course.includes(searchTerm) || school.includes(searchTerm);

            if (searchTerm === '' || matches) {
                row.style.display = '';
                row.classList.add('animate-fadeIn');
                visibleCount++;

                if (status === 'pending') pendingCount++;
                else if (status === 'approved') approvedCount++;
                else if (status === 'rejected') rejectedCount++;
            } else {
                row.style.display = 'none';
                row.classList.remove('animate-fadeIn');
            }
        });

        // Update counts with smooth transition
        updateCountElement(totalCountEl, visibleCount);
        updateCountElement(pendingCountEl, pendingCount);
        updateCountElement(approvedCountEl, approvedCount);
        updateCountElement(rejectedCountEl, rejectedCount);

        // Update search feedback
        updateSearchFeedback(searchTerm, visibleCount);
    }

    function updateCountElement(element, newValue) {
        const currentValue = element.textContent;
        if (currentValue !== newValue.toString()) {
            element.parentElement.classList.add('scale-105');
            element.textContent = newValue;
            setTimeout(() => element.parentElement.classList.remove('scale-105'), 300);
        }
    }

    function updateSearchFeedback(searchTerm, resultCount) {
        if (searchTerm === '') {
            searchFeedback.classList.add('opacity-0');
            feedbackText.textContent = 'Ready to search...';
        } else {
            searchFeedback.classList.remove('opacity-0');
            if (resultCount === 0) {
                feedbackText.innerHTML = `<span class="text-red-500">No results found for "${searchTerm}"</span>`;
            } else if (resultCount === 1) {
                feedbackText.innerHTML = `<span class="text-green-500">Found <strong>1</strong> matching applicant</span>`;
            } else {
                feedbackText.innerHTML = `<span class="text-green-500">Found <strong>${resultCount}</strong> matching applicants</span>`;
            }
        }
    }

    // Event listener for live search with reduced debounce for responsiveness
    let debounceTimer;
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(updateFiltersAndCounts, 50);
    });

    // Initialize counts on page load
    updateFiltersAndCounts();

    // Add CSS for animations if not already present
    if (!document.querySelector('style[data-search-animations]')) {
        const style = document.createElement('style');
        style.setAttribute('data-search-animations', 'true');
        style.textContent = `
            .animate-fadeIn {
                animation: fadeIn 0.3s ease-in;
            }
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            .scale-105 {
                transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
                transform: scale(1.05);
            }
        `;
        document.head.appendChild(style);
    }
</script>
@endsection
