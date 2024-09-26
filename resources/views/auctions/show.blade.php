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
            <div><a href="{{ url('/') }}"><i class="fas fa-long-arrow-alt-left"></i> Főoldal</a></div>
            <div><a href="{{ url('/') }}"><i class="fas fa-long-arrow-alt-left"></i> Aukciók</a></div> {{-- might be auction.index later --}}

            <h1>Aukció {{ $auction->item->name }}</h1>

            <p class="small text-secondary mb-0">
                <i class="far fa-calendar-alt"></i>
                <span>Határidő: {{ $auction->deadline }}</span>
            </p>

            <p class="card-text mt-1"><strong>Ár:</strong> {{ $auction->price }} Ft</p>
            <p class="card-text"><strong>Leírás:</strong> {{ $auction->description }}</p>

            <hr>

            <h2>Kapcsolódó termék</h2>
            <p><strong>Név:</strong> {{ $auction->item->name }}</p>
            <p><strong>Gyártási év:</strong> {{ $auction->item->made_in }}</p>
            <p><strong>Termékleírás:</strong> {{ Str::limit($auction->item->description, 100) }} <a href="{{ route('items.show', $auction->item->id) }}">Tovább...</a></p> {{-- TODO: ÉRTELMES MEGJELENÉS! --}}

            <div class="d-flex justify-content-left mt-3">
                <img src="{{ asset($auction->item->image ? 'storage/' . $auction->item->image : 'images/no_product_image.png') }}" class="card-img-top img-fluid" alt="Item cover" style="max-width: 200px;">
            </div>

            <hr>

            <h2>Új licit</h2>
            {{-- @include('bids.create') --}}

            <div class="mt-3">
                <div class="col-12 col-md-8">
                    <h2>Licit Történet:</h2>
                </div>
                @if ($auction->bids && count($auction->bids) > 0)
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
                        <a role="button" class="btn btn-sm btn-primary" href="{{ route('auctions.edit', ['auction' => $auction->id]) }}"><i class="far fa-edit"></i> Szerkesztés</a>

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
