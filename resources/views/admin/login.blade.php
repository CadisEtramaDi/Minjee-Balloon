<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Minjee Balloon</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; min-height: 100vh; display: flex; }
        
        .login-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Left Panel - Branding */
        .brand-panel {
            flex: 1;
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.7), rgba(2, 132, 199, 0.65)), 
                        url('/images/balloon-background.jpg') center/cover no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 40px;
            position: relative;
            overflow: hidden;
        }

        .brand-panel::before {
            content: '';
            position: absolute;
            top: -80px;
            right: -80px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
        }

        .brand-panel::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: -60px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }

        .brand-logo {
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.3);
        }

        .brand-title {
            font-size: 36px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
            text-align: center;
        }

        .brand-subtitle {
            font-size: 16px;
            color: rgba(255,255,255,0.85);
            text-align: center;
            max-width: 320px;
            line-height: 1.6;
        }

        .brand-features {
            margin-top: 50px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .brand-feature {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.9);
            font-size: 14px;
        }

        .brand-feature-icon {
            width: 36px;
            height: 36px;
            background: rgba(255,255,255,0.15);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Right Panel - Login Form */
        .form-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 40px;
            background: #ffffff;
        }

        .form-container {
            width: 100%;
            max-width: 400px;
        }

        .form-header {
            margin-bottom: 36px;
        }

        .form-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }

        .form-header p {
            font-size: 15px;
            color: #6b7280;
        }

        .alert-error {
            padding: 12px 16px;
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
            color: #b91c1c;
        }

        .alert-success {
            padding: 12px 16px;
            background: #f0fdf4;
            border-left: 4px solid #22c55e;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
            color: #15803d;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 15px;
            color: #111827;
            background: #f9fafb;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-input:focus {
            border-color: #0EA5E9;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .form-error {
            margin-top: 6px;
            font-size: 13px;
            color: #dc2626;
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            padding: 4px;
        }

        .toggle-password:hover {
            color: #6b7280;
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #0EA5E9, #0284c7);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
            letter-spacing: 0.3px;
        }

        .login-btn:hover {
            background: linear-gradient(135deg, #0284c7, #0369a1);
            box-shadow: 0 8px 25px rgba(14, 165, 233, 0.35);
            transform: translateY(-1px);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .form-footer {
            margin-top: 28px;
            text-align: center;
        }

        .form-footer a {
            font-size: 14px;
            color: #0EA5E9;
            text-decoration: none;
            font-weight: 500;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-wrapper { flex-direction: column; }
            .brand-panel { 
                padding: 40px 30px; 
                min-height: auto;
            }
            .brand-features { display: none; }
            .brand-title { font-size: 28px; }
            .brand-logo { width: 70px; height: 70px; margin-bottom: 20px; }
            .form-panel { padding: 40px 24px; }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Left Branding Panel -->
        <div class="brand-panel">
            <div class="brand-logo">
                <svg width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 3.17 2.11 5.84 5 6.71V22h4v-6.29c2.89-.87 5-3.54 5-6.71 0-3.87-3.13-7-7-7z" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10 22h4" stroke-linecap="round"/>
                    <path d="M11 18h2" stroke-linecap="round"/>
                </svg>
            </div>
            <h1 class="brand-title">Minjee Balloon</h1>
            <p class="brand-subtitle">Your all-in-one platform for managing balloon rental bookings, inventory, and payments.</p>
            
            <div class="brand-features">
                <div class="brand-feature">
                    <div class="brand-feature-icon">
                        <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    Booking Management
                </div>
                <div class="brand-feature">
                    <div class="brand-feature-icon">
                        <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    Inventory Tracking
                </div>
                <div class="brand-feature">
                    <div class="brand-feature-icon">
                        <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    Payment & Sales Reports
                </div>
            </div>
        </div>

        <!-- Right Form Panel -->
        <div class="form-panel">
            <div class="form-container">
                <div class="form-header">
                    <h1>Welcome Back</h1>
                    <p>Sign in to access the admin panel</p>
                </div>

                @if(session('error'))
                    <div class="alert-error">{{ session('error') }}</div>
                @endif

                @if(session('success'))
                    <div class="alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.login.post') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            required
                            value="{{ old('username') }}"
                            class="form-input"
                            placeholder="Enter your username"
                        />
                        @error('username')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="password-wrapper">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                class="form-input"
                                placeholder="Enter your password"
                            />
                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                <svg id="eyeIcon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="login-btn">
                        Sign In
                    </button>
                </form>

                <div class="form-footer">
                    <a href="/">← Back to Homepage</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }
    </script>
</body>
</html>
