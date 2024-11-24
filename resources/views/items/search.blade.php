@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Keresési eredmények: <i>{{ $query }}</i></h1>
    <div class="mb-4">
        <a class="btn btn-secondary" href="{{ route('items.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Termékek</a>
    </div>

    <div class="container d-flex justify-content-center mt-5">
        <div class="col-12 col-lg-6">
            <form action="{{ route('items.search') }}" method="GET" class="d-flex">
                <input
                    type="text"
                    name="query"
                    class="form-control me-2"
                    placeholder="Keresés név vagy címke szerint"
                    value="{{ request('query') }}"
                >
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-lg-12">
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
                                class="card-img-top card-cover"
                                alt="Kép"
                            >
                            <div class="card-body">
                                <h5 class="card-title mb-0"> {{ $item->name }} </h5>

                                @if ($item->on_auction)
                                        <p class="small mb-0">
                                            <span>
                                                <i class="fas fa-fire"></i> Aukción
                                            </span>
                                        </p>
                                @endif

                                <p class="small mb-0">
                                    <span>
                                        <i class="far fa-calendar-alt"></i>
                                        <span> {{ $item->made_in }} </span>
                                    </span>
                                </p>

                                @foreach ($item->labels as $label)
                                    <a href="{{ route('labels.show', $label) }}" class="text-decoration-none">
                                        <span class="label-span label-span-background" style="--label-color: {{ $label->color }};">
                                            {{ $label->name }}
                                        </span>
                                    </a>
                                @endforeach

                                <p class="card-text mt-1">{{ \Illuminate\Support\Str::limit($item->description, 100) }}</p>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('items.show', $item) }}" class="btn btn-info">
                                    <span>Részletek</span> <i class="fas fa-angle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning" role="alert">
                            Nem található olyan termék, ahol a nevében vagy egy címkéjében szerepelne ez: {{ $query }}!
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center">
                {{ $items->links() }}
            </div>
        </div>
    </div>

@endsection
