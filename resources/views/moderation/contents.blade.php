@extends('layouts.admin-layout')

@section('title', 'Content Moderation Reports')

@section('content')
    <div x-data="reportManager('{{ session('token') }}', '{{ $initialType ?? 'question' }}')" x-init="init()">
        <div class="flex justify-between items-center mb-2">
            <h1
                class="text-4xl font-black font-gotham text-transparent bg-clip-text bg-gradient-to-r from-[#5BE6B0] to-[#20BDA9]">
                Review Content Reports
            </h1>
        </div>

        {{-- Tab nav --}}
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

        <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto border" style="border: 2px solid #b0e0e4;">
            <div class="md:flex md:justify-between md:items-center mb-4 space-y-4 md:space-y-0">
                <h2 class="text-xl font-semibold text-gray-700"
                    x-text="`${activeType.charAt(0).toUpperCase() + activeType.slice(1)} Reports List`"></h2>

                {{-- Filter --}}
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center space-x-2">
                        <input type="date" x-model="startDate"
                            class="px-2 py-2 border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                        <span class="text-gray-500">to</span>
                        <input type="date" x-model="endDate" :min="startDate"
                            class="px-2 py-2 border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="relative">
                        <input type="text" x-model.debounce.500ms="search"
                            class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Search anything...">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3"><i
                                class="ri-search-line text-gray-400"></i></span>
                    </div>
                    <div class="relative">
                        <select x-model="activeStatus"
                            class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="pending">Pending</option>
                            <option value="resolved">Resolved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="relative">
                        <select x-model="reasonFilter"
                            class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="">All Reasons</option>
                            <template x-for="reason in reportReasons" :key="reason.id">
                                <option :value="reason.id" x-text="reason.title"></option>
                            </template>
                        </select>
                    </div>
                </div>
            </div>

            <div x-show="isLoading" class="flex justify-center items-center p-16">
                <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                    </circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span class="ml-3 text-gray-600">Loading Reports...</span>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto" x-show="!isLoading">
                <table class="min-w-full border divide-y" style="border-color: #b0e0e4; --tw-divide-opacity: 1;">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Report ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Reason & Content Preview</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date
                                Reported</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date
                                Processed</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Reporter</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-if="reports.length === 0">
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    No <span x-text="activeStatus"></span> <span x-text="activeType"></span> reports
                                    matching your criteria.
                                </td>
                            </tr>
                        </template>
                        <template x-for="report in reports" :key="report.id">
                            <tr class="align-top hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap text-sm">
                                    <button @click="viewFullContent(report)" class="font-bold text-blue-600 hover:underline"
                                        x-text="`#${report.id}`"></button>
                                    <span class="block text-gray-500 capitalize"
                                        x-text="report.reported_content.type"></span>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    <div class="font-semibold" x-text="report.reason"></div>
                                    <div class="mt-1 text-xs text-gray-500 italic" x-text="report.reported_content.preview">
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span :title="report.date_reported" x-text="report.date_for_humans"></span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <template x-if="report.date_processed">
                                        <span :title="report.date_processed"
                                            x-text="report.date_processed_for_humans"></span>
                                    </template>
                                    <template x-if="!report.date_processed">
                                        <span>-</span>
                                    </template>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <span x-text="report.reporter.name"></span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize"
                                        :class="{
                                            'bg-yellow-100 text-yellow-800': report.status === 'pending',
                                            'bg-green-100 text-green-800': report.status === 'resolved',
                                            'bg-red-100 text-red-800': report.status === 'rejected'
                                        }"
                                        x-text="report.status">
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        <button @click="viewFullContent(report)" class="text-gray-500 hover:text-blue-700"
                                            title="View Full Content"><i class="ri-eye-line text-lg"></i></button>
                                        <button @click="processReport('approve', report.id)"
                                            :disabled="report.status !== 'pending'"
                                            class="text-gray-500 hover:text-green-700 disabled:text-gray-300 disabled:cursor-not-allowed"
                                            title="Approve Report"><i class="ri-check-line text-lg"></i></button>
                                        <button @click="processReport('reject', report.id)"
                                            :disabled="report.status !== 'pending'"
                                            class="text-gray-500 hover:text-red-700 disabled:text-gray-300 disabled:cursor-not-allowed"
                                            title="Reject Report"><i class="ri-close-line text-lg"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            {{-- PAGINATION --}}
            <div x-show="!isLoading && pagination.total > 0"
                class="mt-4 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="text-sm text-gray-700">
                    Showing <span x-text="pagination.from || 0"></span> to <span x-text="pagination.to || 0"></span> of
                    <span x-text="pagination.total || 0"></span> results
                </div>
                <nav x-show="pagination.last_page > 1">
                    <ul class="inline-flex items-center -space-x-px">
                        <li><button @click="fetchReports(pagination.current_page - 1)"
                                :disabled="pagination.current_page <= 1"
                                class="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 disabled:opacity-50">Prev</button>
                        </li>
                        <template x-for="link in getPaginationLinks()" :key="link.label + Math.random()">
                            <li><button @click="link.url ? fetchReports(link.page) : null"
                                    :class="{
                                        'px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700':
                                            !link.active && link
                                            .url,
                                        'px-3 py-2 text-blue-600 border border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700': link
                                            .active,
                                        'px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 cursor-default':
                                            !link.url
                                    }"
                                    x-text="link.label"></button></li>
                        </template>
                        <li><button @click="fetchReports(pagination.current_page + 1)"
                                :disabled="pagination.current_page >= pagination.last_page"
                                class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 disabled:opacity-50">Next</button>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        {{-- MODAL DETAIL KONTEN --}}
        <div x-show="showDetailModal" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-60 flex items-center justify-center p-4"
            @keydown.escape.window="showDetailModal = false">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col"
                @click.away="showDetailModal = false">
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-800">Full Content View: <span class="capitalize"
                            x-text="selectedReport ? selectedReport.reported_content.type : ''"></span></h3>
                    <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-600"><i
                            class="ri-close-fill text-2xl"></i></button>
                </div>

                <div class="p-6 overflow-y-auto space-y-6">
                    <div x-show="isDetailLoading" class="flex justify-center items-center p-16">
                        <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span class="ml-3 text-gray-600">Loading Full Content...</span>
                    </div>

                    <div x-show="!isDetailLoading && detailedContent" class="space-y-6">

                        {{-- ========= QUESTION DETAIL ========= --}}
                        <template x-if="detailedContent && detailedContent.type === 'question'">
                            <div class="space-y-6">
                                {{-- Kotak Pertanyaan Utama --}}
                                <div class="bg-gray-50 p-4 rounded-lg border">
                                    <h4 class="text-2xl font-bold text-gray-900" x-text="detailedContent.title"></h4>
                                    <div class="flex items-center flex-wrap gap-x-4 gap-y-2 text-sm text-gray-500 my-3">
                                        <span><i class="ri-thumb-up-line"></i> <span
                                                x-text="detailedContent.vote || 0"></span> Votes</span>
                                        <span><i class="ri-eye-line"></i> <span x-text="detailedContent.view || 0"></span>
                                            Views</span>
                                        <span><i class="ri-chat-3-line"></i> <span
                                                x-text="detailedContent.comments_count || 0"></span> Comments</span>
                                        <span class="text-red-600"><i class="ri-flag-line"></i> <span
                                                x-text="detailedContent.report || 0"></span> Reports</span>
                                    </div>
                                    <p class="text-gray-700 leading-relaxed" x-html="detailedContent.question"></p>
                                    <div x-show="detailedContent.image_url" class="mt-4"><img
                                            :src="detailedContent.image_url" alt="Question Image"
                                            class="max-w-full h-auto rounded-lg border shadow-md"></div>
                                    <div class="mt-3 text-xs text-gray-500" x-show="detailedContent.user">Posted by: <span
                                            class="font-medium" x-text="detailedContent.user.username"></span></div>
                                </div>

                                {{-- Daftar Komentar pada Pertanyaan --}}
                                <div class="pl-4">
                                    <h6 class="text-md font-semibold mb-2">Comments on this Question</h6>
                                    <div class="space-y-3">
                                        <template x-for="comment in detailedContent.comments" :key="comment.id">
                                            <div class="border-l-4 border-gray-200 pl-4 text-sm">
                                                <p x-text="comment.comment"></p>
                                                <div class="text-xs text-gray-500 mt-1" x-show="comment.user">By: <span
                                                        class="font-medium" x-text="comment.user.username"></span></div>
                                            </div>
                                        </template>
                                        <div x-show="!detailedContent.comments || detailedContent.comments.length === 0"
                                            class="text-center text-gray-500 py-2 text-sm">No comments found for this
                                            question.</div>
                                    </div>
                                </div>

                                {{-- Daftar Jawaban dari Pertanyaan --}}
                                <div>
                                    <h5 class="text-lg font-semibold mb-3"
                                        x-text="`Answers (${detailedContent.answer ? detailedContent.answer.length : 0})`">
                                    </h5>
                                    <div class="space-y-4">
                                        <template x-for="item in detailedContent.answer" :key="item.id">
                                            <div class="border p-4 rounded-lg"
                                                :class="{ 'border-green-400 bg-green-50': item.verified }">
                                                <div x-show="item.verified"
                                                    class="inline-flex items-center bg-green-200 text-green-800 text-xs font-bold px-2.5 py-0.5 rounded-full mb-2">
                                                    <i class="ri-check-double-line mr-1"></i> Verified Answer
                                                </div>
                                                <p x-html="item.answer"></p>
                                                <div class="text-xs text-gray-500 mt-3">Answered by: <span
                                                        class="font-medium" x-text="item.user.username"></span> - <span
                                                        class="text-red-600"><i class="ri-flag-line"></i> <span
                                                            x-text="item.report || 0"></span> Reports</span></div>
                                            </div>
                                        </template>
                                        <div x-show="!detailedContent.answer || detailedContent.answer.length === 0"
                                            class="text-center text-gray-500 py-4">No answers found for this question.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- ========= ANSWER DETAIL ========= --}}
                        <template x-if="detailedContent && detailedContent.type === 'answer'">
                            <div class="space-y-4">
                                <div class="mb-4">
                                    <h5 class="text-md font-semibold text-gray-600">In response to Question:</h5>
                                    <p class="text-lg font-bold text-blue-600"
                                        x-text="detailedContent.question ? detailedContent.question.title : '[Question Deleted]'">
                                    </p>
                                </div>
                                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                    <p class="text-gray-700 leading-relaxed" x-html="detailedContent.answer"></p>
                                    <div class="mt-3 text-xs text-gray-500" x-show="detailedContent.user">Answered by:
                                        <span class="font-medium" x-text="detailedContent.user.username"></span>
                                    </div>
                                </div>

                                {{-- Daftar Komentar dari Jawaban --}}
                                <h5 class="text-lg font-semibold pt-4">Comments on this Answer</h5>
                                <div class="space-y-3">
                                    <template x-for="comment in detailedContent.comments" :key="comment.id">
                                        <div class="border-l-4 border-gray-200 pl-4 text-sm">
                                            <p x-text="comment.comment"></p>
                                            <div class="text-xs text-gray-500 mt-1" x-show="comment.user">By: <span
                                                    class="font-medium" x-text="comment.user.username"></span></div>
                                        </div>
                                    </template>
                                    <div x-show="!detailedContent.comments || detailedContent.comments.length === 0"
                                        class="text-center text-gray-500 py-4">No comments found for this answer.</div>
                                </div>
                            </div>
                        </template>

                        {{-- ========= COMMENT DETAIL ========= --}}
                        <template x-if="detailedContent && detailedContent.type === 'comment'">
                            <div class="space-y-4">
                                <div class="mb-4" x-if="detailedContent.commentable">
                                    <h5 class="text-md font-semibold text-gray-600">
                                        Comment on <span class="capitalize"
                                            x-text="detailedContent.commentable_type.toLowerCase()"></span>
                                    </h5>
                                    <p class="mt-1 p-2 bg-gray-100 border-l-4 border-gray-300 text-sm text-gray-700 rounded italic"
                                        x-text="detailedContent.commentable.title || (detailedContent.commentable.answer ? detailedContent.commentable.answer.substring(0, 100) + '...' : '[Content Deleted]')">
                                    </p>
                                </div>
                                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                                    <p class="text-gray-700 leading-relaxed" x-html="detailedContent.comment"></p>
                                    <div class="flex items-center space-x-4 text-sm text-gray-500 mt-3">
                                        <span class="text-red-600"><i class="ri-flag-line"></i> <span
                                                x-text="detailedContent.report || 0"></span> Reports</span>
                                    </div>
                                    <div class="mt-3 text-xs text-gray-500" x-show="detailedContent.user">Comment by:
                                        <span class="font-medium" x-text="detailedContent.user.username"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function reportManager(authToken, initialType) {
            const apiBaseUrl = '{{ config('app.api_url', 'http://127.0.0.1:8001') }}';

            return {
                reports: [],
                pagination: {},
                reportReasons: [],
                reasonFilter: '',
                isLoading: true,
                search: '',
                activeType: initialType || 'question',
                authToken: authToken,
                showDetailModal: false,
                selectedReport: null,
                startDate: '',
                endDate: '',
                activeStatus: 'pending',
                isDetailLoading: false,
                detailedContent: null,

                init() {
                    this.fetchReportReasons();
                    this.fetchReports();
                    this.$watch('search', () => this.fetchReports(1));
                    this.$watch('activeStatus', () => this.fetchReports(1));
                    this.$watch('reasonFilter', () => this.fetchReports(1));
                    let dateTimeout;
                    this.$watch(['startDate', 'endDate'], () => {
                        clearTimeout(dateTimeout);
                        dateTimeout = setTimeout(() => {
                            if ((this.startDate && this.endDate) || (!this.startDate && !this.endDate)) this
                                .fetchReports(1);
                        }, 500);
                    });
                },
                changeType(newType) {
                    this.activeType = newType;
                    this.fetchReports(1);
                },
                async fetchReportReasons() {
                    try {
                        const response = await fetch(`${apiBaseUrl}/api/admin/report-reasons`, {
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${this.authToken}`
                            }
                        });
                        if (!response.ok) throw new Error('Failed to fetch reasons');
                        this.reportReasons = await response.json();
                    } catch (error) {
                        console.error('Error fetching report reasons:', error);
                        this.showToast('error', 'Failed to load report reasons.');
                    }
                },
                async fetchReports(page = 1) {
                    if (page < 1 || (this.pagination.last_page && page > this.pagination.last_page)) return;
                    this.isLoading = true;
                    try {
                        const params = new URLSearchParams({
                            page,
                            per_page: 5,
                            search: this.search,
                            type: this.activeType,
                            status: this.activeStatus
                        });
                        if (this.reasonFilter) params.append('reason_id', this.reasonFilter);
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
                async viewFullContent(report) {
                    this.selectedReport = report;
                    this.showDetailModal = true;
                    this.isDetailLoading = true;
                    this.detailedContent = null;
                    if (!report.reported_content || !report.reported_content.id) {
                        this.showToast('error', 'Reported content data is missing or invalid.');
                        this.isDetailLoading = false;
                        this.showDetailModal = false;
                        return;
                    }
                    const type = report.reported_content.type.toLowerCase();
                    const id = report.reported_content.id;
                    try {
                        const response = await fetch(`${apiBaseUrl}/api/admin/content-detail/${type}/${id}`, {
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${this.authToken}`
                            }
                        });
                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message || `Failed to fetch content details.`);
                        }

                        const result = await response.json();
                        this.detailedContent = result.data ||
                            result;
                    } catch (error) {
                        console.error('Error fetching full content:', error);
                        this.showToast('error', error.message);
                        this.showDetailModal = false;
                    } finally {
                        this.isDetailLoading = false;
                    }
                },
                processReport(action, reportId) {
                    const actionText = action.charAt(0).toUpperCase() + action.slice(1);
                    Swal.fire({
                        title: `Confirm ${actionText}`,
                        text: `Are you sure you want to ${action} this report?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: action === 'approve' ? '#3085d6' : '#d33',
                        cancelButtonText: 'Cancel',
                        confirmButtonText: `Yes, ${action} it!`
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                const response = await fetch(
                                    `${apiBaseUrl}/api/admin/reports/${reportId}/process`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'Accept': 'application/json',
                                            'Authorization': `Bearer ${this.authToken}`
                                        },
                                        body: JSON.stringify({
                                            action
                                        })
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
                    const {
                        current_page,
                        last_page
                    } = this.pagination;
                    const onSides = 1;
                    let links = [];
                    for (let i = 1; i <= last_page; i++) {
                        let offset = Math.abs(current_page - i);
                        if (i === 1 || i === last_page || offset < onSides + 1 || (current_page < 4 && i < 4) || (
                                last_page - current_page < 3 && i > last_page - 4)) {
                            links.push({
                                label: i.toString(),
                                page: i,
                                active: i === current_page,
                                url: true
                            });
                        } else if (links.length > 0 && links[links.length - 1].label !== '...') {
                            links.push({
                                label: '...',
                                page: null,
                                active: false,
                                url: false
                            });
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
                    Toast.fire({
                        icon,
                        title
                    });
                }
            };
        }
    </script>
@endpush
