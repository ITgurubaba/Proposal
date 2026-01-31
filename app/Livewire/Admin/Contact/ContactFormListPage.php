<?php

namespace App\Livewire\Admin\Contact;

use App\Helpers\Traits\WithMaryTable;
use App\Models\ContactMail;
use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class ContactFormListPage extends Component
{
    use WithPagination, WithMaryTable, Toast;

    public $request = [];
    public array $statuses = [];
    public $editModal = false;
    protected $validationAttributes = [
        'request.first_name'=>'first name',
        'request.last_name'=>'last name',
        'request.email'=>'email',
        'request.phone'=>'phone',
        'request.subject'=>'subject',
        'request.message'=>'message',
    ];

    protected $rules = [
        'request.first_name'=>'required|max:255',
        'request.last_name'=>'max:255',
        'request.email'=>'max:255',
        'request.phone'=>'max:255',
        'request.subject'=>'required|max:500',
        'request.message'=>'required|max:5000',
    ];

    public function mount()
    {
        $this->statuses = ContactMail::STATUS;
        $this->headers = [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1','sortable' => true],
            ['key' => 'first_name', 'label' => 'Name','sortable' => false],
            ['key' => 'email', 'label' => 'Email','sortable' => false],
            ['key' => 'phone', 'label' => 'Phone','sortable' => false],
            ['key' => 'subject', 'label' => 'Subject','sortable' => false],
            ['key' => 'created_at', 'label' => 'Date','sortable' => true],
            ['key' => 'status', 'label' => 'Status','sortable' => true],
        ];
        $this->NewRequest();
    }
   public function updateStatus($id, $newStatus)
    {
        $contact = ContactMail::find($id);

        if ($contact && in_array((int)$newStatus, array_keys(ContactMail::STATUS))) {
            $contact->status = (int)$newStatus;
            $contact->save();

            $this->success('Contact Status', 'Updated to ' . ContactMail::STATUS[$newStatus]);
        } else {
            $this->error('Contact Failed', 'Invalid contact or status.');
        }
    }


    public function render()
    {
        $data = ContactMail::query();

        if (checkData($this->search))
        {
            $data->where(function ($q) {
                $q->orWhere('id', 'like', $this->search)
                    ->orWhere('first_name', 'like', "{$this->search}%")
                    ->orWhere('last_name', 'like', "%{$this->search}%");
            });
        }

        $data = $data->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage);

        return view('livewire.admin.contact.contact-form-list-page',compact('data'));
    }

    public function OpenAddEditModal($id = null):void
    {
        if (checkData($id))
        {
            $model = ContactMail::find($id);
            if ($model)
            {
                $this->EditRequest($model);
                $this->editModal = true;
            }
        }
        else
        {
            $this->NewRequest();
            $this->editModal = true;
        }
    }

    public function save():void
    {
        $this->validate($this->rules);

        if (Arr::has($this->request,'id'))
        {
            $message = "Updated successfully";
            $check = ContactMail::find($this->request['id']);
        }
        else
        {
            $message = "Created successfully";
            $check = new ContactMail();
        }

        $check->fill(Arr::except($this->request,'id'));

        $check->save();

        $this->editModal = false;

        $this->success('Contact Message',$message);

    }


    protected function EditRequest($contact): void
    {
        $this->request = $contact->only([
            'id',
            'first_name',
            'last_name',
            'email',
            'phone',
            'subject',
            'message',
            'status',
        ]);
    }

    // protected function NewRequest():void
    // {
    //     $this->request = [
    //         'first_name'=>null,
    //         'last_name'=>null,
    //         'email'=>null,
    //         'phone'=>null,
    //         'subject'=>null,
    //         'message'=>null,
    //         'status'=>0,
    //     ];
    // }
    protected function NewRequest(): void
    {
        $this->request = [
            'first_name' => null,
            'last_name' => null,
            'email' => null,
            'phone' => null,
            'subject' => null,
            'message' => null,
            'status' => ContactMail::STATUS_UNSEEN,
        ];
    }


    public function destroy($id = null):void
    {
        $check = ContactMail::find($id);

        if ($check)
        {
            $check->delete();
            $this->success('Delete Post','Deleted successfully');
        }
    }
}
