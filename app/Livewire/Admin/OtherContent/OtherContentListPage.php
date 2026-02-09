<?php
namespace App\Livewire\Admin\OtherContent;

use Livewire\Component;
use App\Models\OtherContent;

class OtherContentListPage extends Component
{
    public function delete($id)
    {
        OtherContent::findOrFail($id)->delete();
        session()->flash('success','Content Deleted');
    }


    public $search = '';

public function getContentsProperty()
{
    return \App\Models\OtherContent::where('title','like','%'.$this->search.'%')
        ->latest()
        ->get();
}

public function render()
{
    return view('livewire.admin.other-content.other-content-list',[
        'contents' => $this->contents,
        'headers' => [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'title', 'label' => 'Title'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'created_at', 'label' => 'Created'],
        ],
    ]);
}

  
}
