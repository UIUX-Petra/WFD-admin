@extends('layouts.admin-layout')

@section('title', 'Log Moderasi')

@section('content')
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Moderation Log</h1>

    <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
        <div class="mb-4 flex justify-between items-center">
            <div class="relative w-full max-w-md">
                <input type="text" class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Search Log (ex. admin name, content ID)...">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="ri-search-line text-gray-400"></i>
                </span>
            </div>
            <div>
                <input type="date" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                {{-- Bisa ditambahkan filter rentang tanggal atau jenis aksi --}}
            </div>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date and Time</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason/Note</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                // $logs sudah di-hardcode di route example
                $logs = $logs ?? [
                    (object)['date' => '2024-05-22 10:00', 'admin_name' => 'Admin Utama', 'action' => 'Menghapus pertanyaan', 'target' => 'Pertanyaan #123 (Spam)', 'reason' => 'Konten spam promosi'],
                    (object)['date' => '2024-05-22 11:00', 'admin_name' => 'Admin Moderator', 'action' => 'Memblokir Pengguna', 'target' => 'User Ani Wijaya (ID: 2)', 'reason' => 'Pelanggaran berulang terhadap panduan komunitas'],
                    (object)['date' => '2024-05-21 15:30', 'admin_name' => 'Admin Utama', 'action' => 'Menutup Pertanyaan', 'target' => 'Pertanyaan #102 (Sudah terjawab)', 'reason' => 'Diskusi telah selesai'],
                ];
                @endphp
                @forelse ($logs as $log)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->date }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $log->admin_name }}</td>
                    <td class="px-6 py-4 whitespace-normal text-sm text-gray-700">{{ $log->action }}</td>
                    <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">{{ $log->target }}</td>
                    <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">{{ $log->reason }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                        Tidak ada log moderasi ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{-- Pagination --}}
    </div>
@endsection