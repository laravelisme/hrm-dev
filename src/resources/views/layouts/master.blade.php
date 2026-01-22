<!DOCTYPE html>
<html lang="en">

<head>
    @include('components.header')
    @yield('meta-tag')
    @stack('styles')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
<div id="app">
    @include('components.sidebar')
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>

        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-md-6 order-md-1 order-last">
                        <h3>@yield('title')</h3>
                        <p class="text-subtitle text-muted">@yield('subtitle')</p>
                    </div>
                </div>
            </div>
            @yield('content')
        </div>
        @include('components.footer')
    </div>
</div>
</body>
@include('components.scripts')
@stack('scripts')
</html>
