<div>
    <x-card>
        <form wire:submit="login" class="space-y-6">
            <x-input label="Email Address" wire:model="email" icon="envelope" />
            
            <x-password label="Password" wire:model="password" />

            <div class="flex items-center justify-between">
                <a href="{{ route('register') }}" class="text-sm font-medium text-army-green-600 hover:text-army-green-500">
                    Create an account
                </a>
            </div>

            <x-button type="submit" color="primary" class="w-full">
                Sign In
            </x-button>
            
            <div class="relative mt-6">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm font-medium leading-6">
                    <span class="bg-white px-6 text-gray-900">Or continue with</span>
                </div>
            </div>

            <x-button href="/auth/google" color="white" class="w-full mt-4 flex items-center justify-center gap-2">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                Google
            </x-button>
        </form>
    </x-card>
</div>
