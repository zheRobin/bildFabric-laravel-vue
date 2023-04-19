<?php

namespace Modules\Subscriptions\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Subscriptions\Contracts\CreatesPlan;
use Modules\Subscriptions\Enums\SubscriptionPlanEnum;
use Modules\Subscriptions\Models\PlanFeature;

class PlanSeeder extends Seeder
{
    /**
     * Run the Database seeds.
     */
    public function run(CreatesPlan $createPlan): void
    {
        $basic = $createPlan([
            'slug' => SubscriptionPlanEnum::BASE->slug(),
            'name' => 'Basic plan',
            'description' => null,
            'is_active' => true,
            'trial_period' => 30,
            'invoice_period' => 0,
        ]);

        $basic->features()->saveMany([
            new PlanFeature(['slug' => 'basic-collections-limit', 'name' => 'Collections', 'description' => 'up to 1 collection', 'value' => 1]),
        ]);

        $pro = $createPlan([
            'slug' => SubscriptionPlanEnum::PRO->slug(),
            'name' => 'Pro plan',
            'description' => null,
            'is_active' => true,
            'trial_period' => 30,
            'invoice_period' => 0,
        ]);

        $pro->features()->saveMany([
            new PlanFeature(['slug' => 'pro-collections-limit', 'name' => 'Collections', 'description' => 'up to 5 collections', 'value' => 5]),
        ]);

        $enterprise = $createPlan([
            'slug' => SubscriptionPlanEnum::ENTERPRISE->slug(),
            'name' => 'Enterprise plan',
            'description' => null,
            'is_active' => true,
            'trial_period' => 30,
            'invoice_period' => 0,
        ]);

        $enterprise->features()->saveMany([
            new PlanFeature(['slug' => 'enterprise-collections-limit', 'name' => 'Collections', 'description' => 'up to 12 collections', 'value' => 12]),
        ]);
    }
}
