
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Profile' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body>
    <header class="profile-header" style="padding:2rem 0 1rem 0; text-align:center; background:#f8faff;">
        <h2 style="font-size:2rem; color:#4361ee; font-weight:700;">{{ __('Profile') }}</h2>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>