<?php

namespace Modules\RestApi\Controllers;

use App\Http\Controllers\Controller;
use DeepL\Translator;
use Illuminate\Http\Request;
use Modules\Imports\Models\CollectionItem;
use Modules\RestApi\Contracts\CompletesCollectionItem;
use Modules\Presets\Models\Preset;


class RestApiController extends Controller
{
    public function generate(Request $request, Preset $preset, CollectionItem $item)
    {
        $completer = app(CompletesCollectionItem::class);

        if(!isset($preset->get()->where('id', $request['preset-id'])->where('collection_id', $request->user()->currentCollection->id)->first()->name)){
            return 'Such a preset does not exist';
        }

        $response = $completer->complete(
            $request->user(),
            $preset->get()->where('id', $request['preset-id'])->where('collection_id', $request->user()->currentCollection->id)->first(),
            $item->get()->where('collection_id', $request->user()->currentCollection->id)->first(),
            $request['translate-target-list'],
            $request['source-list']
        );

        return response()->json(
            $response
        );
    }

    public function translate(Request $request)
    {
        $result = [];

        $translator = app(Translator::class);

        foreach ($request['translate-target-list'] as $lang) {
            $translatedText = $translator->translateText($request['text'], null, $lang);
            $result[$lang] = $translatedText->text;
        }

        return $result;
    }
}
