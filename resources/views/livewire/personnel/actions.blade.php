<div class="flex items-center gap-2">
    @can('view personnel')
    <button wire:click="$dispatch('view-personnel', { id: {{ $row->id }} })" class="text-xs px-2 py-1 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
        View
    </button>
    @endcan
    
    @can('edit personnel')
    <button wire:click="$dispatch('edit-personnel', { id: {{ $row->id }} })" class="text-xs px-2 py-1 bg-army-green-600 text-white rounded hover:bg-army-green-700 transition-colors">
        Edit
    </button>
    @endcan
    
    @can('delete personnel')
    <button wire:click="$dispatch('delete-personnel', { id: {{ $row->id }} })" class="text-xs px-2 py-1 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition-colors">
        Deactivate
    </button>
    @endcan
</div>
