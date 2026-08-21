<div>
    @if(session()->has('success'))
        <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif
    @if(session()->has('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-medium leading-6 text-gray-900">System Roles</h3>
            <p class="text-sm text-gray-600">Manage user roles and assign predefined system permissions.</p>
        </div>
        @can('add roles')
        <button wire:click="openRoleModal" class="px-4 py-2 bg-army-green-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-army-green-700 focus:outline-none">
            Create New Role
        </button>
        @endcan
    </div>

    <div class="space-y-6">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <livewire:roles-permissions.roles-table />
        </div>
    </div>

    <!-- Role Form Modal -->
    @if($showRoleModal)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm overflow-y-auto">
        <div class="fixed inset-0" wire:click="$set('showRoleModal', false)"></div>
        <div class="relative w-full max-w-lg bg-white rounded-xl shadow-2xl z-10 my-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">{{ $roleId ? 'Edit Role' : 'Create New Role' }}</h3>
            </div>
            <div class="p-6">
                <form wire:submit="saveRole" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Role Name</label>
                        <input type="text" wire:model="roleName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-army-green-500 focus:border-army-green-500 sm:text-sm">
                        @error('roleName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Assign Permissions</label>
                        @if($roleName === 'Admin')
                            <div class="mb-2 p-2 bg-blue-50 border border-blue-200 rounded text-sm text-blue-700">
                                Administrator roles automatically have all permissions. They cannot be modified.
                            </div>
                        @endif
                        <div class="max-h-60 overflow-y-auto bg-gray-50 p-4 rounded-md border border-gray-200 space-y-2">
                            @foreach($allPermissions as $permission)
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="rolePermissions" value="{{ $permission }}" id="perm_{{ str_replace(' ', '_', $permission) }}" 
                                        @if($roleName === 'Admin') disabled checked @endif
                                        class="h-4 w-4 text-army-green-600 focus:ring-army-green-500 border-gray-300 rounded disabled:opacity-50 disabled:bg-gray-200">
                                    <label for="perm_{{ str_replace(' ', '_', $permission) }}" class="ml-2 block text-sm text-gray-900 capitalize @if($roleName === 'Admin') opacity-50 @endif">
                                        {{ str_replace('-', ' ', $permission) }}
                                    </label>
                                </div>
                            @endforeach
                            @if(count($allPermissions) === 0)
                                <p class="text-sm text-gray-500">No permissions available in the system.</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" wire:click="$set('showRoleModal', false)" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-army-green-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-army-green-700 focus:outline-none">Save Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Role Modal -->
    @if($showDeleteRoleModal)
    <div class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm overflow-y-auto">
        <div class="fixed inset-0" wire:click="$set('showDeleteRoleModal', false)"></div>
        <div class="relative w-full max-w-lg bg-white rounded-xl shadow-2xl z-10">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900">Delete Role</h3>
                <p class="mt-2 text-sm text-gray-500">Are you sure you want to delete this role? This action cannot be undone.</p>
            </div>
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                <button type="button" wire:click="$set('showDeleteRoleModal', false)" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">Cancel</button>
                <button type="button" wire:click="confirmDeleteRole" class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700 focus:outline-none">Delete Role</button>
            </div>
        </div>
    </div>
    @endif
</div>
