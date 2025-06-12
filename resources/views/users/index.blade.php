@extends('layouts.admin-layout')

@section('title', 'User Data')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">User Registration Data</h1>
        {{-- <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow">
            <i class="ri-add-line mr-1"></i> Add New User (If Needed)
        </button> --}}
    </div>

    <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
        <div class="mb-4 flex justify-between items-center">
            <div class="relative w-full max-w-xs">
                <input type="text" class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Search users...">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="ri-search-line text-gray-400"></i>
                </span>
            </div>
            <div>
                {{-- Filters can be added here if needed (e.g., by status, registration date range) --}}
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
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registration Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                // Dummy data for users
                // In a real application, this would come from your controller/database
                $users = $users ?? [
                    (object)[
                        'id' => 1,
                        'name' => 'Alice Wonderland',
                        'email' => 'alice.wonder@example.com',
                        'registered_at' => '2025-01-15',
                        'status' => 'Active',
                        'activity_route' => route('admin.users.activity', 1) // Assuming route name
                    ],
                    (object)[
                        'id' => 2,
                        'name' => 'Bob The Builder',
                        'email' => 'bob.builder@example.com',
                        'registered_at' => '2025-02-20',
                        'status' => 'Blocked',
                        'activity_route' => route('admin.users.activity', 2)
                    ],
                    (object)[
                        'id' => 3,
                        'name' => 'Charlie Brown',
                        'email' => 'charlie.brown@example.com',
                        'registered_at' => '2025-03-10',
                        'status' => 'Active',
                        'activity_route' => route('admin.users.activity', 3)
                    ],
                    (object)[
                        'id' => 4,
                        'name' => 'Diana Prince',
                        'email' => 'diana.prince@example.com',
                        'registered_at' => '2025-04-05',
                        'status' => 'Active',
                        'activity_route' => route('admin.users.activity', 4)
                    ],
                    (object)[
                        'id' => 5,
                        'name' => 'Edward Scissorhands',
                        'email' => 'edward.hands@example.com',
                        'registered_at' => '2025-05-01',
                        'status' => 'Blocked', // Another status example
                        'activity_route' => route('admin.users.activity', 5)
                    ],
                ];
                @endphp
                @forelse ($users as $user)
                <tr class="align-top">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=fff" alt="{{ $user->name }}">
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($user->registered_at)->format('M d, Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if ($user->status === 'Active')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                        @elseif ($user->status === 'Blocked')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Blocked
                            </span>
                        @else {{-- Assuming 'Suspended' or other statuses --}}
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                {{ $user->status }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-1">
                        <a href="{{ $user->activity_route }}" class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-100" title="View Activity">
                            <i class="ri-eye-line text-lg"></i>
                        </a>
                        @if ($user->status === 'Active')
                            <button class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-100" title="Block User" onclick="confirmUserAction('block', '{{ $user->name }}', {{ $user->id }})">
                                <i class="ri-user-unfollow-line text-lg"></i>
                            </button>
                        @else {{-- For Blocked or Suspended users --}}
                            <button class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-100" title="Activate User" onclick="confirmUserAction('activate', '{{ $user->name }}', {{ $user->id }})">
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
            <nav class="flex items-center justify-between border-t border-gray-200 px-4 py-3 sm:px-6" aria-label="Pagination">
                <div class="hidden sm:block">
                  <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">1</span>
                    to
                    <span class="font-medium">{{ count($users ?? []) }}</span>
                    of
                    <span class="font-medium">{{ count($users ?? []) }}</span>
                    results
                  </p>
                </div>
                <div class="flex-1 flex justify-between sm:justify-end">
                  <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                  </a>
                  <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                  </a>
                </div>
              </nav>
        </div>
    </div>

@push('scripts')
<script>
    function confirmUserAction(action, userName, userId) { // Added userId parameter
        let message = '';
        if (action === 'block') {
            message = `Are you sure you want to block the user "${userName}" (ID: ${userId})?`;
        } else if (action === 'activate') {
            message = `Are you sure you want to activate the user "${userName}" (ID: ${userId})?`;
        }

        if (confirm(message)) {
            // Here you would add logic to send a request to the server
            // For example, using Fetch API or Axios
            alert(`Action "${action}" for user "${userName}" (ID: ${userId}) will be processed.`);
            
            // Example of how you might structure the fetch call:
            /*
            let url = `/admin/users/${userId}/${action}`; // Define your route structure
            fetch(url, {
                method: 'POST', // Or 'PUT', 'PATCH' depending on your API design
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Important for Laravel POST requests
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                // body: JSON.stringify({ any_additional_data: 'value' }) // If you need to send a body
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if(data.success) {
                    alert(data.message || 'Action successful!');
                    window.location.reload(); // Reload to see changes
                } else {
                    alert('Action failed: ' + (data.message || 'Unknown error from server.'));
                }
            })
            .catch(error => {
                console.error('Error performing user action:', error);
                let errorMessage = 'An error occurred.';
                if (error && error.message) {
                    errorMessage = error.message;
                } else if (typeof error === 'string') {
                    errorMessage = error;
                }
                alert(errorMessage);
            });
            */
        }
    }
</script>
@endpush
@endsection