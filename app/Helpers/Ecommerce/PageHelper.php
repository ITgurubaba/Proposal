<?php

namespace App\Helpers\Ecommerce;

use App\Helpers\Ecommerce\Pages\WithAboutPage;
use App\Helpers\Ecommerce\Pages\WithContactPage;
use App\Helpers\Ecommerce\Pages\WithWhyChooseUsPage;
use App\Helpers\Ecommerce\Pages\WithRequestQuotePage;
use App\Helpers\Ecommerce\Pages\WithHomePage;
use App\Helpers\Ecommerce\Pages\WithProductPage;
use App\Models\ThemeSetting;

class PageHelper
{
    use WithHomePage,
        WithAboutPage,
        WithContactPage,
        WithWhyChooseUsPage,
        WithRequestQuotePage,
        WithProductPage;
      

    public static function getThemeData($page = "home"): array
    {
        return match ($page) {
            'product'=>self::getProductPageParseData(),
            'about'   => self::getAboutPageParseData(), 
            'chooseus'   => self::getWhyChooseUsPageParseData(), 
            'requestQuote'   => self::getRequestQuotePageParseData(), 
            default => self::getHomePageParseData(),
        };
    }

    public static function createOrUpdateSettings($data = []): void
    {
        foreach ($data as $key=>$value)
        {
            $check = ThemeSetting::findByKey($key);
            if (!$check)
            {
                $check = new ThemeSetting();
                $check->key = $key;
            }

            $check->value = $value;
            $check->save();
        }
    }

}
