@extends('layouts.admin-layout')

@section('title', 'Content Moderation Reports')

@section('content')
{{-- Include SweetAlert2 for better notifications --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div x-data="reportManager('{{ session('token') }}', '{{ $initialType ?? 'question' }}')" x-init="init()">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Review Content Reports</h1>

    {{-- Tab Navigation --}}
    <div class="mb-6 border-b border-gray-200">
        <nav class="flex flex-wrap -mb-px sm:space-x-4" aria-label="Tabs">
            <button @click="changeType('question')"
                :class="{ 'border-blue-500 text-blue-600': activeType === 'question', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeType !== 'question' }"
                class="whitespace-nowrap py-3 px-2 sm:px-4 border-b-2 font-medium text-sm focus:outline-none">
                Question Reports
            </button>
            <button @click="changeType('answer')"
                :class="{ 'border-blue-500 text-blue-600': activeType === 'answer', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeType !== 'answer' }"
                class="whitespace-nowrap py-3 px-2 sm:px-4 border-b-2 font-medium text-sm focus:outline-none">
                Answer Reports
            </button>
            <button @click="changeType('comment')"
                :class="{ 'border-blue-500 text-blue-600': activeType === 'comment', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeType !== 'comment' }"
                class="whitespace-nowrap py-3 px-2 sm:px-4 border-b-2 font-medium text-sm focus:outline-none">
                Comment Reports
            </button>
        </nav>
    </div>

    {{-- Report Table and Controls --}}
    <div class="bg-white p-6 rounded-lg shadow-lg">
         <div class="md:flex md:justify-between md:items-center mb-4 space-y-4 md:space-y-0">
            <h2 class="text-xl font-semibold text-gray-700" x-text="`${activeType.charAt(0).toUpperCase() + activeType.slice(1)} Reports List`"></h2>
            
            {{-- Filter Controls --}}
            <div class="flex flex-wrap items-center gap-4">
                {{-- Date Range Filter --}}
                <div class="flex items-center space-x-2">
                    <input type="date" x-model="startDate" class="px-2 py-2 border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                    <span class="text-gray-500">to</span>
                    <input type="date" x-model="endDate" :min="startDate" class="px-2 py-2 border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                 {{-- Search Input --}}
                <div class="relative">
                     <input type="text" x-model.debounce.500ms="search"
                        class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Search anything...">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="ri-search-line text-gray-400"></i>
                    </span>
                </div>
            </div>
        </div>

        {{-- Loading Spinner --}}
        <div x-show="isLoading" class="flex justify-center items-center p-16">
            <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="ml-3 text-gray-600">Loading Reports...</span>
        </div>

        {{-- Table Container --}}
        <div class="overflow-x-auto" x-show="!isLoading">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report Info</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reported Content</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Related To</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reporter</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-if="reports.length === 0">
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No new <span x-text="activeType"></span> reports matching your criteria.
                            </td>
                        </tr>
                    </template>
                    <template x-for="report in reports" :key="report.id">
                        <tr class="align-top">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <a :href="report.reported_content.url" target="_blank" class="text-blue-600 hover:underline font-bold" x-text="`#${report.id}`"></a>
                                <span class="block text-gray-500 text-xs" :title="report.date_reported" x-text="report.date_for_humans"></span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 max-w-sm">
                                <a :href="report.reported_content.url" target="_blank" class="text-blue-600 hover:underline block font-semibold" x-text="`Preview of ${report.reported_content.type} #${report.reported_content.id}`"></a>
                                <p class="break-words mt-1 text-gray-600" x-text="report.reported_content.preview"></p>
                                <p class="mt-2 text-xs text-red-700 bg-red-100 p-1 rounded"><strong>Reason:</strong> <span x-text="report.reason"></span></p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                 <template x-if="report.parent_content">
                                    <a :href="report.parent_content.url" target="_blank" class="text-blue-600 hover:underline" x-text="`${report.parent_content.type} #${report.parent_content.id}`"></a>
                                 </template>
                                 <template x-if="!report.parent_content">
                                    <span>-</span>
                                 </template>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <a :href="report.reporter.url" target="_blank" class="text-blue-600 hover:underline" x-text="report.reporter.name"></a>
                                <span class="text-xs text-gray-500 block" x-text="`(ID: ${report.reporter.id})`"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-col space-y-2">
                                     <button @click="viewReportDetail(report)" class="flex items-center text-blue-600 hover:text-blue-900" title="View Report Details">
                                        <i class="ri-information-line text-lg mr-1"></i> Details
                                    </button>
                                    <button @click="processReport('approve', report.id)" class="flex items-center text-green-600 hover:text-green-900" title="Approve Report">
                                        <i class="ri-check-line text-lg mr-1"></i> Approve
                                    </button>
                                    <button @click="processReport('reject', report.id)" class="flex items-center text-red-600 hover:text-red-900" title="Reject Report">
                                        <i class="ri-close-line text-lg mr-1"></i> Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div x-show="!isLoading && pagination.total > 0" class="mt-4 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <div class="text-sm text-gray-700">
                Showing <span x-text="pagination.from || 0"></span> to <span x-text="pagination.to || 0"></span> of <span x-text="pagination.total || 0"></span> results
            </div>
            <nav x-show="pagination.last_page > 1">
                <ul class="inline-flex items-center -space-x-px">
                    <li>
                        <button @click="fetchReports(pagination.current_page - 1)" :disabled="pagination.current_page <= 1" class="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 disabled:opacity-50">Prev</button>
                    </li>
                    <template x-for="link in getPaginationLinks()" :key="link.label + Math.random()">
                        <li>
                            <button @click="link.url ? fetchReports(link.page) : null"
                                :class="{
                                    'px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700': !link.active && link.url,
                                    'px-3 py-2 text-blue-600 border border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700': link.active,
                                    'px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 cursor-default': !link.url
                                }"
                                x-text="link.label">
                            </button>
                        </li>
                    </template>
                    <li>
                        <button @click="fetchReports(pagination.current_page + 1)" :disabled="pagination.current_page >= pagination.last_page" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 disabled:opacity-50">Next</button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    {{-- MODAL DETAIL --}}
    <div x-show="showDetailModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-60 flex items-center justify-center p-4" @keydown.escape.window="showDetailModal = false">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-2xl" @click.away="showDetailModal = false">
            <div class="flex justify-between items-center mb-4 border-b pb-3">
                <h3 class="text-xl font-semibold text-gray-800">Report Details</h3>
                <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-600"><i class="ri-close-fill text-2xl"></i></button>
            </div>
            
            {{-- PERBAIKAN KUNCI: Gunakan <template> untuk membungkus seluruh konten modal --}}
            <template x-if="selectedReport">
                <div class="space-y-4 text-gray-700">
                    <div>
                        <h4 class="font-bold">Reported Content</h4>
                        <div class="mt-1 p-3 bg-gray-50 border rounded">
                            <p class="text-sm text-gray-500" x-text="`Type: ${selectedReport.reported_content.type}`"></p>
                            <p class="mt-2" x-text="selectedReport.reported_content.preview"></p>
                            <a :href="selectedReport.reported_content.url" target="_blank" class="text-blue-600 text-sm hover:underline mt-2 inline-block">View Full Content &rarr;</a>
                        </div>
                    </div>
                    
                    {{-- PERBAIKAN: Gunakan <template> untuk elemen kondisional --}}
                    <template x-if="selectedReport.parent_content">
                        <div>
                             <h4 class="font-bold">Related To</h4>
                            <div class="mt-1 p-3 bg-gray-50 border rounded">
                                <p x-text="`${selectedReport.parent_content.type}: ${selectedReport.parent_content.title}`"></p>
                                <a :href="selectedReport.parent_content.url" target="_blank" class="text-blue-600 text-sm hover:underline mt-2 inline-block">View Parent Content &rarr;</a>
                            </div>
                        </div>
                    </template>
                    
                    <div>
                        <h4 class="font-bold">Report Information</h4>
                         <div class="mt-1 p-3 bg-gray-50 border rounded">
                            <p><strong>Reason:</strong> <span x-text="selectedReport.reason"></span></p>
                            <p><strong>Reporter:</strong> <a :href="selectedReport.reporter.url" x-text="selectedReport.reporter.name" target="_blank" class="text-blue-600 hover:underline"></a></p>
                            <p><strong>Date:</strong> <span x-text="selectedReport.date_reported"></span></p>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function reportManager(authToken, initialType) {
        const apiBaseUrl = 'http://127.0.0.1:8001';

        return {
            reports: [],
            pagination: {},
            isLoading: true,
            search: '',
            activeType: initialType || 'question',
            authToken: authToken,
            showDetailModal: false,
            selectedReport: null,
            startDate: '', // state untuk tanggal mulai
            endDate: '',   // state untuk tanggal akhir

            init() {
                this.fetchReports();
                this.$watch('search', () => this.fetchReports(1));
                
                // Awasi perubahan pada tanggal
                let dateTimeout;
                this.$watch(['startDate', 'endDate'], () => {
                    clearTimeout(dateTimeout);
                    dateTimeout = setTimeout(() => {
                        // Hanya fetch jika kedua tanggal sudah diisi atau keduanya kosong
                        if ((this.startDate && this.endDate) || (!this.startDate && !this.endDate)) {
                           this.fetchReports(1);
                        }
                    }, 500); // Debounce untuk input tanggal
                });
            },

            changeType(newType) {
                this.activeType = newType;
                this.fetchReports(1);
            },

            async fetchReports(page = 1) {
                if (page < 1 || (this.pagination.last_page && page > this.pagination.last_page)) return;

                this.isLoading = true;
                try {
                    const params = new URLSearchParams({
                        page: page,
                        per_page: 5,
                        search: this.search,
                        type: this.activeType
                    });

                    if (this.startDate) params.append('start_date', this.startDate);
                    if (this.endDate) params.append('end_date', this.endDate);

                    const response = await fetch(`${apiBaseUrl}/api/admin/reports?${params.toString()}`, {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${this.authToken}`
                        }
                    });

                    if (!response.ok) throw new Error(`Server responded with status: ${response.status}`);
                    
                    const result = await response.json();
                    this.reports = result.data;
                    this.pagination = result.meta;
                } catch (error) {
                    console.error('Error fetching reports:', error);
                    this.showToast('error', 'Failed to load report data.');
                } finally {
                    this.isLoading = false;
                }
            },

            viewReportDetail(report) {
                this.selectedReport = report;
                this.showDetailModal = true;
            },

            processReport(action, reportId) {
                const actionText = action.charAt(0).toUpperCase() + action.slice(1);
                Swal.fire({
                    title: `Confirm ${actionText}`,
                    text: `Are you sure you want to ${action} this report? This action might be irreversible.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: action === 'approve' ? '#3085d6' : '#d33',
                    cancelButtonText: 'Cancel',
                    confirmButtonText: `Yes, ${action} it!`
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`${apiBaseUrl}/api/admin/reports/${reportId}/process`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'Authorization': `Bearer ${this.authToken}`
                                },
                                body: JSON.stringify({ action: action })
                            });

                            if (!response.ok) throw new Error('Server processing failed.');
                            
                            this.showToast('success', `Report has been ${action}d.`);
                            this.fetchReports(this.pagination.current_page || 1);
                        } catch (error) {
                            console.error(`Error processing report:`, error);
                            this.showToast('error', `Failed to ${action} the report.`);
                        }
                    }
                });
            },

            getPaginationLinks() {
                if (!this.pagination.last_page || this.pagination.last_page <= 1) return [];

                const { current_page, last_page } = this.pagination;
                const onSides = 1;
                let links = [];

                for (let i = 1; i <= last_page; i++) {
                    let offset = Math.abs(current_page - i);
                    if (i === 1 || i === last_page || offset < onSides + 1 || (current_page < 4 && i < 4) || (last_page - current_page < 3 && i > last_page - 4)) {
                         links.push({
                            label: i.toString(),
                            page: i,
                            active: i === current_page,
                            url: true,
                        });
                    } else if (links[links.length - 1].label !== '...') {
                         links.push({ label: '...', page: null, active: false, url: false });
                    }
                }
                return links;
            },

            showToast(icon, title) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
                Toast.fire({ icon, title });
            }
        };
    }
</script>
@endpush
