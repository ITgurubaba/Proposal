<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ComponentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Blade::component('admin.theme.sidebar', \App\View\Components\admin\theme\Sidebar::class);
        Blade::component('admin.theme.navbar', \App\View\Components\admin\theme\navbar::class);
        Blade::component('admin.theme.footer', \App\View\Components\admin\theme\footer::class);

        Blade::component('admin.forms.image-viewer', \App\View\Components\admin\forms\ImageViewer::class);
        Blade::component('admin.forms.phone-input', \App\View\Components\admin\forms\PhoneInput::class);

        Blade::component('forms.input-label', \App\View\Components\forms\inputLabel::class);
        Blade::component('forms.modal', \App\View\Components\forms\Modal::class);

        Blade::component('ecommerce.product-item', \App\View\Components\ecommerce\ProductItem::class);
        Blade::component('ecommerce.product-list-item', \App\View\Components\ecommerce\ProductListItem::class);
        Blade::component('ecommerce.swiper-product-item', \App\View\Components\ecommerce\SwiperProductItem::class);
        Blade::component('ecommerce.breadcrumb', \App\View\Components\ecommerce\Breadcrumb::class);
        Blade::component('ecommerce.breadcrumb', \App\View\Components\ecommerce\Breadcrumb::class);
        
        Blade::component('ecommerce.swiper-gallery-item', \App\View\Components\ecommerce\SwiperGalleryItem::class);
        Blade::component('ecommerce.swiper-multi-gallery-item', \App\View\Components\ecommerce\SwiperMultiGalleryItem::class);

        Blade::component('ecommerce.breadcrumb2', \App\View\Components\ecommerce\Breadcrumb2::class);
        Blade::component('ecommerce.product-variant-choice', \App\View\Components\ecommerce\ProductVariantChoice::class);
    }
}
