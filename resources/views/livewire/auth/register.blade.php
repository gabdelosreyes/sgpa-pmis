<div>
    <x-card>
        <form wire:submit="register" class="space-y-6">
            <x-input label="Full Name" wire:model="name" icon="user" />
            
            <x-input label="Email Address" wire:model="email" icon="envelope" />
            
            <x-password label="Password" wire:model="password" />
            
            <x-password label="Confirm Password" wire:model="password_confirmation" />

            <div class="flex items-center justify-between">
                <a href="{{ route('login') }}" class="text-sm font-medium text-army-green-600 hover:text-army-green-500">
                    Already have an account? Sign in
                </a>
            </div>

            <x-button type="submit" color="primary" class="w-full">
                Register
            </x-button>
        </form>
    </x-card>
</div>
