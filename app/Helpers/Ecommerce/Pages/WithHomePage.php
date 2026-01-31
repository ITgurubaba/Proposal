<?php

namespace App\Helpers\Ecommerce\Pages;

use App\Helpers\Admin\BackendHelper;
use App\Models\ThemeSetting;

trait WithHomePage
{
    public static array $parseHomePageKeys = [
        'ecommerce_banners',
        'ecommerce_featured_collection',
        'ecommerce_custom_featured_collection',
        'ecommerce_ads_collection',


    ];


    public static function getDefaultHomePageData(): array
    {
        return [
            'ecommerce_flash_sale_description' => 'HELLO EVERYONE! 25% Off All Products',
            'ecommerce_flash_sale_status' => 1,

            'ecommerce_banners' => self::getDefaultHomePageSection('banners'),
            'ecommerce_banners_status' => 1,

            'ecommerce_featured_collection_heading' => 'OUR PRODUCTS',
            'ecommerce_featured_collection' => json_encode([]),
            'ecommerce_featured_collection_status' => 1,

            'ecommerce_custom_banners_status' => 1,

            'ecommerce_custom_featured_collection_heading' => 'Our Best Articles',
            'ecommerce_custom_featured_collection_subheading' => 'Explore our best articles on furniture trends, tips, and inspiration.',
            'ecommerce_custom_featured_collection' => json_encode([]),
            'ecommerce_custom_featured_collection_2' => null,

            'ecommerce_custom_featured_collection_status' => 1,

            'ecommerce_ads_collection' => json_encode([]),
            'ecommerce_ads_collection_status' => 1,


            'multi_gallery_status' => 1,
            'multi_gallery_heading' => "Our Gallery",
            'multi_gallery_sub_heading' => "Explore Our Beautiful Gallery",

            'single_gallery_status' => 1,
            'single_gallery_heading' => "Our single Gallery",
            'single_gallery_sub_heading' => "Explore Our Beautiful Gallery",



            'ecommerce_footer_background' => 'assets/default/footer_banner.webp',
            'ecommerce_footer_background_mobile' => 'assets/default/footer_banner.webp',



            'pages_uper_banner' => 'assets/default/footer_banner.webp',

        ];
    }

    public static function getHomePageKeys(): array
    {
        return array_keys(self::getDefaultHomePageData());
    }

    public static function getHomePageParseData(): array
    {
        $requiredFields = self::getDefaultHomePageData();

        $data = ThemeSetting::findByKeys(self::getHomePageKeys());

        foreach ($requiredFields as $key => $value) {
            if (!\Arr::has($data, $key)) {
                $data[$key] = $value;
            }
        }

        foreach (self::$parseHomePageKeys as $item) {
            $data[$item] = BackendHelper::JsonDecode($data[$item] ?? null);
        }

        return $data;
    }

    public static function getDefaultHomePageSection($section = "banners"): mixed
    {
        $data = match ($section) {
            'banners' => [
                [
                    'text_color' => '#000000',
                    'top_heading' => '65 % OFF',
                    'heading' => "New Plant",
                    'sub_heading' => 'Pronia, With 100% Natural, Organic & Plant Shop.',
                    'btn_label' => 'Discover Now',
                    'btn_text_color' => '#000000',
                    'btn_link' => '/',
                    'btn_bg_color' => '#000000',
                    'bg_image' => 'assets/default/banners/bg-banner-default.webp',
                    'main_image' => 'assets/default/banners/product-1.webp',
                    'position' => 0,
                    'status' => 1,
                ],
                [
                    'text_color' => '#000000',
                    'top_heading' => '65 % OFF',
                    'heading' => "New Plant",
                    'sub_heading' => 'Pronia, With 100% Natural, Organic & Plant Shop.',
                    'btn_label' => 'Discover Now',
                    'btn_text_color' => '#000000',
                    'btn_link' => '/',
                    'btn_bg_color' => '#000000',
                    'bg_image' => 'assets/default/banners/bg-banner-default.webp',
                    'main_image' => 'assets/default/banners/product-2.webp',
                    'position' => 0,
                    'status' => 1,
                ]
            ]
        };

        return BackendHelper::JsonEncode($data);
    }
}
