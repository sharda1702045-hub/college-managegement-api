<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - College Management Admin</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }
        .login-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
            background: #fff;
            width: 100%;
            max-width: 440px;
            overflow: hidden;
        }
        .login-header {
            background: #f8fafc;
            border-bottom: 1px solid #f1f5f9;
            padding: 2.5rem 2rem 1.5rem;
            text-align: center;
        }
        .login-logo {
            font-size: 2.5rem;
            color: #3b82f6;
            margin-bottom: 0.75rem;
        }
        .login-body {
            padding: 2.5rem 2.5rem 2rem;
        }
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border-color: #cbd5e1;
            transition: all 0.2s;
        }
        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }
        .btn-primary {
            background: #3b82f6;
            border: none;
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }
        .text-muted {
            color: #64748b !important;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <div class="login-logo">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <h4 class="fw-bold mb-1">Welcome Back</h4>
            <p class="text-muted small mb-0">Sign in to your administration dashboard</p>
        </div>
        
        <div class="login-body">
            
            <!-- Success/Error alert box -->
            @if(session('success'))
                <div class="alert alert-success border-0 small py-2 mb-3" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger border-0 small py-2 mb-3" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label small fw-semibold text-secondary">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                        <input type="email" name="email" id="email" class="form-control border-start-0 ps-0" placeholder="admin@example.com" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label small fw-semibold text-secondary">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                        <input type="password" name="password" id="password" class="form-control border-start-0 ps-0" placeholder="••••••••" required>
                    </div>
                </div>
                
                <div class="mb-4 d-flex align-items-center justify-content-between">
                    <div class="form-check">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                        <label for="remember" class="form-check-label small text-muted">Remember me</label>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 mb-3">
                    Sign In
                </button>
            </form>
            
        </div>
    </div>

</body>
</html>
