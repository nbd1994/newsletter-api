<?php

namespace App\Http\Controllers;

use App\GeminiService;
use App\Models\Post;
use App\Models\ScheduledPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScheduledPostController extends Controller
{
    public function schedulePost(Request $request)
    {
        $count = ScheduledPost::count();
        $needed = $count === 0 ? 3 : ($count < env('BUFFER_THRESHOLD', 2) ? 1 : 0);

        if ($needed > 0) {
            $gemini = app(GeminiService::class);

            for ($i = 0; $i < $needed; $i++) {
                try {
                    $body = $gemini->generatePostBody();
                    $title = $gemini->generateTitle($body);

                    ScheduledPost::create([
                        'title' => $title,
                        'body' => $body,
                        'created_by' => 1,
                    ]);
                } catch (\Throwable $e) {
                    Log::error('AI generation failed', [
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }

    public function autoPost()
    {
        $post = ScheduledPost::oldest()->first();
        // if($post){$this->comment($post);}


        if ($post) {
            Post::create([
                'title' => $post->title,
                'body' => $post->body, // Assuming created_by is the user ID
                'user_id' => $post->created_by,
            ]);
        }
        $post->delete();
    }
}
