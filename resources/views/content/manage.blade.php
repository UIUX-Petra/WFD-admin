@extends('layouts.admin-layout')

@section('title', 'Direct Content Management')

@section('content')
<div x-data="{ activeTab: 'questions' }">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Content Management</h1>

    <div class="mb-6 border-b border-gray-200">
        <nav class="flex space-x-4" aria-label="Tabs">
            <button @click="activeTab = 'questions'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'questions', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'questions' }"
                    class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm focus:outline-none">
                Questions
            </button>
            <button @click="activeTab = 'answers'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'answers', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'answers' }"
                    class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm focus:outline-none">
                Answers
            </button>
            <button @click="activeTab = 'comments'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'comments', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'comments' }"
                    class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm focus:outline-none">
                Comments
            </button>
        </nav>
    </div>

    <div x-show="activeTab === 'questions'" class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Question List</h2>
        <div class="mb-4">
            <input type="text" placeholder="Search questions..." class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                    {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th> --}}
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delete</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                $questions = $questions ?? [ // Fallback dummy data if not passed from controller
                    (object)['id' => 101, 'title' => 'How to set up dual boot Windows and Linux?', 'author' => 'UserX', 'status' => 'Open'],
                    (object)['id' => 102, 'title' => 'IDE recommendations for Python development', 'author' => 'UserY', 'status' => 'Closed'],
                    (object)['id' => 103, 'title' => 'Error "undefined variable" in PHP', 'author' => 'UserZ', 'status' => 'Open'],
                    (object)['id' => 104, 'title' => 'Best practices for REST API design', 'author' => 'DevGuru', 'status' => 'Open'],
                    (object)['id' => 105, 'title' => 'Understanding async/await in JavaScript', 'author' => 'JSNewbie', 'status' => 'Open'],
                ];
                @endphp
                @forelse ($questions as $question)
                <tr class="align-top">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <a href="#" class="text-blue-600 hover:underline">#{{ $question->id }}</a>
                    </td>
                    <td class="px-6 py-4 whitespace-normal text-sm text-gray-700 max-w-md">{{ $question->title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        <a href="#" class="text-blue-600 hover:underline">{{ $question->author }}</a>
                    </td>
                    {{-- <td class="px-6 py-4 whitespace-nowrap">
                        @if ($question->status === 'Open')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Open</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Closed</span>
                        @endif
                    </td> --}}
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-1">
                        {{-- <button class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-100" title="Edit Question (Placeholder)" onclick="alert('Edit Question #{{$question->id}}')"><i class="ri-pencil-line text-lg"></i></button> --}}
                        <button class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-100" title="Delete Question" onclick="confirm('Are you sure you want to delete question #{{$question->id}}: \'{{ addslashes($question->title) }}\'?') && alert('Deleting Question #{{$question->id}}')"><i class="ri-delete-bin-line text-lg"></i></button>
                        {{-- @if ($question->status === 'Open')
                        <button class="text-yellow-600 hover:text-yellow-900 p-1 rounded hover:bg-yellow-100" title="Close Question (Placeholder)" onclick="alert('Close Question #{{$question->id}}')"><i class="ri-lock-line text-lg"></i></button>
                        @else
                        <button class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-100" title="Re-open Question (Placeholder)" onclick="alert('Re-open Question #{{$question->id}}')"><i class="ri-lock-unlock-line text-lg"></i></button>
                        @endif --}}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No questions found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-6">
             <nav class="flex items-center justify-between border-t border-gray-200 px-4 py-3 sm:px-6" aria-label="Pagination">
                <div class="hidden sm:block">
                  <p class="text-sm text-gray-700">
                    Showing <span class="font-medium">1</span> to <span class="font-medium">{{ count($questions) }}</span> of <span class="font-medium">{{ count($questions) }}</span> results
                  </p>
                </div>
                <div class="flex-1 flex justify-between sm:justify-end">
                  <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Previous</a>
                  <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Next</a>
                </div>
              </nav>
        </div>
    </div>

    <div x-show="activeTab === 'answers'" class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto" x-cloak>
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Answer List</h2>
        <div class="mb-4">
            <input type="text" placeholder="Search answers..." class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Answer Preview</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">For Question ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delete</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                $answers = $answers ?? [ // Fallback dummy data
                    (object)['id' => 201, 'preview' => 'You can use GRUB Customizer for that purpose, it makes it easy to manage boot entries.', 'question_id' => 101, 'author' => 'AdminA'],
                    (object)['id' => 202, 'preview' => 'VS Code is an excellent choice with many Python extensions. PyCharm is also very popular, especially the Community Edition.', 'question_id' => 102, 'author' => 'UserB'],
                    (object)['id' => 203, 'preview' => 'Make sure error reporting is enabled in your php.ini and check for typos in the variable name.', 'question_id' => 103, 'author' => 'DevHelper'],
                ];
                @endphp
                @forelse ($answers as $answer)
                <tr class="align-top">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $answer->id }}</td>
                    <td class="px-6 py-4 whitespace-normal text-sm text-gray-700 max-w-md">{{ $answer->preview }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><a href="#" class="text-blue-600 hover:underline">#{{ $answer->question_id }}</a></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><a href="#" class="text-blue-600 hover:underline">{{ $answer->author }}</a></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-1">
                        {{-- <button class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-100" title="Edit Answer (Placeholder)" onclick="alert('Edit Answer #{{$answer->id}}')"><i class="ri-pencil-line text-lg"></i></button> --}}
                        <button class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-100" title="Delete Answer" onclick="confirm('Are you sure you want to delete answer #{{$answer->id}}?') && alert('Deleting Answer #{{$answer->id}}')"><i class="ri-delete-bin-line text-lg"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No answers found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
         <div class="mt-6">
             <nav class="flex items-center justify-between border-t border-gray-200 px-4 py-3 sm:px-6" aria-label="Pagination">
                {{-- ... pagination elements ... --}}
             </nav>
        </div>
    </div>

    <div x-show="activeTab === 'comments'" class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto" x-cloak>
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Comment List</h2>
         <div class="mb-4">
            <input type="text" placeholder="Search comments..." class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comment Preview</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">On Content (ID)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delete</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                $comments = $comments ?? [ // Fallback dummy data
                    (object)['id' => 301, 'preview' => 'Thank you, this was very helpful!', 'content_id_display' => 'Answer #201', 'content_link' => '#', 'author' => 'UserX', 'author_link' => '#'],
                    (object)['id' => 302, 'preview' => 'Have you tried this on the latest version? It might behave differently.', 'content_id_display' => 'Question #103', 'content_link' => '#', 'author' => 'UserC', 'author_link' => '#'],
                    (object)['id' => 303, 'preview' => 'Great point, I hadn\'t considered that aspect of the problem before. Well articulated!', 'content_id_display' => 'Answer #202', 'content_link' => '#', 'author' => 'DevGuru', 'author_link' => '#'],
                ];
                @endphp
                @forelse ($comments as $comment)
                <tr class="align-top">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $comment->id }}</td>
                    <td class="px-6 py-4 whitespace-normal text-sm text-gray-700 max-w-md">{{ $comment->preview }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><a href="{{$comment->content_link}}" class="text-blue-600 hover:underline">{{ $comment->content_id_display }}</a></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><a href="{{$comment->author_link}}" class="text-blue-600 hover:underline">{{ $comment->author }}</a></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-1">
                        {{-- <button class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-100" title="Edit Comment (Placeholder)" onclick="alert('Edit Comment #{{$comment->id}}')"><i class="ri-pencil-line text-lg"></i></button> --}}
                        <button class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-100" title="Delete Comment" onclick="confirm('Are you sure you want to delete comment #{{$comment->id}}?') && alert('Deleting Comment #{{$comment->id}}')"><i class="ri-delete-bin-line text-lg"></i></button>
                    </td>
                </tr>
                 @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No comments found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
         <div class="mt-6">
             <nav class="flex items-center justify-between border-t border-gray-200 px-4 py-3 sm:px-6" aria-label="Pagination">
                {{-- ... pagination elements ... --}}
             </nav>
        </div>
    </div>
</div>
@endsection