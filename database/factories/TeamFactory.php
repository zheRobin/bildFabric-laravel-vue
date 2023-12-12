<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Subscriptions\Models\Plan;
class TeamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Team::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company(),
            'user_id' => User::factory(),
            'personal_team' => true,
        ];
    }
    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Team $team) {
            // Get a random Plan
            $plan = Plan::query()->inRandomOrder()->first();

            // If a Plan exists, create a PlanSubscription for it
            if ($plan) {
                $team->newPlanSubscription($plan);
            } else {
                // If no Plan exists, you might want to handle it, by either creating a Plan here or throwing an exception
            }
        });
    }
}
