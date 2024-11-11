<div class="container">
    @auth
        @if ($messages->isEmpty())
            <p><em>Nincsenek üzenetei itt.</em></p>
        @else
            @if(isset($order))
                <div class="container mt-4">
                    @foreach ($messages as $message)
                        <div class="d-flex {{ $message->sender_id == Auth::id() ? 'justify-content-end' : 'justify-content-start' }} mb-3">
                            <div id="message{{ $message->id }}" class="message-bubble p-2
                                {{ $message->sender_id == Auth::id() ? 'own-message' : 'other-message' }}">
                                <p class="mb-0">{{ $message->text }}</p>
                                <small class="text-muted d-block text-right">{{ $message->created_at->format('Y:m:d - H:i') }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            @elseif(isset($auction))
                @foreach ($messages as $message)
                    <details id="message{{ $message->id }}" class="mb-3" open>
                        <summary>
                            <strong>{{ $message->sender_id === Auth::id() ? 'Ön' : $message->sender->name }} </strong>
                            <i class="fas fa-angle-right"></i> <strong>{{ $message->receiver_id === Auth::id() ? 'Ön' : $message->receiver->name }} </strong>
                            <em> - {{ $message->created_at->format('Y-m-d H:i') }}</em>
                            @if(Auth::id() !== $message->sender->id)
                                <a href="#messageForm" class="btn-sm btn-primary reply-btn"
                                    data-receiver="{{ $message->sender->id }}"
                                    data-receiver-name="{{ $message->sender->name }}">
                                    <i class="fas fa-reply"></i>
                                </a>
                            @elseif(Auth::id() !== $message->receiver->id)
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
        @endif
    @else
        <p>Az üzenetei eléréséhez kérem <a href="{{ route('login') }}">jelentkezzen be</a> vagy <a href="{{ route('register') }}">regisztáljon</a>.</p>
    @endauth
</div>
