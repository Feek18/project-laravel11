<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Conflict Prevention Demo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold text-center mb-8">🔍 QR Code Conflict Prevention Demo</h1>
        
        <div class="max-w-6xl mx-auto bg-white rounded-lg shadow-lg p-6">
            
            <!-- Header Information -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                <h2 class="text-xl font-semibold text-blue-800 mb-2">Enhanced QR Code System with Conflict Detection</h2>
                <p class="text-blue-700">
                    This demo shows how the QR code generation system now detects and prevents conflicts with existing bookings.
                    The system will show detailed conflict information and prevent QR generation for rooms that are already booked.
                </p>
            </div>

            <!-- Demo Scenarios -->
            <div class="grid md:grid-cols-2 gap-8">
                
                <!-- Scenario 1: No Conflicts -->
                <div class="border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-green-800 mb-4">✅ Scenario 1: Available Room</h3>
                    <div class="bg-green-50 border border-green-200 rounded p-4 mb-4">
                        <p class="text-sm text-green-700">
                            <strong>Expected Result:</strong> QR code generated successfully without any warnings.
                        </p>
                    </div>
                    
                    @auth
                    <form class="qr-demo-form" data-scenario="available">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Select Available Room:</label>
                            <select name="id_ruang" class="w-full border rounded px-3 py-2" required>
                                <option value="">Choose a room...</option>
                                @foreach(\App\Models\Ruangan::all() as $ruangan)
                                    <option value="{{ $ruangan->id_ruang }}">{{ $ruangan->nama_ruangan }} - {{ $ruangan->lokasi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Purpose:</label>
                            <input type="text" name="keperluan" class="w-full border rounded px-3 py-2" 
                                   value="Demo - Available Room Test" required>
                        </div>
                        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
                            Generate QR Code (Available Room)
                        </button>
                    </form>
                    @else
                    <div class="text-center p-4 bg-yellow-100 rounded">
                        <p class="text-yellow-800">Please <a href="{{ route('login') }}" class="text-blue-600 underline">login</a> to test QR generation</p>
                    </div>
                    @endauth
                    
                    <div class="qr-result mt-4 hidden"></div>
                </div>

                <!-- Scenario 2: Pending Conflicts -->
                <div class="border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-4">⚠️ Scenario 2: Pending Conflict</h3>
                    <div class="bg-yellow-50 border border-yellow-200 rounded p-4 mb-4">
                        <p class="text-sm text-yellow-700">
                            <strong>Expected Result:</strong> QR code generated with warning about pending conflicts.
                            Shows details of conflicting bookings.
                        </p>
                    </div>
                    
                    @auth
                    <!-- First create a pending booking -->
                    <div class="mb-4 p-3 bg-gray-50 rounded">
                        <p class="text-sm text-gray-600 mb-2"><strong>Step 1:</strong> Create a pending booking first</p>
                        <form class="create-pending-form" data-scenario="pending">
                            @csrf
                            <div class="grid grid-cols-2 gap-2">
                                <select name="id_ruang" class="border rounded px-2 py-1 text-sm" required>
                                    <option value="">Room...</option>
                                    @foreach(\App\Models\Ruangan::take(3)->get() as $ruangan)
                                        <option value="{{ $ruangan->id_ruang }}">{{ $ruangan->nama_ruangan }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="keperluan" value="Demo Pending Booking" 
                                       class="border rounded px-2 py-1 text-sm" required>
                            </div>
                            <button type="submit" class="mt-2 text-sm bg-gray-600 text-white px-3 py-1 rounded">
                                Create Pending Booking
                            </button>
                        </form>
                    </div>

                    <!-- Then try to generate QR for same time/room -->
                    <form class="qr-demo-form" data-scenario="pending-conflict">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Same Room as Above:</label>
                            <select name="id_ruang" class="w-full border rounded px-3 py-2" required>
                                <option value="">Choose same room...</option>
                                @foreach(\App\Models\Ruangan::take(3)->get() as $ruangan)
                                    <option value="{{ $ruangan->id_ruang }}">{{ $ruangan->nama_ruangan }} - {{ $ruangan->lokasi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Purpose:</label>
                            <input type="text" name="keperluan" class="w-full border rounded px-3 py-2" 
                                   value="Demo - Pending Conflict Test" required>
                        </div>
                        <button type="submit" class="w-full bg-yellow-600 text-white py-2 rounded hover:bg-yellow-700">
                            Generate QR Code (Expect Warning)
                        </button>
                    </form>
                    @else
                    <div class="text-center p-4 bg-yellow-100 rounded">
                        <p class="text-yellow-800">Please <a href="{{ route('login') }}" class="text-blue-600 underline">login</a> to test QR generation</p>
                    </div>
                    @endauth
                    
                    <div class="qr-result mt-4 hidden"></div>
                </div>

                <!-- Scenario 3: Approved Conflicts -->
                <div class="border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-red-800 mb-4">🚫 Scenario 3: Approved Conflict</h3>
                    <div class="bg-red-50 border border-red-200 rounded p-4 mb-4">
                        <p class="text-sm text-red-700">
                            <strong>Expected Result:</strong> QR generation blocked completely.
                            Shows error message with details of approved conflicting bookings.
                        </p>
                    </div>
                    
                    @auth
                    <!-- First create an approved booking -->
                    <div class="mb-4 p-3 bg-gray-50 rounded">
                        <p class="text-sm text-gray-600 mb-2"><strong>Step 1:</strong> Create an approved booking first</p>
                        <form class="create-approved-form" data-scenario="approved">
                            @csrf
                            <div class="grid grid-cols-2 gap-2">
                                <select name="id_ruang" class="border rounded px-2 py-1 text-sm" required>
                                    <option value="">Room...</option>
                                    @foreach(\App\Models\Ruangan::skip(3)->take(3)->get() as $ruangan)
                                        <option value="{{ $ruangan->id_ruang }}">{{ $ruangan->nama_ruangan }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="keperluan" value="Demo Approved Booking" 
                                       class="border rounded px-2 py-1 text-sm" required>
                            </div>
                            <button type="submit" class="mt-2 text-sm bg-green-600 text-white px-3 py-1 rounded">
                                Create Approved Booking
                            </button>
                        </form>
                    </div>

                    <!-- Then try to generate QR for same time/room -->
                    <form class="qr-demo-form" data-scenario="approved-conflict">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Same Room as Above:</label>
                            <select name="id_ruang" class="w-full border rounded px-3 py-2" required>
                                <option value="">Choose same room...</option>
                                @foreach(\App\Models\Ruangan::skip(3)->take(3)->get() as $ruangan)
                                    <option value="{{ $ruangan->id_ruang }}">{{ $ruangan->nama_ruangan }} - {{ $ruangan->lokasi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Purpose:</label>
                            <input type="text" name="keperluan" class="w-full border rounded px-3 py-2" 
                                   value="Demo - Approved Conflict Test" required>
                        </div>
                        <button type="submit" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">
                            Generate QR Code (Expect Block)
                        </button>
                    </form>
                    @else
                    <div class="text-center p-4 bg-yellow-100 rounded">
                        <p class="text-yellow-800">Please <a href="{{ route('login') }}" class="text-blue-600 underline">login</a> to test QR generation</p>
                    </div>
                    @endauth
                    
                    <div class="qr-result mt-4 hidden"></div>
                </div>

                <!-- Scenario 4: QR Scan with Conflicts -->
                <div class="border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-purple-800 mb-4">🔍 Scenario 4: QR Scan with Conflicts</h3>
                    <div class="bg-purple-50 border border-purple-200 rounded p-4 mb-4">
                        <p class="text-sm text-purple-700">
                            <strong>Expected Result:</strong> When scanning QR codes, conflict information is displayed
                            and approval is blocked if there are approved conflicts.
                        </p>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="text-sm text-gray-600">
                            <p><strong>Recent QR Codes to Scan:</strong></p>
                            @php
                                $recentQRs = \App\Models\Peminjaman::whereNotNull('qr_token')
                                    ->latest()
                                    ->take(3)
                                    ->get();
                            @endphp
                            
                            @forelse($recentQRs as $qr)
                                <div class="mt-2 p-2 bg-gray-50 rounded text-xs">
                                    <a href="{{ route('qr.scan', $qr->qr_token) }}" 
                                       class="text-blue-600 underline" target="_blank">
                                        {{ $qr->ruangan->nama_ruangan ?? 'N/A' }} - 
                                        {{ $qr->waktu_mulai }} to {{ $qr->waktu_selesai }} -
                                        {{ ucfirst($qr->status_persetujuan) }}
                                    </a>
                                </div>
                            @empty
                                <p class="text-gray-500 text-xs">No recent QR codes found. Generate some above first.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="mt-8 bg-gray-50 border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📋 How to Test</h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-medium text-gray-700 mb-2">Testing Steps:</h4>
                        <ol class="list-decimal list-inside space-y-1 text-sm text-gray-600">
                            <li>Start with Scenario 1 to see normal QR generation</li>
                            <li>In Scenario 2, first create a pending booking, then try QR generation for same room</li>
                            <li>In Scenario 3, first create an approved booking, then try QR generation for same room</li>
                            <li>Use Scenario 4 to scan generated QR codes and see conflict information</li>
                        </ol>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-700 mb-2">What to Look For:</h4>
                        <ul class="list-disc list-inside space-y-1 text-sm text-gray-600">
                            <li>Green success with no conflicts</li>
                            <li>Yellow warning with pending conflict details</li>
                            <li>Red error blocking QR generation</li>
                            <li>Conflict information in QR scan results</li>
                            <li>Disabled approval buttons when conflicts exist</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Handle QR generation forms
        document.querySelectorAll('.qr-demo-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const resultDiv = this.parentElement.querySelector('.qr-result');
                
                const data = {
                    id_ruang: formData.get('id_ruang'),
                    keperluan: formData.get('keperluan')
                };
                
                resultDiv.classList.remove('hidden');
                resultDiv.innerHTML = '<div class="text-blue-600">Generating QR code...</div>';
                
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
                        let resultHTML = `
                            <div class="border border-green-200 bg-green-50 rounded p-4">
                                <h4 class="font-medium text-green-800 mb-2">✅ QR Code Generated Successfully</h4>
                                <img src="${data.qr_code_url}" alt="QR Code" class="mx-auto mb-2" style="max-width: 150px;">
                                <p class="text-xs text-gray-600">Token: ${data.token}</p>
                        `;
                        
                        if (data.has_pending_conflict) {
                            resultHTML += `
                                <div class="mt-3 p-2 bg-yellow-100 border border-yellow-300 rounded">
                                    <p class="text-sm font-medium text-yellow-800">⚠️ Warning: Pending Conflicts</p>
                                    <p class="text-xs text-yellow-700">${data.conflict_warning}</p>
                                    ${data.conflicting_bookings ? `
                                        <ul class="mt-1 text-xs text-yellow-700 list-disc list-inside">
                                            ${data.conflicting_bookings.map(booking => `
                                                <li>${booking.pengguna} (${booking.waktu_mulai}-${booking.waktu_selesai}) - ${booking.status}</li>
                                            `).join('')}
                                        </ul>
                                    ` : ''}
                                </div>
                            `;
                        }
                        
                        resultHTML += '</div>';
                        resultDiv.innerHTML = resultHTML;
                    } else {
                        let errorHTML = `
                            <div class="border border-red-200 bg-red-50 rounded p-4">
                                <h4 class="font-medium text-red-800 mb-2">❌ QR Generation Blocked</h4>
                                <p class="text-sm text-red-700">${data.message}</p>
                        `;
                        
                        if (data.has_approved_conflict && data.conflicting_bookings) {
                            errorHTML += `
                                <div class="mt-2 p-2 bg-red-100 border border-red-300 rounded">
                                    <p class="text-xs font-medium text-red-800">Approved Conflicts:</p>
                                    <ul class="text-xs text-red-700 list-disc list-inside">
                                        ${data.conflicting_bookings.map(booking => `
                                            <li>${booking.pengguna} (${booking.waktu_mulai}-${booking.waktu_selesai}) - ${booking.status}</li>
                                        `).join('')}
                                    </ul>
                                </div>
                            `;
                        }
                        
                        errorHTML += '</div>';
                        resultDiv.innerHTML = errorHTML;
                    }
                })
                .catch(error => {
                    resultDiv.innerHTML = `<div class="text-red-500">Error: ${error.message}</div>`;
                });
            });
        });

        // Handle booking creation forms
        document.querySelectorAll('.create-pending-form, .create-approved-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const isPending = this.classList.contains('create-pending-form');
                
                const data = {
                    id_pengguna: 1, // Demo user
                    id_ruang: formData.get('id_ruang'),
                    keperluan: formData.get('keperluan'),
                    status_persetujuan: isPending ? 'pending' : 'disetujui',
                    tanggal_pinjam: new Date().toISOString().split('T')[0],
                    waktu_mulai: new Date().toTimeString().split(' ')[0].substring(0, 5),
                    waktu_selesai: new Date(Date.now() + 2*60*60*1000).toTimeString().split(' ')[0].substring(0, 5)
                };
                
                // Create booking via API (you might need to implement this endpoint)
                fetch('/api/demo/create-booking', {
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
                        const button = this.querySelector('button');
                        button.textContent = '✓ Created';
                        button.disabled = true;
                        button.classList.add('bg-gray-400');
                    }
                })
                .catch(error => {
                    console.error('Error creating booking:', error);
                });
            });
        });
    </script>
</body>
</html>
