<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Promptable;
use Stringable;

class PostEnhancerAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    public function instructions(): Stringable|string
    {
        return 'You are a helpful assistant that improves post titles, descriptions, and suggests relevant tags.
                Keep the meaning intact, make it engaging and professional.';
    }

    public function messages(): iterable
    {
        return [];
    }

    public function tools(): iterable
    {
        return [];
    }

    public function enhance(string $title, string $description, array $tags = []): array
    {
        $tagsText = !empty($tags) ? implode(', ', $tags) : 'none';

        $userMessage = "Improve the following post content and suggest better tags if needed:
Title: $title
Description: $description
Current Tags: $tagsText
Return the result as JSON: {\"title\": \"\", \"description\": \"\", \"tags\": [\"\", \"\"]}";

        // Use the prompt() method from Promptable trait
        // The system message is automatically included via instructions() method
        $response = $this->prompt(
            $userMessage,
            [],
            'openai',
            'gpt-4'
        );

        $result = json_decode($response->text, true);

        if (!$result) {
            return [
                'title' => $title,
                'description' => $description,
                'tags' => $tags,
            ];
        }

        return $result;
    }
}
