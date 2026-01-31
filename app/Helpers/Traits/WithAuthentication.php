<?php

namespace App\Helpers\Traits;

use App\Models\User;

trait WithAuthentication
{
    public bool $isAuthenticated = false;

    public ?User $user;

    public function __construct()
    {

        $this->isAuthenticated = auth()->check();

        if ($this->isAuthenticated)
        {
            $this->user = User::find(auth()->id());
        }
    }

}
