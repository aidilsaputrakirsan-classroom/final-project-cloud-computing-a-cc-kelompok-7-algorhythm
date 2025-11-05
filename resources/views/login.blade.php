<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Perpustakaan Mini</title>
  <!-- Memuat Tailwind CSS dari CDN (Karena kita tidak pakai Vite/NPM) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Memuat font Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
  
  <script>
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
<body class="h-full bg-gray-100 flex items-center justify-center antialiased">

<div class="flex min-h-full w-full flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 max-w-md">
  
  <div class="bg-white shadow-xl rounded-2xl p-8 sm:p-10">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      
      <!-- Logo Buku SVG -->
      <svg class="mx-auto h-12 w-auto text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" />
      </svg>
      
      <h2 class="mt-6 text-center text-3xl font-bold leading-9 tracking-tight text-gray-900">
        Selamat Datang!
      </h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        Silakan masuk untuk mengakses perpustakaan mini.
      </p>
    </div>

    <div class="mt-10">

      <!-- ============================================== -->
      <!-- PERUBAHAN PENTING ADA DI <form> INI          -->
      <!-- 1. method="POST"                             -->
      <!-- 2. action="{{ route('login.authenticate') }}" -->
      <!-- ============================================== -->
      <form action="{{ route('login.authenticate') }}" method="POST" class="space-y-6">
        
        <!-- Token Keamanan Laravel (WAJIB) -->
        @csrf

        <!-- Menampilkan Pesan Error Jika Login Gagal -->
        @if(session('error'))
            <div class="p-4 rounded-md bg-red-50 border border-red-200">
                <p class="text-sm font-medium text-red-700">
                    {{ session('error') }}
                </p>
            </div>
        @endif
        
        <!-- Input Email -->
        <div>
          <label for="email" class="block text-sm font-medium leading-6 text-gray-700">Alamat email</label>
          <div class="mt-2">
            <input 
              id="email" 
              type="email" 
              name="email" 
              required 
              autocomplete="email" 
              value="{{ old('email') }}" {{-- Mengingat email lama jika login gagal --}}
              class="block w-full rounded-lg border-0 py-2.5 px-3 bg-white text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              placeholder="anda@email.com"
            />
          </div>
        </div>

        <!-- Input Password -->
        <div>
          <div class="flex items-center justify-between">
            <label for="password" class="block text-sm font-medium leading-6 text-gray-700">Password</label>
            <div class="text-sm">
              <!-- Anda bisa tambahkan link "lupa password" manual nanti jika perlu -->
            </div>
          </div>
          <div class="mt-2">
            <input 
              id="password" 
              type="password" 
              name="password" 
              required 
              autocomplete="current-password" 
              class="block w-full rounded-lg border-0 py-2.5 px-3 bg-white text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              placeholder="••••••••"
            />
          </div>
        </div>

        <!-- Checkbox "Remember Me" -->
        <div class="flex items-center">
            <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
            <label for="remember" class="ml-2 block text-sm text-gray-900">Ingat saya</label>
        </div>

        <!-- Tombol Sign In -->
        <div>
          <button 
            type="submit" 
            class="flex w-full justify-center rounded-lg bg-indigo-600 px-3 py-2.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
          >
            Masuk
          </button>
        </div>
      </form>

      <!-- Anda bisa tambahkan link ke halaman register manual nanti jika perlu -->
      <p class="mt-10 text-center text-sm text-gray-500">
        Belum punya akun?
        <a href="#" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">
          Hubungi administrator
        </a>
      </p>
    </div>
  </div>
  
</div>
  
</body>
</html>
