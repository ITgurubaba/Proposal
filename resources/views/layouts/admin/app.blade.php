<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
@livewireStyles

    {{ Vite::useBuildDirectory('build/admin') }}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" href="{{asset(config('settings.site_favicon'))}}" type="image/x-icon"/>
    <link rel="apple-touch-icon" href="{{asset(config('settings.site_favicon'))}}"/>
    <link rel="shortcut icon" href="{{asset(config('settings.site_favicon'))}}" type="image/x-icon"/>

    {{--    <!-- Fonts -->--}}
    {{--    <link rel="preconnect" href="https://fonts.bunny.net">--}}
    {{--    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />--}}

    <!-- Scripts -->
    @vite(['resources/js/admin/app.js'])
    @stack('head')

    <link rel="stylesheet" href="{{ asset('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css') }}">
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/flatpickr') }}"></script>

    <link href="{{ asset('https://cdn.jsdelivr.net/npm/vanilla-calendar-pro@2.9.6/build/vanilla-calendar.min.css') }}" rel="stylesheet">
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/vanilla-calendar-pro@2.9.6/build/vanilla-calendar.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/photoswipe@5.4.3/dist/umd/photoswipe.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/photoswipe@5.4.3/dist/umd/photoswipe-lightbox.umd.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/photoswipe@5.4.3/dist/photoswipe.min.css" rel="stylesheet">

    <link href="{{asset('assets/filepond/dist/filepond.css')}}" rel="stylesheet" />

    {{-- Cropper.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />

    {{-- Sortable.js --}}
   <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.1/Sortable.min.js"></script>

</head>
<body class="font-sans antialiased customScrollbar max-w-[1920px] mx-auto" >

{{-- The navbar with `sticky` and `full-width` --}}

{{-- The main content with `full-width` --}}
<x-mary-main full-width >

    <x-admin.theme.sidebar />
    <x-slot:content class="flex flex-col relative">
        <x-admin.theme.navbar />
        <div class="flex-grow py-5">
            @yield('content', $slot ??'')
        </div>
        <x-admin.theme.footer />
    </x-slot:content>
</x-mary-main>



<x-mary-toast position="toast-bottom toast-end" />
<x-mary-spotlight />

<!--For Filepond -->
<script src="{{asset('assets/filepond/dist/filepond.js')}}" data-navigate-once></script>
<script src="{{asset('assets/filepond/dist/filepond-plugin-file-validate-type.js')}}" data-navigate-once></script>
<!--For Filepond Ends-->

<script src="{{ asset('assets/ckeditor4/ckeditor.js') }}" data-navigate-once />


@stack('scripts')

<livewire:admin.components.notification-drawer />

<script data-navigate-once>
    window.addEventListener('SweetMessage',({detail:{type,title,message,url = false}})=>{
        switch (type) {
            case 'success':
                if(url)
                {
                    Swal.fire({
                        title:title,
                        text: message,
                        icon: "success",
                        buttonsStyling: false,
                        showCancelButton: true,
                        cancelButtonText:'Back To List',
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: 'btn btn-primary me-3',
                            cancelButton: 'btn btn-label-secondary'
                        }
                    }).then((event) => {
                        if(!event.isConfirmed){
                            Livewire.navigate(url)
                        }

                    });
                }
                else{
                    Swal.fire({
                        title:title,
                        text: message,
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
                break;
            default:
                Swal.fire({
                    title:title,
                    text:message,
                    icon:'error'
                });
                break;
        }
    })
    window.addEventListener('OpenUrlNewTab',({detail:{url}})=>{
        window.open(url,'_blank');
    })

    function dispatchEventCall(name = ''){
        const event = new CustomEvent(name);
        window.dispatchEvent(event);
    }
</script>
@livewireScripts
</body>
</html>
