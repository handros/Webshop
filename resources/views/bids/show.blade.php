<div class="container">
    @if ($bids && count($bids))
    <details open>
        <summary  class="mb-3">Eddigi licitek</summary>
        <div class="table-responsive-md">
            <table class="table">
                <tbody>
                    @foreach($bids as $bid)
                        @php
                            $class = '';

                            if(auth()->check()) {
                                if($bid->user_id === auth()->id() && $bid->amount == $auction->price) {
                                    $class = 'table-success';
                                } elseif ($bid->user_id === auth()->id() && $bid->amount != $auction->price) {
                                    $class = 'table-warning';
                                } else {
                                    $class = 'table-danger';
                                }
                            }
                        @endphp
                        <tr class="{{ $class }}">
                            <td>
                                <p><strong>{{ $bid->user->name }}</strong></p>
                            </td>
                            <td>
                                {{ $bid->amount }} Ft
                            </td>
                            <td class="text-end">
                                @if(auth()->id() !== $bid->user->id)
                                    <a href="#messageForm" class="btn btn-primary reply-btn"
                                        data-receiver="{{ $bid->user->id }}"
                                        data-receiver-name="{{ $bid->user->name }}">
                                        Üzenet <i class="fas fa-reply"></i>
                                    </a>
                                @endif

                                @auth
                                    @if(auth()->user()->is_admin)
                                        <div class="btn-group float-right">
                                            <form method="POST" action="{{ route('bids.destroy', $bid) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"><i class="far fa-trash-alt"></i></button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </details>
    @else
        <p><em>Még nincsenek licitek.</em></p>
    @endif

</div>
