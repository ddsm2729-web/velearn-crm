<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>{{ config('app.name', 'VeLearn - Learning Management System') }} - Register</title>

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
                <h1>Start Your Learning Journey</h1>
                <p>Join thousands of learners and professionals on VeLearn. Access industry-grade courses, live webinars, and real-time tracking.</p>
                
                <ul class="feature-list">
                    <li class="feature-item">
                        <i class="fas fa-user-graduate"></i>
                        Comprehensive Course Access
                    </li>
                    <li class="feature-item">
                        <i class="fas fa-certificate"></i>
                        Industry-Recognized Certifications
                    </li>
                    <li class="feature-item">
                        <i class="fas fa-users-line"></i>
                        Dedicated Academic Mentorship
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
                <h2>Create Account</h2>
                <p>Fill in your details to register your student profile</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user prefix-icon"></i>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror" 
                            placeholder="John Doe" required autofocus autocomplete="name">
                    </div>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope prefix-icon"></i>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" 
                            placeholder="name@example.com" required autocomplete="username">
                    </div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock prefix-icon"></i>
                        <input id="password" type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" 
                            placeholder="Create a strong password" required autocomplete="new-password">
                        <span id="togglePassword" class="password-toggle">
                            <i class="fa-regular fa-eye"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock prefix-icon"></i>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror" 
                            placeholder="Re-enter your password" required autocomplete="new-password">
                        <span id="toggleConfirmPassword" class="password-toggle">
                            <i class="fa-regular fa-eye"></i>
                        </span>
                    </div>
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    Create Account
                </button>
            </form>

            <div class="auth-footer" style="margin-top: 24px;">
                <p>Already registered? <a href="{{ route('login') }}" class="forgot-link">Sign In</a></p>
            </div>
            
            <div class="d-lg-none mt-5 text-center footer-copyrights" style="font-size: 0.8rem; color: #94a3b8;">
                © {{ date('Y') }} VeLearn Edutech
            </div>
        </div>
    </div>

    <script>
        // Password Toggles
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

        const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
        const confirmPasswordInput = document.querySelector('#password_confirmation');
        if (toggleConfirmPassword && confirmPasswordInput) {
            toggleConfirmPassword.addEventListener('click', function () {
                const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPasswordInput.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        }
    </script>

</body>

</html>
