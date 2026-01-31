<div class="{{ $attributes->get('parent-class') ??'' }}">
    @if($attributes->has('label'))
        <label>{{ $attributes->get('label') ??'' }}</label>
    @endif
    <select class="form-select @error($attributes->wire('model')->value()) is-invalid @enderror"
        {!! $attributes->merge($attributes->getAttributes()) !!}
    >
        {{ $slot ??'' }}
    </select>
    @error($attributes->wire('model')->value())
    <div class="invalid-feedback"> {{$message}} </div>
    @enderror
</div>
