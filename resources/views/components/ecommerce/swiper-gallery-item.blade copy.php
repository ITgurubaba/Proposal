<div class="swiper-slide text-center">
  <div class="card border-1 shadow-sm h-100 nm-hover-effect">
    <div class="position-relative overflow-hidden">
      {{-- Image / Video --}}
      @if($media->isImage())
        <a href="{{ $media->url() }}" data-fancybox="single-gallery">
          <img 
            src="{{ $media->thumb() }}" 
            alt="Gallery Image" 
            class="w-100 rounded-top img-fluid"
            style="height: 300px; object-fit: cover;"
          >
        </a>
      @else
        <div class="ratio ratio-16x9" style="height: 300px; overflow: hidden;">
          <video 
            class="w-100 h-100 rounded-top" 
            controls 
            style="object-fit: cover; display: block;"
          >
            <source src="{{ $media->url() }}" type="video/mp4">
          </video>
        </div>
      @endif

      {{-- Overlay Button --}}
      <div class="position-absolute top-0 end-0 m-2">
        <a href="{{ $media->url() }}" 
           class="btn btn-sm btn-light rounded-circle shadow"
           @if($media->isImage()) data-fancybox="single-gallery" @endif
           title="View">
            <i class="pe-7s-look"></i>
        </a>
      </div>
    </div>

    {{-- Card Body --}}
    {{-- <div class="card-body text-center py-3">
      <h6 class="fw-semibold mb-1">
        <a href="{{ $galleryUrl ?? 'javascript:void(0)' }}" 
           class="text-dark text-decoration-none nm-hover-title">
          {{ $galleryTitle }}
        </a>
      </h6>
      <p class="text-muted small mb-0">
        {{ $media->isImage() ? 'Image' : ($media->isVideo() ? 'Video' : '') }}
      </p>
    </div> --}}
    
  </div>
</div>
