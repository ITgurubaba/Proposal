<?php

namespace App\Helpers\Traits;

trait ToastNotification
{
    public function successToast(
        $message = "",
    ): void
    {
        $this->dispatch('SetMessage',
            type:"success",
            title:config('settings.site_name'),
            message:$message,
        );
    }

    public function infoToast(
        $message = "",
    ): void
    {
        $this->dispatch('SetMessage',
            type:"info",
            title:config('settings.site_name'),
            message:$message,
        );
    }
    public function errorToast(
        $message = "",
    ): void
    {
        $this->dispatch('SetMessage',
            type:"error",
            title:config('settings.site_name'),
            message:$message,
        );
    }

    public function toast(
        $title = "",
        $message = "",
        $type = "error",
    ): void
    {
        $this->dispatch('SetMessage',
            type:$type,
            title:$title,
            message:$message,
        );
    }

}
