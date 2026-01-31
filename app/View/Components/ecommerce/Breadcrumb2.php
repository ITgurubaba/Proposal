<?php

namespace App\View\Components\ecommerce;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\ThemeSetting;

class Breadcrumb2 extends Component
{
    public string $bgImage;
    public string $title = "";
    public string $menuItem = "";
    public string $key = "pages_uper_banner";

    // New props
    public ?string $highlight;
    public string $highlightClass;
    public bool $showBack;
    public ?string $backUrl;

    // New features 👇
    public ?string $titleColor;
    public ?string $menuUrl;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $title = "",
        $menuItem = "",
        $bgImage = "assets/frontend/images/breadcrumb/bg/b3.png",
        $highlight = null,
        $highlightClass = "text-primary",
        $showBack = false,
        $backUrl = null,
        $titleColor = null,         // new
        $menuUrl = null             // new
    )
    {
        $this->title = $title;
        $this->menuItem = $menuItem;

        $uper_banner_img = optional(ThemeSetting::findByKey($this->key))->value;
        $this->bgImage = $uper_banner_img ?: $bgImage;

        $this->highlight = $highlight;
        $this->highlightClass = $highlightClass;
        $this->showBack = filter_var($showBack, FILTER_VALIDATE_BOOLEAN);
        $this->backUrl = $backUrl;

        $this->titleColor = $titleColor;
        $this->menuUrl = $menuUrl;
    }

    public function render(): View|Closure|string
    {
        return view('components.ecommerce.breadcrumb-2');
    }
}
