@extends('layouts.app')
@section('title', $auction->item->name)

@section('content')
<div class="container">
    @if (Session::has('auction_updated'))
        <div class="alert alert-success" role="alert">
            Aukció frissítve: {{ Session::get('auction_updated')->item->name }}.
        </div>
    @endif

    {{-- @if (Session::has('bid_created'))
        <div class="alert alert-success" role="alert">
            Licit sikeresen létrehozva.
        </div>
    @endif --}}

    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            <h1 class="text-center mb-4">Aukció adatai</h1>
            <h2 class="text-center mb-4">Név: <strong>{{ $auction->item->name }}</strong></h2>

            @if ($auction->opened)
                <h3 class="text-center">Határidő: <span class="badge bg-success"> {{ $auction->deadline }} </span></h3>

                <h3 class="text-center mb-4">Jelenlegi ár: <strong>{{ $auction->price }} Ft*</strong></h3>
                <p class="text-center">* Mindig frissítse az oldalt az aktuális árért!</p>
            @else
                <h3 class="text-center"><span class="badge bg-danger"> Eladva </span></h3>

                <h3 class="text-center mb-4"><strong>{{ $auction->price }} Ft</strong></h3>
            @endif

            <p class="text-center"> {{ $auction->description }} </p>

            <hr>

            <h1 class="text-center mb-4">Kapcsolódó termék adatai</h1>

            <p class="text-center small text-secondary mb-2">
                <i class="far fa-calendar-alt"></i>
                <span>{{ $auction->item->made_in }}</span>
            </p>

            <div class="text-center mb-4">
                Címkék:
                @foreach ($auction->item->labels as $label)
                    <a href="{{ route('labels.show', $label) }}" class="text-decoration-none">
                        <span style="background-color: {{ $label->color }}; color: #ffffff; border-radius: 6px; padding: 1px;">{{ $label->name }}</span>
                    </a>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mb-4">
                <img src="{{
                        asset(
                            $auction->item->image
                                ? 'storage/' . $auction->item->image
                                : 'images/no_product_image.png'
                        )
                    }}"
                    class="card-img-top img-fluid"
                    alt="Item cover"
                    style="max-width: 400px;"
                >
            </div>

            @if ($auction->item->images and count($auction->item->images) > 0)
            <div class="mt-3 mb-4">
                <div class="col-12 col-md-8">
                    <h2>További képek:</h2>
                </div>

                <div class="row">
                    @foreach($auction->item->images as $image)
                        <div class="col-md-4">
                            <img src="{{ asset('images/' . $image->path) }}" alt="Kép" class="img-fluid img-thumbnail" style="height: auto; max-height: 200px;">
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <p class="text-center mb-4"> {{ $auction->item->description }} </p>

            <div class="text-center">
                <a href="{{ route('items.show', ['item' => $auction->item->id]) }}" role="button" class="btn btn-sm btn-primary"></i> Részletesebben <i class="fas fa-angle-right"></i></a>
            </div>

            <hr>

            <h2>Új licit</h2>
            {{-- @include('bids.create') --}}

            <div class="mt-3">
                <div class="col-12 col-md-8">
                    <h2>Licit Történet:</h2>
                </div>
                @if ($auction->bids and count($auction->bids) > 0)
                    @foreach ($auction->bids as $bid)
                        <p>{{ $bid->user->name }} licitált: {{ $bid->amount }} Ft</p>
                    @endforeach
                @else
                    <p><em>Még nincsenek licitek.</em></p>
                @endif
            </div>

        </div>

        <div class="col-12 col-md-4">
            <div class="float-lg-end">
                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('auctions.edit', ['auction' => $auction->id]) }}" role="button" class="btn btn-sm btn-primary"><i class="far fa-edit"></i> Szerkesztés</a>

                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delete-confirm-modal"><i class="far fa-trash-alt"></i>
                            <span> Törlés</span>
                        </button>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="delete-confirm-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Törlés megerősítése</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Biztosan törölni akarja az ehhez tartozó aukciót <strong>{{ $auction->item->name }}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                    <button
                        type="button"
                        class="btn btn-danger"
                        onclick="document.getElementById('delete-post-form').submit();"
                    >
                        Igen, biztosan törölje
                    </button>

                    <form id="delete-post-form" action="{{ route('auctions.destroy', $auction) }}" method="POST" class="d-none">
                        @method('DELETE')
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
    </br>


</div>
@endsection
