<div class="container">
    <div class="table-responsive-md">
        <table class="table">
            <tbody>
                @foreach($comments as $comment)
                    <tr>
                        <td class="align-middle">
                            <p><strong>{{ $comment->user->name }}</strong> - {{ $comment->created_at }}</p>
                            @if ($comment->rating != null)
                                <p>Értékelés: {{ $comment->rating }}</p>
                            @endif
                            <p>{{ $comment->text }}</p>
                        </td>
                        <td class="align-middle">
                            @auth
                                @if($comment->user_id === Auth::id())
                                    <div class="btn-group float-right">
                                        <form method="POST" action="{{ route('comments.destroy', $comment) }}">
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
</div>
