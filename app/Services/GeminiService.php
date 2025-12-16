<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class GeminiService
{
    protected string $apiKey;
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->apiUrl = config('services.gemini.api_url');
    }

    /**
     * Gemini APIでテキストを生成
     *
     * @param string $prompt プロンプト
     * @param array $options 追加オプション
     * @return array APIレスポンス
     * @throws RequestException
     */
    public function generateContent(string $prompt, array $options = []): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl . '?key=' . $this->apiKey, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => array_merge([
                'temperature' => 0.7,
                'maxOutputTokens' => 2048,
            ], $options),
        ]);

        $response->throw();

        return $response->json();
    }

    /**
     * レスポンスからテキストを抽出
     *
     * @param array $response generateContentのレスポンス
     * @return string|null 生成されたテキスト
     */
    public function extractText(array $response): ?string
    {
        return $response['candidates'][0]['content']['parts'][0]['text'] ?? null;
    }

    /**
     * シンプルなテキスト生成
     *
     * @param string $prompt プロンプト
     * @return string|null 生成されたテキスト
     */
    public function ask(string $prompt): ?string
    {
        $response = $this->generateContent($prompt);
        return $this->extractText($response);
    }
}
