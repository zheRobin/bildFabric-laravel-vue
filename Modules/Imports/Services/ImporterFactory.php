<?php

namespace Modules\Imports\Services;

use InvalidArgumentException;
use Modules\Imports\Contracts\Importer;

class ImporterFactory
{
    public function getImporter(string $fileExtension): Importer
    {
        return match ($fileExtension) {
            'png', 'jpeg', 'jpg', 'webp', 'gif' => new JsonImporter,
            default => throw new InvalidArgumentException("Wrong importer file extension [{$fileExtension}]"),
        };
    }
}
