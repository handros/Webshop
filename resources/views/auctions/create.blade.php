@extends('layouts.app')
@section('title', 'Aukció létrehozása')

@section('content')
<div class="container">

    <h1>Aukció létrehozása: {{ $item->name }}</h1>
    <div class="mb-4">
        <a class="btn btn-secondary" href="{{ route('items.show', $item->id) }}"><i class="fas fa-long-arrow-alt-left"></i> Termék: {{ $item->name }}</a>
    </div>

    <form action="{{ route('auctions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="item_id" value="{{ $item->id }}">

        <div class="form-group row mb-3">
            <label for="price" class="col-sm-2 col-form-label">Ár*</label>
            <div class="col-sm-3">
                <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}">
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
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') }}</textarea>
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
                <input type="date" class="form-control @error('deadline') is-invalid @enderror" id="deadline" name="deadline" value="{{ old('deadline') }}">
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
                    <input class="form-check-input" type="checkbox" id="opened" name="opened" value="1" {{ old('opened', 1) ? 'checked' : '' }}>
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
