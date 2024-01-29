@extends('layouts.app')
@section('title', 'Create label')

@section('content')
<div class="container">
    <h1>Create label</h1>
    <div class="mb-4">
        {{-- TODO: Link --}}
        <a href="{{ url('/') }}"><i class="fas fa-long-arrow-alt-left"></i> Back to the homepage</a>
    </div>

    @if (Session::has('label_created'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('label_created')->name }} label succesfully created.
        </div>
    @endif


    <form action="{{ route('labels.store') }}" method="POST">
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
            <label for="color" class="col-sm-2 col-form-label py-0">Color*</label>
            <div class="col-sm-10">
                <input type="color" id="color" name="color" value="{{ old('color') }}" />
            </div>

        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Store</button>
        </div>

    </form>
</div>
@endsection
