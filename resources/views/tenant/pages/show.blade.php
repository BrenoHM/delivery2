@extends('tenant.layouts.app')

@section('title', $product->name)

@section('content')
    <div class="container product-show">
        <div class="row">
            <div class="col-md-5 text-center">
                @if ($product->photo)
                    <img src="{{$product->photo}}" alt="{{$product->name}}" class="img-fluid">    
                @else
                    <img class="img-fluid" src="{{ asset('assets/images/no-image.png') }}" alt="{{ $product->name }}">    
                @endif
            </div>
            <div class="col-md-6 offset-md-1">
                <h2>{{$product->name}}</h2>
                <h3 class="price">R$ <span>{{ $product->variations->count() ? 'selecione uma opção' : number_format($product->price,2,",",".") }}</span></h3>
                <p>{{$product->description}}</p>

                @if ($isOpened)
                    <form class="row" action="{{ route('cart.store', $tenant) }}" method="post">
                        @csrf
                        {{-- additions --}}
                        @if ($product->additions->count())
                            @foreach ($product->additions as $addition)
                                <div class="mb-2">
                                    <label>
                                        <input type="checkbox" class="addition" name="additions[]" id="" data-price="{{ $addition->price }}" value="{{ $addition->id }}" onchange="calculateProduct()">
                                        {{ $addition->addition }} por + R$ {{ number_format($addition->price, 2, ",", ".") }}
                                    </label>
                                </div>
                            @endforeach
                        @endif

                        {{-- variations --}}
                        @if ($product->variations->count())
                            <div class="row">
                                <div class="col-5 mb-2">
                                    <select name="variation_id" id="variation_id" class="col-md-3 form-control" onchange="calculateProduct()" required>
                                        <option value="">Selecione a opção</option>
                                        @foreach ($product->variations as $variation)
                                            <option value="{{ $variation->id }}" data-price="{{ $variation->price }}">
                                                {{ $variation->option->option }} - {{ number_format($variation->price, 2, ",", ".") }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="col-3">
                            <input type="number" step="1" name="quantity" id="quantity" value="1" min="1" class="form-control" onchange="calculateProduct()" required>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-success"><i class="fa-solid fa-cart-shopping"></i> Adicionar</button>    
                        </div>
                        <input type="hidden" value="{{ $product->id }}" name="id">
                        <input type="hidden" value="{{ $product->name }}" name="name">
                        <input type="hidden" value="{{ $product->price }}" name="price">
                        <input type="hidden" value="{{ $product->photo }}" name="photo">
                        <input type="hidden" id="priceProduct" value="{{ $product->price }}">
                    </form>
                @else
                    <button type="button" class="btn btn-warning">Fechado para pedidos</button>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    
    <script>

        const calculateProduct = () => {

            var total = 0;
            if( $("#variation_id :selected").val() ) {
                total = parseFloat($("#variation_id :selected").data('price'));
            }else{
                total = parseFloat($('#priceProduct').val());
            }

            $('.addition').each(function(){
                const checked = $(this).is(':checked');
                if (checked) total += parseFloat($(this).data('price'));
            });

            total = total * parseInt($('#quantity').val());
            $('h3.price span').text(total.toFixed(2).replace(".", ","));
            
        }

        $( document ).ready(function() {
            // if( $("#variation_id").length > 0 ){
            //     alert($("#variation_id :selected").val());
            // }else{
            //     alert('nao tem variation')
            // }
        });

    </script>
@endsection

