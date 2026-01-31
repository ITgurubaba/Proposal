<div>

    <x-mary-header subtitle="Here you can change website settings">

        <x-slot:title class="text-4xl">
            General Settings
        </x-slot:title>

        <x-slot:actions>

        </x-slot:actions>
    </x-mary-header>

    <x-mary-card class="shadow border">
        <form wire:submit.prevent="save">
            <x-mary-tabs wire:model="selectedTab">
                <x-mary-tab name="main-tab" label="Main" icon="o-home">
                    <x-mary-input wire:model="request.site_name" label="Site Name" class="mb-5" />
                    <x-mary-textarea wire:model="request.site_description" label="Site Description" class="mb-5" />
                    <x-mary-input wire:model="request.site_title" label="Site Title" class="mb-5" />
                    <div class="mb-5">
                        <x-mary-input wire:model="request.meta_title" label="Meta Name" hint="(Max 110 characters)" />
                    </div>
                    <div class="mb-5">
                        <x-mary-textarea wire:model="request.meta_description" label="Meta Description"
                            hint="(Max 160 characters)" />
                    </div>
                    <div class="grid lg:grid-cols-4 gap-5 mb-5">
                        <div class="col-span-2">
                            <x-forms.input-label label="Meta OS Image" description="(Dimension:1,200 x 630 pixels)">
                                <x-forms.filepond wire:model="request.meta_os_image" folder="logo/" accept="image/*" />
                            </x-forms.input-label>
                        </div>
                        <div class="col-span-2">
                            <x-admin.forms.image-viewer wire:model="request.meta_os_image" class="max-w-40" />
                        </div>
                    </div>
                    <x-mary-tags label="Tags" wire:model="request.meta_tags" hint="Hit enter to create a new tag"
                        class="tagInput" />
                </x-mary-tab>
                <x-mary-tab name="logo-tab" label="Logo" icon="o-photo">
                    <div class="grid lg:grid-cols-4 gap-5 mb-5">
                        <div class="col-span-2">
                            <x-forms.input-label label="Website Logo">
                                <x-forms.filepond wire:model="request.site_logo" folder="logo/" accept="image/*" />
                            </x-forms.input-label>
                        </div>
                        <div class="col-span-2">
                            <x-admin.forms.image-viewer wire:model="request.site_logo" class="max-w-20" />
                        </div>
                    </div>
                    <div class="grid lg:grid-cols-4 gap-5 mb-5">
                        <div class="col-span-2">
                            <x-forms.input-label label="Website Logo 2">
                                <x-forms.filepond wire:model="request.site_logo_2" folder="logo/" accept="image/*" />
                            </x-forms.input-label>
                        </div>
                        <div class="col-span-2">
                            <x-admin.forms.image-viewer wire:model="request.site_logo_2" class="max-w-20" />
                        </div>
                    </div>
                    <div class="grid lg:grid-cols-4 gap-5 mb-5">
                        <div class="col-span-2">
                            <x-forms.input-label label="Website Logo Mobile">
                                <x-forms.filepond wire:model="request.site_mobile_logo" folder="logo/"
                                    accept="image/*" />
                            </x-forms.input-label>
                        </div>
                        <div class="col-span-2">
                            <x-admin.forms.image-viewer wire:model="request.site_mobile_logo" class="max-w-20" />
                        </div>
                    </div>
                    <div class="grid lg:grid-cols-4 gap-5 mb-5">
                        <div class="col-span-2">
                            <x-forms.input-label label="Website Favicon Icon">
                                <x-forms.filepond wire:model="request.site_favicon" folder="logo/" accept="image/*" />
                            </x-forms.input-label>
                        </div>
                        <div class="col-span-2">
                            <x-admin.forms.image-viewer wire:model="request.site_favicon" class="max-w-20" />
                        </div>
                    </div>
                </x-mary-tab>
                <x-mary-tab name="company-tab" label="Company" icon="o-building-office">
                    <x-mary-input wire:model="request.company_name" label="Company Name" class="mb-5" />
                    <x-mary-input wire:model="request.company_email" label="Company Email" class="mb-5" />
                    <x-mary-input wire:model="request.company_phone" label="Company Phone" class="mb-5" />
                    <x-mary-input wire:model="request.company_wtsp_no" label="Company whatsapp No.(With Country Code)"
                        class="mb-5" />
                    {{-- <x-mary-input wire:model="request.product_query_wtsp_number"
                        label="Product Query Whatsapp No.(With Country Code)" class="mb-5" /> --}}
                    {{-- <x-mary-input wire:model="request.product_query_msg" label="Product Query Message" class="mb-5" /> --}}
                    <x-mary-textarea wire:model="request.company_about" label="About Company" class="mb-5" />
                    <x-mary-textarea wire:model="request.company_address" label="Company Address" class="mb-5" />
                </x-mary-tab>

                {{-- <x-mary-tab name="whatsapp-tab" label="Whatsapp" icon="o-building-office">
                    <x-mary-input wire:model="request.company_wtsp_no" label="Company whatsapp No.(With Country Code)"
                        class="mb-5" />
                    <x-mary-input wire:model="request.product_query_wtsp_number"
                        label="Product Query Whatsapp No.(With Country Code)" class="mb-5" />
                    <x-mary-input wire:model="request.product_query_msg" label="Product Query Message"
                        class="mb-5" />
                </x-mary-tab> --}}





                <x-mary-tab name="social-tab" label="Social Links" icon="o-chat-bubble-bottom-center-text">
                    <x-mary-input wire:model="request.fb_link" label="Facebook Link" class="mb-5" />
                    <x-mary-input wire:model="request.twitter_link" label="Twitter Link" class="mb-5" />
                    <x-mary-input wire:model="request.instagram_link" label="Instagram Link" class="mb-5" />
                    <x-mary-input wire:model="request.pinterest_link" label="Pinterest Link" class="mb-5" />
                    <x-mary-input wire:model="request.linked_in_link" label="LinkedIn Link" class="mb-5" />
                    <x-mary-input wire:model="request.youtube_link" label="Youtube Link" class="mb-5" />
                    <x-mary-input wire:model="request.google_plus_link" label="Google Plus Link" class="mb-5" />
                </x-mary-tab>
                <x-mary-tab name="footer-tab" label="Footer" icon="o-bars-3-bottom-left">
                    <div class="grid lg:grid-cols-4 gap-5 mb-5">
                        <div class="col-span-2">
                            <x-forms.input-label label="Footer Logo">
                                <x-forms.filepond wire:model="request.footer_logo" folder="logo/" accept="image/*" />
                            </x-forms.input-label>
                        </div>
                        <div class="col-span-2">
                            <x-admin.forms.image-viewer wire:model="request.footer_logo" class="max-w-20" />
                        </div>
                    </div>
                    <x-mary-textarea wire:model="request.footer_description" label="Footer Description"
                        class="mb-5" />
                </x-mary-tab>
                <x-mary-tab name="backend-tab" label="Backend" icon="o-command-line">
                    <div class="grid lg:grid-cols-4 gap-5 mb-5">
                        <div class="col-span-2">
                            <x-forms.input-label label="Admin Dashboard Logo">
                                <x-forms.filepond wire:model="request.admin_logo" folder="logo/" accept="image/*" />
                            </x-forms.input-label>
                        </div>
                        <div class="col-span-2">
                            <x-admin.forms.image-viewer wire:model="request.admin_logo" class="max-w-20" />
                        </div>
                    </div>
                </x-mary-tab>
                <x-mary-tab name="other-tab" label="Other" icon="o-cog-6-tooth">
                    <x-mary-input wire:model="request.app_base_url" label="App Base URL" class="mb-5" />

                    <div class="mb-5">
                        @php
                            $editors = [
                                [
                                    'value' => 'ck-editor-4',
                                    'label' => 'CK-Editor 4',
                                ],
                                [
                                    'value' => 'ck-editor-5',
                                    'label' => 'CK-Editor 5',
                                ],
                                [
                                    'value' => 'tiny-mce',
                                    'label' => 'TinyMCE',
                                ],
                                [
                                    'value' => 'quill-editor',
                                    'label' => 'Quill Rich Text Editor',
                                ],
                            ];
                        @endphp

                        <x-mary-select label="Editor Layout" placeholder="Select Editor Layout" placeholder-value="0"
                            hint="Select one, editor please." wire:model="request.editor_layout" :options="$editors"
                            option-label="label" option-value="value" />
                    </div>
                    <div class="mb-5">
                        <x-mary-toggle wire:model="request.captcha" :checked="(bool) $request['captcha']" value="1"
                            label="Image Captcha" />
                    </div>
                    <div class="mb-5">
                        <x-mary-toggle wire:model="request.maintenance_mode" :checked="(bool) $request['maintenance_mode']" value="1"
                            label="Maintenance Mode" />
                    </div>
                </x-mary-tab>
            </x-mary-tabs>

            <div class="text-center">
                <x-mary-button label="Submit" class="btn-primary" spinner="save" type="submit" />
            </div>
        </form>
    </x-mary-card>

</div>
