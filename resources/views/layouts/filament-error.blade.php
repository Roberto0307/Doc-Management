<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} – @yield('title', 'Error')</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/filament/filament/app.css')}}"/>
</head>
<body class="h-full">
    @yield('content')

    <script src="{{ asset('filament/filament/js/app.js')}}"></script>
</body>
</html>
