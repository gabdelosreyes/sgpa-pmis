<div class="flex flex-wrap gap-1">
    @if($row->permissions->isEmpty())
        <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">None</span>
    @else
        @foreach($row->permissions as $permission)
            <span class="inline-flex items-center rounded-md bg-army-green-50 px-2 py-1 text-xs font-medium text-army-green-700 ring-1 ring-inset ring-army-green-600/20 capitalize">
                {{ str_replace('-', ' ', $permission->name) }}
            </span>
        @endforeach
    @endif
</div>
