<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Akun - Perpustakaan Mini</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
  <script>
    tailwind.config = {
      theme: { extend: { fontFamily: { inter: ['Inter', 'sans-serif'], }, } }
    }
  </script>
</head>
<body class="h-full bg-gray-100 flex items-center justify-center antialiased">

<div class="flex min-h-full w-full flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 max-w-md">
  
  <div class="bg-white shadow-xl rounded-2xl p-8 sm:p-10">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <svg class="mx-auto h-12 w-auto text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
      </svg>
      
      <h2 class="mt-6 text-center text-3xl font-bold leading-9 tracking-tight text-gray-900">
        Buat Akun Baru
      </h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        Bergabunglah untuk mulai meminjam buku.
      </p>
    </div>

    <div class="mt-10">
      <form action="{{ route('register.perform') }}" method="POST" class="space-y-6">
        @csrf

        @if ($errors->any())
            <div class="p-4 rounded-md bg-red-50 border border-red-200">
                <ul class="list-disc list-inside text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div>
          <label for="name" class="block text-sm font-medium leading-6 text-gray-700">Nama Lengkap</label>
          <div class="mt-2">
            <input id="name" type="text" name="name" required value="{{ old('name') }}" class="block w-full rounded-lg border-0 py-2.5 px-3 bg-white text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Nama Anda">
          </div>
        </div>

        <div>
          <label for="email" class="block text-sm font-medium leading-6 text-gray-700">Alamat Email</label>
          <div class="mt-2">
            <input id="email" type="email" name="email" required value="{{ old('email') }}" class="block w-full rounded-lg border-0 py-2.5 px-3 bg-white text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="anda@email.com">
          </div>
        </div>

        <div>
          <label for="password" class="block text-sm font-medium leading-6 text-gray-700">Password</label>
          <div class="mt-2">
            <input id="password" type="password" name="password" required class="block w-full rounded-lg border-0 py-2.5 px-3 bg-white text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="••••••••">
          </div>
        </div>

        <div>
          <label for="password_confirmation" class="block text-sm font-medium leading-6 text-gray-700">Konfirmasi Password</label>
          <div class="mt-2">
            <input id="password_confirmation" type="password" name="password_confirmation" required class="block w-full rounded-lg border-0 py-2.5 px-3 bg-white text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="••••••••">
          </div>
        </div>

        <div>
          <button type="submit" class="flex w-full justify-center rounded-lg bg-indigo-600 px-3 py-2.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            Daftar Sekarang
          </button>
        </div>
      </form>

      <p class="mt-10 text-center text-sm text-gray-500">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Masuk di sini</a>
      </p>
    </div>
  </div>
</div>
</body>
</html>