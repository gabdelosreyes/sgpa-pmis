<div>
    @if(session()->has('success'))
        <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 flex justify-between items-center">
        <p class="text-gray-600">Manage all active command personnel in this module.</p>
        <div class="flex gap-2">
            @can('view personnel')
            <button wire:click="$toggle('showDeactivatedModal')" class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                Deactivated Roster
            </button>
            @endcan
            
            @can('add personnel')
            <button wire:click="openCreateModal" class="flex items-center px-4 py-2 bg-army-green-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-army-green-700 focus:outline-none">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Personnel
            </button>
            @endcan
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <livewire:personnel-table />
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm overflow-y-auto">
        <div class="fixed inset-0" wire:click="$set('showCreateModal', false)"></div>
        <div class="relative w-full max-w-lg bg-white rounded-xl shadow-2xl z-10 my-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Add New Personnel</h3>
            </div>
            <div class="p-6">
                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" wire:model="first_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-army-green-500 focus:border-army-green-500 sm:text-sm">
                        @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" wire:model="last_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-army-green-500 focus:border-army-green-500 sm:text-sm">
                        @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Age</label>
                            <input type="number" wire:model="age" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-army-green-500 focus:border-army-green-500 sm:text-sm">
                            @error('age') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gender</label>
                            <select wire:model="gender" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-army-green-500 focus:border-army-green-500 sm:text-sm">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            @error('gender') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Department / Assignment</label>
                        <input type="text" wire:model="department" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-army-green-500 focus:border-army-green-500 sm:text-sm">
                        @error('department') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date Started</label>
                        <input type="date" wire:model="date_started" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-army-green-500 focus:border-army-green-500 sm:text-sm">
                        @error('date_started') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center mt-4">
                        <input type="checkbox" wire:model="is_active" class="h-4 w-4 text-army-green-600 focus:ring-army-green-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">Active Status</label>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" wire:click="$set('showCreateModal', false)" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-army-green-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-army-green-700 focus:outline-none">Save Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Edit Modal -->
    @if($showEditModal && $activePersonnel)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm overflow-y-auto">
        <div class="fixed inset-0" wire:click="$set('showEditModal', false)"></div>
        <div class="relative w-full max-w-lg bg-white rounded-xl shadow-2xl z-10 my-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Edit Personnel</h3>
            </div>
            <div class="p-6">
                <form wire:submit="update" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" wire:model="first_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-army-green-500 focus:border-army-green-500 sm:text-sm">
                        @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" wire:model="last_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-army-green-500 focus:border-army-green-500 sm:text-sm">
                        @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Age</label>
                            <input type="number" wire:model="age" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-army-green-500 focus:border-army-green-500 sm:text-sm">
                            @error('age') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gender</label>
                            <select wire:model="gender" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-army-green-500 focus:border-army-green-500 sm:text-sm">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            @error('gender') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Department / Assignment</label>
                        <input type="text" wire:model="department" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-army-green-500 focus:border-army-green-500 sm:text-sm">
                        @error('department') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date Started</label>
                        <input type="date" wire:model="date_started" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-army-green-500 focus:border-army-green-500 sm:text-sm">
                        @error('date_started') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center mt-4">
                        <input type="checkbox" wire:model="is_active" class="h-4 w-4 text-army-green-600 focus:ring-army-green-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">Active Status</label>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" wire:click="$set('showEditModal', false)" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-army-green-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-army-green-700 focus:outline-none">Update Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- View Modal -->
    @if($showViewModal && $activePersonnel)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm overflow-y-auto">
        <div class="fixed inset-0" wire:click="$set('showViewModal', false)"></div>
        <div class="relative w-full max-w-lg bg-white rounded-xl shadow-2xl z-10 my-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Personnel Profile</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4 border-b border-gray-100 pb-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Full Name</span>
                            <p class="mt-1 text-base font-semibold text-gray-900">{{ $activePersonnel->first_name }} {{ $activePersonnel->last_name }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Status</span>
                            <p class="mt-1">
                                @if($activePersonnel->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">Deactivated</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-y-4 gap-x-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Age</span>
                            <p class="mt-1 text-base text-gray-900">{{ $activePersonnel->age }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Gender</span>
                            <p class="mt-1 text-base text-gray-900">{{ $activePersonnel->gender }}</p>
                        </div>
                        <div class="col-span-2">
                            <span class="text-sm font-medium text-gray-500">Department / Assignment</span>
                            <p class="mt-1 text-base text-gray-900">{{ $activePersonnel->department }}</p>
                        </div>
                        <div class="col-span-2">
                            <span class="text-sm font-medium text-gray-500">Date Started</span>
                            <p class="mt-1 text-base text-gray-900">{{ $activePersonnel->date_started->format('M d, Y') }}</p>
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

    <!-- Deactivated Roster Modal -->
    @if($showDeactivatedModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm overflow-y-auto">
        <div class="fixed inset-0" wire:click="$set('showDeactivatedModal', false)"></div>
        <div class="relative w-full max-w-7xl bg-white rounded-xl shadow-2xl z-10 my-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Deactivated Roster</h3>
            </div>
            <div class="p-6">
                <div class="w-full">
                    <livewire:deactivated-personnel-table />
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="button" wire:click="$set('showDeactivatedModal', false)" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">Close Roster</button>
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
                        <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Delete Record</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Are you sure you want to permanently delete this record? This action cannot be undone.</p>
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
