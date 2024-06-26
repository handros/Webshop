@extends('layouts.app')
@section('title', 'Create post')

@section('content')
<div class="container">

    <h1>Create post</h1>
    <div class="mb-4">
        <a href="{{ route('items.index') }}"><i class="fas fa-long-arrow-alt-left"></i> All jewelry</a>
    </div>

    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Name*</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
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
                <input type="number" class="form-control @error('made_in') is-invalid @enderror" id="made_in" name="made_in" placeholder="YYYY" min="2000" max="{{ date('Y') }}" pattern="[0-9]{4}" value="{{ old('made_in') }}">
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
                <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description') }}">
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
                            @checked(is_array(old('labels')) && in_array($label->id, old('labels')))
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

        <div class="form-group row mb-3">
            <label for="image" class="col-sm-2 col-form-label">Image*</label>
            <div class="col-sm-10">
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <input type="file" class="form-control-file" id="image" name="image">
                        </div>
                        <div id="cover_preview" class="col-12 d-none">
                            <p>Cover preview:</p>
                            <img id="cover_preview_image" src="#" alt="Cover preview" width="200px">
                        </div>
                    </div>
                </div>
            </div>

            @error('image')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group row mb-3">
            <label for="images" class="col-sm-2 col-form-label">További képek kiválasztása</label>

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
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Store</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    const coverImageInput = document.querySelector('input#image');
    const coverPreviewContainer = document.querySelector('#cover_preview');
    const coverPreviewImage = document.querySelector('img#cover_preview_image');

    coverImageInput.onchange = event => {
        const [file] = coverImageInput.files;
        if (file) {
            coverPreviewContainer.classList.remove('d-none');
            coverPreviewImage.src = URL.createObjectURL(file);
        } else {
            coverPreviewContainer.classList.add('d-none');
        }
    }
</script>
@endsection
