<?php

namespace App\Helpers\Ecommerce\Pages;
use App\Helpers\Admin\BackendHelper;
use App\Models\ThemeSetting;
trait WithAboutPage
{
    
    public static function getDefaultAboutPageData(): array
    {
        return [
            'about_title' => 'Our Story',
            'about_description' => 'We started with a vision to bring the best natural products to your home. Our journey began in 2010...',
            'about_video_url' => 'https://www.youtube.com/watch?v=yourvideoid',
            'about_video_thumbnail' => 'assets/default/footer_banner.webp',
        ];
    }

    public static function getAboutPageKeys(): array
    {
        return array_keys(self::getDefaultAboutPageData());
    }

    public static function getAboutPageParseData(): array
    {
        $requiredFields = self::getDefaultAboutPageData();
        $data = ThemeSetting::findByKeys(self::getAboutPageKeys());

        foreach ($requiredFields as $key => $value) {
            if (!\Arr::has($data, $key)) {
                $data[$key] = $value;
            }
        }

        return $data;
    }


}
