<div>

    <x-mary-header subtitle="Check this on mobile">

        <x-slot:title class="text-4xl">
            Dashboard
        </x-slot:title>

        <x-slot:middle class="!justify-end">
            <x-mary-input icon="o-magnifying-glass" placeholder="Search..." />
        </x-slot:middle>
        <x-slot:actions>
            <x-mary-button icon="o-funnel" />
            <x-mary-button icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-mary-header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-10">

        <div x-data @click="Livewire.navigate('/admin/contact/list')"
            class="shadow hover:shadow-lg duration-300 cursor-pointer">
            <x-mary-stat title="Unseen Messages" value="{{ $contactMsg }}" icon="o-envelope" />
        </div>

        <div class="shadow hover:shadow-lg duration-300  cursor-pointer">
            <x-mary-stat title="Sales" description="This month" value="22.124" icon="o-arrow-trending-up" />
        </div>

        <div class="shadow hover:shadow-lg duration-300  cursor-pointer">
            <x-mary-stat title="Lost" description="This month" value="34" icon="o-arrow-trending-down" />
        </div>

        <div class="shadow hover:shadow-lg duration-300  cursor-pointer">
            <x-mary-stat title="Sales" description="This month" value="22.124" icon="o-arrow-trending-down"
                class="text-orange-500" color="text-pink-500" />
        </div>
    </div>

    <div class="grid grid-cols-3 my-10 gap-10">
        <div class="col-span-2">
            <x-mary-chart wire:model="chartTwo" />
        </div>
        <div>
            <x-mary-chart wire:model="chartOne" />
        </div>
    </div>


</div>


@assets
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endassets
