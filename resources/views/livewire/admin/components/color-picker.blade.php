@props(['model'])

<div x-data="{ color: @entangle($attributes->wire('model')) }">
    <input type="color" x-model="color" class="w-12 h-12 border-0 cursor-pointer rounded-full shadow" />
</div>
