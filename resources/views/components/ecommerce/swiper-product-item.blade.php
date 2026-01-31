<div x-data="{
       quickViewLoading:false,
       wishlistLoading:false,
       cartLoading:false,
       addToCart:function(){
           this.cartLoading = true;
           setTimeout(()=>{
              $dispatch('{{ Ecommerce::PRODUCT_Add_To_Cart }}',['{{ $product->id }}',1]);
              this.cartLoading = false;
           },200)
       },
       quickView:function(){
           this.quickViewLoading = true;
           setTimeout(()=>{
              $dispatch('{{ Ecommerce::PRODUCT_Quick_View }}',[{{ $product->id }}])
              this.quickViewLoading = false;
           },200)
       },
       addToWishlist:function(){
           this.wishlistLoading = true;
           setTimeout(()=>{
              $dispatch('{{ Ecommerce::PRODUCT_Add_To_Wishlist }}',[{{ $product->id }}])
              this.wishlistLoading = false;
           },200)
       }
     }"
     class="swiper-slide product-item"
     wire:ignore
>
    {{-- Product Image --}}
    <div class="product-img">
        <a href="{{ $product->slugUrl() }}">
            <img class="primary-img"
                 src="{{ $product->thumbnail() }}"
                 alt="Product Image {{ $product->name ?? '' }} main">
            <img class="secondary-img"
                 src="{{ $product->thumbnailHover() }}"
                 alt="Product Image {{ $product->name ?? '' }} hover">
        </a>

        {{-- Product Actions --}}
        <div class="product-add-action">
            <ul>

                {{-- Wishlist Button (Show only if Website Purchase ON) --}}
                @if (config('settings.purchase_from_website_status') == 1)
                    <li>
                        <a href="{{ Theme::LINK_NONE }}"
                           data-tippy="Add to wishlist"
                           data-tippy-inertia="true"
                           data-tippy-animation="shift-away"
                           data-tippy-delay="50"
                           data-tippy-arrow="true"
                           data-tippy-theme="sharpborder"
                           @click.prevent="addToWishlist"
                        >
                            <i class="fa fa-spinner" x-show="wishlistLoading"></i>
                            <i class="pe-7s-like" x-show="!wishlistLoading"></i>
                        </a>
                    </li>
                @endif

                {{-- Quick View Button (Always Show) --}}
                <li class="quuickview-btn" @click.prevent="quickView">
                    <a href="{{ Theme::LINK_NONE }}"
                       data-tippy="Quickview"
                       data-tippy-inertia="true"
                       data-tippy-animation="shift-away"
                       data-tippy-delay="50"
                       data-tippy-arrow="true"
                       data-tippy-theme="sharpborder"
                    >
                        <i class="fa fa-spinner" x-show="quickViewLoading"></i>
                        <i class="pe-7s-look" x-show="!quickViewLoading"></i>
                    </a>
                </li>

                {{-- Add to Cart Button (Show only if Website Purchase ON) --}}
                @if (config('settings.purchase_from_website_status') == 1)
                    <li>
                        <a href="{{ Theme::LINK_NONE }}"
                           data-tippy="Add to cart"
                           data-tippy-inertia="true"
                           data-tippy-animation="shift-away"
                           data-tippy-delay="50"
                           data-tippy-arrow="true"
                           data-tippy-theme="sharpborder"
                           @click.prevent="addToCart"
                        >
                            <i class="fa fa-spinner" x-show="cartLoading"></i>
                            <i class="pe-7s-cart" x-show="!cartLoading"></i>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    {{-- Product Details --}}
    <div class="product-content">
        <a class="product-name" href="{{ $product->slugUrl() }}">
            {{ trans($product->name ?? '') }}
        </a>
        <div class="price-box pb-1">
            <span class="new-price">{{ $product->getDefaultPrice() }}</span>
        </div>
    </div>
</div>
