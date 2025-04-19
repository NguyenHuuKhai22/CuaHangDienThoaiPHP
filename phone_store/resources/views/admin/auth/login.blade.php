<!-- resources/views/admin/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin - HK Store</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: while;
            background: linear-gradient(135deg,rgb(255, 255, 255) 0%,rgb(255, 255, 255) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-wrapper {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 2rem;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 3rem 2rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        .brand-logo {
            width: 80px;
            height: 80px;
            background: #4338ca;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            box-shadow: 0 5px 15px rgba(67, 56, 202, 0.3);
        }
        .brand-logo i {
            font-size: 2.5rem;
            color: white;
        }
        .form-control {
            border: 2px solid #e2e8f0;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            border-radius: 10px;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #4338ca;
            box-shadow: 0 0 0 0.2rem rgba(67, 56, 202, 0.15);
        }
        .input-group-text {
            background: transparent;
            border: 2px solid #e2e8f0;
            border-right: none;
            color: #64748b;
        }
        .form-control {
            border-left: none;
        }
        .input-group:focus-within .input-group-text {
            border-color: #4338ca;
            color: #4338ca;
        }
        .btn-login {
            background: #4338ca;
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 10px;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(67, 56, 202, 0.3);
        }
        .btn-login:hover {
            background: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(67, 56, 202, 0.4);
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .welcome-text {
            color: #1e293b;
        }
        .welcome-text h4 {
            font-weight: 700;
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }
        .welcome-text p {
            color: #64748b;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="brand-logo">
                <i class="fas fa-store"></i>
            </div>
            
            <div class="welcome-text text-center mb-4">
                <h4>Chào mừng trở lại!</h4>
                <p>Đăng nhập để quản lý cửa hàng của bạn</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                <div class="mb-4">
                    <label class="form-label text-muted fw-medium mb-2">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="Nhập email của bạn"
                               required 
                               autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted fw-medium mb-2">Mật khẩu</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               name="password" 
                               placeholder="Nhập mật khẩu"
                               required>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-login btn-primary">
                        <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>