<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="Expires" content="0">
        <meta http-equiv="Last-Modified" content="0">
        <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <title>{{ $title ?? 'Heladerias - Menú' }}</title>
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        @stack('otherCss')
            <!-- Scripts -->
            <script src="{{ asset('js/app.js') }}" defer></script>
        @stack('headScripts')
        <style>
            .color-bootstrap {
                background-color: #007BFF;
            }
            body { 
                background: url( "{{ asset('img/fondo_heladerias_2.jpg') }}" ) no-repeat center fixed; 
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            }
            #clock {
                position: fixed;
                bottom: 8px;
                right: 8px;
            }
          </style>
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="absolute hidden left-20 xl:top-2">
            <img class="h-20" src="{{ asset('img/logo_heladerias_dc.png') }}" alt="logo">
        </div>
        <!-- Navegacion -->
        @section('navbar')
        @show
        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
        <!-- Footer -->
        <div>
            <footer>
                    <div class="text-gray-600 text-sm font-bold text-center"
                        id="clock">
                    </div>
            </footer>
        </div>
        @stack('scripts')
        <script>
            function currentTime() {
                let date = new Date(); 
                let hh = date.getHours();
                let mm = date.getMinutes();
                let ss = date.getSeconds();
                hh = (hh < 10) ? "0" + hh : hh;
                mm = (mm < 10) ? "0" + mm : mm;
                ss = (ss < 10) ? "0" + ss : ss;
                let time = hh + ":" + mm + ":" + ss;
                document.getElementById("clock").innerText = time; 
                var t = setTimeout(function(){ currentTime() }, 1000); 
            }

            currentTime();
        </script>
    </body>
</html>
