@extends('layouts.admin-layout')

@section('title', 'Review Comment Reports')

@section('content')
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Review Comment Reports</h1>

    <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
        <div class="mb-4 flex justify-between items-center">
            <div class="relative w-full max-w-xs">
                <input type="text" class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Search reports...">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="ri-search-line text-gray-400"></i>
                </span>
            </div>
            <div class="flex items-center space-x-2">
                <label for="report_status_filter" class="text-sm font-medium text-gray-700">Status:</label>
                <select id="report_status_filter" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="all">All</option>
                    <option value="pending" selected>Pending Review</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        </div>
        {{-- Menambahkan class table-auto atau table-fixed bisa membantu jika ada masalah lebar kolom --}}
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Comment ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[250px] max-w-sm">Comment Preview</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">On Content</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reporter</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[150px]">Reason</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Reported</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                $reportedComments = $reportedComments ?? [
                    (object)[
                        'id' => 301,
                        'comment_preview' => 'This is totally wrong, you should use a different approach. The author of this answer clearly doesn\'t know what they are talking about. It lacks fundamental understanding and is misleading to others seeking genuine help. A better method would involve X, Y, and Z for optimal results.',
                        'reported_on_type' => 'Answer',
                        'reported_on_id' => 201,
                        'reported_on_content_preview' => 'You should definitely use framework X for this task as it provides better performance and easier scalability compared to other options discussed earlier in this thread by UserABC.',
                        'reporter' => 'UserZeta',
                        'reporter_id' => 208,
                        'reason' => 'Disrespectful and aggressive tone. Unconstructive criticism towards another user.',
                        'reported_at' => '2025-05-24 10:30:00',
                        'status' => 'Pending'
                    ],
                    (object)[
                        'id' => 302,
                        'comment_preview' => 'Hey, check out my website for more great tutorials and exclusive content: my-awesome-tutorials-and-spam-link.com/subscribe-now - Don\'t miss out on this incredible opportunity to learn from the best!',
                        'reported_on_type' => 'Question',
                        'reported_on_id' => 105,
                        'reported_on_content_preview' => 'Best way to learn Python for Data Science in 2025? Looking for courses.',
                        'reporter' => 'UserEta',
                        'reporter_id' => 209,
                        'reason' => 'Spam / Self-promotion. External link not relevant to the discussion.',
                        'reported_at' => '2025-05-23 15:12:00',
                        'status' => 'Pending'
                    ],
                    (object)[
                        'id' => 303,
                        'comment_preview' => 'LOL what a noob question. RTFM before wasting people\'s time here. Seriously, just google it, it\'s not that hard. Some people should not be allowed to ask questions.',
                        'reported_on_type' => 'Question',
                        'reported_on_id' => 120,
                        'reported_on_content_preview' => 'Help with my C++ homework on pointers, I am getting a segfault.',
                        'reporter' => 'UserTheta',
                        'reporter_id' => 210,
                        'reason' => 'Unhelpful, demeaning, and gatekeeping behavior. Very rude to the new user.',
                        'reported_at' => '2025-05-25 08:00:00',
                        'status' => 'Pending'
                    ],
                     (object)[
                        'id' => 304,
                        'comment_preview' => 'This link is dead: example.com/deadlink. Can you update it?',
                        'reported_on_type' => 'Answer',
                        'reported_on_id' => 202,
                        'reported_on_content_preview' => 'The main library for this is at example.com/library...',
                        'reporter' => 'UserKappa',
                        'reporter_id' => 211,
                        'reason' => 'Broken Link / Outdated Information',
                        'reported_at' => '2025-05-25 11:45:00',
                        'status' => 'Pending'
                    ],
                ];
                @endphp
                @forelse ($reportedComments as $report)
                <tr class="align-top"> {{-- Perataan vertikal ke atas --}}
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <a href="#" class="text-blue-600 hover:underline">#{{ $report->id }}</a>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700 min-w-[250px] max-w-sm"> {{-- Atur min/max width --}}
                        <p class="break-words">{{ Str::limit($report->comment_preview, 150) }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <a href="#" class="text-blue-600 hover:underline">{{ $report->reported_on_type }} #{{ $report->reported_on_id }}</a>
                        <p class="text-xs text-gray-400 truncate">{{ Str::limit($report->reported_on_content_preview, 50) }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        <a href="#" class="text-blue-600 hover:underline">{{ $report->reporter }}</a>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 min-w-[150px]"> {{-- Atur min width --}}
                        <p class="break-words">{{ $report->reason }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($report->reported_at)->diffForHumans() }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-1">
                        {{-- <button class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-100" title="View Report Details" onclick="viewReportDetail('comment', {{ $report->id }})">
                            <i class="ri-information-line text-lg"></i>
                        </button> --}}
                        <button class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-100" title="Approve Report (e.g., Delete Comment)" onclick="processReport('approve', 'comment', {{ $report->id }})">
                            <i class="ri-check-line text-lg"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-100" title="Reject Report" onclick="processReport('reject', 'comment', {{ $report->id }})">
                            <i class="ri-close-line text-lg"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                        No new comment reports matching your criteria.
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
    function viewReportDetail(type, id) {
        window.dispatchEvent(new CustomEvent('view-report-detail', { detail: { type: type, id: id }}));
    }
    function processReport(action, reportType, reportId) {
        window.dispatchEvent(new CustomEvent('process-report-confirm', { detail: { action: action, reportType: reportType, reportId: reportId }}));
    }
    function performConfirmedAction() {
        // Ambil komponen Alpine modal utama. Hati-hati jika ada beberapa.
        const alpineComponent = document.querySelector('[x-data^="{ openModal:"][x-data*="originalCommentPreview"]'); // Lebih spesifik
        if (!alpineComponent || !alpineComponent.__x) {
            // Jika modal utama tidak aktif atau tidak ditemukan, jangan lakukan apa-apa atau log error.
            // Ini penting agar tidak crash jika hanya modal test yang aktif.
            // console.warn('Main Alpine modal component not found for performing action.');
            return; 
        }
        const alpineData = alpineComponent.__x.$data;

        const reportType = alpineData.reportType;
        const reportId = alpineData.reportId;
        const action = alpineData.actionForConfirmation;

        if (!action || !reportType || !reportId) {
            alert('Error: Could not determine action details for confirmation from main modal.');
            if(alpineData) alpineData.openModal = false; // Tutup modal jika ada error pada data
            return;
        }
        alert(`Action confirmed for ${reportType} #${reportId}. Action: ${action}. Implement AJAX logic here.`);
        alpineData.openModal = false;
        alpineData.actionForConfirmation = ''; 
    }
</script>
@endpush
@endsection