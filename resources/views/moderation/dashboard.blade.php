@extends('layouts.admin-layout')

@section('title', 'Moderation Dashboard')

@section('content')
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Central Moderation Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="" class="block bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-500 bg-opacity-20 text-orange-600">
                    <i class="ri-question-mark text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">New Question Reports</p>
                    <p class="text-2xl font-semibold text-gray-800">5</p> {{-- Hardcoded --}}
                </div>
            </div>
            <p class="mt-3 text-xs text-gray-500">Review questions reported by users.</p>
        </a>

        <a href="" class="block bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-500 bg-opacity-20 text-purple-600">
                    <i class="ri-chat-3-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">New Answer Reports</p>
                    <p class="text-2xl font-semibold text-gray-800">8</p> {{-- Hardcoded --}}
                </div>
            </div>
            <p class="mt-3 text-xs text-gray-500">Review answers reported by users.</p>
        </a>

        <a href="" class="block bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-teal-500 bg-opacity-20 text-teal-600">
                    <i class="ri-message-3-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">New Comment Reports</p>
                    <p class="text-2xl font-semibold text-gray-800">2</p> {{-- Hardcoded --}}
                </div>
            </div>
            <p class="mt-3 text-xs text-gray-500">Review comments reported by users.</p>
        </a>
    </div>

    <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Quick Moderation Stats (This Week)</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Total Reports Received:</p>
                <p class="text-3xl font-bold text-gray-800">25</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Reports Handled:</p>
                <p class="text-3xl font-bold text-green-600">18</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Pending Reports:</p>
                <p class="text-3xl font-bold text-red-600">7</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Completion Rate:</p>
                <p class="text-3xl font-bold text-blue-600">72%</p>
            </div>
        </div>
        <div class="mt-4">
            {{-- The user had removed the route from this link, so I'll keep it as an empty href for now.
                 You should replace this with the correct route to your full report statistics page,
                 or remove the link if it's no longer needed because stats are on the main dashboard. --}}
            {{-- <a href="#" class="text-sm text-blue-500 hover:underline">View Full Report Statistics &rarr;</a> --}}
        </div>
    </div>

@endsection