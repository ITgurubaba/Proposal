<?php

namespace App\Helpers\Admin;

use App\Models\Setting;

class SettingHelper
{
    public static function createOrUpdateSetting($data = []):void
    {
        foreach($data as $key=>$value)
        {
            $setting = Setting::getByKey($key);
            if(!$setting)
            {
                $setting = new Setting();
                $setting->key = $key;
            }
            $setting->value = $value;
            $setting->save();
        }

    }
}
