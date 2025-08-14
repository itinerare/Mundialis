<?php header('Permissions-Policy: interest-cohort=()'); ?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('mundialis.settings.site_name', 'Mundialis') }} -@yield('title')</title>

    <!-- Primary Meta Tags -->
    <meta name="title" content="{{ config('mundialis.settings.site_name', 'Mundialis') }} -@yield('title')">
    <meta name="description"
        content="@if (View::hasSection('meta-desc')) @yield('meta-desc') @else {{ config('mundialis.settings.site_desc', 'A Mundialis site') }} @endif">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ config('app.url', 'http://localhost') }}">
    <meta property="og:image"
        content="@if (View::hasSection('meta-img')) @yield('meta-img') @else {{ Storage::url('images/meta-image.png') }} @endif">
    <meta property="og:title" content="{{ config('mundialis.settings.site_name', 'Mundialis') }} -@yield('title')">
    <meta property="og:description"
        content="@if (View::hasSection('meta-desc')) @yield('meta-desc') @else {{ config('mundialis.settings.site_desc', 'A Mundialis site') }} @endif">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ config('app.url', 'http://localhost') }}">
    <meta property="twitter:image"
        content="@if (View::hasSection('meta-img')) @yield('meta-img') @else {{ Storage::url('images/meta-image.png') }} @endif">
    <meta property="twitter:title"
        content="{{ config('mundialis.settings.site_name', 'Mundialis') }} -@yield('title')">
    <meta property="twitter:description"
        content="@if (View::hasSection('meta-desc')) @yield('meta-desc') @else {{ config('mundialis.settings.site_desc', 'A Mundialis site') }} @endif">

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>
    <script defer src="{{ mix('js/app-secondary.js') }}"></script>
    <script defer src="{{ asset('js/site.js') }}"></script>
    <script src="{{ asset('js/tinymce.min.js') }}"></script>
    <script src="{{ asset('js/jquery.tinymce.min.js') }}"></script>
    @if (View::hasSection('head-scripts'))
        @yield('head-scripts')
    @endif

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mundialis.css') }}" rel="stylesheet">
    @if (Storage::fileExists('/css/custom.css'))
        <link href="{{ Storage::url('css/custom.css') }}" rel="stylesheet">
    @endif

    {{-- Font Awesome --}}
    <link defer href="{{ asset('css/all.min.css') }}" rel="stylesheet">

    {{-- jQuery UI --}}
    <link defer href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet">

    {{-- Bootstrap Toggle --}}
    <link defer href="{{ asset('css/bootstrap4-toggle.min.css') }}" rel="stylesheet">

    <link defer href="{{ asset('css/lightbox.min.css') }}" rel="stylesheet">
    <link defer href="{{ asset('css/croppie.css') }}" rel="stylesheet">
    <link defer href="{{ asset('css/magnific.css') }}" rel="stylesheet">
    <link defer href="{{ asset('css/selectize.bootstrap4.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        @include('layouts._nav')
        @if (View::hasSection('sidebar'))
            <div class="site-mobile-header bg-secondary"><a href="#" class="btn btn-sm btn-outline-light"
                    id="mobileMenuButton">Menu <i class="fas fa-caret-right ml-1"></i></a></div>
        @endif

        <main class="container-fluid">
            <div class="row">
                @if (Settings::get('visitors_can_read') || Auth::check())
                    <div class="sidebar col-lg-2" id="sidebar">
                        <a href="{{ url('/') }}" class="py-2"><img src="{{ Storage::url('images/logo.png') }}"
                                class="mw-100 mobile-hide rounded" /></a>
                        @yield('sidebar')
                    </div>
                @endif
                <div class="main-content col-lg no-gutters">

                    <div class="p-4">
                        <div>
                            @include('flash::message')
                            @yield('content')
                        </div>

                        <div class="site-footer mt-4" id="footer">
                            @include('layouts._footer')
                        </div>
                    </div>
                </div>
            </div>

        </main>


        <div class="modal fade" id="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="modal-title h5 mb-0"></span>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                    </div>
                </div>
            </div>
        </div>

        @yield('scripts')
        <script>
            $(function() {
                $('[data-toggle="tooltip"]').tooltip({
                    html: true
                });
                tinymce.init({
                    selector: '.wysiwyg',
                    height: 500,
                    menubar: false,
                    convert_urls: false,
                    plugins: [
                        'advlist autolink lists link image charmap print preview anchor',
                        'searchreplace visualblocks code fullscreen',
                        'insertdatetime media table paste code help wordcount'
                    ],
                    toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | code',
                    content_css: [
                        '{{ asset('css/app.css') }}',
                        '{{ asset('css/mundialis.css') }}'
                    ],
                    target_list: false
                });
                var $mobileMenuButton = $('#mobileMenuButton');
                var $sidebar = $('#sidebar');
                $('#mobileMenuButton').on('click', function(e) {
                    e.preventDefault();
                    $sidebar.toggleClass('active');
                });

            });
        </script>
    </div>
</body>

</html>
