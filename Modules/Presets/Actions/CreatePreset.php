<?php

namespace Modules\Presets\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Modules\Collections\Models\Collection;
use Modules\Presets\Contracts\CreatesPreset;
use Modules\Presets\Data\PresetInput;
use Modules\Presets\Models\Preset;
use Illuminate\Validation\Rule;

class CreatePreset implements CreatesPreset
{
    public function create(User $user, PresetInput $presetInput): Preset
    {
        $collection = Collection::find($presetInput->collection_id);
        Gate::forUser($user)->authorize('create', [Preset::class, $collection]);
        Validator::make($presetInput->all(), [
            'name' => [
                'required',
                'string',
                Rule::unique('presets')->where('collection_id', $presetInput->collection_id)
            ]
        ])->validate();
        return Preset::create($presetInput->all());
    }

    public function redirectTo(): string
    {
        return route('editor.index');
    }
}
