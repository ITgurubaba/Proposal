<div class="product-variant-choice"
     x-data="{
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
            if(type === 'color') {
                this.availableColors = @js(collect($colorOptions)->pluck('id')->toArray());
            } else {
                this.availableSizes = @js(collect($sizeOptions)->pluck('id')->toArray());
            }
        },
        resetColor() {
            this.selectedColor = null;
            this.availableColors = @js(collect($colorOptions)->pluck('id')->toArray());
            this.disableColor = false;
            this.colorWarning = '';
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
                this.resetColor();
                return;
            }

            this.selectedColor = color;

            if (!color) {
                this.resetOptions('size');
                this.disableSize = false;
                return;
            }

            // Support both full and partial variants
            this.availableSizes = this.variants.filter(v =>
                (v.product_type === 'both' && v.sub_product_type_id === color) ||
                (v.product_type === 'color' && v.sub_product_type_id === color)
            ).map(v => parseInt(v.product_type_id));

            if (this.availableSizes.length === 0) {
                this.disableSize = true;
                this.sizeWarning = 'No sizes available for this color';
            } else {
                this.disableSize = false;
                this.sizeWarning = '';
            }

            if (this.selectedSize && !this.availableSizes.includes(parseInt(this.selectedSize))) {
                this.selectedSize = null;
            }
        },

        handleSizeChange(e) {
            const size = parseInt(e.target.value);

            if (this.selectedSize === size) {
                this.resetSize();
                return;
            }

            this.selectedSize = size;

            if (!size) {
                this.resetOptions('color');
                this.disableColor = false;
                return;
            }

            // Support both full and partial variants
            this.availableColors = this.variants.filter(v =>
                (v.product_type === 'both' && v.product_type_id === size) ||
                (v.product_type === 'size' && v.product_type_id === size)
            ).map(v => parseInt(v.sub_product_type_id));

            if (this.availableColors.length === 0) {
                this.disableColor = true;
                this.colorWarning = 'No colors available for this size';
            } else {
                this.disableColor = false;
                this.colorWarning = '';
            }

            if (this.selectedColor && !this.availableColors.includes(parseInt(this.selectedColor))) {
                this.selectedColor = null;
            }
        }
     }"
     wire:ignore
>
    @if(count($colorOptions) > 0)
        <div class="variant-group mb-3">
            <label class="form-label fw-bold mb-2">Color</label>
            <div class="d-flex flex-wrap gap-2">
                @foreach($colorOptions as $color)
                    <div class="form-check variant-radio">
                        <input
                            type="radio"
                            class="form-check-input"
                            name="color"
                            id="color-{{ $color['id'] }}"
                            value="{{ $color['id'] }}"
                            x-on:click="handleColorChange"
                            :disabled="disableColor || (!availableColors.includes({{ $color['id'] }}) && selectedColor !== {{ $color['id'] }})"
                            :checked="parseInt(selectedColor) === {{ $color['id'] }}"
                        >
                        <label class="form-check-label" for="color-{{ $color['id'] }}">
                            {{ $color['name'] }}
                        </label>
                    </div>
                @endforeach
            </div>
            <div x-show="colorWarning" x-text="colorWarning" class="text-danger mt-2"></div>
        </div>
    @endif

    @if(count($sizeOptions) > 0)
        <div class="variant-group">
            <label class="form-label fw-bold mb-2">Size</label>
            <div class="d-flex flex-wrap gap-2">
                @foreach($sizeOptions as $size)
                    <div class="form-check variant-radio">
                        <input
                            type="radio"
                            class="form-check-input"
                            name="size"
                            id="size-{{ $size['id'] }}"
                            value="{{ $size['id'] }}"
                            x-on:click="handleSizeChange"
                            :disabled="disableSize || (!availableSizes.includes({{ $size['id'] }}) && selectedSize !== {{ $size['id'] }})"
                            :checked="parseInt(selectedSize) === {{ $size['id'] }}"
                        >
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
