<?php

namespace App\Livewire\Admin\ServiceContent;

use App\Models\Service;
use App\Models\ServiceContent;
use App\Models\ServiceItem;
use Livewire\Component;
use Mary\Traits\Toast;

class ServiceContentManager extends Component
{
    use Toast;

    public $services;
    public $selected_service_id = null;
    public $selected_item_id = null;

    // Content form fields
    public $content_id = null;
    public $title = '';
    public $content = '';
    public $showAllContent = false;


    public function mount()
    {
        $this->services = Service::with(['items', 'contents'])->get();
    }

    public function getSelectedServiceProperty()
    {
        if ($this->selected_service_id) {
            return Service::with(['items', 'contents'])->find($this->selected_service_id);
        }
        return null;
    }

    public function selectService($serviceId)
    {
        $this->selected_service_id = $serviceId;
        $this->selected_item_id = null;

        $service = Service::find($serviceId);
        if (!$service) return;

        $this->title = $service->name;

        $content = ServiceContent::where('service_id', $serviceId)
            ->whereNull('service_item_id')
            ->first();

        if ($content) {
            $this->content_id = $content->id;
            $this->content = $content->content;
        } else {
            $this->resetForm(false);
        }

        $this->dispatch('refreshEditor', $this->content);
    }

    public function selectItem($itemId)
    {
        $this->selected_item_id = $itemId;

        // 🔴 VERY IMPORTANT: service_id bhi set karo
        $item = ServiceItem::find($itemId);
        if (!$item) return;

        $this->selected_service_id = $item->service_id;

        $this->title = $item->name;

        $content = ServiceContent::where('service_item_id', $itemId)->first();

        if ($content) {
            $this->content_id = $content->id;
            $this->content = $content->content;
        } else {
            $this->content_id = null;
            $this->content = '';
        }

        $this->dispatch('refreshEditor', $this->content);
    }

    public function editContent($contentId)
    {
        $content = ServiceContent::find($contentId);
        if ($content) {
            $this->content_id = $content->id;
            $this->title = $content->title;
            $this->content = $content->content;

            $this->dispatch('refreshEditor', $this->content);
        }
    }

    public function saveContent()
    {
        if (!$this->selected_service_id) {
            $this->error('Error', 'Service not selected');
            return;
        }

        $this->validate([
            'content' => 'required|string',
        ]);

        ServiceContent::updateOrCreate(
            [
                'service_id' => $this->selected_service_id,
                'service_item_id' => $this->selected_item_id,
            ],
            [
                'title' => $this->title,
                'content' => $this->content,
            ]
        );

        $this->success('Content', 'Saved successfully');
    }

    public function deleteContent($contentId)
    {
        ServiceContent::findOrFail($contentId)->delete();
        $this->success('Content', 'Deleted successfully');
        $this->mount();
    }

    public function resetForm($clearEditor = true)
    {
        $this->content_id = null;
        $this->title = '';
        $this->content = '';

        if ($clearEditor) {
            $this->dispatch('refreshEditor', '');
        }
    }

    public function render()
    {
        return view('livewire.admin.service-content.service-content-manager');
    }
}
