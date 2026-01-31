<?php

namespace App\Helpers\Ecommerce\Pages;

use App\Helpers\Admin\BackendHelper;
use App\Models\ThemeSetting;

trait WithWhyChooseUsPage
{
    public static function getDefaultWhyChooseUsPageData(): array
    {
        return [
            'why_choose_us_section_title' => 'Our Story',
            'why_choose_us_section_subheading' => 'Trusted By ',
            'why_choose_us_section_1' => json_decode(self::getDefaultWhyChooseUsPageSection1(), true),
            'why_choose_us_section_2' => json_decode(self::getDefaultWhyChooseUsPageSection2(), true),

            'why_choose_us_section_1__image' => 'assets/default/footer_banner.webp',
            'why_choose_us_section_2__image' => 'assets/default/footer_banner.webp',
        ];
    }

    public static function getWhyChooseUsPageKeys(): array
    {
        return array_keys(self::getDefaultWhyChooseUsPageData());
    }
    public static function getDefaultWhyChooseUsPageSection1($section = "section_1"): mixed
    {
        $data = match ($section) {
            'section_1' => [
                [
                    'title' => "Title 1",
                    'short_description' => 'This is description 1.',
                    'position' => 0,
                    'status' => 1,
                ],
                [
                    'title' => "Title 2",
                    'short_description' => 'This is description 2.',
                    'position' => 0,
                    'status' => 1,
                ],
                [
                    'title' => "Title 3",
                    'short_description' => 'This is description 3.',
                    'position' => 0,
                    'status' => 1,
                ],
            ]
        };

        return BackendHelper::JsonEncode($data);
    }

    public static function getDefaultWhyChooseUsPageSection2($section = "section_2"): mixed
    {
        $data = match ($section) {
            'section_2' => [
                [
                    'title' => "Title 1",
                    'short_description' => 'This is description 1.',
                    'position' => 0,
                    'status' => 1,
                ],
                [
                    'title' => "Title 2",
                    'short_description' => 'This is description 2.',
                    'position' => 0,
                    'status' => 1,
                ],
                [
                    'title' => "Title 3",
                    'short_description' => 'This is description 3.',
                    'position' => 0,
                    'status' => 1,
                ],
            ]
        };

        return BackendHelper::JsonEncode($data);
    }

    public static function getWhyChooseUsPageParseData(): array
    {
        $requiredFields = self::getDefaultWhyChooseUsPageData();
        $data = ThemeSetting::findByKeys(self::getWhyChooseUsPageKeys());

        foreach ($requiredFields as $key => $value) {
            if (!\Arr::has($data, $key)) {
                $data[$key] = $value;
            }
        }

        return $data;
    }
}
