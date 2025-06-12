<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold text-center mb-8">QR Code Room Borrowing Test</h1>
        
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-6">
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Admin Section -->
                <div class="border-r pr-8">
                    <h2 class="text-xl font-semibold mb-4">Admin - Generate Room QR</h2>
                    
                    <form id="generateRoomQR" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium mb-2">Select Room:</label>
                            <select name="ruangan_id" class="w-full border rounded px-3 py-2" required>
                                <option value="">Select Room</option>
                                @foreach(\App\Models\Ruangan::all() as $ruangan)
                                    <option value="{{ $ruangan->id_ruang }}">{{ $ruangan->nama_ruangan }} - {{ $ruangan->lokasi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                            Generate Room QR Code
                        </button>
                    </form>
                </div>

                <!-- User Section -->
                <div class="pl-8">
                    <h2 class="text-xl font-semibold mb-4">User - Instant QR Borrowing</h2>
                    
                    @auth
                    <form id="generateInstantQR" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium mb-2">Select Room:</label>
                            <select name="id_ruang" class="w-full border rounded px-3 py-2" required>
                                <option value="">Select Room</option>
                                @foreach(\App\Models\Ruangan::all() as $ruangan)
                                    <option value="{{ $ruangan->id_ruang }}">{{ $ruangan->nama_ruangan }} - {{ $ruangan->lokasi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Purpose:</label>
                            <input type="text" name="keperluan" class="w-full border rounded px-3 py-2" placeholder="Meeting, Class, etc." required>
                        </div>
                        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
                            Generate Instant QR Code
                        </button>
                    </form>
                    @else
                    <div class="text-center p-4 bg-yellow-100 rounded">
                        <p class="text-yellow-800">Please <a href="{{ route('login') }}" class="text-blue-600 underline">login</a> to test instant QR generation</p>
                    </div>
                    @endauth
                </div>
            </div>

            <!-- Results Section -->
            <div id="results" class="mt-8 hidden">
                <h3 class="text-lg font-semibold mb-4">Generated QR Code:</h3>
                <div id="qrResult" class="text-center p-4 border rounded"></div>
            </div>
        </div>

        <!-- Test Links -->
        <div class="max-w-4xl mx-auto mt-8 bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Test QR Scan Links</h2>
            <div class="space-y-2">
                <p><strong>Room Direct Borrow:</strong> <a href="{{ route('qr.room.borrow', 1) }}" class="text-blue-600 underline" target="_blank">Test Room 1 Borrowing</a></p>
                <p><strong>Recent Borrowings:</strong></p>
                <ul class="ml-4 space-y-1">
                    @foreach(\App\Models\Peminjaman::whereNotNull('qr_token')->latest()->take(3)->get() as $peminjaman)
                        <li>
                            <a href="{{ route('qr.scan', $peminjaman->qr_token) }}" class="text-blue-600 underline" target="_blank">
                                {{ $peminjaman->ruangan->nama_ruangan ?? 'N/A' }} - Token: {{ substr($peminjaman->qr_token, 0, 8) }}...
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <script>
        // Room QR Generation
        document.getElementById('generateRoomQR').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('{{ route("qr.generate.room") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(html => {
                // Open in new window
                const newWindow = window.open('', '_blank');
                newWindow.document.write(html);
                newWindow.document.close();
            })
            .catch(error => {
                alert('Error: ' + error.message);
            });
        });

        // Instant QR Generation
        @auth
        document.getElementById('generateInstantQR').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            const data = {
                id_ruang: formData.get('id_ruang'),
                keperluan: formData.get('keperluan')
            };
            
            fetch('{{ route("qr.generate.instant") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('results').classList.remove('hidden');
                    document.getElementById('qrResult').innerHTML = `
                        <img src="${data.qr_code_url}" alt="QR Code" class="mx-auto mb-4" style="max-width: 200px;">
                        <p class="text-sm text-gray-600">Token: ${data.token}</p>
                        <p class="text-sm text-green-600">QR Code generated successfully!</p>
                        <a href="${data.qr_code_url}" target="_blank" class="text-blue-600 underline">View Full Size</a>
                    `;
                } else {
                    alert('Error generating QR code');
                }
            })
            .catch(error => {
                alert('Error: ' + error.message);
            });
        });
        @endauth
    </script>
</body>
</html>
