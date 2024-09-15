@extends('layouts.app')

{{-- @section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}

@section('content')
    <div class="container">
        <div class="col-12 col-md-8 mb-5">
            <h1>Aukció</h1>
            <p>Ezen az oldalon betekintést nyerhetnek az összes termékemre, ami alatt találhatják majd az aukcióra bocsátottakat is.</p>
        </div>
        <div class="mb-5">
            <div class="gallery js-flickity" data-flickity-options='{ "wrapAround": true }'>
                @foreach($items as $item)
                    <div class="gallery-cell">
                        <img src="{{
                            asset(
                                $item->image
                                    ? 'storage/' . $item->image
                                    : 'images/no_product_image.png'
                            )
                        }}" alt="Gallery Image" class="img-fluid gallery-img">
                    </div>
                @endforeach
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="row">
                    <h2>Jelenleg <b>{{ $auction_count }} ékszer</b> van aukcióra bocsátva</h2>
                    @forelse ($auction_items as $item)
                        <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex align-self-stretch">
                            <div class="card w-100">
                                <img
                                src="{{
                                    asset(
                                        $item->image
                                            ? 'storage/' . $item->image
                                            : 'images/no_product_image.png'
                                    )
                                }}"
                                    class="card-img-top"
                                    alt="Item cover"
                                >
                                <div class="card-body">
                                    <h5 class="card-title mb-0"> {{ $item->name }} </h5>
                                    <p class="small mb-0">
                                        <span>
                                            <i class="far fa-calendar-alt"></i>
                                            <span> {{ $item->made_in }} </span>
                                        </span>
                                    </p>

                                    @foreach ($item->labels as $label)
                                        <a href="{{ route('labels.show', $label) }}" class="text-decoration-none">
                                            <span style="background-color: {{ $label->color }}; color: #ffffff; border-radius: 6px; padding: 1px;">{{ $label->name }}</span>
                                        </a>
                                    @endforeach

                                    {{-- TODO: can be a link --}}
                                    @if ( strlen($item->description) > 100 )
                                        <p class="card-text mt-1"> {{ substr($item->description, 0, 100) }}... </p>
                                    @else
                                        <p class="card-text mt-1"> {{ substr($item->description, 0, 100) }} </p>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    {{-- TODO: Link --}}
                                    <a href="{{ route('items.show', $item) }}" class="btn btn-info">
                                        <span>Részletek</span> <i class="fas fa-angle-right"></i>
                                    </a>
                                    <a href="{{ route('items.show', $item) }}" class="btn btn-outline-primary">
                                        <span>Licitálok</span> <i class="fas fa-angle-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning" role="alert">
                                Nem találhatóak ékszerek.
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-center">
                    {{-- TODO: Pagination --}}
                </div>

            </div>
        </div>
    </div>
@endsection
