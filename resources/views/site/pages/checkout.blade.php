@extends('site.layouts.app')

@section('title', 'Home')

@section('content')
    <div class="checkout container">
        <h2>Finalizando pedido</h2>
        <div class="row g-5">

            <div class="col-md-5 col-lg-4 order-md-last">
              <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-primary">Your cart</span>
                <span class="badge bg-primary rounded-pill">3</span>
              </h4>
              <ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between lh-sm">
                  <div>
                    <h6 class="my-0">Product name</h6>
                    <small class="text-muted">Brief description</small>
                  </div>
                  <span class="text-muted">$12</span>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-sm">
                  <div>
                    <h6 class="my-0">Second product</h6>
                    <small class="text-muted">Brief description</small>
                  </div>
                  <span class="text-muted">$8</span>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-sm">
                  <div>
                    <h6 class="my-0">Third item</h6>
                    <small class="text-muted">Brief description</small>
                  </div>
                  <span class="text-muted">$5</span>
                </li>
                <li class="list-group-item d-flex justify-content-between bg-light">
                  <div class="text-success">
                    <h6 class="my-0">Promo code</h6>
                    <small>EXAMPLECODE</small>
                  </div>
                  <span class="text-success">−$5</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <span>Total (USD)</span>
                  <strong>$20</strong>
                </li>
              </ul>
      
              <form class="card p-2">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Promo code">
                  <button type="submit" class="btn btn-secondary">Redeem</button>
                </div>
              </form>
            </div>

            <div class="col-md-7 col-lg-8">
              <h4 class="mb-3">Dados do estabelecimento</h4>
              <form class="needs-validation" novalidate>
                <div class="row g-3">
                  <div class="col-sm-6">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="name" placeholder="" value="" required>
                    <div class="invalid-feedback">
                      Valid first name is required.
                    </div>
                  </div>
      
                  <div class="col-sm-6">
                    <label for="cpf" class="form-label">Cpf</label>
                    <input type="text" name="cpf" class="form-control" id="cpf" placeholder="" value="" required>
                    <div class="invalid-feedback">
                      Valid last name is required.
                    </div>
                  </div>

                  <div class="col-sm-6">
                    <label for="phone_number" class="form-label">Telefone</label>
                    <input type="text" name="phone_number" class="form-control" id="phone_number" placeholder="(33) 93333-3333" value="" required>
                    <div class="invalid-feedback">
                      Valid first name is required.
                    </div>
                  </div>
      
                  <div class="col-sm-6">
                    <label for="birth" class="form-label">Data nascimento</label>
                    <input type="text" class="form-control" id="birth" placeholder="" value="" required>
                    <div class="invalid-feedback">
                      Valid last name is required.
                    </div>
                  </div>
      
                  <div class="col-12">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="seu-email@gmail.com" required>
                    <div class="invalid-feedback">
                      Please enter a valid email address for shipping updates.
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="zip_code" class="form-label">Cep</label>
                    <input type="text" name="zip_code" class="form-control" id="zip_code" placeholder="" onkeyup="handleSearchCep()" required>
                    <div class="invalid-feedback">
                      Zip code required.
                    </div>
                  </div>
      
                  <div class="col-sm-9">
                    <label for="street" class="form-label">Endereço</label>
                    <input type="text" name="street" class="form-control" id="street" placeholder="" required readonly>
                    <div class="invalid-feedback">
                      Please enter your shipping address.
                    </div>
                  </div>

                  <div class="col-sm-3">
                    <label for="number" class="form-label">Número</label>
                    <input type="text" name="number" class="form-control" id="number" placeholder="" required>
                    <div class="invalid-feedback">
                      Please enter your shipping address.
                    </div>
                  </div>

                  <div class="col-md-5">
                    <label for="neighborhood" class="form-label">Bairro</label>
                    <input type="text" name="neighborhood" class="form-control" id="neighborhood" required readonly>
                    <div class="invalid-feedback">
                      Please enter your shipping address.
                    </div>
                  </div>

                  <div class="col-md-5">
                    <label for="city" class="form-label">Cidade</label>
                    <input type="text" name="city" class="form-control" id="city" required readonly>
                    <div class="invalid-feedback">
                      Please enter your shipping address.
                    </div>
                  </div>

                  <div class="col-md-2">
                    <label for="state" class="form-label">Estado</label>
                    <input type="text" name="state" class="form-control" id="state" required readonly>
                    <div class="invalid-feedback">
                      Please enter your shipping address.
                    </div>
                  </div>
                </div>
      
                <hr class="my-4">
      
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="same-address">
                  <label class="form-check-label" for="same-address">Shipping address is the same as my billing address</label>
                </div>
      
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="save-info">
                  <label class="form-check-label" for="save-info">Save this information for next time</label>
                </div>
      
                <hr class="my-4">
      
                <h4 class="mb-3">Payment</h4>
      
                <div class="my-3">
                  <div class="form-check">
                    <input id="credit" name="paymentMethod" type="radio" class="form-check-input" checked required>
                    <label class="form-check-label" for="credit">Credit card</label>
                  </div>
                  <div class="form-check">
                    <input id="debit" name="paymentMethod" type="radio" class="form-check-input" required>
                    <label class="form-check-label" for="debit">Debit card</label>
                  </div>
                  <div class="form-check">
                    <input id="paypal" name="paymentMethod" type="radio" class="form-check-input" required>
                    <label class="form-check-label" for="paypal">PayPal</label>
                  </div>
                </div>
      
                <div class="row gy-3">
                  <div class="col-md-6">
                    <label for="cc-name" class="form-label">Name on card</label>
                    <input type="text" class="form-control" id="cc-name" placeholder="" required>
                    <small class="text-muted">Full name as displayed on card</small>
                    <div class="invalid-feedback">
                      Name on card is required
                    </div>
                  </div>
      
                  <div class="col-md-6">
                    <label for="cc-number" class="form-label">Credit card number</label>
                    <input type="text" class="form-control" id="cc-number" placeholder="" required>
                    <div class="invalid-feedback">
                      Credit card number is required
                    </div>
                  </div>
      
                  <div class="col-md-3">
                    <label for="cc-expiration" class="form-label">Expiration</label>
                    <input type="text" class="form-control" id="cc-expiration" placeholder="" required>
                    <div class="invalid-feedback">
                      Expiration date required
                    </div>
                  </div>
      
                  <div class="col-md-3">
                    <label for="cc-cvv" class="form-label">CVV</label>
                    <input type="text" class="form-control" id="cc-cvv" placeholder="" required>
                    <div class="invalid-feedback">
                      Security code required
                    </div>
                  </div>
                </div>
      
                <hr class="my-4">
      
                <button class="w-100 btn btn-primary btn-lg" type="submit">Continue to checkout</button>
              </form>
            </div>
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

    (() => {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }

            form.classList.add('was-validated')
            }, false)
        })
    })()

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

    $( document ).ready(function() {
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
    });
</script>
@endsection

