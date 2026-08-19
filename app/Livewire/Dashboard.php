<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Personnel;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        $totalUsers = User::where('is_active', true)->count();
        $totalPersonnel = Personnel::where('is_active', true)->count();
        
        // Mock distributions since we have no data yet
        $genderDistribution = [
            'Male' => Personnel::where('gender', 'Male')->where('is_active', true)->count() ?: 120,
            'Female' => Personnel::where('gender', 'Female')->where('is_active', true)->count() ?: 45,
        ];

        return view('livewire.dashboard', compact('totalUsers', 'totalPersonnel', 'genderDistribution'))
            ->layoutData(['header' => 'Dashboard Overview']);
    }
}
