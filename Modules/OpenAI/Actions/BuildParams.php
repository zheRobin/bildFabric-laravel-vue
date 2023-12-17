<?php

namespace Modules\OpenAI\Actions;

use App\Models\User;
use Modules\Imports\Models\CollectionItem;
use Modules\OpenAI\Contracts\BuildsParams;
use Modules\OpenAI\Contracts\BuildsPrompt;
use Modules\OpenAI\Services\PromptService;
use Modules\Presets\Models\Preset;
use Modules\Subscriptions\Enums\SubscriptionFeatureEnum;

class BuildParams implements BuildsParams
{
    public function build(User $user, Preset $preset, CollectionItem $collectionItem): array
    {
        $promptService = app(PromptService::class);
        $promptData = $promptService->getData($collectionItem);
        $hasOwnParams = $user->currentTeam->planSubscription->canUseFeature(SubscriptionFeatureEnum::OPENAI_PARAMS);

        return $hasOwnParams
            ? $preset->getChatParams($promptData, $preset->getUserMessage())
            : $preset->getDefaultChatParams($promptData, $preset->getUserMessage());
    }
}
