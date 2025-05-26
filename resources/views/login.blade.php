<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name') }}</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h1 {
            color: #333;
            font-size: 2rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .login-header p {
            color: #666;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .input-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            padding-left: 45px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #667eea;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .checkbox-wrapper input[type="checkbox"] {
            margin-right: 8px;
            transform: scale(1.1);
        }

        .checkbox-wrapper label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .btn-loading {
            display: none;
            align-items: center;
            justify-content: center;
        }

        .btn-loading.show {
            display: flex;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            display: none;
        }

        .alert.show {
            display: block;
            animation: slideDown 0.3s ease-out;
        }

        .alert-error {
            background: #fee;
            color: #c53030;
            border: 1px solid #fed7d7;
        }

        .alert-success {
            background: #f0fff4;
            color: #2f855a;
            border: 1px solid #c6f6d5;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-message {
            color: #e53e3e;
            font-size: 0.8rem;
            margin-top: 0.5rem;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        .forgot-password {
            text-align: center;
            margin-top: 1.5rem;
        }

        .forgot-password a {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 2rem 1.5rem;
                margin: 10px;
            }
            
            .login-header h1 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Welcome Back</h1>
            <p>Please sign in to your account</p>
        </div>

        <div id="alert-container"></div>

        <form id="loginForm">
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                </div>
                <div class="error-message" id="email-error"></div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                    <i class="fas fa-eye password-toggle" id="passwordToggle"></i>
                </div>
                <div class="error-message" id="password-error"></div>
            </div>

            <div class="checkbox-wrapper">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn-login" id="loginBtn">
                <span class="btn-text">Sign In</span>
                <span class="btn-loading">
                    <div class="spinner"></div>
                    Signing in...
                </span>
            </button>
        </form>

        <div class="forgot-password">
            <a href="#" onclick="alert('Forgot password functionality not implemented yet')">Forgot your password?</a>
        </div>
    </div>

    <script>
        // CSRF token setup for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // DOM elements
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        const btnText = document.querySelector('.btn-text');
        const btnLoading = document.querySelector('.btn-loading');
        const alertContainer = document.getElementById('alert-container');
        const passwordToggle = document.getElementById('passwordToggle');
        const passwordInput = document.getElementById('password');

        // Password toggle functionality
        passwordToggle.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Clear errors when user starts typing
        ['email', 'password'].forEach(field => {
            document.getElementById(field).addEventListener('input', function() {
                clearFieldError(field);
                hideAlert();
            });
        });

        // Form submission
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous errors
            clearAllErrors();
            hideAlert();
            
            // Show loading state
            setLoadingState(true);
            
            // Get form data
            const formData = new FormData(this);
            const loginData = {
                email: formData.get('email'),
                password: formData.get('password'),
                remember: formData.get('remember') ? true : false
            };

            // Make AJAX request
            fetch('{{ route("login.post") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(loginData)
            })
            .then(async response => {
                const data = await response.json();
                
                if (response.ok) {
                    // Success - redirect or show success message
                    showAlert('Login successful! Redirecting...', 'success');
                    
                    // Redirect after a short delay
                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ route("dashboard") }}';
                    }, 1000);
                    
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        displayValidationErrors(data.errors);
                    } else {
                        showAlert(data.message || 'Login failed. Please try again.', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Login error:', error);
                showAlert('An unexpected error occurred. Please try again.', 'error');
            })
            .finally(() => {
                setLoadingState(false);
            });
        });

        // Helper functions
        function setLoadingState(loading) {
            loginBtn.disabled = loading;
            
            if (loading) {
                btnText.style.display = 'none';
                btnLoading.classList.add('show');
            } else {
                btnText.style.display = 'inline';
                btnLoading.classList.remove('show');
            }
        }

        function showAlert(message, type) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
            const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
            
            alertContainer.innerHTML = `
                <div class="alert ${alertClass} show">
                    <i class="${icon}" style="margin-right: 8px;"></i>
                    ${message}
                </div>
            `;
        }

        function hideAlert() {
            alertContainer.innerHTML = '';
        }

        function displayValidationErrors(errors) {
            for (const [field, messages] of Object.entries(errors)) {
                const errorElement = document.getElementById(`${field}-error`);
                const inputElement = document.getElementById(field);
                
                if (errorElement && inputElement) {
                    errorElement.textContent = messages[0];
                    errorElement.classList.add('show');
                    inputElement.style.borderColor = '#e53e3e';
                }
            }
        }

        function clearFieldError(fieldName) {
            const errorElement = document.getElementById(`${fieldName}-error`);
            const inputElement = document.getElementById(fieldName);
            
            if (errorElement) {
                errorElement.classList.remove('show');
                errorElement.textContent = '';
            }
            
            if (inputElement) {
                inputElement.style.borderColor = '#e1e5e9';
            }
        }

        function clearAllErrors() {
            ['email', 'password'].forEach(field => {
                clearFieldError(field);
            });
        }

        // Auto-focus on email field when page loads
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });
    </script>
</body>
</html>