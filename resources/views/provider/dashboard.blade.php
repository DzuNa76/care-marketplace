<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Provider - Care Marketplace</title>
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
            Ini adalah dashboard provider Care Marketplace.
        </p>

        <div class="mt-8 grid gap-6 md:grid-cols-3">
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <h3 class="font-semibold text-gray-800">Profil Provider</h3>
                <p class="mt-2 text-sm text-gray-500">
                    Kelola informasi dan profil layanan Anda.
                </p>
            </div>

            <div class="rounded-xl bg-white p-6 shadow-sm">
                <h3 class="font-semibold text-gray-800">Verifikasi Dokumen</h3>
                <p class="mt-2 text-sm text-gray-500">
                    Unggah dokumen untuk proses verifikasi akun.
                </p>

                <a href="{{ route('provider.verification-documents') }}"
                    class="mt-4 inline-block text-sm font-semibold text-blue-700 hover:underline">
                    Kelola dokumen →
                </a>
            </div>

            <div class="rounded-xl bg-white p-6 shadow-sm">
                <h3 class="font-semibold text-gray-800">Permintaan Booking</h3>
                <p class="mt-2 text-sm text-gray-500">
                    Lihat permintaan pemesanan dari customer.
                </p>
            </div>
        </div>
    </main>

</body>
</html>
