<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Delivery') }} | @yield('title') </title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/tenant.css') }}" >
        
    </head>
    <body>
        
        <nav class="navbar fixed-top navbar-light bg-light">
            <div class="container d-flex">
                <div>
                    <a class="navbar-brand" href="/">{{Session::get('tenant')->user->name}}</a>
                </div>
                <div>
                    @if (Cart::getTotalQuantity())
                        <a href="/checkout" class="btn btn-outline-dark">Finalizar compra</a>    
                    @endif
                    <a href="/cart" class="btn btn-outline-dark btn-cart-count"><i class="fa-solid fa-cart-shopping"></i> (<span>{{ Cart::getTotalQuantity() }}</span>)</a>
                </div>
            </div>
        </nav>

        @yield('content')

        <footer class="fixed-bottom text-center">
            Desenvolvido por Alguém
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/af1f242183.js" crossorigin="anonymous"></script>

        @yield('load-js')

        @yield('js')

        <div class="loader">
            <div class="lds-ripple"><div></div><div></div></div>
        </div>
    </body>
</html>
