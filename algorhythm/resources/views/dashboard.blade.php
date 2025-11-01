<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            
            <h1 class="text-3xl font-bold text-gray-900">
                Selamat Datang di Dashboard!
            </h1>

            <!-- Menampilkan nama user yang sedang login -->
            <p class="mt-4 text-lg text-gray-700">
                Anda login sebagai: <span class="font-medium text-indigo-600">{{ Auth::user()->name }}</span>
            </p>
            <p class="text-gray-600">
                Email: {{ Auth::user()->email }}
            </p>

            <!-- Tombol Logout -->
            <!-- HARUS menggunakan <form> dengan method POST untuk keamanan -->
            <form action="{{ route('logout') }}" method="POST" class="mt-6">
                @csrf
                <button 
                    type="submit"
                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500"
                >
                    Logout
                </button>
            </form>

        </div>
    </div>
</body>
</html>
