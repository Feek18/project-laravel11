<x-app-layout>
    <x-slot name="header">
        <h2 class="font-normal text-xs text-gray-500 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header Section --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard KelasReady</h1>
                <p class="text-gray-600">Monitor dan kelola kegiatan pembelajaran kampus secara real-time</p>
            </div>
            
            {{-- Main Layout: Left Sidebar + Right Calendar --}}
            <div class="flex flex-col xl:flex-row gap-6">
                {{-- Left Sidebar: Statistics and Monitoring --}}
                <div class="xl:w-80 2xl:w-96 flex-shrink-0">
                    <div class="space-y-6">
                        {{-- Quick Stats Grid --}}
                        <div class="grid grid-cols-2 xl:grid-cols-1 gap-4">
                            {{-- Total Users --}}
                            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <x-icons.group-icon class="text-blue-600 size-5" />
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-600">Total Pengguna</p>
                                            <p class="text-xl font-bold text-gray-900">{{ number_format($stats['totalUsers']) }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            {{ $stats['userGrowth'] >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $stats['userGrowth'] >= 0 ? '+' : '' }}{{ $stats['userGrowth'] }}%
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Total Rooms --}}
                            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                            <x-icons.ruangan-kuliah-icon class="text-green-600 size-5" />
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-600">Ruangan</p>
                                            <p class="text-xl font-bold text-gray-900">{{ number_format($stats['totalRooms']) }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500">{{ $stats['roomUtilization'] }}% aktif</p>
                                        <p class="text-xs text-gray-400">hari ini</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Total Bookings --}}
                            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <x-icons.box-icon-line class="text-purple-600 size-5" />
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-600">Peminjaman</p>
                                            <p class="text-xl font-bold text-gray-900">{{ number_format($stats['totalPeminjaman']) }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            {{ $stats['peminjamanGrowth'] >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $stats['peminjamanGrowth'] >= 0 ? '+' : '' }}{{ $stats['peminjamanGrowth'] }}%
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Pending Approvals --}}
                            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-600">Pending</p>
                                            <p class="text-xl font-bold text-gray-900">{{ number_format($stats['peminjaman']['pending']) }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500">{{ $stats['peminjaman']['today'] }} hari ini</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="bg-white rounded-xl border border-gray-200 shadow-sm h-full">
                                <div class="p-6 border-b border-gray-200">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                        <div>
                                            <h2 class="text-xl font-semibold text-gray-900">Kalender Peminjaman & Jadwal</h2>
                                            <p class="text-sm text-gray-600 mt-1">Klik pada event untuk melihat detail lengkap</p>
                                        </div>
                                        {{-- Legend --}}
                                        <div class="flex flex-wrap items-center gap-4 text-sm">
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 bg-blue-500 rounded mr-2"></div>
                                                <span class="text-gray-600">Jadwal Kuliah</span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 bg-green-500 rounded mr-2"></div>
                                                <span class="text-gray-600">Disetujui</span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 bg-yellow-500 rounded mr-2"></div>
                                                <span class="text-gray-600">Pending</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <div id="calendar" style="height: calc(100vh - 300px); min-height: 600px;" class="rounded-lg"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Charts Section --}}
                        <div class="space-y-4">
                            {{-- Status Distribution Chart --}}
                            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Peminjaman</h3>
                                <div style="height: 200px;">
                                    <canvas id="statusPieChart"></canvas>
                                </div>
                            </div>

                            {{-- Monthly Trend Chart --}}
                            <div style="display:none" class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Trend 6 Bulan</h3>
                                <div style="height: 160px;">
                                    <canvas id="monthlyTrendChart"></canvas>
                                </div>
                            </div>
                        </div>

                        {{-- Recent Activity --}}
                        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
                                <div class="flex items-center space-x-2">
                                    <div class="flex space-x-1">
                                        <button onclick="filterActivity('all')" class="filter-btn active px-2 py-1 text-xs rounded-md bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors" data-filter="all">
                                            Semua
                                        </button>
                                        <button onclick="filterActivity('pending')" class="filter-btn px-2 py-1 text-xs rounded-md bg-yellow-100 text-yellow-700 hover:bg-yellow-200 transition-colors" data-filter="pending">
                                            Pending
                                        </button>
                                        <button onclick="filterActivity('disetujui')" class="filter-btn px-2 py-1 text-xs rounded-md bg-green-100 text-green-700 hover:bg-green-200 transition-colors" data-filter="disetujui">
                                            Disetujui
                                        </button>
                                        <button onclick="filterActivity('ditolak')" class="filter-btn px-2 py-1 text-xs rounded-md bg-red-100 text-red-700 hover:bg-red-200 transition-colors" data-filter="ditolak">
                                            Ditolak
                                        </button>
                                    </div>
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full" id="activity-count">
                                        {{ $stats['recentBookings']->count() }} items
                                    </span>
                                </div>
                            </div>
                            
                            {{-- Search Input --}}
                            <div class="mb-3">
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        id="activity-search" 
                                        placeholder="Cari berdasarkan nama pengguna atau ruangan..." 
                                        class="w-full pl-8 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                </div>
                            </div>
                            <div class="space-y-3 max-h-96 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 pr-2">
                                @forelse($stats['recentBookings'] as $booking)
                                    <div class="activity-item flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors border-l-4
                                        @if($booking->status_persetujuan === 'disetujui') border-green-400
                                        @elseif($booking->status_persetujuan === 'ditolak') border-red-400
                                        @else border-yellow-400 @endif" 
                                        id="booking-{{ $booking->id }}" 
                                        data-status="{{ $booking->status_persetujuan }}"
                                        data-user="{{ strtolower($booking->pengguna->nama ?? 'unknown') }}"
                                        data-room="{{ strtolower($booking->ruangan->nama_ruangan ?? 'unknown') }}"
                                        data-purpose="{{ strtolower($booking->keperluan) }}">
                                        
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center
                                                @if($booking->status_persetujuan === 'disetujui') bg-green-100
                                                @elseif($booking->status_persetujuan === 'ditolak') bg-red-100
                                                @else bg-yellow-100 @endif">
                                                @if($booking->status_persetujuan === 'disetujui')
                                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @elseif($booking->status_persetujuan === 'ditolak')
                                                    <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-900 truncate">
                                                        {{ $booking->pengguna->nama ?? 'Unknown User' }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 truncate">
                                                        {{ $booking->ruangan->nama_ruangan ?? 'Unknown Room' }} • {{ $booking->keperluan }}
                                                    </p>
                                                    <p class="text-xs text-gray-400 mt-1">
                                                        📅 {{ \Carbon\Carbon::parse($booking->tanggal_pinjam)->format('d M Y') }} • 
                                                        🕒 {{ $booking->waktu_mulai }} - {{ $booking->waktu_selesai }}
                                                    </p>
                                                    <p class="text-xs text-gray-400">
                                                        {{ $booking->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                                
                                                <div class="flex flex-col items-end space-y-2">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                        @if($booking->status_persetujuan === 'disetujui') bg-green-100 text-green-800
                                                        @elseif($booking->status_persetujuan === 'ditolak') bg-red-100 text-red-800
                                                        @else bg-yellow-100 text-yellow-800 @endif">
                                                        {{ ucfirst($booking->status_persetujuan) }}
                                                    </span>
                                                    
                                                    @if($booking->status_persetujuan === 'pending')
                                                        <div class="flex space-x-1">
                                                            <button 
                                                                onclick="quickApproval({{ $booking->id }}, 'disetujui')"
                                                                class="inline-flex items-center px-2 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded-md transition-colors duration-200 shadow-sm"
                                                                title="Setujui">
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </button>
                                                            <button style="background:red"
                                                                onclick="quickApproval({{ $booking->id }}, 'ditolak')"
                                                                class="inline-flex items-center px-2 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-md transition-colors duration-200 shadow-sm"
                                                                title="Tolak">
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8 text-gray-500">
                                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        <p class="text-sm">Belum ada aktivitas terbaru</p>
                                        <p class="text-xs text-gray-400 mt-1">Data peminjaman akan muncul di sini</p>
                                    </div>
                                @endforelse
                            </div>
                            
                            @if($stats['recentBookings']->count() >= 50)
                                <div class="mt-4 text-center">
                                    <p class="text-xs text-gray-500">
                                        Menampilkan 50 aktivitas terbaru • 
                                        <a href="{{ route('peminjam.index') }}" class="text-blue-600 hover:text-blue-800 underline">
                                            Lihat semua peminjaman →
                                        </a>
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- Popular Rooms --}}
                        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ruangan Teramai</h3>
                            <div class="space-y-3">
                                @forelse($stats['popularRooms']->take(5) as $index => $room)
                                    <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-r 
                                                @if($index === 0) from-yellow-400 to-orange-500
                                                @elseif($index === 1) from-gray-300 to-gray-400
                                                @elseif($index === 2) from-yellow-600 to-yellow-700
                                                @else from-blue-400 to-blue-500 @endif
                                                flex items-center justify-center text-white text-sm font-bold">
                                                {{ $index + 1 }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900 text-sm truncate">
                                                    {{ $room->ruangan->nama_ruangan ?? 'Unknown Room' }}
                                                </p>
                                                <p class="text-xs text-gray-500">{{ $room->total }} peminjaman</p>
                                            </div>
                                        </div>
                                        @if($index === 0)
                                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                @empty
                                    <div class="text-center py-6 text-gray-500">
                                        <svg class="w-10 h-10 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <p class="text-sm">Belum ada data</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- Charts JavaScript --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Pass data to JavaScript
        window.allEventsData = @json($allEvents);
        window.dashboardStats = @json($stats);
        
        document.addEventListener('DOMContentLoaded', function() {
            // Monthly Trend Chart
            const monthlyCtx = document.getElementById('monthlyTrendChart').getContext('2d');
            const monthlyLabels = window.dashboardStats.monthlyBookings.map(item => item.month.split(' ')[0]);
            const monthlyData = window.dashboardStats.monthlyBookings.map(item => item.count);
            
            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Peminjaman',
                        data: monthlyData,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                color: '#6B7280',
                                font: {
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#6B7280',
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });

            // Status Distribution Pie Chart
            const statusCtx = document.getElementById('statusPieChart').getContext('2d');
            const statusData = window.dashboardStats.statusDistribution;
            
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Disetujui', 'Ditolak'],
                    datasets: [{
                        data: [statusData.pending, statusData.disetujui, statusData.ditolak],
                        backgroundColor: [
                            '#F59E0B', // yellow for pending
                            '#10B981', // green for approved
                            '#EF4444'  // red for rejected
                        ],
                        borderWidth: 2,
                        borderColor: '#fff',
                        hoverBorderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    size: 11
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });

            // Live data refresh indicator
            const refreshInterval = setInterval(function() {
                // Optional: Add a subtle indicator that data is being monitored
                console.log('Dashboard monitoring active...');
            }, 60000); // Check every minute

            // Clean up on page unload
            window.addEventListener('beforeunload', function() {
                clearInterval(refreshInterval);
            });
        });

        // Quick Approval Function
        async function quickApproval(bookingId, status) {
            // Show loading state
            const bookingElement = document.getElementById(`booking-${bookingId}`);
            if (!bookingElement) {
                console.error('Booking element not found:', bookingId);
                return;
            }

            const buttons = bookingElement.querySelectorAll('button');
            buttons.forEach(btn => {
                btn.disabled = true;
                btn.innerHTML = '<svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            });

            try {
                console.log('Sending approval request for booking:', bookingId, 'with status:', status);
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    throw new Error('CSRF token not found');
                }

                const response = await fetch(`/dashboard/quick-approval/${bookingId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        status_persetujuan: status
                    })
                });

                console.log('Response status:', response.status);
                console.log('Response headers:', Object.fromEntries(response.headers.entries()));

                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const textResponse = await response.text();
                    console.error('Non-JSON response received:', textResponse);
                    throw new Error('Server returned invalid response format');
                }

                const data = await response.json();
                console.log('Response data:', data);

                if (response.ok && data.success) {
                    // Update the UI to reflect the new status
                    updateBookingStatus(bookingId, status);
                    
                    // Show success message using SweetAlert
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message,
                            icon: 'success',
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    }

                    // Refresh statistics after a short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || `Server error: ${response.status}`);
                }
            } catch (error) {
                console.error('Quick approval error:', error);
                
                let errorMessage = 'Terjadi kesalahan saat memproses permintaan';
                
                if (error.name === 'TypeError' && error.message.includes('Failed to fetch')) {
                    errorMessage = 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.';
                } else if (error.message) {
                    errorMessage = error.message;
                }
                
                // Show error message using SweetAlert
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Error!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } else {
                    alert(errorMessage);
                }

                // Restore button states
                buttons.forEach((btn, index) => {
                    btn.disabled = false;
                    if (index === 0) {
                        btn.innerHTML = '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
                    } else {
                        btn.innerHTML = '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>';
                    }
                });
            }
        }

        function updateBookingStatus(bookingId, status) {
            const bookingElement = document.getElementById(`booking-${bookingId}`);
            
            // Update border color
            bookingElement.className = bookingElement.className.replace(
                /border-(yellow|green|red)-400/g, 
                status === 'disetujui' ? 'border-green-400' : 'border-red-400'
            );
            
            // Update icon background
            const iconDiv = bookingElement.querySelector('.w-8.h-8');
            iconDiv.className = iconDiv.className.replace(
                /bg-(yellow|green|red)-100/g, 
                status === 'disetujui' ? 'bg-green-100' : 'bg-red-100'
            );
            
            // Update icon
            const icon = iconDiv.querySelector('svg');
            if (status === 'disetujui') {
                icon.outerHTML = '<svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
            } else {
                icon.outerHTML = '<svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>';
            }
            
            // Update status badge
            const statusBadge = bookingElement.querySelector('.inline-flex.items-center');
            statusBadge.className = statusBadge.className.replace(
                /bg-(yellow|green|red)-100 text-(yellow|green|red)-800/g, 
                status === 'disetujui' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
            );
            statusBadge.textContent = status === 'disetujui' ? 'Disetujui' : 'Ditolak';
            
            // Remove buttons
            const buttonContainer = bookingElement.querySelector('.flex.space-x-1');
            if (buttonContainer) {
                buttonContainer.remove();
            }
        }

        // Activity Filtering Function
        function filterActivity(status) {
            const activityItems = document.querySelectorAll('.activity-item');
            const filterButtons = document.querySelectorAll('.filter-btn');
            const activityCount = document.getElementById('activity-count');
            
            // Update button states
            filterButtons.forEach(btn => {
                btn.classList.remove('active', 'bg-blue-100', 'text-blue-700');
                const baseClasses = ['px-2', 'py-1', 'text-xs', 'rounded-md', 'transition-colors'];
                if (btn.dataset.filter === status) {
                    btn.classList.add('active', 'bg-blue-100', 'text-blue-700', ...baseClasses);
                } else {
                    // Reset to original color based on filter type
                    btn.classList.add(...baseClasses);
                    switch(btn.dataset.filter) {
                        case 'pending':
                            btn.classList.add('bg-yellow-100', 'text-yellow-700', 'hover:bg-yellow-200');
                            break;
                        case 'disetujui':
                            btn.classList.add('bg-green-100', 'text-green-700', 'hover:bg-green-200');
                            break;
                        case 'ditolak':
                            btn.classList.add('bg-red-100', 'text-red-700', 'hover:bg-red-200');
                            break;
                        default:
                            btn.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
                    }
                }
            });
            
            let visibleCount = 0;
            
            // Filter items using data attributes
            activityItems.forEach(item => {
                const itemStatus = item.dataset.status;
                
                if (status === 'all' || itemStatus === status) {
                    item.style.display = 'flex';
                    item.classList.add('fade-in');
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                    item.classList.remove('fade-in');
                }
            });
            
            // Update count
            activityCount.textContent = `${visibleCount} items`;
            
            // Scroll to top of activity list
            const activityContainer = document.querySelector('.scrollbar-thin');
            if (activityContainer) {
                activityContainer.scrollTop = 0;
            }
        }

        // Enhanced search functionality
        function searchActivity(searchTerm) {
            const activityItems = document.querySelectorAll('.activity-item');
            const activityCount = document.getElementById('activity-count');
            let visibleCount = 0;
            
            const searchLower = searchTerm.toLowerCase().trim();
            
            activityItems.forEach(item => {
                const userName = item.dataset.user;
                const roomName = item.dataset.room;
                const purpose = item.dataset.purpose;
                
                const matchesSearch = searchLower === '' || 
                    userName.includes(searchLower) || 
                    roomName.includes(searchLower) || 
                    purpose.includes(searchLower);
                
                if (matchesSearch) {
                    item.style.display = 'flex';
                    item.classList.add('fade-in');
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                    item.classList.remove('fade-in');
                }
            });
            
            activityCount.textContent = `${visibleCount} items`;
            
            // Highlight search terms if any
            if (searchTerm.trim()) {
                highlightSearchTerm(searchTerm);
            } else {
                removeHighlights();
            }
        }

        // Highlight search terms in visible items
        function highlightSearchTerm(term) {
            removeHighlights();
            const visibleItems = document.querySelectorAll('.activity-item[style*="flex"]');
            const regex = new RegExp(`(${term})`, 'gi');
            
            visibleItems.forEach(item => {
                const textElements = item.querySelectorAll('.text-sm, .text-xs');
                textElements.forEach(el => {
                    if (el.classList.contains('text-gray-400') || el.classList.contains('rounded-full')) return;
                    const originalText = el.textContent;
                    const highlightedText = originalText.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>');
                    if (highlightedText !== originalText) {
                        el.innerHTML = highlightedText;
                    }
                });
            });
        }

        // Remove search highlights
        function removeHighlights() {
            const highlights = document.querySelectorAll('.activity-item mark');
            highlights.forEach(mark => {
                const parent = mark.parentNode;
                parent.replaceChild(document.createTextNode(mark.textContent), mark);
                parent.normalize();
            });
        }

        // Auto-refresh functionality
        let refreshInterval;
        function startAutoRefresh() {
            refreshInterval = setInterval(() => {
                // Check if there are any pending approvals
                const pendingItems = document.querySelectorAll('[data-status="pending"]');
                if (pendingItems.length > 0) {
                    console.log(`Monitoring ${pendingItems.length} pending approvals...`);
                }
            }, 30000); // Check every 30 seconds
        }

        function stopAutoRefresh() {
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
        }

        // Initialize auto-refresh on page load
        document.addEventListener('DOMContentLoaded', function() {
            startAutoRefresh();
            
            // Stop refresh when page is about to unload
            window.addEventListener('beforeunload', stopAutoRefresh);
            
            // Add search input event listener if exists
            const searchInput = document.getElementById('activity-search');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    searchActivity(e.target.value);
                });
            }
        });
    </script>
</x-app-layout>
