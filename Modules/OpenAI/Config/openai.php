<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OpenAI API Key and Organization
    |--------------------------------------------------------------------------
    |
    | Here you may specify your OpenAI API Key and organization. This will be
    | used to authenticate with the OpenAI API - you can find your API key
    | and organization on your OpenAI dashboard, at https://openai.com.
    */

    'api_key' => env('OPENAI_API_KEY'),
    'organization' => env('OPENAI_ORGANIZATION'),

    'default-params' => [
        'model' => 'gpt-4-vision-preview',
        'temperature' => 1,
        'top_p' => 1,
        'presence_penalty' => 0,
        'frequency_penalty' => 0,
        'max_tokens' => 300,
    ]
];
