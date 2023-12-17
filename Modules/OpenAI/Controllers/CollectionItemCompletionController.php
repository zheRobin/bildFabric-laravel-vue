<?php

namespace Modules\OpenAI\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Imports\Models\CollectionItem;
use Modules\OpenAI\Contracts\BuildsParams;
use Modules\OpenAI\Contracts\CompletesItemStreamed;
use Modules\Presets\Models\Preset;
use Modules\Subscriptions\Enums\SubscriptionFeatureEnum;

class CollectionItemCompletionController extends Controller
{
    public function complete(Request $request, Preset $preset, CollectionItem $item)
    {
        $builder = app(BuildsParams::class);
        $completer = app(CompletesItemStreamed::class);
        $params = $builder->build($request->user(), $preset, $item);

        return response()->stream(function () use ($completer, $params, $request) {
            $completer->complete($request->user(), $params);
        }, 200, [
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Content-Type' => 'text/event-stream',
            'Content-Encoding' => 'none',
        ]);
    }
}
