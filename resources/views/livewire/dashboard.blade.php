<div class="space-y-6">
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <x-stats title="Active Personnel" number="{{ $totalPersonnel }}" icon="users" color="primary" />
        <x-stats title="System Users" number="{{ $totalUsers }}" icon="shield-check" color="emerald" />
        <x-stats title="Male Personnel" number="{{ $genderDistribution['Male'] }}" icon="user" color="blue" />
        <x-stats title="Female Personnel" number="{{ $genderDistribution['Female'] }}" icon="user" color="pink" />
    </div>

    <x-card>
        <x-slot:header>
            Command Overview
        </x-slot:header>
        
        <p class="text-gray-600">
            Welcome to the Signal Regiment Personnel Management Information System. Use the sidebar to navigate to specific modules.
        </p>
    </x-card>
</div>
