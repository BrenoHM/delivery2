@extends('tenant.layouts.app')

@section('title', 'Order Informartion')

@section('content')
    <div class="container order-information">
        <h2 class="border-bottom mb-3">Informações sobre o pedido - Nº #{{ $order->id }}</h2>

        <div class="card card-timeline px-2 border-none mb-3">
            <ul class="bs4-order-tracking"> 
                @if ($canceled)
                    <li class="step {{ $status[0]['active'] ? 'active' : '' }}"> <div><i class="fas {{$status[0]['icon']}}"></i></div> {{ $status[0]['label'] }} <br> <small>{{ $status[0]['quando'] }}</small> </li> 
                    <li class="step active"> <div><i class="fas fa-bread-slice"></i></div> Cancelado <br> <small>{{ $canceled->created_at->format('d/m/y H:i:s') }}</small> </li> 
                    <li class="step"> <div><i class="fas fa-bread-slice"></i></div> Em preparação</li> 
                    <li class="step"> <div><i class="fas fa-person-running"></i></div> Saiu para entrega</li> 
                @else
                    @foreach ($status as $st)
                        <li class="step {{ $st['active'] ? 'active' : '' }}"> <div><i class="fas {{$st['icon']}}"></i></div> {{ $st['label'] }} <br> <small>{{ $st['quando'] }}</small> </li> 
                    @endforeach    
                @endif
                
            </ul> 
        </div>

        <div class="row">
            <div class="col-md-6">
                <h4>Endereço de entrega</h4>

                <p>{{ $order->name }} - {{ $order->phone }}</p>
                

                @if ($order->delivery_method == 'shipping')
                    <p>
                        {{ $order->street }}, {{ $order->number }}{{ $order->complement ? ', ' . $order->complement : '' }}, {{ $order->neighborhood }}<br>
                        {{ $order->zip_code }} - {{ $order->city }}/{{ $order->state }}
                    </p>
                @else
                    <p>
                        <strong>Retirar no local:</strong><br>
                        {{ $tenant->street }}, {{ $tenant->number }}{{ $tenant->complement ? ', ' . $tenant->complement : '' }}, {{ $tenant->neighborhood }}<br>
                        {{ $tenant->zip_code }} - {{ $tenant->city }}/{{ $tenant->state }}
                    </p>
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
                        <td><strong>R$ {{ number_format($order->total, 2, ",", ".") }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-end">Método de pagamento</td>
                        <td>
                            @if ($order->payment_method == 'money')
                                Dinheiro
                            @elseif($order->payment_method == 'card')
                                Cartão
                            @else
                                Pix
                            @endif
                        </td>
                    </tr>
                    @if ( $order->payment_method == 'pix' )
                        <tr>
                            <td colspan="2" class="text-end">Chave Pix: <strong>{{ $typePix[$tenant->type_pix_key] }}</strong></td>
                            <td>{{ $tenant->pix_key }}</td>
                        </tr>
                    @endif
                    @if ( $order->additional_information )
                        <tr>
                            <td colspan="2" class="text-end">Informação adicional</td>
                            <td>{{ $order->additional_information }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')    
    <script>
        window.setTimeout( function() {
            window.location.reload();
        }, 60000);
    </script>
@endsection