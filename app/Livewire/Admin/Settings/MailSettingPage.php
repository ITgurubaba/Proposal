<?php

namespace App\Livewire\Admin\Settings;

use App\Helpers\Admin\SettingHelper;
use App\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Component;
use Mary\Traits\Toast;

class MailSettingPage extends Component
{
    use Toast;

    public $request = [];

    public $requiredFields = [
        'mail_host' => null,
        'mail_port' => 465,
        'mail_username' => null,
        'mail_password' => null,
        'mail_encryption' => 'SSL',
        'mail_address' => null,
    ];

    public function mount(): void
    {
        $this->EditRequest();
    }

    public function render()
    {
        return view('livewire.admin.settings.mail-setting-page');
    }

    public function save(): void
    {
        SettingHelper::createOrUpdateSetting($this->request);

        // ✅ Corrected call to SettingHelper::updateEnv()
        SettingHelper::updateEnv([
            'MAIL_HOST' => Str::replace(' ', '', $this->request['mail_host']),
            'MAIL_PORT' => Str::replace(' ', '', $this->request['mail_port']),
            'MAIL_USERNAME' => Str::replace(' ', '', $this->request['mail_username']),
            'MAIL_PASSWORD' => Str::replace(' ', '', $this->request['mail_password']),
            'MAIL_ENCRYPTION' => Str::replace(' ', '', $this->request['mail_encryption']),
            'MAIL_FROM_ADDRESS' => Str::replace(' ', '', $this->request['mail_address']),
        ]);

        $this->success(
            title: trans('Mail Settings'),
            description: trans('Updated Successfully')
        );
    }

    protected function EditRequest(): void
    {
        $this->request = Setting::getByKeys([
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
            'mail_address',
        ]);

        foreach ($this->requiredFields as $field => $value) {
            if (!Arr::has($this->request, $field)) {
                $this->request[$field] = $value;
            }
        }
    }
}
