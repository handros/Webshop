@extends('layouts.app')
@section('title', 'Update' . $item->name)

@section('content')
<div class="container">
    <h1>Update {{ $item->name }}</h1>
    <div class="mb-4">
        <a href="{{ route('items.show', $item) }}"><i class="fas fa-long-arrow-alt-left"></i> Cancel</a>
    </div>

    <form action="{{ route('items.update', $item) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="form-group row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Name*</label>
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
            <label for="made_in" class="col-sm-2 col-form-label">Manufactured*</label>
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
            <label for="description" class="col-sm-2 col-form-label">Description*</label>
            <div class="col-sm-10">
                <input
                    type="text"
                    class="form-control @error('description') is-invalid @enderror"
                    id="description"
                    name="description"
                    value="{{ old('description') ?? $item->description ?? '' }}">
                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="labels" class="col-sm-2 col-form-label py-0">Labels</label>
            <div class="col-sm-10">
                @forelse ($labels as $label)
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            value="{{ $label->id }}"
                            id="label-{{ $label->id }}"
                            name="labels[]"
                            {{-- @if((is_array(old('labels')) && in_array($label->id, old('labels'))) && (in_array($label->id, $item->labels->pluck('id')->toArray())))
                                checked
                            @endif --}}
                            {{-- @checked(is_array(old('labels')) && in_array($label->id, old('labels'))) --}}
                            @checked(
                                in_array(
                                    $label->id,
                                    old('labels', $item->labels->pluck('id')->toArray())
                                )
                            )
                        >
                        <label for="{{ $label }}" class="form-check-label">
                            <span style="background-color: {{ $label->color }}; color: #ffffff; border-radius: 6px; padding: 1px;">{{ $label->name }}</span>
                        </label>
                    </div>
                @empty
                    <p>No labels found</p>
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
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Store</button>
        </div>
    </form>
</div>
@endsection
