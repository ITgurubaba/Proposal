<?php

namespace App\Livewire\Admin\Settings;

use App\Helpers\Admin\BackendHelper;
use App\Helpers\Admin\SettingHelper;
use App\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Component;
use Mary\Traits\Toast;

class GeneralSettingPage extends Component
{
    use Toast;

    public array $request = [];
    public $selectedTab = "main-tab";

    public function mount():void
    {
        $this->EditRequest();
    }

    public function render()
    {
        return view('livewire.admin.settings.general-setting-page');
    }

    public function save():void
    {
        SettingHelper::createOrUpdateSetting($this->request);

        if (isset($this->request['site_name']))
        {
            update_env([
                "APP_NAME"=>Str::replace(" ","",$this->request['site_name'])
            ]);
        }

        $this->success(
            title:'General Settings',
            description:'Updated Successfully'
        );
    }

    protected function EditRequest():void
    {
        $this->request = Setting::getByKeys([
            'site_name',
            'site_description',
            'site_title',
            'meta_title',
            'meta_description',
            'meta_tags',

            'site_logo',
            'site_logo_2',
            'site_mobile_logo',
            'site_favicon',
            'admin_logo',
            'footer_logo',
            'meta_os_image',

            'company_name',
            'company_email',
            'company_phone',
            
            'purchase_by_whatsapp_status',
            'purchase_from_website_status',
            'company_wtsp_no',
            'product_query_wtsp_number',
            'product_query_msg',
            'company_about',
            'company_address',

            'fb_link',
            'twitter_link',
            'instagram_link',
            'pinterest_link',
            'linked_in_link',
            'youtube_link',
            'google_plus_link',

            'footer_description',
            'dark_mode',

            'captcha',
            'editor_layout',
            'app_base_url',
            'other_mail_group',

            'maintenance_mode',
        ]);

        $requiredFields = [
            'meta_tags'=>null,
            'site_logo'=>null,
            'site_logo_2'=>null,
            'site_mobile_logo'=>null,
            'site_favicon'=>null,
            'admin_logo'=>null,
            'footer_logo'=>null,
            'meta_os_image'=>null,
            'editor_layout'=>'ck-editor-4',
            'app_base_url'=>null,
            'other_mail_group'=>null,
            'captcha'=>1,
            'maintenance_mode'=>0,
            'purchase_by_whatsapp_status'=>1,
            'purchase_from_website_status'=>1,
        ];

        foreach ($requiredFields as $field=>$value)
        {
            if(!Arr::has($this->request,$field))
            {
                $this->request[$field] = $value;
            }
        }

        $this->request['meta_tags'] = BackendHelper::JsonDecode($this->request['meta_tags']);

    }

}
