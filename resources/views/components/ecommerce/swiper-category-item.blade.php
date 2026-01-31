<div class="swiper-slide  category-item text-center">
    <a href="{{ $category->slug ? url('category/' . $category->slug) : '#' }}">
        <img src="{{ $category->image ?? asset('images/placeholder.png') }}"
             alt="{{ $category->name }}"
             class="mx-auto mb-2 h-24 w-24 object-cover rounded-full ">
        <h4 class="text-sm font-medium text-gray-800">{{ $category->name }}</h4>
    </a>
</div>

