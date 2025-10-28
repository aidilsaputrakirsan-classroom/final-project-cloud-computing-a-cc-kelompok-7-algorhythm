{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Mini - @yield('title', 'Dashboard')</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        
        @include('partials._sidebar')

        <main class="main-content">
            <header class="top-navbar">
                <div class="user-profile">
                    <img src="{{ asset('images/profile-pic.png') }}" alt="User">
                </div>
            </header>

            @yield('content')
            
            <footer class="page-footer">
                Design and Developed by <strong>Perpustakaan Mini</strong>
            </footer>
        </main>
    </div>

    {{-- <script src="{{ asset('js/script.js') }}"></script> --}}
</body>
</html>