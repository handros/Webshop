<div class="container">
    @auth
    <form action="{{ route('messages.store') }}" method="POST">
        @csrf
        @if(isset($auction))
            <input type="hidden" name="auction_id" value="{{ $auction->id }}">
            <input type="hidden" name="order_id" value="">
            <input type="hidden" name="receiver_id" id="receiver_id">
        @elseif(isset($order))
            <input type="hidden" name="auction_id" value="">
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <input type="hidden" name="receiver_id" value="{{ Auth::user()->is_admin ? $order->orderer_id : $adminId }}">
        @endif
        
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
        <p>Az üzenetküldéshez kérem <a href="{{ route('login') }}">jelentkezzen be</a> vagy <a href="{{ route('register') }}">regisztáljon</a>.</p>
    @endauth
</div>
