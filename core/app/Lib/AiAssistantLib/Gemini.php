<?php

namespace App\Lib\AiAssistantLib;

use App\Models\AiAssistant;
use Exception;
use Illuminate\Support\Facades\Http;

class Gemini
{
    protected $apiKey;
    protected $model;
    protected $temperature;
    protected $maxOutputTokens;

    public function __construct()
    {
        $assistant = AiAssistant::where('provider', 'gemini')->active()->first();

        if ($assistant) {
            $config = (object) $assistant->config;

            $this->apiKey           = $config->api_key ?? null;
            $this->model            = $config->model ?? 'gemini-2.5-flash';
            $this->temperature      = $config->temperature ?? 0.7;
            $this->maxOutputTokens  = $config->max_output_tokens;
        }
    }

    public function getAiReply(string $systemPrompt, string $prompt)
    {
        $systemPrompt = strip_tags($systemPrompt)."\n" . "Note: if the question/query is out of the box e-commerce, then please respond empty(do not reply to anything, not even a string)";
        $prompt = strip_tags($prompt);

        try {
            $url = $this->getApiUrl() . '?key=' . $this->apiKey;

            $response = Http::post($url, [
                'system_instruction' => [
                    'parts' => [
                        ['text' => $systemPrompt]
                    ]
                ],
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                "generationConfig" => [
                    "temperature" => $this->temperature
                ]
            ]);

            $data = $response->json();

            if (isset($data['error'])) {
                throw new Exception($data['error']['message'] ?? 'Something went wrong');
            }

            $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$reply) {
                throw new Exception('Unable to generate response');
            }

            return [
                'response' => $reply,
                'success'  => true
            ];
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
            $url = $this->getApiUrl() . '?key=' . $this->apiKey;
            $response = Http::post($url, [
                'system_instruction' => [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ],
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $text]
                        ]
                    ]
                ]
            ]);

            $data = $response->json();

            if (isset($data['error'])) {
                throw new Exception($data['error']['message'] ?? 'Something went wrong');
            }

            $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$reply) {
                throw new Exception('Unable to generate response');
            }

            return [
                'response' => $reply,
                'success'  => true
            ];
        } catch (Exception $e) {
            return [
                'response' => $e->getMessage(),
                'success'  => false
            ];
        }
    }


    private function getApiUrl()
    {
        return "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";
    }

}
