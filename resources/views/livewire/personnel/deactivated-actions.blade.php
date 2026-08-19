<div class="flex items-center gap-2">
    <button wire:click="$dispatch('view-personnel', { id: {{ $row->id }} })" class="text-xs px-2 py-1 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
        View
    </button>
    
    <button wire:click="$dispatch('activate-personnel', { id: {{ $row->id }} })" class="text-xs px-2 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition-colors">
        Activate
    </button>
    
    <button wire:click="$dispatch('permanent-delete-personnel', { id: {{ $row->id }} })" class="text-xs px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
        Delete
    </button>
</div>
