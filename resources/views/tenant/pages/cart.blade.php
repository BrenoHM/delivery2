@extends('tenant.layouts.app')

@section('title', 'Cart')

@section('content')
    <div class="container cart-list">
        <h2 class="border-bottom">Carrinho</h2>
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
                                {{ $item->name }}
                                @if ( $item->attributes->variation_description )
                                    <br />
                                    <strong>{{ $item->attributes->variation_description }}</strong>
                                @endif
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
                            <td class="text-center" colspan="7">Seu carrinho esta vazio :(</td>
                        </tr>    
                    @endforelse
                    
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-md-6">
                <a href="/" class="btn btn-outline-success btn-continue">{{ Cart::getTotalQuantity() ? "Continuar comprando" : "Carrinho vazio, comece a comprar!" }}</a>
            </div>
            
            @if (Cart::getTotal() > 0)

                <div class="col-md-6">
                    <div>
                        <h2>Total no carrinho</h2>
                        <table class="table table-bordered table-striped w-100 table-total-cart">
                            <tr>
                                <td>Subtotal</td>
                                <td class="subtotal-td">R$ <span>{{ number_format(Cart::getTotal(),2,",",".") }}<span></td>
                            </tr>
                            <tr>
                                <td class="align-middle">Entrega</td>
                                <td>
                                    <div class="d-flex p-2">
                                        <input type="text" name="zip_code" id="zip_code" class="form-control" placeholder="ex: 05010-000" />
                                        <button class="btn btn-secondary" onclick="handleSearchCep()">Consultar</button>
                                    </div>

                                    @if ( Session::has('freight_details') && isset(Session::get('freight_details')['price']) )
                                        <div id="delivery_method_text">
                                            <label class="p-2">
                                                <input type="radio"
                                                        name="delivery_method"
                                                        data-price="{{ Session::get('freight_details')['price'] }}"
                                                        value="shipping"
                                                        onchange="handleChangeDeliveryMethod($(this))"
                                                        @if (Session::get('freight_details')['delivery_method'] == 'shipping') checked @endif>
                                                        Retirar na {{ Session::get('freight_details')['street'] }}, {{ Session::get('freight_details')['neighborhood'] }} por <strong>R$ {{number_format(Session::get('freight_details')['price'] , 2, ",", ".")}}</strong>
                                            </label>
                                        </div>
                                    @else
                                        <div id="delivery_method_text"></div>
                                    @endif
                                    
                                    <label class="p-2">
                                        <input
                                            type="radio"
                                            name="delivery_method"
                                            data-price="0"
                                            value="local"
                                            onchange="handleChangeDeliveryMethod($(this))"
                                            @if (isset(Session::get('freight_details')['delivery_method']) && Session::get('freight_details')['delivery_method'] == 'local') checked @endif /> Retirar no local
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td class="total-td">R$ <span>{{ number_format(Cart::getTotal(),2,",",".") }}<span></td>
                            </tr>
                        </table>
                        
                        @if ($isOpened)
                            <a href="/checkout" class="btn btn-success btn-cart-total w-100">
                                Continuar para a finalização da compra - Total: R$ <span>{{ number_format(Cart::getTotal(),2,",",".") }}<span>
                            </a>
                        @else
                            <button type="button" class="btn btn-warning w-100">Fechado para pedidos</button>
                        @endif

                    </div>
                </div>
                
            @endif
            
        </div>
    </div>
@endsection

@section('load-js')
    <script src="https://cdn.jsdelivr.net/npm/cep-promise/dist/cep-promise.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/jquery.mask.min.js') }}"></script>
@endsection

@section('js')
    
    <script>

        const handleSearchCep = () => {
            const value = document.querySelector("#zip_code").value;
            if( value.length == 9 ) {
                cep(value)
                    .then(data => {
                        $.ajax({
                            method: 'POST',
                            url: "/freigh/search",
                            data: {_token: "{{ csrf_token() }}", ...data},
                            beforeSend: () => {
                                document.querySelector('.loader').style.display = 'flex';
                            },
                            success: (data) => {
                                document.querySelector('.loader').style.display = 'none';
                                if( data.success ) {
                                    $("#delivery_method_text").html(`
                                        <label class="p-2">
                                            <input type="radio" name="delivery_method" data-price="${data.price}" value="shipping" onchange="handleChangeDeliveryMethod($(this))"> ${data.message}
                                        </label>
                                    `)
                                }
                                handleChangeDeliveryMethod($(null), true);
                            },
                            error: (err) => {
                                document.querySelector('.loader').style.display = 'none';
                                $("#delivery_method_text").html('');
                                handleChangeDeliveryMethod($(null), true);
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Oops...',
                                    text: err.responseJSON.message,
                                })
                            }
                        });
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: err.message,
                            //footer: '<a href="">Why do I have this issue?</a>'
                        })
                });
            }
        }

        const handleChangeQuantity = (e, id) => {
            //frete
            let freight = $('input[name="delivery_method"]:checked').data('price');
            if( freight ) {
                freight = parseFloat(freight);
            }else{
                freight = 0;
            }
            
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
                        $("table td.total-td span").text(parseFloat(data.totalCart + freight).toFixed(2).replace('.', ','));
                        $("a.btn-cart-total span").text(parseFloat(data.totalCart + freight).toFixed(2).replace('.', ','));
                        $("a.btn-cart-count span").text(data.totalQuantityCart);
                        document.querySelector('.loader').style.display = 'none';
                    }
                }
            });
        }

        const handleDeleteItem = (e) => {
            if( !confirm('Deseja realmente excluir este produto?') ) return;

            const id = e.attr('id');

            let freight = $('input[name="delivery_method"]:checked').data('price');
            if( freight ) {
                freight = parseFloat(freight);
            }else{
                freight = 0;
            }

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
                        $("table td.total-td span").text(parseFloat(data.totalCart + freight).toFixed(2).replace('.', ','));
                        $("a.btn-cart-total span").text(parseFloat(data.totalCart + freight).toFixed(2).replace('.', ','));
                        $("a.btn-cart-count span").text(data.totalQuantityCart);
                        document.querySelector('.loader').style.display = 'none';
                    }
                }
            })

        }

        const handleChangeDeliveryMethod = (e, inLoadPage = false) => {

            var value = 0;

            if(!inLoadPage) {
                value = parseFloat(e.data('price'));
            }else{
                if( $('input[name="delivery_method"]:checked').data('price') ) {
                    value = parseFloat($('input[name="delivery_method"]:checked').data('price'));
                }
            }

            let data = {
                _token: "{{ csrf_token() }}",
                delivery_method: inLoadPage ? $('input[name="delivery_method"]:checked').val() : e.val()
            }
            
            $.ajax({
                url: "/cart/total",
                data: data,
                beforeSend: () => {
                    document.querySelector('.loader').style.display = 'flex';
                },
                success: function(data) {
                    if( data.success ) {
                        $("table td.total-td span").text(parseFloat(data.totalCart + value).toFixed(2).replace('.', ','));
                        $("a.btn-cart-total span").text(parseFloat(data.totalCart + value).toFixed(2).replace('.', ','));
                        document.querySelector('.loader').style.display = 'none';
                    }
                }
            });
        }

        $( document ).ready(function() {
            
            @if(Session::has('freight_details') && Session::get('freight_details')['delivery_method'] == 'shipping')

                handleChangeDeliveryMethod($(null), true);
                
            @endif

            $("#zip_code").mask('00000-000');
            
        });

    </script>
@endsection