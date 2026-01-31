<div>
    <x-mary-header subtitle="Here you can setup your store configurations" >

        <x-slot:title class="text-4xl">
            Store Setting
        </x-slot:title>

        <x-slot:middle class="!justify-end">

        </x-slot:middle>
        <x-slot:actions>

        </x-slot:actions>
    </x-mary-header>
    <x-mary-card class="shadow-sm border">
        <x-mary-tabs wire:model="selectedTab">
            <x-mary-tab name="main-tab" label="General" >
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-12">
                        <x-forms.input-label label="Product Information">
                            <x-admin.forms.ck-editor-input wire:model="request.product_page_information" />
                        </x-forms.input-label>
                    </div>
                </div>
            </x-mary-tab>
{{--            <x-mary-tab name="others-tab" label="Others" >--}}
{{--                <div>Others</div>--}}
{{--            </x-mary-tab>--}}
        </x-mary-tabs>
        <div class="text-center mt-5">
            <x-mary-button class="btn-primary"
                           label="Save Setting"
                           wire:click.prevent="Save"
                           spinner="Save"
            />
        </div>
    </x-mary-card>
</div>
