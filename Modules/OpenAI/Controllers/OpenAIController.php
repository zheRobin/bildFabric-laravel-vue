<?php

namespace Modules\OpenAI\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\OpenAI\Enums\ChatModelEnum;
use Modules\Translations\Models\Language;

class OpenAIController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('OpenAI::Editor',[
            'presets' => $request->user()->currentCollection->presets,
            'selectedPreset' => session('preset'),
            'previewItem' => $request->user()->currentCollection->items()->first(),
            'models' => ChatModelEnum::values(),
            'languages' => Language::all(),
        ]);
    }
}
