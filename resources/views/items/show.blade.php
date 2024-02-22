@extends('layouts.app')
{{-- TODO: Post title --}}
@section('title', 'View jewellery: ')

@section('content')
<div class="container">

    {{-- TODO: Session flashes --}}

    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            <a href="{{ route('items.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Back to all jewelry</a>
            
            <h1>{{ $item->name }}</h1>

            <p class="small text-secondary mb-0">
                <i class="far fa-calendar-alt"></i>
                <span>{{ $item->made_in }}</span>
            </p>
            </br>

            <div class="mb-2">
                @foreach ($item->labels as $label)
                    <a href="#" class="text-decoration-none">
                        <span style="background-color: {{ $label->color }}; color: #ffffff; border-radius: 6px; padding: 1px;">{{ $label->name }}</span>
                    </a>
                @endforeach
            </div>

            <p class="card-text mt-1"> {{ $item->description }} </p>


        </div>

        <div class="col-12 col-md-4">
            <div class="float-lg-end">

                {{-- TODO: Links, policy --}}
                <a role="button" class="btn btn-sm btn-primary" href="#"><i class="far fa-edit"></i> Edit post</a>

                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delete-confirm-modal"><i class="far fa-trash-alt">
                    <span></i> Delete post</span>
                </button>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="delete-confirm-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Confirm delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- TODO: Title --}}
                    Are you sure you want to delete post <strong>N/A</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button
                        type="button"
                        class="btn btn-danger"
                        onclick="document.getElementById('delete-post-form').submit();"
                    >
                        Yes, delete this post
                    </button>

                    {{-- TODO: Route, directives --}}
                    <form id="delete-post-form" action="#" method="POST" class="d-none">

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <img
        src="{{
            asset(
                $item->image
                    ? 'storage/' . $item->image
                    : 'images/no_product_image.png'
            )
        }}"
            class="card-img-top img-fluid"
            alt="Vehicle cover"
            style="max-width: 400px;"
        >
    </div>
</div>
@endsection
