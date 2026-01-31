<div
    x-data="{
        model: @entangle($attributes->wire('model')),
        myEditor:null,
        route_prefix:'/admin/file-manager',
    }"
    x-init="
           myEditor =  CKEDITOR.replace($refs.input,{
               removePlugins: ['exportpdf,autosave','save'],
               height: {{$attributes->has('data-height')?$attributes->get('data-height'):400}},
               filebrowserImageBrowseUrl: route_prefix + '?type=Images',
               filebrowserImageUploadUrl: route_prefix + '/upload?type=Images&_token={{csrf_token()}}',
               filebrowserBrowseUrl: route_prefix + '?type=Files',
               filebrowserUploadUrl: route_prefix + '/upload?type=Files&_token={{csrf_token()}}',
               autosave: { autoLoad: false,messageType : 'no'},
            });
            myEditor.on('change',function (){ model = myEditor.getData(); })
            @if($attributes->has('data-update'))
             window.addEventListener('{{ $attributes->get('data-update') }}',({detail:{content}})=>{
                myEditor.setData(data);
            })
            @endif
    "
    wire:ignore
>
    <textarea x-ref="input" {{ $attributes->merge(['class' => 'form-control']) }}></textarea>
</div>
