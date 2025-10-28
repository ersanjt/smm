<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - SMM Turk</title>
    
    <!-- Modern CSS Framework -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #e74c3c;
            --primary-dark: #c0392b;
            --secondary-color: #2c3e50;
            --accent-color: #f39c12;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --text-color: #2c3e50;
            --text-muted: #7f8c8d;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .auth-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 600px;
        }
        
        .auth-left {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .auth-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="white" opacity="0.1"><circle cx="50" cy="50" r="40"/></svg>');
            background-size: 200px 200px;
            animation: float 20s infinite linear;
        }
        
        @keyframes float {
            0% { transform: translateX(-50px) translateY(-50px); }
            100% { transform: translateX(50px) translateY(50px); }
        }
        
        .auth-left-content {
            position: relative;
            z-index: 2;
        }
        
        .auth-left h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .auth-left p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .auth-left .features {
            text-align: left;
        }
        
        .auth-left .feature {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }
        
        .auth-left .feature i {
            margin-right: 0.75rem;
            color: var(--accent-color);
        }
        
        .auth-right {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .auth-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }
        
        .auth-header p {
            color: var(--text-muted);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-color);
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f9fafb;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
            background: white;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }
        
        .input-group .form-control {
            padding-left: 3rem;
        }
        
        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .form-check-input {
            margin-right: 0.5rem;
        }
        
        .form-check-label {
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            width: 100%;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(231, 76, 60, 0.3);
        }
        
        .btn-secondary {
            background: #f8f9fa;
            color: var(--text-color);
            border: 2px solid #e5e7eb;
        }
        
        .btn-secondary:hover {
            background: #e5e7eb;
        }
        
        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e5e7eb;
        }
        
        .divider span {
            background: white;
            padding: 0 1rem;
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        .social-login {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .btn-social {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            background: white;
            color: var(--text-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-social:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .btn-social i {
            margin-right: 0.5rem;
        }
        
        .auth-footer {
            text-align: center;
            margin-top: 2rem;
        }
        
        .auth-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }
        
        .auth-footer a:hover {
            text-decoration: underline;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border: none;
        }
        
        .alert-danger {
            background: #fef2f2;
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }
        
        .alert-success {
            background: #f0fdf4;
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .auth-container {
                grid-template-columns: 1fr;
                max-width: 400px;
            }
            
            .auth-left {
                display: none;
            }
            
            .auth-right {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Left Side - Branding -->
        <div class="auth-left">
            <div class="auth-left-content">
                <h1>SMM Turk</h1>
                <p>World's Best Cheap & Easy SMM Panel</p>
                <p>Join thousands of satisfied customers who trust SMM Turk for their social media marketing needs.</p>
                
                <div class="features">
                    <div class="feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Cheapest prices in the market</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Fastest delivery guaranteed</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-check-circle"></i>
                        <span>24/7 customer support</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Secure payment methods</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Real-time order tracking</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Login Form -->
        <div class="auth-right">
            <div class="auth-header">
                <h2>Welcome Back!</h2>
                <p>Sign in to your SMM Turk account</p>
            </div>
            
            <?php if(isset($error_message)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($success_message)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <form action="<?php echo base_url('login/process'); ?>" method="POST">
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                </div>
                
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                    <a href="<?php echo base_url('recover'); ?>" class="ms-auto" style="color: var(--primary-color); text-decoration: none;">
                        Forgot password?
                    </a>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>
            
            <div class="divider">
                <span>or continue with</span>
            </div>
            
            <div class="social-login">
                <a href="#" class="btn-social">
                    <i class="fab fa-google"></i>
                    <span>Google</span>
                </a>
                <a href="#" class="btn-social">
                    <i class="fab fa-facebook"></i>
                    <span>Facebook</span>
                </a>
            </div>
            
            <div class="auth-footer">
                <p>Don't have an account? <a href="<?php echo base_url('register'); ?>">Sign up here</a></p>
            </div>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script>
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const email = document.querySelector('input[name="email"]').value;
            const password = document.querySelector('input[name="password"]').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all fields');
                return;
            }
            
            if (!isValidEmail(email)) {
                e.preventDefault();
                alert('Please enter a valid email address');
                return;
            }
        });
        
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
        // Password visibility toggle
        const passwordInput = document.querySelector('input[name="password"]');
        const lockIcon = document.querySelector('.fa-lock');
        
        lockIcon.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                lockIcon.classList.remove('fa-lock');
                lockIcon.classList.add('fa-unlock');
            } else {
                passwordInput.type = 'password';
                lockIcon.classList.remove('fa-unlock');
                lockIcon.classList.add('fa-lock');
            }
        });
    </script>
</body>
</html>
