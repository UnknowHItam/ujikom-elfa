<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Registrasi Pengguna - SMK Negeri 4 Bogor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #224abe;
            --error-color: #dc3545;
            --success-color: #28a745;
        }
        
        body {
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 15px;
            margin: 0;
            line-height: 1.5;
        }
        
        .register-container {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
            padding: 0;
            animation: slideUp 0.5s ease;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .register-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }
        
        .register-content {
            padding: 2.5rem;
        }
        
        @media (max-width: 576px) {
            .register-content {
                padding: 1.5rem;
            }
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .register-logo {
            width: 100px;
            height: 100px;
            object-fit: contain;
            margin: 0 auto 1.5rem;
            display: block;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
        }
        
        @media (max-width: 576px) {
            .register-logo {
                width: 80px;
                height: 80px;
            }
        }
        
        .register-header h2 {
            color: #2c3e50;
            font-weight: 700;
            margin: 0 0 0.5rem;
            font-size: 1.75rem;
        }
        
        @media (max-width: 576px) {
            .register-header h2 {
                font-size: 1.5rem;
            }
        }
        
        .register-header p {
            color: #6c757d;
            margin: 0;
            font-size: 0.95rem;
        }
        
        @media (max-width: 576px) {
            .register-header p {
                font-size: 0.875rem;
            }
        }
        
        .form-label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.9rem;
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border: 1px solid #e1e5eb;
            border-radius: 8px;
            transition: all 0.3s ease;
            width: 100%;
            font-size: 0.95rem;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .btn-register {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            border-radius: 8px;
            width: 100%;
            margin-top: 1rem;
            color: white;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            margin: 1rem 0;
            padding: 0.75rem 1.25rem;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .invalid-feedback {
            color: var(--error-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }
        
        .is-invalid {
            border-color: var(--error-color) !important;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        @media (max-width: 576px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
        
        .text-center-link {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.9rem;
        }
        
        .text-center-link a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .text-center-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-content">
            <div class="register-header">
                <img src="{{ asset('images/logo smkn 4.png') }}" alt="Logo SMK Negeri 4 Bogor" class="register-logo">
                <h2>Registrasi Pengguna</h2>
                <p>SMK Negeri 4 Bogor</p>
                <p class="text-muted small">Buat akun baru untuk melanjutkan</p>
            </div>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                </div>
            @endif
            
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('user.register.submit') }}" class="mt-4" autocomplete="off">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user me-1"></i> Nama Lengkap
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               placeholder="Masukkan nama lengkap"
                               required>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="username" class="form-label">
                            <i class="fas fa-at me-1"></i> Username
                        </label>
                        <input type="text" 
                               class="form-control @error('username') is-invalid @enderror" 
                               id="username" 
                               name="username" 
                               value="{{ old('username') }}"
                               placeholder="Masukkan username"
                               required>
                        @error('username')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-1"></i> Email
                        </label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               placeholder="Masukkan email"
                               required>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="phone" class="form-label">
                            <i class="fas fa-phone me-1"></i> No. HP
                        </label>
                        <input type="text" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               placeholder="Masukkan nomor HP">
                        @error('phone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-1"></i> Password
                        </label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Masukkan password"
                               required>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-check-circle me-1"></i> Konfirmasi Password
                        </label>
                        <input type="password" 
                               class="form-control @error('password_confirmation') is-invalid @enderror" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="Ulangi password"
                               required>
                        @error('password_confirmation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- reCAPTCHA -->
                @if(config('captcha.sitekey') && config('captcha.sitekey') !== 'your_recaptcha_site_key')
                <div class="form-group mt-4">
                    {!! NoCaptcha::renderJs() !!}
                    {!! NoCaptcha::display() !!}
                    @error('g-recaptcha-response')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                @else
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Mode Development:</strong> CAPTCHA dinonaktifkan. Silakan setup Google reCAPTCHA keys di file .env untuk production.
                </div>
                @endif
                
                <div class="d-grid mt-3">
                    <button type="submit" class="btn btn-register">
                        <i class="fas fa-user-plus me-2"></i> Daftar
                    </button>
                </div>
                
                <div class="text-center-link">
                    Sudah punya akun? <a href="{{ route('user.login') }}">Masuk di sini</a>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


