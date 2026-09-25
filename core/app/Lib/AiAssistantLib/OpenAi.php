<?php

namespace App\Lib\AiAssistantLib;

use App\Models\AiAssistant;
use Exception;
use Illuminate\Support\Facades\Http;

class OpenAi
{
    protected $apiKey;
    protected int $temperature;
    protected $model;

    public function __construct()
    {
        $assistant = AiAssistant::where('provider', 'openai')->active()->first();
        if ($assistant) {
            $config = (object) $assistant->config;

            $this->apiKey       = $config->api_key ?? null;
            $this->temperature  = $config->temperature ?? 0.7;
            $this->model        = $config->model ?? 'gpt-4o-mini';
        }
    }

    public function getAiReply(string $systemPrompt, string $prompt)
    {
        try {

            $systemPrompt = strip_tags($systemPrompt);

            $prompt = strip_tags($prompt);

            $response = Http::withToken($this->apiKey)
                ->post($this->getApiUrl(), [
                    'model'       => $this->model,
                    'temperature' => $this->temperature,
                    'messages'    => [
                        [
                            'role'    => 'system',
                            'content' => $systemPrompt,
                        ],
                        [
                            'role'    => 'user',
                            'content' => $prompt,
                        ],
                    ],
                ]);

            $data = $response->json();

            if (isset($data['error'])) {
                throw new Exception($data['error']['message']) ?? "Something went wrong";
            }

            if (!isset($data['choices'][0]['message']['content'])) {
                throw new Exception("Unable to generate response");
            }

            if ($response->successful()) {
                return [
                    'response' => $data['choices'][0]['message']['content'] ?? null,
                    'success'  => true
                ];
            }
        } catch (Exception $e) {
            return [
                'response' => $e->getMessage(),
                'success'  => false
            ];
        }
    }

    public function getTranslatedText(string $text)
    {
        try {
            $prompt = "Translate this message to English. Note: If the text already in English then make it's grammar correct. If unable to translate the text please do not return anything, not event a empty string.";
            $response = Http::withToken($this->apiKey)
                ->post($this->getApiUrl(), [
                    'model'       => $this->model,
                    'temperature' => $this->temperature,
                    'messages'    => [
                        [
                            'role'    => 'system',
                            'content' => $prompt,
                        ],
                        [
                            'role'    => 'user',
                            'content' => $text,
                        ],
                    ],
                ]);

            $data = $response->json();

            if (isset($data['error'])) {
                throw new Exception($data['error']['message']) ?? "Something went wrong";
            }

            if (!isset($data['choices'][0]['message']['content'])) {
                throw new Exception("Unable to generate response");
            }

            if ($response->successful()) {
                return [
                    'response' => $data['choices'][0]['message']['content'] ?? null,
                    'success'  => true
                ];
            }
        } catch (Exception $e) {
            return [
                'response' => $e->getMessage(),
                'success'  => false
            ];
        }
    }

    private function getApiUrl()
    {
        return 'https://api.openai.com/v1/chat/completions';
    }
}
