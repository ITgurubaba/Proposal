<div class="swiper-slide text-center">
  <div class="card border-1 shadow-sm h-100 nm-hover-effect rounded-top">
    <div class="position-relative overflow-hidden">
      {{-- Thumbnail --}}
      <a href="{{ $url }}" aria-label="Open gallery: {{ $title }}">
        <img 
          src="{{ $thumb }}" 
          alt="{{ $title }}"
          class="w-100 rounded-top img-fluid"
          loading="lazy"
          width="800" height="600"
          style="height: {{ $height }}px; object-fit: cover;"
        >
      </a>

      {{-- Overlay Button --}}
      <div class="position-absolute top-0 end-0 m-2">
        <a href="{{ $url }}" 
           class="btn btn-sm btn-light rounded-circle shadow"
           title="View Gallery"
           aria-label="View Gallery {{ $title }}">
          <i class="pe-7s-look"></i>
        </a>
      </div>
    </div>

    {{-- Card Body --}}
    <div class="card-body text-center py-3">
      <h6 class="fw-semibold mb-0">
        <a href="{{ $url }}" class="text-dark text-decoration-none nm-hover-title">
          {{ $title }}
        </a>
      </h6>
      <p class="text-muted small mb-0">{{ $description }}</p>
    </div>
  </div>
</div>
