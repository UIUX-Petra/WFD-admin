@extends('layouts.admin-layout')

@section('title', 'Role Management')

@section('content')
<div x-data="{ 
    showAddModal: false, 
    showEditModal: false, 
    roleToEdit: { id: null, name: '', permissions_description: '' } 
}">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Role Management</h1>
        <button @click="showAddModal = true; roleToEdit = { name: '', permissions_description: '' };" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-150 ease-in-out">
            <i class="ri-add-line mr-1"></i> Add New Role
        </button>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
        <div class="mb-4">
            <input type="text" placeholder="Search roles..." class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[300px]">Permissions / Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                $roles = $roles ?? [
                    (object)['id' => 1, 'name' => 'Superadmin', 'permissions_description' => 'All Access. Can manage all aspects of the platform including users, roles, content, and settings.', 'user_count' => 1],
                    // (object)['id' => 2, 'name' => 'Editor', 'permissions_description' => 'Can manage questions, answers, and comments directly (edit, delete). Cannot manage users, roles, or site-wide settings.', 'user_count' => 3],
                    // (object)['id' => 3, 'name' => 'Moderator', 'permissions_description' => 'Can review and act on reported content. Can close/reopen questions. Limited direct content editing. Cannot manage users or settings.', 'user_count' => 2],
                    // (object)['id' => 4, 'name' => 'Support Agent', 'permissions_description' => 'Can manage user support tickets and respond to user queries via Help CS. Cannot manage content or platform settings.', 'user_count' => 1],
                ];
                @endphp
                @forelse ($roles as $role)
                <tr class="align-top">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $role->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">{{ $role->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600 min-w-[300px]">
                        <p class="break-words">{{ Str::limit($role->permissions_description, 150) }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $role->user_count }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-1">
                        <button @click="roleToEdit = {{ json_encode($role) }}; showEditModal = true" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-100" title="Edit Role">
                            <i class="ri-pencil-line text-lg"></i>
                        </button>
                        @if($role->name !== 'Superadmin')
                        <button class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-100" title="Delete Role" onclick="confirmDeleteRole({{ $role->id }}, '{{ addslashes($role->name) }}')">
                            <i class="ri-delete-bin-line text-lg"></i>
                        </button>
                        @else
                         <button class="text-gray-400 p-1 rounded cursor-not-allowed" title="Cannot delete Superadmin role" disabled>
                            <i class="ri-delete-bin-line text-lg"></i>
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No roles found. Click "Add New Role" to create one.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{-- Pagination placeholder if needed --}}
    </div>

    <div x-show="showAddModal" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg transform transition-all" @click.away="showAddModal = false">
            <div class="flex justify-between items-center mb-6 pb-3 border-b">
                <h3 class="text-xl font-semibold text-gray-800">Add New Role</h3>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600"><i class="ri-close-fill text-2xl"></i></button>
            </div>
            <form action="#" method="POST" id="addRoleForm"> 
                @csrf
                <div class="mb-4">
                    <label for="add_role_name" class="block text-sm font-medium text-gray-700 mb-1">Role Name <span class="text-red-500">*</span></label>
                    <input type="text" id="add_role_name" name="name" x-model="roleToEdit.name" required
                           class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-6">
                    <label for="add_permissions_description" class="block text-sm font-medium text-gray-700 mb-1">Permissions / Description</label>
                    <textarea id="add_permissions_description" name="permissions_description" rows="4" x-model="roleToEdit.permissions_description"
                              class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Describe the permissions for this role (e.g., 'Can manage all content', 'Limited to viewing reports')."></textarea>
                    <p class="mt-1 text-xs text-gray-500">This description helps other admins understand the role's purpose.</p>
                </div>
                <div class="flex justify-end space-x-3 pt-3 border-t">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md shadow-sm">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow-sm">Save Role</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="showEditModal && roleToEdit?.id" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg transform transition-all" @click.away="showEditModal = false; roleToEdit = null">
            <div class="flex justify-between items-center mb-6 pb-3 border-b">
                <h3 class="text-xl font-semibold text-gray-800">Edit Role: <span x-text="roleToEdit?.name" class="text-blue-600"></span></h3>
                <button @click="showEditModal = false; roleToEdit = null" class="text-gray-400 hover:text-gray-600"><i class="ri-close-fill text-2xl"></i></button>
            </div>
            <form action="#" method="POST" id="editRoleForm" x-show="roleToEdit?.id">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" :value="roleToEdit?.id">
                <div class="mb-4">
                    <label for="edit_role_name" class="block text-sm font-medium text-gray-700 mb-1">Role Name <span class="text-red-500">*</span></label>
                    <input type="text" id="edit_role_name" name="name" required x-model="roleToEdit.name"
                           class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-6">
                    <label for="edit_permissions_description" class="block text-sm font-medium text-gray-700 mb-1">Permissions / Description</label>
                    <textarea id="edit_permissions_description" name="permissions_description" rows="4" x-model="roleToEdit.permissions_description"
                              class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Describe the permissions for this role."></textarea>
                </div>
                <div class="flex justify-end space-x-3 pt-3 border-t">
                    <button type="button" @click="showEditModal = false; roleToEdit = null" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md shadow-sm">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow-sm">Update Role</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function confirmDeleteRole(roleId, roleName) {
        if (confirm(`Are you sure you want to delete the role "${roleName}" (ID: ${roleId})? This action cannot be undone and might affect users assigned to this role.`)) {
            let form = document.createElement('form');
            // Use a placeholder action or your specific backend endpoint if not using Laravel routes
            form.action = `/admin/roles/delete/${roleId}`; // Example placeholder URL
            form.method = 'POST';

            let csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}'; 
            form.appendChild(csrfToken);

            let methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            document.body.appendChild(form);
            // alert(`Form for deleting role ${roleId} would be submitted here to ${form.action}`);
            // form.submit(); // Uncomment this to actually submit the form
            console.log(`Simulating delete for role ID: ${roleId}, Name: ${roleName} via action ${form.action}`);
            alert(`Simulating delete for role "${roleName}". Implement actual form submission for role ID: ${roleId}.`);
        }
    }
</script>
@endpush