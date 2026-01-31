<div class="product-variant-choice" x-data="{
    variants: @js($productVariants),
    availableColors: @js(collect($colorOptions)->pluck('id')->toArray()),
    availableSizes: @js(collect($sizeOptions)->pluck('id')->toArray()),
    disableColor: false,
    disableSize: false,
    colorWarning: '',
    sizeWarning: '',
    selectedColor: @entangle('request.color'),
    selectedSize: @entangle('request.size'),

    resetOptions(type) {
        if (type === 'color') {
            this.availableColors = @js(collect($colorOptions)->pluck('id')->toArray());
        } else {
            this.availableSizes = @js(collect($sizeOptions)->pluck('id')->toArray());
        }
    },

    resetColor() {
        this.selectedColor = null;
        this.resetOptions('color');
        this.resetOptions('size');
        this.disableSize = false;
        this.colorWarning = '';
        this.sizeWarning = '';
    },

    resetSize() {
        this.selectedSize = null;
        this.resetOptions('size');
        this.resetOptions('color');
        this.disableColor = false;
        this.colorWarning = '';
        this.sizeWarning = '';
    },

    handleColorChange(e) {
        const color = parseInt(e.target.value);
        if (this.selectedColor === color) {
            this.selectedColor = null;
            $wire.set('request.color', null);
        } else {
            this.selectedColor = color;
            $wire.set('request.color', color);
        }
    },
    handleSizeChange(e) {
        const size = parseInt(e.target.value);
        if (this.selectedSize === size) {
            this.selectedSize = null;
            $wire.set('request.size', null);
        } else {
            this.selectedSize = size;
            $wire.set('request.size', size);
        }
    }

}" wire:ignore>
    @if (count($colorOptions) > 0)
        <div class="variant-group mb-3">
            <label class="form-label fw-bold mb-2">Color</label>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($colorOptions as $color)
                    <div class="form-check variant-radio">
                        <input type="radio" class="form-check-input" name="color" id="color-{{ $color['id'] }}"
                            value="{{ $color['id'] }}" x-on:click="handleColorChange"
                            :disabled="disableColor || (!availableColors.includes({{ $color['id'] }}) && selectedColor !==
                                {{ $color['id'] }})"
                            :checked="parseInt(selectedColor) === {{ $color['id'] }}">
                        <label class="form-check-label" for="color-{{ $color['id'] }}">
                            {{ $color['name'] }}
                        </label>
                    </div>
                @endforeach
            </div>
            <div x-show="colorWarning" x-text="colorWarning" class="text-danger mt-2"></div>
        </div>
    @endif

    @if (count($sizeOptions) > 0)
        <div class="variant-group">
            <label class="form-label fw-bold mb-2">Size</label>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($sizeOptions as $size)
                    <div class="form-check variant-radio">
                        <input type="radio" class="form-check-input" name="size" id="size-{{ $size['id'] }}"
                            value="{{ $size['id'] }}" x-on:click="handleSizeChange"
                            :disabled="disableSize || (!availableSizes.includes({{ $size['id'] }}) && selectedSize !==
                                {{ $size['id'] }})"
                            :checked="parseInt(selectedSize) === {{ $size['id'] }}">
                        <label class="form-check-label" for="size-{{ $size['id'] }}">
                            {{ $size['name'] }}
                        </label>
                    </div>
                @endforeach
            </div>
            <div x-show="sizeWarning" x-text="sizeWarning" class="text-danger mt-2"></div>
        </div>
    @endif
</div>
