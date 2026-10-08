<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>{{ config('app.name', 'VeLearn - Learning Management System') }} - Forgot Password</title>

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
                <h1>Account Security & Recovery</h1>
                <p>Forgot your password? No problem. Enter your email and we'll send you a password reset link to safely regain access.</p>
                
                <ul class="feature-list">
                    <li class="feature-item">
                        <i class="fas fa-shield-halved"></i>
                        Encrypted Reset Verification
                    </li>
                    <li class="feature-item">
                        <i class="fas fa-envelope-open-text"></i>
                        Instant Password Email Delivery
                    </li>
                    <li class="feature-item">
                        <i class="fas fa-clock-rotate-left"></i>
                        24/7 Account Support
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
                <h2>Forgot Password?</h2>
                <p>Enter your registered email address to receive a reset link</p>
            </div>

            @if (session('status'))
                <div style="color: #0f5132; background-color: #d1e7dd; border: 1px solid #badbcc; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; text-align: center;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope prefix-icon"></i>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" 
                            placeholder="name@example.com" required autofocus>
                    </div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    Send Password Reset Link
                </button>
            </form>

            <div class="auth-footer" style="margin-top: 24px;">
                <p>Remember your password? <a href="{{ route('login') }}" class="forgot-link">Sign In</a></p>
            </div>
            
            <div class="d-lg-none mt-5 text-center footer-copyrights" style="font-size: 0.8rem; color: #94a3b8;">
                © {{ date('Y') }} VeLearn Edutech
            </div>
        </div>
    </div>

</body>

</html>
