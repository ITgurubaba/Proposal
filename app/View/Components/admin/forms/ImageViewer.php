<?php

namespace App\View\Components\admin\forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ImageViewer extends Component
{
    /**
     * Create a new component instance.
     */

    public string $defaultImage;

    public function __construct($defaultImage = "assets/default/no-upload.png")
    {
        $this->defaultImage = $defaultImage;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.forms.image-viewer');
    }
}
