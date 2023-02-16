<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/tenant.css') }}" >
        
    </head>
    <body>
        
        <nav class="navbar fixed-top navbar-light bg-light">
            <div class="container d-flex">
                <div>
                    <a class="navbar-brand" href="/">Nome da loja</a>
                </div>
                <div>
                    <a href="#" class="btn btn-outline-dark">Fechar compra</a>
                    <a href="#" class="btn btn-outline-dark"><i class="fa-solid fa-cart-shopping"></i></a>
                </div>
            </div>
        </nav>

        @yield('content')

        <footer class="fixed-bottom text-center">
            Desenvolvido por
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/af1f242183.js" crossorigin="anonymous"></script>

        @yield('js')
    </body>
</html>