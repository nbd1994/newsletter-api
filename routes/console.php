<?php

use App\GeminiService;
use App\Models\Post;
use App\Models\ScheduledPost;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('schedulePost', function () {
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
})->purpose('to periodically generate posts for later use');


Artisan::command('autoPost', function () {
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
})->purpose('to automatically post scheduled posts to the main posts table');
