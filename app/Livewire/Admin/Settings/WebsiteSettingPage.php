<?php

namespace App\Livewire\Admin\Settings;

use App\Helpers\Admin\SettingHelper;
use App\Models\Setting;
use Illuminate\Support\Arr;
use Livewire\Component;
use Mary\Traits\Toast;

class WebsiteSettingPage extends Component
{
    use Toast;

    public array $request = [];

    public function mount()
    {
        $this->EditRequest();
    }

    public function render()
    {
        return view('livewire.admin.settings.website-setting-page');
    }

    public function save():void
    {
        SettingHelper::createOrUpdateSetting($this->request);

        update_env([
            "APP_ENV"=>$this->request['app_env'],
            "APP_URL"=>$this->request['app_url'],
            "APP_DEBUG"=>(string)($this->request['app_env'] === "local"),
            "DEBUGBAR_ENABLED"=> (string)($this->request['app_debug']==1),
            "APP_TIMEZONE"=>$this->request['app_timezone']
        ]);

        $this->success(
            title:'Website Settings',
            description:'Updated Successfully'
        );
    }

    protected function EditRequest():void
    {
        $this->request = Setting::getByKeys([
            'app_env',
            'app_url',
            'app_debug',
            'app_timezone',
            'https_redirect',
        ]);

        $requiredFields = [
            'app_env'=>'local',
            'app_url'=>'http://localhost',
            'app_debug'=>0,
            'app_timezone'=>'Asia/Kolkata',
            'https_redirect'=>0,
        ];

        foreach ($requiredFields as $key=>$value)
        {
            if(!Arr::has($this->request,$key))
            {
                $this->request[$key] = $value;
            }
        }
    }
}
