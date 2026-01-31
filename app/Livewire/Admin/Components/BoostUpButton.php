<?php

namespace App\Livewire\Admin\Components;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Mary\Traits\Toast;

class BoostUpButton extends Component
{
    use Toast;

    public $currentUrl;

    public function mount(Request $request): void
    {
        $this->currentUrl = $request->url();
    }

    public function render()
    {
        return view('livewire.admin.components.boost-up-button');
    }

    public function BoostUp(): void
    {
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        $this->success(
            config('settings.site_name',' '),
            'System is boosted up',
            redirectTo:$this->currentUrl
        );
    }
}
