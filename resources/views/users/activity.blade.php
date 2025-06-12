@extends('layouts.admin-layout')

@section('title', 'Aktivitas Pengguna: ' . ($user->name ?? 'Tidak Ditemukan'))

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-blue-500 hover:text-blue-700">
            <i class="ri-arrow-left-s-line mr-1 text-lg"></i>
            Back to Users Data
        </a>
    </div>

    <h1 class="text-3xl font-semibold text-gray-800 mb-2">Aktivitas Pengguna</h1>
    <p class="text-lg text-gray-600 mb-6">Details for: <span class="font-semibold">{{ $user->name ?? 'Alice Wonderland' }}</span> ({{ $user->email ?? 'alice.wonder@example.com' }})</p>

    {{-- <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Log Aktivitas</h2>
        @if (!empty($activities))
        <ul class="divide-y divide-gray-200">
            @foreach ($activities as $activity)
            <li class="py-4">
                <div class="flex space-x-3">
                    <div class="flex-1 space-y-1">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-medium text-gray-800">{{ $activity->action }}</h3>
                            <p class="text-sm text-gray-500">{{ $activity->date }}</p>
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
        @else
        <p class="text-sm text-gray-500">Tidak ada aktivitas tercatat untuk pengguna ini.</p>
        @endif
    </div> --}}

    <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Contribution Summary</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-blue-50 rounded-lg">
                <p class="text-sm font-medium text-blue-700">Total Questions</p>
                <p class="text-2xl font-semibold text-blue-800">15</p> {{-- Hardcoded --}}
            </div>
            <div class="p-4 bg-green-50 rounded-lg">
                <p class="text-sm font-medium text-green-700">Total Answers</p>
                <p class="text-2xl font-semibold text-green-800">42</p> {{-- Hardcoded --}}
            </div>
            <div class="p-4 bg-yellow-50 rounded-lg">
                <p class="text-sm font-medium text-yellow-700">Total Reputations</p>
                <p class="text-2xl font-semibold text-yellow-800">1250</p> {{-- Hardcoded --}}
            </div>
        </div>
    </div>
@endsection