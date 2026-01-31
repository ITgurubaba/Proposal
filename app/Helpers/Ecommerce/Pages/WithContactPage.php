<?php

namespace App\Helpers\Ecommerce\Pages;
use App\Helpers\Admin\BackendHelper;
use App\Models\ThemeSetting;
trait WithContactPage
{
    
    public static function getDefaultContactPageData(): array
    {
        return [
                'contact_page_map'=>null,
                'contact_page_forward_form'=>0,
                'contact_page_left_side_background' => null,
                'contact_page_forward_emails' => [],
        ];
    }

    public static function getContactPageKeys(): array
    {
        return array_keys(self::getDefaultContactPageData());
    }

    public static function getContactPageParseData(): array
    {
        $map = optional(ThemeSetting::where('key','contact_page_map')->first())->value ?? '';
        $bgImg = optional(ThemeSetting::where('key','contact_page_left_side_background')->first())->value ?? null;
        $forwardRaw = optional(ThemeSetting::where('key','contact_page_forward_emails')->first())->value ?? '[]';
        $forward = BackendHelper::JsonDecode($forwardRaw);

        $forwardFormRaw = optional(ThemeSetting::where('key','contact_page_forward_form')->first())->value ?? false;
        $forwardForm = (bool) $forwardFormRaw;

        return [
            'contact_page_map' => (string) $map,
            'contact_page_forward_emails' => $forward,   // ✅ array for <x-mary-tags>
            'contact_page_forward_form' => $forwardForm, // ✅ boolean for toggle
            'contact_page_left_side_background' => $bgImg,
        ];
    }

}
