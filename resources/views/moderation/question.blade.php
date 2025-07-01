@extends('layouts.admin-layout')

@section('title', 'Manajemen Pertanyaan')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<div x-data="questionManager('{{ session('token') }}')" x-init="init()">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Questions Management</h1>
    </div>

    <!-- Notifikasi -->
    <div x-show="notification.show" x-cloak
         class="p-4 mb-4 rounded-md"
         :class="{ 'bg-green-100 border-l-4 border-green-500 text-green-700': notification.type === 'success', 'bg-red-100 border-l-4 border-red-500 text-red-700': notification.type === 'error' }"
         x-transition x-text="notification.message">
    </div>

    <!-- Loading Spinner -->
    <div x-show="isLoading" class="flex justify-center items-center p-10">
        <svg class="animate-spin -ml-1 mr-3 h-10 w-10 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-lg text-gray-600">Memuat data...</span>
    </div>

    <!-- Tabel data -->
    <div x-show="!isLoading" class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asked by</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statistic</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created at</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <template x-if="questions.length === 0">
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data pertanyaan.</td>
                    </tr>
                </template>
                <template x-for="question in questions" :key="question.id">
                    <tr :class="{ 'bg-gray-100': question.deleted_at }">
                        <td class="px-6 py-4 whitespace-normal text-sm font-medium text-gray-900 max-w-sm">
                            <a href="#" class="hover:text-blue-600" x-text="question.title.substring(0, 70) + (question.title.length > 70 ? '...' : '')"></a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="question.user ? question.user.username : 'User Dihapus'"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex items-center space-x-3">
                                <span><i class="ri-thumb-up-line" title="Votes"></i> <span x-text="question.vote"></span></span>
                                <span><i class="ri-eye-line" title="Views"></i> <span x-text="question.view"></span></span>
                                <span :class="{ 'text-red-500 font-bold': question.report > 0 }"><i class="ri-flag-line" title="Reports"></i> <span x-text="question.report"></span></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="formatDate(question.created_at)"></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <template x-if="question.deleted_at">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Dihapus</span>
                            </template>
                            <template x-if="!question.deleted_at">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            </template>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <template x-if="!question.deleted_at">
                                <div>
                                    {{-- <button @click="openEditModal(question)" class="text-blue-600 hover:text-blue-900" title="Edit Pertanyaan"><i class="ri-pencil-line text-lg"></i></button> --}}
                                    <button @click="openDeleteModal(question)" class="text-red-600 hover:text-red-900" title="Hapus Pertanyaan"><i class="ri-delete-bin-line text-lg"></i></button>
                                </div>
                            </template>
                            <template x-if="question.deleted_at">
                                <div>
                                    <button @click="restoreQuestion(question.id)" class="text-green-600 hover:text-green-900" title="Pulihkan Pertanyaan"><i class="ri-arrow-go-back-line text-lg"></i></button>
                                    <button @click="openDeleteModal(question)" class="text-red-600 hover:text-red-900" title="Hapus Permanen"><i class="ri-delete-bin-2-fill text-lg"></i></button>
                                </div>
                            </template>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Modal editt -->
    {{-- <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-2xl" @click.away="showEditModal = false">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Edit Pertanyaan</h3>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600"><i class="ri-close-fill text-2xl"></i></button>
            </div>
            <form @submit.prevent="updateQuestion" id="editForm">
                <div class="mb-4">
                    <label for="q_title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" id="q_title" name="title" required x-model="questionToEdit.title" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="q_question" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="q_question" name="question" rows="6" required x-model="questionToEdit.question" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="mb-4">
                    <label for="q_image" class="block text-sm font-medium text-gray-700 mb-1">Image (Optional)</label>
                    <div x-show="imageUrl" class="my-2">
                        <img :src="imageUrl" alt="Current Image" class="rounded-lg shadow-md max-w-xs max-h-48 object-contain">
                    </div>
                    <input type="file" id="q_image" name="image" @change="previewImage" class="w-full p-2 border border-gray-300 rounded-md file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">Update Pertanyaan</button>
                </div>
            </form>
        </div>
    </div> --}}

    <!-- Modal delete confirmation -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md" @click.away="showDeleteModal = false">
            <div class="flex flex-col items-center text-center">
                <div class="bg-red-100 p-3 rounded-full mb-4"><i class="ri-error-warning-line text-red-600 text-4xl"></i></div>
                {{-- <h3 class="text-xl font-semibold text-gray-800 mb-2">Anda Yakin?</h3> --}}
                <p class="text-gray-500 mb-1" x-text="questionToDelete?.deleted_at ? 'This will be deleted permanently!' : 'This data will be moved to archive!'"></p>
                {{-- <p class="text-gray-600 font-bold mb-4 truncate" x-text="`Judul: ${questionToDelete?.title}`"></p> --}}
                
                <div class="w-full mt-4 flex flex-col-reverse sm:flex-row sm:justify-center gap-3">
                    <button @click="showDeleteModal = false" type="button" class="w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:w-auto">
                        Batal
                    </button>
                    <button @click="deleteQuestion()" type="button" class="w-full inline-flex justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function questionManager(token) {
        return {
            // --- STATE ---
            authToken: token,
            questions: [],
            isLoading: true,
            showEditModal: false,
            showDeleteModal: false,
            questionToEdit: {},
            questionToDelete: null,
            imageUrl: '',
            notification: { show: false, message: '', type: 'success' },
            
            // --- KONFIGURASI ---
            API_BASE_URL: '{{ env("API_BASE_URL", "http://localhost:8001/api") }}',
            IMAGE_BASE_URL: '{{ env("IMAGE_BASE_URL", "http://localhost:8001/storage") }}',

            // --- METHODS ---
            init() {
                if (!this.authToken) {
                    console.error('Authentication token is missing!');
                    this.showNotification('Session is not valid, please login again!', 'error');
                    this.isLoading = false;
                    return;
                }
                this.fetchQuestions();
            },

            getAuthHeaders() {
                return {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${this.authToken}`
                };
            },

            async fetchQuestions() {
                this.isLoading = true;
                try {
                    const response = await fetch(`${this.API_BASE_URL}/admin/questions`, {
                        headers: this.getAuthHeaders()
                    });
                    if (response.status === 401) throw new Error('Access rejected. Session probably has ended.');
                    if (!response.ok) throw new Error('Fail retrieving data from the server.');
                    
                    const data = await response.json();
                    this.questions = data.data;
                } catch (error) {
                    this.showNotification(error.message, 'error');
                } finally {
                    this.isLoading = false;
                }
            },

            openEditModal(question) {
                this.questionToEdit = JSON.parse(JSON.stringify(question));
                this.imageUrl = question.image ? `${this.IMAGE_BASE_URL}/${question.image}` : '';
                this.showEditModal = true;
            },

            openDeleteModal(question) {
                this.questionToDelete = question;
                this.showDeleteModal = true;
            },

            previewImage(event) {
                if (event.target.files && event.target.files[0]) {
                    this.imageUrl = URL.createObjectURL(event.target.files[0]);
                }
            },

            // async updateQuestion() {
            //     const form = document.getElementById('editForm');
            //     const formData = new FormData(form);
            //     formData.append('_method', 'PUT');

            //     try {
            //         const response = await fetch(`${this.API_BASE_URL}/admin/questions/${this.questionToEdit.id}`, {
            //             method: 'POST',
            //             body: formData,
            //             headers: this.getAuthHeaders()
            //         });

            //         const result = await response.json();
            //         if (!response.ok) throw new Error(result.message || 'Gagal memperbarui data.');
                    
            //         this.showNotification('Pertanyaan berhasil diperbarui.', 'success');
            //         this.showEditModal = false;
            //         this.fetchQuestions();
            //     } catch (error) {
            //         this.showNotification(error.message, 'error');
            //     }
            // },

            async deleteQuestion() {
                const isForceDelete = !!this.questionToDelete.deleted_at;
                const url = isForceDelete 
                    ? `${this.API_BASE_URL}/admin/questions/force-delete/${this.questionToDelete.id}`
                    : `${this.API_BASE_URL}/admin/questions/${this.questionToDelete.id}`;
                
                try {
                    const response = await fetch(url, {
                        method: 'DELETE',
                        headers: this.getAuthHeaders()
                    });

                    const result = await response.json();
                    if (!response.ok) throw new Error(result.message || 'Fail sending the data!');

                    this.showNotification(result.message, 'success');
                    this.showDeleteModal = false;
                    this.fetchQuestions();
                } catch (error) {
                    this.showNotification(error.message, 'error');
                }
            },

            async restoreQuestion(id) {
                try {
                    const response = await fetch(`${this.API_BASE_URL}/admin/questions/${id}/restore`, {
                        method: 'PUT',
                        headers: this.getAuthHeaders()
                    });

                    const result = await response.json();
                    if (!response.ok) throw new Error(result.message || 'Fail recovering the data!');
                    
                    this.showNotification(result.message, 'success');
                    this.fetchQuestions();
                } catch (error) {
                    this.showNotification(error.message, 'error');
                }
            },

            formatDate(dateString) {
                if (!dateString) return '';
                const options = { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' };
                return new Date(dateString).toLocaleDateString('id-ID', options);
            },

            showNotification(message, type = 'success') {
                this.notification.message = message;
                this.notification.type = type;
                this.notification.show = true;
                setTimeout(() => { this.notification.show = false; }, 4000);
            }
        }
    }
</script>
@endpush
