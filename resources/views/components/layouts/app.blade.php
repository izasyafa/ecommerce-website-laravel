<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'IzzaCode' }}</title>

        @vite('resources/css/app.css', 'resources/js/app.js')

        @livewireStyles()
    </head>

    <body class="bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 font-sans antialiased">
        @livewire('partials.navbar')

        <main>
            {{ $slot }}
        </main>

        @livewire('partials.footer')
        @livewireScripts
    </body>
</html>
