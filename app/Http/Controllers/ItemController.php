<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\Item;
use App\Models\User;
use App\Models\Label;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('items.index', [
            'items' => Item::all(),
            'labels' => Label::all(),
            'user_count' => User::count(),
            'label_count' => Label::count(),
            'item_count' => Item::count(), //Item::total()  ??
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('items.create', [
            'labels' => Label::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required',
            'made_in'=> [
                'required',
                'numeric',
                'before_or_equal:' . now()->format('Y'),
            ],
            'description'=> [
                'required',
                'string',
                'max:1000',
            ],
            'labels' => [
                'nullable',
                'array',
            ],
            'labels.*' => [
                'numeric',
                'integer',
                'exists:labels,id'
            ],
            'image' => [
                'required',
                'file',
                'image',
                'max:5120'
            ],
        ]);

        $image = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image = $file->store('images', ['disk' => 'public']);
        }

        $item = new Item;
        $item->name = $data['name'];
        $item->made_in = $data['made_in'];
        $item->description = $data['description'];
        $item->image = $image;

        $item->save();

        if(isset($data['labels'])) {
            $item->labels()->sync($data['labels']);
        }

        Session::flash('item_created', $item);

        return Redirect::route('items.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('items.show', [
            'item' => Item::find($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('items.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
