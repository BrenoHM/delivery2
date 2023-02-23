@extends('tenant.layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="container checkout-page">
        <h2 class="border-bottom mb-3">Finalização de compra</h2>
        <form action="{{ route('checkout.store', $tenant) }}" method="POST">
            @if ($isOpened) @csrf @endif
            <div class="row">
                <div class="col-md-6 border rounded-1 p-3">
                    <h4>Detalhes de faturamento</h4>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome *</label>
                        <div class="col-md-12">
                          <input type="text" name="name" class="form-control" id="name" placeholder="Ex: João" value="{{ old('name') }}" required />
                          @if($errors->has('name'))
                            <div class="invalid-feedback d-block">{{ $errors->first('name') }}</div>
                          @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefone *</label>
                        <div class="col-md-6">
                          <input type="text" name="phone" class="form-control" id="phone" placeholder="Ex: (99) 99999-9999" value="{{ old('phone') }}" required />
                          @if($errors->has('phone'))
                            <div class="invalid-feedback d-block">{{ $errors->first('phone') }}</div>
                          @endif
                        </div>
                    </div>

                    <div class="mb-3">
                      <label for="payment_method" class="form-label">Forma de pagamento *</label>
                      <div class="col-md-6">
                        <select name="payment_method" id="payment_method" class="form-control" required>
                          <option value="">Selecione</option>
                          <option value="money">Dinheiro</option>
                          <option value="card">Cartão</option>
                        </select>
                        @if($errors->has('payment_method'))
                          <div class="invalid-feedback d-block">{{ $errors->first('payment_method') }}</div>
                        @endif
                      </div>
                    </div>

                    <div class="mb-3">
                      <label>
                        <input
                          type="radio"
                          name="delivery_method"
                          data-price="{{ $freightDetails['price'] ?? 0 }}"
                          value="shipping"
                          onchange="handleChangeDeliveryMethod($(this))"
                          @if (isset($freightDetails['delivery_method']) && $freightDetails['delivery_method'] == 'shipping') checked @endif required /> Entregar no meu endereço
                      </label>
                    </div>

                    <div class="block_address {{ isset($freightDetails['delivery_method']) && $freightDetails['delivery_method'] == 'shipping' ? 'd-block' : 'd-none' }}">
                        <div class="mb-3">
                            <label for="zip_code" class="form-label">Cep *</label>
                            <div class="col-md-6">
                              <input type="text" name="zip_code" class="form-control" id="zip_code" placeholder="Ex: 05010-000" value="{{ $freightDetails['zip_code'] ?? "" }}" onkeyup="handleSearchCep()" required />
                              @if($errors->has('zip_code'))
                                <div class="invalid-feedback d-block">{{ $errors->first('zip_code') }}</div>
                              @endif
                            </div>
                        </div>

                        <div class="mb-3">
                          <label for="street" class="form-label">Endereço</label>
                          <div class="col-md-12">
                            <input type="text" name="street" class="form-control" id="street" placeholder="Ex: Rua Maria" value="{{ $freightDetails['street'] ?? "" }}" readonly />
                            @if($errors->has('street'))
                              <div class="invalid-feedback d-block">{{ $errors->first('street') }}</div>
                            @endif
                          </div>
                        </div>

                        <div class="mb-3">
                          <label for="number" class="form-label">Número *</label>
                          <div class="col-md-3">
                            <input type="number" step="1" min="1" name="number" class="form-control" id="number" placeholder="Ex: 80" />
                            @if($errors->has('number'))
                              <div class="invalid-feedback d-block">{{ $errors->first('number') }}</div>
                            @endif
                          </div>
                        </div>

                        <div class="mb-3">
                          <label for="complement" class="form-label">Complemento</label>
                          <div class="col-md-6">
                            <input type="text" name="complement" class="form-control" id="complement" placeholder="Ex: Apt 200" />
                          </div>
                        </div>

                        <div class="mb-3">
                          <label for="neighborhood" class="form-label">Bairro</label>
                          <div class="col-md-6">
                            <input type="text" name="neighborhood" class="form-control" id="neighborhood" placeholder="Ex: Apt 200" value="{{ $freightDetails['neighborhood'] ?? "" }}" readonly />
                            @if($errors->has('neighborhood'))
                              <div class="invalid-feedback d-block">{{ $errors->first('neighborhood') }}</div>
                            @endif
                          </div>
                        </div>

                        <div class="mb-3">
                          <label for="city" class="form-label">Cidade</label>
                          <div class="col-md-12">
                            <input type="text" name="city" class="form-control" id="city" placeholder="Ex: São Paulo" value="{{ $freightDetails['city'] ?? "" }}" readonly />
                            @if($errors->has('city'))
                              <div class="invalid-feedback d-block">{{ $errors->first('city') }}</div>
                            @endif
                          </div>
                        </div>

                        <div class="mb-3">
                          <label for="state" class="form-label">Estado</label>
                          <div class="col-md-2">
                            <input type="text" name="state" class="form-control" id="state" placeholder="Ex: SP" value="{{ $freightDetails['state'] ?? "" }}" readonly />
                            @if($errors->has('state'))
                              <div class="invalid-feedback d-block">{{ $errors->first('state') }}</div>
                            @endif
                          </div>
                        </div>
                    </div>

                    <label>
                      <input
                        type="radio"
                        name="delivery_method"
                        data-price="0"
                        value="local"
                        onchange="handleChangeDeliveryMethod($(this))"
                        @if (isset($freightDetails['delivery_method']) && $freightDetails['delivery_method'] == 'local') checked @endif required /> Retirar no local
                    </label>
                    @if($errors->has('delivery_method'))
                      <div class="invalid-feedback d-block">{{ $errors->first('delivery_method') }}</div>
                    @endif
                    
                </div>
                <div class="col-md-5 border rounded-1 offset-md-1 p-3">
                    <h4>Seu pedido</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Adicional</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cartItems as $item)
                                    <tr>
                                      <td class="align-middle">{{ $item->name }} x <strong>{{ $item->quantity }}</strong></td>
                                      <td class="align-middle">
                                        @if ($item->attributes->additions)
                                            @foreach ($item->attributes->additions as $addition)     
                                                <span class="d-block"> <span class="badge rounded-pill bg-success">+</span> {{ $addition->addition }} + R$ {{ number_format($addition->price, 2, ",", ".") }}</span>
                                            @endforeach
                                        @endif
                                      </td>
                                      <td class="align-middle">R$ <span>{{ number_format($item->price * $item->quantity,2,",",".") }}</span></td>
                                    </tr>
                                @endforeach
                                    <tr>
                                        <td><strong>Subtotal</strong></td>
                                        <td>&nbsp;</td>
                                        <td><strong>R$ {{ number_format(Cart::getTotal(),2,",",".") }}<strong></td>
                                    </tr>
                                    <tr>
                                      <td><strong>+Frete</strong></td>
                                      <td>&nbsp;</td>
                                      <td class="tot-frete">
                                        <strong>
                                          
                                            @if ($freightDetails['delivery_method'] == "local")
                                                R$ <span>0,00</span>
                                            @elseif($freightDetails['delivery_method'] == null)
                                                R$ <span>Não informado</span>
                                            @elseif($freightDetails['delivery_method'] == "shipping")
                                                R$ <span>{{ isset($freightDetails['price']) ? number_format(($freightDetails['price']),2,",",".") : "0,00" }}</span>
                                            @endif
                                          
                                        <strong>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td><strong>Total</strong></td>
                                      <td>&nbsp;</td>
                                      <td class="tot-total">
                                        <strong>
                                          
                                            @if ($freightDetails['delivery_method'] == "local" || $freightDetails['delivery_method'] == null)
                                                R$ <span>{{ number_format((Cart::getTotal()),2,",",".") }}</span>
                                            @else
                                                R$ <span>{{ isset($freightDetails['price']) ? number_format((Cart::getTotal() + $freightDetails['price']),2,",",".") : number_format((Cart::getTotal()),2,",",".") }}</span>
                                            @endif
                                          
                                        <strong>
                                      </td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                    <h4>Informção Adicional</h4>
                    <div class="mb-3">
                      <label for="additional_information" class="form-label">Notas do pedido (opcional)</label>
                      <div class="col-md-12">
                        <textarea
                          name="additional_information"
                          class="form-control"
                          id="additional_information"
                          placeholder="Notas sobre seu pedido, por exemplo, informações especiais sobre entrega."
                          rows="5"></textarea>
                      </div>
                    </div>
                    @if ($isOpened)
                      <button class="btn btn-success w-100">Enviar pedido</button>
                    @else
                      <button type="button" class="btn btn-warning w-100">Fechado para pedidos</button>
                    @endif
                </div>
            </div>
        </form>
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
                        const result = data;
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
                                  $("#street").val(result.street);
                                  $("#number").focus();
                                  $("#neighborhood").val(result.neighborhood);
                                  $("#city").val(result.city);
                                  $("#state").val(result.state);
                                }
                                $('input[name="delivery_method"][value="shipping"]').data('price', data.price);
                                handleChangeDeliveryMethod($(null), true);
                            },
                            error: (err) => {
                                document.querySelector('.loader').style.display = 'none';
                                $('input[name="delivery_method"][value="shipping"]').data('price', 0);
                                handleChangeDeliveryMethod($(null), true);
                                $("#zip_code").val('');
                                $("#street").val('');
                                $("#number").val('');
                                $("#complement").val('');
                                $("#neighborhood").val('');
                                $("#city").val('');
                                $("#state").val('');
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Oops...',
                                    text: err.responseJSON.message,
                                })
                            }
                        });
                    })
                    .catch(err => {
                        $('input[name="delivery_method"][value="shipping"]').data('price', 0);
                        handleChangeDeliveryMethod($(null), true);
                        $("#zip_code").val('');
                        $("#street").val('');
                        $("#number").val('');
                        $("#complement").val('');
                        $("#neighborhood").val('');
                        $("#city").val('');
                        $("#state").val('');
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: err.message,
                        })
                });
            }
        }

        const configFields = (value) => {
          if( value == 'local' ) {
            $('.block_address').removeClass('d-block');
            $('.block_address').addClass('d-none');
            $("#number").removeAttr("required");
            $("#zip_code").removeAttr("required");
          }else if(value == 'shipping'){
            $('.block_address').removeClass('d-none');
            $('.block_address').addClass('d-block');
            $("#number").attr("required", true);
            $("#zip_code").attr("required", true);
          }
        }

        const handleChangeDeliveryMethod = (e, inLoadPage = false) => {

          configFields(e.val())

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
                      $("table td.tot-frete span").text(parseFloat(value).toFixed(2).replace('.', ','));
                      $("table td.tot-total span").text(parseFloat(data.totalCart + value).toFixed(2).replace('.', ','));
                      document.querySelector('.loader').style.display = 'none';
                  }
              }
          });
        }

        $( document ).ready(function() {

            configFields($('input[name="delivery_method"]:checked').val());
            
            var behavior = function (val) {
                return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
            },

            options = {
                onKeyPress: function (val, e, field, options) {
                    field.mask(behavior.apply({}, arguments), options);
                }
            };

            $('#phone').mask(behavior, options);

            $("#zip_code").mask('00000-000');
        });

    </script>
@endsection