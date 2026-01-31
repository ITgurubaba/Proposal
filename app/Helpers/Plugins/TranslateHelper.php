<?php

namespace App\Helpers\Plugins;

class TranslateHelper
{
    public static array $languages = [
        'en' => 'English',
        'hi' => 'Hindi',
    ];

    public static function getLanguage($language = "en")
    {
        return self::$languages[$language] ?? self::$languages['en'];
    }
}
