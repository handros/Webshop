<div class="container">
    @auth
    <form action="{{ route('messages.store') }}" method="POST">
        @csrf
        <input type="hidden" name="receiver_id" id="receiver_id">
        <input type="hidden" name="auction_id" value="{{ $auction->id }}">

        <div class="form-group mb-3">
            <label for="text" class="col-sm-2 col-form-label">Üzenet*</label>
            <div class="col-sm-10">
                <textarea class="form-control @error('text') is-invalid @enderror" id="text" name="text">{{ old('text') }}</textarea>
                @error('text')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Küldés <i class="fas fa-paper-plane"></i></button>
    </form>
    @else
        <p>Az üzenetküldéshez kérlek <a href="{{ route('login') }}">jelentkezzen be</a>.</p>
    @endauth
</div>
