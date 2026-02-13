<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Title  -->
    <title>@yield('head_title', config('settings.site_title'))</title>

    <meta name="title" content="@yield('head_title', config('settings.meta_title'))" />
    <meta name="description" content="@yield('head_description', config('settings.meta_description'))" />
    <meta name="author" content="@yield('head_author', '')">
    <meta name="keywords" content="@yield('head_keywords', config('settings.meta_tags'))" />
    <link rel="canonical" href="@yield('head_conical_url', request()->url())">

    <meta property="og:type" content="@yield('head_type', 'article')" />
    <meta property="og:title" content="@yield('head_title', config('settings.meta_title'))" />
    <meta property="og:description" content="@yield('head_description', config('settings.meta_description'))" />
    <meta property="og:image" content="@yield('head_image', asset(config('settings.meta_os_image')))" />
    <meta property="og:url" content="@yield('head_url', request()->url())" />
    <meta property="og:image:width" content="1024" />
    <meta property="og:image:height" content="1024" />
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="@yield('head_image', asset(config('settings.meta_os_image')))">
    <link rel="image_src" href="@yield('head_image', asset(config('settings.meta_os_image')))">

    <link rel="icon" href="{{ asset(config('settings.site_favicon')) }}" type="image/x-icon" />
    <link rel="apple-touch-icon" href="{{ asset(config('settings.site_favicon')) }}" />
    <link rel="shortcut icon" href="{{ asset(config('settings.site_favicon')) }}" type="image/x-icon" />

    <!-- CSS ============================================ -->

    <link rel="stylesheet" href="/assets/frontend/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/assets/frontend/css/font-awesome.min.css" />
    <link rel="stylesheet" href="/assets/frontend/css/Pe-icon-7-stroke.css" />
    <link rel="stylesheet" href="/assets/frontend/css/animate.min.css">
    <link rel="stylesheet" href="/assets/frontend/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/frontend/css/nice-select.css">
    <link rel="stylesheet" href="/assets/frontend/css/magnific-popup.min.css" />
    <link rel="stylesheet" href="/assets/frontend/css/ion.rangeSlider.min.css" />
    <!-- Fancybox (for image preview popup) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />


    <!-- Style CSS -->
    <link rel="stylesheet" href="/assets/frontend/css/style.css">

    <link rel="stylesheet" href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css') }}"
        integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @stack('head')

    <style>
        .qty-btn {
            width: 35px;
            height: 35px;
            border: 0;
            font-size: 12px;
            font-weight: normal;
        }

        .qty-input {
            text-align: center;
            width: 45px;
            height: 35px;
            border: 1px solid #dee2e6 !important;
        }
    </style>


    <style>
        .floating-whatsapp {
            position: fixed;
            bottom: 80px;
            right: 30px;
            z-index: 99;
            cursor: pointer;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        /* WhatsApp Icon */
        .wts-icon {
            width: 60px;
            height: 60px;
            transition: transform 0.3s ease;
        }

        /* Hover effect on icon */
        .floating-whatsapp:hover .wts-icon {
            transform: scale(1.1);
        }

        /* Hidden label initially */
        .whatsapp-label {
            background-color: #25D366;
            color: white;
            font-weight: bold;
            padding: 8px 12px;
            border-radius: 8px 0 0 8px;
            margin-right: 10px;
            opacity: 0;
            white-space: nowrap;
            transform: translateX(10px);
            transition: all 0.3s ease;
            font-family: sans-serif;
            font-size: 14px;
        }

        /* Show label on hover (desktop only) */
        @media (hover: hover) {
            .floating-whatsapp:hover .whatsapp-label {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Responsive adjustments */
        @media screen and (max-width: 768px) {
            .wts-icon {
                width: 48px;
                height: 48px;
            }

            .whatsapp-label {
                font-size: 12px;
                padding: 6px 10px;
            }
        }

        @media screen and (max-width: 480px) {
            .wts-icon {
                width: 44px;
                height: 44px;
            }

            .whatsapp-label {
                display: none;
                /* Hide label on very small screens */
            }
        }
    </style>

    <style>
        /* Common Styles for Floating Buttons */
        .fl-fl {
            position: fixed;
            right: -140px;
            width: 190px;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 15px;
            padding: 5px;
            display: flex;
            align-items: center;
            transition: right 0.3s ease;
            z-index: 1000;
            border-radius: 5px 0 0 5px;
        }

        .fl-fl:hover {
            right: 0;
        }

        .fl-fl i,
        .fl-fl a:hover {
            color: black;
        }


        .fl-ph i,
        .fl-ph a:hover {
            color: #a88601;
        }

        .fl-fl i {
            width: 40px;
            text-align: center;
            font-size: 20px;
            padding: 10px 0;
            color: white;
        }

        .fl-fl a {
            color: white;
            text-decoration: none;
            flex: 1;
            line-height: 40px;
            padding-left: 10px;
        }

        /* Specific Background Colors */
        .float-wtsp {
            background: #25D366;
            /* WhatsApp Green */
            top: 350px;
        }

        .float-ph {
            background: #414b3b;
            /* Phone Dark Green */
            top: 405px;
        }
    </style>


</head>

<body>


    <div class="preloader-activate preloader-active open_tm_preloader">
        <div class="preloader-area-wrap">
            <div class="spinner d-flex justify-content-center align-items-center h-100">
                <div class="bounce1"></div>
                <div class="bounce2"></div>
                <div class="bounce3"></div>
            </div>
        </div>
    </div>

    <div class="main-wrapper">
       
        @yield('content', $slot ?? '')
       
        <!-- Begin Scroll To Top -->
        <a class="scroll-to-top" href="">
            <i class="fa fa-angle-double-up"></i>
        </a>
        <!-- Scroll To Top End Here -->

        <!-- whatsapp icon  -->
        {{-- <a href="https://api.whatsapp.com/send?phone={{ config('settings.company_phone') }}&text=Hi%2C%20I%20have%20a%20question%20about%20your%20services.&type=phone_number&app_absent=0"
            class="floating-whatsapp" target="_blank">
            <span class="whatsapp-label">Chat with us</span>
            <img src="/assets/frontend/images/others/wts.png" alt="WhatsApp" class="wts-icon">
        </a> --}}

        <!-- Floating Social Media Bar -->
        <div class="float-sm">
            <div class="fl-fl float-wtsp">
                <i class="fa fa-whatsapp"></i>
                <a
                    href="https://api.whatsapp.com/send?phone={{ config('settings.company_wtsp_no') }}&text=Hi%2C%20I%20have%20a%20question%20about%20your%20services.&type=phone_number&app_absent=0"">Chat
                    With Us</a>
            </div>
            <div class="fl-fl fl-ph float-ph">
                <i class="fa fa-phone"></i>
                <a href="tel:{{ config('settings.company_phone') }}">Call Us</a>
            </div>
        </div>


    </div>

    @stack('scripts')

    @if (config('settings.prevent_clicks'))
        <script>
            /* For Prevention of clicks */
            document.addEventListener("contextmenu", (event) => {
                event.preventDefault();
            });
        </script>
    @endif

    @if (!isset($disableCart))
       
    @endif

    <!-- Global Vendor, plugins JS -->

    <!-- JS Files
============================================ -->

    <script src="/assets/frontend/js/vendor/bootstrap.bundle.min.js"></script>
    <script src="/assets/frontend/js/vendor/jquery-3.6.0.min.js"></script>
    <script src="/assets/frontend/js/vendor/jquery-migrate-3.3.2.min.js"></script>
    <script src="/assets/frontend/js/vendor/jquery.waypoints.js"></script>
    <script src="/assets/frontend/js/vendor/modernizr-3.11.2.min.js"></script>
    <script src="/assets/frontend/js/plugins/wow.min.js"></script>
    <script src="/assets/frontend/js/plugins/swiper-bundle.min.js"></script>
    <script src="/assets/frontend/js/plugins/jquery.nice-select.js"></script>
    <script src="/assets/frontend/js/plugins/parallax.min.js"></script>
    <script src="/assets/frontend/js/plugins/jquery.magnific-popup.min.js"></script>
    <script src="/assets/frontend/js/plugins/tippy.min.js"></script>
    <script src="/assets/frontend/js/plugins/ion.rangeSlider.min.js"></script>
    <script src="/assets/frontend/js/plugins/mailchimp-ajax.js"></script>
    <script src="/assets/frontend/js/plugins/jquery.counterup.js"></script>

    <!--Main JS (Common Activation Codes)-->
    <script src="{{ asset('assets/frontend/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js') }}"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-bottom-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            "title": "{{ config('settings.site_name') }}"
        };
        @if (Session::has('message'))
            toastr.success("{!! Session::get('message') !!}")
        @endif

        @if (Session::has('error'))
            toastr.error("{!! Session::get('error') !!}")
        @endif

        @foreach ($errors->all() as $error)
            toastr.error("{!! $error !!}")
        @endforeach

        window.addEventListener('SetMessage', ({
            detail: {
                type,
                title,
                message,
                close
            }
        }) => {
            switch (type) {
                case 'success':
                    toastr.success(message, title)
                    break;
                case 'info':
                    toastr.info(message, title)
                    break;
                default:
                    toastr.error(message, title)
                    break;
            }
            if (typeof close != 'undefined') {
                $('.modal').modal('hide');
            }
        })


        /* For Prevention of clicks */
        @if (config('settings.prevent_clicks', false))
            document.addEventListener("contextmenu", (event) => {
                event.preventDefault();
            });
        @endif
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper('.product-slider', {
                loop: true,
                autoplay: {
                    delay: 3500,
                    disableOnInteraction: false,
                },
                slidesPerView: 1, // Default for smallest screens
                spaceBetween: 20,
                breakpoints: {
                    640: {
                        slidesPerView: 1
                    }, // Mobile (up to 640px)
                    768: {
                        slidesPerView: 2
                    }, // Tablets
                    1024: {
                        slidesPerView: 4
                    }, // Small desktops
                    1280: {
                        slidesPerView: 4
                    }, // Large desktops
                },
            });
        });
    </script>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,hi,pa',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
            }, 'google_translate_element');
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const footer = document.querySelector(".footer-area");
            if (!footer) return;

            const desktopImage = footer.getAttribute("data-bg-image-desktop");
            const mobileImage = footer.getAttribute("data-bg-image-mobile");

            function updateFooterBg() {
                if (window.innerWidth <= 768) {
                    footer.style.backgroundImage = `url('${mobileImage}')`;
                } else {
                    footer.style.backgroundImage = `url('${desktopImage}')`;
                }
                footer.style.backgroundSize = "cover";
                footer.style.backgroundPosition = "center";
                footer.style.backgroundRepeat = "no-repeat";
            }

            updateFooterBg(); // initial load
            window.addEventListener("resize", updateFooterBg);
        });
    </script>


    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
    </script>

</body>

</html>
