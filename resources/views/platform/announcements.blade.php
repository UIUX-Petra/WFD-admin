@extends('layouts.admin-layout')

@section('title', 'Pengumuman Platform')

@section('content')
<div x-data="{ showModal: false, announcementToEdit: null, modalTitle: 'Add Announcement' }">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Platform Announcement</h1>
        <button @click="modalTitle = 'Add New Announcement'; announcementToEdit = null; showModal = true" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow">
            <i class="ri-add-line mr-1"></i> Add New Announcement
        </button>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posting Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                // $announcements sudah di-hardcode di route example
                $announcements = $announcements ?? [
                    (object)['id' => 1, 'title' => 'Maintenance Terjadwal Minggu Depan', 'posted_at' => '2024-05-20', 'status' => 'Aktif', 'content' => 'Akan ada maintenance pada tanggal...'],
                    (object)['id' => 2, 'title' => 'Update Kebijakan Privasi', 'posted_at' => '2024-05-15', 'status' => 'Aktif', 'content' => 'Kebijakan privasi kami telah diperbarui...'],
                    (object)['id' => 3, 'title' => 'Selamat Datang Mahasiswa Baru!', 'posted_at' => '2024-04-01', 'status' => 'Kadaluarsa', 'content' => 'Selamat datang di platform Q&A Informatika!'],
                ];
                @endphp
                @foreach ($announcements as $announcement)
                <tr>
                    <td class="px-6 py-4 whitespace-normal text-sm font-medium text-gray-900 max-w-md">{{ $announcement->title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $announcement->posted_at }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if ($announcement->status === 'Aktif')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button @click="modalTitle = 'Annoucement Edit'; announcementToEdit = {{ json_encode($announcement) }}; showModal = true" class="text-blue-600 hover:text-blue-900" title="Edit Pengumuman">
                            <i class="ri-pencil-line text-lg"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-900" title="Hapus Pengumuman" onclick="confirm('Yakin ingin menghapus pengumuman \'{{ $announcement->title }}\'?')">
                            <i class="ri-delete-bin-line text-lg"></i>
                        </button>
                        @if ($announcement->status === 'Aktif')
                        <button class="text-yellow-600 hover:text-yellow-900" title="Nonaktifkan"><i class="ri-pause-circle-line text-lg"></i></button>
                        @else
                        <button class="text-green-600 hover:text-green-900" title="Aktifkan Kembali"><i class="ri-play-circle-line text-lg"></i></button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div x-show="showModal" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg" @click.away="showModal = false; announcementToEdit = null">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800" x-text="modalTitle"></h3>
                <button @click="showModal = false; announcementToEdit = null" class="text-gray-400 hover:text-gray-600"><i class="ri-close-fill text-2xl"></i></button>
            </div>
            <form action="#" method="POST"> {{-- Ganti # dengan route action --}}
                @csrf
                <input type="hidden" name="id" :value="announcementToEdit?.id"> {{-- Untuk edit --}}
                <div x-show="announcementToEdit" class="hidden">@method('PUT')</div> {{-- Untuk edit --}}

                <div class="mb-4">
                    <label for="ann_title" class="block text-sm font-medium text-gray-700 mb-1">Announcement Title</label>
                    <input type="text" id="ann_title" name="title" required :value="announcementToEdit?.title"
                           class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="ann_content" class="block text-sm font-medium text-gray-700 mb-1">Announcement Content</label>
                    <textarea id="ann_content" name="content" rows="5" required :value="announcementToEdit?.content"
                              class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    {{-- Pertimbangkan menggunakan Rich Text Editor di sini seperti Trix atau CKEditor --}}
                </div>
                <div class="mb-4">
                    <label for="ann_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="ann_status" name="status" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="aktif" :selected="announcementToEdit?.status === 'Aktif' || !announcementToEdit">Aktif</option>
                        <option value="kadaluarsa" :selected="announcementToEdit?.status === 'Kadaluarsa'">Kadaluarsa/Arsip</option>
                    </select>
                </div>
                 <div class="mb-4">
                    <label for="ann_target" class="block text-sm font-medium text-gray-700 mb-1">User Target</label>
                    <select id="ann_target" name="target_users" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="all" selected>All Users</option>
                        <option value="students_only">Only Ltudents</option>
                        <option value="lecturers_only">Only Lecturers</option>
                    </select>
                </div>
                 <div class="mb-4">
                    <label for="ann_expiry_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" id="ann_expiry_date" name="expiry_date" :value="announcementToEdit?.expiry_date"
                           class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showModal = false; announcementToEdit = null" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md" x-text="announcementToEdit ? 'Update Announcement' : 'Publish Announcement'"></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection