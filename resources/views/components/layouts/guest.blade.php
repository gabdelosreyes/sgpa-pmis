<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SGPA-PMIS' }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    <tallstackui:script />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans text-gray-900 antialiased selection:bg-army-green-500 selection:text-white bg-gray-50 flex items-center justify-center">
    <x-toast />
    <x-dialog />
    
    <div class="w-full max-w-md p-6">
        <div class="flex justify-center mb-8">
            <span class="text-3xl font-bold tracking-widest text-army-green-900">SGPA-PMIS</span>
        </div>
        
        {{ $slot }}
    </div>
    
    @livewireScripts
</body>
</html>
