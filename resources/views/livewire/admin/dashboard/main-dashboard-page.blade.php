<div>

    <x-mary-header subtitle="Check this on mobile">

        <x-slot:title class="text-4xl">
            Dashboard
        </x-slot:title>

        {{-- <x-slot:middle class="!justify-end">
            <x-mary-input icon="o-magnifying-glass" placeholder="Search..." />
        </x-slot:middle>
        <x-slot:actions>
            <x-mary-button icon="o-funnel" />
            <x-mary-button icon="o-plus" class="btn-primary" />
        </x-slot:actions> --}}
    </x-mary-header>
    <div class="flex flex-wrap gap-4 my-5">
        <div>
            <label for="selectedYear">Select Year</label>
            <select id="selectedYear" wire:model.live="selectedYear" class="border p-2 rounded">
                @for ($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label for="selectedMonth">Select Month</label>
            <select id="selectedMonth" wire:model.live="selectedMonth" class="border p-2 rounded">
                @foreach ([1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'] as $m => $name)
                    <option value="{{ $m }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-10">


        <div x-data @click="Livewire.navigate('/admin/contact/list')"
            class="shadow hover:shadow-lg duration-300 cursor-pointer">
            <x-mary-stat title="Unseen Messages" value="{{ $contactMsg }}" icon="o-envelope" />
        </div>
        <div class="shadow hover:shadow-lg duration-300 cursor-pointer">
            <x-mary-stat title="Pending Orders" :value="number_format($totalPendingOrders)" icon="o-queue-list" />
        </div>
        <div class="shadow hover:shadow-lg duration-300 cursor-pointer">
            <x-mary-stat title="Sales" description="This month" :value="number_format($monthlySales, 2)" icon="o-arrow-trending-up" />
            <x-mary-stat title="Delivered " :value="number_format($deliveredCount)" icon="o-archive-box" />
        </div>

        <div class="shadow hover:shadow-lg duration-300 cursor-pointer w-full max-w-[250px]">
            <x-mary-stat title="Lost" description="This month" :value="number_format($monthlyLoss, 2)" icon="o-arrow-trending-down"
                class="text-red-600" />
            <x-mary-stat title="Canceled/Refunded" :value="number_format($refundedCount + $cancelledCount)" icon="o-archive-box-x-mark" />
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
