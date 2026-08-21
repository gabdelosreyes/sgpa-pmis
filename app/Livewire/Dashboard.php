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
        
        $genderDistribution = [
            'Male' => Personnel::where('gender', 'Male')->where('is_active', true)->count() ?: 120,
            'Female' => Personnel::where('gender', 'Female')->where('is_active', true)->count() ?: 45,
        ];

        // Fetch department distribution
        $departments = Personnel::where('is_active', true)
            ->selectRaw('department, count(*) as count')
            ->groupBy('department')
            ->pluck('count', 'department')
            ->toArray();

        // If no data, use some mock data for the presentation
        if (empty($departments)) {
            $departments = [
                'Headquarters' => 45,
                'Operations' => 85,
                'Logistics' => 30,
                'Communications' => 120,
                'Intelligence' => 25,
            ];
        }

        // Active vs Inactive Personnel
        $statusDistribution = [
            'Active' => $totalPersonnel ?: 448,
            'Deactivated' => Personnel::where('is_active', false)->count() ?: 32,
        ];

        return view('livewire.dashboard', compact('totalUsers', 'totalPersonnel', 'genderDistribution', 'departments', 'statusDistribution'))
            ->layoutData(['header' => 'Dashboard Overview']);
    }
}
