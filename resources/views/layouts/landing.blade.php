<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Perpustakaan Digital</title>
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}" />
  
  {{-- CSS Utama (Sama dengan dashboard agar rapi) --}}
  <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
  
  {{-- Ikon Tambahan --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  
  <style>
      body { background-color: #f8f9fa; }
      .navbar-brand img { max-height: 40px; }
  </style>
</head>
<body>
  
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3 fixed-top">
    <div class="container">
      <a class="navbar-brand font-weight-bold text-primary d-flex align-items-center" href="/">
    <i class="fas fa-book-reader me-2 fs-4"></i> {{-- Ikon Buku --}} <span>Perpustakaan Digital</span>
      </a>
      
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item"><a class="nav-link fw-bold" href="/">Beranda</a></li>
          <li class="nav-item"><a class="nav-link fw-bold" href="#katalog">Katalog Buku</a></li>
          
          @auth
            {{-- Tampilan jika sudah Login --}}
            <li class="nav-item dropdown ms-3">
                <a class="btn btn-outline-primary dropdown-toggle rounded-pill px-4" href="#" role="button" data-bs-toggle="dropdown">
                    Halo, {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                    @if(Auth::user()->role == 'admin')
                        <li><a class="dropdown-item" href="{{ route('dashboard') }}">Ke Dashboard Admin</a></li>
                    @endif
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">Logout</button>
                        </form>
                    </li>
                </ul>
            </li>
          @else
            {{-- Tombol Login jika belum Login --}}
            <li class="nav-item ms-3">
                <a href="{{ route('login') }}" class="btn btn-primary px-4 rounded-pill">Masuk / Daftar</a>
            </li>
          @endauth
        </ul>
      </div>
    </div>
  </nav>

  <div style="margin-top: 80px;"></div>

  <main>
      @yield('content')
  </main>

  <footer class="bg-white py-5 border-top mt-auto">
      <div class="container text-center">
        <div class="mb-4">
            <img src="{{ asset('assets/images/logos/dark-logo.svg') }}" width="100" alt="Logo Footer">
        </div>
        <p class="mb-0 text-muted small">&copy; {{ date('Y') }} Perpustakaan Digital Kelompok 7 Algorhythm. <br>All Rights Reserved.</p>
      </div>
  </footer>

  <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>