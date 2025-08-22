<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Nex Procure</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-p..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>


    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset
        <main class="grid grid-cols-[256px_1fr]">
            <aside class="w-64 bg-white shadow-md min-h-[calc(100vh-64px)] sticky top-0">
                <nav class="p-4 space-y-2">
                    <a href="{{ route('dashboard') }}"
                        class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('dashboard') ? 'bg-gray-300 font-semibold' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('products.index') }}"
                        class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('products.*') ? 'bg-gray-300 font-semibold' : '' }}">
                        Products
                    </a>
                    <a href="{{ route('suppliers.index') }}"
                        class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('suppliers.*') ? 'bg-gray-300 font-semibold' : '' }}">
                        Suppliers
                    </a>
                    <a href="{{ route('purchases.index') }}"
                        class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('purchases.*') ? 'bg-gray-300 font-semibold' : '' }}">
                        Purchases
                    </a>

                </nav>
            </aside>
            <div class="p-6">
                {{ $slot }}
            </div>
        </main>
    </div>

    <x-toastr />
</body>

</html>
