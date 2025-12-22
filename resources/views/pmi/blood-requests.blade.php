@extends('layouts.pmi')

@section('title', 'Permintaan Darah')
@section('header', 'Permintaan dari Rumah Sakit')

@section('content')
<div class="space-y-6">
    <!-- Filter Tabs -->
    <div class="border-b border-gray-200">
        <nav class="flex space-x-8" aria-label="Tabs">
            <button onclick="filterRequests('all')" class="tab-filter py-4 px-1 border-b-2 font-medium text-sm {{ request('status') == null ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <span>Semua</span>
                <span class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2 rounded-full text-xs">{{ $requests->count() }}</span>
            </button>
            <button onclick="filterRequests('pending')" class="tab-filter py-4 px-1 border-b-2 font-medium text-sm {{ request('status') == 'pending' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <span>Menunggu</span>
                <span class="ml-2 bg-yellow-100 text-yellow-800 py-0.5 px-2 rounded-full text-xs">{{ $requests->where('status', 'pending')->count() }}</span>
            </button>
            <button onclick="filterRequests('approved')" class="tab-filter py-4 px-1 border-b-2 font-medium text-sm {{ request('status') == 'approved' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <span>Disetujui</span>
                <span class="ml-2 bg-green-100 text-green-800 py-0.5 px-2 rounded-full text-xs">{{ $requests->where('status', 'approved')->count() }}</span>
            </button>
            <button onclick="filterRequests('rejected')" class="tab-filter py-4 px-1 border-b-2 font-medium text-sm {{ request('status') == 'rejected' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <span>Ditolak</span>
                <span class="ml-2 bg-red-100 text-red-800 py-0.5 px-2 rounded-full text-xs">{{ $requests->where('status', 'rejected')->count() }}</span>
            </button>
            <button onclick="filterRequests('processed')" class="tab-filter py-4 px-1 border-b-2 font-medium text-sm {{ request('status') == 'processed' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <span>Diproses</span>
                <span class="ml-2 bg-blue-100 text-blue-800 py-0.5 px-2 rounded-full text-xs">{{ $requests->where('status', 'processed')->count() }}</span>
            </button>
        </nav>
    </div>

    <!-- Search and Filter -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="relative flex-1 max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" id="searchInput" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500" placeholder="Cari permintaan...">
        </div>
        <div class="flex items-center gap-3">
            <select id="urgencyFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500">
                <option value="">Semua Urgensi</option>
                <option value="normal">Normal</option>
                <option value="urgent">Urgent</option>
                <option value="emergency">Emergency</option>
            </select>
        </div>
    </div>

    <!-- Requests Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($requests as $request)
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition-shadow request-card" data-status="{{ $request->status }}" data-urgency="{{ $request->urgency }}">
            <div class="p-6">
                <!-- Header -->
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="flex items-center">
                            <span class="text-lg font-semibold text-gray-900">#{{ str_pad($request->id, 6, '0', STR_PAD_LEFT) }}</span>
                            <span class="ml-3 px-3 py-1 text-xs rounded-full font-semibold
                                {{ $request->urgency == 'emergency' ? 'bg-red-100 text-red-800' : 
                                   ($request->urgency == 'urgent' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800') }}">
                                @if($request->urgency == 'emergency')
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Emergency
                                @elseif($request->urgency == 'urgent')
                                    <i class="fas fa-clock mr-1"></i> Urgent
                                @else
                                    <i class="fas fa-check mr-1"></i> Normal
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center mt-2 text-sm text-gray-600">
                            <i class="fas fa-hospital mr-2"></i>
                            <span>{{ $request->hospital->name }}</span>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <span class="px-3 py-1 text-xs rounded-full font-semibold
                            {{ $request->status == 'approved' ? 'bg-green-100 text-green-800' : 
                               ($request->status == 'rejected' ? 'bg-red-100 text-red-800' : 
                               ($request->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                            @if($request->status == 'approved')
                                <i class="fas fa-check mr-1"></i> Disetujui
                            @elseif($request->status == 'rejected')
                                <i class="fas fa-times mr-1"></i> Ditolak
                            @elseif($request->status == 'pending')
                                <i class="fas fa-clock mr-1"></i> Menunggu
                            @else
                                <i class="fas fa-truck mr-1"></i> Diproses
                            @endif
                        </span>
                        <div class="text-xs text-gray-500 mt-2">
                            {{ $request->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Blood Info -->
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                    <span class="text-red-600 font-bold text-lg">{{ $request->blood_type }}</span>
                                </div>
                                <div class="ml-3">
                                    <div class="font-medium">Golongan {{ $request->blood_type }}{{ $request->rhesus == 'positive' ? '+' : '-' }}</div>
                                    <div class="text-sm text-gray-600">Rhesus {{ $request->rhesus == 'positive' ? 'Positif' : 'Negatif' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ $request->quantity }}</div>
                            <div class="text-sm text-gray-600">Kantong</div>
                        </div>
                    </div>
                </div>

                <!-- Patient Info -->
                @if($request->patient_info)
                <div class="mb-4">
                    <div class="text-sm font-medium text-gray-700 mb-1">Informasi Pasien:</div>
                    <div class="text-sm text-gray-600 bg-blue-50 p-3 rounded-lg">
                        {{ $request->patient_info }}
                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-user mr-1"></i> Diajukan oleh: {{ $request->createdByUser->name }}
                    </div>
                    
                    <div class="flex space-x-2">
                        @if($request->status == 'pending')
                        <button onclick="openVerifyModal({{ $request->id }}, 'approve')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm flex items-center">
                            <i class="fas fa-check mr-2"></i> Setujui
                        </button>
                        <button onclick="openVerifyModal({{ $request->id }}, 'reject')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm flex items-center">
                            <i class="fas fa-times mr-2"></i> Tolak
                        </button>
                        @endif
                        
                        {{-- Detail button removed per request --}}
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        
        @if($requests->isEmpty())
        <div class="col-span-2 text-center py-12">
            <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500">Tidak ada permintaan</p>
        </div>
        @endif
    </div>
</div>

<!-- Verification Modal -->
<div id="verifyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800" id="modalTitle">Verifikasi Permintaan</h3>
            <button onclick="closeVerifyModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="verifyForm" method="POST">
            @csrf
            <input type="hidden" name="request_id" id="request_id">
            <input type="hidden" name="action_type" id="action_type">
            <input type="hidden" name="status" id="status">
            
            <div id="approveContent" class="hidden">
                <div class="mb-4">
                    <div class="text-sm text-gray-600 mb-2">Anda akan menyetujui permintaan ini:</div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div id="requestDetails"></div>
                    </div>
                </div>
                <div class="text-sm text-gray-600">
                    <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                    Permintaan akan diproses untuk distribusi.
                </div>
            </div>
            
            <div id="rejectContent" class="hidden">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                    <textarea name="rejection_reason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Berikan alasan penolakan..." required></textarea>
                </div>
                <div class="text-sm text-gray-600">
                    <i class="fas fa-info-circle text-red-500 mr-1"></i>
                    Rumah sakit akan menerima notifikasi penolakan.
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeVerifyModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Konfirmasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Filter Functions
    function filterRequests(status) {
        if(status === 'all') {
            window.location.href = '{{ route("pmi.blood-requests") }}';
        } else {
            window.location.href = '{{ route("pmi.blood-requests") }}?status=' + status;
        }
    }

    // Search Functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const cards = document.querySelectorAll('.request-card');
        
        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Urgency Filter
    document.getElementById('urgencyFilter').addEventListener('change', function(e) {
        const urgency = e.target.value;
        const cards = document.querySelectorAll('.request-card');
        
        cards.forEach(card => {
            const cardUrgency = card.getAttribute('data-urgency');
            if(!urgency || cardUrgency === urgency) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Modal Functions
    function openVerifyModal(requestId, action) {
        document.getElementById('request_id').value = requestId;
        document.getElementById('action_type').value = action;
        document.getElementById('verifyForm').action = `/pmi/blood-requests/${requestId}/verify`;
        document.getElementById('status').value = action === 'approve' ? 'approved' : 'rejected';
        
        if(action === 'approve') {
            document.getElementById('modalTitle').textContent = 'Setujui Permintaan';
            document.getElementById('approveContent').classList.remove('hidden');
            document.getElementById('rejectContent').classList.add('hidden');
            const reason = document.querySelector('textarea[name="rejection_reason"]');
            if (reason) reason.removeAttribute('required');
        } else {
            document.getElementById('modalTitle').textContent = 'Tolak Permintaan';
            document.getElementById('approveContent').classList.add('hidden');
            document.getElementById('rejectContent').classList.remove('hidden');
            const reason = document.querySelector('textarea[name="rejection_reason"]');
            if (reason) reason.setAttribute('required', 'required');
        }
        
        document.getElementById('verifyModal').classList.remove('hidden');
    }
    
    function closeVerifyModal() {
        document.getElementById('verifyModal').classList.add('hidden');
    }
    
    


    window.onclick = function(event) {
        const modal = document.getElementById('verifyModal');
        if(event.target == modal) {
            closeVerifyModal();
        }
    }
</script>
@endsection