@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<div class="container-fluid px-4 mt-4 animate__animated animate__fadeIn">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bolder text-dark mb-1">Selamat Datang, Admin Nandha & Nadya👋</h3>
            <p class="text-muted mb-0 small">Ringkasan aktivitas perpustakaan hari ini.</p>
        </div>
        <div class="d-none d-md-block">
            <div class="px-3 py-2 rounded-pill bg-white shadow-sm border d-flex align-items-center text-muted small">
                <i class="ti ti-calendar me-2"></i>
                <span class="fw-bold">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4 animate__animated animate__fadeInDown" role="alert">
            <i class="ti ti-check-circle fs-5 me-2 align-middle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 text-white overflow-hidden h-100 shadow hover-scale-icon" style="background: linear-gradient(135deg, #11998e, #38ef7d);">
                <div class="card-body position-relative p-3">
                    <h6 class="text-uppercase fw-bold mb-2 opacity-75 small">Member Baru</h6>
                    <h2 class="fs-2 fw-bolder mb-0" id="newMembersCountToday">0</h2>
                    <i class="ti ti-user-plus bg-icon"></i> 
                    <a href="{{ route('member.index') }}" class="stretched-link mt-3 d-inline-block text-white text-decoration-none small opacity-75 hover-underline">
                        Lihat Detail <i class="ti ti-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 text-white overflow-hidden h-100 shadow hover-scale-icon" style="background: linear-gradient(135deg, #36d1dc, #5b86e5);">
                <div class="card-body position-relative p-3">
                    <h6 class="text-uppercase fw-bold mb-2 opacity-75 small">Dipinjam Hari Ini</h6>
                    <h2 class="fs-2 fw-bolder mb-0" id="borrowingBooksCountToday">0</h2>
                    <i class="ti ti-book-upload bg-icon" style="transform: rotate(-15deg);"></i>
                    <a href="{{ route('peminjaman') }}" class="stretched-link mt-3 d-inline-block text-white text-decoration-none small opacity-75 hover-underline">
                        Lihat Detail <i class="ti ti-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 text-white overflow-hidden h-100 shadow hover-scale-icon" style="background: linear-gradient(135deg, #56CCF2, #2F80ED);">
                <div class="card-body position-relative p-3">
                    <h6 class="text-uppercase fw-bold mb-2 opacity-75 small">Dikembalikan Hari Ini</h6>
                    <h2 class="fs-2 fw-bolder mb-0" id="returnBooksCountToday">0</h2>
                    <i class="ti ti-book-download bg-icon"></i>
                    <a href="{{ route('pengembalian') }}" class="stretched-link mt-3 d-inline-block text-white text-decoration-none small opacity-75 hover-underline">
                        Lihat Detail <i class="ti ti-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 text-white overflow-hidden h-100 shadow hover-scale-icon" style="background: linear-gradient(135deg, #ff416c, #ff4b2b);">
                <div class="card-body position-relative p-3">
                    <h6 class="text-uppercase fw-bold mb-2 opacity-75 small">Jatuh Tempo / Telat</h6>
                    <h2 class="fs-2 fw-bolder mb-0" id="overdueLoansCount">0</h2>
                    <i class="ti ti-alert-triangle bg-icon" style="right: -15px;"></i>
                    <a href="{{ route('peminjaman') }}" class="stretched-link mt-3 d-inline-block text-white text-decoration-none small opacity-75 hover-underline">
                        Lihat Detail <i class="ti ti-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
            <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
                <div class="card-header bg-white p-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold text-dark mb-0">Tren Peminjaman Buku</h6>
                        <small class="text-muted">Statistik 7 hari terakhir</small>
                    </div>
                    <div class="bg-light p-2 rounded-circle text-primary">
                        <i class="ti ti-chart-line fs-5"></i>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div id="chart" style="min-height: 300px;"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
            <h6 class="fw-bold text-dark mb-3">Ringkasan Data Master</h6>
            
            <div class="row g-3">
                <div class="col-6">
                    <div class="card border-0 shadow-sm h-100 rounded-4 card-hover-up" style="background-color: #F8F9FA;">
                        <div class="card-body p-4 text-center">
                            <div class="d-inline-block p-3 rounded-circle bg-white text-primary shadow-sm mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <i class="ti ti-users fs-2"></i>
                            </div>
                            <h3 class="fw-bolder text-dark mb-1" id="totalMembers">0</h3>
                            <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">TOTAL ANGGOTA</small>
                        </div>
                    </div>
                </div>

                 <div class="col-6">
                     <div class="card border-0 shadow-sm h-100 rounded-4 card-hover-up" style="background-color: #FFF5F5;">
                        <div class="card-body p-4 text-center">
                            <div class="d-inline-block p-3 rounded-circle bg-white text-danger shadow-sm mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <i class="ti ti-books fs-2"></i>
                            </div>
                            <h3 class="fw-bolder text-danger mb-1" id="totalBooks">0</h3>
                            <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">JUDUL BUKU</small>
                        </div>
                    </div>
                </div>

                 <div class="col-6">
                     <div class="card border-0 shadow-sm h-100 rounded-4 card-hover-up" style="background-color: #FFFBF0;">
                        <div class="card-body p-4 text-center">
                            <div class="d-inline-block p-3 rounded-circle bg-white text-warning shadow-sm mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <i class="ti ti-layout-grid fs-2"></i>
                            </div>
                            <h3 class="fw-bolder text-warning mb-1" id="totalCategories">0</h3>
                            <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">KATEGORI</small>
                        </div>
                    </div>
                </div>

                 <div class="col-6">
                     <div class="card border-0 shadow-sm h-100 rounded-4 card-hover-up" style="background-color: #F0F7FF;">
                        <div class="card-body p-4 text-center">
                            <div class="d-inline-block p-3 rounded-circle bg-white text-info shadow-sm mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <i class="ti ti-layout-board fs-2"></i>
                            </div>
                            <h3 class="fw-bolder text-info mb-1" id="totalRacks">0</h3>
                            <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">TOTAL RAK</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    /* Icon Background */
    .bg-icon {
        position: absolute;
        right: -5px;
        bottom: -15px;
        font-size: 6rem;
        opacity: 0.12;
        transform: rotate(10deg);
        pointer-events: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .hover-scale-icon:hover .bg-icon {
        transform: rotate(0deg) scale(1.2) translateY(-10px);
        opacity: 0.2;
    }
    .hover-underline:hover { text-decoration: underline !important; }

    /* Card Master Data Hover */
    .card-hover-up { transition: all 0.3s ease; }
    .card-hover-up:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
    }

    /* Utilities */
    .shadow-lg { box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important; }
    .rounded-3 { border-radius: 0.75rem !important; }
    .rounded-4 { border-radius: 1rem !important; }
    .fs-2 { font-size: 2rem !important; }

    .apexcharts-tooltip {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
        border-radius: 8px !important;
    }

    .animate__animated { animation-duration: 0.8s; }
