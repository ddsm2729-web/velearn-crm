<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('includes.head')
    @push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/layouts-header.css') }}">
@endpush
</head>

<body>
    <!-- Preloader -->
    <div id="preloader">
        <div class="loader"></div>
    </div>

    <div class="wrapper">
        @if(auth()->check() && auth()->user()->hasRole('SuperAdmin') || auth()->check() && auth()->user()->hasRole('Operation Executive'))
            @include('includes.super_admin_sidebar')
        @else
            @include('includes.sidebar')
        @endif
        <div class="main">
            @if(session()->has('impersonated_by'))
            <div class="bg-danger text-white text-center py-2 fw-bold w-100" style="z-index: 1050;">
                You are currently impersonating a staff member. 
                <a href="{{ route('admin.impersonate.leave') }}" class="text-white text-decoration-underline ms-2">Return to Super Admin</a>
            </div>
            @endif

            @include('includes.topbar')
            <main class="content">
                @yield('section')
            </main>
            @include('includes.footer')
        </div>
    </div>

    <script>
        var baseUrl = "{{ url('/') }}";

        // Hide preloader when page is fully loaded
        window.addEventListener('load', function() {
            var preloader = document.getElementById('preloader');
            preloader.style.display = 'none';
        });
    </script>

    @include('includes.scripts')
</body>

</html>
