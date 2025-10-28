{{-- resources/views/layouts/partials/_sidebar.blade.php --}}
<aside class="sidebar">
    <div class="sidebar-header">
        
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li class="nav-section-title">HOME</li>
            <li><a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            
            <li class="nav-section-title">TRANSAKSI</li>
            <li><a href="#"><i class="fas fa-upload"></i> Peminjaman</a></li>
            <li><a href="#"><i class="fas fa-download"></i> Pengembalian</a></li>
            <li><a href="#"><i class="fas fa-file-invoice-dollar"></i> Penagihan Denda</a></li>
            <li><a href="#"><i class="fas fa-history"></i> History Transaksi</a></li>

            <li class="nav-section-title">MASTER</li>
            <li><a href="#"><i class="fas fa-users"></i> Anggota</a></li>
            
            <li>
                <a href="{{ route('buku.index') }}" class="{{ request()->routeIs('buku.index') ? 'active' : '' }}">
                    <i class="fas fa-book"></i> Buku
                </a>
            </li>
            
            <li><a href="#"><i class="fas fa-tags"></i> Kategori</a></li>
            <li><a href="#"><i class="fas fa-layer-group"></i> Rak</a></li>
        </ul>
    </nav>
</aside>