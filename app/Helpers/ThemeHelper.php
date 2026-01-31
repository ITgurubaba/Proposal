<?php

namespace App\Helpers;

use App\Models\BlogPost;
use App\Models\Service;
use App\Models\SlugManager;
use App\Models\ThemePage;
use App\Models\VatTax;

class ThemeHelper
{
    const LINK_NONE = "javascript:void(0)";

    public static function getTaxLabel()
    {
        $tax = VatTax::where('status',1)->first();
        return $tax?($tax->name." (".$tax->discountLabel(true).")"):"";
    }

    public static function getPageUrl($page = "privacy-policy")
    {
        return match ($page) {
            "privacy-policy" => url('privacy-policy'),
            default => url($page),
        };
    }

    public static function getOtherSlugs():array
    {
        return SlugManager::where([
            'model'=>ThemePage::class,
        ])->whereHas('themePage')->pluck('slug')->toArray();
    }

    public static function getNavbarServices()
    {
        return Service::where('status',1)
            ->orderBy('name','asc')
            ->get();
    }

    public static function getFooterServices()
    {
        return Service::where('status',1)
            ->orderBy('name','asc')
            ->limit(5)
            ->get();
    }

    public static function getFooterRecentPosts()
    {
        return BlogPost::where('status',1)
            ->orderBy('post_date','desc')
            ->limit(2)
            ->get();
    }

    public static function getServices($expect = null)
    {
        return Service::where('status',1)
            ->where('id','!=',$expect)
            ->orderBy('name','asc')
            ->get();
    }

    public static function getRecentPostsByLimit($limit = 6)
    {
        return BlogPost::where('status',1)
            ->orderBy('post_date','desc')
            ->limit($limit)
            ->get();
    }



}
