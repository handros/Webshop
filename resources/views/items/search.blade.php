@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Keresési eredmények</h1>

    <div class="table-responsive-md">
        <table class="table" >
            <thead>
                <tr class="text-center">
                    <th scope="col">#</th>
                    <th scope="col" class="col-3">Item név</th>
                    <th scope="col">Címkék</th>
                    <th scope="col">Aukción</th>
                    <th scope="col">Kép</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $i => $item)
                    <tr class="text-center">
                        <th class="align-middle" scope="row">{{ $i + 1 }}</th>
                        <td class="align-middle">{{ $item->name }}</td>
                        <td class="align-middle">
                            @foreach($item->labels as $label)
                                <a href="{{ route('labels.show', $label) }}" class="text-decoration-none">
                                    <span style="background-color: {{ $label->color }}; color: #ffffff; border-radius: 6px; padding: 1px;">{{ $label->name }}</span>
                                </a>
                            @endforeach
                        </td>
                        <td class="align-middle">
                            @if($item->on_auction)
                                <a href="{{ route('auctions.show', $item->auction) }}" class="btn btn-outline-success">Igen</a>
                            @else
                                <a href="#" class="btn btn-outline-danger disabled" aria-disabled="true">Nem</a>
                            @endif
                        </td>
                        <td class="align-middle">
                            <img
                                src="{{
                                    asset(
                                        $item->image
                                            ? 'storage/' . $item->image
                                            : 'images/no_product_image.png'
                                    )
                                }}"
                                class="img-fluid"
                                alt="Item cover"
                                style="max-width: 100px;"
                            >
                        </td>
                        <td class="align-middle">
                            <a href="{{ route('items.show', $item) }}" class="btn btn-primary">
                                Részletek <i class="fas fa-angle-right"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <p>Nincs találat a megadott keresésre.</p>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
