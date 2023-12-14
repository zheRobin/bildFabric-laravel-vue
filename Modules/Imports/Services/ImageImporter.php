<?php

namespace Modules\Imports\Services;

use App\Models\User;
use Modules\Collections\Models\Collection;
use Modules\Imports\Contracts\Importer;
use Illuminate\Support\Facades\Storage;

class ImageImporter implements Importer
{
    public function import(User $user, Collection $collection): void
    {
        // Get the original name of the image file
        $originalName = $collection->importFilePath();
        $hashName = $collection->hashName($collection->importFileExtension());
        // Path where the image will be stored
        $filePath = 'images/' . $hashName;
        error_log($filePath);
        // Store image to S3
        Storage::disk('s3')->put($filePath, file_get_contents($collection->importFilePath()));
        Storage::disk('s3')->setVisibility($filePath, 'public');

        // Get the URL of the uploaded image
        $url = Storage::disk('s3')->url($filePath);

        // Store the URL and the original filename to CollectionItem Model
        $user->currentCollection->items()->create([
            'uploaded_file_path' => $url,
            'original_file_name' => $originalName
        ]);
    }
}
