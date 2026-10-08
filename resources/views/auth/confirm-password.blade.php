<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>{{ config('app.name', 'VeLearn - Learning Management System') }} - Confirm Password</title>

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
                <h1>Security Verification</h1>
                <p>This is a protected area of the application. Please confirm your password before continuing.</p>
                
                <ul class="feature-list">
                    <li class="feature-item">
                        <i class="fas fa-user-check"></i>
                        Identity Verification Required
                    </li>
                    <li class="feature-item">
                        <i class="fas fa-shield-cat"></i>
                        Protected System Resources
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
                <h2>Confirm Password</h2>
                <p>Please enter your current password to continue</p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock prefix-icon"></i>
                        <input id="password" type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" 
                            placeholder="Enter your password" required autocomplete="current-password" autofocus>
                        <span id="togglePassword" class="password-toggle">
                            <i class="fa-regular fa-eye"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    Confirm Password
                </button>
            </form>

            <div class="auth-footer" style="margin-top: 24px;">
                <p><a href="{{ route('/') }}" class="forgot-link">Cancel & Return to Dashboard</a></p>
            </div>
            
            <div class="d-lg-none mt-5 text-center footer-copyrights" style="font-size: 0.8rem; color: #94a3b8;">
                © {{ date('Y') }} VeLearn Edutech
            </div>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        }
    </script>

</body>

</html>
