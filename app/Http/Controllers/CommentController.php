<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
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
        $request->validate([
            'text' => 'required|string|max:500',
            'rating' => 'nullable|integer|min:1|max:5',
            'item_id' => 'required|exists:items,id',
        ]);

        $comment = new Comment();
        $comment->text = $request->text;
        $comment->rating = $request->rating;
        $comment->user_id = Auth::id();
        $comment->item_id = $request->item_id;
        $comment->save();

        Session::flash('comment_created', $comment);
        return Redirect::route('items.show', $request->item_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        if(Auth::guest()) {
            abort(401);
        }

        if (!Auth::user()->is_admin && $comment->user_id !== Auth::id()) {
            abort(403);
        }

        $comment->delete();

        Session::flash('comment_deleted', $comment);
        return Redirect::back();
    }
}
