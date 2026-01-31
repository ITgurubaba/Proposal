<div x-data="{
       model: @entangle($attributes->wire('model')),
       options: @json($options),
       optionLabel: '{{ $optionLabel }}',
       optionValue: '{{ $optionValue }}',
     }"
     x-init="
         // Initialize nice-select
         $($refs.select).niceSelect();

         // Watch for changes in the select element
         $($refs.select).on('change', (event) => {
             model = event.target.value; // Update the Alpine model
         });

         // Watch for changes in the Alpine model to update the select
         $watch('model', (newValue) => {
             $($refs.select).val(newValue).niceSelect('update'); // Update nice-select
         });
     "
     class="selector-wrap {{ $attributes->get('parent-class') ?? 'color-option' }}"
     wire:ignore
>
    <span class="selector-title border-bottom-0">{{ $attributes->get('label') ?? '' }}</span>
    <select x-ref="select" {!! $attributes->merge(['class' => 'wide border-bottom-0 rounded-0']) !!}>
        <option value="">Select</option>
        <template x-for="(item, index) in options" :key="index">
            <option :value="item[optionValue]" x-text="item[optionLabel]"></option>
        </template>
    </select>
</div>
