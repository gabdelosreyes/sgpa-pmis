<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable(['first_name', 'last_name', 'age', 'gender', 'department', 'date_started', 'is_active'])]
class Personnel extends Model
{
    use HasFactory, LogsActivity;

    protected function casts(): array
    {
        return [
            'date_started' => 'date',
            'is_active' => 'boolean',
            'age' => 'integer',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }
}
