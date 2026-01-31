<div x-data="{
    model: @entangle($attributes->wire('model')),
    prefixUrl:'{{ url('/') }}' + '/',
    removeFile:function(){
       this.model = null;
    }
}"
     class="flex align-middle items-center h-full"
     x-cloak
>
    <div class="relative flex align-middle items-center gap-3">
        <div>
            <img :src="model!=null && model!=''?(prefixUrl + model):'{{ asset($defaultImage) }}'"
                {{ $attributes->merge() }}
            />
        </div>
        <div x-show="model!=null && model!=''">
            <x-mary-button icon="o-trash"
                           class="btn btn-sm btn-circle btn-error text-white"
                           tooltip="Remove"
                           @click.prevent="removeFile"
            />
        </div>
    </div>
</div>
