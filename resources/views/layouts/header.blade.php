<!--  Header Start -->
<header class="app-header animate__animated animate__fadeInDown">
    <nav class="navbar navbar-expand-lg navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
                <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2"></i>
                </a>
            </li>
        </ul>
        <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                
                <li class="nav-item dropdown">
                    <a class="nav-link nav-icon-hover profile-icon" href="javascript:void(0)" id="drop2"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <!-- Ganti dengan path ke gambar profile admin Anda -->
                        <img src="{{ asset('assets/images/logos/favicon.png') }}" alt="Perpustakaan" width="35"
    height="35" class="rounded-circle profile-image p-1 border">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                        <div class="message-body">
                            
                            <!-- Menampilkan nama user yang login -->
                            <div class="text-center my-2 mx-3">
                                <!-- Mengambil nama dari user yang sedang login -->
                                <strong>Halo, {{ Auth::user()->name ?? 'Admin' }}</strong>
                            </div>
                            
                            <!-- Form Logout -->
                            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                @csrf
                                <!-- 
                                  PERUBAHAN:
                                  - Menggunakan fungsi confirmLogout() dari file footer.blade.php Anda
                                -->
                                <button type="button" onclick="confirmLogout()"
                                    class="btn btn-outline-primary mx-3 mt-2 d-block w-100">Logout</button>
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>
<!--  Header End -->

<!-- 
  STYLE UNTUK HEADER
  (Ini dari file header.blade.php Anda, tapi saya pindahkan ke sini agar rapi)
-->
<style>
    .app-header {
        background-color: #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        /* Z-index dikurangi agar di bawah modal/alert */
        z-index: 1020; 
    }

    .navbar-light .navbar-nav .nav-link {
        color: #333;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .navbar-light .navbar-nav .nav-link:hover {
        color: #0056b3;
    }

    .navbar-nav .nav-icon-hover {
        position: relative;
        padding: 10px;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .nav-icon-hover:hover {
        transform: scale(1.1);
    }

    .profile-icon {
        display: flex;
        align-items: center;
        transition: transform 0.3s ease;
    }

    .profile-icon:hover {
        transform: scale(1.1);
    }

    .profile-image {
        border: 2px solid #333;
        transition: border-color 0.3s ease;
    }

    .profile-image:hover {
        border-color: #0056b3;
    }

    .dropdown-menu-animate-up {
        animation: fadeInUp 0.3s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>