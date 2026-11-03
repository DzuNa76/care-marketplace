<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Customer - Care Marketplace</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100">

    <header class="bg-white shadow-sm">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <h1 class="text-xl font-bold text-blue-700">
                Care Marketplace
            </h1>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="rounded-lg bg-red-600 px-4 py-2 text-sm text-white hover:bg-red-700">
                    Logout
                </button>
            </form>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-6 py-10">
        <h2 class="text-2xl font-bold text-gray-800">
            Selamat datang, {{ auth()->user()->name }}!
        </h2>

        <p class="mt-2 text-gray-600">
            Ini adalah dashboard customer Care Marketplace.
        </p>

        <div class="mt-8 grid gap-6 md:grid-cols-3">
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <h3 class="font-semibold text-gray-800">Booking Saya</h3>
                <p class="mt-2 text-sm text-gray-500">
                    Lihat dan kelola pemesanan layanan Anda.
                </p>
            </div>

            <div class="rounded-xl bg-white p-6 shadow-sm">
                <h3 class="font-semibold text-gray-800">Cari Layanan</h3>
                <p class="mt-2 text-sm text-gray-500">
                    Temukan caregiver, babysitter, perawat, dan cleaning service.
                </p>
            </div>

            <div class="rounded-xl bg-white p-6 shadow-sm">
                <h3 class="font-semibold text-gray-800">Profil Saya</h3>
                <p class="mt-2 text-sm text-gray-500">
                    Kelola informasi akun dan profil Anda.
                </p>
            </div>
        </div>
    </main>

</body>
</html>
