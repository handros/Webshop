<div class="container">
    @auth
        @if($auction->opened && $auction->deadline->endOfDay() >= now())
            <form action="{{ route('bids.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="auction_id" value="{{ $auction->id }}">

                <div class="form-group row mb-3">
                    <label for="amount" class="col-sm-2 col-form-label">Licit (Ft)*</label>
                    <div class="col-sm-3">
                        <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', $minBid) }}" min="{{ $minBid }}">
                        @error('amount')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <em>A licitnek <strong>500 Ft</strong>-tal nagyobbnak kell lennie, mint a jelenlegi árnak <strong>({{$minBid}} Ft)</strong></em>
                </div>

                <div >
                    <button type="submit" class="btn btn-warning"><i class="fas fa-handshake"></i> Licitálás</button>
                </div>
            </form>
        @else
            <p>Lejárt a határidő <b>({{ $auction->deadline->endOfDay() }})</b></p>
        @endif

    @else
        <p>A licitáláshoz kérlek <a href="{{ route('login') }}">jelentkezzen be</a>.</p>
    @endauth
</div>
