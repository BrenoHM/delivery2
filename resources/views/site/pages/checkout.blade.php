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
                        @if ($trial)
                          <small class="badge bg-success d-block mt-1">Período de teste</small>
                        @endif
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
                @if ($trial)
                  <small class="text-muted">Só será cobrado após o período de {{ $trial->attributes->trial_days }} dias. O plano pode ser cancelado a qualquer momento</small>
                @endif
              </ul>
            </div>

            <div class="col-md-7 col-lg-8">
              <h4 class="mb-3">Dados do estabelecimento</h4>
              <form action="{{ route('site.checkout.process') }}" method="post" id="form">
                @csrf
                <div class="row g-3">
                  <div class="col-sm-6">
                    <label for="name" class="form-label">Nome *</label>
                    <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" required>
                    @if($errors->has('name'))
                      <div class="invalid-feedback d-block">{{ $errors->first('name') }}</div>
                    @endif
                  </div>
      
                  <div class="col-sm-6">
                    <label for="cpf" class="form-label">Cpf *</label>
                    <input type="text" name="cpf" class="form-control" id="cpf" value="{{ old('cpf') }}" required>
                    @if($errors->has('cpf'))
                      <div class="invalid-feedback d-block">{{ $errors->first('cpf') }}</div>
                    @endif
                  </div>

                  <div class="col-sm-6">
                    <label for="phone_number" class="form-label">Telefone *</label>
                    <input type="text" name="phone_number" class="form-control" id="phone_number" placeholder="(33) 93333-3333" value="{{ old('phone_number') }}" required>
                    @if($errors->has('phone_number'))
                      <div class="invalid-feedback d-block">{{ $errors->first('phone_number') }}</div>
                    @endif
                  </div>
      
                  <div class="col-sm-6">
                    <label for="birth" class="form-label">Data nascimento *</label>
                    <input type="text" name="birth" class="form-control" id="birth" value="{{ old('birth') }}" required>
                    @if($errors->has('birth'))
                      <div class="invalid-feedback d-block">{{ $errors->first('birth') }}</div>
                    @endif
                  </div>

                  <div class="col-12">
                    <label for="tenant_name" class="form-label">Nome da sua Loja *</label>
                    <input type="text" name="tenant_name" class="form-control" id="tenant_name" value="{{ old('tenant_name') }}" onchange="handleConvertSlug(this.value)" required>
                    <small class="text-muted">Nome da sua loja ficará assim: <strong class="slug-url"></strong></small>
                    @if($errors->has('tenant_name'))
                      <div class="invalid-feedback d-block">{{ $errors->first('tenant_name') }}</div>
                    @endif
                    @if($errors->has('domain'))
                      <div class="invalid-feedback d-block">{{ $errors->first('domain') }}</div>
                    @endif
                  </div>
      
                  <div class="col-12">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="seu-email@gmail.com" value="{{ old('email') }}" required>
                    @if($errors->has('email'))
                      <div class="invalid-feedback d-block">{{ $errors->first('email') }}</div>
                    @endif
                  </div>

                  <div class="col-12">
                    <label for="zip_code" class="form-label">Cep *</label>
                    <input type="text" name="zip_code" class="form-control" id="zip_code" onkeyup="handleSearchCep()" value="{{ old('zip_code') }}" required>
                    @if($errors->has('zip_code'))
                      <div class="invalid-feedback d-block">{{ $errors->first('zip_code') }}</div>
                    @endif
                  </div>
      
                  <div class="col-sm-9">
                    <label for="street" class="form-label">Endereço *</label>
                    <input type="text" name="street" class="form-control" id="street" value="{{ old('street') }}" readonly>
                    @if($errors->has('street'))
                      <div class="invalid-feedback d-block">{{ $errors->first('street') }}</div>
                    @endif
                  </div>

                  <div class="col-sm-3">
                    <label for="number" class="form-label">Número *</label>
                    <input type="text" name="number" class="form-control" id="number" value="{{ old('number') }}" required>
                    @if($errors->has('number'))
                      <div class="invalid-feedback d-block">{{ $errors->first('number') }}</div>
                    @endif
                  </div>

                  <div class="col-md-5">
                    <label for="neighborhood" class="form-label">Bairro *</label>
                    <input type="text" name="neighborhood" class="form-control" id="neighborhood" value="{{ old('neighborhood') }}" readonly>
                    @if($errors->has('neighborhood'))
                      <div class="invalid-feedback d-block">{{ $errors->first('neighborhood') }}</div>
                    @endif
                  </div>

                  <div class="col-md-5">
                    <label for="city" class="form-label">Cidade *</label>
                    <input type="text" name="city" class="form-control" id="city" value="{{ old('city') }}" readonly>
                    @if($errors->has('city'))
                      <div class="invalid-feedback d-block">{{ $errors->first('city') }}</div>
                    @endif
                  </div>

                  <div class="col-md-2">
                    <label for="state" class="form-label">Estado *</label>
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
                    <input id="credit" name="paymentMethod" value="credit" type="radio" class="form-check-input" onchange="handleChangePaymentMethod('credit')" checked required>
                    <label class="form-check-label" for="credit">Cartão de crédito</label>
                  </div>
                  @if (!$trial)
                    <div class="form-check">
                      <input id="billet" name="paymentMethod" value="billet" type="radio" class="form-check-input" onchange="handleChangePaymentMethod('billet')" required>
                      <label class="form-check-label" for="billet">Boleto</label>
                    </div>
                  @endif
                  @if($errors->has('paymentMethod'))
                      <div class="invalid-feedback d-block">{{ $errors->first('paymentMethod') }}</div>
                  @endif
                </div>
      
                <div id="block-card" class="row gy-3">
                  <div class="col-md-6">
                    <label for="cc-name" class="form-label">Nome no cartão</label>
                    <input type="text" name="cc-form" class="form-control" id="cc-name" placeholder="João S Santos" value="{{ old('cc-form') }}" required>
                    <small class="text-muted d-block">Nome completo conforme exibido no cartão</small>
                    @if($errors->has('cc-form'))
                        <div class="invalid-feedback d-block">{{ $errors->first('cc-form') }}</div>
                    @endif
                  </div>
      
                  <div class="col-md-6">
                    <label for="cc-number" class="form-label">Número do cartão</label>
                    <input type="text" name="cc-number" class="form-control" id="cc-number" value="{{ old('cc-number') }}" required>
                    @if($errors->has('cc-number'))
                        <div class="invalid-feedback d-block">{{ $errors->first('cc-number') }}</div>
                    @endif
                  </div>
      
                  <div class="col-md-4">
                    <label for="cc-expiration" class="form-label">Data expiração</label>
                    <input type="text" name="cc-expiration" class="form-control" id="cc-expiration" placeholder="03/25" value="{{ old('cc-expiration') }}" required>
                    @if($errors->has('cc-expiration'))
                        <div class="invalid-feedback d-block">{{ $errors->first('cc-expiration') }}</div>
                    @endif
                  </div>
      
                  <div class="col-md-3">
                    <label for="cc-cvv" class="form-label">CVV</label>
                    <input type="text" name="cc-cvv" class="form-control" id="cc-cvv" placeholder="155" value="{{ old('cc-cvv') }}" required>
                    @if($errors->has('cc-cvv'))
                        <div class="invalid-feedback d-block">{{ $errors->first('cc-cvv') }}</div>
                    @endif
                  </div>
                </div>
      
                @if (Cart::getTotalQuantity())
                  <hr class="my-4">
                  <button class="w-100 btn btn-primary btn-lg" type="button" id="btnComprar">Comprar</button>
                @endif

                <input type="hidden" id="payment_token" name="payment_token" />
                @if ($trial) 
                  <input type="hidden" id="trial_days" name="trial_days" value="{{ $trial->attributes->trial_days }}" />
                @endif
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

    const handleConvertSlug = (value) => {
      //alert(value)
      $.ajax({
        url: "{{ route('site.checkout.convert.slug') }}",
        data: {term: value},
        beforeSend: function() {
          document.querySelector('.loader').style.display = 'flex';
        },
        success: function(result){
          document.querySelector('.loader').style.display = 'none';
          if(result.success) {
            $("small strong.slug-url").text(result.url);
          }
        }
      });
    }

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
        $("#cc-name").prop('required', true);
        $("#cc-number").prop('required', true);
        $("#cc-expiration").prop('required', true);
        $("#cc-cvv").prop('required', true);
        $("#block-card").removeClass('d-none');
      }
    }

    $( document ).ready(function() {
      
        const rules = {
          errorClass:'error-validate',
          rules: {
            email: {
              email: true
            },
            "cc-number": {
              minlength: 19
            },
            "cc-cvv": {
              minlength: 3
            },
            "cc-expiration": {
              minlength: 5
            },
          },
          messages: {
            name: {
              required: 'Nome é obrigatório'
            },
            cpf: {
              required: 'Cpf é obrigatório'
            },
            phone_number: {
              required: 'Telefone é obrigatório'
            },
            birth: {
              required: 'Data nascimento é obrigatória'
            },
            tenant_name: {
              required: 'Nome da sua Loja é obrigatório'
            },
            email: {
              required: 'Email é obrigatório',
              email: 'Por favor insira um endereço de e-mail válido.'
            },
            zip_code: {
              required: 'Cep é obrigatório'
            },
            number: {
              required: 'Número é obrigatório'
            },
            paymentMethod: {
              required: 'Tipo de pagamento é obrigatório'
            },
            "cc-form": {
              required: 'Nome no cartão é obrigatório'
            },
            "cc-number": {
              required: 'Número do cartão é obrigatório',
              minlength: 'Formato inválido'
            },
            "cc-expiration": {
              required: 'Data expiração é obrigatório',
              minlength: 'Formato inválido'
            },
            "cc-cvv": {
              required: 'Código é obrigatório',
              minlength: 'Mínimo 3 caracteres'
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
        $("#cc-number").mask('0000-0000-0000-0000');
        $("#cc-expiration").mask('00/00');
        $("#cc-cvv").mask('000');

        $gn.ready(function (checkout) {

            $("#btnComprar").on('click', function(){

              var form = $( "#form" );
              form.validate(rules);

              if( form.valid() ) {
                if( $("input[name='paymentMethod']:checked").val() == 'credit' ){
                  document.querySelector('.loader').style.display = 'flex';
                  const cardNumber = $("#cc-number").val();
                  const expiration = $("#cc-expiration").val().split('/');

                  checkout.getPaymentToken(
                    {
                        // brand: 'Visa', // bandeira do cartão
                        // number: '4012001038443335', // número do cartão
                        // cvv: '123', // código de segurança
                        // expiration_month: '05', // mês de vencimento
                        // expiration_year: '2021' // ano de vencimento
                        brand: GetCardType(cardNumber), // bandeira do cartão
                        number: cardNumber, // número do cartão
                        cvv: $("#cc-cvv").val(), // código de segurança
                        expiration_month: expiration[0], // mês de vencimento
                        expiration_year: expiration[1] // ano de vencimento
                    },
                    function (error, response) {
                        if (error) {
                            // Trata o erro ocorrido
                            document.querySelector('.loader').style.display = 'none';
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: error.error_description,
                            })
                        } else {
                            // Trata a resposta
                            document.querySelector('.loader').style.display = 'none';
                            $("#payment_token").val(response.data.payment_token);
                            form.submit();
                        }
                    }
                  );
                }else{
                  //billet send form
                  form.submit();
                }
              }     
            });

        });

    });

    function GetCardType(number)
    {
        // visa
        var re = new RegExp("^4");
        if (number.match(re) != null)
            return "visa";

        // Mastercard 
        // Updated for Mastercard 2017 BINs expansion
        if (/^(5[1-5][0-9]{14}|2(22[1-9][0-9]{12}|2[3-9][0-9]{13}|[3-6][0-9]{14}|7[0-1][0-9]{13}|720[0-9]{12}))$/.test(number)) 
            return "mastercard";

        // AMEX
        re = new RegExp("^3[47]");
        if (number.match(re) != null)
            return "amex";

        // Discover
        re = new RegExp("^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)");
        if (number.match(re) != null)
            return "discover";

        // Diners
        re = new RegExp("^36");
        if (number.match(re) != null)
            return "diners";

        // Diners - Carte Blanche
        re = new RegExp("^30[0-5]");
        if (number.match(re) != null)
            return "Diners - Carte Blanche";

        // JCB
        re = new RegExp("^35(2[89]|[3-8][0-9])");
        if (number.match(re) != null)
            return "JCB";

        // Visa Electron
        re = new RegExp("^(4026|417500|4508|4844|491(3|7))");
        if (number.match(re) != null)
            return "Visa Electron";

        return "";
    }
</script>
@endsection

