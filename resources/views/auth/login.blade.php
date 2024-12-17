<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | Course Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'poppins', sans-serif;
        }
        body {
            background: url('{{ asset('img/student/one.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: rgba(39,39,39,0.3);
        }
        .nav {
            position: fixed;
            top: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 20px 40px;
            z-index: 100;
        }
        .nav-logo {
            color: white;
            font-size: 40px;
            font-weight: 600;
        }
        .nav-buttons {
            display: flex;
            gap: 20px;
        }
        .nav-buttons i {
            color: white;
            font-size: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 10px;
            user-select: none;
        }
        .nav-buttons i:hover {
            transform: scale(1.1);
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }
        .nav-buttons i.active {
            color: #fff;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
        }
        .form-container {
            width: 400px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.08);
            -webkit-backdrop-filter: blur(5px);
            backdrop-filter: blur(5px);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            position: relative;
            overflow: hidden;
        }
        .forms {
            display: flex;
            width: 200%;
            transition: transform 0.5s ease-in-out;
            transform: translateX(0);
        }
        .login-form, .signup-form {
            min-width: 50%;
            padding: 0 20px;
            transition: opacity 0.3s ease-in-out;
        }
        .signup-form {
            opacity: 0;
        }
        .forms.show-signup {
            transform: translateX(-50%);
        }
        .forms.show-signup .signup-form {
            opacity: 1;
        }
        .forms.show-signup .login-form {
            opacity: 0;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }
        .input-group {
            margin-bottom: 20px;
        }
        .input-group input {
            width: 100%;
            padding: 12px 20px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            color: white;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .input-group input::placeholder {
            color: rgba(255, 255, 255, 0.8);
        }
        .input-group input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
        }
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .remember-forgot a {
            color: white;
            text-decoration: none;
        }
        .remember-forgot a:hover {
            text-decoration: underline;
        }
        .btn {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.85);
            border: none;
            border-radius: 30px;
            color: #333;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn:hover {
            background: white;
        }
        .signup-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
        .signup-link a {
            color: white;
            text-decoration: none;
            font-weight: 600;
        }
        .signup-link a:hover {
            text-decoration: underline;
        }
        .error-message {
            color: #ff4444;
            font-size: 14px;
            margin-top: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <nav class="nav">
            <div class="nav-logo">Nano Net</div>
            <div class="nav-buttons">
                <i onclick="showLogin()">&lt;</i>
                <i onclick="showSignup()">&gt;</i>
            </div>
        </nav>

        <div class="form-container">
            <div class="forms">
                <!-- Login Form -->
                <div class="login-form">
                    <h2>Login</h2>
                    <form id="loginForm" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="email" name="email" placeholder="Email" required>
                            <div class="error-message"></div>
                        </div>
                        <div class="input-group">
                            <input type="password" name="password" placeholder="Password" required>
                            <div class="error-message"></div>
                        </div>
                        <div class="remember-forgot">
                            <label>
                                <input type="checkbox" name="remember"> Remember me
                            </label>
                            <a href="#">Forgot Password?</a>
                        </div>
                        <button type="submit" class="btn">Sign In</button>
                        <div class="signup-link">
                            Don't have an account? <a href="#" onclick="showSignup()">Sign Up</a>
                        </div>
                    </form>
                </div>

                <!-- Signup Form -->
                <div class="signup-form">
                    <h2>Sign Up</h2>
                    <form id="signupForm" action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="first_name" placeholder="First Name" required>
                            <div class="error-message"></div>
                        </div>
                        <div class="input-group">
                            <input type="text" name="last_name" placeholder="Last Name" required>
                            <div class="error-message"></div>
                        </div>
                        <div class="input-group">
                            <input type="email" name="email" placeholder="Email" required>
                            <div class="error-message"></div>
                        </div>
                        <div class="input-group">
                            <input type="password" name="password" placeholder="Password" required>
                            <div class="error-message"></div>
                        </div>
                        <button type="submit" class="btn">Sign Up</button>
                        <div class="signup-link">
                            Already have an account? <a href="#" onclick="showLogin()">Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    const forms = document.querySelector('.forms');
    const loginBtn = document.querySelector('.nav-buttons i:first-child');
    const signupBtn = document.querySelector('.nav-buttons i:last-child');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    loginBtn.classList.add('active');

    function showLogin() {
        forms.classList.remove('show-signup');
        loginBtn.classList.add('active');
        signupBtn.classList.remove('active');
        clearErrors();
        setTimeout(() => {
            document.getElementById('loginForm').reset();
        }, 300);
    }

    function showSignup() {
        forms.classList.add('show-signup');
        signupBtn.classList.add('active');
        loginBtn.classList.remove('active');
        clearErrors();
        setTimeout(() => {
            document.getElementById('signupForm').reset();
        }, 300);
    }

    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(div => {
            div.textContent = '';
        });
    }

    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            clearErrors();

            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    if (data.errors) {
                        Object.entries(data.errors).forEach(([field, messages]) => {
                            const input = this.querySelector(`[name="${field}"]`);
                            if (input) {
                                const errorDiv = input.nextElementSibling;
                                errorDiv.textContent = messages[0];
                            }
                        });
                    } else if (data.message) {
                        const firstError = this.querySelector('.error-message');
                        firstError.textContent = data.message;
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const firstError = this.querySelector('.error-message');
                firstError.textContent = 'An error occurred. Please try again.';
            });
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') showLogin();
        if (e.key === 'ArrowRight') showSignup();
    });
    </script>
</body>
</html>