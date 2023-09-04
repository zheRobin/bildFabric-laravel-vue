<?php

namespace Modules\Compilations\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use App\Models\Compilations;
use Modules\Translations\Models\Language;

class CompilationsController extends Controller
{
    public function index(Request $request)
    {
        $compilations = Compilations::where('owner', $request->user()->current_team_id)
            ->where('collection_id', $request->user()->currentCollection?->id)
            ->get();

//        dd($compilations);
        // verify collection needed to be picked (or throw exception)
        return Inertia::render('Compilations::Index', [
            'presets' => $request->user()->currentCollection?->presets,
            'previewItem' => boolval($request->user()?->currentCollection?->items()->exists()) ? $request->user()->currentCollection?->items()->get()[0] : null,
            'previewItemLength' => $request->user()?->currentCollection?->items()->exists() ? count($request->user()->currentCollection?->items()->get()) : null,
            'complications' => $compilations,
            'languages' => Language::all(),
            'hasItems' => boolval($request->user()?->currentCollection?->items()->exists()),
            'permissions' => [
                'canManageCompilations' => Gate::check('manage', Compilations::class)
            ]
        ]);
    }

    public function getItem(Request $request): array
    {
        return [
            'item' => $request->user()->currentCollection?->items()->get()[(int)$request['id']]
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => ["required", "string", "min:3", "max:60"],
            "preset_ids" => ["array"],
        ]);

        $compilation = new Compilations();
        $compilation->name = $data['name'];
        $compilation->owner = $request->user()->current_team_id;
        $compilation->preset_ids = $data['preset_ids'];
        $compilation->collection_id = $request->user()->currentCollection->id;

        $compilation->save();

        // Optionally, you can redirect back or perform other actions after saving
        return redirect()->back()->with('success', 'Data saved successfully.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            "id" => ["required", "numeric"],
            "name" => ["string"],
            "owner" => ["numeric"],
            "preset_ids" => ["array"],
        ]);

        $compilation = Compilations::find($data['id']);

        if (!$compilation) {
            return redirect()->back()->withErrors('Compilation not found.');
        }

        $compilation->name = $data['name'];
        $compilation->owner = $data['owner'];
        $compilation->preset_ids = $data['preset_ids'];

        $compilation->save();

        // Optionally, you can redirect back or perform other actions after saving
        return redirect()->back()->with('success', 'Data saved successfully.');
    }

    public function delete(Request $request)
    {
        $compilation = Compilations::find($request['id']);

        $compilation->delete();
    }
}
