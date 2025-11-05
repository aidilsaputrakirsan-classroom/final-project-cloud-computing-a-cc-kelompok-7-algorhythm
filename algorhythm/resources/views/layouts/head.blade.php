<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Libranation')</title>
<link rel="shortcut icon" type="image/png" href="{{ asset('img/logoTitle.png') }}" />

<!-- Ini adalah CSS utama dari template Anda -->
<link rel="stylesheet" href="{{ asset('/assets/css/styles.min.css') }}" />

<!-- Bootstrap (dari file Anda) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    xintegrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<!-- Datatables (dari file Anda) -->
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>

<!-- Bootstrap Icons (dari file Anda) -->
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

<!-- Tabler Icons (Dibutuhkan oleh sidebar) -->
<link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.47.0/tabler-icons.min.css" rel="stylesheet">
    
<!-- Animate.css (dari file header Anda) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<!-- Untuk CSS tambahan per halaman -->
@stack('head')