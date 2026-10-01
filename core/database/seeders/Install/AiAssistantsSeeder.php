<?php

namespace Database\Seeders\Install;

/**
 * Reference data for the ai_assistants table, generated from install/database.sql.
 *
 * Primary keys are written explicitly because other seeded tables reference them by id.
 */
class AiAssistantsSeeder extends InstallSeeder
{
    public function run(): void
    {
        $this->insertRows('ai_assistants', [
            ['id' => '1', 'name' => 'Open AI', 'info' => 'OpenAI provides advanced AI models capable of generating human-like text. It powers chatbots, content creation.', 'provider' => 'openai', 'config' => '{"api_key":"------------------","model":"gpt-4o-mini","temperature":"0.7"}', 'url' => 'https://platform.openai.com/api-keys', 'status' => '0', 'created_at' => null, 'updated_at' => '2025-09-30 13:49:07'],
            ['id' => '2', 'name' => 'Google Gemini', 'info' => 'Google Gemini delivers powerful AI models designed for reasoning. It supports chat, content generation, and automation.', 'provider' => 'gemini', 'config' => '{"api_key":"-------------","temperature":"0.7","model":"gemini-2.5-flash","max_output_tokens":"512"}', 'url' => 'https://aistudio.google.com/app/api-keys', 'status' => '0', 'created_at' => null, 'updated_at' => '2025-09-30 13:49:07'],
        ]);
    }
}
