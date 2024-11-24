@extends('layouts.app')
@section('title', 'Üzeneteim')

@section('content')
@auth
    <div class="container">
        <h1 class="mb-4">Üzeneteim <span class="badge bg-info">{{ $messageCount }}</span></h1>

        @if($messages->isEmpty())
            <p>Nincs megjeleníthető üzenet.</p>
        @else
            <div class="row">
                @foreach($messages as $message)
                    <div class="col-md-6">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    {{ $message->sender == Auth::user() ? 'Ön' : $message->sender->name }}
                                    <i class="fas fa-angle-right"></i>
                                    {{ $message->receiver == Auth::user() ? 'Ön' : $message->receiver->name }}
                                </h5>
                                @if(isset($message->auction_id))
                                    <p class="card-text">
                                        <strong>Aukció:</strong>
                                        <a  class="btn btn-light" href="{{ route('auctions.show', $message->auction->id) }}">
                                            {{ $message->auction->item->name }}
                                        </a>
                                    </p>
                                    <p class="card-text">
                                        <strong>Üzenet:</strong> {{ \Illuminate\Support\Str::limit($message->text, 100) }}
                                    </p>
                                    <p class="card-text d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Küldve: {{ $message->created_at->format('Y-m-d H:i') }}</small>
                                        <a class="btn btn-info btn-sm" href="{{ route('auctions.show', ['auction' => $message->auction->id]) }}#message{{ $message->id }}">Elolvas</a>
                                    </p>

                                @elseif(isset($message->order_id))
                                    <p class="card-text">
                                        <a  class="btn btn-light" href="{{ route('orders.show', $message->order->id) }}">
                                            <strong>Rendelés: {{ $message->order->id }}</strong>
                                        </a>
                                    </p>
                                    <p class="card-text">
                                        <strong>Üzenet:</strong> {{ \Illuminate\Support\Str::limit($message->text, 100) }}
                                    </p>
                                    <p class="card-text d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Küldve: {{ $message->created_at->format('Y-m-d H:i') }}</small>
                                        <a class="btn btn-info btn-sm" href="{{ route('orders.show', ['order' => $message->order->id]) }}#message{{ $message->id }}">Elolvas</a>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
@endauth
@endsection
