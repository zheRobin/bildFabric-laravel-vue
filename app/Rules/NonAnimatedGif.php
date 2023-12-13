<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class NonAnimatedGif implements Rule
{
    public function passes($attribute, $value): bool
    {
        // The $value is expected to be an instance of UploadedFile.
        $file = $value;

        if (!($file instanceof UploadedFile)) {
            return false;
        }

        if (! in_array(exif_imagetype($file->getPathname()), [IMAGETYPE_GIF])) {
            return true;  // Pass validation if not a GIF.
        }

        $framecount = 0;
        $handle = fopen($file->getPathname(), 'rb');
        while (!feof($handle) && $framecount < 2) {
            $chunk = fread($handle, 1024 * 100);
            $framecount += preg_match_all('#\x00\x21\xF9\x04.{4}\x00(\x2C|\x21)#s', $chunk, $matches);
        }
        fclose($handle);

        // Returns true if the file is a non-animated gif
        return $framecount < 2;
    }

    public function message(): string
    {
        return 'The :attribute must be a non-animated gif or different file type.';
    }
}
