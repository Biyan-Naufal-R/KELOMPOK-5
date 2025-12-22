@extends('layouts.rs')

@section('title', 'Daftar Permintaan')
@section('header', 'Daftar Permintaan Darah')

@section('content')
<div class="space-y-6">
    <!-- Filter Tabs -->
    <div class="border-b border-gray-200">
        <nav class="flex space-x-8" aria-label="Tabs">
            <button onclick="filterRequests('all')" class="tab-filter py-4 px-1 border-b-2 font-medium text-sm {{ request('status') == null ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <span>Semua</span>
                <span class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2 rounded-full text-xs">{{ $requests->count() }}</span>
            </button>
            <button onclick="filterRequests('pending')" class="tab-filter py-4 px-1 border-b-2 font-medium text-sm {{ request('status') == 'pending' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <span>Menunggu</span>
                <span class="ml-2 bg-yellow-100 text-yellow-800 py-0.5 px-2 rounded-full text-xs">{{ $requests->where('status', 'pending')->count() }}</span>
            </button>
            <button onclick="filterRequests('approved')" class="tab-filter py-4 px-1 border-b-2 font-medium text-sm {{ request('status') == 'approved' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <span>Disetujui</span>
                <span class="ml-2 bg-green-100 text-green-800 py-0.5 px-2 rounded-full text-xs">{{ $requests->where('status', 'approved')->count() }}</span>
            </button>
            <button onclick="filterRequests('processed')" class="tab-filter py-4 px-1 border-b-2 font-medium text-sm {{ request('status') == 'processed' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <span>Diproses</span>
                <span class="ml-2 bg-blue-100 text-blue-800 py-0.5 px-2 rounded-full text-xs">{{ $requests->where('status', 'processed')->count() }}</span>
            </button>
            <button onclick="filterRequests('rejected')" class="tab-filter py-4 px-1 border-b-2 font-medium text-sm {{ request('status') == 'rejected' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <span>Ditolak</span>
                <span class="ml-2 bg-red-100 text-red-800 py-0.5 px-2 rounded-full text-xs">{{ $requests->where('status', 'rejected')->count() }}</span>
            </button>
        </nav>
    </div>

    <!-- Search and Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" id="searchInput" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Cari permintaan...">
            </div>
        </div>
        <div class="flex items-center justify-end gap-3">
            <select id="urgencyFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Urgensi</option>
                <option value="normal">Normal</option>
                <option value="urgent">Urgent</option>
                <option value="emergency">Emergency</option>
            </select>
            <a href="{{ route('rs.create-request') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center">
                <i class="fas fa-plus mr-2"></i> Baru
            </a>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Golongan Darah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urgensi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="requestsTable">
                    @foreach($requests as $request)
                    <tr class="hover:bg-gray-50 request-row" data-status="{{ $request->status }}" data-urgency="{{ $request->urgency }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ str_pad($request->id, 6, '0', STR_PAD_LEFT) }}</div>
                            @if($request->patient_info)
                            <div class="text-xs text-gray-500 mt-1 truncate max-w-xs">{{ Str::limit($request->patient_info, 40) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                    <span class="text-red-600 font-bold">{{ $request->blood_type }}</span>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $request->blood_type }}{{ $request->rhesus == 'positive' ? '+' : '-' }}</div>
                                    <div class="text-xs text-gray-500">Rhesus {{ $request->rhesus == 'positive' ? 'Positif' : 'Negatif' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $request->quantity }}</div>
                            <div class="text-xs text-gray-500">kantong</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($request->urgency == 'emergency')
                            <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-800 font-medium">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Emergency
                            </span>
                            @elseif($request->urgency == 'urgent')
                            <span class="px-3 py-1 text-xs rounded-full bg-orange-100 text-orange-800 font-medium">
                                <i class="fas fa-clock mr-1"></i> Urgent
                            </span>
                            @else
                            <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-800 font-medium">
                                <i class="fas fa-check mr-1"></i> Normal
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($request->status == 'approved')
                            <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800 font-medium">
                                <i class="fas fa-check mr-1"></i> Disetujui
                            </span>
                            @elseif($request->status == 'rejected')
                            <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-800 font-medium">
                                <i class="fas fa-times mr-1"></i> Ditolak
                                @if($request->rejection_reason)
                                <div class="text-xs text-red-600 mt-1" title="{{ $request->rejection_reason }}">
                                    <i class="fas fa-comment mr-1"></i> {{ Str::limit($request->rejection_reason, 30) }}
                                </div>
                                @endif
                            </span>
                            @elseif($request->status == 'pending')
                            <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 font-medium">
                                <i class="fas fa-clock mr-1"></i> Menunggu
                            </span>
                            @elseif($request->status == 'processed')
                            <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-800 font-medium">
                                <i class="fas fa-truck mr-1"></i> Diproses
                            </span>
                            @elseif($request->status == 'delivered')
                            <span class="px-3 py-1 text-xs rounded-full bg-purple-100 text-purple-800 font-medium">
                                <i class="fas fa-box mr-1"></i> Dikirim
                            </span>
                            @else
                            <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-800 font-medium">
                                <i class="fas fa-check-circle mr-1"></i> Selesai
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>{{ $request->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs">{{ $request->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="viewDetails({{ $request->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if($request->status == 'pending')
                            <button onclick="cancelRequest({{ $request->id }})" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    
                    @if($requests->isEmpty())
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-inbox fa-3x mb-4 opacity-20"></i>
                                <p>Belum ada permintaan</p>
                                <a href="{{ route('rs.create-request') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    <i class="fas fa-plus mr-2"></i> Buat Permintaan Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($requests->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $requests->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold text-gray-800">Detail Permintaan</h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div id="detailContent">
            <!-- Will be populated by JavaScript -->
        </div>
        
        <div class="mt-6 flex justify-end">
            <button onclick="closeDetailModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    // Filter Functions
    function filterRequests(status) {
        if(status === 'all') {
            window.location.href = '{{ route("rs.requests") }}';
        } else {
            window.location.href = '{{ route("rs.requests") }}?status=' + status;
        }
    }

    // Search Functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.request-row');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Urgency Filter
    document.getElementById('urgencyFilter').addEventListener('change', function(e) {
        const urgency = e.target.value;
        const rows = document.querySelectorAll('.request-row');
        
        rows.forEach(row => {
            const rowUrgency = row.getAttribute('data-urgency');
            if(!urgency || rowUrgency === urgency) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // View Details
    function viewDetails(requestId) {
        const detailHTML = `
            <div class="space-y-4">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mr-4">
                            <span class="text-red-600 font-bold text-xl">A</span>
                        </div>
                        <div>
                            <div class="font-bold text-lg">Golongan A+</div>
                            <div class="text-gray-600">5 kantong • Permintaan #000123</div>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div class="text-sm text-gray-600">Status</div>
                        <div class="font-medium text-green-600">Disetujui</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div class="text-sm text-gray-600">Urgensi</div>
                        <div class="font-medium">Normal</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div class="text-sm text-gray-600">Tanggal Pengajuan</div>
                        <div class="font-medium">15/03/2024 14:30</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div class="text-sm text-gray-600">Disetujui Pada</div>
                        <div class="font-medium">15/03/2024 15:15</div>
                    </div>
                </div>
                
                <div class="border-t pt-4">
                    <div class="text-sm font-medium text-gray-700 mb-2">Informasi Pasien</div>
                    <div class="text-gray-600">Pasien operasi jantung, usia 45 tahun</div>
                </div>
                
                <div class="border-t pt-4">
                    <div class="text-sm font-medium text-gray-700 mb-2">Catatan PMI</div>
                    <div class="text-gray-600">Permintaan disetujui dan sedang dipersiapkan untuk distribusi.</div>
                </div>
            </div>
        `;
        
        document.getElementById('detailContent').innerHTML = detailHTML;
        document.getElementById('detailModal').classList.remove('hidden');
    }
    
    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }
    
    function cancelRequest(requestId) {
        if(confirm('Apakah Anda yakin ingin membatalkan permintaan ini?')) {
            fetch(`/rs/requests/${requestId}/cancel`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if(response.ok) {
                    location.reload();
                }
            });
        }
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('detailModal');
        if(event.target == modal) {
            closeDetailModal();
        }
    }
</script>
@endsection