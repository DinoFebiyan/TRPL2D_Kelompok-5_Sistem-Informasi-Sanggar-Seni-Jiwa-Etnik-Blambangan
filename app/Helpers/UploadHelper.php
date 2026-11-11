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


    public static function tambahWatermark(string $pathAbsolut, string $teks = 'Jiwa Etnik Blambangan'): bool
    {
        $info = @getimagesize($pathAbsolut);
        if (! $info) {
            return false;
        }

        $gambar = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($pathAbsolut),
            IMAGETYPE_PNG => @imagecreatefrompng($pathAbsolut),
            default => false,
        };
        if (! $gambar) {
            return false;
        }

        $lebar = imagesx($gambar);
        $tinggi = imagesy($gambar);
        $font = 5;
        $lebarTeks = imagefontwidth($font) * strlen($teks);
        $tinggiTeks = imagefontheight($font);
        $x = max(10, $lebar - $lebarTeks - 20);
        $y = max(10, $tinggi - $tinggiTeks - 20);

        $latar = imagecolorallocatealpha($gambar, 0, 0, 0, 80);
        imagefilledrectangle($gambar, $x - 8, $y - 5, $x + $lebarTeks + 8, $y + $tinggiTeks + 5, $latar);
        imagestring($gambar, $font, $x, $y, $teks, imagecolorallocate($gambar, 255, 255, 255));

        $berhasil = $info[2] === IMAGETYPE_PNG
            ? imagepng($gambar, $pathAbsolut)
            : imagejpeg($gambar, $pathAbsolut, 90);

        imagedestroy($gambar);

        return $berhasil;
    }
}