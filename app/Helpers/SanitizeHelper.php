<?php

namespace App\Helpers;

use HTMLPurifier;
use HTMLPurifier_Config;

class SanitizeHelper
{
    // Simpan instance purifier agar tidak dibuat berulang
    protected static $purifier = null;

    protected static function getPurifier()
    {
        if (self::$purifier === null) {
            $config = HTMLPurifier_Config::createDefault();

            // Tentukan tag dan atribut yang diizinkan
            $config->set(
                'HTML.Allowed',
                'p,b,i,u,strong,em,a[href],ul,ol,li,br,span,img[src|alt],h1,h2,h3,h4,blockquote,pre,code'
            );

            // Matikan resource eksternal agar lebih aman
            $config->set('URI.DisableExternalResources', true);
            $config->set('URI.DisableResources', true);

            self::$purifier = new HTMLPurifier($config);
        }
        return self::$purifier;
    }

    public static function sanitize($html)
    {
        if (empty($html)) return '';
        return self::getPurifier()->purify($html);
    }
}
