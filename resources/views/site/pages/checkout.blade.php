@extends('site.layouts.app')

@section('title', 'Home')

@section('content')
    <div class="checkout container">
        <h2>Finalizando pedido</h2>
        <div class="row g-5">

            <div class="col-md-5 col-lg-4 order-md-last">
              <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-primary">Seu pedido</span>
                <span class="badge bg-primary rounded-pill">{{ Cart::getTotalQuantity() }}</span>
              </h4>
              <ul class="list-group mb-3">

                @if ($cartItems)

                @foreach ($cartItems as $item)
                  <li class="list-group-item d-flex justify-content-between lh-sm">
                    <div>
                      <h6 class="my-0">{{ $item->name }}</h6>
                      <small class="text-muted">{{$item->attributes->plan_item_name}}</small>
                    </div>
                    <span class="text-muted">R$ {{ number_format($item->price, 2, ",", ".") }}</span>
                    <form action="" method="post">
                      @csrf
                      @method('DELETE')
                      <button class="border-0 bg-transparent" title="Remover Plano"><i class="fa-solid fa-trash"></i></button>
                      <input type="hidden" name="id" value="{{ $item->id }}" />
                    </form>
                  </li>
                @endforeach
                    
                @endif
                
                <li class="list-group-item d-flex justify-content-between">
                  <span>Total (R$)</span>
                  <strong>R$ {{ number_format($item->price ?? 0, 2, ",", ".") }}</strong>
                </li>
              </ul>
            </div>

            <div class="col-md-7 col-lg-8">
              <h4 class="mb-3">Dados do estabelecimento</h4>
              <form action="{{ route('site.checkout.process') }}" method="post" id="form">
                @csrf
                <div class="row g-3">
                  <div class="col-sm-6">
                    <label for="name" class="form-label">Nome *</label>
                    <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" >
                    @if($errors->has('name'))
                      <div class="invalid-feedback d-block">{{ $errors->first('name') }}</div>
                    @endif
                  </div>
      
                  <div class="col-sm-6">
                    <label for="cpf" class="form-label">Cpf *</label>
                    <input type="text" name="cpf" class="form-control" id="cpf" value="{{ old('cpf') }}" >
                    @if($errors->has('cpf'))
                      <div class="invalid-feedback d-block">{{ $errors->first('cpf') }}</div>
                    @endif
                  </div>

                  <div class="col-sm-6">
                    <label for="phone_number" class="form-label">Telefone *</label>
                    <input type="text" name="phone_number" class="form-control" id="phone_number" placeholder="(33) 93333-3333" value="{{ old('phone_number') }}" >
                    @if($errors->has('phone_number'))
                      <div class="invalid-feedback d-block">{{ $errors->first('phone_number') }}</div>
                    @endif
                  </div>
      
                  <div class="col-sm-6">
                    <label for="birth" class="form-label">Data nascimento *</label>
                    <input type="text" name="birth" class="form-control" id="birth" value="{{ old('birth') }}" >
                    @if($errors->has('birth'))
                      <div class="invalid-feedback d-block">{{ $errors->first('birth') }}</div>
                    @endif
                  </div>

                  <div class="col-12">
                    <label for="tenant_name" class="form-label">Nome da sua Loja *</label>
                    <input type="text" name="tenant_name" class="form-control" id="tenant_name" value="{{ old('tenant_name') }}" >
                    <small class="text-muted">Nome da sua loja ficará assim: <strong>www.loja.com.br</strong></small>
                    @if($errors->has('tenant_name'))
                      <div class="invalid-feedback d-block">{{ $errors->first('tenant_name') }}</div>
                    @endif
                    @if($errors->has('domain'))
                      <div class="invalid-feedback d-block">{{ $errors->first('domain') }}</div>
                    @endif
                  </div>
      
                  <div class="col-12">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="seu-email@gmail.com" value="{{ old('email') }}" >
                    @if($errors->has('email'))
                      <div class="invalid-feedback d-block">{{ $errors->first('email') }}</div>
                    @endif
                  </div>

                  <div class="col-12">
                    <label for="zip_code" class="form-label">Cep</label>
                    <input type="text" name="zip_code" class="form-control" id="zip_code" onkeyup="handleSearchCep()" value="{{ old('zip_code') }}" >
                    @if($errors->has('zip_code'))
                      <div class="invalid-feedback d-block">{{ $errors->first('zip_code') }}</div>
                    @endif
                  </div>
      
                  <div class="col-sm-9">
                    <label for="street" class="form-label">Endereço</label>
                    <input type="text" name="street" class="form-control" id="street" value="{{ old('street') }}" readonly>
                    @if($errors->has('street'))
                      <div class="invalid-feedback d-block">{{ $errors->first('street') }}</div>
                    @endif
                  </div>

                  <div class="col-sm-3">
                    <label for="number" class="form-label">Número</label>
                    <input type="text" name="number" class="form-control" id="number" value="{{ old('number') }}" >
                    @if($errors->has('number'))
                      <div class="invalid-feedback d-block">{{ $errors->first('number') }}</div>
                    @endif
                  </div>

                  <div class="col-md-5">
                    <label for="neighborhood" class="form-label">Bairro</label>
                    <input type="text" name="neighborhood" class="form-control" id="neighborhood" value="{{ old('neighborhood') }}" readonly>
                    @if($errors->has('neighborhood'))
                      <div class="invalid-feedback d-block">{{ $errors->first('neighborhood') }}</div>
                    @endif
                  </div>

                  <div class="col-md-5">
                    <label for="city" class="form-label">Cidade</label>
                    <input type="text" name="city" class="form-control" id="city" value="{{ old('city') }}" readonly>
                    @if($errors->has('city'))
                      <div class="invalid-feedback d-block">{{ $errors->first('city') }}</div>
                    @endif
                  </div>

                  <div class="col-md-2">
                    <label for="state" class="form-label">Estado</label>
                    <input type="text" name="state" class="form-control" id="state" value="{{ old('state') }}" readonly>
                    @if($errors->has('state'))
                      <div class="invalid-feedback d-block">{{ $errors->first('state') }}</div>
                    @endif
                  </div>
                </div>
      
                <hr class="my-4">
      
                {{-- <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="same-address">
                  <label class="form-check-label" for="same-address">Shipping address is the same as my billing address</label>
                </div>
      
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="save-info">
                  <label class="form-check-label" for="save-info">Save this information for next time</label>
                </div>
      
                <hr class="my-4"> --}}
      
                <h4 class="mb-3">Tipo de pagamento</h4>
      
                <div class="my-3">
                  <div class="form-check">
                    <input id="credit" name="paymentMethod" type="radio" class="form-check-input" onchange="handleChangePaymentMethod('credit')" checked required>
                    <label class="form-check-label" for="credit">Cartão de crédito</label>
                  </div>
                  <div class="form-check">
                    <input id="billet" name="paymentMethod" type="radio" class="form-check-input" onchange="handleChangePaymentMethod('billet')" required>
                    <label class="form-check-label" for="billet">Boleto</label>
                  </div>
                </div>
      
                <div id="block-card" class="row gy-3">
                  <div class="col-md-6">
                    <label for="cc-name" class="form-label">Nome no cartão</label>
                    <input type="text" name="cc-form" class="form-control" id="cc-name" placeholder="João S Santos"  />
                    <small class="text-muted d-block">Nome completo conforme exibido no cartão</small>
                    <div class="invalid-feedback">
                      Name on card is required
                    </div>
                  </div>
      
                  <div class="col-md-6">
                    <label for="cc-number" class="form-label">Número do cartão</label>
                    <input type="text" class="form-control" id="cc-number" placeholder="" >
                    <div class="invalid-feedback">
                      Credit card number is required
                    </div>
                  </div>
      
                  <div class="col-md-3">
                    <label for="cc-expiration" class="form-label">Data expiração</label>
                    <input type="text" class="form-control" id="cc-expiration" placeholder="03/25" >
                    <div class="invalid-feedback">
                      Data expiração
                    </div>
                  </div>
      
                  <div class="col-md-3">
                    <label for="cc-cvv" class="form-label">CVV</label>
                    <input type="text" class="form-control" id="cc-cvv" placeholder="155" >
                    <div class="invalid-feedback">
                      Security code required
                    </div>
                  </div>
                </div>
      
                @if (Cart::getTotalQuantity())
                  <hr class="my-4">
                  <button class="w-100 btn btn-primary btn-lg" type="button" id="btnComprar">Comprar</button>
                @endif

                <input type="hidden" id="payment_token" name="payment_token" />
              </form>
            </div>
        </div>
    </div>
