@extends('layouts.admin-layout')

@section('title', 'Review Question Reports')

@section('content')
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Review Question Reports</h1>

    {{-- Konten tabel Anda tetap di sini seperti sebelumnya --}}
    <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
        {{-- ... isi tabel laporan pertanyaan Anda ... --}}
        <div class="mb-4 flex justify-between items-center">
            <div class="relative w-full max-w-xs">
                <input type="text" class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Search reports...">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="ri-search-line text-gray-400"></i>
                </span>
            </div>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question Preview</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reporter</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Reported</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                $reportedQuestions = $reportedQuestions ?? [
                    (object)['id' => 101, 'question_title' => 'How to install dual boot Windows and Linux?', 'question_preview' => 'I am trying to set up a dual boot system with Windows 11 and Ubuntu 24.04...', 'reporter' => 'UserAlpha', 'reporter_id' => 201, 'reason' => 'Duplicate Question (already asked by #98)', 'reported_at' => '2025-05-22', 'status' => 'Pending'],
                    (object)['id' => 105, 'question_title' => 'Best way to learn Python for Data Science in 2025?', 'question_preview' => 'What is the most effective and up-to-date pathway to learn Python specifically for data science applications?...', 'reporter' => 'UserBeta', 'reporter_id' => 202, 'reason' => 'Off-topic for this specific "Web Development" subject.', 'reported_at' => '2025-05-21', 'status' => 'Pending'],
                ];
                @endphp
                @forelse ($reportedQuestions as $report)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <a href="#" class="text-blue-600 hover:underline">#{{ $report->id }}</a>
                    </td>
                    <td class="px-6 py-4 whitespace-normal text-sm text-gray-700 max-w-xs group">
                        <span class="font-semibold text-gray-800 block">{{ Str::limit($report->question_title, 60) }}</span>
                        <span class="text-xs text-gray-500 truncate group-hover:whitespace-normal group-hover:overflow-visible block">{{ Str::limit($report->question_preview, 100) }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        <a href="#" class="text-blue-600 hover:underline">{{ $report->reporter }}</a>
                    </td>
                    <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">{{ $report->reason }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($report->reported_at)->diffForHumans() }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-1">
                        {{-- Tombol-tombol aksi Anda tetap di sini --}}
                        {{-- <button class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-100" title="View Report Details" onclick="viewReportDetail('question', {{ $report->id }})">
                            <i class="ri-information-line text-lg"></i>
                        </button> --}}
                        <button class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-100" title="Approve Report (e.g., Delete Question)" onclick="processReport('approve', 'question', {{ $report->id }})">
                            <i class="ri-check-line text-lg"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-100" title="Reject Report" onclick="processReport('reject', 'question', {{ $report->id }})">
                            <i class="ri-close-line text-lg"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                        No new question reports matching your criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
         <div class="mt-6">
            {{-- Pagination --}}
        </div>
    </div>

    

@push('scripts')
<script>
    // Fungsi-fungsi JavaScript Anda (viewReportDetail, processReport, performConfirmedAction) tetap di sini
    // Namun, untuk testing di atas, fungsi-fungsi ini tidak langsung dipanggil oleh modal yang disederhanakan.
    function viewReportDetail(type, id) {
        window.dispatchEvent(new CustomEvent('view-report-detail', { detail: { type: type, id: id }}));
    }

    function processReport(action, reportType, reportId) {
        window.dispatchEvent(new CustomEvent('process-report-confirm', { detail: { action: action, reportType: reportType, reportId: reportId }}));
    }

    function performConfirmedAction() {
        const alpineComponent = document.querySelector('[x-data^="{ openModal:"]');
        if (!alpineComponent || !alpineComponent.__x) {
            console.error('Alpine component for main modal not found.');
            // alert('Error: Alpine component not found.'); // Mungkin nonaktifkan alert ini jika modal utama sedang tidak aktif
            return;
        }
        const alpineData = alpineComponent.__x.$data;
        // ... sisa fungsi performConfirmedAction
        alert(`Action confirmed for ${alpineData.reportType} #${alpineData.reportId}. Action: ${alpineData.actionForConfirmation}. Implement AJAX.`);
        alpineData.openModal = false;
        alpineData.actionForConfirmation = '';
    }
</script>
@endpush
@endsection