<?php

namespace App\Helpers\Admin;

use App\Models\Setting;

class SettingHelper
{
    public static function createOrUpdateSetting($data = []): void
    {
        foreach($data as $key => $value)
        {
            $setting = Setting::getByKey($key);
            if (!$setting) {
                $setting = new Setting();
                $setting->key = $key;
            }
            $setting->value = $value;
            $setting->save();
        }
    }

    public static function updateEnv(array $data): bool
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            return false;
        }

        $envContent = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            $key = strtoupper($key);
            $escaped = preg_quote($key, '/');

            if (preg_match("/^{$escaped}=.*/m", $envContent)) {
                $envContent = preg_replace(
                    "/^{$escaped}=.*/m",
                    "{$key}=\"{$value}\"",
                    $envContent
                );
            } else {
                $envContent .= "\n{$key}=\"{$value}\"";
            }
        }

        file_put_contents($envPath, $envContent);

        return true;
    }
}
