<div class="flex items-center gap-2">
    <button wire:click="$dispatch('view-user', { id: {{ $row->id }} })" class="text-xs px-2 py-1 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
        View
    </button>
    
    <button wire:click="$dispatch('edit-user', { id: {{ $row->id }} })" class="text-xs px-2 py-1 bg-army-green-600 text-white rounded hover:bg-army-green-700 transition-colors">
        Edit Roles
    </button>
    
    <button wire:click="$dispatch('deactivate-user', { id: {{ $row->id }} })" class="text-xs px-2 py-1 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition-colors">
        Deactivate
    </button>
</div>
