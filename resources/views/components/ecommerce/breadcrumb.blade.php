<div class="breadcrumb-area breadcrumb-height"
     style="height: 140px;"
     data-bg-image="{{ asset($bgImage ??'') }}"
     wire:ignore
>
    <div class="container h-100">
        <div class="row h-100">
            <div class="col-lg-12">
                <div class="breadcrumb-item">
                    <h2 class="breadcrumb-heading">
                        {{ $title ??'' }}
                    </h2>
                    <ul>
                
                        <li>
                            <a href="{{ route('frontend::home') }}">Home</a>
                        </li>
                        <li>{{ $menuItem ??'' }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
