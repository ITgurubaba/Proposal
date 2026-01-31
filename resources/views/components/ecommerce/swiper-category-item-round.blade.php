<div class="swiper-slide category-item text-center">
  <a href="{{ $category->slug ? url('category/' . $category->slug) : '#' }}" class="inline-block group">
    <div
      class="relative mx-auto mb-2 h-24 w-24 rounded-full overflow-hidden 
              bg-white shadow-md group-hover:shadow-lg 
             transition-all duration-300 ease-in-out flex items-center justify-center">
      <img
        src="{{ $category->image ?? asset('images/placeholder.png') }}"
        alt="{{ $category->name }}"
        class="h-full w-full object-cover rounded-full transform group-hover:scale-105 transition-transform duration-300"
        style="border-radius: 50%;">
    </div>
    <h4 class="text-sm font-medium text-gray-800 mt-2 group-hover:text-primary transition-colors duration-300">
      {{ $category->name }}
    </h4>
  </a>
</div>
