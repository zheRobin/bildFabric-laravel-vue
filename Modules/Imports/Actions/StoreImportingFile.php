<?php

namespace Modules\Imports\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use App\Rules\NonAnimatedGif;
use Modules\Imports\Contracts\StoresImportingFile;

class StoreImportingFile implements StoresImportingFile
{
    public function store(User $user, array $input): void
    {
        Gate::forUser($user)->authorize('update', $user->currentCollection);
        Validator::make($input, [
            'upload' => [
                'required',
                new NonAnimatedGif,
                File::types(['png', 'jpeg', 'jpg', 'webp', 'gif'])
                    ->max(15 * 1024),
            ],
            'append' => ['required', 'boolean']
        ])->after(function (\Illuminate\Validation\Validator $validator) use ($user, $input) {
            if (is_null($user->currentCollection)) {
                $validator->errors()->add(
                    'upload', 'You need to select a collection before importing.'
                );
            }
            if ($input['append'] || $user->currentCollection->items->isEmpty()) {
                $user->currentCollection->uploadImportFile($input['upload']);
            }
            if (!$input['append'] && $user->currentCollection->items->isNotEmpty()) {
                $user->currentCollection->items->each->delete();
                $user->currentCollection->uploadImportFile($input['upload']);
            }
        })->validateWithBag('importFile');
    }
}
