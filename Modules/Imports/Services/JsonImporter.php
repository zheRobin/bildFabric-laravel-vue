<?php

namespace Modules\Imports\Services;

use Modules\Collections\Models\Collection;
use Modules\Imports\Contracts\Importer;
use Modules\Imports\Models\CollectionItem;

class JsonImporter implements Importer
{
    /**
     * @var int
     */
    public const JSON_MAX_DEPTH = 3;

    public function import(Collection $collection): void
    {
        $data = $this->validateJsonStructure($collection->importFileContent());

        if (empty($data)) {
            return;
        }

        $headers = array_keys(current($data));

        $headerItems = [];
        foreach ($headers as $header) {
            $headerItems[] = [
                'name' => $header,
                'type' => 'text',
            ];
        }

        $collection->forceFill(['headers' => $headerItems])->save();

        foreach ($data as $row) {
            if (!is_array($row)) {
                continue;
            }

            $collectionItem = new CollectionItem;
            $collectionItemCells = [];

            foreach ($row as $key => $value) {
                if (in_array($key, $headers)) {
                    $collectionItemCells[] = [
                        'header' => $key,
                        'value' => $value
                    ];
                }
            }

            $collectionItem->data = $collectionItemCells;
            $collection->items()->save($collectionItem);
        }
    }

    public function getHeaders(Collection $collection): array
    {
        $data = $this->validateJsonStructure($collection->importFileContent());

        if (empty($data)) {
            return [];
        }

        return array_keys(current($data));
    }

    protected function validateJsonStructure($data): array|false
    {
        $array = json_decode(
            $data,
            true,
            self::JSON_MAX_DEPTH,
        );

        // skip if empty or max depth received
        if (empty($array)) {
            return false;
        }

        // skip if there is no headers
        if (!is_array(current($array))) {
            return false;
        }

        // check if there is valid headers
        $headers = array_filter(array_keys(current($array)), function ($item) {
            return $item && is_string($item);
        });

        if (empty($headers)) {
            return false;
        }

        return $array;
    }
}