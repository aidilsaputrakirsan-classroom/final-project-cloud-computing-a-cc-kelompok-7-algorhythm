<style>
  .menggulung .left-sidebar::-webkit-scrollbar-thumb {
        background-color: #c1c1c1;
        border-radius: 10px;
    }

    .sidebar-wrapper {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .sidebar-nav {
        flex: 1;
        padding: 50px 0;
    }

    .sidebar-item {
        position: relative;
        padding: 5px 15px;
        margin: 5px 0;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        color: white;
        text-decoration: none;
        padding: 10px 15px;
        border-radius: 4px;
        transition: all 0.3s;
    }


.sidebar-link:hover {
        background: rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transform: translateX(5px);
    }
    
    .sidebar-link .ti {
        margin-right: 10px;
        font-size: 18px;
    }


</style>

<aside class="left-sidebar">
    <div class="sidebar-wrapper">
        <!-- Sidebar navigation -->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('dashboard') }}" aria-expanded="false">
                        <span><i class="ti ti-layout-dashboard"></i></span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('peminjaman') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-arrows-exchange"></i>
                        </span>
                        <span class="hide-menu">Peminjaman</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('pengembalian') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-check"></i>
                        </span>
                        <span class="hide-menu">Pengembalian</span>
                    </a>
                </li>

                {{-- <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Transaksi</span>
                </li> --}}
           

                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Master</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('member.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-user"></i>
                        </span>
                        <span class="hide-menu">Anggota</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('books.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-book"></i>
                        </span>
                        <span class="hide-menu">Buku</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('categories.index')}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-category-2"></i>
                        </span>
                        <span class="hide-menu">Kategori</span>
                    </a>
                </li>
            
                <li class="sidebar-item">
                    <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('Rak.showdata') }}" aria-expanded="false">
                        <span><i class="ti ti-columns"></i></span>
                        <span class="hide-menu">Rak</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Auth</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="#" aria-expanded="false" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <span>
                            <i class="ti ti-logout"></i>
                        </span>
                        <span class="hide-menu">Logout</span>
                    </a>
                    
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Add animate.css classes for opening the sidebar
        $('#sidebarCollapse').on('click', function () {
            if ($('.left-sidebar').hasClass('animate__fadeInLeft')) {
                $('.left-sidebar').removeClass('animate__fadeInLeft').addClass('animate__fadeOutLeft');
            } else {
                $('.left-sidebar').removeClass('animate__fadeOutLeft').addClass('animate__fadeInLeft');
            }
        });

        // Automatically add animation class for sidebar items
        $('.sidebar-item').each(function (index) {
            $(this).addClass('animate__animated animate__fadeInLeft');
            $(this).css('animation-delay', (index * 0.2) + 's');
        });
    });
</script>

