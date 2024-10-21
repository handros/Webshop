<div class="container">
    @if (Session::has('auction_created'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('auction_created')->item->name }} nevű aukctió létrehozva.
        </div>
    @endif

    @if (Session::has('auction_deleted'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('auction_deleted')->item->name }} nevű aukció törölve.
        </div>
    @endif
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
                    }}" alt="Kép" class="img-fluid gallery-img">
                </div>
            @endforeach
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="row">
                <h2>Jelenleg <b>{{ $auction_count }} termék</b> van aukcióra bocsátva</h2>

                @forelse ($auctions as $auction)
                    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex align-self-stretch">
                        <div class="card w-100">
                            <img
                            src="{{
                                asset(
                                    $auction->item->image
                                        ? 'storage/' . $auction->item->image
                                        : 'images/no_product_image.png'
                                )
                            }}"
                                class="card-img-top"
                                alt="Item cover"
                            >
                            <div class="card-body">
                                @if ( $auction->opened )
                                    <h5 class="card-title mb-0"> Nyitva eddig: {{ $auction->deadline }} </h5>
                                @else
                                    <h5 class="card-title mb-0"> Véget ért: {{ $auction->deadline }} </h5>
                                @endif

                                <h5 class="card-title mb-0"> {{ $auction->item->name }} </h5>

                                <p class="small mb-0">
                                    <span>
                                        <i class="far fa-calendar-alt"></i>
                                        <span> {{ $auction->item->made_in }} </span>
                                    </span>
                                </p>

                                @foreach ($auction->item->labels as $label)
                                    <a href="{{ route('labels.show', $label) }}" class="text-decoration-none">
                                        <span style="background-color: {{ $label->color }}; color: #ffffff; border-radius: 6px; padding: 1px;">{{ $label->name }}</span>
                                    </a>
                                @endforeach

                                @if ( strlen($auction->description) > 100 )
                                    <p class="card-text mt-1"> {{ substr($auction->description, 0, 100) }}... </p>
                                @else
                                    <p class="card-text mt-1"> {{ substr($auction->description, 0, 100) }} </p>
                                @endif
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('auctions.show', $auction) }}" class="btn btn-info">
                                    <span>Részletek</span> <i class="fas fa-angle-right"></i>
                                </a>
                                {{-- TODO: Ha a te licited van, akkor legyen zöld, egyébként piros --}}
                                <a href="#" class="btn {{ $auction->opened ? 'btn-success' : 'btn-danger' }} disabled" aria-disabled="true"> {{ $auction->price }} Ft</a>

                                {{-- <a href="{{ route('items.show', $item) }}" class="btn btn-outline-primary">
                                    <span>Licitálok</span> <i class="fas fa-angle-right"></i>
                                </a> --}}
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
                {{ $auctions->links() }}
            </div>

        </div>
    </div>
</div>