@endsection

@section('load-js')
    <script src="https://cdn.jsdelivr.net/npm/cep-promise/dist/cep-promise.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/jquery.mask.min.js') }}"></script>
    <!-- Inclusão do Plugin jQuery Validation-->
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

    <script type='text/javascript'>var s=document.createElement('script');s.type='text/javascript';var v=parseInt(Math.random()*1000000);s.src='https://sandbox.gerencianet.com.br/v1/cdn/1e1c8dae72cf3333a03d4ed331c97a92/'+v;s.async=false;s.id='1e1c8dae72cf3333a03d4ed331c97a92';if(!document.getElementById('1e1c8dae72cf3333a03d4ed331c97a92')){document.getElementsByTagName('head')[0].appendChild(s);};$gn={validForm:true,processed:false,done:{},ready:function(fn){$gn.done=fn;}};</script>

    {{-- js production --}}
    {{-- <script type='text/javascript'>var s=document.createElement('script');s.type='text/javascript';var v=parseInt(Math.random()*1000000);s.src='https://api.gerencianet.com.br/v1/cdn/1e1c8dae72cf3333a03d4ed331c97a92/'+v;s.async=false;s.id='1e1c8dae72cf3333a03d4ed331c97a92';if(!document.getElementById('1e1c8dae72cf3333a03d4ed331c97a92')){document.getElementsByTagName('head')[0].appen --}}
@endsection

@section('js')
<script>

    const handleSearchCep = () => {
        const value = document.querySelector("#zip_code").value;
        if( value.length == 9 ) {
            document.querySelector('.loader').style.display = 'flex';
            cep(value)
                .then(data => {
                    const result = data;
                    $("#street").val(result.street);
                    $("#number").focus();
                    $("#neighborhood").val(result.neighborhood);
                    $("#city").val(result.city);
                    $("#state").val(result.state);
                    document.querySelector('.loader').style.display = 'none';
                })
                .catch(err => {
                    document.querySelector('.loader').style.display = 'none';
                    $("#zip_code").val('');
                    $("#street").val('');
                    $("#number").val('');
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

    const handleChangePaymentMethod = (method) => {
      if( method == 'billet' ) {
        $("#cc-name").removeAttr('required');
        $("#cc-number").removeAttr('required');
        $("#cc-expiration").removeAttr('required');
        $("#cc-cvv").removeAttr('required');
        $("#block-card").addClass('d-none');
      }else{
        $("#block-card").removeClass('d-none');
      }
    }

    $( document ).ready(function() {

      const rules = {
        errorClass:'error-validate',
        rules: {
          name
        },
        messages: {
          name: {
            required: 'Nome é Obrigatório'
          },
          cpf: {
            required: 'Cpf é Obrigatório'
          },
          phone_number: {
            required: 'Telefone é Obrigatório'
          }
        }
      }
      
        var behavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },

        options = {
            onKeyPress: function (val, e, field, options) {
                field.mask(behavior.apply({}, arguments), options);
            }
        };

        $('#cpf').mask('000.000.000-00');
        $('#phone_number').mask(behavior, options);
        $('#birth').mask('00/00/0000');
        $("#zip_code").mask('00000-000');
        $("#cc-number").mask('00000-0000-0000-0000');
        $("#cc-expiration").mask('00/00');
        $("#cc-cvv").mask('000');

        $gn.ready(function (checkout) {

            $("#btnComprar").on('click', function(){

              // var form = $( "#form" );
              // form.validate(rules);

              //if( form.valid() ) {
                document.querySelector('.loader').style.display = 'flex';
                checkout.getPaymentToken(
                  {
                      brand: 'visa', // bandeira do cartão
                      number: '4012001038443335', // número do cartão
                      cvv: '123', // código de segurança
                      expiration_month: '05', // mês de vencimento
                      expiration_year: '2021' // ano de vencimento
                  },
                  function (error, response) {
                      if (error) {
                          // Trata o erro ocorrido
                          console.error(error);
                          document.querySelector('.loader').style.display = 'none';
                      } else {
                          // Trata a resposta
                          console.log(response);
                          document.querySelector('.loader').style.display = 'none';
                          $("#payment_token").val(response.data.payment_token);
                          form.submit();
                          
                      }
                  }
                );
              //}     
            });

        });

    });
</script>
@endsection

