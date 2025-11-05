<!DOCTYPE html>
<html lang="id">

<head>
    {{-- Memuat Meta Tags, dll. dari partial head --}}
    @include('layouts.head') 

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Tambahkan font Awesome/Tabler Icons jika 'ti' digunakan --}}
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.47.0/tabler-icons.min.css" rel="stylesheet">

    <title>Anggota Baru | Sidebar</title>

    {{-- CSS KRUSIAL UNTUK MEMPERBAIKI TAMPILAN SIDEBAR --}}
    <style>
        /* Mengatur agar sidebar fix dan mengambil tinggi penuh */
        .left-sidebar {
            /* **PERUBAHAN WARNA LATAR BELAKANG KE PUTIH/TERANG** */
            background-color: #ffffff; 
            /* **PERUBAHAN WARNA TEKS UTAMA KE GELAP** */
            color: #343a40; 
            position: fixed;
            height: 100vh;
            width: 280px; /* Lebar default sidebar */
            top: 0;
            left: 0;
            z-index: 1000;
            overflow-y: auto;
            /* Tambahkan sedikit shadow di sisi kanan untuk memisahkan dari konten utama */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05); 
        }

        /* Mengatur margin body-wrapper agar tidak tertutup sidebar */
        .body-wrapper {
            margin-left: 280px; 
            min-height: 100vh;
            transition: margin-left 0.3s ease;
            background-color: #f7f7f7; /* Memberi sedikit warna background pada konten utama */
        }

        /* CSS Sidebar dari input Anda sebelumnya */
        .sidebar-wrapper {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .sidebar-nav {
            flex: 1;
            padding: 20px 0;
        }

        .sidebar-item {
            padding: 0 15px; /* Hapus padding vertikal, pindahkan ke link */
            margin: 5px 0;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            /* **PERUBAHAN WARNA LINK KE GELAP (DEFAULT)** */
            color: #343a40; 
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .sidebar-link:hover {
            /* Hover effect yang lebih halus untuk background terang */
            background: #f1f1f1; 
            box-shadow: none; /* Hapus shadow saat hover untuk tampilan clean */
            transform: translateX(0); /* Hapus efek geser */
        }
        
        /* **STYLE UNTUK ITEM AKTIF (TAMPILAN BIRU)** */
        .sidebar-link.active {
            background-color: #5d87ff; /* Warna biru yang umum digunakan */
            color: white; /* Teks putih di atas background biru */
            font-weight: 500;
        }

        .sidebar-link.active .ti {
            color: white;
        }

        .sidebar-link .ti {
            margin-right: 12px;
            font-size: 20px;
        }
    </style>
</head>

<body>
    <!-- Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        
        <!-- Sidebar Start -->
        @include('layouts.sidebar')
        <!-- Sidebar End -->

        <!-- Main Wrapper Start -->
        <div class="body-wrapper">
            
            <!-- Header Start -->
            @include('layouts.header')
            <!-- Header End -->

            <!-- Main Content Start -->
            <div class="container-fluid">
                @yield('content')
            </div>
            <!-- Main Content End -->

            <!-- Footer Start -->
            @include('layouts.footer')
            <!-- Footer End -->
        </div>
        <!-- Main Wrapper End -->
    </div>
    
    {{-- Memuat Bootstrap JS --}}
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    {{-- AOS JS (Jika masih diperlukan) --}}
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>
