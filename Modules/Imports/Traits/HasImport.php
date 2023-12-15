<?php

namespace Modules\Imports\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Imports\Models\CollectionItem;

trait HasImport
{
    /**
     * @param UploadedFile $uploadedFile
     * @return void
     */
    public function uploadImportFile(UploadedFile $uploadedFile): void
    {
        $originalName = $uploadedFile->getClientOriginalName();
        $hashName = $this->hashName($uploadedFile->getClientOriginalExtension());
        $filePath = 'images/' . $hashName;
        $bytes = $uploadedFile->getSize();
        $kilobytes = round($bytes / 1024);
        $megabytes = round($bytes / (1024 * 1024),2);
        if ($bytes < 1024) {
            $size = $bytes . " Bytes";
        } elseif ($bytes < 1048576) {
            $size = $kilobytes . " KB";
        } else {
            $size = $megabytes . " MB";
        }
        Storage::disk('s3')->put($filePath, file_get_contents($uploadedFile));
        Storage::disk('s3')->setVisibility($filePath, 'public');
        $url = Storage::disk('s3')->url($filePath);
        $this->items()->create([
            'source' => $url,
            'title' => $originalName,
            'size' => $size
        ]);
    }

    /**
     * @return false|string
     */
    public function importFilePath(): string|false
    {
        return $this->last_uploaded_file_path
            ? Storage::disk($this->importFileDisk())->path($this->last_uploaded_file_path)
            : false;
    }

    /**
     * @return string
     */
    public function importFileExtension(): string
    {
        return File::extension($this->last_uploaded_file_path);
    }

    /**
     * @return string
     */
    public function importFileDisk(): string
    {
        return 'public';
    }

    /**
     * @param string $extension
     * @return string
     */
    public function hashName(string $extension): string
    {
        $hash = Str::random(40);

        return implode('.', [$hash, $extension]);
    }

    /**
     * @return string
     */
    public function importFileDirectory(): string
    {
        return "team-{$this->team_id}/collection-{$this->getKey()}";
    }

    /**
     * @return string|null
     */
    public function importFileContent(): null|string
    {
        return Storage::disk($this->importFileDisk())->get($this->last_uploaded_file_path);
    }

    /**
     * @return bool
     */
    public function removeImportedFile(): bool
    {
        return Storage::disk($this->importFileDisk())->delete($this->last_uploaded_file_path);
    }

    public function newRow(): array
    {
        $cells = [];

        foreach ($this->headers as $header) {
            $cells[] = [
                'header' => $header['name'],
                'value' => null,
            ];
        }

        return $cells;
    }

    /**
     * @return void
     */
    public function purgeItems(): void
    {
        $this->items()->delete();

        $this->update(['headers' => null]);
    }

    /**
     * @param string $coordinates
     * @return CollectionItem|null
     */
    public function collectionItemByCoordinates(string $coordinates): CollectionItem|null
    {
        return $this->items()
            ->whereJsonContains('data', ['external_identifier' => $coordinates])
            ->first();
    }
}
