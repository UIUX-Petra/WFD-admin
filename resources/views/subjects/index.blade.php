@extends('layouts.admin-layout')

@section('title', 'Manajemen Subjects')

@section('content')
    <div x-data="subjectManager('{{ session('token') }}')" x-init="init()">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-4xl font-black font-gotham text-transparent bg-clip-text bg-gradient-to-r from-[#5BE6B0] to-[#20BDA9]">
                 Subjects Management
            </h1>
            <button @click="openAddModal()"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow flex items-center">
                <i class="ri-add-line mr-1"></i> Add New Subject
            </button>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto border" style="border: 2px solid #b0e0e4;">
            {{-- Search --}}
            <div class="mb-4">
                <input type="text" x-model.debounce.500ms="search"
                    placeholder="Search by name, abbreviation, or description..."
                    class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    style="border-color: #b0e0e4;">
            </div>

            {{-- spinner buat loading --}}
            <div x-show="isLoading" class="flex justify-center items-center p-8">
                <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                    </circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span class="ml-3 text-gray-600">Memuat data...</span>
            </div>

            {{-- tabel data --}}
            <div class="overflow-x-auto" x-show="!isLoading">
               <table class="min-w-full border divide-y" style="border-color: #b0e0e4; --tw-divide-opacity: 1;">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Abbreviation</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Questions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-if="subjects.length === 0">
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data subject
                                    ditemukan.</td>
                            </tr>
                        </template>
                        <template x-for="subject in subjects" :key="subject.id">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                    x-text="subject.name"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700" x-text="subject.abbreviation">
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 hidden md:table-cell"
                                    x-text="subject.description ? subject.description.substring(0, 50) + (subject.description.length > 50 ? '...' : '') : '-'">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    x-text="subject.group_question_count"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <button @click="openEditModal(subject)" class="text-blue-600 hover:text-blue-900"
                                        title="Edit Subject">
                                        <i class="ri-pencil-line text-lg"></i>
                                    </button>
                                    <button @click="deleteSubject(subject.id, subject.name)"
                                        class="text-red-600 hover:text-red-900" title="Hapus Subject">
                                        <i class="ri-delete-bin-line text-lg"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div x-show="!isLoading && pagination.total > 0"
                class="mt-4 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="text-sm text-gray-700">
                    Showing <span x-text="pagination.from || 0"></span> to <span x-text="pagination.to || 0"></span> of
                    <span x-text="pagination.total || 0"></span> results
                </div>

                <nav x-show="pagination.last_page > 1">
                    <ul class="inline-flex items-center -space-x-px text-sm">
                        <li>
                            <button @click="fetchSubjects(pagination.current_page - 1)" :disabled="pagination.current_page <= 1"
                                class="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-[#b0e0e4] rounded-l-lg hover:bg-[#e0f7f9] hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                Prev
                            </button>
                        </li>

                        <template x-for="page in Array.from({ length: pagination.last_page }, (_, i) => i + 1)" :key="page">
                            <li>
                                <button @click="fetchSubjects(page)"
                                    :class="page === pagination.current_page
                                        ? 'px-3 py-2 text-[#2e304f] border border-[#b0e0e4] bg-[#e0f7f9] font-semibold'
                                        : 'px-3 py-2 text-gray-500 bg-white border border-[#b0e0e4] hover:bg-[#f0fafa] hover:text-gray-700'"
                                    class="leading-tight rounded-none">
                                    <span x-text="page"></span>
                                </button>
                            </li>
                        </template>

                        <li>
                            <button @click="fetchSubjects(pagination.current_page + 1)" :disabled="pagination.current_page >= pagination.last_page"
                                class="px-3 py-2 leading-tight text-gray-500 bg-white border border-[#b0e0e4] rounded-r-lg hover:bg-[#e0f7f9] hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                Next
                            </button>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>

        {{-- Add Subject --}}
        <div x-show="showAddModal" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4"
            x-transition>
            <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md" @click.away="showAddModal = false">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">Add New Subject</h3>
                    <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600"><i
                            class="ri-close-fill text-2xl"></i></button>
                </div>
                <form @submit.prevent="addSubject()">
                    <div class="mb-4">
                        <label for="add_name" class="block text-sm font-medium text-gray-700 mb-1">Subject Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="add_name" x-model="newSubject.name" required
                            class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="add_abbreviation"
                            class="block text-sm font-medium text-gray-700 mb-1">Abbreviation</label>
                        <input type="text" id="add_abbreviation" x-model="newSubject.abbreviation"
                            class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="add_description"
                            class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="add_description" x-model="newSubject.description" rows="3"
                            class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="showAddModal = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">Save
                            Subject</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Edit Subject --}}
        <div x-show="showEditModal" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4"
            x-transition>
            <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md" @click.away="showEditModal = false">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">Edit Subject: <span
                            x-text="editSubjectData.name"></span></h3>
                    <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600"><i
                            class="ri-close-fill text-2xl"></i></button>
                </div>
                <form @submit.prevent="updateSubject()">
                    <input type="hidden" x-model="editSubjectData.id">
                    <div class="mb-4">
                        <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">Subject Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="edit_name" x-model="editSubjectData.name" required
                            class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="edit_abbreviation"
                            class="block text-sm font-medium text-gray-700 mb-1">Abbreviation</label>
                        <input type="text" id="edit_abbreviation" x-model="editSubjectData.abbreviation"
                            class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="edit_description"
                            class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="edit_description" x-model="editSubjectData.description" rows="3"
                            class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="showEditModal = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">Update
                            Subject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // nerima param token
        function subjectManager(authToken) {
            const apiBaseUrl = "{{ env('API_URL') }}";

            return {
                // State
                subjects: [],
                pagination: {},
                isLoading: true,
                search: '',
                showAddModal: false,
                showEditModal: false,
                newSubject: {
                    name: '',
                    abbreviation: '',
                    description: ''
                },
                editSubjectData: {},
                authToken: authToken, // simpan token dalam state

                init() {
                    this.fetchSubjects();
                    this.$watch('search', () => {
                        this.fetchSubjects(1);
                    });
                },

                async fetchSubjects(page = 1) {
                    // Jangan fetch jika halaman tidak valid
                    if (page < 1 || (this.pagination.last_page && page > this.pagination.last_page)) {
                        return;
                    }

                    this.isLoading = true;
                    try {
                        const params = new URLSearchParams({
                            page: page,
                            per_page: 10,
                            search: this.search,
                        });

                        const response = await fetch(`${apiBaseUrl}/admin/subjects?${params.toString()}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Authorization': `Bearer ${this.authToken}`
                            }
                        });

                        if (response.status === 401) {
                            this.showToast('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
                            return;
                        }
                        if (!response.ok) throw new Error('Failed to fetch subjects.');

                        const result = await response.json();
                        this.subjects = result.data.data;
                        this.pagination = {
                            current_page: result.data.current_page,
                            last_page: result.data.last_page,
                            from: result.data.from,
                            to: result.data.to,
                            total: result.data.total,
                        };
                    } catch (error) {
                        console.error('Error fetching subjects:', error);
                        this.showToast('error', 'Gagal memuat data subject.');
                    } finally {
                        this.isLoading = false;
                    }
                },

                openAddModal() {
                    this.newSubject = {
                        name: '',
                        abbreviation: '',
                        description: ''
                    };
                    this.showAddModal = true;
                },

                async addSubject() {
                    try {
                        const response = await fetch(`${apiBaseUrl}/admin/subjects`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Authorization': `Bearer ${this.authToken}`
                            },
                            body: JSON.stringify(this.newSubject)
                        });

                        const result = await response.json();
                        if (!response.ok) {
                            if (response.status === 422) {
                                const errors = Object.values(result.errors).flat().join('\n');
                                this.showToast('error', errors);
                            } else {
                                throw new Error(result.message || 'Failed to add subject.');
                            }
                            return;
                        }

                        this.showToast('success', 'Subject berhasil ditambahkan!');
                        this.showAddModal = false;
                        this.fetchSubjects(this.pagination.current_page);

                    } catch (error) {
                        console.error('Error adding subject:', error);
                        this.showToast('error', 'Gagal menambahkan subject.');
                    }
                },

                openEditModal(subject) {
                    this.editSubjectData = {
                        ...subject
                    };
                    this.showEditModal = true;
                },

                async updateSubject() {
                    const id = this.editSubjectData.id;
                    try {
                        const response = await fetch(`${apiBaseUrl}/admin/subjects/${id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Authorization': `Bearer ${this.authToken}`
                            },
                            body: JSON.stringify(this.editSubjectData)
                        });

                        const result = await response.json();
                        if (!response.ok) {
                            if (response.status === 422) {
                                const errors = Object.values(result.errors).flat().join('\n');
                                this.showToast('error', errors);
                            } else {
                                throw new Error(result.message || 'Failed to update subject.');
                            }
                            return;
                        }

                        this.showToast('success', 'Subject berhasil diperbarui!');
                        this.showEditModal = false;
                        this.fetchSubjects(this.pagination.current_page);

                    } catch (error) {
                        console.error('Error updating subject:', error);
                        this.showToast('error', 'Gagal memperbarui subject.');
                    }
                },

                deleteSubject(id, name) {
                    Swal.fire({
                        title: 'Anda yakin?',
                        text: `Anda akan menghapus subject "${name}". Tindakan ini tidak dapat dibatalkan!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                const response = await fetch(`${apiBaseUrl}/admin/subjects/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute('content'),
                                        'Authorization': `Bearer ${this.authToken}`
                                    }
                                });

                                if (!response.ok) throw new Error('Failed to delete subject.');

                                this.showToast('success', `Subject "${name}" berhasil dihapus.`);
                                this.fetchSubjects(this.pagination.current_page);

                            } catch (error) {
                                console.error('Error deleting subject:', error);
                                this.showToast('error', 'Gagal menghapus subject.');
                            }
                        }
                    })
                },

                showToast(icon, title) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });

                    Toast.fire({
                        icon,
                        title
                    });
                }
            }
        }
    </script>
@endsection
