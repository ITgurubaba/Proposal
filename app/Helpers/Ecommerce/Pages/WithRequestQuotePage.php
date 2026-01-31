<?php

namespace App\Helpers\Ecommerce\Pages;

use App\Helpers\Admin\BackendHelper;
use App\Models\ThemeSetting;

trait WithRequestQuotePage
{
    public static function getDefaultRequestQuotePageData(): array
    {
        return [
            'request_quote_section_heading' => 'Our Story',
            'request_quote_section' => json_decode(self::getDefaultRequestQuotePageSection(), true),
            'request_quote_section_image' => 'assets/default/footer_banner.webp',
        ];
    }

    public static function getRequestQuotePageKeys(): array
    {
        return array_keys(self::getDefaultRequestQuotePageData());
    }
    public static function getDefaultRequestQuotePageSection($section = "section"): string
    {
        $data = match ($section) {
            'section' => [
                [
                    'title' => "Professional & Reliable",
                    'short_description' => "Our team brings over a decade of hands-on experience, delivering quality solutions with professionalism and integrity.",
                    'position' => 0,
                    'status' => 1,
                ],
                [
                    'title' => "Tailored Logistics Solutions",
                    'short_description' => "We understand that no two businesses are alike. That's why we customize our services to fit your unique needs.",
                    'position' => 0,
                    'status' => 1,
                ],
                [
                    'title' => "Customer-Centric Approach",
                    'short_description' => "We build long-term relationships by listening, adapting, and delivering beyond expectations.",
                    'position' => 0,
                    'status' => 1,
                ],
            ],
            default => []
        };
    
        return BackendHelper::JsonEncode($data);
    }
    

 

    public static function getRequestQuotePageParseData(): array
    {
        $requiredFields = self::getDefaultRequestQuotePageData();
        $data = ThemeSetting::findByKeys(self::getRequestQuotePageKeys());

        foreach ($requiredFields as $key => $value) {
            if (!\Arr::has($data, $key)) {
                $data[$key] = $value;
            }
        }

        return $data;
    }
}