</style>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // DATA
    var newMembersCountToday = {!! json_encode($newMembersCountToday) !!};
    var borrowingBooksCountToday = {!! json_encode($borrowingBooksCountToday) !!};
    var returnBooksCountToday = {!! json_encode($returnBooksCountToday) !!};
    var overdueLoansCount = {!! json_encode($overdueLoansCount) !!};
    
    var totalMembers = {!! json_encode($totalMembers) !!};
    var totalBooks = {!! json_encode($totalBooks) !!};
    var totalCategories = {!! json_encode($totalCategories) !!};
    var totalRacks = {!! json_encode($totalRacks) !!};

    var chartLabels = {!! json_encode($chartLabels) !!};
    var chartData = {!! json_encode($chartData) !!};

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('newMembersCountToday').textContent = newMembersCountToday;
        document.getElementById('borrowingBooksCountToday').textContent = borrowingBooksCountToday;
        document.getElementById('returnBooksCountToday').textContent = returnBooksCountToday;
        document.getElementById('overdueLoansCount').textContent = overdueLoansCount;

        document.getElementById('totalMembers').textContent = totalMembers;
        document.getElementById('totalBooks').textContent = totalBooks;
        document.getElementById('totalCategories').textContent = totalCategories;
        document.getElementById('totalRacks').textContent = totalRacks;

        var options = {
            chart: {
                type: 'area',
                height: 330,
                toolbar: { show: false },
                fontFamily: 'inherit',
                zoom: { enabled: false }
            },
            series: [{
                name: 'Total Peminjaman',
                data: chartData
            }],
            xaxis: {
                categories: chartLabels,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#aaa', fontSize: '12px' } }
            },
            yaxis: {
                labels: { style: { colors: '#aaa', fontSize: '12px' } }
            },
            colors: ['#5b86e5'], 
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            fill: {
                type: 'gradient',
                gradient: { shadeIntensity: 1, opacityFrom: 0.5, opacityTo: 0.05, stops: [0, 100] }
            },
            grid: {
                borderColor: '#f5f5f5',
                strokeDashArray: 4,
                padding: { top: 0, right: 20, bottom: 0, left: 20 }
            },
            tooltip: { theme: 'light' }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    });
</script>
@endsection