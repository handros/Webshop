<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Auction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
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
            'auction_id' => 'required|exists:auctions,id',
            'amount' => 'required|integer|min:0',
        ]);

        $auction = Auction::find($data['auction_id']);
        $highestBid = Bid::where('auction_id', $auction->id)->max('amount');
        $highestBid = $highestBid ?? $auction->price;
        $minBid = $highestBid + 500;

        if ($data['amount'] < $minBid) {
            return Redirect::back()->withErrors([
                'amount' => 'A licitednek nagyobbnak kell lennie a legmagasabb eddigi összegnél + minimum 500 Ft (' . $minBid . ' Ft*).'
            ])->withInput();
        }

        $bid = new Bid();
        $bid->user_id = Auth::id();
        $bid->auction_id = $data['auction_id'];
        $bid->amount = $data['amount'];

        $bid->save();

        $auction->update(['price' => $data['amount']]);

        Session::flash('bid_created', $bid); //TODO: Működjön
        return Redirect::route('auctions.show', $bid->auction_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bid $bid)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bid $bid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bid $bid)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bid $bid)
    {
        if (!Auth::user()->is_admin) {
            abort(401);
        }

        $bid->delete();

        Session::flash('bid_deleted', $bid);
        return redirect()->back();
    }
}
