<?php

namespace App\View\Components\ecommerce;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\GalleryMedia;
use App\Models\Gallery;

class SwiperGalleryItem extends Component
{
    public GalleryMedia $media;
    public string $galleryTitle;
    public string|null $galleryUrl;

    /**
     * Create a new component instance.
     */
    public function __construct(GalleryMedia $media, string $galleryTitle = '', string|null $galleryUrl = null)
    {
        $this->media = $media;
        $this->galleryTitle = $galleryTitle;
        $this->galleryUrl = $galleryUrl;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ecommerce.swiper-gallery-item');
    }
}
