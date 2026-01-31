<?php

namespace App\Livewire\Admin\Ecommerce;

use App\Helpers\Admin\BackendHelper;
use App\Helpers\Ecommerce\PageHelper;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class SettingPage extends Component
{
    use WithPagination, Toast;
    public $selectedTab = "main-tab";

    public $request = [];

    public function mount()
    {
        $this->NewRequest();
    }

    public function render()
    {
        return view('livewire.admin.ecommerce.setting-page');
    }

    private function NewRequest():void
    {
        $this->request = PageHelper::getProductPageParseData();
    }

    public function Save(): void
    {
        try
        {
            $data = $this->request;

            foreach (PageHelper::$parseProductPageKeys as $item)
            {
                $data[$item] = BackendHelper::JsonEncode($data[$item]);
            }

            PageHelper::createOrUpdateSettings($data);

            $this->success('Setting','Saved successfully');

        }
        catch (\Exception $exception)
        {
            $this->error('Error!',$exception->getMessage());
        }
    }
}
