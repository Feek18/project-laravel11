<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('css/qr-conflicts.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">🚀 QR Code System Test & Demo</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Test the enhanced QR code generation system with advanced conflict detection and prevention.
            </p>
            <div class="mt-4 flex justify-center space-x-4">
                <a href="{{ route('qr.conflict.demo') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    🔍 View Conflict Demo
                </a>
                <a href="{{ url('/') }}" 
                   class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
                    🏠 Back to Home
                </a>
            </div>
        </div>
        
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
            .then(data => {                if (data.success) {
                    document.getElementById('results').classList.remove('hidden');
                    let resultHTML = `
                        <div class="qr-generation-result qr-conflict-success border border-green-200 bg-green-50 rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <h4 class="font-medium text-green-800">QR Code Generated Successfully</h4>
                            </div>
                            <div class="qr-code-display ${data.has_pending_conflict ? 'has-warning' : ''} mb-3">
                                <img src="${data.qr_code_url}" alt="QR Code" class="mx-auto" style="max-width: 200px;">
                            </div>
                            <p class="text-sm text-gray-600 text-center mb-2">Token: <code class="bg-gray-200 px-2 py-1 rounded text-xs">${data.token}</code></p>
                            <div class="text-center">
                                <a href="${data.qr_code_url}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm underline">View Full Size</a>
                            </div>
                    `;
                    
                    if (data.has_pending_conflict) {
                        resultHTML += `
                            <div class="mt-4 qr-conflict-warning border border-yellow-300 bg-yellow-50 rounded-lg p-3">
                                <div class="flex items-center mb-2">
                                    <svg class="w-4 h-4 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-medium text-yellow-800">Pending Conflicts Detected</span>
                                </div>
                                <p class="text-sm text-yellow-700 mb-2">${data.conflict_warning}</p>
                                ${data.conflicting_bookings ? `
                                    <div class="text-sm">
                                        <p class="font-medium text-yellow-800 mb-1">Conflicting Bookings:</p>
                                        <div class="space-y-1">
                                            ${data.conflicting_bookings.map(booking => `
                                                <div class="flex items-center justify-between bg-yellow-100 rounded px-2 py-1">
                                                    <span class="text-yellow-700">${booking.pengguna}</span>
                                                    <span class="text-yellow-600 text-xs">${booking.waktu_mulai} - ${booking.waktu_selesai}</span>
                                                    <span class="conflict-badge pending">${booking.status}</span>
                                                </div>
                                            `).join('')}
                                        </div>
                                    </div>
                                ` : ''}
                            </div>
                        `;
                    }
                    
                    resultHTML += '</div>';
                    document.getElementById('qrResult').innerHTML = resultHTML;
                } else {
                    document.getElementById('results').classList.remove('hidden');
                    let errorHTML = `
                        <div class="qr-generation-result qr-conflict-error border border-red-200 bg-red-50 rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <h4 class="font-medium text-red-800">QR Generation Blocked</h4>
                            </div>
                            <p class="text-sm text-red-700 mb-3">${data.message}</p>
                    `;
                    
                    if (data.has_approved_conflict && data.conflicting_bookings) {
                        errorHTML += `
                            <div class="border border-red-300 bg-red-100 rounded-lg p-3">
                                <p class="font-medium text-red-800 mb-2">Approved Conflicts:</p>
                                <div class="space-y-1">
                                    ${data.conflicting_bookings.map(booking => `
                                        <div class="flex items-center justify-between bg-red-200 rounded px-2 py-1">
                                            <span class="text-red-700">${booking.pengguna}</span>
                                            <span class="text-red-600 text-xs">${booking.waktu_mulai} - ${booking.waktu_selesai}</span>
                                            <span class="conflict-badge approved">${booking.status}</span>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `;
                    }
                    
                    errorHTML += '</div>';
                    document.getElementById('qrResult').innerHTML = errorHTML;
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
