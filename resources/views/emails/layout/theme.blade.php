<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Notificación de ' . config('app.name') )</title>
    @include('emails.layout.styles')
</head>
<body>
    <table class="body">
        <tr>
            <td align="center">
                <table class="container">
                    <tr>
                        <td class="header">
                            {{-- <img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }}" height="40"> --}}
                        </td>
                    </tr>
                    <tr>
                        <td class="content">
                            <h1>@yield('title', 'Notificación de ' . config('app.name'))</h1>
                            @yield('content')
                            <p>Gracias,<br>Equipo de {{ config('app.name') }}.</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="footer">
                            Este mensaje fue generado automáticamente por el sistema {{ config('app.name') }}.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
