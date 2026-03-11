@extends('layouts.app')

@section('title', 'Login')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LDMS — Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 60% 50% at 20% 50%, rgba(37,99,235,.15) 0%, transparent 70%),
                radial-gradient(ellipse 40% 40% at 80% 20%, rgba(124,58,237,.1) 0%, transparent 60%);
        }
        .login-container {
            width: 100%; max-width: 420px;
            padding: 1rem;
            position: relative; z-index: 1;
        }
        .brand-header {
            text-align: center; margin-bottom: 2rem;
        }
        .brand-logo {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            border-radius: 16px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.8rem; color: #fff;
            margin-bottom: 1rem;
            box-shadow: 0 8px 25px rgba(37,99,235,.4);
        }
        .brand-header h1 { font-size: 1.6rem; font-weight: 800; color: #fff; margin: 0; }
        .brand-header p  { color: #94a3b8; font-size: .875rem; margin: .25rem 0 0; }

        .auth-card {
            background: rgba(30,41,59,.8);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 20px;
            padding: 2rem;
            backdrop-filter: blur(20px);
        }
        .auth-card h2 { font-size: 1.15rem; font-weight: 700; color: #f1f5f9; margin-bottom: .25rem; }
        .auth-card p  { color: #94a3b8; font-size: .85rem; margin-bottom: 1.5rem; }

        .form-label { color: #cbd5e1; font-size: .85rem; font-weight: 500; }
        .form-control, .form-select {
            background: rgba(15,23,42,.6);
            border: 1.5px solid rgba(255,255,255,.1);
            color: #f1f5f9; border-radius: 10px;
            padding: .65rem .875rem; font-size: .875rem;
        }
        .form-control:focus {
            background: rgba(15,23,42,.8);
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,.2);
            color: #f1f5f9;
        }
        .form-control::placeholder { color: #475569; }
        .btn-login {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            border: none; color: #fff;
            padding: .75rem; border-radius: 10px;
            font-weight: 600; font-size: .9rem;
            width: 100%; transition: all .2s;
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(37,99,235,.4);
            color: #fff;
        }
        .divider { border-color: rgba(255,255,255,.1); margin: 1.5rem 0; }
        .link-muted { color: #94a3b8; font-size: .85rem; }
        .link-muted a { color: #60a5fa; text-decoration: none; }
        .link-muted a:hover { color: #93c5fd; }
        .alert-danger { background: rgba(220,38,38,.15); border: 1px solid rgba(248,113,113,.2); color: #fca5a5; border-radius: 10px; font-size: .85rem; }
        .demo-creds {
            background: rgba(37,99,235,.1);
            border: 1px solid rgba(59,130,246,.2);
            border-radius: 10px; padding: .75rem 1rem; margin-top: 1rem;
        }
        .demo-creds p { color: #93c5fd; font-size: .78rem; margin: 0; font-family: 'JetBrains Mono', monospace; }
        .demo-creds .demo-title { color: #60a5fa; font-weight: 600; margin-bottom: .3rem; }
    </style>
</head>
<body>
<div class="login-container">
    <div class="brand-header">
        <div class="brand-logo"><i class="bi bi-truck"></i></div>
        <h1>LDMS</h1>
        <p>Local Delivery Management System</p>
    </div>

    <div class="auth-card">
        <h2>Welcome back</h2>
        <p>Sign in to your account to continue</p>

        @if($errors->any())
            <div class="alert alert-danger mb-3">
                @foreach($errors->all() as $error) {{ $error }} @endforeach
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" placeholder="you@example.com"
                       value="{{ old('email') }}" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label link-muted" for="remember">Remember me</label>
                </div>
            </div>
            <button type="submit" class="btn btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign in
            </button>
        </form>

        <hr class="divider">
        <p class="text-center link-muted mb-0">
            New seller? <a href="{{ route('register') }}">Create an account</a>
        </p>

        <div class="demo-creds">
            <p class="demo-title"><i class="bi bi-info-circle me-1"></i>Demo Credentials</p>
            <p>Admin: admin@ldms.com / password</p>
            <p>Seller: sara@ldms.com / password</p>
            <p>Driver: ali@ldms.com / password</p>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection
