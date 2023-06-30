<?php

namespace Modules\OpenAI\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\OpenAI\Actions\BuildPrompt;
use Modules\OpenAI\Actions\CompleteCollectionItem;
use Modules\OpenAI\Contracts\BuildsPrompt;
use Modules\OpenAI\Contracts\CompletesCollectionItem;

class OpenAIServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public array $bindings = [
        CompletesCollectionItem::class => CompleteCollectionItem::class,
        BuildsPrompt::class => BuildPrompt::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->configureRoutes();
        $this->mergeConfigFrom(__DIR__ . '/../Config/openai.php', 'openai');
    }

    /**
     * Configure routes.
     */
    protected function configureRoutes(): void
    {
        Route::middleware('web')
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
            });
    }
}