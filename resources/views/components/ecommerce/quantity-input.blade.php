<div x-data="{
      model:@entangle($attributes->wire('model')),
      increment:function(){
          this.model = this.model + 1;
      },
      decrement:function(){
          this.model = this.model - (this.model > 1 ? 1 : 0);
      },
     }"
     class="input-group h-100"
     wire:ignore
>
    <div class="input-group-text p-0">
        <button class="btn btn-light h-100"
                @click.prevent="decrement"
                style="border-top-right-radius: 0;border-bottom-right-radius: 0">
            <i class="fa fa-minus"></i>
        </button>
    </div>
    <input value="1"
           type="text"
           x-model="model"
           class="form-control text-center"
           style="width: 50px"
    >
    <div class="input-group-text p-0">
        <button class="btn btn-light h-100"
                @click.prevent="increment"
                style="border-top-left-radius: 0;border-bottom-left-radius: 0">
            <i class="fa fa-plus"></i>
        </button>
    </div>
</div>
