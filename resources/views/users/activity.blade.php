@extends('layouts.admin-layout')

@section('title', 'User Activity: ' . $user->username)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-blue-500 hover:text-blue-700">
            <i class="ri-arrow-left-s-line mr-1 text-lg"></i>
            Back to Users Data
        </a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-lg mb-8 flex items-center space-x-6">
        <img class="h-24 w-24 rounded-full object-cover"
            src="{{ $user->image ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->username) . '&background=random&color=fff' }}"
            alt="{{ $user->username }}">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $user->username }}</h1>
            <p class="text-gray-600">{{ $user->email }}</p>
            <p class="text-sm text-gray-500 mt-1">Joined on: {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-8">
        <div class="p-4 bg-yellow-100 rounded-lg text-center">
            <p class="text-sm font-medium text-yellow-800">Reputation</p>
            <p class="text-3xl font-bold text-yellow-900 mt-1">{{ number_format($stats['reputation']) }}</p>
        </div>
        <div class="p-4 bg-blue-100 rounded-lg text-center">
            <p class="text-sm font-medium text-blue-800">Questions</p>
            <p class="text-3xl font-bold text-blue-900 mt-1">{{ number_format($stats['total_questions']) }}</p>
        </div>
        <div class="p-4 bg-green-100 rounded-lg text-center">
            <p class="text-sm font-medium text-green-800">Answers</p>
            <p class="text-3xl font-bold text-green-900 mt-1">{{ number_format($stats['total_answers']) }}</p>
        </div>
        <div class="p-4 bg-indigo-100 rounded-lg text-center">
            <p class="text-sm font-medium text-indigo-800">Comments</p>
            <p class="text-3xl font-bold text-indigo-900 mt-1">{{ number_format($stats['total_comments']) }}</p>
        </div>
        <div class="p-4 bg-red-100 rounded-lg text-center">
            <p class="text-sm font-medium text-red-800">Content Reported</p>
            <p class="text-3xl font-bold text-red-900 mt-1">{{ number_format($stats['total_reports_against_user']) }}</p>
        </div>
    </div>

    <div x-data="{ activeTab: 'questions' }" class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Recent Activities</h2>
        
        <div class="border-b border-gray-200 mb-4">
            <nav class="flex -mb-px space-x-6" aria-label="Tabs">
                <button @click="activeTab = 'questions'"
                    :class="activeTab === 'questions' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm focus:outline-none">
                    Questions
                </button>
                <button @click="activeTab = 'answers'"
                    :class="activeTab === 'answers' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm focus:outline-none">
                    Answers
                </button>
                <button @click="activeTab = 'comments'"
                    :class="activeTab === 'comments' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm focus:outline-none">
                    Comments
                </button>
            </nav>
        </div>

        <div>
            <div x-show="activeTab === 'questions'" class="space-y-4">
                @forelse ($activities['questions'] as $question)
                    <div class="p-4 border rounded-md hover:bg-gray-50">
                        <a href="#" class="font-semibold text-blue-600 hover:underline">{{ $question['title'] }}</a>
                        <p class="text-sm text-gray-500 mt-1">
                            Posted {{ \Carbon\Carbon::parse($question['created_at'])->diffForHumans() }} |
                            Votes: {{ $question['vote'] }} | Views: {{ $question['view'] }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500">This user has not asked any questions yet.</p>
                @endforelse
            </div>

            <div x-show="activeTab === 'answers'" x-cloak class="space-y-4">
                @forelse ($activities['answers'] as $answer)
                    <div class="p-4 border rounded-md hover:bg-gray-50">
                        <p class="text-gray-700">"{{ \Illuminate\Support\Str::limit($answer['answer'], 150) }}"</p>
                        <p class="text-sm text-gray-500 mt-2">
                            Answered on question: 
                            <a href="#" class="font-semibold text-blue-600 hover:underline">"{{ $answer['question']['title'] }}"</a>
                            - {{ \Carbon\Carbon::parse($answer['created_at'])->diffForHumans() }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500">This user has not provided any answers yet.</p>
                @endforelse
            </div>

            <div x-show="activeTab === 'comments'" x-cloak class="space-y-4">
                @forelse ($activities['comments'] as $comment)
                    <div class="p-4 border rounded-md hover:bg-gray-50">
                        <p class="text-gray-700 italic">"{{ $comment['comment'] }}"</p>
                        <p class="text-sm text-gray-500 mt-2">
                            Commented on {{ \Illuminate\Support\Str::of($comment['commentable_type'])->after('App\\Models\\')->lower() }}
                            - {{ \Carbon\Carbon::parse($comment['created_at'])->diffForHumans() }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500">This user has not made any comments yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection