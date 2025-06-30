<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller {
    public function likePost(Request $request, Post $post) {
        $user = auth('api')->user();
        if ($post->likes()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Already liked'], 400);
        }
        $like = $post->likes()->create(['user_id' => $user->id]);
        return response()->json($like, 201);
    }

    public function unlikePost(Request $request, Post $post) {
        $user = auth('api')->user();
        $like = $post->likes()->where('user_id', $user->id)->first();
        if (!$like) {
            return response()->json(['error' => 'Not liked'], 400);
        }
        $like->delete();
        return response()->json(null, 204);
    }
}