<div x-data="{
       quickViewLoading:false,
       wishlistLoading:false,
       cartLoading:false,
       addToCart(){ this.cartLoading = true; setTimeout(()=>{ $dispatch('{{ Ecommerce::PRODUCT_Add_To_Cart }}',['{{ $product->id }}',1]); this.cartLoading = false; },200) },
       quickView(){ this.quickViewLoading = true; setTimeout(()=>{ $dispatch('{{ Ecommerce::PRODUCT_Quick_View }}',[{{ $product->id }}]); this.quickViewLoading = false; },200) },
       addToWishlist(){ this.wishlistLoading = true; setTimeout(()=>{ $dispatch('{{ Ecommerce::PRODUCT_Add_To_Wishlist }}',[{{ $product->id }}]); this.wishlistLoading = false; },200) }
     }"
     class="product-item"
     wire:ignore
>
    <div class="product-img">
        <a href="{{ $product->slugUrl() }}">
            <img class="primary-img" src="{{ $product->thumbnail() }}" alt="Product Images">
            <img class="secondary-img" src="{{ $product->thumbnailHover() }}" alt="Product Images">
        </a>

        <div class="product-add-action">
            <ul>
                {{-- Wishlist (only if website purchase ON) --}}
                @if (config('settings.purchase_from_website_status') == 1)
                <li>
                    <a href="{{ Theme::LINK_NONE }}"
                       data-tippy="Add to wishlist"
                       data-tippy-inertia="true"
                       data-tippy-animation="shift-away"
                       data-tippy-delay="50"
                       data-tippy-arrow="true"
                       data-tippy-theme="sharpborder"
                       @click.prevent="addToWishlist">
                        <i class="fa fa-spinner" x-show="wishlistLoading"></i>
                        <i class="pe-7s-like" x-show="!wishlistLoading"></i>
                    </a>
                </li>
                @endif

                {{-- Quickview (always) --}}
                <li class="quuickview-btn" @click.prevent="quickView">
                    <a href="{{ Theme::LINK_NONE }}"
                       data-tippy="Quickview"
                       data-tippy-inertia="true"
                       data-tippy-animation="shift-away"
                       data-tippy-delay="50"
                       data-tippy-arrow="true"
                       data-tippy-theme="sharpborder">
                        <i class="fa fa-spinner" x-show="quickViewLoading"></i>
                        <i class="pe-7s-look" x-show="!quickViewLoading"></i>
                    </a>
                </li>

                {{-- Add to cart (only if website purchase ON) --}}
                @if (config('settings.purchase_from_website_status') == 1)
                <li>
                    <a href="{{ Theme::LINK_NONE }}"
                       data-tippy="Add to cart"
                       data-tippy-inertia="true"
                       data-tippy-animation="shift-away"
                       data-tippy-delay="50"
                       data-tippy-arrow="true"
                       data-tippy-theme="sharpborder"
                       @click.prevent="addToCart">
                        <i class="fa fa-spinner" x-show="cartLoading"></i>
                        <i class="pe-7s-cart" x-show="!cartLoading"></i>
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="product-content">
        <a class="product-name" href="{{ $product->slugUrl() }}">
            {{ trans($product->name ?? '') }}
        </a>
        <div class="price-box pb-1">
            <span class="new-price">{{ $product->getDefaultPrice() }}</span>
        </div>
    </div>

     {{-- <div class="rating-box">
            <ul>
                @for($i = 1; $i<=5; $i++)
                    @if($i < $productRating)
                        <li><i class="fa fa-star"></i></li>
                    @else
                        <li><i class="fa fa-star-o"></i></li>
                    @endif
                @endfor
            </ul>
        </div> --}}
</div>

