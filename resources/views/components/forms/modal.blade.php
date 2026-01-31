<div x-data="{
    isOpen: false,
    model: @entangle($attributes->wire('model')),
    openModal:function(){
        this.isOpen = true;
        $($refs.modalDialog).modal('show');
    },
    closeModal:function(){
        this.isOpen = false;
        $($refs.modalDialog).modal('hide');
    },
}"

     x-init="$watch('model', value => {
        if (value) {
           openModal();
        } else {
           closeModal();
        }
    })"

>
    <div class="modal fade modal-close-out"
         data-bs-backdrop="static"
         x-ref="modalDialog"
         wire:ignore.self
    >
        <div class="modal-dialog {{ $size ??'' }}">
            <div class="modal-content">
                <div class="modal-header pb-2">
                    <div class="modal-title">
                        <h5 class="font-bold">
                            {{ $title ??'' }}
                        </h5>
                        @if(checkData($description))
                            <p class="small">{{ $description ??'' }}</p>
                        @endif
                    </div>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2"
                         @click="model = false"
                    >
                        <i class="fa fa-close"></i>
                    </div>
                    <!--end::Close-->
                </div>
                <div class="modal-body">
                    {!! $body !!}
                </div>
                @if($footer)
                    <div class="modal-footer">
                        {!! $footer !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
