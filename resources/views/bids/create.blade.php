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
                    <em>A licitnek <strong>500 Ft</strong>-tal nagyobbnak kell lennieca jelenlegi árnál <strong>(min. {{$minBid}} Ft)</strong>.</em>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-warning">Licitálás <i class="fas fa-handshake"></i></button>
                </div>
                <i class="fas fa-exclamation-triangle"></i><strong> Elfogadja</strong>, hogy a <em><strong>Licitálás</strong></em> gomb lenyomása esetleges fizetési kötelezettséggel jár (amennyiben Ön nyeri a licitet)!
            </form>
        @else
            <p>Lejárt a határidő <strong>({{ $auction->deadline->endOfDay()->format('Y-m-d') }})</strong></p>
        @endif

    @else
        <p>A licitáláshoz kérem <a href="{{ route('login') }}">jelentkezzen be</a> vagy <a href="{{ route('register') }}">regisztáljon</a>.</p>
    @endauth
</div>
