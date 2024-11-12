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
                @foreach ($otherUsers as $otherUser)
                    <div class="mb-4">
                        <details class="mb-3">
                            <summary class="mb-2">
                                <strong>{{ $otherUser->name }}</strong>
                                <a href="#messageForm" class="btn-sm btn-primary reply-btn"
                                    data-receiver="{{ $otherUser->id }}"
                                    data-receiver-name="{{ $otherUser->name }}">
                                    <i class="fas fa-reply"></i>
                                </a>
                            </summary>
                            @foreach ($messages as $message)
                                @if (($message->sender_id == $otherUser->id || $message->receiver_id == $otherUser->id) && $message->auction_id == $auction->id)
                                    <div class="d-flex {{ $message->sender_id == Auth::id() ? 'justify-content-end' : 'justify-content-start' }} mb-3">
                                        <div id="message{{ $message->id }}" class="message-bubble p-2
                                            {{ $message->sender_id == Auth::id() ? 'own-message' : 'other-message' }}">
                                                <p class="mb-0">{{ $message->text }}</p>
                                                <small class="text-muted d-block text-right">{{ $message->created_at->format('Y:m:d - H:i') }}</small>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </details>
                        <hr>
                    </div>
                @endforeach
            @endif
        @endif
    @else
        <p>Az üzenetei eléréséhez kérem <a href="{{ route('login') }}">jelentkezzen be</a> vagy <a href="{{ route('register') }}">regisztáljon</a>.</p>
    @endauth
</div>
