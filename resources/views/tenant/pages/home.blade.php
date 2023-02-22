@extends('tenant.layouts.app')

@section('title', 'Home')

@section('content')
    <section class="banner d-flex align-items-center justify-content-center" style="background: url({{ asset('assets/images/banner.jpg') }})">
        <div class="tenant-information">
            <h1 class="text-center">{{$tenant->user->name}}</h1>
            <p class="text-center">
                @if ( $tenant->street && $tenant->number && $tenant->neighborhood )
                    {{$tenant->street}}, {{$tenant->number}} - {{$tenant->neighborhood}}    
                @endif
            </p>
            <p class="text-center">
                @if ( $tenant->zip_code && $tenant->city && $tenant->state )
                    {{$tenant->zip_code}} - {{$tenant->city}}/{{$tenant->state}}
                @endif
            </p>
            <p class="text-center mb-0">
                <button class="btn {{ $isOpened ? 'btn-success' : 'btn-warning' }}">
                    {{ $isOpened ? 'Aberto agora' : 'Fechado para pedidos' }}
                </button>
            </p>
        </div>
    </section>

    
    <section class="categories pt-1">
        <ul class="nav nav-tabs container" id="myTab" role="tablist">

            @foreach ($categories as $index => $categorie)
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link {{ $index == 0 ? "active" : "" }}"
                        id="cat-{{$categorie->id}}-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#cat-{{$categorie->id}}-tab-pane"
                        type="button"
                        role="tab"
                        aria-controls="cat-{{$categorie->id}}-tab-pane"
                        aria-selected="{{ $index == 0 ? "true" : "false" }}"
                        @if (count($categorie->products) <= 0) disabled @endif>
                        {{ $categorie->categorie }}
                    </button>
                </li>
            @endforeach

            {{-- <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Profile</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Contact</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="disabled-tab" data-bs-toggle="tab" data-bs-target="#disabled-tab-pane" type="button" role="tab" aria-controls="disabled-tab-pane" aria-selected="false" disabled>Disabled</button>
            </li> --}}
        </ul>
    </section>

    <div class="tab-content container container-list-products">
        @foreach ($categories as $index => $categorie)
            <div class="p-2 tab-pane fade {{ $index == 0 ? "show active" : "" }}" id="cat-{{$categorie->id}}-tab-pane" role="tabpanel" aria-labelledby="cat-{{$categorie->id}}-tab" tabindex="0">
                @if (count($categorie->products) > 0)
                    <div class="row">
                        @foreach($categorie->products as $product)
                        
                            <div class="col-md-3 text-center">
                                <a href="/product/{{$product->id}}">
                                    @if ($product->photo)
                                        <img class="img-fluid" src="{{ $product->photo }}" alt="{{ $product->name }}">
                                    @else
                                        <img class="img-fluid" src="{{ asset('assets/images/no-image.png') }}" alt="{{ $product->name }}">
                                    @endif
                                    <h3 class="mt-2">{{ $product->name }}</h3>
                                    <p>{{ $product->description }}</p>
                                    <p>R$ {{ number_format($product->price, 2, ",", ".") }}</p>
                                </a>
                            </div>
                        
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
        {{-- <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">...</div>
        <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">Contact</div>
        <div class="tab-pane fade" id="disabled-tab-pane" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">...</div> --}}
    </div>

@endsection

@section('js')
<script>
    // $( document ).ready(function() {
    //     console.log( "ready!" );
    //     $.ajax({
    //         method: 'GET',
    //         url: "{{route('category.index', ['json'=> true])}}",
    //         success: (data) => { 
    //             alert('foi')
    //         }
    //     })
    // });
</script>
@endsection

