<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Flight Management')</title>
    <script src="https://cdn.tailwindcss.com/"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="h-full">
    <div class="min-h-full">
    <nav class="bg-gray-800">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center">
            <div class="shrink-0">
                <img class="size-8" src="https://tailwindui.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company">
            </div>
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-4">
                <x-nav-link href="/cities" :active="request()->is('cities')">Cities</x-nav-link>
                <x-nav-link href="/airlines" :active="request()->is('airlines')">Airlines</x-nav-link>
                <x-nav-link href="/flights" :active="request()->is('flights')">Flights</x-nav-link>
                </div>
            </div>
            </div>
        </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state. -->
        <div class="md:hidden" id="mobile-menu">
        <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
        <x-nav-link href="/cities" :active="request()->is('cities')">Cities</x-nav-link>
        <x-nav-link href="/airlines" :active="request()->is('airlines')">Airlines</x-nav-link>
        <x-nav-link href="/flights" :active="request()->is('flights')">Flights</x-nav-link>
        </div>
        </div>
    </nav>
    <main>
        <div class="flex-1 px-8 py-6 w-full">
            @yield('content')
        </div>
    </main>
    </div>
    @stack('scripts')
</body>
