<div class="container">
    @auth
        <form action="{{ route('comments.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="item_id" value="{{ $item->id }}">

            <div class="form-group row mb-3">
                <label for="text" class="col-sm-2 col-form-label">Komment* </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control @error('text') is-invalid @enderror" id="text" name="text" value="{{ old('text') }}">
                    @error('text')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="rating" class="col-sm-2 col-form-label">Értékelés (1-5) </label>
                <div class="col-sm-2">
                    <input type="number" class="form-control @error('rating') is-invalid @enderror" id="rating" name="rating" min="1" max="5" value="{{ old('rating') }}">
                    @error('rating')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div >
                <button type="submit" class="btn btn-primary">Küldés <i class="fas fa-paper-plane"></i></button>
            </div>
        </form>
    @else
        <p>A kommenteléshez kérlek <a href="{{ route('login') }}">jelentkezzen be</a>.</p>
    @endauth
</div>
