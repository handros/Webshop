@extends('layouts.app')
@section('title', $auction->item->name . ' szerkesztése')

@section('content')
<div class="container">
    <h1>Aukció: <i>{{ $auction->item->name }}</i> szerkesztése</h1>
    <div class="mb-4">
        <a href="{{ route('auctions.show', $auction) }}"><i class="fas fa-long-arrow-alt-left"></i> Mégse</a>
    </div>

    <form action="{{ route('auctions.update', $auction) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="form-group row mb-3">
            <label for="price" class="col-sm-2 col-form-label">Ár (Ft)*</label>
            <div class="col-sm-3">
                <input
                    type="number"
                    class="form-control @error('price') is-invalid @enderror"
                    id="price"
                    name="price"
                    value="{{ old('price') ?? $auction->price ?? '' }}"
                />
                @error('price')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="description" class="col-sm-2 col-form-label">Leírás*</label>
            <div class="col-sm-10">
                <textarea
                    class="form-control @error('description') is-invalid @enderror"
                    id="description"
                    name="description">{{ old('description') ?? $auction->description ?? '' }}</textarea>
                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="deadline" class="col-sm-2 col-form-label">Határidő*</label>
            <div class="col-sm-4">
                <input
                    type="date"
                    class="form-control @error('deadline') is-invalid @enderror"
                    id="deadline"
                    name="deadline"
                    value="{{ old('deadline') ?? $auction->deadline ?? '' }}"
                />
                @error('deadline')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="opened" class="col-sm-2 col-form-label">Nyitott aukció?</label>
            <div class="col-sm-4">
                <div class="form-check form-switch">
                    <input type="hidden" name="opened" value="0">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="opened"
                        name="opened"
                        value="1"
                        {{ old('opened', $auction->opened ?? true) ? 'checked' : '' }}
                    />
                    @error('opened')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>


        <div class="text-center">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Mentés</button>
        </div>
    </form>
</div>
@endsection
