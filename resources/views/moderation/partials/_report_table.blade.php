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

{{-- resources/views/admin/moderation/partials/_report_table.blade.php --}}
<div class="mb-4 flex justify-between items-center">
    {{-- BUNGKUS DENGAN FORM --}}
    <form action="{{ route('admin.moderation.reports') }}" method="GET" class="w-full max-w-xs">
        {{-- Pertahankan parameter 'type' saat mencari --}}
        <input type="hidden" name="type" value="{{ request('type', 'question') }}">

        <div class="relative">
            <input type="text" name="search"
                class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Search reports..." value="{{ request('search') }}">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="ri-search-line text-gray-400"></i>
            </span>
        </div>
        {{-- Tombol submit bisa disembunyikan jika Anda ingin search terjadi saat menekan Enter --}}
        {{-- <button type="submit" class="hidden">Search</button> --}}
    </form>
</div>
{{-- ... Search bar ... --}}
<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">ID</th>
            <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[250px] max-w-sm">
                Preview</th>
            @if ($reportItemType === 'answer' || $reportItemType === 'comment')
                <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Related To
                </th>
            @endif
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Reporter</th>
            <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[150px]">
                Reason</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Date Reported</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse ($reports as $report)
            <tr class="align-top">
                {{-- ID Laporan --}}
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    <a href="#" class="text-blue-600 hover:underline">#{{ $report['id'] }}</a>
                </td>

                {{-- Preview Konten --}}
                <td class="px-6 py-4 text-sm text-gray-700 min-w-[250px] max-w-sm">
                    <p class="break-words">{{ Str::limit($report['preview'], 150) }}</p>
                </td>

                {{-- Related To --}}
                @if ($reportItemType === 'answer' || $reportItemType === 'comment')
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <a href="#" class="text-blue-600 hover:underline">
                            {{ $report['related_to']['type'] }} #{{ $report['related_to']['id'] }}
                        </a>
                        <p class="text-xs text-gray-400 truncate">{{ $report['related_to']['title'] }}</p>
                    </td>
                @endif

                {{-- Reporter --}}
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    <a href="#" class="text-blue-600 hover:underline">{{ $report['reporter']['name'] }}</a>
                    <span class="text-xs text-gray-500 block">(ID: {{ $report['reporter']['id'] }})</span>
                </td>

                {{-- Reason --}}
                <td class="px-6 py-4 text-sm text-gray-500 min-w-[150px]">
                    <p class="break-words">{{ $report['reason'] }}</p>
                </td>

                {{-- Date Reported --}}
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" title="{{ $report['date_reported'] }}">
                    {{ $report['date_reported'] }}
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-1">
                    {{-- Gunakan $report['id'] karena data berasal dari array JSON --}}
                    <button class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-100"
                        title="View Report Details"
                        onclick="viewReportDetail('{{ $reportItemType }}', {{ $report['id'] }})">
                        <i class="ri-information-line text-lg"></i>
                    </button>
                    <button class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-100"
                        title="Approve Report"
                        onclick="processReport('approve', '{{ $reportItemType }}', {{ $report['id'] }})">
                        <i class="ri-check-line text-lg"></i>
                    </button>
                    <button class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-100" title="Reject Report"
                        onclick="processReport('reject', '{{ $reportItemType }}', {{ $report['id'] }})">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ $reportItemType === 'answer' || $reportItemType === 'comment' ? 7 : 6 }}"
                    class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                    No new {{ $reportItemType }} reports matching your criteria.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>



