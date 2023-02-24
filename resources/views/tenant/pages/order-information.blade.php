@extends('tenant.layouts.app')

@section('title', 'Order Informartion')

@section('content')
    <div class="container order-information">
        <h2 class="border-bottom mb-3">Informações sobre o pedido</h2>

        <div class="card card-timeline px-2 border-none mb-3">
            <ul class="bs4-order-tracking"> 
                {{-- <li class="step active"> <div><i class="fas fa-user"></i></div> Pedido Realizado <br> <small>24/02/2023 às 11:00</small> </li> 
                <li class="step active"> <div><i class="fas fa-bread-slice"></i></div> Em preparação <br> <small>24/02/2023 às 11:00</small> </li> 
                <li class="step"> <div><i class="fas fa-truck"></i></div> Saiu para entrega <br> <small>24/02/2023 às 11:00</small> </li> 
                <li class="step "> <div><i class="fas fa-birthday-cake"></i></div> Entregue <br> <small>24/02/2023 às 11:00</small> </li>  --}}
                @foreach ($status as $st)
                    <li class="step {{ $st['active'] ? 'active' : '' }}"> <div><i class="fas {{$st['icon']}}"></i></div> {{ $st['label'] }} <br> <small>{{ $st['quando'] }}</small> </li> 
                @endforeach
            </ul> 
            {{-- <h5 class="text-center"><b>In transit</b>. The order has been shipped!</h5> --}}
        </div>

        <div class="row">
            <div class="col-md-6">
                <h4>Endereço de entrega</h4>

                <p>{{ $order->name }} - {{ $order->phone }}</p>
                

                @if ($order->delivery_method == 'shipping')
                    <p>{{ $order->street }}, {{ $order->number }}, {{ $order->complement }}, {{ $order->neighborhood }}</p>
                @else
                    <p>Retirado no local</p>
                @endif
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    @foreach ($order->items as $item)
                        <tr>
                            <td><img src="{{ $item->product->photo }}" alt="" class="img-fluid" width="50"></td>
                            <td>{{ $item->product->name }} <br> x{{ $item->quantity }}</td>
                            <td>R$ {{ number_format($item->total, 2, ",", ".") }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" class="text-end">SubTotal dos produtos</td>
                        <td>R$ {{ number_format($order->total - $order->freight_total, 2, ",", ".") }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-end">Taxa de frete</td>
                        <td>R$ {{ number_format($order->freight_total, 2, ",", ".") }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-end">Total do pedido</td>
                        <td>R$ {{ number_format($order->total, 2, ",", ".") }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-end">Método de pagamento</td>
                        <td>{{ $order->payment_method == 'money' ? 'Dinheiro' : 'Cartão' }}</td>
                    </tr>
                </table>
                {{-- {{ $order->items }} --}}
            </div>
        </div>
    </div>
@endsection

@section('load-js')
    
@endsection

@section('js')
    
    <script>

       
    </script>
@endsection