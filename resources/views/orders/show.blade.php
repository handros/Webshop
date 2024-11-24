@extends('layouts.app')
@section('title', 'Rendelés #' . $order->id)

@section('content')
@auth
    <div class="container">
        @if (Session::has('message_created'))
            <div class="alert alert-success" role="alert">
                Üzenet elküldve.
            </div>
        @endif

        <div class="row justify-content-between">
            <div class="col-12 col-lg-10 col-md-8">
                <h1 class="text-center">Rendelés: #{{ $order->id }} adatai</h1>
                <div class="">
                    <a class="btn btn-secondary" href="{{ route('orders.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Rendelések</a>
                </div>
                @if (Auth::user()->is_admin)
                    <h2 class="text-center mb-4"> Megrendelte: <strong>{{ $order->orderer->name }}</strong> - <em class="text-muted"> {{ $order->created_at }}</em></h2>
                @else
                    <h2 class="text-center mb-4"> Megrendelve:<em class="text-muted"> {{ $order->created_at }}</em></h2>
                @endif
                <p class="text-center text-break"><strong>Megadott leírás: </strong>{{ $order->description }} </p>

                <div class="text-center mb-4">
                    @foreach ($order->labels as $label)
                        <span class="label-span label-span-background me-1" style="--label-color: {{ $label->color }};">
                            {{ $label->name }}
                        </span>
                    @endforeach
                </div>

                @if ($order->ready)
                    <h2 class="text-center mb-4"><span class="badge bg-success"> Elkészült* </span></h2>
                    <p class="text-center">* A további teendők pontosításáért emailban és itt a rendelés alatt üzenetben felveszem Önnel a kapcsolatot!</p>
                @else
                    <h2 class="text-center mb-4"><span class="badge bg-warning"> Készül* </span></h2>
                    <p class="text-center">* Értesíteni fogom emailban és itt a rendelés alatt üzenetben, amint elkészül a rendelése!</p>
                    <p class="text-center">Kérdés vagy pontosítás esetén keressen bátran!</p>
                @endif

                @if ($order->images && count($order->images) > 0)
                <div class="mt-3 mb-5">
                    <div class="col-12 col-md-8">
                        <h2><i class="fas fa-images"></i> Feltöltött képek:</h2>
                    </div>

                    <div class="gallery js-flickity" data-flickity-options='{ "wrapAround": true }'>
                        @foreach($order->images as $image)
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
                        <h2><i class="fas fa-paper-plane"></i> <span id="receiverLabel"> Üzenet:</span></h2>
                    </div>
                    @include('messages.create')
                </div>

                <div class="mt-3">
                    <div class="col-12 col-md-8">
                        <h2><i class="fas fa-envelope-open"></i> Üzeneteim ({{ $messageCount }})</h2>
                    </div>
                        @include('messages.show')
                    </details>
                </div>
            </div>
    </div>
@endauth
@endsection
