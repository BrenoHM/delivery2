@extends('tenant.layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="container checkout-page">
        <h2 class="border-bottom mb-3">Finalização de compra</h2>
        <form action="{{ route('checkout.store', $tenant) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 border rounded-1 p-3">
                    <h4>Detalhes de faturamento</h4>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome *</label>
                        <div class="col-md-12">
                          <input type="text" name="name" class="form-control" id="name" placeholder="Ex: João" required />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefone *</label>
                        <div class="col-md-6">
                          <input type="text" name="phone" class="form-control" id="phone" placeholder="Ex: (99) 99999-9999" required />
                        </div>
                    </div>

                    <div class="mb-3">
                      <label>
                        <input
                          type="radio"
                          name="delivery_method"
                          data-price="{{ $freightDetails['price'] ?? "" }}"
                          value="shipping"
                          onchange="handleChangeDeliveryMethod($(this))"
                          @if (isset($freightDetails['delivery_method']) && $freightDetails['delivery_method'] == 'shipping') checked @endif /> Entregar no meu endereço
                      </label>
                    </div>

                    <div class="block_address {{ isset($freightDetails['delivery_method']) && $freightDetails['delivery_method'] == 'shipping' ? 'd-block' : 'd-none' }}">
                        <div class="mb-3">
                            <label for="zip_code" class="form-label">Cep</label>
                            <div class="col-md-6">
                              <input type="text" name="zip_code" maxlength="8" class="form-control" id="zip_code" placeholder="Ex: 05010000" value="{{ $freightDetails['cep'] ?? "" }}" />
                            </div>
                        </div>

                        <div class="mb-3">
                          <label for="street" class="form-label">Endereço</label>
                          <div class="col-md-12">
                            <input type="text" name="street" class="form-control" id="street" placeholder="Ex: Rua Maria" value="{{ $freightDetails['street'] ?? "" }}" readonly />
                          </div>
                        </div>

                        <div class="mb-3">
                          <label for="number" class="form-label">Número *</label>
                          <div class="col-md-3">
                            <input type="number" step="1" min="1" name="number" class="form-control" id="number" placeholder="Ex: 80" required />
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
                          </div>
                        </div>

                        <div class="mb-3">
                          <label for="city" class="form-label">Cidade</label>
                          <div class="col-md-12">
                            <input type="text" name="city" class="form-control" id="city" placeholder="Ex: São Paulo" value="{{ $freightDetails['city'] ?? "" }}" readonly />
                          </div>
                        </div>

                        <div class="mb-3">
                          <label for="state" class="form-label">Estado</label>
                          <div class="col-md-2">
                            <input type="text" name="state" class="form-control" id="state" placeholder="Ex: São Paulo" value="{{ $freightDetails['state'] ?? "" }}" readonly />
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
                        @if (isset($freightDetails['delivery_method']) && $freightDetails['delivery_method'] == 'local') checked @endif /> Retirar no local
                    </label>
                    
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
                                              <span>Não informado</span>
                                          @else
                                              R$ <span>{{ number_format(($freightDetails['price']),2,",",".") }}</span>
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
                                              R$ <span>{{ number_format((Cart::getTotal() + $freightDetails['price']),2,",",".") }}</span>
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
                    <button class="btn btn-success w-100">Enviar pedido</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
    
    <script>

        const handleChangeDeliveryMethod = (e, inLoadPage = false) => {

          if( e.val() == 'local' ) {
            $('.block_address').removeClass('d-block');
            $('.block_address').addClass('d-none');
            $("#number").removeAttr("required");
          }else if(e.val() == 'shipping'){
            $('.block_address').removeClass('d-none');
            $('.block_address').addClass('d-block');
            $("#number").attr("required", true);
          }

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
            
            // @if(Session::has('freight_details') && Session::get('freight_details')['delivery_method'] == 'shipping')

            //     handleChangeDeliveryMethod($(null), true);
                
            // @endif
            
        });

    </script>
@endsection