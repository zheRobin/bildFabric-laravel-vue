<?php

namespace Modules\Presets\Traits;

use Modules\OpenAI\Enums\ChatRoleEnum;

trait HasOpenAIParams
{
    public function getChatParams(string $promptData, string $userMessage)
    {
        return array_filter([
            'model' => $this->model,
            'messages' => [
                [
                    'role' => ChatRoleEnum::USER->roleName(),
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $userMessage,
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => $promptData
                            ],
                        ]
                    ],
                ]
            ],
            'temperature' => $this->temperature,
            'top_p' => $this->top_p,
            'presence_penalty' => $this->presence_penalty,
            'frequency_penalty' => $this->frequency_penalty,
            'max_tokens' => config('openai.default-params.max_tokens')
        ]);
    }

    public function getDefaultChatParams(string $promptData, string $userMessage)
    {
        return [
            'messages' => [
                [
                    'role' => ChatRoleEnum::USER->roleName(),
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $userMessage,
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => $promptData
                            ],
                        ]
                    ],
                ]
            ],
            ...config('openai.default-params')
        ];
    }

    public function getSystemMessage()
    {
        return strval($this->system_prompt);
    }

    public function getUserMessage()
    {
        return strval($this->user_prompt);
    }
}
