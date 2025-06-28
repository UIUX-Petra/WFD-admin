@extends('layouts.admin-layout')

@section('title', 'Announcement Management')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-4xl font-black font-gotham text-transparent bg-clip-text bg-gradient-to-r from-[#5BE6B0] to-[#20BDA9]">
        Announcement Management
    </h1>
    <a href="{{ route('admin.announcements.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow">
        <i class="ri-add-line mr-1"></i> Add New Announcement
    </a>
</div>

@if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <p>{{ session('success') }}</p>
    </div>
@endif
@if (session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p>{{ session('error') }}</p>
    </div>
@endif

<div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto border" style="border: 2px solid #b0e0e4;">
     <table class="min-w-full border divide-y" style="border-color: #b0e0e4; --tw-divide-opacity: 1;">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Web Display</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Publish Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notification Sent</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($announcements as $announcement)
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 max-w-sm">{{ $announcement['title'] }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize 
                            {{ $announcement['status'] === 'published' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $announcement['status'] === 'draft' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $announcement['status'] === 'archived' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ $announcement['status'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                         <i class="{{ $announcement['display_on_web'] ? 'ri-checkbox-circle-line text-green-500' : 'ri-close-circle-line text-red-500' }} text-xl"></i>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $announcement['published_at'] ? \Carbon\Carbon::parse($announcement['published_at'])->format('d M Y, H:i') : '—' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $announcement['notified_at'] ? \Carbon\Carbon::parse($announcement['notified_at'])->format('d M Y, H:i') : '—' }}</td>
                    <td class="px-6 py-4 text-sm font-medium space-x-2">
                        <a href="{{ route('admin.announcements.edit', $announcement['id']) }}" class="text-blue-600 hover:text-blue-900" title="Edit"><i class="ri-pencil-line text-lg"></i></a>
                        
                        <button type="button" class="text-red-600 hover:text-red-900 delete-btn" 
                                data-url="{{ route('admin.announcements.destroy', $announcement['id']) }}"
                                data-token="{{ csrf_token() }}"
                                title="Delete">
                            <i class="ri-delete-bin-line text-lg"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-gray-500">No announcements found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const url = this.dataset.url;
            const csrfToken = this.dataset.token;

            Swal.fire({
                title: 'Are you sure?',
                text: "This announcement will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Deleted!',
                                data.message,
                                'success'
                            ).then(() => {
                                // Reload the page to see the changes
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Failed!',
                                data.message || 'An error occurred while deleting.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        Swal.fire(
                            'Error!',
                            'Could not connect to the server.',
                            'error'
                        );
                    });
                }
            });
        });
    });
});
</script>
@endpush
