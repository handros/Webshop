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
        if(Auth::user() == null or !Auth::user()->is_admin) {
            abort(401);
        }
        return view('labels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $names = Label::pluck('name')->toArray();

        $data = $request->validate([
            'name'=>[
                'required',
                Rule::notIn($names),
            ],
            'color'=>'required',
        ],
        );

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
        //
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
