<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Heladerías - Menú' }}</title>
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <style>
            .color-bootstrap {
                background-color: #007BFF;
            }
            body { 
                background: url( "{{ asset('img/fondo_heladerias_2.jpg') }}" ) no-repeat center center fixed; 
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            }
            #clock {
                position: absolute;
                bottom: 8px;
                right: 8px;
            }
          </style>
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="flex flex-col h-screen justify-between">
            <!-- Logo top left -->
            <div class="absolute left-20 top-2 hidden xl:block">
                <img class="h-20" src="{{ asset('img/logo_netsmart2.png') }}" alt="logo">
            </div>
            <!-- Navegacion -->
            @include('layouts.navegacion')
            <!-- Logo top left -->
<!--        
            <div class="absolute left-50 right-5 bottom-14">
                <img class="h-32" src="{{ asset('img/logo_heladerias_dc.png') }}" alt="logo">
            </div>  
-->
            <div class="text-gray-200 text-sm font-semibold text-center"
                id="clock">
            </div>
            <!-- Page Content -->
            <div class="py-12">
                <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg opacity-75">
                        <div class="p-6 text-center">
                            NetSmart - Menú Principal
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <div class="bg-blue-700">
                <footer class="flex flex-wrap items-center justify-between p-3 m-auto h-10">
                    <div class="container mx-auto flex flex-col flex-wrap items-center justify-between">
                        <div class="flex mx-auto text-gray-200 text-sm text-center"
                            id="copyright">
                            Copyright por script JS
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script>
            var copyRight = 'Copyright AUSoft © ' + new Date().getFullYear() + ' / Ver. 45.0.3';
            document.querySelector('#copyright').innerText = copyRight;
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
