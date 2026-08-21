<?php

namespace App\Livewire\ActivityLogs;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

class ActivityLogsTable extends PowerGridComponent
{
    public string $tableName = 'activity-logs-table';

    public function setUp(): array
    {
        return [
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
        return Activity::query()->with('causer')->latest();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('log_name')
            ->add('description')
            ->add('subject_type', fn (Activity $model) => $model->subject_type ? class_basename($model->subject_type) : '-')
            ->add('causer_id', fn (Activity $model) => $model->causer ? $model->causer->name : 'System')
            ->add('formatted_properties', function (Activity $model) {
                // 1. Resolve Subject Name
                $subjectModel = $model->subject;
                $subjectName = '';
                
                if ($subjectModel) {
                    if (isset($subjectModel->first_name) && isset($subjectModel->last_name)) {
                        $subjectName = $subjectModel->first_name . ' ' . $subjectModel->last_name;
                    } elseif (isset($subjectModel->name)) {
                        $subjectName = $subjectModel->name;
                    }
                }
                
                $subjectType = $model->subject_type ? class_basename($model->subject_type) : 'record';
                $subjectText = $subjectName ? "{$subjectType}: {$subjectName}" : $subjectType;

                // 2. Parse Properties
                $props = $model->properties;
                if (is_string($props)) {
                    $props = json_decode($props, true) ?? [];
                }
                if ($props instanceof \Illuminate\Support\Collection) {
                    $props = $props->toArray();
                }

                $action = strtolower($model->description);
                $attributes = $props['attributes'] ?? [];
                if (empty($attributes) && !isset($props['old'])) {
                    // Sometimes Spatie doesn't wrap in 'attributes'
                    $attributes = $props;
                }

                // 3. Format Message
                if ($action === 'created') {
                    return "Added new {$subjectText}";
                }
                
                if ($action === 'deleted') {
                    return "Deleted {$subjectText}";
                }

                if ($action === 'updated') {
                    if (array_key_exists('is_active', $attributes)) {
                        $status = (bool) $attributes['is_active'];
                        return $status ? "Activated {$subjectText}" : "Deactivated {$subjectText}";
                    }
                    
                    if (!empty($attributes)) {
                        // Exclude fields that are internal or unreadable
                        $changedKeys = array_diff(array_keys($attributes), ['updated_at', 'id']);
                        if (!empty($changedKeys)) {
                            return "Updated {$subjectText} - Changed: " . implode(', ', $changedKeys);
                        }
                    }
                    
                    return "Updated {$subjectText}";
                }
                
                return ucfirst($action) . " {$subjectText}";
            })
            ->add('created_at_formatted', fn (Activity $model) => $model->created_at ? $model->created_at->format('d/m/Y H:i:s') : '');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable()
                ->hidden(),

            Column::make('Timestamp', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::make('Actor', 'causer_id')
                ->searchable(),

            Column::make('Action', 'description')
                ->sortable()
                ->searchable(),

            Column::make('Target Model', 'subject_type')
                ->sortable()
                ->searchable(),

            Column::make('Details', 'formatted_properties')
        ];
    }
}
