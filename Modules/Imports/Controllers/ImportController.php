<?php

namespace Modules\Imports\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Modules\Imports\Contracts\ImportsImage;
use Modules\Imports\Contracts\StoresImportingFile;
use Modules\Imports\Services\ImporterFactory;

class ImportController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Imports::Index', [
//            'headers' => $request->user()->currentCollection->headers,
            'items' =>  $request->user()->currentCollection?->items()->paginate(5)->onEachSide(2),
            'permissions' => [
                'canUpdateCollection' => Gate::check('update', $request->user()->currentCollection),
            ]
        ]);
    }

    public function store(Request $request, StoresImportingFile $uploader)
    {
        $uploader->store($request->user(), $request->all());
        return back(303);
    }

    public function importImages(Request $request)
    {
        $importer = app(ImportsImage::class);
        $importer->import($request->user(), $request->user()->currentCollection, $request->all());

        return back(303);
    }
}
