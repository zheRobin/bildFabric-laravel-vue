<?php

namespace Modules\OpenAI\Enums;

enum ChatModelEnum: string
{
    case GPT_4_VISION_PREVIEW = 'gpt-4-vision-preview';

    /**
     * @return String[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
