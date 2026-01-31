<div>
    <x-mary-header subtitle="Showing the list of taxes." >

        <x-slot:title class="text-4xl">
            Tax Schemes
        </x-slot:title>

    </x-mary-header>

    <div class="grid grid-cols-12 gap-3">
        <div class="col-span-12 lg:col-span-3">
            <x-mary-card class="shadow border"
                         title="New Tax Scheme"
                         subtitle="Here you can add new tax scheme"
            >
                <x-mary-select wire:model="request.country_id"
                               label="Select Country"
                               :options="$countries"
                               option-label="nicename"
                               option-value="id"
                               class="mb-3"

                />
                <div class="mb-3">
                    <x-mary-select wire:model.live="request.type"
                                   label="Shipping Rate Type"
                                   :options="Ecommerce::getTypes('shipping')"
                                   option-label="label"
                                   option-value="value"
                    />
                </div>
                <div class="mb-3">
                    @if($request['type'] == "percentage")
                        <x-mary-input wire:model="request.rate"
                                      label="Shipping Rate"
                                      type="number"
                                      min="0"
                                      step="any"
                                      suffix="%"
                        />
                    @else
                        <x-mary-input wire:model="request.rate"
                                      label="Rate"
                                      type="number"
                                      min="0"
                                      step="any"
                                      suffix="{{ getDefaultCurrencySymbol() }}"
                        />
                    @endif
                </div>
                <x-mary-select wire:model="request.status"
                               label="Status"
                               :options="BackendHelper::STATUS_OPTIONS"
                               option-value="value"
                               option-label="label"
                               class="mb-3"
                />
                <div class="text-center">
                    <x-mary-button label="Submit"
                                   class="btn btn-primary"
                                   wire:click.prevent="Submit"
                                   spinner="Submit"
                    />
                </div>
            </x-mary-card>
        </div>
        <div class="col-span-12 lg:col-span-9">
            @forelse($data as $i=>$item)
                <x-mary-card class="border border-dashed mb-4">
                    @if($selectedTaxId == $item->id)
                        <form wire:submit.prevent="Save">
                            <div class="grid grid-cols-12 gap-3">
                                <div class="col-span-12 lg:col-span-6">
                                    <x-mary-input wire:model="editRequest.name"
                                                  label="Name"
                                    />
                                </div>
                                <div class="col-span-12 lg:col-span-6">
                                    <x-mary-select wire:model="editRequest.country_id"
                                                   label="Select Country"
                                                   :options="$countries"
                                                   option-label="nicename"
                                                   option-value="id"
                                                   class="mb-3"

                                    />
                                </div>
                                <div class="col-span-12 lg:col-span-4">
                                    <x-mary-select wire:model.live="editRequest.type"
                                                   label="Shipping Rate Type"
                                                   :options="Ecommerce::getTypes('shipping')"
                                                   option-label="label"
                                                   option-value="value"
                                    />
                                </div>
                                <div class="col-span-12 lg:col-span-4">
                                    @if($editRequest['type'] == "percentage")
                                        <x-mary-input wire:model="editRequest.rate"
                                                      label="Shipping Rate"
                                                      type="number"
                                                      min="0"
                                                      step="any"
                                                      suffix="%"
                                        />
                                    @else
                                        <x-mary-input wire:model="editRequest.rate"
                                                      label="Shipping Rate"
                                                      type="number"
                                                      min="0"
                                                      step="any"
                                                      suffix="{{ getDefaultCurrencySymbol() }}"
                                        />
                                    @endif
                                </div>
                                <div class="col-span-12 lg:col-span-4">
                                    <x-mary-select wire:model="editRequest.status"
                                                   label="Status"
                                                   :options="BackendHelper::STATUS_OPTIONS"
                                                   option-value="value"
                                                   option-label="label"
                                                   class="mb-3"
                                    />
                                </div>
                                <div class="col-span-12">
                                    <x-mary-button label="Save"
                                                   class="btn btn-primary"
                                                   type="submit"
                                                   spinner="Save"
                                    />
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="flex justify-between gap-3">
                            <div>
                                <h4 class="font-bold">
                                    {{ $item->name ??$item->countryName() }}
                                </h4>
                                <p class="text-sm mt-3">
                                    @if($item->type == "percentage")
                                        Tax ({{ round($item->rate,2) }}%) <br>
                                    @else
                                        Tax ({{ currency($item->rate) }}) <br>
                                    @endif

                                    Country: {{ $item->countryName() }}
                                </p>
                            </div>
                            <div>
                                @if($item->status)
                                    <x-mary-badge value="Active" class="badge-primary" />
                                @else
                                    <x-mary-badge value="Inactive" class="badge-warning" />
                                @endif
                                <div class="mt-3 flex gap-3">
                                    <x-mary-button class="btn btn-sm btn-primary btn-square"
                                                   icon="o-pencil"
                                                   wire:click.prevent="EditRequest({{ $item->id }})"
                                                   spinner="EditRequest({{ $item->id }})"
                                    />
                                    <x-mary-button class="btn btn-sm btn-error btn-square"
                                                   icon="o-trash"
                                                   wire:click.prevent="destroy({{ $item->id }})"
                                                   spinner="destroy({{ $item->id }})"
                                    />
                                </div>
                            </div>
                        </div>
                    @endif
                </x-mary-card>
            @empty
                <x-mary-card class="border shadow">
                    <p class="text-center">
                        No data available
                    </p>
                </x-mary-card>
            @endforelse
        </div>
    </div>

</div>
