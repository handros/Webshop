@extends('layouts.app')
@section('title', 'Jewelry')

@section('content')
<div class="container">
    @auth
        @if(auth()->user()->is_admin)
            <div class="row justify-content-between">
                <div class="col-12 col-md-8">
                    <h1>All jewelry</h1>
                </div>
                <div class="col-12 col-md-4">
                    <div class="float-lg-end">
                        <a href="{{ route('items.create') }}" role="button" class="btn btn-sm btn-success mb-1"><i class="fas fa-plus-circle"></i> New jewellery</a>
                        <a href="{{ route('labels.create') }}" role="button" class="btn btn-sm btn-success mb-1"><i class="fas fa-plus-circle"></i> New label</a>
                    </div>
                </div>
            </div>
        @endif
    @endauth


    @if (Session::has('item_created'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('item_created')->name }} létrehozva.
        </div>
    @endif

    @if (Session::has('item_deleted'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('item_deleted')->name }} törölve.
        </div>
    @endif

    @if (Session::has('label_created'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('label_created')->name }} törölve.
        </div>
    @endif

    <div class="row mt-3">
        <div class="col-12 col-lg-9">
            <div class="row">
                @forelse ($items as $item)
                    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex align-self-stretch">
                        <div class="card w-100">
                            <img
                            src="{{
                                asset(
                                    $item->image
                                        ? 'storage/' . $item->image
                                        : 'images/no_product_image.png'
                                )
                            }}"
                                class="card-img-top"
                                alt="Item cover"
                            >
                            <div class="card-body">
                                <h5 class="card-title mb-0"> {{ $item->name }} </h5>
                                <p class="small mb-0">
                                    <span>
                                        <i class="far fa-calendar-alt"></i>
                                        <span> {{ $item->made_in }} </span>
                                    </span>
                                </p>

                                @foreach ($item->labels as $label)
                                    <a href="{{ route('labels.show', $label) }}" class="text-decoration-none">
                                        <span style="background-color: {{ $label->color }}; color: #ffffff; border-radius: 6px; padding: 1px;">{{ $label->name }}</span>
                                    </a>
                                @endforeach

                                {{-- TODO: can be a link --}}
                                @if ( strlen($item->description) > 100 )
                                    <p class="card-text mt-1"> {{ substr($item->description, 0, 100) }}... </p>
                                @else
                                    <p class="card-text mt-1"> {{ substr($item->description, 0, 100) }} </p>
                                @endif
                            </div>
                            <div class="card-footer">
                                {{-- TODO: Link --}}
                                <a href="{{ route('items.show', $item) }}" class="btn btn-primary">
                                    <span>View jewellery</span> <i class="fas fa-angle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning" role="alert">
                            No items found!
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center">
                {{-- TODO: Pagination --}}
            </div>

        </div>
        <div class="col-12 col-lg-3">
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card bg-light">
                        <div class="card-header">
                            Labels
                        </div>
                        <div class="card-body">
                            @foreach ($labels as $label)
                                <a href="{{ route('labels.show', $label) }}" class="text-decoration-none">
                                    <span style="background-color: {{ $label->color }}; color: #ffffff; border-radius: 6px; padding: 1px;">{{ $label->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                @auth
                    @if(auth()->user()->is_admin)
                        <div class="col-12 mb-3">
                            <div class="card bg-light">
                                <div class="card-header">
                                    Statistics
                                </div>
                                <div class="card-body">
                                    <div class="small">
                                        <ul class="fa-ul">
                                            <li><span class="fa-li"><i class="fas fa-user"></i></span>Users: {{ $user_count }}</li>
                                            <li><span class="fa-li"><i class="fas fa-layer-group"></i></span>Labels: {{ $label_count }}</li>
                                            <li><span class="fa-li"><i class="fas fa-file-alt"></i></span>Items: {{ $item_count }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>

        </div>
    </div>
</div>
@endsection
