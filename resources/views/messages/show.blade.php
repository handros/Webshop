<div class="container">
    @auth
        @if ($messages->isEmpty())
            <p><em>Nincsenek üzeneteid az aukció kapcsán.</em></p>
        @else
            @foreach ($messages as $message)
                <details id="message{{ $message->id }}" class="mb-3" open>
                    <summary>
                        <strong>{{ $message->sender_id === auth()->id() ? 'Ön' : $message->sender->name }} </strong>
                        <i class="fas fa-angle-right"></i> <strong>{{ $message->receiver_id === auth()->id() ? 'Ön' : $message->receiver->name }} </strong>
                        <em> - {{ $message->created_at->format('Y-m-d H:i') }}</em>
                        @if(auth()->id() !== $message->sender->id)
                            <a href="#messageForm" class="btn-sm btn-primary reply-btn"
                                data-receiver="{{ $message->sender->id }}"
                                data-receiver-name="{{ $message->sender->name }}">
                                <i class="fas fa-reply"></i>
                            </a>
                        @elseif(auth()->id() !== $message->receiver->id)
                            <a href="#messageForm" class="btn-sm btn-primary reply-btn"
                                data-receiver="{{ $message->receiver->id }}"
                                data-receiver-name="{{ $message->receiver->name }}">
                                <i class="fas fa-reply"></i>
                            </a>
                        @endif
                    </summary>
                    <p>{{ $message->text }}</p>
                </details>
            @endforeach
        @endif
    @else
        <p>Az üzenetei eléréséhez kérlek <a href="{{ route('login') }}">jelentkezzen be</a> vagy <a href="{{ route('register') }}">regisztáljon</a>.</p>
    @endauth
</div>
