<?php

namespace App\Livewire\ActivityLogs;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app', ['header' => 'Activity Logs'])]
#[Title('Activity Logs')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.activity-logs.index');
    }
}
