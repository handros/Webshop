@extends('layouts.app')
@section('title', $item->name . ' szerkesztése')

@section('content')
<div class="container">
    <h1>Termék: <i>{{ $item->name }}</i> szerkesztése</h1>
    <div class="mb-4">
        <a class="btn btn-secondary" href="{{ route('items.show', $item->id) }}"><i class="fas fa-long-arrow-alt-left"></i> Termék: {{ $item->name }}</a>
    </div>

    <form action="{{ route('items.update', $item) }}" method="POST" enctype="multipart/form-data">
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
                    value="{{ old('name') ?? $item->name ?? '' }}"
                />
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="made_in" class="col-sm-2 col-form-label">Gyártási év*</label>
            <div class="col-sm-4">
                <input
                    type="number"
                    class="form-control @error('made_in') is-invalid @enderror"
                    id="made_in" name="made_in"
                    placeholder="YYYY" min="2000" max="{{ date('Y') }}"
                    pattern="[0-9]{4}"
                    value="{{ old('made_in') ?? $item->made_in ?? '' }}">
                @error('made_in')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="description" class="col-sm-2 col-form-label">Leírás</label>
            <div class="col-sm-10">
                <textarea class="form-control @error('description') is-invalid @enderror"
                    id="description"
                    name="description">
                        {{ old('description') ?? $item->description ?? '' }}
                </textarea>
                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="labels" class="col-sm-2 col-form-label py-0">Címkék</label>
            <div class="col-sm-10">
                @forelse ($labels as $label)
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            value="{{ $label->id }}"
                            id="label-{{ $label->id }}"
                            name="labels[]"
                            @checked(
                                in_array(
                                    $label->id,
                                    old('labels', $item->labels->pluck('id')->toArray())
                                )
                            )
                        >
                        <label for="{{ $label }}" class="form-check-label">
                            <span class="label-span label-span-background" style="--label-color: {{ $label->color }};">
                                {{ $label->name }}
                            </span>
                        </label>
                    </div>
                @empty
                    <p>Nem találhatóak címkék.</p>
                @endforelse

                @foreach ($errors->get('labels.*') as $message)
                    <p>{{ json_encode($message) }}</p>
                @endforeach

                @error('labels')
                    <p class="text-danger">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Mentés</button>
        </div>
    </form>
</div>
@endsection
