<?php

return [
    'api_key' => env('OPENAI_API_KEY'),
    'organization' => env('OPENAI_ORGANIZATION'),
    'model' => 'gpt-3.5-turbo',
    'temperature' => 0.7,
    'max_tokens' => 1000,
]; 