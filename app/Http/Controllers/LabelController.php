<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Label;

class LabelController extends Controller
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
            abort(403);
        }
        return view('labels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(Auth::guest() or !Auth::user()->is_admin) {
            abort(403);
        }

        $names = Label::pluck('name')->toArray();

        $data = $request->validate([
            'name'=>[
                'required',
                Rule::notIn($names),
            ],
            'color'=>'required',
        ]);

        $label = Label::create($data);

        Session::flash('label_created', $label);

        return Redirect::route('labels.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('labels.show', [
            'label' => Label::find($id),
            'labels' => Label::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if(Auth::guest() or !Auth::user()->is_admin) {
            abort(403);
        }
        return view('labels.edit', [
            'label' => Label::find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(Auth::guest() or !Auth::user()->is_admin) {
            abort(403);
        }

        $label = Label::find($id);
        $temp_names = Label::pluck('name')->toArray();
        $names = array_values(array_diff($temp_names, [$label->name]));

        $data = $request->validate([
            'name'=>[
                'required',
                Rule::notIn($names),
            ],
            'color'=>'required',
        ]);

        $label->update($data);

        Session::flash('label_updated', $label);
        return Redirect::route('labels.show', $label);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
