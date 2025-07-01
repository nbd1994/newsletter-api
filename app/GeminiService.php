<?php

namespace App;


use Illuminate\Support\Facades\Http;

class GeminiService
{
    protected $apiKey;
    protected $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    public function generatePostBody(): string
    {
        $prompt = "You are TechTickler, a sarcastic yet knowledgeable coder who writes engaging newsletter posts about software development, focusing on web and app development. 

Write a single, 300t o 400-word post in a humorous, witty tone. Include practical tips, sprinkle in tech jokes, and keep it accessible for intermediate developers. Only return the article body. Do not include titles, greetings, sign-offs, subject lines, or markdown formatting.
";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '?key=' . $this->apiKey, [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $prompt
                        ]
                    ]
                ]
            ]
        ]);

        $data = $response->json();

        // Check for errors
        if (isset($data['error'])) {
            return 'Error: ' . ($data['error']['message'] ?? 'Unknown error');
        }

        return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No body generated.';
    }

    public function generateTitle(string $body): string
    {
        $prompt = "Based on the following article, generate a short, catchy, and descriptive title that fits the sarcastic, humorous tone of the article. Only return the title text itself — one single sentence. Do not include multiple options, commentary, formatting, or any explanations.\n\n" . $body;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '?key=' . $this->apiKey, [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $prompt
                        ]
                    ]
                ]
            ]
        ]);

        $data = $response->json();

        if (isset($data['error'])) {
            return 'Error: ' . ($data['error']['message'] ?? 'Unknown error');
        }

        return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Untitled Post';
    }
}
