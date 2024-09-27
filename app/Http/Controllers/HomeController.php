<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Label;
use App\Models\Auction;

class HomeController extends Controller
{
    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // return view('home');
        return view('home', [
            'items' => Item::all(),
            'auctions' => Auction::with('item')->get(),
            'auction_items' => Item::where('auction', true)->get(),
            'labels' => Label::all(),
            'auction_count' => Item::where('auction', true)->count(),
        ]);
    }
}
