<div class="space-y-6">
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <x-stats title="Active Personnel" number="{{ $totalPersonnel }}" icon="users" color="primary" />
        <x-stats title="System Users" number="{{ $totalUsers }}" icon="shield-check" color="emerald" />
        <x-stats title="Male Personnel" number="{{ $genderDistribution['Male'] }}" icon="user" color="blue" />
        <x-stats title="Female Personnel" number="{{ $genderDistribution['Female'] }}" icon="user" color="pink" />
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <x-card>
            <x-slot:header>
                Personnel by Department
            </x-slot:header>
            
            <div class="relative h-72 w-full" wire:ignore>
                <canvas id="departmentChart"></canvas>
            </div>
        </x-card>

        <x-card>
            <x-slot:header>
                Status Distribution
            </x-slot:header>
            
            <div class="relative h-72 w-full flex justify-center" wire:ignore>
                <canvas id="statusChart"></canvas>
            </div>
        </x-card>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            const depCtx = document.getElementById('departmentChart');
            new Chart(depCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($departments)) !!},
                    datasets: [{
                        label: 'Personnel Count',
                        data: {!! json_encode(array_values($departments)) !!},
                        backgroundColor: '#3b5f41', // Army green
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });

            const statusCtx = document.getElementById('statusChart');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode(array_keys($statusDistribution)) !!},
                    datasets: [{
                        data: {!! json_encode(array_values($statusDistribution)) !!},
                        backgroundColor: ['#22c55e', '#ef4444'], // Green for Active, Red for Deactivated
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
</div>
