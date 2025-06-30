<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller {
    public function store(Request $request, Post $post) {
        $validated = $request->validate(['body' => 'required|string']);
        $comment = $post->comments()->create([
            'user_id' => auth('api')->id(),
            'body' => $validated['body'],
        ]);
        return response()->json($comment, 201);
    }

    public function update(Request $request, Comment $comment) {
        // $this->authorize('update', $comment);
        $validated = $request->validate(['body' => 'required|string']);
        $comment->update($validated);
        return response()->json($comment);
    }

    public function destroy(Comment $comment) {
        $this->authorize('delete', $comment);
        $comment->delete();
        return response()->json(null, 204);
    }
}