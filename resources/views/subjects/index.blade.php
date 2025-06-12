@extends('layouts.admin-layout')

@section('title', 'Manajemen Subjects')

@section('content')
<div x-data="{ showAddModal: false, showEditModal: false, subjectToEdit: null }">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Subjects Management</h1>
        <button @click="showAddModal = true" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow">
            <i class="ri-add-line mr-1"></i> Add New Subjects
        </button>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
        <div class="mb-4">
            <input type="text" placeholder="Cari subject..." class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Questions Count</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                // $subjects sudah di-hardcode di route example
                $subjects = $subjects ?? [
                    (object)['id' => 1, 'name' => 'Web Frameworks and Development', 'question_count' => 152],
                    (object)['id' => 2, 'name' => 'Artificial Intelligence and Machine Learning', 'question_count' => 88],
                    (object)['id' => 3, 'name' => 'Data Mining', 'question_count' => 210],
                ];
                @endphp
                @foreach ($subjects as $subject)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $subject->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $subject->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $subject->question_count }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button @click="subjectToEdit = {{ json_encode($subject) }}; showEditModal = true" class="text-blue-600 hover:text-blue-900" title="Edit Subject">
                            <i class="ri-pencil-line text-lg"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-900" title="Hapus Subject" onclick="confirm('Yakin ingin menghapus subject \'{{ $subject->name }}\'?')">
                            <i class="ri-delete-bin-line text-lg"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div x-show="showAddModal" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md" @click.away="showAddModal = false">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Add New Subject</h3>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600"><i class="ri-close-fill text-2xl"></i></button>
            </div>
            <form action="#" method="POST"> {{-- Ganti # dengan route action --}}
                @csrf
                <div class="mb-4">
                    <label for="add_subject_name" class="block text-sm font-medium text-gray-700 mb-1">Subject Name</label>
                    <input type="text" id="add_subject_name" name="name" required
                           class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="add_subject_description" class="block text-sm font-medium text-gray-700 mb-1">Subject Description</label>
                    <textarea id="add_subject_description" name="description" rows="3"
                              class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">Save Subject</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="showEditModal && subjectToEdit" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md" @click.away="showEditModal = false; subjectToEdit = null">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Edit Subject: <span x-text="subjectToEdit?.name"></span></h3>
                <button @click="showEditModal = false; subjectToEdit = null" class="text-gray-400 hover:text-gray-600"><i class="ri-close-fill text-2xl"></i></button>
            </div>
            <form action="#" method="POST" x-show="subjectToEdit"> {{-- Ganti # dengan route action, bind ID --}}
                @csrf
                @method('PUT')
                <input type="hidden" name="id" :value="subjectToEdit?.id">
                <div class="mb-4">
                    <label for="edit_subject_name" class="block text-sm font-medium text-gray-700 mb-1">Subject Name</label>
                    <input type="text" id="edit_subject_name" name="name" required :value="subjectToEdit?.name"
                           class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="edit_subject_description" class="block text-sm font-medium text-gray-700 mb-1">Subject Description</label>
                    <textarea id="edit_subject_description" name="description" rows="3" :value="subjectToEdit?.description"
                              class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showEditModal = false; subjectToEdit = null" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">Update Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection