<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Item;
use App\Models\Label;
use App\Models\Message;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class AuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('home', [
            'items' => Item::all(),
            'auctions' => Auction::with('item')->orderBy('deadline', 'desc')->paginate(9),
            'auction_items' => Item::where('on_auction', true)->get(),
            'labels' => Label::all(),
            'auctionCount' => Auction::count(),
            'opened_auctionCount' => Item::where('on_auction', true)
                ->whereHas('auction', function ($query) {
                    $query->where('deadline', '>=', now()->startOfDay());
                })->count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Item $item)
    {
        if(Auth::guest()) {
            abort(401);
        }

        if(!Auth::user()->is_admin || $item->on_auction) {
            abort(403);
        }

        return view('auctions.create', ['item' => $item]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(Auth::guest()) {
            abort(401);
        }

        $item = Item::findOrFail($request->item_id);

        if(!Auth::user()->is_admin || $item->on_auction) {
            abort(403);
        }

        $data = $request->validate([
            'item_id' => 'required|exists:items,id',
            'price' => 'required|integer|min:0',
            'description' => 'required|string|max:1000',
            'deadline' => 'required|date|after_or_equal:today',
            'opened' => 'nullable|boolean',
        ]);

        $auction = new Auction;
        $auction->item_id = $data['item_id'];
        $auction->price = $data['price'];
        $auction->description = $data['description'];
        $auction->deadline = $data['deadline'];
        $auction->opened = $request->has('opened');

        $auction->save();

        $item->on_auction = true;
        $item->save();

        Session::flash('auction_created', $auction);
        return Redirect::route('home');
    }

    /**
     * Display the specified resource.
     */
    public function show(Auction $auction)
    {
        $userId = Auth::id();

        $highestBid = $auction->bids()->max('amount') ?? $auction->price;
        $minBid = intval($highestBid) + 500;
        $bids = $auction->bids()->orderBy('amount', 'desc')->get();

        $otherUsers = $auction->messages()
            ->where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
            })
            ->get()
            ->map(function ($message) use ($userId) {
                return $message->sender_id === $userId ? $message->receiver : $message->sender;
            })
            ->unique('id')
            ->values();

        $messages = $auction->messages()
            ->where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $receiverId = null;

        $open = $auction->opened && $auction->deadline->endOfDay() >= now();
        $bought = Auth::check()
                && (!$auction->opened || $auction->deadline->endOfDay() < now())
                && $auction->bids()->exists()
                && $auction->bids()->where('amount', $highestBid)->first()->user_id === $userId;
        $no_bid_over = (!$auction->opened || $auction->deadline->endOfDay() < now()) && !$auction->bids()->exists();

        return view('auctions.show', [
            'auction' => $auction,
            'auctions' => Auction::all(),
            'highestBid' => $highestBid,
            'minBid' => $minBid,
            'bids' => $bids,
            'messages' => $messages,
            'receiverId' => $receiverId,
            'messageCount' => $messages->count(),
            'open' => $open,
            'bought' => $bought,
            'no_bid_over' => $no_bid_over,
            'otherUsers' => $otherUsers,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Auction $auction)
    {
        if(Auth::guest()) {
            abort(401);
        }

        if(!Auth::user()->is_admin) {
            abort(403);
        }

        return view('auctions.edit', [
            'auction' => $auction,
            'auctions' => Auction::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Auction $auction)
    {
        if(Auth::guest()) {
            abort(401);
        }

        if(!Auth::user()->is_admin) {
            abort(403);
        }

        $data = $request->validate([
            'price' => 'required|integer|min:0',
            'description' => 'required|string|max:1000',
            'deadline' => 'required|date',
            'opened' => 'nullable|boolean',
        ]);

        $auction->price = $data['price'];
        $auction->description = $data['description'];
        $auction->deadline = $data['deadline'];
        $auction->opened = $data['opened'] ?? false;
        $auction->save();

        Session::flash('auction_updated', $auction);
        return Redirect::route('auctions.show', $auction);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Auction $auction)
    {
        if(Auth::guest()) {
            abort(401);
        }

        if(!Auth::user()->is_admin) {
            abort(403);
        }

        $auction->item->on_auction = false;
        $auction->item->save();

        $auction->delete();

        Session::flash('auction_deleted', $auction);
        return Redirect::route('home');
    }
}
