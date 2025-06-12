@extends('layouts.admin-layout')

@section('title', 'Dashboard')

@section('content')
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Basic Statistics Dashboard</h1>

    {{-- Initial Basic Statistics Section --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-500 bg-opacity-20 text-blue-600">
                    <i class="ri-group-fill text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Users</p>
                    <p class="text-2xl font-semibold text-gray-800">1,250</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-500 bg-opacity-20 text-green-600">
                    <i class="ri-question-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">New Questions (This Month)</p>
                    <p class="text-2xl font-semibold text-gray-800">320</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-500 bg-opacity-20 text-red-600">
                    <i class="ri-error-warning-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Active Content Reports</p>
                    <p class="text-2xl font-semibold text-gray-800">15</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-500 bg-opacity-20 text-yellow-600">
                    <i class="ri-book-open-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Subjects</p>
                    <p class="text-2xl font-semibold text-gray-800">25</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Existing User Activity Placeholder --}}
    {{-- <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">User Activity (Chart Example)</h2>
        <div class="bg-gray-200 h-64 flex items-center justify-center rounded">
            <p class="text-gray-500">Placeholder for User Growth / Activity Graph</p>
        </div>
    </div> --}}

    {{-- Content Report Statistics Section (Merged) --}}
    <div class="mt-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Content Report Statistics</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-500 bg-opacity-20 text-red-600">
                        <i class="ri-flag-line text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Reports Received (This Month)</p>
                        <p class="text-2xl font-semibold text-gray-800">75</p> {{-- Hardcoded --}}
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-500 bg-opacity-20 text-green-600">
                        <i class="ri-shield-check-line text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Reports Processed (This Month)</p>
                        <p class="text-2xl font-semibold text-gray-800">60</p> {{-- Hardcoded --}}
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-500 bg-opacity-20 text-yellow-600">
                        <i class="ri-time-line text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending Reports</p>
                        <p class="text-2xl font-semibold text-gray-800">15</p> {{-- Hardcoded --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-8 bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Report Trends per Period</h3>
            <div class="bg-gray-200 h-64 flex items-center justify-center rounded">
                <p class="text-gray-500">Placeholder for Report Trends Graph</p>
                {{-- Chart.js or ApexCharts integration here --}}
            </div>
            <div class="mt-2 text-sm text-gray-500">Filter: <select class="border-gray-300 rounded-md"><option>This Week</option><option selected>This Month</option><option>This Year</option></select></div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Report Type Breakdown (This Month)</h3>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Number of Reports</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage of Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Common Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                    $reportStats = [
                        (object)['type' => 'Spam/Promotion', 'count' => 30, 'percentage' => '40%', 'common_action' => 'Delete Content & Warn User'],
                        (object)['type' => 'Irrelevant/Off-topic', 'count' => 20, 'percentage' => '26.7%', 'common_action' => 'Close Question/Move'],
                        (object)['type' => 'Hate Speech/Rude', 'count' => 15, 'percentage' => '20%', 'common_action' => 'Delete Content & Ban User'],
                        (object)['type' => 'Copyright Infringement', 'count' => 5, 'percentage' => '6.7%', 'common_action' => 'Delete Content'],
                        (object)['type' => 'Other', 'count' => 5, 'percentage' => '6.7%', 'common_action' => 'Further Investigation'],
                    ];
                    @endphp
                    @foreach ($reportStats as $stat)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $stat->type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $stat->count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex items-center">
                                <div class="w-2/3 bg-gray-200 rounded-full h-2.5 mr-2">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $stat->percentage }}"></div>
                                </div>
                                <span>{{ $stat->percentage }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-normal text-sm text-gray-500">{{ $stat->common_action }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Existing Online Users Section --}}
    {{-- <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Online Users</h2>
        <ul class="list-disc list-inside text-gray-600">
            <li>Budi Santoso</li>
            <li>Ani Wijaya</li>
            <li>Eko Prasetyo</li>
            <li>Siti Aminah</li>
            <li>Rudi Hartono</li>
        </ul> --}}
        {{-- <p class="mt-2 text-sm text-gray-500">This feature requires active session tracking implementation.</p> --}}
    {{-- </div> --}}
@endsection