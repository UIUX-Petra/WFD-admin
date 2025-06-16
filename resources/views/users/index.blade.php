@extends('layouts.admin-layout')

@section('title', 'User Data')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">User Registration Data</h1>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
        <div class="mb-4 flex justify-between items-center">
            <div class="relative w-full max-w-xs">
                <input type="text"
                    class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Search users...">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="ri-search-line text-gray-400"></i>
                </span>
            </div>
            <div>
                <select class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="all">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="blocked">Blocked</option>
                </select>
            </div>
        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registration
                        Date</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($users as $user)
                    @php($user = (object) $user)
                    <tr class="align-top" id="user-row-{{ $user->id }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <img class="h-8 w-8 rounded-full"
                                        src="{{ $user->image ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->username) . '&background=random&color=fff' }}"
                                        alt="{{ $user->username }}">
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->username }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->registered_at }}</td>
                        <td class="px-6 py-4 whitespace-nowrap" id="user-status-{{ $user->id }}">
                            @if ($user->status === 'Active')
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @elseif ($user->status === 'Blocked')
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Blocked</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-1"
                            id="user-actions-{{ $user->id }}">
                            <a href="{{ route('admin.users.activity', $user->id) }}"
                                class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-100"
                                title="View Activity">
                                <i class="ri-eye-line text-lg"></i>
                            </a>
                            @if ($user->status === 'Active')
                                <button class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-100"
                                    title="Block User"
                                    onclick="confirmUserAction('block', '{{ $user->username ?? 'this user' }}', '{{ $user->id }}')">
                                    <i class="ri-user-unfollow-line text-lg"></i>
                                </button>
                            @else
                                <button class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-100"
                                    title="Activate User"
                                    onclick="confirmUserAction('unblock', '{{ $user->username ?? 'this user' }}', '{{ $user->id }}')">
                                    <i class="ri-user-follow-line text-lg"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                            No user data found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Ambil URL API dan Token dari variabel yang dikirim controller
        const API_URL = '{{ $apiUrl }}';
        const API_TOKEN = '{{ $apiToken }}';

        /**
         * Fungsi generik untuk memanggil API secara langsung.
         * Menggunakan Bearer Token untuk otentikasi.
         */
        async function callApi(endpoint, method = 'POST', body = null) {
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${API_TOKEN}` // Otentikasi langsung ke API
            };

            const options = {
                method: method.toUpperCase(),
                headers: headers
            };

            if (body) {
                options.body = JSON.stringify(body);
            }

            const response = await fetch(`${API_URL}${endpoint}`, options); // Gunakan URL API lengkap
            const responseData = await response.json();

            if (!response.ok) {
                const errorMessage = responseData.message || `An error occurred: ${response.statusText}`;
                throw new Error(errorMessage);
            }
            return responseData;
        }

        /**
         * Fungsi konfirmasi menggunakan SweetAlert (TIDAK BERUBAH)
         * Tetap seperti ini sesuai permintaan Anda.
         */
        function confirmUserAction(action, userName, userId) {
            const config = {
                block: {
                    title: 'Block User?',
                    text: `You are about to block "${userName}". They will be restricted.`,
                    confirmButtonText: 'Yes, block user!',
                    icon: 'warning'
                },
                unblock: {
                    title: 'Activate User?',
                    text: `You are about to activate "${userName}". They will regain full access.`,
                    confirmButtonText: 'Yes, activate user!',
                    icon: 'info'
                }
            };

            Swal.fire({
                title: config[action].title,
                text: config[action].text,
                icon: config[action].icon,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: config[action].confirmButtonText
            }).then((result) => {
                if (result.isConfirmed) {
                    // Panggil processAction jika dikonfirmasi
                    processAction(action, userId, userName);
                }
            });
        }

        // const API_URL = '{{ $apiUrl }}';
        // const API_TOKEN = '{{ $apiToken }}';

        async function processAction(action, userId, userName) {
            // HAPUS "/api" dari awal string endpoint ini
            const endpoint = `/admin/users/${userId}/${action}`; // <-- PERBAIKAN

            try {
                const data = await callApi(endpoint, 'POST');

                if (data.success) {
                    Toastify({
                        text: data.message,
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                    }).showToast();
                    updateUserRow(action, userId, userName);
                }
            } catch (error) {
                Toastify({
                    text: error.message,
                    duration: 4000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                }).showToast();
            }
        }

        /**
         * Fungsi untuk memperbarui tampilan baris pengguna di tabel (TIDAK BERUBAH)
         */
        function updateUserRow(originalAction, userId, userName) {
            const statusCell = document.getElementById(`user-status-${userId}`);
            const actionsCell = document.getElementById(`user-actions-${userId}`);
            const viewButtonHTML = actionsCell.querySelector('a').outerHTML;

            if (originalAction === 'block') {
                statusCell.innerHTML =
                    '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Blocked</span>';
                actionsCell.innerHTML = `
                ${viewButtonHTML}
                <button class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-100" title="Activate User" onclick="confirmUserAction('unblock', '${userName}', '${userId}')">
                    <i class="ri-user-follow-line text-lg"></i>
                </button>
            `;
            } else if (originalAction === 'unblock') {
                statusCell.innerHTML =
                    '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>';
                actionsCell.innerHTML = `
                ${viewButtonHTML}
                <button class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-100" title="Block User" onclick="confirmUserAction('block', '${userName}', '${userId}')">
                    <i class="ri-user-unfollow-line text-lg"></i>
                </button>
            `;
            }
        }
    </script>
@endpush
