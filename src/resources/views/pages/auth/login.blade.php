<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $global_setting['app_name'] ?? 'Admin Login' }}</title>
    <link rel="shortcut icon" href="{{ !empty($global_setting['app_favicon']) ? asset('storage/' . $global_setting['app_favicon']) : asset('assets/static/favicon.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background:
                @if(!empty($global_setting['app_background']))
                    url("{{ asset('storage/' . $global_setting['app_background']) }}") no-repeat center center/cover;
            @else
#f8f9fa;
        @endif
}

        .form-container {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(6px);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 420px;
        }

        .brand-logo {
            max-height: 70px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<div class="form-container">
    <div class="text-center">
        @if(!empty($global_setting['app_logo']))
            <img src="{{ asset('storage/' . $global_setting['app_logo']) }}" class="brand-logo" alt="Logo">
        @endif
        <h4 class="mb-0">{{ $global_setting['app_name'] ?? 'Panel Admin' }}</h4>
        <p class="text-muted mb-4">Admin Login</p>
    </div>

    <form action="{{ route('admin.login.post') }}" method="POST">
        @csrf

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Sign in</button>
    </form>

    <footer class="text-center mt-4 small text-muted">
        &copy; <script>document.write(new Date().getFullYear())</script>
        {{ $global_setting['app_name'] ?? 'Company' }}
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
