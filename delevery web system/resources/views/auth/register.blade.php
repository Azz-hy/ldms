@extends('layouts.app')
@section('title', 'Register')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LDMS — Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0f172a;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            position: relative;
        }
        body::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse 60% 50% at 20% 50%, rgba(37,99,235,.12) 0%, transparent 70%);
        }
        .register-container { width: 100%; max-width: 480px; padding: 1rem; position: relative; z-index: 1; }
        .brand-logo {
            width: 48px; height: 48px;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            border-radius: 14px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.4rem; color: #fff; margin-bottom: .75rem;
        }
        .auth-card {
            background: rgba(30,41,59,.8);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 20px; padding: 2rem;
            backdrop-filter: blur(20px);
        }
        .auth-card h2 { font-size: 1.15rem; font-weight: 700; color: #f1f5f9; margin-bottom: .25rem; }
        .auth-card p.sub { color: #94a3b8; font-size: .85rem; margin-bottom: 1.5rem; }
        .form-label { color: #cbd5e1; font-size: .85rem; font-weight: 500; }
        .form-control {
            background: rgba(15,23,42,.6); border: 1.5px solid rgba(255,255,255,.1);
            color: #f1f5f9; border-radius: 10px; padding: .6rem .875rem; font-size: .875rem;
        }
        .form-control:focus { background: rgba(15,23,42,.8); border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.2); color: #f1f5f9; }
        .form-control::placeholder { color: #475569; }
        .btn-register {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            border: none; color: #fff; padding: .75rem;
            border-radius: 10px; font-weight: 600; width: 100%;
        }
        .btn-register:hover { color: #fff; transform: translateY(-1px); box-shadow: 0 8px 20px rgba(37,99,235,.4); }
        .invalid-feedback { font-size: .8rem; }
        .divider { border-color: rgba(255,255,255,.1); margin: 1.25rem 0; }
        .link-muted { color: #94a3b8; font-size: .85rem; }
        .link-muted a { color: #60a5fa; text-decoration: none; }
        .alert-danger { background: rgba(220,38,38,.15); border: 1px solid rgba(248,113,113,.2); color: #fca5a5; border-radius: 10px; font-size: .85rem; }
        .section-divider { color: #475569; font-size: .75rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; margin: 1.25rem 0 .75rem; position: relative; }
        .section-divider::after { content: ''; position: absolute; top: 50%; left: 0; right: 0; height: 1px; background: rgba(255,255,255,.08); z-index: -1; }
        .section-divider span { background: rgba(30,41,59,.95); padding: 0 .5rem 0 0; }
    </style>
</head>
<body>
<div class="register-container">
    <div class="text-center mb-3">
        <div class="brand-logo"><i class="bi bi-truck"></i></div>
        <h1 style="font-size:1.4rem;font-weight:800;color:#fff;margin:0">LDMS</h1>
        <p style="color:#94a3b8;font-size:.8rem">Local Delivery Management System</p>
    </div>
    <div class="auth-card">
        <h2>Create Seller Account</h2>
        <p class="sub">Join LDMS to manage your deliveries efficiently</p>

        @if($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirm Password *</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>

            <div class="section-divider"><span>Business Info (Optional)</span></div>

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Business Name</label>
                    <input type="text" name="business_name" class="form-control" value="{{ old('business_name') }}" placeholder="Your shop or brand name">
                </div>
                <div class="col-12">
                    <label class="form-label">Business Address</label>
                    <textarea name="business_address" class="form-control" rows="2" placeholder="Your pickup location">{{ old('business_address') }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-register mt-4">
                <i class="bi bi-person-plus me-2"></i>Create Account
            </button>
        </form>

        <hr class="divider">
        <p class="text-center link-muted mb-0">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection
