
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">

<link rel="preconnect" href="https://fonts.gstatic.com">
<link rel="shortcut icon" href="img/icons/icon-48x48.png" />

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<title> @yield('pageTitle') </title>

<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/app.css') }}?v={{ time() }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/light.css') }}?v={{ time() }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/toastr.min.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/calls.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/report.css') }}" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dashboards.css') }}?v={{ time() }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom-styles.css') }}">

<!-- Font Awesome CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

@stack('css')
@stack('styles')