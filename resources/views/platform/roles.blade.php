@extends('layouts.admin-layout')

@section('title', 'Role Management')

@push('styles')
{{-- SweetAlert2 untuk konfirmasi hapus yang lebih baik --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Remixicon untuk ikon --}}
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
<style>
    /* Style untuk transisi Alpine.js */
    [x-cloak] { display: none !important; }
    .swal2-container { z-index: 9999 !important; }
    .super-admin-row {
        background-color: #fef3c7; /* yellow-100 */
        border-left: 4px solid #f59e0b; /* amber-500 */
    }
    .super-admin-row:hover {
        background-color: #fde68a; /* brighter yellow */
    }
    .badge-protected {
        font-size: 0.75rem;
        background-color: #facc15; /* yellow-400 */
        color: #92400e; /* yellow-900 */
        padding: 2px 6px;
        border-radius: 4px;
        margin-left: 4px;
    }
</style>
@endpush


@section('content')
<div 
    x-data="roleManager()" 
    x-init="init"
    class="text-gray-800"
>
    <div x-show="notification.show" x-cloak
         class="fixed top-5 right-5 z-[100] p-4 rounded-lg shadow-lg text-white"
         :class="{ 'bg-green-500': notification.type === 'success', 'bg-red-500': notification.type === 'error' }"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-[-20px]"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-[-20px]"
         x-text="notification.message"
         @click="notification.show = false">
    </div>

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold">Role Management</h1>
        <button @click="openAddModal" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-150 ease-in-out flex items-center">
            <i class="ri-add-line mr-2"></i> Add New Role
        </button>
    </div>

    {{-- Tabel --}}
    <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
        <div class="mb-4">
            <input type="text" x-model="searchQuery" placeholder="Search roles by name or description..." class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div x-show="isLoading" class="text-center py-10">
            <p class="text-gray-500">Loading data...</p>
        </div>

        <div x-show="!isLoading">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Admins</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="role in roles" :key="role.id">
                        <tr class="hover:bg-gray-50 transition-colors duration-150 align-top" x-bind:class="{ 'super-admin-row': role.slug === 'super-admin' }">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-semibold text-gray-800 flex items-center">
                                    <span x-text="role.name"></span>
                                    <template x-if="role.slug === 'super-admin'">
                                        <i class="ri-shield-star-line text-yellow-500 ml-2"></i>
                                    </template>
                                </p>
                                <div class="flex items-center text-xs text-gray-500">
                                    <span x-text="role.slug"></span>
                                    <template x-if="role.slug === 'super-admin'">
                                        <span 
                                            class="badge-protected ml-2 relative group cursor-help"
                                            x-data="{ show: false }"
                                            @mouseenter="show = true" 
                                            @mouseleave="show = false"
                                        >
                                            Protected
                                            <div 
                                                x-show="show" 
                                                x-cloak
                                                class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 bg-yellow-500 text-white text-xs rounded py-1 px-2 shadow-lg z-10"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 translate-y-1"
                                                x-transition:enter-end="opacity-100 translate-y-0"
                                                x-transition:leave="transition ease-in duration-150"
                                                x-transition:leave-start="opacity-100 translate-y-0"
                                                x-transition:leave-end="opacity-0 translate-y-1"
                                            >
                                                This role is protected and cannot be deleted.
                                            </div>
                                        </span>
                                    </template>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 min-w-[300px]" x-text="role.description ? (role.description.length > 100 ? role.description.substring(0, 100) + '...' : role.description) : '-'"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center" x-text="role.admins_count"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center space-x-1">
                                <button @click="openPersonnelModal(role)" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-100" title="Add Personnel">
                                    <i class="ri-user-add-line text-lg"></i>
                                </button>
                                <button @click="openEditModal(role)" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-100" title="Edit Role">
                                    <i class="ri-pencil-line text-lg"></i>
                                </button>
                                <template x-if="role.slug !== 'super-admin'">
                                    <button @click="confirmDelete(role)" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-100" title="Delete Role">
                                        <i class="ri-delete-bin-line text-lg"></i>
                                    </button>
                                </template>
                                <template x-if="role.slug === 'super-admin'">
                                    <button class="text-gray-400 p-1 rounded cursor-not-allowed" title="Cannot delete Superadmin role" disabled>
                                        <i class="ri-lock-line text-lg"></i>
                                    </button>
                                </template>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="!isLoading && roles.length === 0">
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                            No roles found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

   {{-- Tambah/Edit Role --}}
   <div x-show="isModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
       <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg transform transition-all" @click.away="closeModal">
           <div class="flex justify-between items-center mb-6 pb-3 border-b">
               <h3 class="text-xl font-semibold" x-text="modal.isEditMode ? 'Edit Role' : 'Add New Role'"></h3>
               <button @click="closeModal" class="text-gray-400 hover:text-gray-600"><i class="ri-close-fill text-2xl"></i></button>
           </div>
           <form @submit.prevent="saveRole">
               <div class="space-y-4">
                   <div>
                       <label for="role_name" class="block text-sm font-medium text-gray-700 mb-1">Role Name <span class="text-red-500">*</span></label>
                       <input type="text" id="role_name" x-model="modal.name" @input="modal.slug = $event.target.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '')" required class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" :disabled="modal.isEditMode && modal.slug === 'super-admin'">
                   </div>
                   <div>
                       <label for="role_slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                       <input type="text" id="role_slug" x-model="modal.slug" readonly class="w-full p-2 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed">
                   </div>
                   <div>
                        <label for="role_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                       <textarea id="role_description" x-model="modal.description" rows="3" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                   </div>
               </div>
               <div class="flex justify-end space-x-3 pt-4 border-t mt-6">
                   <button type="button" @click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md shadow-sm">Cancel</button>
                   <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow-sm" x-text="modal.isEditMode ? 'Update Role' : 'Save Role'"></button>
               </div>
           </form>
       </div>
   </div>
  
   {{-- Add admin --}}
   <div x-show="isPersonnelModalOpen" x-cloak class="fixed inset-0 z-[60] overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
       <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-xl transform transition-all" @click.away="closePersonnelModal">
           <div class="flex justify-between items-center mb-4 pb-3 border-b">
               <div>
                   <h3 class="text-xl font-semibold">Manage Personnel</h3>
                   <p class="text-sm text-blue-600 font-medium" x-text="`Role: ${roleToManagePersonnel?.name}`"></p>
               </div>
               <button @click="closePersonnelModal" class="text-gray-400 hover:text-gray-600"><i class="ri-close-fill text-2xl"></i></button>
           </div>
           <div class="mb-4">
               <input type="text" x-model="personnelSearchQuery" placeholder="Search admin by name or email..." class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
           </div>
           <div class="max-h-80 overflow-y-auto border rounded-md p-2 space-y-2">
               <div x-show="isPersonnelLoading" class="text-center py-4">Loading admins...</div>
               <template x-for="admin in filteredAdmins" :key="admin.id">
                   <label class="flex items-center p-2 rounded-md hover:bg-gray-100 cursor-pointer">
                       <input type="checkbox" :value="admin.id" x-model="assignedAdminIds" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                       <span class="ml-3 text-sm font-medium text-gray-700" x-text="admin.name"></span>
                       <span class="ml-auto text-xs text-gray-500" x-text="admin.email"></span>
                   </label>
               </template>
                <div x-show="!isPersonnelLoading && filteredAdmins.length === 0" class="text-center text-gray-500 py-4">
                   No admins found.
               </div>
           </div>
           <div class="flex justify-end space-x-3 pt-4 border-t mt-4">
               <button type="button" @click="closePersonnelModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md shadow-sm">Cancel</button>
               <button @click="savePersonnelChanges" type="button" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md shadow-sm">Save Changes</button>
           </div>
       </div>
   </div>
