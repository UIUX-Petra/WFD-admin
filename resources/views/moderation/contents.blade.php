@extends('layouts.admin-layout')

@section('title', 'Content Moderation Reports')
@section('content')
    <style>
        .pagination-links nav {
            display: flex;
            justify-content: center;
        }

        .pagination-links ul.pagination {
            /* Bootstrap default */
            padding-left: 0;
            margin: 20px 0;
            border-radius: 4px;
            list-style: none;
            display: inline-block;
        }

        .pagination-links .page-item {
            display: inline;
        }

        .pagination-links .page-link {
            position: relative;
            display: block;
            padding: .5rem .75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: var(--accent-primary);
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            transition: all 0.2s ease-in-out;
        }

        .pagination-links .page-item:first-child .page-link {
            margin-left: 0;
            border-top-left-radius: .25rem;
            border-bottom-left-radius: .25rem;
        }

        .pagination-links .page-item:last-child .page-link {
            border-top-right-radius: .25rem;
            border-bottom-right-radius: .25rem;
        }

        .pagination-links .page-link:hover {
            z-index: 2;
            color: var(--accent-secondary);
            background-color: var(--bg-card-hover);
            border-color: var(--accent-primary);
        }

        .pagination-links .page-item.active .page-link {
            z-index: 3;
            color: var(--text-dark);
            background-color: var(--accent-primary);
            border-color: var(--accent-primary);
        }

        .pagination-links .page-item.disabled .page-link {
            color: var(--text-muted);
            pointer-events: none;
            background-color: var(--bg-card);
            border-color: var(--border-color);
        }
    </style>


    {{-- Gunakan tipe dari controller untuk menentukan tab aktif secara dinamis --}}
    <div x-data="{ activeTab: '{{ $type }}_reports' }">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6">Review Content Reports</h1>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="mb-6 border-b border-gray-200">
            {{-- Navigasi Tab, sekarang link biasa agar halaman me-reload dengan parameter query --}}
            <nav class="flex flex-wrap -mb-px sm:space-x-4" aria-label="Tabs">
                <a href="{{ route('admin.moderation.reports', array_merge(request()->query(), ['type' => 'question', 'page' => 1])) }}"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'question_reports', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'question_reports' }"
                    class="whitespace-nowrap py-3 px-2 sm:px-4 border-b-2 font-medium text-sm focus:outline-none">
                    Question Reports
                </a>
                <a href="{{ route('admin.moderation.reports', array_merge(request()->query(), ['type' => 'answer', 'page' => 1])) }}"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'answer_reports', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'answer_reports' }"
                    class="whitespace-nowrap py-3 px-2 sm:px-4 border-b-2 font-medium text-sm focus:outline-none">
                    Answer Reports
                </a>
                <a href="{{ route('admin.moderation.reports', array_merge(request()->query(), ['type' => 'comment', 'page' => 1])) }}"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'comment_reports', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'comment_reports' }"
                    class="whitespace-nowrap py-3 px-2 sm:px-4 border-b-2 font-medium text-sm focus:outline-none">
                    Comment Reports
                </a>
            </nav>
        </div>

        {{-- Tampilkan tabel berdasarkan tab aktif --}}
        <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">{{ ucfirst($type) }} Reports List</h2>
            {{-- Kirim data reports (Paginator) ke partial view --}}
            @include('moderation.partials._report_table', [
                'reports' => $reports,
                'reportItemType' => $type,
            ])
            <div id="pagination-container" class="mt-8 pagination-links">
                @if ($reports->hasPages())
                    {{-- Gunakan template bootstrap-5 untuk paginasi --}}
                    {{ $reports->links() }}
                @endif
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            // Fungsi JavaScript global Anda
            function viewReportDetail(type, id) {
                console.log(`Dispatching view-report-detail for main modal: type=${type}, id=${id}`);
                window.dispatchEvent(new CustomEvent('view-report-detail', {
                    detail: {
                        type: type,
                        id: id
                    }
                }));
            }

            function processReport(action, reportType, reportId) {
                console.log(
                    `Dispatching process-report-confirm for main modal: action=${action}, type=${reportType}, id=${id}`);
                window.dispatchEvent(new CustomEvent('process-report-confirm', {
                    detail: {
                        action: action,
                        reportType: reportType,
                        reportId: reportId
                    }
                }));
            }

            function performConfirmedAction() {
                // Target modal utama yang mungkin dikomentari
                const alpineComponent = document.querySelector(
                    '[x-data*="originalQuestionTitle"]'); // Atau selector lain yang spesifik untuk modal utama Anda
                if (!alpineComponent || !alpineComponent.__x) {
                    console.warn(
                        'Main Alpine modal component not found for performConfirmedAction. This is expected if it is commented out.'
                    );
                    // Jika Anda ingin fungsi ini tetap bekerja dengan modal test, Anda perlu logika tambahan
                    // atau buat fungsi terpisah untuk modal test. Untuk saat ini, kita fokus pada diagnosa.
                    return;
                }
                const alpineData = alpineComponent.__x.$data;

                const reportType = alpineData.reportType;
                const reportId = alpineData.reportId;
                const action = alpineData.actionForConfirmation;

                if (!action || !reportType || !reportId) {
                    // alert('Error: Could not determine action details for confirmation from main modal data.');
                    if (alpineData) alpineData.openModal = false;
                    return;
                }
                alert(
                    `Main modal: Action confirmed for ${reportType} #${reportId}. Action: ${action}. Implement AJAX logic here.`
                );
                alpineData.openModal = false;
                alpineData.actionForConfirmation = '';
            }
        </script>
    @endpush
@endsection
