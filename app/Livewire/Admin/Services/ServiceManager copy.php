<?php

namespace App\Livewire\Admin\Services;

use Livewire\Component;
use App\Models\Service;
use App\Models\ServiceField;
use App\Models\ServiceItem;

class ServiceManager extends Component
{
    public $services;

    // ================= SERVICE =================
    public $service_id;
    public $name;
    public $base_price;
    public $pricing_type = 'individual';

    // ================= FIELD =================
    public $field_name;
    public $field_label;
    public $field_type = 'text';
    public $options = '';
    public $is_required = false;

    // ================= SUB SERVICE =================
    public $item_name;
    public $item_price;

    // ================= EDIT IDS =================
    public $editItemId = null;
    public $editFieldId = null;

    // ================= SINGLE SERVICE =================
    public $serviceId;
    public $service;

    // ================= MOUNT =================
    public function mount($service_id = null)
    {
        if ($service_id) {
            // 👉 SINGLE SERVICE MODE
            $this->serviceId = $service_id;
            $this->service = Service::with(['fields','items'])->findOrFail($service_id);

            $this->service_id   = $this->service->id;
            $this->name         = $this->service->name;
            $this->base_price   = $this->service->base_price;
            $this->pricing_type= $this->service->pricing_type;

            $this->services = collect([$this->service]);
        } else {
            // 👉 ALL SERVICES MODE (optional)
            $this->services = Service::with(['fields','items'])->get();
        }
    }

    // ================= SERVICE =================
    public function saveService()
    {
        $this->validate([
            'name' => 'required',
            'base_price' => 'required|numeric',
            'pricing_type' => 'required',
        ]);

        Service::updateOrCreate(
            ['id' => $this->service_id],
            [
                'name' => $this->name,
                'base_price' => $this->base_price,
                'pricing_type' => $this->pricing_type,
            ]
        );

        $this->reset(['service_id','name','base_price','pricing_type']);
        $this->refreshServices();
    }

    public function deleteService($id)
    {
        Service::findOrFail($id)->delete();
        $this->refreshServices();
    }

    // ================= SUB SERVICES =================
    public function saveItem($serviceId)
    {
        $this->validate([
            'item_name' => 'required',
            'item_price' => 'nullable|numeric',
        ]);

        ServiceItem::create([
            'service_id' => $serviceId,
            'name' => $this->item_name,
            'price' => $this->item_price,
        ]);

        $this->reset(['item_name','item_price']);
        $this->refreshServices();
    }

    public function editItem($id)
    {
        $item = ServiceItem::findOrFail($id);

        $this->editItemId = $id;
        $this->item_name = $item->name;
        $this->item_price = $item->price;
    }

    public function updateItem($serviceId)
    {
        $this->validate([
            'item_name' => 'required',
            'item_price' => 'nullable|numeric',
        ]);

        ServiceItem::where('id', $this->editItemId)->update([
            'name' => $this->item_name,
            'price' => $this->item_price,
        ]);

        $this->reset(['item_name','item_price','editItemId']);
        $this->refreshServices();
    }

    public function deleteItem($id)
    {
        ServiceItem::findOrFail($id)->delete();
        $this->refreshServices();
    }

    // ================= SERVICE FIELDS =================
    public function saveField($serviceId)
    {
        $this->validate([
            'field_name' => 'required',
            'field_label' => 'required',
            'field_type' => 'required',
        ]);

        $options = $this->processOptions();

        ServiceField::create([
            'service_id' => $serviceId,
            'field_name' => $this->field_name,
            'field_label' => $this->field_label,
            'field_type' => $this->field_type,
            'options' => $options,
            'is_required' => $this->is_required,
        ]);

        $this->reset(['field_name','field_label','field_type','options','is_required']);
        $this->refreshServices();
    }

    public function editField($id)
    {
        $field = ServiceField::findOrFail($id);

        $this->editFieldId = $id;
        $this->field_name = $field->field_name;
        $this->field_label = $field->field_label;
        $this->field_type = $field->field_type;
        $this->options = is_array($field->options) ? implode(',', $field->options) : '';
        $this->is_required = $field->is_required;
    }

    public function updateField($serviceId)
    {
        $this->validate([
            'field_name' => 'required',
            'field_label' => 'required',
            'field_type' => 'required',
        ]);

        $options = $this->processOptions();

        ServiceField::where('id', $this->editFieldId)->update([
            'field_name' => $this->field_name,
            'field_label' => $this->field_label,
            'field_type' => $this->field_type,
            'options' => $options,
            'is_required' => $this->is_required,
        ]);

        $this->reset(['field_name','field_label','field_type','options','is_required','editFieldId']);
        $this->refreshServices();
    }

    public function deleteField($id)
    {
        ServiceField::findOrFail($id)->delete();
        $this->refreshServices();
    }

    // ================= HELPERS =================
    private function processOptions()
    {
        if ($this->field_type === 'select' && !empty(trim($this->options))) {
            return array_map('trim', explode(',', $this->options));
        }
        return null;
    }

    public function updatedFieldType($value)
    {
        if ($value !== 'select') {
            $this->options = '';
        }
    }

    // ================= REFRESH =================
    public function refreshServices()
    {
        if ($this->serviceId) {
            $this->service = Service::with(['fields','items'])->findOrFail($this->serviceId);
            $this->services = collect([$this->service]);
        } else {
            $this->services = Service::with(['fields','items'])->get();
        }
    }

    // ================= RENDER =================
    public function render()
    {
        return view('livewire.admin.services.service-manager');
    }
}
