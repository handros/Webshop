@extends('layouts.app')
@section('title', 'Rendelés')

@section('content')
<div class="container">

    <h1>Rendelés</h1>
    <div class="mb-4">
        <a href="{{ route('orders.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Rendeléseim</a>
    </div>

    @auth
        <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="orderer_id" id="{{ Auth::id() }}">
            <div class="form-group row mb-3">
                <label for="description" class="col-sm-2 col-form-label">Leírás*</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description') }}">
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
                                @checked(is_array(old('labels')) && in_array($label->id, old('labels')))
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

            <div class="form-group row mb-3">
                <label for="images" class="col-sm-2 col-form-label">Képek feltöltése</label>

                <div class="col-sm-10">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <input type="file" class="form-control-file" id="images" name="images[]" multiple>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach ($errors->get('images.*') as $message)
                    <p>{{ json_encode($message) }}</p>
                @endforeach

                @error('images')
                    <p class="text-danger">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Mentés</button>
            </div>
        </form>
    @else
        <p>Rendelés leadásához kérem <a href="{{ route('login') }}">jelentkezzen be</a> vagy <a href="{{ route('register') }}">regisztáljon</a>.</p>
    @endauth
</div>
@endsection
