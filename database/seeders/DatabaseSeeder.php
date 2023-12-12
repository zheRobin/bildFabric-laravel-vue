<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Subscriptions\Database\Seeders\PlanSeeder;
use Modules\Translations\Database\Seeders\LanguageSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PlanSeeder::class,
            WelcomeSeeder::class,
            // LanguageSeeder::class,
        ]);
    }
}
