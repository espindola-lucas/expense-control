<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f7fafc;
                margin: 0;
                padding: 0;
            }
            .container {
                max-width: 600px;
                margin: 40px auto;
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }
            .header {
                background-color: #1d4ed8;
                color: white;
                padding: 24px;
                text-align: center;
            }
            .content {
                padding: 32px;
                color: #2d3748;
            }
            .button {
                margin-top: 24px;
                padding: 12px 24px;
                border-radius: 6px;
                text-decoration: none;
                background-color: #1d4ed8 !important;
                color: white !important;
            }
            .footer {
                padding: 16px;
                text-align: center;
                font-size: 12px;
                color: #718096;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="container">
            <div class="header">
                <h1>Recuperación de contraseña</h1>
            </div>
            <div class="content">
                <p>Hola {{ $user->name }},</p>
    
                <p>Vamos a recuperar tu contraseña para que puedas iniciar sesión nuevamente.</p>
    
                <p>Por favor haz clic en el siguiente botón:</p>
    
                <div style="text-align: center; margin-top: 12px;">
                    <a href="{{ $resetPasswordUrl }}" class="button">Cambiar contraseña</a>
                </div>

                <p style="margin-top: 32px;">Si no solicitaste la recuperación de contraseña, puedes ignorar este mensaje.</p>
            </div>
            <div class="footer">
                © {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
            </div>
        </div>
    </body>
</html>