@extends('layouts.app')
@section('title', $item->name)

@section('content')
<div class="container">
    @if (Session::has('item_updated'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('item_updated')->name }} frissítve.
        </div>
    @endif

    @if (Session::has('comment_created'))
        <div class="alert alert-success" role="alert">
            Ön létrehozott egy kommentet.
        </div>
    @endif

    @if (Session::has('comment_deleted'))
        <div class="alert alert-success" role="alert">
            Komment sikeresen törölve.
        </div>
    @endif

    <div class="row justify-content-between">
        <div class="col-12 col-md-8">

            <h1 class="text-center">
                {{ $item->name }}
                @if($item->on_auction)
                    <a href="{{ route('auctions.show', $item->auction) }}" class="btn btn-warning"><strong>Aukcióra bocsátva <i class="fas fa-angle-double-right"></i></strong></a>
                @endif
            </h1>

            <p class="text-center">
                <i class="far fa-calendar-alt"></i>
                <span>{{ $item->made_in }}</span>
            </p>

            <p class="text-center"> {{ $item->description }} </p>

            <div class="mb-3 text-center">
                @foreach ($item->labels as $label)
                    <a href="{{ route('labels.show', $label) }}" class="text-decoration-none">
                        <span class="label-span label-span-background" style="--label-color: {{ $label->color }};">
                        {{ $label->name }}
                    </span>
                    </a>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mb-3">
                @if($item->image)
                    <a href="{{ asset('storage/' . $item->image) }}" target="_blank">
                        <img src="{{ asset('storage/' . $item->image) }}"
                            class="card-img-top img-fluid"
                            alt="Kép"
                            style="max-width: 400px;">
                    </a>
                @else
                    <img src="{{ asset('images/no_product_image.png') }}"
                        class="card-img-top img-fluid"
                        alt="No product image available"
                        style="max-width: 400px;">
                @endif
            </div>

            @if ($item->images && count($item->images) > 0)
                <div class="mt-3 mb-5">
                    <div class="col-12 col-md-8 mb-3">
                        <h2><i class="fas fa-images"></i> További képek:</h2>
                    </div>

                    <div class="gallery js-flickity" data-flickity-options='{ "wrapAround": true }'>
                        @foreach($item->images as $image)
                            <div class="gallery-cell">
                                <a href="{{ asset('images/' . $image->path) }}" target="_blank">
                                    <img src="{{ asset('images/' . $image->path) }}" alt="Kép" class="img-fluid gallery-img">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <hr>

            <div class="mt-3">
                <div class="col-12 col-md-8">
                    <h2><i class="fas fa-comment"></i> Új Komment</h2>
                </div>
                @include('comments.create')
            </div>

            <div class="mt-3">
                <div class="col-12 col-md-8">
                    <h2><i class="fas fa-comments"></i> Kommentek ({{ $commentCount }})</h2>
                </div>
                <details open>
                    <summary class="mb-3">
                        Kommentek
                    </summary>
                    @include('comments.show')
                </details>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="float-lg-end">

                @auth
                    @if(Auth::user()->is_admin)
                        @if (!$item->on_auction)
                            <a href="{{ route('auctions.create', ['item' => $item->id]) }}" role="button" class="btn btn-sm btn-warning"><i class="fas fa-fire"></i> Aukcióra bocsát</a>
                        @else
                            <a href="{{ route('auctions.edit', ['auction' => $item->auction->id]) }}" role="button" class="btn btn-sm btn-warning"><i class="fas fa-fire"></i> Aukció szerkesztése</a>
                        @endif

                        <a href="{{ route('items.edit', ['item' => $item->id]) }}" role="button" class="btn btn-sm btn-primary"><i class="far fa-edit"></i> Szerkesztés</a>

                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delete-confirm-modal"><i class="far fa-trash-alt"></i>
                            <span> Törlés</span>
                        </button>
                    @endif
                @endauth

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="delete-confirm-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Törlés megerősítése</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Biztosan törölni akarja ezt az termékt <strong>{{ $item->name }}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                    <button
                        type="button"
                        class="btn btn-danger"
                        onclick="document.getElementById('delete-post-form').submit();"
                    >
                        Igen, biztosan törölje
                    </button>

                    <form id="delete-post-form" action="{{ route('items.destroy', $item) }}" method="POST" class="d-none">
                        @method('DELETE')
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
    </br>


</div>
@endsection
