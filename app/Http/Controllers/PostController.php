<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller {
    public function index() {
        $posts = Post::with(['user', 'comments', 'likes'])->get();
        return response()->json($posts);
        // return 'hit hit';
    }

    public function search(Request $request) {
        $query = $request->query('q');
        if (!$query) {
            return response()->json(['error' => 'Search query required'], 400);
        }
        $posts = Post::where('title', 'like', "%{$query}%")
            ->orWhere('body', 'like', "%{$query}%")
            ->with(['user', 'comments', 'likes'])
            ->get();
        return response()->json($posts);
    }

    public function likedPosts() {
        $posts = auth()->user()->likes()->with('post')->get()->pluck('post');
        return response()->json($posts);
    }

    public function commentedPosts() {
        $posts = auth()->user()->comments()->with('post')->get()->pluck('post')->unique('id');
        return response()->json($posts);
    }

    public function show(Post $post) {
        return response()->json($post->load(['user', 'comments', 'likes']));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);
        $post = auth()->user()->posts()->create($validated);
        return response()->json($post, 201);
    }

    public function update(Request $request, Post $post) {
        $this->authorize('update', $post);
        $validated = $request->validate([
            'title' => 'string|max:255',
            'body' => 'string',
        ]);
        $post->update($validated);
        return response()->json($post);
    }

    public function destroy(Post $post) {
        $this->authorize('delete', $post);
        $post->delete(); // Cascades to comments and likes
        return response()->json(null, 204);
    }
}