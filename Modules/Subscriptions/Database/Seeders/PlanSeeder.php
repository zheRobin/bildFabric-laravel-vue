<?php

namespace Modules\Subscriptions\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Subscriptions\Contracts\CreatesPlan;
use Modules\Subscriptions\Enums\ResettableIntervalEnum;
use Modules\Subscriptions\Enums\SubscriptionFeatureEnum;
use Modules\Subscriptions\Enums\SubscriptionPlanEnum;
use Modules\Subscriptions\Models\PlanFeature;

class PlanSeeder extends Seeder
{
    /**
     * Run the Database seeds.
     */
    public function run(CreatesPlan $createPlan): void
    {
        $trial = $createPlan([
            'slug' => SubscriptionPlanEnum::TRIAL->slug(),
            'name' => 'Trial plan',
            'description' => null,
            'is_active' => true,
            'trial_period' => 14,
            'invoice_period' => 0,
        ]);

        $trial->features()->saveMany([
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::COLLECTIONS_LIMIT->slug(),
                'name' => 'Collections',
                'description' => 'up to 2 collection',
                'value' => 2
            ]),
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::COLLECTION_ITEMS_LIMIT->slug(),
                'name' => 'Collection Items',
                'description' => 'up to 5 items per collection',
                'value' => 5
            ]),
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::API_REQUESTS->slug(),
                'name' => 'API requests',
                'description' => 'up to 300 request per month',
                'value' => 300,
                'resettable_interval' => ResettableIntervalEnum::MONTH->value,
                'resettable_period' => 1,
            ]),
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::OPENAI_REQUESTS->slug(),
                'name' => 'OpenAI requests',
                'description' => 'up to 300 request per month',
                'value' => 300,
                'resettable_interval' => ResettableIntervalEnum::MONTH->value,
                'resettable_period' => 1,
            ]),
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::DEEPL_REQUESTS->slug(),
                'name' => 'DeepL requests',
                'description' => 'up to 300 request per month',
                'value' => 300,
                'resettable_interval' => ResettableIntervalEnum::MONTH->value,
                'resettable_period' => 1,
            ]),
        ]);

        $basic = $createPlan([
            'slug' => SubscriptionPlanEnum::BASE->slug(),
            'name' => 'Basic plan',
            'description' => null,
            'is_active' => true,
            'trial_period' => 14,
            'invoice_period' => 0,
        ]);

        $basic->features()->saveMany([
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::COLLECTIONS_LIMIT->slug(),
                'name' => 'Collections',
                'description' => 'up to 20 collection',
                'value' => 20
            ]),
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::COLLECTION_ITEMS_LIMIT->slug(),
                'name' => 'Collection Items',
                'description' => 'up to 100 items per collection',
                'value' => 100
            ]),
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::API_REQUESTS->slug(),
                'name' => 'API requests',
                'description' => 'up to 3.000 request per month',
                'value' => 3000,
                'resettable_interval' => ResettableIntervalEnum::MONTH->value,
                'resettable_period' => 1,
            ]),
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::OPENAI_REQUESTS->slug(),
                'name' => 'OpenAI requests',
                'description' => 'up to 3.000 request per month',
                'value' => 3000,
                'resettable_interval' => ResettableIntervalEnum::MONTH->value,
                'resettable_period' => 1,
            ]),
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::DEEPL_REQUESTS->slug(),
                'name' => 'DeepL requests',
                'description' => 'up to 3.000 request per month',
                'value' => 3000,
                'resettable_interval' => ResettableIntervalEnum::MONTH->value,
                'resettable_period' => 1,
            ]),
        ]);

        $pro = $createPlan([
            'slug' => SubscriptionPlanEnum::PRO->slug(),
            'name' => 'Pro plan',
            'description' => null,
            'is_active' => true,
            'trial_period' => 14,
            'invoice_period' => 0,
        ]);

        $pro->features()->saveMany([
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::COLLECTIONS_LIMIT->slug(),
                'name' => 'Collections',
                'description' => 'up to 5 collections',
                'value' => 5
            ]),
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::COLLECTION_ITEMS_LIMIT->slug(),
                'name' => 'Collection Items',
                'description' => 'up to 50 items per collection',
                'value' => 50
            ]),
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::API_REQUESTS->slug(),
                'name' => 'API requests',
                'description' => 'up to 10.000 request per month',
                'value' => 10000,
                'resettable_interval' => ResettableIntervalEnum::MONTH->value,
                'resettable_period' => 1,
            ]),
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::OPENAI_REQUESTS->slug(),
                'name' => 'OpenAI requests',
                'description' => 'up to 10.000 request per month',
                'value' => 10000,
                'resettable_interval' => ResettableIntervalEnum::MONTH->value,
                'resettable_period' => 1,
            ]),
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::DEEPL_REQUESTS->slug(),
                'name' => 'DeepL requests',
                'description' => 'up to 10.000 request per month',
                'value' => 10000,
                'resettable_interval' => ResettableIntervalEnum::MONTH->value,
                'resettable_period' => 1,
            ]),
        ]);

        $enterprise = $createPlan([
            'slug' => SubscriptionPlanEnum::ENTERPRISE->slug(),
            'name' => 'Enterprise plan',
            'description' => null,
            'is_active' => true,
            'trial_period' => 14,
            'invoice_period' => 0,
        ]);

        $enterprise->features()->saveMany([
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::COLLECTIONS_LIMIT->slug(),
                'name' => 'Collections',
                'description' => 'up to 15 collections',
                'value' => 15
            ]),
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::COLLECTION_ITEMS_LIMIT->slug(),
                'name' => 'Collection Items',
                'description' => 'up to 150 items per collection',
                'value' => 150
            ]),
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::API_REQUESTS->slug(),
                'name' => 'API requests',
                'description' => 'up to 30.000 request per month',
                'value' => 30000,
                'resettable_interval' => ResettableIntervalEnum::MONTH->value,
                'resettable_period' => 1,
            ]),
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::OPENAI_REQUESTS->slug(),
                'name' => 'OpenAI requests',
                'description' => 'up to 30.000 request per month',
                'value' => 30000,
                'resettable_interval' => ResettableIntervalEnum::MONTH->value,
                'resettable_period' => 1,
            ]),
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::OPENAI_PARAMS->slug(),
                'name' => 'OpenAI params',
                'description' => 'possibility to configure parameters',
                'value' => true,
            ]),
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::DEEPL_REQUESTS->slug(),
                'name' => 'DeepL requests',
                'description' => 'up to 30.000 request per month',
                'value' => 30000,
                'resettable_interval' => ResettableIntervalEnum::MONTH->value,
                'resettable_period' => 1,
            ]),
        ]);

        $unlimited = $createPlan([
            'slug' => SubscriptionPlanEnum::UNLIMITED->slug(),
            'name' => 'Unlimited plan',
            'description' => null,
            'is_active' => true,
            'trial_period' => 14,
            'invoice_period' => 0,
        ]);

        $unlimited->features()->saveMany([
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::COLLECTIONS_LIMIT->slug(),
                'name' => 'Collections',
                'description' => 'up to 100 collections',
                'value' => 100
            ]),
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::COLLECTION_ITEMS_LIMIT->slug(),
                'name' => 'Collection Items',
                'description' => 'up to 500 items per collection',
                'value' => 500
            ]),
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::API_REQUESTS->slug(),
                'name' => 'API requests',
                'description' => 'up to 500.000 request per month',
                'value' => 500000,
                'resettable_interval' => ResettableIntervalEnum::MONTH->value,
                'resettable_period' => 1,
            ]),
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::OPENAI_REQUESTS->slug(),
                'name' => 'OpenAI requests',
                'description' => 'up to 500.000 request per month',
                'value' => 500000,
                'resettable_interval' => ResettableIntervalEnum::MONTH->value,
                'resettable_period' => 1,
            ]),
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::OPENAI_PARAMS->slug(),
                'name' => 'OpenAI params',
                'description' => 'possibility to configure parameters',
                'value' => true,
            ]),
            new PlanFeature([
                'slug' => SubscriptionFeatureEnum::DEEPL_REQUESTS->slug(),
                'name' => 'DeepL requests',
                'description' => 'up to 500.000 request per month',
                'value' => 500000,
                'resettable_interval' => ResettableIntervalEnum::MONTH->value,
                'resettable_period' => 1,
            ]),
        ]);

    }
}
