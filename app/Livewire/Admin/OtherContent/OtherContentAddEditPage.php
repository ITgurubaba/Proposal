<?php

namespace App\Livewire\Admin\OtherContent;

use Livewire\Component;
use App\Models\OtherContent;

class OtherContentAddEditPage extends Component
{
    public $content_id;
    public $title;
    public $content;
    public $status = 1; // 1 = Active, 0 = Inactive

    protected $rules = [
        'title'   => 'required|string|max:255',
        'content' => 'required',
        'status'  => 'boolean',
    ];

 public function mount($content_id = null)
{
    if ($content_id) {
        $data = OtherContent::findOrFail($content_id);

        $this->content_id = $data->id;
        $this->title = $data->title;
        $this->content = $data->content;

        // 🔥 IMPORTANT FIX
        $this->status = (bool) $data->status; // 0/1 → true/false
    }
}


    public function save()
    {
        $this->validate();

        OtherContent::updateOrCreate(
            ['id' => $this->content_id],
            [
                'title'   => $this->title,
                'content' => $this->content,
                'status'  => $this->status,
            ]
        );

        session()->flash('success', 'Content saved successfully');

        return redirect()->route('admin::other-content:list');
    }

    public function render()
    {
        return view('livewire.admin.other-content.other-content-add-edit');
    }
}
