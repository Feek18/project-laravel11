<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KelasReady | Website</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-poppins">
    {{-- navbar --}}
    @include('layouts.header')

    {{-- detail ruangan --}}
    <section>
        <div class="container px-4 sm:px-6 lg:px-20 mt-10">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-2">
                <h1 class="text-2xl md:text-4xl font-semibold">Detail Ruangan</h1>
                <p class="text-sm md:text-md text-gray-600">Prodi D4 Teknologi Rekayasa Perangkat Lunak</p>
            </div>

            <div class="mt-10">
                <div class="flex flex-col lg:flex-row gap-8">
                    <div class="flex-1">
                        <div
                            class="rounded-xl overflow-hidden shadow-lg transition-transform duration-300 hover:scale-105">
                            <img class="w-full h-[300px] md:h-[420px] object-cover object-center"
                                src="{{ $ruangan->gambar }}" alt="Room Image">
                        </div>
                        <p class="text-sm text-gray-500 mt-4">{{ $ruangan->lokasi }}</p>
                        <h2 class="text-2xl lg:text-4xl font-semibold mt-1">{{ $ruangan->nama_ruangan }}</h2>
                    </div>

                    <div class="w-full lg:w-[400px] bg-white shadow-md rounded-2xl p-6">
                        <h3 class="text-xl font-semibold">Form Peminjaman Ruangan</h3>
                        <p class="text-sm text-gray-600 mb-4">Silakan isi form peminjaman ruangan di bawah ini</p>
                        <form>
                            <div class="mb-4">
                                <label for="nama_pengguna" class="block text-sm font-medium text-gray-700">Nama
                                    Pengguna</label>
                                <input type="text" id="nama_pengguna" name="nama_pengguna"
                                    class="mt-1 block w-full rounded-lg border border-gray-300 p-2.5 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Masukkan nama anda" required />
                            </div>

                            <div class="mb-4">
                                <label for="nama_ruangan" class="block mb-2 text-sm font-medium text-gray-700">Nama
                                    Ruangan</label>
                                <select id="nama_ruangan" name="nama_ruangan"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option selected>Pilih ruangan</option>
                                    <option value="US">United States</option>
                                    <option value="CA">Canada</option>
                                    <option value="FR">France</option>
                                    <option value="DE">Germany</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700">Tanggal
                                    Pinjam</label>
                                <input type="date" name="tanggal_pinjam " id="tanggal_pinjam"
                                    class="mt-1 block w-full rounded-lg border border-gray-300 p-2.5 focus:ring-blue-500 focus:border-blue-500"
                                    required />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="mb-4">
                                    <label for="waktu_mulai" class="block text-sm font-medium text-gray-700">Waktu
                                        Mulai</label>
                                    <input type="datetime" name="waktu_mulai " id="waktu_mulai"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 p-2.5 focus:ring-blue-500 focus:border-blue-500"
                                        required />
                                </div>

                                <div class="mb-4">
                                    <label for="waktu_selesai" class="block text-sm font-medium text-gray-700">Waktu
                                        Selesai</label>
                                    <input type="datetime" name="waktu_selesai " id="waktu_selesai"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 p-2.5 focus:ring-blue-500 focus:border-blue-500"
                                        required />
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="keperluan" class="block text-sm font-medium text-gray-700">Keperluan
                                    Pinjam</label>
                                <textarea id="keperluan" name="keperluan" rows="4"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500
                                    placeholder="Masukkan
                                    keperluan anda"></textarea>
                            </div>

                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition duration-300">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