</div>
@endsection


@push('scripts')
<script>
function roleManager() {
   return {
       roles: [],
       allAdmins: [],
       isLoading: true,
       isPersonnelLoading: false,
       isModalOpen: false,
       isPersonnelModalOpen: false,
       searchQuery: '',
       personnelSearchQuery: '',
       notification: { show: false, message: '', type: 'success' },
       modal: { id: null, name: '', slug: '', description: '', isEditMode: false },
       roleToManagePersonnel: null,
       assignedAdminIds: [],
       debounceTimer: null,


       API_URL: "{{ env('API_URL') }}/admin/roles",
       ADMINS_API_URL: "{{ env('API_URL') }}/admin/admins",


       get apiHeaders() {
           const headers = { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' };
           const token = '{{ session('token') }}';
           if (token) headers['Authorization'] = `Bearer ${token}`;
           return headers;
       },
       get filteredAdmins() {
           if (!this.personnelSearchQuery) return this.allAdmins;
           return this.allAdmins.filter(admin =>
               admin.name.toLowerCase().includes(this.personnelSearchQuery.toLowerCase()) ||
               admin.email.toLowerCase().includes(this.personnelSearchQuery.toLowerCase())
           );
       },


       async init() {
           await this.fetchRoles();
           await this.fetchAllAdmins();


           this.$watch('searchQuery', () => {
               this.isLoading = true;
               if (this.debounceTimer) clearTimeout(this.debounceTimer);
               this.debounceTimer = setTimeout(() => {
                   this.fetchRoles();
               }, 350);
           });
       },
       async fetchRoles() {
           try {
               const url = new URL(this.API_URL);
               if (this.searchQuery) {
                   url.searchParams.append('search', this.searchQuery);
               }
              
               const response = await fetch(url.toString(), { headers: this.apiHeaders });
              
               if (!response.ok) throw new Error('Failed to fetch roles.');
               this.roles = await response.json();
           } catch (error) {
               this.showNotification(error.message, 'error');
           } finally {
               this.isLoading = false;
           }
       },
       async fetchAllAdmins() {
           try {
               const response = await fetch(this.ADMINS_API_URL, { headers: this.apiHeaders });
               if (!response.ok) throw new Error('Failed to fetch admins.');
               this.allAdmins = await response.json();
           } catch (error) {
               this.showNotification(error.message, 'error');
           }
       },
      
       openAddModal() {
           this.modal = { id: null, name: '', slug: '', description: '', isEditMode: false };
           this.isModalOpen = true;
       },
       openEditModal(role) {
           this.modal = { ...role, isEditMode: true };
           this.isModalOpen = true;
       },
       closeModal() { this.isModalOpen = false; },
       async saveRole() {
           const url = this.modal.isEditMode ? `${this.API_URL}/${this.modal.id}` : this.API_URL;
           const method = this.modal.isEditMode ? 'PUT' : 'POST';
           try {
               const response = await fetch(url, {
                   method: method,
                   headers: { ...this.apiHeaders, 'Content-Type': 'application/json' },
                   body: JSON.stringify({ name: this.modal.name, description: this.modal.description })
               });
               const data = await response.json();
               if (!response.ok) throw new Error(data.message || 'Failed to save role.');
              
               await this.fetchRoles();
               this.showNotification(`Role ${this.modal.isEditMode ? 'updated' : 'created'} successfully!`, 'success');
               this.closeModal();
           } catch (error) {
               this.showNotification(error.message, 'error');
           }
       },


       async openPersonnelModal(role) {
           this.roleToManagePersonnel = role;
           this.personnelSearchQuery = '';
           this.isPersonnelLoading = true;
           this.isPersonnelModalOpen = true;
           try {
               const response = await fetch(`${this.API_URL}/${role.id}/admins`, { headers: this.apiHeaders });
               if (!response.ok) throw new Error('Failed to fetch assigned personnel.');
               const assignedAdmins = await response.json();
               this.assignedAdminIds = assignedAdmins.map(admin => admin.id);
           } catch (error) {
               this.showNotification(error.message, 'error');
               this.closePersonnelModal();
           } finally {
               this.isPersonnelLoading = false;
           }
       },
       closePersonnelModal() {
           this.isPersonnelModalOpen = false;
           this.roleToManagePersonnel = null;
           this.assignedAdminIds = [];
       },
       async savePersonnelChanges() {
           if (!this.roleToManagePersonnel) return;
           try {
               const response = await fetch(`${this.API_URL}/${this.roleToManagePersonnel.id}/sync-admins`, {
                   method: 'POST',
                   headers: { ...this.apiHeaders, 'Content-Type': 'application/json' },
                   body: JSON.stringify({ admin_ids: this.assignedAdminIds })
               });
                const data = await response.json();
               if (!response.ok) throw new Error(data.message || 'Failed to update personnel.');
              
               this.showNotification('Personnel updated successfully!', 'success');
               this.closePersonnelModal();
               await this.fetchRoles();
           } catch (error) {
               this.showNotification(error.message, 'error');
           }
       },


       confirmDelete(role) {
           Swal.fire({
               title: 'Are you sure?',
               html: `You are about to delete the role "<b>${role.name}</b>".<br>This action cannot be undone.`,
               icon: 'warning',
               showCancelButton: true,
               confirmButtonColor: '#d33',
               cancelButtonColor: '#3085d6',
               confirmButtonText: 'Yes, delete it!'
           }).then((result) => {
               if (result.isConfirmed) this.deleteRole(role.id);
           });
       },
       async deleteRole(id) {
           try {
               const response = await fetch(`${this.API_URL}/${id}`, { method: 'DELETE', headers: this.apiHeaders });
               const data = await response.json();
               if (!response.ok) throw new Error(data.message || 'Failed to delete role.');
               this.roles = this.roles.filter(r => r.id !== id);
               this.showNotification('Role deleted successfully!', 'success');
           } catch (error) {
               this.showNotification(error.message, 'error');
           }
       },
      
       showNotification(message, type = 'success') {
           this.notification.message = message;
           this.notification.type = type;
           this.notification.show = true;
           setTimeout(() => { this.notification.show = false; }, 3000);
       }
   }
}
</script>
@endpush



