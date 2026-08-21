<?php

namespace App\Livewire\RolesPermissions;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

class PermissionsTable extends PowerGridComponent
{
    public string $tableName = 'permissions-table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Permission::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('created_at_formatted', fn (Permission $model) => $model->created_at ? $model->created_at->format('d/m/Y') : '');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable()
                ->hidden(),

            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Created', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::action('Action')
        ];
    }

    public function actions(Permission $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit')
                ->class('text-xs px-2 py-1 bg-army-green-600 text-white rounded hover:bg-army-green-700 transition-colors')
                ->dispatch('edit-permission', ['id' => $row->id]),
                
            Button::add('delete')
                ->slot('Delete')
                ->class('text-xs px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition-colors')
                ->dispatch('delete-permission', ['id' => $row->id]),
        ];
    }
}
