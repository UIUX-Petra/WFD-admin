@extends('layouts.admin-layout')

@section('title', 'User Support (Help CS)')

@section('content')
    <div x-data="{ activeTab: 'open_tickets' }">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6">User Support (Help CS)</h1>

        <div class="mb-6 border-b border-gray-200">
            <nav class="flex flex-wrap -mb-px sm:space-x-4" aria-label="Tabs">
                <button @click="activeTab = 'open_tickets'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'open_tickets', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'open_tickets' }"
                    class="whitespace-nowrap py-3 px-2 sm:px-4 border-b-2 font-medium text-sm focus:outline-none">
                    Open Tickets <span class="ml-1 px-2 py-0.5 text-xs font-semibold text-blue-600 bg-blue-100 rounded-full">
                        {{-- Calculate count of open tickets --}}
                        @php
                            $allTickets = $tickets ?? [
                                (object) [
                                    'id' => 'TICK-001',
                                    'user_name' => 'Citra Dewi',
                                    'user_id' => 301,
                                    'subject' => 'Cannot login with Google',
                                    'status' => 'Open',
                                    'priority' => 'High',
                                    'last_update' => '2025-05-22 10:00',
                                    'created_at' => '2025-05-22 09:30',
                                    'assigned_to' => null,
                                ],
                                (object) [
                                    'id' => 'TICK-002',
                                    'user_name' => 'Rahmat Hidayat',
                                    'user_id' => 302,
                                    'subject' =>
                                        'Error when trying to post a new question - "An unexpected error occurred"',
                                    'status' => 'In Progress',
                                    'priority' => 'Medium',
                                    'last_update' => '2025-05-21 14:30',
                                    'created_at' => '2025-05-20 11:00',
                                    'assigned_to' => 'Admin Staff A',
                                ],
                                (object) [
                                    'id' => 'TICK-003',
                                    'user_name' => 'Bambang P.',
                                    'user_id' => 303,
                                    'subject' => 'Feature Suggestion: Dark Mode',
                                    'status' => 'Closed',
                                    'priority' => 'Low',
                                    'last_update' => '2025-05-19 16:00',
                                    'created_at' => '2025-05-18 08:20',
                                    'closed_at' => '2025-05-19 17:00',
                                    'assigned_to' => 'Admin Staff B',
                                ],
                                (object) [
                                    'id' => 'TICK-004',
                                    'user_name' => 'Siti Aminah',
                                    'user_id' => 304,
                                    'subject' => 'How do I change my profile picture?',
                                    'status' => 'Open',
                                    'priority' => 'Low',
                                    'last_update' => '2025-05-23 09:15',
                                    'created_at' => '2025-05-23 09:15',
                                    'assigned_to' => null,
                                ],
                                (object) [
                                    'id' => 'TICK-005',
                                    'user_name' => 'Eko Prasetyo',
                                    'user_id' => 305,
                                    'subject' => 'Reported user behavior - harassment in comments',
                                    'status' => 'In Progress',
                                    'priority' => 'High',
                                    'last_update' => '2025-05-23 11:00',
                                    'created_at' => '2025-05-22 17:00',
                                    'assigned_to' => 'Admin Staff A',
                                ],
                                (object) [
                                    'id' => 'TICK-006',
                                    'user_name' => 'Lina Marlina',
                                    'user_id' => 306,
                                    'subject' => 'Question about subject "Advanced Algorithms"',
                                    'status' => 'Open',
                                    'priority' => 'Medium',
                                    'last_update' => '2025-05-23 13:00',
                                    'created_at' => '2025-05-23 12:45',
                                    'assigned_to' => null,
                                ],
                            ];
                            echo count(array_filter($allTickets, fn($ticket) => $ticket->status === 'Open'));
                        @endphp
                    </span>
                </button>
                <button @click="activeTab = 'in_progress_tickets'"
                    :class="{ 'border-yellow-500 text-yellow-600': activeTab === 'in_progress_tickets', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'in_progress_tickets' }"
                    class="whitespace-nowrap py-3 px-2 sm:px-4 border-b-2 font-medium text-sm focus:outline-none">
                    In Progress <span
                        class="ml-1 px-2 py-0.5 text-xs font-semibold text-yellow-600 bg-yellow-100 rounded-full">
                        {{ count(array_filter($allTickets, fn($ticket) => $ticket->status === 'In Progress')) }}
                    </span>
                </button>
                <button @click="activeTab = 'closed_tickets'"
                    :class="{ 'border-green-500 text-green-600': activeTab === 'closed_tickets', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'closed_tickets' }"
                    class="whitespace-nowrap py-3 px-2 sm:px-4 border-b-2 font-medium text-sm focus:outline-none">
                    Closed Tickets
                </button>
                <button @click="activeTab = 'live_chat_placeholder'"
                    :class="{ 'border-purple-500 text-purple-600': activeTab === 'live_chat_placeholder', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'live_chat_placeholder' }"
                    class="whitespace-nowrap py-3 px-2 sm:px-4 border-b-2 font-medium text-sm focus:outline-none">
                    Live Chat (Placeholder)
                </button>
            </nav>
        </div>

        <div x-show="activeTab === 'open_tickets'" class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Open Tickets List</h2>
            @php
                $openTickets = array_filter($allTickets, fn($ticket) => $ticket->status === 'Open');
            @endphp
            @if (!empty($openTickets))
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ticket ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last
                                Update</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($openTickets as $ticket)
                            <tr class="align-top">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600 hover:underline">
                                    <a href="#">{{ $ticket->id }}</a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <a href="#" class="hover:underline">{{ $ticket->user_name }}</a>
                                    <span class="text-xs text-gray-500 block">(ID: {{ $ticket->user_id }})</span>
                                </td>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-700 max-w-sm">
                                    <p class="truncate group-hover:whitespace-normal group-hover:overflow-visible">
                                        {{ Str::limit($ticket->subject, 70) }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($ticket->priority === 'High')
                                        <span
                                            class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">High</span>
                                    @elseif($ticket->priority === 'Medium')
                                        <span
                                            class="px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">Medium</span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Low</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    title="{{ \Carbon\Carbon::parse($ticket->last_update)->format('Y-m-d H:i:s') }}">
                                    {{ \Carbon\Carbon::parse($ticket->last_update)->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-1">
                                    <button class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-100"
                                        title="View & Reply to Ticket"><i class="ri-reply-line text-lg"></i></button>
                                    <button class="text-yellow-600 hover:text-yellow-900 p-1 rounded hover:bg-yellow-100"
                                        title="Assign / Start Processing"><i class="ri-loader-2-line text-lg"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-500 text-center py-4">No open tickets at this time.</p>
            @endif
        </div>

        <div x-show="activeTab === 'in_progress_tickets'" class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto" x-cloak>
            <h2 class="text-xl font-semibold text-gray-700 mb-4">In Progress Tickets List</h2>
            @php
                $inProgressTickets = array_filter($allTickets, fn($ticket) => $ticket->status === 'In Progress');
            @endphp
            @if (!empty($inProgressTickets))
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ticket ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Assigned To</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last
                                Update</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($inProgressTickets as $ticket)
                            <tr class="align-top">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600 hover:underline">
                                    <a href="#">{{ $ticket->id }}</a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <a href="#" class="hover:underline">{{ $ticket->user_name }}</a>
                                    <span class="text-xs text-gray-500 block">(ID: {{ $ticket->user_id }})</span>
                                </td>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-700 max-w-sm">
                                    <p class="truncate group-hover:whitespace-normal group-hover:overflow-visible">
                                        {{ Str::limit($ticket->subject, 70) }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $ticket->assigned_to ?? 'Unassigned' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    title="{{ \Carbon\Carbon::parse($ticket->last_update)->format('Y-m-d H:i:s') }}">
                                    {{ \Carbon\Carbon::parse($ticket->last_update)->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-1">
                                    <button class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-100"
                                        title="View & Reply to Ticket"><i class="ri-reply-line text-lg"></i></button>
                                    <button class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-100"
                                        title="Mark as Closed"><i class="ri-check-double-line text-lg"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-500 text-center py-4">No tickets are currently in progress.</p>
            @endif
        </div>

        <div x-show="activeTab === 'closed_tickets'" class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto" x-cloak>
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Closed Tickets List</h2>
            @php
                $closedTickets = array_filter($allTickets, fn($ticket) => $ticket->status === 'Closed');
            @endphp
            @if (!empty($closedTickets))
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ticket ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Closed Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($closedTickets as $ticket)
                            <tr class="align-top">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600 hover:underline">
                                    <a href="#">{{ $ticket->id }}</a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <a href="#" class="hover:underline">{{ $ticket->user_name }}</a>
                                    <span class="text-xs text-gray-500 block">(ID: {{ $ticket->user_id }})</span>
                                </td>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-700 max-w-sm">
                                    <p class="truncate group-hover:whitespace-normal group-hover:overflow-visible">
                                        {{ Str::limit($ticket->subject, 70) }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    title="{{ \Carbon\Carbon::parse($ticket->closed_at)->format('Y-m-d H:i:s') }}">
                                    {{ \Carbon\Carbon::parse($ticket->closed_at)->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-1">
                                    <button class="text-gray-600 hover:text-gray-900 p-1 rounded hover:bg-gray-100"
                                        title="View Archived Ticket Details"><i
                                            class="ri-archive-line text-lg"></i></button>
                                    <button class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-100"
                                        title="Re-open Ticket"><i class="ri-folder-open-line text-lg"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-500 text-center py-4">No tickets have been closed yet.</p>
            @endif
            {{-- Pagination for closed tickets --}}
        </div>

        <div x-show="activeTab === 'live_chat_placeholder'" class="bg-white p-6 rounded-lg shadow-lg" x-cloak>
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Live Chat Support</h2>

            <div class="flex flex-col md:flex-row gap-6">

                <div class="md:w-1/3 border border-gray-200 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-3">Incoming Chat Queue
                        <span
                            class="ml-2 px-2 py-0.5 text-xs font-semibold text-red-600 bg-red-100 rounded-full animate-pulse">3
                            New</span>
                    </h3>
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @php
                            $chatQueue = [
                                (object) [
                                    'user_name' => 'JohnDoe123',
                                    'user_id' => 401,
                                    'preview_message' => 'I forgot my password and the reset link isn\'t working...',
                                    'waiting_time' => '2 mins',
                                    'avatar_char' => 'J',
                                ],
                                (object) [
                                    'user_name' => 'JaneSmith_Pet',
                                    'user_id' => 402,
                                    'preview_message' => 'How do I edit my profile? I can\'t find the option.',
                                    'waiting_time' => '5 mins',
                                    'avatar_char' => 'J',
                                ],
                                (object) [
                                    'user_name' => 'TechSavvyStudent',
                                    'user_id' => 403,
                                    'preview_message' => 'Experiencing slow loading times on the questions page.',
                                    'waiting_time' => '8 mins',
                                    'avatar_char' => 'T',
                                ],
                                (object) [
                                    'user_name' => 'NewbieUser',
                                    'user_id' => 404,
                                    'preview_message' => 'Hello, I need help getting started with the platform.',
                                    'waiting_time' => '12 mins',
                                    'avatar_char' => 'N',
                                ],
                            ];
                        @endphp

                        @foreach ($chatQueue as $chat)
                            <div class="p-3 bg-gray-50 rounded-md shadow-sm hover:bg-gray-100 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <span
                                            class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-500 text-white text-sm font-semibold">{{ $chat->avatar_char }}</span>
                                        <span class="font-semibold text-sm text-gray-800">{{ $chat->user_name }}</span>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $chat->waiting_time }}</span>
                                </div>
                                <p class="mt-1 text-xs text-gray-600 truncate">{{ $chat->preview_message }}</p>
                                <button
                                    class="mt-2 text-xs bg-green-500 hover:bg-green-600 text-white font-semibold py-1 px-3 rounded-md w-full sm:w-auto">
                                    <i class="ri-chat-smile-2-line mr-1"></i> Pick Up Chat
                                </button>
                            </div>
                        @endforeach
                        @if (empty($chatQueue))
                            <p class="text-sm text-gray-500 text-center py-4">No users currently waiting in the chat queue.
                            </p>
                        @endif
                    </div>
                </div>

                <div class="md:w-2/3 border border-dashed border-gray-300 rounded-lg p-4 flex flex-col">
                    <div class="flex items-center justify-between pb-3 border-b mb-3">
                        <div>
                            <h3 class="text-lg font-medium text-gray-700">Chat with: <span
                                    class="text-blue-600">JaneSmith_Pet</span></h3>
                            <p class="text-xs text-gray-500">Topic: Profile Editing Issue</p>
                        </div>
                        <button class="text-xs bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-3 rounded-md">
                            <i class="ri-close-circle-line mr-1"></i> End Chat
                        </button>
                    </div>

                    <div class="flex-grow bg-gray-100 rounded-md p-3 space-y-3 overflow-y-auto min-h-[200px] max-h-80">
                        <div class="flex justify-start">
                            <div class="bg-blue-500 text-white p-2 rounded-lg max-w-xs text-sm shadow">
                                Halo Admin, bagaimana cara mengedit profil saya? Saya tidak bisa menemukan opsinya.
                                <div class="text-xs text-blue-200 mt-1 text-right">10:35 AM</div>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <div class="bg-gray-200 text-gray-800 p-2 rounded-lg max-w-xs text-sm shadow">
                                Hai Jane! Anda bisa menemukan opsi "Edit Profil" di bawah menu pengguna Anda di pojok kanan
                                atas. Klik avatar Anda, lalu "Pengaturan Profil".
                                <div class="text-xs text-gray-500 mt-1 text-right">10:36 AM</div>
                            </div>
                        </div>
                        <div class="flex justify-start">
                            <div class="bg-blue-500 text-white p-2 rounded-lg max-w-xs text-sm shadow">
                                Oh, sudah ketemu! Terima kasih banyak!
                                <div class="text-xs text-blue-200 mt-1 text-right">10:37 AM</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 pt-3 border-t">
                        <textarea class="w-full p-2 border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500"
                            rows="2" placeholder="Type your reply here..."></textarea>
                        <div class="mt-2 flex justify-end space-x-2">
                            <button
                                class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-1.5 px-3 rounded-md">
                                <i class="ri-attachment-2 mr-1"></i> Attach File
                            </button>
                            <button
                                class="text-xs bg-blue-500 hover:bg-blue-600 text-white font-bold py-1.5 px-4 rounded-md">
                                Send Reply <i class="ri-send-plane-2-line ml-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
