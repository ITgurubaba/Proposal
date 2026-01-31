<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class TeamListPage extends Component
{
    use WithPagination,Toast;

    public $search;

    public $headers = [];

    public $sortBy = [];
    public $perPage = 10;

    public User $adminUser;

    public function mount():void
    {
        $this->resetFilter();
        $this->headers = [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1','sortable' => true],
            ['key' => 'image', 'label' => 'Avatar','sortable' => false],
            ['key' => 'user_type', 'label' => 'Role','sortable' => true],
            ['key' => 'name', 'label' => 'Name','sortable' => true],
            ['key' => 'email', 'label' => 'Email','sortable' => true],
            ['key' => 'status', 'label' => 'Status','sortable' => true],
            ['key' => 'online', 'label' => 'Online','sortable' => false],
        ];
        $this->adminUser = Auth::user();
    }

    public function render()
    {
        $data = User::query();

        $data->whereIn('user_type',['admin','sub_admin']);

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

        return view('livewire.admin.users.team-list-page',compact('data'));
    }

    private function resetFilter():void
    {
        $this->sortBy = [
            'column'=>'id',
            'direction'=>'desc',
        ];
    }

    public function destroy($id = null): void
    {
        $check = User::where([
            'id'=>$id,
        ])->where('id','!=',$this->adminUser->id)->first();

        if ($check)
        {
            $check->forceDelete();
            $this->success('Delete User','Deleted successfully');
        }
    }

}
