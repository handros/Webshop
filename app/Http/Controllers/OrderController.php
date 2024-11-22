<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Image;
use App\Models\Label;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::guest()) {
            abort(401);
        }

        $userId = Auth::id();

        $myOrders = Order::where('orderer_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $myOrderCount = $myOrders->count();

        $orders = Order::with('orderer')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', [
            'orders' => $orders,
            'myOrders' => $myOrders,
            'orderCount' => Order::count(),
            'myOrderCount' => $myOrderCount,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(Auth::guest()) {
            abort(401);
        }

        return view('orders.create', [
            'labels' => Label::all(),
        ]);
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
            'description' => 'required|string|max:1000',
            'labels' => 'nullable|array',
            'labels.*' => 'numeric|integer|exists:labels,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:12288',
        ]);

        $order = new Order;
        $order->orderer_id = Auth::id();
        $order->description = $data['description'];
        $order->ready = false;

        $order->save();

        if(isset($data['labels'])) {
            $order->labels()->sync($data['labels']);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images'), $imageName);

                $newImage = new Image();
                $newImage->order_id = $order->id;
                $newImage->path = $imageName;
                $newImage->save();
            }
        }

        Session::flash('order_created', $order);
        return Redirect::route('orders.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        if(Auth::guest()) {
            abort(401);
        }

        if(!Auth::user()->is_admin && $order->orderer_id != Auth::id()) {
            abort(403);
        }

        $order->load(['orderer', 'labels']);

        $userId = Auth::id();
        $adminId = User::where('is_admin', true)->value('id');

        $messages = $order->messages()
            ->where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        $messageCount = $messages->count();

        return view('orders.show', compact('order', 'messages', 'messageCount', 'adminId'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        if(Auth::guest()) {
            abort(401);
        }

        if(!Auth::user()->is_admin) {
            abort(403);
        }

        $order->update(['ready' => $request->input('ready', 0)]);

        Session::flash('order_updated', $order);
        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        if(Auth::guest()) {
            abort(401);
        }

        if(!Auth::user()->is_admin) {
            abort(403);
        }

        foreach ($order->images as $image) {
            $filePath = public_path('images/' . $image->path);

            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        $order->delete();

        Session::flash('order_deleted', $order);
        return Redirect::back();
    }
}
