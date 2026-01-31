<div
    x-data="{
        model: @entangle($attributes->wire('model')),
        uploadFile:FilePond.create($refs.input),
        FileID:null
    }"
    x-init="() => {
        uploadFile.setOptions({
            instantUpload: true,
            allowFileTypeValidation:'{!! $attributes->has('allowFileTypeValidation') || $attributes->has('accept') !!}',
            server: {
                    headers:{ 'X-CSRF-TOKEN':'{!! csrf_token() !!}', 'folder':'{!! $attributes->has('folder')?$attributes->get('folder'):'images/' !!}'},
                    process:{
                        url : '{{ $attributes->has('data-upload-url')?$attributes->get('data-upload-url'):'/api/admin/upload' }}',
                        method:'POST',
                        onload:(response) => {

                            console.log(response);

                            const responseData = JSON.parse(response);
                            if(responseData.success){ model = responseData.data.url; FileID = responseData.data.id;  }
                            else{ console.log(responseData.message) }
                        },
                        onerror: (response) => {  const responseData = JSON.parse(response); if(!responseData.success){  console.log(responseData.message)  }  },
                    } ,
                    revert: (filename, load) => {
                            try{ removeFileByID(FileID); model = null; }
                            catch(e){ console.log(e.message); }
                            finally{ load(); }
                     },
            },
        });
        this.addEventListener('{{$attributes->has('data-remove')?$attributes->get('data-remove'):'removeUploadedFile'}}', e => {
            uploadFile.removeFiles();
        });
    }
    "
    wire:ignore
>
    <input type="file"
           x-ref="input"
           class="filepond"
           name="pic"
           @if($attributes->has('accept')) accept="{{$attributes->get('accept') ??''}}" @endif
    />
</div>

@once
    @assets
        <link href="{{asset('assets/filepond/dist/filepond.css')}}" rel="stylesheet" />
        <!--For Filepond -->
        <script src="{{asset('assets/filepond/dist/filepond.js')}}"></script>
        <script src="{{asset('assets/filepond/dist/filepond-plugin-file-validate-type.js')}}"></script>
        <!--For Filepond Ends-->
    @endassets

    @script
        <script>
            FilePond.registerPlugin(FilePondPluginFileValidateType);
            function removeFileByID(fileId = null)
            {
                if(fileId!==null)
                {
                    $.ajax({
                        method:'POST',
                        url:'/api/admin/revert',
                        data:{ 'file_id':fileId,'_token':$('meta[name="csrf-token"]').attr('content') },
                        dataType:'JSON',
                        success: function(response){ console.log(response)  }
                    });
                }
            }
        </script>
    @endscript
@endonce
