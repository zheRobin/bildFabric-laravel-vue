<?php

namespace Modules\Imports\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use App\Rules\NonAnimatedGif;
use Modules\Imports\Contracts\StoresImportingFile;
use Modules\Imports\Services\ImporterFactory;

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
            if (!$input['append']) {

                $user->currentCollection->uploadImportFile($input['upload']);
                try {
                    $importer = (new ImporterFactory)
                        ->getImporter($user->currentCollection->importFileExtension());
                } catch (\Exception $exception) {
                    $validator->errors()->add(
                        'upload', $exception->getMessage()
                    );
                    return;
                }
            }
            if ($input['append'] && $user->currentCollection->items->isNotEmpty()) {
                $user->currentCollection->uploadImportFile($input['upload']);

                try {
                    $importer = (new ImporterFactory)
                        ->getImporter($user->currentCollection->importFileExtension());
                } catch (\Exception $exception) {
                    $validator->errors()->add(
                        'upload', $exception->getMessage()
                    );
                    return;
                }
            }
        })->validateWithBag('importFile');
        $user->currentCollection->uploadImportFile($input['upload']);
    }
}
