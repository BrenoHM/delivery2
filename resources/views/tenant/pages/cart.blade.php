@extends('tenant.layouts.app')

@section('content')
    <div class="container cart-list">
        <h1>Carrinho</h1>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Produto</th>
                        <th>Adicionais</th>
                        <th>Preço</th>
                        <th>Quantidade</th>
                        <th>Subtotal</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cartItems as $item)
                        <tr>
                            <td class="align-middle">
                                @if ($item->attributes->photo)
                                    <img src="{{ $item->attributes->photo }}" alt="{{ $item->name }}" width="50" />
                                @else
                                    <img src="{{ asset('assets/images/no-image.png') }}" alt="{{ $item->name }}" width="50" />
                                @endif
                            </td>
                            <td class="align-middle">
                                {{$item->name}}
                            </td>
                            <td class="align-middle">
                                @if ($item->attributes->additions)
                                    @foreach ($item->attributes->additions as $addition)     
                                        <span class="d-block"> <span class="badge rounded-pill bg-success">+</span> {{ $addition->addition }} + R$ {{ number_format($addition->price, 2, ",", ".") }}</span>
                                    @endforeach
                                @endif
                            </td>
                            <td class="align-middle">R$ {{ number_format($item->price,2,",",".") }}</td>
                            <td class="align-middle"><input type="number" name="quantity" onchange='handleChangeQuantity($(this), "{{$item->id}}")' class="form-control" min="1" step="1" style="width: 60px" value="{{$item->quantity}}" /></td>
                            <td class="align-middle subtotal-{{$item->id}}">R$ <span>{{ number_format($item->price * $item->quantity,2,",",".") }}</span></td>
                            <td class="align-middle"><button class="btn" id="{{ $item->id }}" onclick="handleDeleteItem($(this))"><i class="fa-regular fa-trash-can"></i></button></td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="5">Seu carrinho esta vazio :(</td>
                        </tr>    
                    @endforelse
                    
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-md-7">
                <a href="/" class="btn btn-outline-success">{{ Cart::getTotalQuantity() ? "Continuar comprando" : "Carrinho vazio, comece a comprar!" }}</a>
            </div>
            <div class="col-md-5">
                <div>
                    <h2>Total no carrinho</h2>
                    <table class="table table-bordered table-striped w-100">
                        <tr>
                            <td>Subtotal</td>
                            <td class="subtotal-td">R$ <span>{{ number_format(Cart::getTotal(),2,",",".") }}<span></td>
                        </tr>
                        <tr>
                            <td>Entrega</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td class="total-td">R$ <span>{{ number_format(Cart::getTotal(),2,",",".") }}<span></td>
                        </tr>
                    </table>
                    @if (Cart::getTotalQuantity() > 0)
                        <button type="button" class="btn btn-success btn-cart-total w-100">
                            Continuar para a finalização da compra - Total: R$ <span>{{ number_format(Cart::getTotal(),2,",",".") }}<span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        {{-- {{$cartItems}} --}}
    </div>
@endsection

@section('js')
    
    <script>

        const handleChangeQuantity = (e, id) => {
            $.ajax({
                method: 'POST',
                url: "/update-cart",
                data: {_token: "{{ csrf_token() }}", id, quantity: e.val()},
                beforeSend: () => {
                    document.querySelector('.loader').style.display = 'flex';
                },
                success: (data) => {
                    if( data.success ) {
                        $(`td.subtotal-${id} span`).text(parseFloat(data.subTotal).toFixed(2).replace('.', ','));
                        $("table td.subtotal-td span").text(parseFloat(data.totalCart).toFixed(2).replace('.', ','));
                        $("table td.total-td span").text(parseFloat(data.totalCart).toFixed(2).replace('.', ','));
                        $("button.btn-cart-total span").text(parseFloat(data.totalCart).toFixed(2).replace('.', ','));
                        $("a.btn-cart-count span").text(data.totalQuantityCart);
                        document.querySelector('.loader').style.display = 'none';
                    }
                }
            })
        }

        const handleDeleteItem = (e) => {
            if( !confirm('Deseja realmente excluir este produto?') ) return;

            const id = e.attr('id');

            $.ajax({
                method: 'POST',
                url: "/cart/remove",
                data: {_token: "{{ csrf_token() }}", id},
                beforeSend: () => {
                    document.querySelector('.loader').style.display = 'flex';
                },
                success: (data) => {
                    if( data.success ) {
                        e.closest('tr').remove();
                        $("table td.subtotal-td span").text(parseFloat(data.totalCart).toFixed(2).replace('.', ','));
                        $("table td.total-td span").text(parseFloat(data.totalCart).toFixed(2).replace('.', ','));
                        $("button.btn-cart-total span").text(parseFloat(data.totalCart).toFixed(2).replace('.', ','));
                        $("a.btn-cart-count span").text(data.totalQuantityCart);
                        document.querySelector('.loader').style.display = 'none';
                    }
                }
            })

        }

    </script>
@endsection