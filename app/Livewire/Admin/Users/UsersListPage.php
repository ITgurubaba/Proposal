<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use function Livewire\of;

class UsersListPage extends Component
{
    use WithPagination,Toast;

    public $search;

    public $headers = [];

    public $sortBy = [];
    public $perPage = 10;

    public function mount():void
    {
        $this->resetFilter();
        $this->headers = [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1','sortable' => true],
            ['key' => 'image', 'label' => 'Avatar','sortable' => false],
            ['key' => 'name', 'label' => 'Name','sortable' => true],
            ['key' => 'email', 'label' => 'Email','sortable' => true],
            ['key' => 'phone', 'label' => 'Phone','sortable' => true],
            ['key' => 'status', 'label' => 'Status','sortable' => true],
            ['key' => 'online', 'label' => 'Online','sortable' => false],
        ];
    }

    public function render()
    {
        $data = User::query();

        $data->where('user_type','user');

        if (checkData($this->search))
        {
            $data->where(function ($q) {
                $q->orWhere('id', 'like', $this->search)
                    ->orWhere('name', 'like', "{$this->search}%")
                    ->orWhere('name', 'like', "%{$this->search}%");
            });
        }

       $data = $data->orderBy(...array_values($this->sortBy))
           ->paginate($this->perPage);

        return view('livewire.admin.users.users-list-page',compact('data'));
    }

    private function resetFilter():void
    {
        $this->sortBy = [
            'column'=>'id',
            'direction'=>'desc',
        ];
    }

    public function destroy($id = null)
    {
        $check = User::where([
            'id'=>$id,
            'user_type'=>'user',
        ])->first();

        if ($check)
        {
             $check->forceDelete();
             $this->success('Delete User','Deleted successfully');
        }
    }

}
