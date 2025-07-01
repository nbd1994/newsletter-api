<?php

namespace App\Http\Controllers;

use App\GeminiService;
use Illuminate\Http\Request;

class AIController extends Controller
{

    public function generatePreview(GeminiService $gemini)
    {
        $body = $gemini->generatePostBody();
        $title = $gemini->generateTitle($body);

        return response()->json(['title' => $title, 'body' => $body]);
    }
}
