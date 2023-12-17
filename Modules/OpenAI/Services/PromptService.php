<?php

namespace Modules\OpenAI\Services;

use Modules\Collections\Models\Collection;
use Modules\Imports\Enums\HeaderTypeEnum;
use Modules\Imports\Models\CollectionItem;
use finfo;

class PromptService
{
    /**
     * Retrieve valid headers for prompt generation.
     *
     * @param Collection $collection
     * @return array
     */
    private function getCode($imagePath)
    {
        $image = file_get_contents($imagePath);
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $type = $finfo->buffer($image);
        return 'data:' . $type . ';base64,' . base64_encode($image);
    }

    /**
     * Retrieve valid data for prompt generation.
     *
     * @param CollectionItem $collectionItem
     * @return array
     */
    public function getData(CollectionItem $collectionItem)
    {
        $base64Data = $this->getCode($collectionItem->source);
        return $base64Data;
    }
}
