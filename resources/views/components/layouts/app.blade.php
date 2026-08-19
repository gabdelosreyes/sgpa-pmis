<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SGPA-PMIS' }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    <tallstackui:script />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans text-gray-900 antialiased selection:bg-army-green-500 selection:text-white">
    <x-toast />
    <x-dialog />
    
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <div class="hidden w-64 overflow-y-auto bg-army-green-900 md:block">
            <div class="flex h-16 items-center justify-center bg-army-green-950 px-4">
                <span class="text-xl font-bold tracking-widest text-white">SGPA-PMIS</span>
            </div>
            <nav class="mt-5 px-2 text-white">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-army-green-800 text-white' : 'text-army-green-100 hover:bg-army-green-700 hover:text-white' }} group flex items-center rounded-md px-2 py-2 text-sm font-medium">
                    <x-icon name="home" class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-army-green-300' : 'text-army-green-300 group-hover:text-white' }}" />
                    Dashboard
                </a>
                <a href="{{ route('personnel.index') }}" class="{{ request()->is('personnel*') ? 'bg-army-green-800 text-white' : 'text-army-green-100 hover:bg-army-green-700 hover:text-white' }} group mt-1 flex items-center rounded-md px-2 py-2 text-sm font-medium">
                    <x-icon name="users" class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->is('personnel*') ? 'text-army-green-300' : 'text-army-green-300 group-hover:text-white' }}" />
                    Personnel
                </a>
                <a href="#" class="group mt-1 flex items-center rounded-md px-2 py-2 text-sm font-medium text-army-green-100 hover:bg-army-green-700 hover:text-white">
                    <x-icon name="shield-check" class="mr-3 h-5 w-5 flex-shrink-0 text-army-green-300 group-hover:text-white" />
                    User Management
                </a>
                <a href="#" class="group mt-1 flex items-center rounded-md px-2 py-2 text-sm font-medium text-army-green-100 hover:bg-army-green-700 hover:text-white">
                    <x-icon name="clipboard-document-list" class="mr-3 h-5 w-5 flex-shrink-0 text-army-green-300 group-hover:text-white" />
                    Activity Logs
                </a>
            </nav>
        </div>

        <!-- Main content -->
        <div class="flex flex-1 flex-col overflow-hidden">
            <!-- Topbar -->
            <header class="flex h-16 items-center justify-between bg-white px-6 shadow-sm border-b border-gray-200">
                <div class="flex items-center">
                    <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none md:hidden">
                        <x-icon name="bars-3" class="h-6 w-6" />
                    </button>
                    <h2 class="ml-4 text-xl font-semibold text-gray-800">{{ $header ?? 'Dashboard' }}</h2>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'Guest' }}</span>
                    <x-avatar text="{{ substr(auth()->user()->name ?? 'A', 0, 2) }}" color="primary" />
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                            Log out
                        </button>
                    </form>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
    
    @livewireScripts
</body>
</html>
