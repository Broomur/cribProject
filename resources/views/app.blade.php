<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Bienvenue sur le site web de la crèche associative les Petits Mickeys située à Barby (Savoie), vous y trouverez les informations principales concernant notre établissement.">
        <title inertia>{{ config('app.name', 'Laravel') }}</title>

         <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ URL::asset('/favicon/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ URL::asset('/favicon/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('/favicon/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ URL::asset('/favicon/site.webmanifest') }}">
        <link rel="mask-icon" href="{{ URL::asset('/favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        @routes
        @vite('resources/js/app.js')
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
