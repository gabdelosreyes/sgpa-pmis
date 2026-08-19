<?php

namespace App\Livewire;

use App\Models\Personnel;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;

class PersonnelTable extends PowerGridComponent
{
    public string $tableName = 'personnel-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            PowerGrid::header()
                ->showSearchInput()
                ->showToggleColumns(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Personnel::query()->where('is_active', true);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('first_name')
            ->add('last_name')
            ->add('department')
            ->add('age')
            ->add('gender')
            ->add('is_active', fn (Personnel $model) => 'Active')
            ->add('date_started_formatted', fn (Personnel $model) => $model->date_started ? $model->date_started->format('d/m/Y') : '');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable()
                ->searchable()
                ->hidden(),

            Column::make('First Name', 'first_name')
                ->sortable()
                ->searchable(),

            Column::make('Last Name', 'last_name')
                ->sortable()
                ->searchable(),

            Column::make('Department', 'department')
                ->sortable()
                ->searchable(),

            Column::make('Age', 'age')
                ->sortable()
                ->searchable(),

            Column::make('Gender', 'gender')
                ->sortable()
                ->searchable(),

            Column::make('Status', 'is_active')
                ->sortable()
                ->searchable(),

            Column::make('Date Started', 'date_started_formatted', 'date_started')
                ->sortable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('first_name'),
            Filter::inputText('last_name'),
            Filter::inputText('department'),
        ];
    }

    public function actionsFromView($row): \Illuminate\Contracts\View\View
    {
        return view('livewire.personnel.actions', ['row' => $row]);
    }
}
