<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        if(Auth::guest()) {
            abort(401);
        }
        $userId = Auth::id();
        $messages = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->orderBy('created_at', 'desc')
            ->with(['sender', 'receiver', 'auction', 'order'])
            ->get();

        $messageCount = $messages->count();

        return view('messages.index', compact('messages', 'messageCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(Auth::guest()) {
            abort(401);
        }

        $data = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'auction_id' => 'nullable|exists:auctions,id',
            'order_id' => 'nullable|exists:orders,id',
            'text' => 'required|string|max:500',
        ]);

        $message = new Message();
        $message->sender_id = Auth::id();
        $message->receiver_id = $data['receiver_id'];
        $message->auction_id = $data['auction_id'] ?: null;
        $message->order_id = $data['order_id'] ?: null;
        $message->text = $data['text'];
        $message->save();

        Session::flash('message_created', $message);
        return Redirect::back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }
}
