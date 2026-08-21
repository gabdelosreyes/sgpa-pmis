<div>
    @if(session()->has('success'))
        <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 flex justify-between items-center">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Active Users</h3>
        <button wire:click="$toggle('showDeactivatedModal')" class="flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-gray-700 focus:outline-none">
            Deactivated Users
        </button>
    </div>

    <div class="bg-white shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <livewire:users.users-table />
        </div>
    </div>

    <!-- View Modal -->
    @if($showViewModal && $activeUser)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm overflow-y-auto">
        <div class="fixed inset-0" wire:click="$set('showViewModal', false)"></div>
        <div class="relative w-full max-w-lg bg-white rounded-xl shadow-2xl z-10 my-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">User Profile</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4 border-b border-gray-100 pb-4">
                        <div class="col-span-2">
                            <span class="text-sm font-medium text-gray-500">Full Name</span>
                            <p class="mt-1 text-base font-semibold text-gray-900">{{ $activeUser->name }}</p>
                        </div>
                        <div class="col-span-2">
                            <span class="text-sm font-medium text-gray-500">Email Address</span>
                            <p class="mt-1 text-base font-semibold text-gray-900">{{ $activeUser->email }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Status</span>
                            <p class="mt-1">
                                @if($activeUser->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">Deactivated</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Roles</span>
                            <p class="mt-1 text-base font-semibold text-gray-900">
                                {{ $activeUser->getRoleNames()->join(', ') ?: 'No roles assigned' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="button" wire:click="$set('showViewModal', false)" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Edit Roles Modal -->
    @if($showEditRolesModal && $activeUser)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm overflow-y-auto">
        <div class="fixed inset-0" wire:click="$set('showEditRolesModal', false)"></div>
        <div class="relative w-full max-w-lg bg-white rounded-xl shadow-2xl z-10 my-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Manage User Roles</h3>
            </div>
            <div class="p-6">
                <form wire:submit="updateRoles" class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-4">Editing roles for <span class="font-semibold">{{ $activeUser->name }}</span> ({{ $activeUser->email }})</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Assign Roles</label>
                            <div class="space-y-2 bg-gray-50 p-4 rounded-md border border-gray-200 h-48 overflow-y-auto">
                                @foreach($availableRoles as $role)
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model="selectedRoles" value="{{ $role }}" id="role_{{ $role }}" class="h-4 w-4 text-army-green-600 focus:ring-army-green-500 border-gray-300 rounded">
                                        <label for="role_{{ $role }}" class="ml-2 block text-sm text-gray-900 capitalize">
                                            {{ $role }}
                                        </label>
                                    </div>
                                @endforeach
                                @if(count($availableRoles) === 0)
                                    <p class="text-sm text-gray-500">No roles available.</p>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Direct Permissions</label>
                            @if(in_array('Admin', $selectedRoles))
                                <div class="mb-2 p-2 bg-blue-50 border border-blue-200 rounded text-sm text-blue-700">
                                    Admins automatically inherit all permissions.
                                </div>
                            @endif
                            <div class="space-y-2 bg-gray-50 p-4 rounded-md border border-gray-200 h-48 overflow-y-auto">
                                @foreach($availablePermissions as $permission)
                                    @php $isAdmin = in_array('Admin', $selectedRoles); @endphp
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission }}" id="perm_{{ str_replace(' ', '_', $permission) }}" 
                                            @if($isAdmin) disabled checked @endif
                                            class="h-4 w-4 text-army-green-600 focus:ring-army-green-500 border-gray-300 rounded disabled:opacity-50 disabled:bg-gray-200">
                                        <label for="perm_{{ str_replace(' ', '_', $permission) }}" class="ml-2 block text-sm text-gray-900 capitalize @if($isAdmin) opacity-50 @endif">
                                            {{ str_replace('-', ' ', $permission) }}
                                        </label>
                                    </div>
                                @endforeach
                                @if(count($availablePermissions) === 0)
                                    <p class="text-sm text-gray-500">No permissions available.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" wire:click="$set('showEditRolesModal', false)" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-army-green-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-army-green-700 focus:outline-none">Update Roles</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Deactivated Users Modal -->
    @if($showDeactivatedModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm overflow-y-auto">
        <div class="fixed inset-0" wire:click="$set('showDeactivatedModal', false)"></div>
        <div class="relative w-full max-w-7xl bg-white rounded-xl shadow-2xl z-10 my-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Deactivated Users</h3>
            </div>
            <div class="p-6">
                <div class="w-full">
                    <livewire:users.deactivated-users-table />
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="button" wire:click="$set('showDeactivatedModal', false)" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">Close Window</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Permanent Delete Confirmation Dialog -->
    @if($showDeleteDialog)
    <div class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm overflow-y-auto">
        <div class="fixed inset-0" wire:click="$set('showDeleteDialog', false)"></div>
        <div class="relative w-full max-w-lg bg-white rounded-xl shadow-2xl z-10">
            <div class="p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Delete User</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Are you sure you want to permanently delete this user? This action cannot be undone.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                <button type="button" wire:click="$set('showDeleteDialog', false)" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">Cancel</button>
                <button type="button" wire:click="confirmPermanentDelete" class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700 focus:outline-none">Delete</button>
            </div>
        </div>
    </div>
    @endif
</div>
