<?php

namespace Modules\RestApi\Actions;

use App\Models\Team;
use App\Models\User;
use DeepL\DeepLException;
use DeepL\Translator;
use Modules\Imports\Models\CollectionItem;
use Modules\Presets\Models\Preset;
use Modules\RestApi\Contracts\CompletesCollectionItem;
use Modules\Subscriptions\Enums\SubscriptionFeatureEnum;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponse;

class CompleteCollectionItem implements CompletesCollectionItem
{
    /**
     * @throws DeepLException
     * @throws \Exception
     */
    public function complete(User|Team $model, Preset $preset, CollectionItem $collectionItem, $translate, $sourceList)
    {
        if ($model instanceof User) {
            $model = $model->currentTeam;
        }

        if (!$this->validate($model)) {
            throw new \Exception(trans('Your team is out of remaining requests for this month. Please adjust your plan or wait until the next month.'), 403);
        }

        $systemMessage = $preset->system_prompt;
        $userMessage = $preset->user_prompt;

        if ($sourceList !== null) {
            foreach ($sourceList as $key => $value) {
                $systemMessage = str_replace($key, $value, $systemMessage);
                $userMessage = str_replace($key, $value, $userMessage);
            }
        }

        $params = $preset->getChatParams($systemMessage, $userMessage);

        // Handle OpenAI chat request
        $completion = OpenAI::chat()->create($params);

        // ------------------------------------------------
        // count subscription plan ------------------------
        $model->planSubscription->recordFeatureUsage(SubscriptionFeatureEnum::OPENAI_REQUESTS);
        $model->planSubscription->recordFeatureUsage(SubscriptionFeatureEnum::API_REQUESTS);
        // ------------------------------------------------

        $formattedResponse = $this->formatResponse($completion);

        $result['output'] = $formattedResponse;

        $translator = app(Translator::class);
        if (count($translate) > 0) {
            foreach ($translate as $lang) {
                $translatedText = $translator->translateText($formattedResponse, null, $lang);

                // ------------------------------------------------
                // count subscription plan ------------------------
                $model->planSubscription->recordFeatureUsage(SubscriptionFeatureEnum::DEEPL_REQUESTS);
                $model->planSubscription->recordFeatureUsage(SubscriptionFeatureEnum::API_REQUESTS);
                // ------------------------------------------------

                $result[$lang] = $translatedText->text;
            }
        }

        return $result;
    }

    protected function formatResponse(CreateResponse $response): string
    {
        $contents = [];

        foreach ($response->choices as $choice) {
            $contents[] = $choice->message->content;
        }

        return implode("\r\n----\r\n", $contents);
    }

    protected function validate(Team $team): bool
    {
        $planSubscription = $team->planSubscription;

        return $planSubscription->canUseFeature(SubscriptionFeatureEnum::OPENAI_REQUESTS) &&
            $planSubscription->canUseFeature(SubscriptionFeatureEnum::API_REQUESTS);
    }

}
