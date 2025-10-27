<!DOCTYPE html>
<!-- Mengubah ke mode terang (menghapus kelas 'dark') -->
<html lang="id" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perpustakaan Mini</title>
  <!-- Memuat Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Memuat font Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    /* Menerapkan font Inter sebagai default */
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
  
  <script>
    // Konfigurasi Tailwind
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            inter: ['Inter', 'sans-serif'],
          },
        }
      }
    }
  </script>
</head>
<!-- Mengganti latar belakang ke abu-abu terang -->
<body class="h-full bg-gray-100 flex items-center justify-center antialiased">

<div class="flex min-h-full w-full flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 max-w-md">
  
  <!-- Kontainer Form Login, ganti ke latar belakang putih -->
  <div class="bg-white shadow-xl rounded-2xl p-8 sm:p-10">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      
      <!-- Logo Buku SVG -->
      <svg class="mx-auto h-12 w-auto text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" />
      </svg>
      
      <!-- Judul Form Baru -->
      <h2 class="mt-6 text-center text-3xl font-bold leading-9 tracking-tight text-gray-900">
        Selamat Datang!
      </h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        Silakan masuk untuk mengakses perpustakaan mini.
      </p>
    </div>

    <div class="mt-10">
      <form action="#" method="POST" class="space-y-6">
        
        <!-- Input Email -->
        <div>
          <!-- Ganti warna label -->
          <label for="email" class="block text-sm font-medium leading-6 text-gray-700">Alamat email</label>
          <div class="mt-2">
            <input 
              id="email" 
              type="email" 
              name="email" 
              required 
              autocomplete="email" 
              class="block w-full rounded-lg border-0 py-2.5 px-3 bg-white text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition duration-150 ease-in-out"
              placeholder="anda@email.com"
            />
          </div>
        </div>

        <!-- Input Password -->
        <div>
          <div class="flex items-center justify-between">
            <!-- Ganti warna label -->
            <label for="password" class="block text-sm font-medium leading-6 text-gray-700">Password</label>
            <div class="text-sm">
              <!-- Ganti warna link -->
              <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500 transition duration-150 ease-in-out">Lupa password?</a>
            </div>
          </div>
          <div class="mt-2">
            <input 
              id="password" 
              type="password" 
              name="password" 
              required 
              autocomplete="current-password" 
              class="block w-full rounded-lg border-0 py-2.5 px-3 bg-white text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition duration-150 ease-in-out"
              placeholder="••••••••"
            />
          </div>
        </div>

        <!-- Tombol Sign In -->
        <div>
          <button 
            type="submit" 
            class="flex w-full justify-center rounded-lg bg-indigo-600 px-3 py-2.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition duration-150 ease-in-out"
          >
            Masuk
          </button>
        </div>
      </form>

      <!-- Link Daftar -->
      <!-- Ganti warna teks -->
      <p class="mt-10 text-center text-sm text-gray-500">
        Belum punya akun?
        <a href="#" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500 transition duration-150 ease-in-out">
          Mulai uji coba gratis
        </a>
      </p>
    </div>
  </div>
  
</div>
  
</body>
</html>
