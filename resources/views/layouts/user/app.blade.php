<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title') &mdash; {{ $app->name_app }}</title>

    <link rel="icon" href="{{ asset('file/setting/' . $app->logo) }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('file/setting/' . $app->logo) }}" type="image/x-icon">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('stisla/library/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @stack('style')

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('stisla/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('stisla/css/components.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Start GA -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-94034622-3');
    </script>
    <!-- END GA -->
</head>
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <!-- Header -->
            @include('layouts.user.header')

            <!-- Sidebar -->
            @include('layouts.user.sidebar')

            <!-- Content -->
            @yield('main')

            <!-- Footer -->
            @include('layouts.user.footer')
        </div>
    </div>

    <!-- General JS Scripts -->
    @include('sweetalert::alert')
    <script src="{{ asset('stisla/library/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('stisla/library/popper.js/dist/umd/popper.js') }}"></script>
    <script src="{{ asset('stisla/library/tooltip.js/dist/umd/tooltip.js') }}"></script>
    <script src="{{ asset('stisla/library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('stisla/library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('stisla/library/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('stisla/js/stisla.js') }}"></script>
    <script>
        $(document).ready(function() {
            window.addEventListener('beforeunload', function(event) {
                $.ajax({
                    url: 'logoutOnTabClose',
                    method: 'GET',
                    data: {},
                    success: function(response) {
                        // Handle the success response if needed
                    },
                    error: function(xhr, status, error) {
                        // Handle the error if needed
                    }
                });
            });

            window.addEventListener('unload', function(event) {
                $.ajax({
                    url: 'logoutOnTabClose',
                    method: 'GET',
                    data: {},
                    success: function(response) {
                        // Handle the success response if needed
                    },
                    error: function(xhr, status, error) {
                        // Handle the error if needed
                    }
                });
            });
        });
    </script>

    @stack('scripts')

    <!-- Template JS File -->
    <script src="{{ asset('stisla/js/scripts.js') }}"></script>
    <script src="{{ asset('stisla/js/custom.js') }}"></script>
    <script>
        setTimeout(function() {
            $('.swal2-popup').fadeOut();
        }, 3000);
    </script>
</body>

</html>
