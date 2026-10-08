<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>{{ config('app.name', 'VeLearn - Learning Management System') }}</title>

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
                <h1>Elevate Your Learning Journey</h1>
                <p>Welcome to the next generation of educational management. Professional tools for professional learners.</p>
                
                <ul class="feature-list">
                    <li class="feature-item">
                        <i class="fas fa-chart-line"></i>
                        Real-time Progress Tracking
                    </li>
                    <li class="feature-item">
                        <i class="fas fa-video"></i>
                        Integrated Live Webinars
                    </li>
                    <li class="feature-item">
                        <i class="fas fa-user-shield"></i>
                        Secure Student Portal
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
                <h2>Welcome Back</h2>
                <p>Login to access your personalized dashboard</p>
            </div>

            @if (session('error'))
                <div style="color: #842029; background-color: #f8d7da; border: 1px solid #f5c2c7; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; text-align: center;">
                    {{ session('error') }}
                </div>
            @endif

            @php
                $isImpersonating = isset($impersonateUser);
                $formAction = $isImpersonating ? route('admin.impersonate.login', $impersonateUser->id) : route('login');
                $emailValue = $isImpersonating ? $impersonateUser->email : old('email');
                $passwordValue = $isImpersonating ? '••••••••' : '';
                $readonlyAttr = $isImpersonating ? 'readonly' : '';
            @endphp
            <form method="POST" action="{{ $formAction }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope prefix-icon"></i>
                        <input type="email" name="email" value="{{ $emailValue }}" {{ $readonlyAttr }}
                            class="form-control @error('email') is-invalid @enderror" 
                            placeholder="name@example.com" required {{ !$isImpersonating ? 'autofocus' : '' }}>
                    </div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock prefix-icon"></i>
                        <input id="password" type="password" name="password" value="{{ $passwordValue }}" {{ $readonlyAttr }}
                            class="form-control @error('password') is-invalid @enderror" 
                            placeholder="Enter your password" required>
                        @if(!$isImpersonating)
                        <span id="togglePassword" class="password-toggle">
                            <i class="fa-regular fa-eye"></i>
                        </span>
                        @endif
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-options">
                    @if(!$isImpersonating)
                    <label class="remember-me">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                    @endif
                    @endif
                </div>

                <button type="submit" class="btn-submit">
                    {{ $isImpersonating ? 'View Details' : 'Sign In' }}
                </button>
            </form>

            {{-- <div class="auth-footer">
                <p>New here? <a href="#" class="forgot-link">Request institutional access</a></p>
            </div> --}}
            
            <div class="d-lg-none mt-5 text-center footer-copyrights" style="font-size: 0.8rem; color: #94a3b8;">
                © {{ date('Y') }} VeLearn Edutech
            </div>
        </div>
    </div>

    <script>
        // Password Toggle Script
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    </script>

</body>

</html>
