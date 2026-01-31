<?php

namespace App\View\Components\ecommerce;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Gallery;

class SwiperMultiGalleryItem extends Component
{
    public Gallery $gallery;
    public string $title;
    public string $description;
    public ?string $url;
    public ?string $thumb;
    public int $height;

    /**
     * Create a new component instance.
     */
    public function __construct(Gallery $gallery, int $height = 300)
    {
        $this->gallery = $gallery;
        $this->title   = $gallery->title ?? 'Untitled Gallery';
        $this->description   = $gallery->description ?? 'No description available';
        $this->url     = $gallery->slugUrl() ?? 'javascript:void(0)';
        $this->thumb   = $gallery->thumbnailUrl() ?? asset('images/placeholder-800x600.png');
        $this->height  = $height;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ecommerce.swiper-multi-gallery-item');
    }
}
