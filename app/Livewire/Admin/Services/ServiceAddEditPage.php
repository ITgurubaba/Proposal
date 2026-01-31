<?php

namespace App\Livewire\Admin\Services;

use App\Helpers\Admin\BackendHelper;
use App\Helpers\Admin\SlugHelper;
use App\Models\Service;
use App\Rules\SlugValidate;
use Illuminate\Support\Str;
use Livewire\Component;
use Mary\Traits\Toast;

class ServiceAddEditPage extends Component
{
    use Toast;

    public $service_id;
    public array $request = [], $metaRequest = [], $postCategoryRequest = [];
    public mixed $postTagRequest;

    public bool $editSlug;
    public mixed $slug = "";

    protected array $validationAttributes = [
        'request.name'=>'name',
        'request.slug'=>'slug',
        'request.description'=>'description',
        'request.status'=>'status',

        'metaRequest.title'=>'title',
        'metaRequest.description'=>'description',
    ];

    protected function rules():array
    {
        return [
            'request.name'=>'required|max:255',
            'request.description'=>'max:500',
            'request.slug'=>['required','max:255',new SlugValidate(['model_id'=>$this->service_id ??null,'model_class'=>Service::class])],

            'metaRequest.title'=>'max:110',
            'metaRequest.description'=>'max:160',

            'request.status'=>'required',
        ];
    }

    public function mount()
    {
        if (checkData($this->service_id))
        {
            $check = Service::find($this->service_id);
            if ($check)
            {
                $this->EditRequest($check);
            }
            else{ redirect()->route('admin::services:list')->with('error','Invalid Service'); }
        }
        else{ $this->NewRequest(); }
    }

    public function render()
    {
        return view('livewire.admin.services.service-add-edit-page');
    }

    public function Save():void
    {
        $this->validate($this->rules());
        $this->update($this->request);
    }

    protected function update($data = []):void
    {
        try
        {
            $check = Service::find($this->service_id);
            $check->fill($data);
            $check->save();

            $this->metaRequest['model_id'] = $check->id;

            SlugHelper::createOrUpdate(
                $data['slug'],
                $data['name'],
                $check->id,
                Service::class
            );
            BackendHelper::createOrUpdateMetaData($this->metaRequest);

            $this->dispatch('SweetMessage',
                type:'success',
                title:'Edit Service',
                message:'Updated successfully',
                url:route('admin::services:list')
            );
        }
        catch (\Exception $exception)
        {
            $this->error('Error!',$exception->getMessage());
        }
    }

    public function Submit():void
    {
        $this->validate($this->rules());
        $this->create($this->request);
    }

    protected function create($data = []):void
    {
        try
        {
            $check = Service::create($data);

            $this->service_id = $this->metaRequest['model_id'] = $check->id;

            SlugHelper::createOrUpdate(
                $data['slug'],
                $data['name'],
                $check->id,
                Service::class
            );

            BackendHelper::createOrUpdateMetaData($this->metaRequest);

            $this->dispatch('SweetMessage',
                type:'success',
                title:'New Service',
                message:'Created successfully',
                url:route('admin::services:list')
            );
        }
        catch (\Exception $exception)
        {
            $this->error('Error!',$exception->getMessage());
        }
    }

    protected function NewRequest():void
    {
        $this->metaRequest = BackendHelper::getMetaData(null,Service::class);
        $this->request = [
            'name'=>null,
            'slug'=>null,
            'description'=>null,
            'content'=>null,
            'image'=>null,
            'logo'=>null,
            'position'=>0,
            'status'=>0,
        ];
    }

    protected function EditRequest($check):void
    {
        $this->metaRequest = BackendHelper::getMetaData($check,Service::class);
        $this->request = $check->only([
            'name',
            'description',
            'content',
            'image',
            'logo',
            'position',
            'status',
        ]);
        $this->request['slug'] = $check->getSlugAttribute();
    }

    public function generateSlug():void
    {
        try
        {
            $this->slug = $this->request['slug'] = Str::slug($this->request['name']);
        }
        catch (\Exception $exception)
        {
            $this->slug = $this->request['slug'] = "";
            $this->error('Error!',$exception->getMessage());
        }
    }

    public function EditSlug($edit = true):void
    {
        $this->editSlug = $edit;
        if ($edit)
        {
            $this->slug = $this->request['slug'];
        }
    }

    public function SaveSlug():void
    {
        $this->validate([
            'slug'=>'required|string|max:255',
        ]);
        $this->request['slug'] = $this->slug;
        $this->editSlug = false;
    }
}
