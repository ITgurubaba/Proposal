<div class="breadcrumb-area breadcrumb-height text-center"
     style="height: 140px;"
     data-bg-image="{{ asset($bgImage ?? '') }}"
     wire:ignore
>
  <div class="container h-100">
    <div class="row h-100 align-items-center justify-content-center">
      <div class="col-lg-12">
        <div class="breadcrumb-item">

          {{-- Title (with highlight + custom color) --}}
          <h2 class="breadcrumb-heading mb-2 fw-semibold"
              style="{{ $titleColor ? 'color: '.$titleColor.';' : '' }}">
            @if($highlight && str_contains($title, $highlight))
              {!! preg_replace(
                  '/'.preg_quote($highlight, '/').'/u',
                  '<span class="'.$highlightClass.'">$0</span>',
                  e($title),
                  1
              ) !!}
            @else
              {{ $title }}
            @endif
          </h2>

          {{-- Breadcrumb trail --}}
          <ul class="d-inline-flex flex-wrap gap-2 justify-content-center align-items-center m-0 p-0 list-unstyled small">
            <li>
              <a href="{{ route('frontend::home') }}" class="text-decoration-none fw-medium">Home</a>
            </li>
            @if(!empty($menuItem))
              <li>/</li>
              <li>
                @if($menuUrl)
                  <a href="{{ $menuUrl }}" class="text-decoration-none fw-medium text-secondary">
                    {{ $menuItem }}
                  </a>
                @else
                  <span class="fw-medium text-secondary">{{ $menuItem }}</span>
                @endif
              </li>
            @endif
          </ul>

          {{-- Optional Back button --}}
          @if($showBack)
            @php
              $goBack = $backUrl ? "window.location.href='{$backUrl}'" : "history.back()";
            @endphp
            <div class="mt-3">
              <button type="button"
                      class="btn btn-sm btn-outline-secondary"
                      onclick="{{ $goBack }}">
                ← Back
              </button>
            </div>
          @endif

        </div>
      </div>
    </div>
  </div>
</div>
