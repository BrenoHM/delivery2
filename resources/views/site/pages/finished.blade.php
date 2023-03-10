@extends('site.layouts.app')

@section('title', 'Home')

@section('content')
    <div class="container finished">
        <h2>Parabéns!!!</h2>

        {{-- <div class="alert alert-success" role="alert">
            Sua assinatura foi realizada com sucesso! Em breve você receberá emails com instruções de acesso!
        </div> --}}

        @if (Session::has('message'))
            <div class="alert alert-success" role="alert">
                {{Session::get('message')}}
            </div>
        @elseif (Session::has('error'))
            <div class="alert alert-danger" role="alert">
                {{Session::get('error')}}
            </div>
        @endif
        
        @if (Session::has('response'))
            <h2 class="text-center">Assinatura N° {{ Session::get('response')['data']['subscription_id'] }}</h2>
        @endif
        
    </div>
@endsection