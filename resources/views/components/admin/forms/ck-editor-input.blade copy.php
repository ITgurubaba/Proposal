<div
    x-data="{
        model: @entangle($attributes->wire('model')),
        myEditor: null,
        route_prefix: '/admin/file-manager',
    }"
    x-init="
        CKEDITOR.plugins.add('metatags', {
            requires: 'richcombo',
            init: function(editor) {
                editor.ui.addRichCombo('MetaTags', {
                    label: 'Insert Tag',
                    title: 'Insert Tag',
                    toolbar: 'insert',

                    panel: {
                        css: [ CKEDITOR.skin.getPath('editor') ].concat(editor.config.contentsCss),
                        multiSelect: false
                    },

                    init: function () {
                        this.add('company_name', 'Company Name', 'Company Name');
                        this.add('company_registration_number', 'Company Registration Number', 'Company Registration Number');
                        this.add('client_name', 'Client Name', 'Client Name');
                        this.add('client_email', 'Client Email', 'Client Email');
                        this.add('client_phone', 'Client Phone', 'Client Phone');
                        this.add('company_address', 'Company Address', 'Company Address');
                    },

                    onClick: function (value) {
                        editor.insertText('[' + value + ']');
                    }
                });
            }
        });

        myEditor = CKEDITOR.replace($refs.input,{
            removePlugins: ['exportpdf,autosave','save'],
            extraPlugins: 'richcombo,metatags',

            toolbar: [
                { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline' ] },
                { name: 'paragraph',   items: [
                    'NumberedList','BulletedList',
                    'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'
                ] },
                { name: 'colors',      items: [ 'TextColor', 'BGColor' ] },
                { name: 'links',       items: [ 'Link', 'Unlink' ] },
                { name: 'insert',      items: [ 'MetaTags' ] }, // 👈 DROPDOWN
                { name: 'undo',        items: [ 'Undo', 'Redo' ] }
            ],

            height: {{ $attributes->has('data-height') ? $attributes->get('data-height') : 200 }},

            filebrowserImageBrowseUrl: route_prefix + '?type=Images',
            filebrowserImageUploadUrl: route_prefix + '/upload?type=Images&_token={{ csrf_token() }}',
            filebrowserBrowseUrl: route_prefix + '?type=Files',
            filebrowserUploadUrl: route_prefix + '/upload?type=Files&_token={{ csrf_token() }}',

            autosave: { autoLoad: false, messageType: 'no' },
        });

        // Livewire sync
        myEditor.on('change', function () {
            model = myEditor.getData();
        });

        @if($attributes->has('data-update'))
        window.addEventListener('{{ $attributes->get('data-update') }}', ({ detail: { content } }) => {
            myEditor.setData(content || '');
        });
        @endif
    "
    wire:ignore
>
    <textarea x-ref="input" {{ $attributes->merge(['class' => 'form-control']) }}></textarea>
</div>
