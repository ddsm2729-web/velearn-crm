<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>{{ config('app.name', 'VeLearn - Learning Management System') }} - Verify Email</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.png') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <link href="{{ asset('assets/css/login.css') }}" rel="stylesheet">
</head>

<body>

    <div class="auth-container">
        <!-- Left Side: Visual -->
        <div class="auth-visual">
            <div class="visual-header">
                <img src="{{ asset('images/velearn-logo.png') }}" alt="Logo" class="logo-white" style="max-height: 50px; width: auto;">
            </div>
            
            <div class="visual-content">
                <h1>Verify Your Email</h1>
                <p>Thanks for signing up! Before getting started, please check your inbox and click the verification link to activate your account.</p>
                
                <ul class="feature-list">
                    <li class="feature-item">
                        <i class="fas fa-envelope-circle-check"></i>
                        Verified Email Identity
                    </li>
                    <li class="feature-item">
                        <i class="fas fa-circle-check"></i>
                        Unlocked Full Platform Features
                    </li>
                </ul>
            </div>

            <div class="visual-footer">
                <p>&copy; {{ date('Y') }} VeLearn. Build your future today.</p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="auth-form-container">
            <!-- Mobile Logo -->
            <div class="mobile-logo-container text-center mb-4">
                <img src="{{ asset('images/velearn-logo.png') }}" alt="Logo" style="max-width: 140px; height: auto;">
            </div>

            <div class="form-header">
                <h2>Email Verification</h2>
                <p>Please verify your email address to complete your registration</p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div style="color: #0f5132; background-color: #d1e7dd; border: 1px solid #badbcc; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; text-align: center;">
                    A new verification link has been sent to the email address you provided during registration.
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-submit">
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" style="margin-top: 16px;">
                @csrf
                <button type="submit" class="btn-submit" style="background-color: transparent; color: var(--text-body); border: 1.5px solid var(--border); box-shadow: none;">
                    Log Out
                </button>
            </form>

            <div class="d-lg-none mt-5 text-center footer-copyrights" style="font-size: 0.8rem; color: #94a3b8;">
                © {{ date('Y') }} VeLearn Edutech
            </div>
        </div>
    </div>

</body>

</html>
