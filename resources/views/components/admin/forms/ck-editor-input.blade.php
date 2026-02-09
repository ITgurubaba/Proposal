<div x-data="{
    model: @entangle($attributes->wire('model')),
    myEditor: null,
    insertTag(tag) {
        if (this.myEditor) {
            this.myEditor.focus();
            this.myEditor.insertText('[' + tag + ']');
        }
    }
}" x-init="myEditor = CKEDITOR.replace($refs.input, {
    removePlugins: ['exportpdf', 'autosave', 'save', 'image', 'uploadimage', 'filebrowser'],
    toolbar: [
        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline'] },
        {
            name: 'paragraph',
            items: [
                'NumberedList', 'BulletedList',
                'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'
            ]
        },
        { name: 'undo', items: ['Undo', 'Redo'] }
    ],
    height: {{ $attributes->has('data-height') ? $attributes->get('data-height') : 120 }},
});
window.activeCkEditor = myEditor;
myEditor.on('change', function() {
    model = myEditor.getData();
});

@if($attributes->has('data-update'))
window.addEventListener('{{ $attributes->get('data-update') }}', ({ detail: { content } }) => {
    myEditor.setData(content || '');
});
@endif" wire:ignore>

    <!-- ✅ INSERT TAG DROPDOWN (VIEW SE) -->
    @if (!$attributes->has('hide-tags'))
        <div class="mb-2 flex gap-2 items-center">
            <label class="text-sm font-medium">Insert Tag:</label>
            <select class="form-select form-select-sm"
                @change="insertTag($event.target.value); $event.target.selectedIndex = 0;">
                <option value="">-- Select Tag --</option>
                <option value="company_name">Company Name</option>
                <option value="company_registration_number">Company Registration Number</option>
                <option value="client_name">Client Name</option>
                <option value="client_email">Client Email</option>
                <option value="client_phone">Client Phone</option>
                <option value="company_address">Company Address</option>
            </select>
        </div>
    @endif


    <!-- CKEditor -->
    <textarea x-ref="input" {{ $attributes->merge(['class' => 'form-control']) }}></textarea>
</div>
