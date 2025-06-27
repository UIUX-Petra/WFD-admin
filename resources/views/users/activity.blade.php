@extends('layouts.admin-layout')

@section('title', 'User Activity: ' . $user->username)
@section('style')
    <style>
        .modal {
            transition: opacity 0.25s ease;
        }

        .modal-content {
            transition: transform 0.25s ease;
        }
    </style>
@endsection
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
            <p class="text-sm text-gray-500 mt-1">Joined on: {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}
            </p>
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
                    :class="activeTab === 'questions' ? 'border-blue-500 text-blue-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm focus:outline-none">
                    Questions
                </button>
                <button @click="activeTab = 'answers'"
                    :class="activeTab === 'answers' ? 'border-blue-500 text-blue-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm focus:outline-none">
                    Answers
                </button>
                <button @click="activeTab = 'comments'"
                    :class="activeTab === 'comments' ? 'border-blue-500 text-blue-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm focus:outline-none">
                    Comments
                </button>
            </nav>
        </div>

        <div>
            <div x-show="activeTab === 'questions'" class="space-y-4">
                @forelse ($activities['questions'] as $question)
                    <div class="p-4 border rounded-md hover:bg-gray-50">
                        {{-- UBAH <a> MENJADI <button> --}}
                        <button data-type="question" data-id="{{ $question['id'] }}"
                            class="view-activity-btn text-left w-full font-semibold text-blue-600 hover:underline">
                            {{ $question['title'] }}
                        </button>
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
                    {{-- BUAT SELURUH DIV BISA DIKLIK --}}
                    <div data-type="answer" data-id="{{ $answer['id'] }}"
                        class="view-activity-btn p-4 border rounded-md hover:bg-gray-50 cursor-pointer">
                        <p class="text-gray-700">"{{ \Illuminate\Support\Str::limit($answer['answer'], 150) }}"</p>
                        <p class="text-sm text-gray-500 mt-2">
                            Answered on question:
                            <span class="font-semibold text-blue-600">"{{ $answer['question']['title'] }}"</span>
                            - {{ \Carbon\Carbon::parse($answer['created_at'])->diffForHumans() }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500">This user has not provided any answers yet.</p>
                @endforelse
            </div>

            <div x-show="activeTab === 'comments'" x-cloak class="space-y-4">
                @forelse ($activities['comments'] as $comment)
                    {{-- BUAT SELURUH DIV BISA DIKLIK --}}
                    <div data-type="comment" data-id="{{ $comment['id'] }}"
                        class="view-activity-btn p-4 border rounded-md hover:bg-gray-50 cursor-pointer">
                        <p class="text-gray-700 italic">"{{ $comment['comment'] }}"</p>
                        <p class="text-sm text-gray-500 mt-2">
                            Commented on
                            {{ \Illuminate\Support\Str::of($comment['commentable_type'])->after('App\\Models\\')->lower() }}
                            - {{ \Carbon\Carbon::parse($comment['created_at'])->diffForHumans() }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500">This user has not made any comments yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div id="activity-modal"
        class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 hidden">
        <div
            class="modal-content bg-gray-100 rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col transform scale-95">
            <div class="flex-shrink-0 flex justify-between items-center p-4 border-b">
                <h3 id="modal-title" class="text-xl font-semibold text-gray-800">Activity Detail</h3>
                <button @click="closeModal()" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
            </div>
            <div id="modal-body" class="flex-grow overflow-y-auto p-6">
                <p>Loading...</p>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Definisi Modal
            const modal = document.getElementById('activity-modal');
            const modalContent = modal.querySelector('.modal-content');
            const modalTitle = document.getElementById('modal-title');
            const modalBody = document.getElementById('modal-body');

            // Fungsi untuk membuka modal
            window.openModal = () => {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden'); // Mencegah scroll di belakang modal
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    modalContent.classList.remove('scale-95');
                }, 10);
            }

            // Fungsi untuk menutup modal
            window.closeModal = () => {
                modal.classList.add('opacity-0');
                modalContent.classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    modalBody.innerHTML = '<p>Loading...</p>'; // Reset body modal
                }, 250);
            }

            // Event listener untuk tombol close di modal
            const closeButton = modal.querySelector('button');
            closeButton.addEventListener('click', closeModal);

            modal.addEventListener('click', (event) => {
                // Cek apakah target klik adalah latar belakang modal itu sendiri,
                // BUKAN konten modal (modal-content).
                if (event.target === modal) {
                    closeModal();
                }
            });
            // Event listener untuk klik pada item aktivitas
            document.body.addEventListener('click', function(event) {
                const triggerButton = event.target.closest('.view-activity-btn');
                if (triggerButton) {
                    const type = triggerButton.dataset.type;
                    const id = triggerButton.dataset.id;

                    openModal();
                    fetchActivityDetail(type, id);
                }
            });

            // Fungsi untuk mengambil data dari API
            async function fetchActivityDetail(type, id) {
                const apiUrl = `{{ env('API_URL') }}/admin/content-detail/${type}/${id}`;

                try {
                    const response = await fetch(apiUrl, {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer {{ session('token') }}`
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Failed to fetch activity details.');
                    }

                    const result = await response.json();
                    if (result.success) {
                        renderModalContent(result.data); // result.data berisi objek pertanyaan
                    } else {
                        throw new Error(result.message || 'Could not retrieve data.');
                    }

                } catch (error) {
                    modalBody.innerHTML = `<p class="text-red-500">${error.message}</p>`;
                }
            }

            // Fungsi untuk merender konten ke dalam modal
            // Ganti seluruh fungsi ini
            function renderModalContent(question) {
                modalTitle.textContent = `Detail: ${question.title}`;

                let answersHtml = '';
                // PERBAIKAN DI SINI: ganti .answers menjadi .answer
                if (question.answer && question.answer.length > 0) {
                    // PERBAIKAN DI SINI: ganti .answers menjadi .answer
                    question.answer.forEach(answer => {
                        let commentsHtml = '';
                        if (answer.comment && answer.comment.length > 0) {
                            commentsHtml = '<div class="mt-3 pl-4 border-l-2 border-gray-200 space-y-2">';
                            answer.comment.forEach(comment => {
                                commentsHtml += `
                        <div class="text-sm">
                            <p class="text-gray-700">${comment.comment}</p>
                            <p class="text-xs text-gray-500">- ${comment.user.username}</p>
                        </div>
                    `;
                            });
                            commentsHtml += '</div>';
                        }

                        answersHtml += `
                <div class="p-4 bg-green-50 border border-green-200 rounded-md mt-4">
                    <p class="text-gray-800 whitespace-pre-wrap">${answer.answer}</p>
                    <p class="text-sm text-gray-600 mt-2 font-semibold">- Answer by ${answer.user.username}</p>
                    ${commentsHtml}
                </div>
            `;
                    });
                }

                let questionCommentsHtml = '';
                if (question.comment && question.comment.length > 0) {
                    questionCommentsHtml = '<div class="mt-3 pl-4 border-l-2 border-gray-200 space-y-2">';
                    question.comment.forEach(comment => {
                        questionCommentsHtml += `
                <div class="text-sm">
                    <p class="text-gray-700">${comment.comment}</p>
                    <p class="text-xs text-gray-500">- ${comment.user.username}</p>
                </div>
            `;
                    });
                    questionCommentsHtml += '</div>';
                }

                const fullHtml = `
        <div class="p-4 bg-blue-50 border border-blue-200 rounded-md">
            <h4 class="text-xl font-bold text-gray-800">${question.title}</h4>
            <p class="mt-2 text-gray-700 whitespace-pre-wrap">${question.question}</p>
            <p class="text-sm text-gray-600 mt-4 font-semibold">- Question by ${question.user.username}</p>
            ${questionCommentsHtml}
        </div>
        <div class="mt-4">
            {{-- PERBAIKAN DI SINI: ganti .answers menjadi .answer --}}
            <h5 class="text-lg font-semibold text-gray-700">Answers (${question.answer.length})</h5>
            ${answersHtml || '<p class="text-gray-500 mt-2">No answers yet.</p>'}
        </div>
    `;

                modalBody.innerHTML = fullHtml;
            }
        });
    </script>
@endpush
