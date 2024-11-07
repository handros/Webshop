@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Üzeneteim</h1>

    <div class="mb-4">
        <h5>Összes üzenet: <span class="badge bg-primary">{{ $messageCount }}</span></h5>
    </div>

    @if($messages->isEmpty())
        <p>Nincs megjeleníthető üzenet.</p>
    @else
        <div class="row">
            @foreach($messages as $message)
                <div class="col-md-6">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                <strong>Feladó:</strong> {{ $message->sender->name }}
                                <i class="fas fa-angle-right"></i>
                                <strong>Címzett:</strong> {{ $message->receiver->name }}
                            </h5>
                            <p class="card-text">
                                <strong>Aukció:</strong>
                                <a  class="btn btn-light" href="{{ route('auctions.show', $message->auction->id) }}">
                                    {{ $message->auction->item->name }}
                                </a>
                            </p>
                            <p class="card-text">
                                <strong>Üzenet:</strong> {{ \Illuminate\Support\Str::limit($message->text, 100) }}
                            </p>
                            <p class="card-text">
                                <small class="text-muted">Küldve: {{ $message->created_at->format('Y-m-d H:i') }}</small>
                            </p>
                            <a  class="btn btn-primary btn-sm" href="{{ route('auctions.show', ['auction' => $message->auction->id]) }}#message{{ $message->id }}">Részletek</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
