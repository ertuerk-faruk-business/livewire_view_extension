@props([
    'title' => 'Ellada',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Du suchst einen neuen Look? Dann bist du bei uns richtig! Unsere Mitarbeiter sind alle sehr begabt und werden dir jeden Wunsch erfüllen.">
        <meta name="author" content="Faruk Ertürk">
        <meta name="copyright" content="Faruk Ertürk">
        <meta name="publisher" content="Faruk Ertürk">
        @unless(empty(env('APP_URL')))
            <meta name="canonical" href="{{ env('APP_URL', '/') }}">
        @endunless
        <link href="{{ mix('/css/site.css') }}" rel="stylesheet">
        <link rel="mask-icon" href="/favicon.svg" color="#ff8a01">
        <link rel="icon" type="image/svg+xml" href="/favicon.png">
        <title>{{ $title }}</title>
        <livewire:styles/>
        @stack('styles')

        {{ $head ?? '' }}

    </head>
    <body class="text-white text-base font-sans bg-black antialiased  no-scroll-bar overflow-y-scroll scroll-smooth">
        @livewireScripts
        @stack('scripts')
        {{ $slot }}
        <script src="{{ mix('/js/site.js') }}"></script>
        <script src="{{ mix('/js/slider.js') }}"></script>
    </body>
</html>
