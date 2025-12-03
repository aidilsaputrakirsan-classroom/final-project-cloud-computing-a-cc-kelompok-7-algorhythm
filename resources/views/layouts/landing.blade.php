<!DOCTYPE html>
<html lang="id">

<head>
    {{-- 1. Memuat aset CSS & Meta Tags global --}}
    @include('layouts.head') 

    {{-- CSS Tambahan Khusus Landing Page --}}
    <style>
        body {
            /* Pastikan font sesuai dengan template admin */
            font-family: "Plus Jakarta Sans", sans-serif; 
            background-color: #f8f9fa;
        }
        
        .navbar-landing {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.95);
        }

        .nav-link {
            font-weight: 500;
            color: #2a3547;
        }

        .nav-link:hover {
            color: #5d87ff;
        }

        /* Footer agar selalu di bawah meski konten sedikit */
        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .content {
            flex: 1;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <nav class="navbar navbar-expand-lg navbar-light fixed-top shadow-sm navbar-landing py-3">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('landing') }}">
    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
        <i class="ti ti-book text-white fs-4"></i>
    </div>
    <div class="d-flex flex-column" style="line-height: 1.1;">
        <span class="fw-bold text-dark fs-5">Perpustakaan</span>
        <small class="text-primary fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">DIGITAL</small>
    </div>
</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link px-3" href="{{ route('landing') }}">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3" href="#katalog">Katalog Buku</a>
                        </li>

                        @auth
                            <li class="nav-item dropdown ms-2">
                                <a class="nav-link dropdown-toggle btn btn-outline-primary px-4 rounded-pill" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-user fs-4 me-1"></i> Hai, {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="navbarDropdown">
                                    {{-- Menu Dashboard (Khusus Admin) --}}
                                    @if(Auth::user()->isAdmin())
                                        <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="ti ti-layout-dashboard me-2"></i>Dashboard Admin</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                    
                                    {{-- MENU BARU: Koleksi Saya (Semua User Login) --}}
                                    <li>
                                        <a class="dropdown-item" href="{{ route('bookmarks.index') }}">
                                            <i class="ti ti-bookmarks me-2"></i> Koleksi Saya
                                        </a>
                                    </li>
                                    
                                    <li><hr class="dropdown-divider"></li>

                                    {{-- Logout --}}
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="ti ti-logout me-2"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            {{-- Jika Belum Login --}}
                            <li class="nav-item ms-2">
                                <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4">Login / Daftar</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <div class="content">
            {{-- Tambahkan spacing top agar tidak tertutup navbar fixed --}}
            <div style="margin-top: 80px;">
                @yield('content')
            </div>
        </div>

        {{-- Kita include footer yang sama, atau bisa buat footer khusus user --}}
        @include('layouts.footer')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    {{-- Memuat aset JS utama template --}}
    <script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>