@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <style>
        /* (CSS dari template Anda tetap di sini, tidak perlu diubah) */
        /* ... saya sembunyikan CSS agar tidak terlalu panjang ... */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            color: #333;
            line-height: 1.6;
        }

        .container-fluid {
            padding: 20px;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            margin-bottom: 20px;
            padding: 20px;
            background-color: white;
            overflow: hidden;
            position: relative;
            transform-style: preserve-3d;
            perspective: 1000px;
        }

        .card:hover {
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.2);
            transform: translateY(-10px) rotateX(10deg);
        }
        
        .card-simple {
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.07);
            padding: 15px;
            margin-bottom: 15px;
            background: #fff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-simple:hover {
             transform: translateY(-5px);
             box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .card-header {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 15px;
            background: linear-gradient(135deg, transparent 0%, transparent 17%, rgba(87, 146, 234, 0.6) 17%, rgba(87, 146, 234, 0.6) 59%, transparent 59%, transparent 64%, rgba(34, 81, 222, 0.6) 64%, rgba(34, 81, 222, 0.6) 100%), linear-gradient(45deg, transparent 0%, transparent 2%, rgb(87, 146, 234) 2%, rgb(87, 146, 234) 46%, rgb(114, 178, 239) 46%, rgb(114, 178, 239) 54%, transparent 54%, transparent 63%, rgb(7, 48, 216) 63%, rgb(7, 48, 216) 100%), linear-gradient(90deg, rgb(255, 255, 255), rgb(255, 255, 255));
            color: white;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .card-body {
            font-size: 1rem;
            padding: 15px;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-top: 5px;
            transition: transform 0.5s;
        }

        .stat-box {
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            cursor: pointer;
            background: linear-gradient(45deg, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.1));
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
        }


        .stat-box:hover .stat-number {
            transform: scale(1.2);
        }

        .stat-box h4,
        .stat-box h3 {
            position: relative;
            z-index: 2;
        }

        .stat-box:before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: inherit;
            filter: blur(30px);
            z-index: 1;
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }

        .bg-primary {
            background-color: #007bff !important;
            color: white;
        }

        .bg-success {
            background: repeating-linear-gradient(45deg, rgba(208, 208, 208, 0.13) 0px, rgba(208, 208, 208, 0.13) 43px, rgba(195, 195, 195, 0.13) 43px, rgba(195, 195, 195, 0.13) 85px, rgba(41, 41, 41, 0.13) 85px, rgba(41, 41, 41, 0.13) 109px, rgba(88, 88, 88, 0.13) 109px, rgba(88, 88, 88, 0.13) 129px, rgba(24, 24, 24, 0.13) 129px, rgba(24, 24, 24, 0.13) 139px, rgba(92, 92, 92, 0.13) 139px, rgba(92, 92, 92, 0.13) 179px, rgba(37, 37, 37, 0.13) 179px, rgba(37, 37, 37, 0.13) 219px), repeating-linear-gradient(45deg, rgba(18, 18, 18, 0.13) 0px, rgba(18, 18, 18, 0.13) 13px, rgba(48, 48, 48, 0.13) 13px, rgba(48, 48, 48, 0.13) 61px, rgba(130, 130, 130, 0.13) 61px, rgba(130, 130, 130, 0.13) 84px, rgba(233, 233, 233, 0.13) 84px, rgba(233, 233, 233, 0.13) 109px, rgba(8, 8, 8, 0.13) 109px, rgba(8, 8, 8, 0.13) 143px, rgba(248, 248, 248, 0.13) 143px, rgba(248, 248, 248, 0.13) 173px, rgba(37, 37, 37, 0.13) 173px, rgba(37, 37, 37, 0.13) 188px), repeating-linear-gradient(45deg, rgba(3, 3, 3, 0.1) 0px, rgba(3, 3, 3, 0.1) 134px, rgba(82, 82, 82, 0.1) 134px, rgba(82, 82, 82, 0.1) 282px, rgba(220, 220, 220, 0.1) 282px, rgba(220, 220, 220, 0.1) 389px, rgba(173, 173, 173, 0.1) 389px, rgba(173, 173, 173, 0.1) 458px, rgba(109, 109, 109, 0.1) 458px, rgba(109, 109, 109, 0.1) 516px, rgba(240, 240, 240, 0.1) 516px, rgba(240, 240, 240, 0.1) 656px, rgba(205, 205, 205, 0.1) 656px, rgba(205, 205, 205, 0.1) 722px), linear-gradient(90deg, rgb(21, 145, 22), rgb(39, 248, 84));
            important;
            box-shadow: 0px 10px 15px rgba(76, 175, 80, 0.4);
            !important;
            color: white;
        }

        .bg-info {
            background: repeating-linear-gradient(45deg, rgba(208, 208, 208, 0.13) 0px, rgba(208, 208, 208, 0.13) 43px, rgba(195, 195, 195, 0.13) 43px, rgba(195, 195, 195, 0.13) 85px, rgba(41, 41, 41, 0.13) 85px, rgba(41, 41, 41, 0.13) 109px, rgba(88, 88, 88, 0.13) 109px, rgba(88, 88, 88, 0.13) 129px, rgba(24, 24, 24, 0.13) 129px, rgba(24, 24, 24, 0.13) 139px, rgba(92, 92, 92, 0.13) 139px, rgba(92, 92, 92, 0.13) 179px, rgba(37, 37, 37, 0.13) 179px, rgba(37, 37, 37, 0.13) 219px), repeating-linear-gradient(45deg, rgba(18, 18, 18, 0.13) 0px, rgba(18, 18, 18, 0.13) 13px, rgba(48, 48, 48, 0.13) 13px, rgba(48, 48, 48, 0.13) 61px, rgba(130, 130, 130, 0.13) 61px, rgba(130, 130, 130, 0.13) 84px, rgba(233, 233, 233, 0.13) 84px, rgba(233, 233, 233, 0.13) 109px, rgba(8, 8, 8, 0.13) 109px, rgba(8, 8, 8, 0.13) 143px, rgba(248, 248, 248, 0.13) 143px, rgba(248, 248, 248, 0.13) 173px, rgba(37, 37, 37, 0.13) 173px, rgba(37, 37, 37, 0.13) 188px), repeating-linear-gradient(45deg, rgba(3, 3, 3, 0.1) 0px, rgba(3, 3, 3, 0.1) 134px, rgba(82, 82, 82, 0.1) 134px, rgba(82, 82, 82, 0.1) 282px, rgba(220, 220, 220, 0.1) 282px, rgba(220, 220, 220, 0.1) 389px, rgba(173, 173, 173, 0.1) 389px, rgba(173, 173, 173, 0.1) 458px, rgba(109, 109, 109, 0.1) 458px, rgba(109, 109, 109, 0.1) 516px, rgba(240, 240, 240, 0.1) 516px, rgba(240, 240, 240, 0.1) 656px, rgba(205, 205, 205, 0.1) 656px, rgba(205, 205, 205, 0.1) 722px), linear-gradient(90deg, rgb(26, 47, 236), rgb(39, 77, 248));
            !important;
            box-shadow: 0px 5px 10px rgb(19, 93, 221);
            !important;
            color: white;
        }

        .bg-danger {
            background: repeating-linear-gradient(45deg, rgba(208, 208, 208, 0.13) 0px, rgba(208, 208, 208, 0.13) 43px, rgba(195, 195, 195, 0.13) 43px, rgba(195, 195, 195, 0.13) 85px, rgba(41, 41, 41, 0.13) 85px, rgba(41, 41, 41, 0.13) 109px, rgba(88, 88, 88, 0.13) 109px, rgba(88, 88, 88, 0.13) 129px, rgba(24, 24, 24, 0.13) 129px, rgba(24, 24, 24, 0.13) 139px, rgba(92, 92, 92, 0.13) 139px, rgba(92, 92, 92, 0.13) 179px, rgba(37, 37, 37, 0.13) 179px, rgba(37, 37, 37, 0.13) 219px), repeating-linear-gradient(45deg, rgba(18, 18, 18, 0.13) 0px, rgba(18, 18, 18, 0.13) 13px, rgba(48, 48, 48, 0.13) 13px, rgba(48, 48, 48, 0.13) 61px, rgba(130, 130, 130, 0.13) 61px, rgba(130, 130, 130, 0.13) 84px, rgba(233, 233, 233, 0.13) 84px, rgba(233, 233, 233, 0.13) 109px, rgba(8, 8, 8, 0.13) 109px, rgba(8, 8, 8, 0.13) 143px, rgba(248, 248, 248, 0.13) 143px, rgba(248, 248, 248, 0.13) 173px, rgba(37, 37, 37, 0.13) 173px, rgba(37, 37, 37, 0.13) 188px), repeating-linear-gradient(45deg, rgba(3, 3, 3, 0.1) 0px, rgba(3, 3, 3, 0.1) 134px, rgba(82, 82, 82, 0.1) 134px, rgba(82, 82, 82, 0.1) 282px, rgba(220, 220, 220, 0.1) 282px, rgba(220, 220, 220, 0.1) 389px, rgba(173, 173, 173, 0.1) 389px, rgba(173, 173, 173, 0.1) 458px, rgba(109, 109, 109, 0.1) 458px, rgba(109, 109, 109, 0.1) 516px, rgba(240, 240, 240, 0.1) 516px, rgba(240, 240, 240, 0.1) 656px, rgba(205, 205, 205, 0.1) 656px, rgba(205, 205, 205, 0.1) 722px), linear-gradient(90deg, rgb(236, 26, 26), rgb(148, 19, 19));
            !important;
            box-shadow: 0px 5px 10px rgb(198, 21, 51);
            !important;
            color: white;
        }

        .bg-warning {
             background-color: #ffc107 !important;
             color: #333;
        }

        .bg-secondary {
             background-color: #6c757d !important;
             color: white;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .pulse {
            animation: pulse 3.5s infinite;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .slide-in {
            animation: slideIn 2s ease-out;
        }

        @keyframes bounceIn {
            from { opacity: 0; transform: scale(0.5); }
            to { opacity: 1; transform: scale(1); }
        }

        .bounce-in {
            animation: bounceIn 1s ease-out;
        }
    </style>

    <!-- ======================================================================= -->
    <!-- JAVASCRIPT UNTUK MENGISI DATA (DISESUAIKAN) -->
    <!-- ======================================================================= -->
    <script>
        // Data dari Controller (dihapus denda, ditambah data master)
        var newMembersCountToday = {!! json_encode($newMembersCountToday) !!};
        var borrowingBooksCountToday = {!! json_encode($borrowingBooksCountToday) !!};
        var returnBooksCountToday = {!! json_encode($returnBooksCountToday) !!};
        var overdueLoansCount = {!! json_encode($overdueLoansCount) !!};
        
        var totalMembers = {!! json_encode($totalMembers) !!};
        var totalBooks = {!! json_encode($totalBooks) !!};
        var totalCategories = {!! json_encode($totalCategories) !!};
        var totalRacks = {!! json_encode($totalRacks) !!};

        // Data Chart
        var chartLabels = {!! json_encode($chartLabels) !!};
        var chartData = {!! json_encode($chartData) !!};


        document.addEventListener('DOMContentLoaded', function() {
            // Update stat boxes "Laporan Hari Ini"
            document.getElementById('newMembersCountToday').textContent = newMembersCountToday;
            document.getElementById('borrowingBooksCountToday').textContent = borrowingBooksCountToday;
            document.getElementById('returnBooksCountToday').textContent = returnBooksCountToday;
            document.getElementById('overdueLoansCount').textContent = overdueLoansCount;

            // Update stat boxes "Master Data"
            document.getElementById('totalMembers').textContent = totalMembers;
            document.getElementById('totalBooks').textContent = totalBooks;
            document.getElementById('totalCategories').textContent = totalCategories;
            document.getElementById('totalRacks').textContent = totalRacks;

            // Render Chart
            var options = {
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                series: [{
                    name: 'Peminjaman',
                    data: chartData
                }],
                xaxis: {
                    categories: chartLabels
                },
                colors: ['#007bff'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        });
    </script>
    <!-- ======================================================================= -->
    <!-- END OF JAVASCRIPT -->
    <!-- ======================================================================= -->


    <div class="container-fluid slide-in">
        <div class="col-12">
            <div class="card bounce-in">
                <div class="card-header bg-primary">
                    Laporan Hari Ini
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 col-md-3">
                            <div class="stat-box bg-success pulse" onclick="window.location.href='/member'">
                                <h4 class="text-white"><b>Member Baru</b></h4>
                                <!-- ID disesuaikan -->
                                <h3 id="newMembersCountToday" class="stat-number">0</h3>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-box bg-info pulse" onclick="window.location.href='/peminjaman'">
                                <h4 class="text-white"><b>Pinjam Buku</b></h4>
                                <!-- ID disesuaikan -->
                                <h3 id="borrowingBooksCountToday" class="stat-number">0</h3>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-box bg-info pulse" onclick="window.location.href='/pengembalian'">
                                <h4 class="text-white"><b>Kembali Buku</b></h4>
                                <!-- ID disesuaikan -->
                                <h3 id="returnBooksCountToday" class="stat-number">0</h3>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-box bg-danger pulse" onclick="window.location.href='/peminjaman'">
                                <h4 class="text-white"><b>Pinjaman Telat</b></h4>
                                <!-- ID disesuaikan -->
                                <h3 id="overdueLoansCount" class="stat-number">0</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- ======================================================================= -->
            <!-- CHART (DISESUAIKAN) -->
            <!-- ======================================================================= -->
            <div class="col-lg-8 d-flex align-items-stretch">
                <div class="card w-100 bounce-in">
                    <div class="card-body">
                        <div class="d-sm-flex d-block align-items-center justify-content-between mb-4">
                            <div class="mb-3 mb-sm-0">
                                <h5 class="card-title fw-semibold">Ikhtisar Peminjaman (7 Hari Terakhir)</h5>
                            </div>
                        </div>
                        <!-- Chart akan dirender di sini oleh ApexCharts -->
                        <div id="chart">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ======================================================================= -->
            <!-- BAGIAN FINANSIAL (DIGANTI DENGAN MASTER DATA) -->
            <!-- ======================================================================= -->
            <div class="col-lg-4">
                <div class="card bounce-in">
                    <div class="card-body">
                         <h5 class="card-title fw-semibold text-center mb-4">Master Data</h5>

                         <!-- Card Master Data (BARU) -->
                         <div class="card-simple bg-light-success mb-3">
                             <div class="d-flex justify-content-between align-items-center">
                                 <h6 class="mb-0">Total Anggota</h6>
                                 <h4 id="totalMembers" class="fw-semibold mb-0">0</h4>
                             </div>
                         </div>
                         <div class="card-simple bg-light-danger mb-3">
                             <div class="d-flex justify-content-between align-items-center">
                                 <h6 class="mb-0">Total Judul Buku</h6>
                                 <h4 id="totalBooks" class="fw-semibold mb-0">0</h4>
                             </div>
                         </div>
                         <div class="card-simple bg-warning mb-3">
                             <div class="d-flex justify-content-between align-items-center">
                                 <h6 class="mb-0">Total Kategori</h6>
                                 <h4 id="totalCategories" class="fw-semibold mb-0">0</h4>
                             </div>
                         </div>
                         <div class="card-simple bg-secondary text-white">
                             <div class="d-flex justify-content-between align-items-center">
                                 <h6 class="mb-0">Total Rak</h6>
                                 <h4 id="totalRacks" class_="fw-semibold mb-0">0</h4>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
            <!-- ======================================================================= -->
            <!-- AKHIR DARI BAGIAN YANG DIGANTI -->
            <!-- ======================================================================= -->
        </div>
    </div>

    <!-- PENTING: Tambahkan library ApexCharts untuk membuat chart -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endsection