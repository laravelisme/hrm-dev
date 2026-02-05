<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $global_setting['app_name'] }} - @yield('title')</title>
<link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
<link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
<link rel="shortcut icon" href="{{ !empty($global_setting['app_favicon']) ? asset('storage/' . $global_setting['app_favicon']) : asset('assets/static/favicon.ico') }}" type="image/x-icon">
