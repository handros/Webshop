@extends('layouts.app')
@section('title', $auction->item->name)

@section('content')
<div class="container">
    @if (Session::has('auction_updated'))
        <div class="alert alert-success" role="alert">
            Aukció frissítve: {{ Session::get('auction_updated')->item->name }}.
        </div>
    @endif

    @if (Session::has('bid_created'))
        <div class="alert alert-success" role="alert">
            Ön {{ Session::get('bid_created')->amount }} Ft értékben licitált.
        </div>
    @endif

    @if (Session::has('message_created'))
        <div class="alert alert-success" role="alert">
            Üzenet elküldve.
        </div>
    @endif

    @if (Session::has('bid_deleted'))
        <div class="alert alert-success" role="alert">
            Licit sikeresen törölve.
        </div>
    @endif

    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            <h1 class="text-center mb-4">Aukció adatai</h1>
            <h2 class="text-center mb-4">Név: <strong>{{ $auction->item->name }}</strong></h2>

            @if ($open)
                <h3 class="text-center">Határidő: <span class="badge bg-success"> {{ $auction->deadline->endOfDay() }} </span></h3>
                <h3 class="text-center mb-4">Jelenlegi ár: <strong>{{ $highestBid }} Ft*</strong></h3>
                <p class="text-center">* Mindig frissítse az oldalt az aktuális árért!</p>
            @elseif ($no_bid_over)
                <h3 class="text-center"><span class="badge bg-danger"> Vége az aukciónak* </span></h3>
                <p class="text-center">* Az aukció újranyitásáért figyelje a főoldalt, vagy iratkozzon fel a hírlevelekre!</p> {{-- TODO: FELIRATKOZÁS HÍRLEVÉLRE BUTTON --}}
                <h3 class="text-center mb-4"><strong>{{ $highestBid }} Ft</strong></h3>
            @elseif ($bought)
                <h3 class="text-center"><span class="badge bg-success"> Öné a termék* </span></h3>
                <p class="text-center">* Hamarosan felveszem Önnel a kapcsolatot a részletekért!</p>
                <h3 class="text-center mb-4"><strong>{{ $highestBid }} Ft</strong></h3>
            @else
                <h3 class="text-center"><span class="badge bg-danger"> Eladva </span></h3>
                <h3 class="text-center mb-4"><strong>{{ $highestBid }} Ft</strong></h3>
            @endif

            <p class="text-center"> {{ $auction->description }} </p>

            <hr>

            <h1 class="text-center mb-4">Kapcsolódó termék adatai</h1>

            <div class="text-center mb-3">
                <a href="{{ route('items.show', ['item' => $auction->item->id]) }}" role="button" class="btn btn-sm btn-primary"></i> Részletesebben <i class="fas fa-angle-right"></i></a>
            </div>

            <p class="text-center small text-secondary mb-2">
                <i class="far fa-calendar-alt"></i>
                <span>{{ $auction->item->made_in }}</span>
            </p>

            <div class="text-center mb-4">
                @foreach ($auction->item->labels as $label)
                    <a href="{{ route('labels.show', $label) }}" class="text-decoration-none">
                        <span style="background-color: {{ $label->color }}; color: #ffffff; border-radius: 6px; padding: 1px;">{{ $label->name }}</span>
                    </a>
                @endforeach
            </div>

            <p class="text-center mb-4"> {{ $auction->item->description }} </p>

            <div class="d-flex justify-content-center mb-3">
                @if($auction->item->image)
                    <a href="{{ asset('storage/' . $auction->item->image) }}" target="_blank">
                        <img src="{{ asset('storage/' . $auction->item->image) }}"
                            class="card-img-top img-fluid"
                            alt="Kép"
                            style="max-width: 400px;">
                    </a>
                @else
                    <img src="{{ asset('images/no_product_image.png') }}"
                        class="card-img-top img-fluid"
                        alt="Nem érhető el kép"
                        style="max-width: 400px;">
                @endif
            </div>

            @if ($auction->item->images && count($auction->item->images) > 0)
            <div class="mt-3 mb-5">
                <div class="col-12 col-md-8">
                    <h2><i class="fas fa-images"></i> További képek:</h2>
                </div>

                <div class="gallery js-flickity" data-flickity-options='{ "wrapAround": true }'>
                    @foreach($auction->item->images as $image)
                        <div class="gallery-cell">
                            <a href="{{ asset('images/' . $image->path) }}" target="_blank">
                                <img src="{{ asset('images/' . $image->path) }}" alt="Kép" class="img-fluid gallery-img">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <hr>

            <div class="mt-3">
                <div class="col-12 col-md-8">
                    <h2><i class="fas fa-fire"></i> Licitálás:</h2>
                </div>
                <div id="bid-form">
                    @include('bids.create', ['auction' => $auction])
                </div>
            </div>

            <div class="mt-3">
                <div class="col-12 col-md-8">
                    <h2><i class="fas fa-book"></i> Licit Történet:</h2>
                </div>
                @include('bids.show')
            </div>

            <hr>

            <div class="mt-3" id="messageForm" style="display: none;">
                <div class="col-12 col-md-8">
                    <h2><i class="fas fa-paper-plane"></i> <span id="receiverLabel"> Üzenet:</span></h2>
                </div>
                @include('messages.create')
            </div>

            <div class="mt-3">
                <div class="col-12 col-md-8">
                    <h2><i class="fas fa-envelope-open"></i> Üzeneteim ({{ $messageCount }})</h2>
                </div>
                <details>
                    <summary class="mb-3">
                        Üzenetek
                    </summary>
                    @include('messages.show')
                </details>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="float-lg-end">
                @auth
                    <a href="#bid-form" class="btn btn-sm btn-warning">
                        <i class="fas fa-fire"></i> Licitálok
                    </a>
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

@section('scripts')
<script>
    document.querySelectorAll('.reply-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            const receiverId = this.dataset.receiver;
            const receiverName = this.dataset.receiverName;

            document.querySelector('#receiver_id').value = receiverId;
            document.querySelector('#receiverLabel').textContent = ` Üzenet: ${receiverName}`;

            const messageForm = document.querySelector('#messageForm');
            messageForm.style.display = 'block';
            messageForm.scrollIntoView({ behavior: 'smooth' });
        });
    });
</script>
@endsection
