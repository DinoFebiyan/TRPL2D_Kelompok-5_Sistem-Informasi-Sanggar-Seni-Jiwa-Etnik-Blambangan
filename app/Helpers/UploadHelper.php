<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class UploadHelper
{
    public static function isImageMimeValid(UploadedFile $file, $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'])
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file->getRealPath());
        finfo_close($finfo);
        return in_array($mime, $allowedMimes);
    }
}