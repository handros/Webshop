<div class="container">
    @auth
        @if ($messages->isEmpty())
            <p><em>Nincsenek üzeneteid az aukció kapcsán.</em></p>
        @else
            @foreach ($messages as $message)
                <details class="mb-3" open>
                    <summary>
                        <strong>{{ $message->sender_id === auth()->id() ? 'Ön' : $message->sender->name }} </strong>
                        <i class="fas fa-angle-right"></i> <strong>{{ $message->receiver_id === auth()->id() ? 'Ön' : $message->receiver->name }} </strong>
                        <em> - {{ $message->created_at->format('Y-m-d H:i') }}</em>
                    </summary>
                    <p>{{ $message->text }}</p>
                </details>
            @endforeach
        @endif
    @else
        <p>Az üzenetei eléréséhez kérlek <a href="{{ route('login') }}">jelentkezzen be</a>.</p>
    @endauth
</div>
