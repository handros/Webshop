<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Item;
use App\Models\User;
use App\Models\Label;
use App\Models\Image;
use App\Models\Auction;
use App\Models\Order;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orderBy = $request->query('order_by', 'created_at');
        $sortOrder = $request->query('sort', 'desc');

        $items = Item::query()
            ->when($orderBy, function ($query) use ($orderBy, $sortOrder) {
                return $query->orderBy($orderBy, $sortOrder);
            })
            ->paginate(9);

        $items = $items->appends(['order_by' => $orderBy, 'sort' => $sortOrder]);

        return view('items.index', [
            'items' => $items,
            'labels' => Label::all(),
            'userCount' => User::count(),
            'labelCount' => Label::count(),
            'itemCount' => Item::count(),
            'auctionCount' => Auction::count(),
            'orderCount' => Order::count(),
            'orderReadyCount' => Order::where('ready', true)->count(),
        ]);
    }

    /**
     * Search based on name of an Item or Label.
     */
    public function search(Request $request) {
        $query = $request->input('query');

        $items = Item::where('name', 'like', '%' . $query . '%')
            ->orWhereHas('labels', function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%');
            })
            ->paginate(9);

        return view('items.search', compact('items', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(Auth::guest()) {
            abort(401);
        }

        if(!Auth::user()->is_admin) {
            abort(403);
        }

        return view('items.create', [
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

        if(!Auth::user()->is_admin) {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:30',
            'made_in' => [
                'required',
                'numeric',
                'before_or_equal:' . now()->year,
            ],
            'description' => 'nullable|string|max:1000',
            'labels' => 'nullable|array',
            'labels.*' => 'numeric|integer|exists:labels,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:12288',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:12288',
        ]);

        $image = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image = $file->store('images', ['disk' => 'public']);
        }

        $item = new Item;
        $item->name = $data['name'];
        $item->made_in = $data['made_in'];
        $item->description = $data['description'] ?? '';
        $item->on_auction = false;
        $item->image = $image;

        $item->save();

        if(isset($data['labels'])) {
            $item->labels()->sync($data['labels']);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images'), $imageName);

                $newImage = new Image();
                $newImage->item_id = $item->id;
                $newImage->path = $imageName;
                $newImage->save();
            }
        }

        Session::flash('item_created', $item);
        return Redirect::route('items.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Item::findOrFail($id);
        $comments = $item->comments()->orderBy('created_at', 'desc')->get();

        $commentCount = $comments->count();

        return view('items.show', compact('item', 'comments', 'commentCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if(Auth::guest()) {
            abort(401);
        }

        if(!Auth::user()->is_admin) {
            abort(403);
        }

        return view('items.edit', [
            'item' => Item::find($id),
            'labels' => Label::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(Auth::guest()) {
            abort(401);
        }

        if(!Auth::user()->is_admin) {
            abort(403);
        }

        $item = Item::find($id);
        $data = $request->validate([
            'name' => 'required|string|max:30',
            'made_in' => 'required|numeric|before_or_equal:' . now()->format('Y'),
            'description' => 'nullable|string|max:1000',
            'labels' => 'nullable|array',
            'labels.*' => 'numeric|integer|exists:labels,id',
        ]);

        $item->name = $data['name'];
        $item->made_in = $data['made_in'];
        $item->description = $data['description'] ?? '';
        $item->save();

        if(isset($data['labels'])) {
            $item->labels()->sync($data['labels']);
        }

        Session::flash('item_updated', $item);
        return Redirect::route('items.show', $item);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(Auth::guest()) {
            abort(401);
        }

        if(!Auth::user()->is_admin) {
            abort(403);
        }

        $item = Item::findOrFail($id);

        foreach ($item->images as $image) {
            $filePath = public_path('images/' . $image->path);

            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        Session::flash('item_deleted', $item);
        return Redirect::route('items.index');
    }
}
