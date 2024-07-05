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
        <div class="row mt-3">
            <div class="col-12 col-lg-9">
                <div class="row">
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
                                    <a href="{{ route('items.show', $item) }}" class="btn btn-primary">
                                        <span>Részletek</span> <i class="fas fa-angle-right"></i>
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
            <div class="col-12 col-lg-3">
                <div class="row">
                    {{-- TODO: Filtering --}}

                    @auth
                        @if(auth()->user()->is_admin)
                            <div class="col-12 mb-3">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        Statisztika
                                    </div>
                                    <div class="card-body">
                                        <div class="small">
                                            <ul class="fa-ul">
                                                <li><span class="fa-li"><i class="fas fa-user"></i></span>Felhasználók: {{ $user_count }}</li>
                                                <li><span class="fa-li"><i class="fas fa-layer-group"></i></span>Címkék: {{ $label_count }}</li>
                                                <li><span class="fa-li"><i class="fas fa-file-alt"></i></span>Ékszerek: {{ $item_count }}</li>
                                                <li><span class="fa-li"><i class="fas fa-fire"></i></span>Aukción: {{ $auction_count }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>

            </div>
        </div>
    </div>
@endsection
