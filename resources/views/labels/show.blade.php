@extends('layouts.app')
@section('title', $label->name)

@section('content')
<div class="container">

    @if (Session::has('label_updated'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('label_updated')->name }} frissítve.
        </div>
    @endif

    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            <h1>Szűrés: <span  style="background-color: {{ $label->color }}; color: #ffffff; border-radius: 6px; padding: 1px;">{{ $label->name }}</span></h1>
        </div>
        <div class="col-12 col-md-4">
            <div class="float-lg-end">

                @auth
                    @if(Auth::user()->is_admin)
                        <a role="button" class="btn btn-sm btn-primary" href="{{ route('labels.edit', ['label' => $label->id]) }}"><i class="far fa-edit"></i> Szerkesztés</a>

                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delete-confirm-modal"><i class="far fa-trash-alt">
                            <span></i> Törlés</span>
                        </button>
                    @endif
                @endauth

            </div>
        </div>
    </div>

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

                                @foreach ($item->labels as $lab)
                                    <a href="{{ route('labels.show', $lab) }}" class="text-decoration-none">
                                        <span class="label-span label-span-background me-1" style="--label-color: {{ $lab->color }};">
                                            {{ $lab->name }}
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
                            Nem található termék erre a címkére!
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center">
                {{ $items->links() }}
            </div>

        </div>
        <div class="col-12 col-lg-3">
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card bg-light">
                        <div class="card-header">
                            Címkék
                        </div>
                        <div class="card-body">
                            @foreach ($labels as $lab)
                                <a href="{{ route('labels.show', $lab) }}" class="text-decoration-none">
                                    <span class="label-span label-span-background me-1" style="--label-color: {{ $lab->color }};">
                                        {{ $lab->name }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
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
                    Biztosan törölni akarja ezt a címkét <strong>{{ $label->name }}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                    <button
                        type="button"
                        class="btn btn-danger"
                        onclick="document.getElementById('delete-category-form').submit();"
                    >
                        Igen, biztosan törölje
                    </button>

                    <form id="delete-category-form" action="{{ route('labels.destroy', $label) }}" method="POST" class="d-none">
                        @method('DELETE')
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
