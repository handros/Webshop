<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Label;
use App\Models\Auction;
use App\Models\User;

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
    public function index() {
        return view('home', [
            'items' => Item::all(),
            'auctions' => Auction::with('item')->orderBy('deadline', 'desc')->paginate(9),
            'auction_items' => Item::where('on_auction', true)->get(),
            'labels' => Label::all(),
            'auctionCount' => Item::where('on_auction', true)->count(),
            'opened_auctionCount' => Item::where('on_auction', true)
                ->whereHas('auction', function ($query) {
                    $query->where('deadline', '>=', now()->startOfDay());
                })->count(),
        ]);
    }

    public function about() {
        return view('about');
    }

    public function users() {
        if(Auth::guest()) {
            abort(401);
        }

        if(!Auth::user()->is_admin) {
            abort(403);
        }

        $users = User::orderBy('name')->get();

        return view('users', [
            'users' => $users,
        ]);
    }
}
