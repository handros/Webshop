<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        //TODO: bejelentkezés + maxBidnél legyen nagyobb az amount
        $data = $request->validate([
            'auction_id' => 'required|exists:auctions,id',
            'amount' => 'required|integer|min:0',
        ]);

        $bid = new Bid();
        $bid->user_id = Auth::id(); // Az aktuális felhasználó ID-ja
        $bid->auction_id = $data['auction_id']; // Aukció ID hozzárendelése
        $bid->amount = $data['amount']; // Licit összeg

        $bid->save();
        //TODO: redirect
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
        //
    }
}
