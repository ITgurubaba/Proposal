<div>
    <x-mary-header subtitle="Create or update static website content like Privacy Policy, Laws, Engagement Letter etc.">

        <x-slot:title class="text-4xl">
            {{ $content_id ? 'Edit Content' : 'Add Content' }}
        </x-slot:title>

        {{-- 🔥 STATUS TOGGLE IN HEADER --}}
        <x-slot:middle class="!justify-end">
            <x-mary-toggle
                wire:model.live="status"
                label="{{ $status ? 'Active' : 'Inactive' }}"
            />
        </x-slot:middle>

        <x-slot:actions>
            <x-mary-button
                icon="o-arrow-left"
                class="btn-secondary"
                href="{{ route('admin::other-content:list') }}"
                wire:navigate
            />
        </x-slot:actions>

    </x-mary-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left Column - Form --}}
        <div class="lg:col-span-2 space-y-6">

            <x-mary-card class="shadow border">
                <h3 class="text-lg font-semibold mb-4">Content Details</h3>

                <x-mary-input
                    label="Title"
                    placeholder="e.g. Privacy Policy, Terms & Conditions"
                    wire:model.defer="title"
                    required
                />

                {{-- CKEditor --}}
                <div class="mt-4">
                    <label class="block text-sm font-medium mb-2">Content</label>

                    <x-admin.forms.ck-editor-input
                        wire:model.defer="content"
                    />
                </div>

            </x-mary-card>

        </div>

        {{-- Right Column - Action --}}
        <div class="lg:col-span-1">
            <x-mary-card class="shadow border sticky top-4">
                <h3 class="text-lg font-semibold mb-4">Actions</h3>

                <div class="space-y-4">
                    <x-mary-button
                        label="Save Content"
                        icon="o-check"
                        class="btn-primary w-full"
                        wire:click="save"
                    />
                </div>
            </x-mary-card>
        </div>

    </div>
</div>
