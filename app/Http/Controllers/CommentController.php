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
        $request->validate([
            'text' => 'required|string',
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
        // if ($comment->user_id !== Auth::id()) {
        //     abort(403);
        // }

        // return view('comments.edit', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        // if ($comment->user_id !== Auth::id()) {
        //     abort(403);
        // }

        // $request->validate([
        //     'text' => 'required|string',
        //     'rating' => 'nullable|integer|min:1|max:5',
        //     'item_id' => 'required|exists:items,id',
        // ]);

        // $comment->update([
        //     'text' => $request->text,
        //     'rating' => $request->rating,
        // ]);

        // return Redirect::route('items.show', $comment->item_id)->with('success', 'A komment sikeresen frissítve.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        if (!Auth::user()->is_admin or $comment->user_id !== auth()->id()) {
            abort(401);
        }

        $comment->delete();

        Session::flash('comment_deleted', $comment);
        return redirect()->back();
    }
}
