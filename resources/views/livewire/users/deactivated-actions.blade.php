<div class="flex items-center gap-2">
    <button wire:click="$dispatch('view-user', { id: {{ $row->id }} })" class="text-xs px-2 py-1 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
        View
    </button>
    
    <button wire:click="$dispatch('reactivate-user', { id: {{ $row->id }} })" class="text-xs px-2 py-1 bg-army-green-600 text-white rounded hover:bg-army-green-700 transition-colors">
        Reactivate
    </button>
    
    <button wire:click="$dispatch('delete-user', { id: {{ $row->id }} })" class="text-xs px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
        Delete
    </button>
</div>
