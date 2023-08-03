<?php

namespace Modules\OpenAI\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Modules\OpenAI\Enums\ChatModelEnum;
use Modules\Subscriptions\Enums\SubscriptionFeatureEnum;
use Modules\Translations\Models\Language;
use Modules\Presets\Models\Preset;

class OpenAIController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        return Inertia::render('OpenAI::Editor',[
            'presets' => $request->user()->currentCollection->presets ?? [],
            'selectedPreset' => session('preset'),
            'attributes' => $request->user()->currentCollection->headers ?? [],
            'models' => ChatModelEnum::values(),
            'languages' => Language::all(),
            'hasItems' => boolval($request->user()?->currentCollection?->items()->exists()),
            'permissions' => [
                'canManagePresets' => Gate::check('manage', Preset::class),
                'canChangeOpenAIParams' => $request->user()
                    ->currentTeam
                    ->planSubscription
                    ->canUseFeature(SubscriptionFeatureEnum::OPENAI_PARAMS),
            ]
        ]);
    }
}
