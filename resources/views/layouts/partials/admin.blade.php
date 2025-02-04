<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />

    <!-- Core Css -->
    <link rel="stylesheet" href="../assets/css/styles.css" />
    {{-- <link rel="stylesheet" href="../assets/css/test.css" /> --}}
    <!-- Owl Carousel  -->
    <link rel="stylesheet" href="../assets/libs/owl.carousel/dist/assets/owl.carousel.min.css" />

    <script src="{{ asset('plugins/fontawesome/font.min.js') }}"></script>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/jqueryvalidation/jquery.validate.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap5.min.css') }}">
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap5.min.js') }}"></script>


    {{-- <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/jqueryvalidation/jquery.validate.min.js') }}"></script> --}}

    <!-- jQuery (required for validation plugin) -->
    {{-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> --}}

    <!-- jQuery Validation Plugin -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script> --}}


    {{-- <link rel="stylesheet" href="../assets/libs/datatables/dataTables.bootstrap5.min.css" /> --}}
    

    {{-- SWEET ALERT --}}
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.css') }}">
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.js') }}"></script>

    {{-- CDN for intel input --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>


    @vite([
        'resources/js/changepassword.js',
    ])
</head>

<body>
    {{-- <div class="toast toast-onload align-items-center text-bg-primary border-0" role="alert" aria-live="assertive"
        aria-atomic="true">
        <div class="toast-body hstack align-items-start gap-6">
            <i class="ti ti-alert-circle fs-6"></i>
            <div>
                <h5 class="text-white fs-3 mb-1">Welcome to Modernize</h5>
                <h6 class="text-white fs-2 mb-0">Easy to costomize the Template!!!</h6>
            </div>
            <button type="button" class="btn-close btn-close-white fs-2 m-0 ms-auto shadow-none"
                data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div> --}}
    <!-- Preloader -->
    <div class="preloader">
        <img src="../assets/images/logos/favicon.png" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <div id="main-wrapper">
        @include('layouts.partials.sidebar')
        <div class="page-wrapper">
            @include('layouts.partials.header')
            @include('layouts.partials.harizontalsidebar')
            @include('layouts.partials.settings')
            <div class="body-wrapper">
                @yield('content')
            </div>
        </div>
        @include('layouts.partials.searchbar')
    </div>
    <div class="dark-transparent sidebartoggler"></div>
    <script>
        function handleColorTheme(e) {
            document.documentElement.setAttribute("data-color-theme", e);
        }
    </script>
    {{-- <script src="../assets/js/vendor.min.js"></script> --}}
    <!-- Import Js Files -->
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/libs/simplebar/dist/simplebar.min.js"></script>
    <script src="../assets/js/theme/app.init.js"></script>
    <script src="../assets/js/theme/theme.js"></script>
    <script src="../assets/js/theme/app.min.js"></script>
    <script src="../assets/js/theme/sidebarmenu.js"></script>

    <!-- solar icons -->
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <script src="../assets/libs/owl.carousel/dist/owl.carousel.min.js"></script>

    {{-- <script src="../assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="../assets/js/datatables/datatable-basic.init.js"></script> --}}



</body>

</html>
