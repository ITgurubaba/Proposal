<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{ Vite::useBuildDirectory('build/admin') }}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{--    <!-- Fonts -->--}}
    {{--    <link rel="preconnect" href="https://fonts.bunny.net">--}}
    {{--    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />--}}

    <!-- Scripts -->
    @vite(['resources/js/admin/app.js'])
    @stack('head')
</head>
<body class="font-sans antialiased customScrollbar">
<x-mary-main with-nav full-width>
    <x-slot:content class="flex flex-col ">
        <div class="flex-grow">
            {{ $slot }}
        </div>
    </x-slot:content>
</x-mary-main>

<x-mary-toast />
@stack('scripts')
</body>
</html>
