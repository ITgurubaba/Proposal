<?php

namespace App\Helpers\Ecommerce\Pages;

use App\Helpers\Admin\BackendHelper;
use App\Models\ThemeSetting;

trait WithProductPage
{
    public static array $parseProductPageKeys = [

    ];


    public static function getDefaultProductPageData():array
    {
        return [
            'product_page_information'=>null,
        ];
    }

    public static function getProductPageKeys():array
    {
        return array_keys(self::getDefaultProductPageData());
    }

    public static function getProductPageParseData():array
    {
        $requiredFields = self::getDefaultProductPageData();

        $data = ThemeSetting::findByKeys(self::getProductPageKeys());

        foreach ($requiredFields as $key => $value)
        {
            if (!\Arr::has($data,$key))
            {
                $data[$key] = $value;
            }
        }

        foreach (self::$parseProductPageKeys as $item)
        {
            $data[$item] = BackendHelper::JsonDecode($data[$item] ??null);
        }

        return $data;
    }

    public static function getDefaultProductPageSection($section = "banners"):mixed
    {
        $data = match ($section){
            default=>[],
        };

        return BackendHelper::JsonEncode($data);
    }
}
