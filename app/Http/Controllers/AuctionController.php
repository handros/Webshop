<?php

namespace App\Http\Controllers;

use App\Models\Auction;
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
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(Auth::guest() or !Auth::user()->is_admin) {
            abort(401);
        }
        return view('auctions.create', [
            'auctions' => Auction::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(Auth::guest() or !Auth::user()->is_admin) {
            abort(401);
        }
        $data = $request->validate([
            'price' => 'required|integer|min:0',
            'description' => 'required|string|max:1000',
            'deadline' => 'required|date|after_or_equal:today',
            'opened' => 'nullable|boolean',
        ]);

        $auction = new Auction;
        $auction->price = $data['price'];
        $auction->description = $data['description'];
        $auction->deadline = $data['deadline'];
        $auction->opened = $request->has('opened');

        $auction->save();

        Session::flash('auction_created', $auction);

        return Redirect::route('home.index'); //might be auction.index later
    }

    /**
     * Display the specified resource.
     */
    public function show(Auction $auction)
    {
        return view('auctions.show', [
            'auction' => $auction,
            'auctions' => Auction::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Auction $auction)
    {
        if(Auth::guest() or !Auth::user()->is_admin) {
            abort(401);
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
        if(Auth::guest() or !Auth::user()->is_admin) {
            abort(401);
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
        if(Auth::guest() or !Auth::user()->is_admin) {
            abort(401);
        }

        $auction->item->auction = false;
        $auction->item->save();

        $auction->delete();

        // Session::flash('auction_deleted', $auction);
        return Redirect::to('/');
    }
}
