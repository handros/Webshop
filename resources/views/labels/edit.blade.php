@extends('layouts.app')
@section('title', $label->name . ' szerkesztése')

@section('content')
<div class="container">
    <h1>{{ $label->name }} szerkesztése</h1>
    <div class="mb-4">
        <a class="btn btn-secondary" href="{{ route('labels.show', $label) }}"><i class="fas fa-long-arrow-alt-left"></i> Mégse</a>
    </div>

    <form action="{{ route('labels.update', $label) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="form-group row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Név*</label>
            <div class="col-sm-10">
                <input
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    id="name"
                    name="name"
                    value="{{ old('name') ?? $label->name ?? '' }}"
                />
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="color" class="col-sm-2 col-form-label py-0">Szín*</label>
            <div class="col-sm-10">
                <input type="color" id="color" name="color" value="{{ old('color') ?? $label->color }}" />
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Mentés</button>
        </div>
    </form>
</div>
@endsection
