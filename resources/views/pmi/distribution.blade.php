@extends('layouts.pmi')

@section('title', 'Distribusi Darah')
@section('header', 'Pengeluaran & Distribusi')

@section('content')
<div class="space-y-6">
    <!-- Header dengan Filter -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Manajemen Distribusi</h3>
            <p class="text-sm text-gray-600">Kelola pengiriman darah ke rumah sakit</p>
        </div>
        <div class="flex items-center gap-3">
            <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500">
                <option value="">Semua Status</option>
                <option value="preparing">Dipersiapkan</option>
                <option value="on_delivery">Dalam Perjalanan</option>
                <option value="delivered">Terkirim</option>
            </select>
            <button onclick="openNewDistributionModal()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center">
                <i class="fas fa-plus mr-2"></i> Buat Distribusi
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600">Dipersiapkan</div>
                    <div class="text-2xl font-bold text-gray-900 mt-2">{{ $distributions->where('status', 'preparing')->count() }}</div>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600">Dalam Perjalanan</div>
                    <div class="text-2xl font-bold text-gray-900 mt-2">{{ $distributions->where('status', 'on_delivery')->count() }}</div>
                </div>
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-truck text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600">Terkirim</div>
                    <div class="text-2xl font-bold text-gray-900 mt-2">{{ $distributions->where('status', 'delivered')->count() }}</div>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Distributions Table -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Distribusi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rumah Sakit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permintaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver & Kendaraan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($distributions as $distribution)
                    <tr class="hover:bg-gray-50 distribution-row" data-status="{{ $distribution->status }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ str_pad($distribution->id, 5, '0', STR_PAD_LEFT) }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $distribution->created_at->format('d/m/Y H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-hospital text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $distribution->bloodRequest->hospital->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $distribution->bloodRequest->hospital->phone }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <div class="flex items-center">
                                    <div class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center mr-2">
                                        <span class="text-red-600 font-bold text-xs">{{ $distribution->bloodRequest->blood_type }}</span>
                                    </div>
                                    <span class="font-medium">{{ $distribution->bloodRequest->blood_type }}{{ $distribution->bloodRequest->rhesus == 'positive' ? '+' : '-' }}</span>
                                </div>
                                <div class="text-xs text-gray-600 mt-1">{{ $distribution->bloodRequest->quantity }} kantong</div>
                                <div class="text-xs text-gray-500">
                                    @if($distribution->bloodRequest->urgency == 'emergency')
                                        <span class="text-red-600 font-medium">EMERGENCY</span>
                                    @elseif($distribution->bloodRequest->urgency == 'urgent')
                                        <span class="text-orange-600">Urgent</span>
                                    @else
                                        Normal
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <div class="font-medium">{{ $distribution->driver_name }}</div>
                                <div class="text-xs text-gray-600">{{ $distribution->vehicle_info }}</div>
                                @if($distribution->notes)
                                <div class="text-xs text-gray-500 mt-1 truncate max-w-xs" title="{{ $distribution->notes }}">
                                    <i class="fas fa-sticky-note mr-1"></i>{{ Str::limit($distribution->notes, 30) }}
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                @if($distribution->departure_time)
                                <div>
                                    <span class="text-gray-600">Berangkat:</span>
                                    <span class="font-medium">{{ $distribution->departure_time->format('H:i') }}</span>
                                </div>
                                @endif
                                @if($distribution->estimated_arrival)
                                <div>
                                    <span class="text-gray-600">Estimasi:</span>
                                    <span class="font-medium">{{ $distribution->estimated_arrival->format('H:i') }}</span>
                                </div>
                                @endif
                                @if($distribution->actual_arrival)
                                <div>
                                    <span class="text-gray-600">Tiba:</span>
                                    <span class="font-medium">{{ $distribution->actual_arrival->format('H:i') }}</span>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($distribution->status == 'delivered')
                            <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800 font-medium">
                                <i class="fas fa-check mr-1"></i> Terkirim
                            </span>
                            @if($distribution->receipt_proof)
                            <div class="text-xs text-green-600 mt-1">
                                <i class="fas fa-file-alt mr-1"></i> Bukti diterima
                            </div>
                            @endif
                            @elseif($distribution->status == 'on_delivery')
                            <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 font-medium">
                                <i class="fas fa-truck mr-1"></i> Dalam Perjalanan
                            </span>
                            @if($distribution->estimated_arrival)
                            <div class="text-xs text-gray-600 mt-1">
                                ETA: {{ $distribution->estimated_arrival->format('H:i') }}
                            </div>
                            @endif
                            @else
                            <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-800 font-medium">
                                <i class="fas fa-box mr-1"></i> Dipersiapkan
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($distribution->status != 'delivered')
                            <button onclick="updateStatusModal({{ $distribution->id }})" class="text-blue-600 hover:text-blue-900 mr-3" title="Update Status">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            @endif
                            <button onclick="viewDetails({{ $distribution->id }})" class="text-green-600 hover:text-green-900 mr-3" title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="printPackingList({{ $distribution->id }})" class="text-purple-600 hover:text-purple-900" title="Cetak Packing List">
                                <i class="fas fa-print"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    
                    @if($distributions->isEmpty())
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-truck fa-3x mb-4 opacity-20"></i>
                                <p>Belum ada distribusi</p>
                                <button onclick="openNewDistributionModal()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                    <i class="fas fa-plus mr-2"></i> Buat Distribusi Pertama
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        @if($distributions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $distributions->links() }}
        </div>
        @endif
    </div>
</div>

<!-- New Distribution Modal -->
<div id="newDistributionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Buat Distribusi Baru</h3>
            <button onclick="closeNewDistributionModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="newDistributionForm" action="{{ route('pmi.distribution') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <!-- Pilih Permintaan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Permintaan</label>
                    <select name="blood_request_id" id="blood_request_select" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required onchange="updateRequestDetails()">
                        <option value="">Pilih Permintaan yang Disetujui</option>
                        @foreach($approvedRequests as $request)
                        <option value="{{ $request->id }}" data-details="{{ json_encode($request) }}">
                            #{{ str_pad($request->id, 6, '0', STR_PAD_LEFT) }} - {{ $request->hospital->name }} - {{ $request->blood_type }}{{ $request->rhesus == 'positive' ? '+' : '-' }} ({{ $request->quantity }} kantong)
                        </option>
                        @endforeach
                    </select>
                    
                    <!-- Request Details -->
                    <div id="requestDetails" class="mt-4 bg-gray-50 p-4 rounded-lg hidden">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-xs text-gray-600">Rumah Sakit</div>
                                <div class="font-medium" id="detail-hospital"></div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-600">Golongan Darah</div>
                                <div class="font-medium" id="detail-blood-type"></div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-600">Jumlah</div>
                                <div class="font-medium" id="detail-quantity"></div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-600">Urgensi</div>
                                <div class="font-medium" id="detail-urgency"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Driver & Vehicle Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Driver</label>
                        <input type="text" name="driver_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Info Kendaraan</label>
                        <input type="text" name="vehicle_info" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Contoh: Toyota Hilux B 1234 CD" required>
                    </div>
                </div>
                
                <!-- Schedule -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Berangkat</label>
                        <input type="datetime-local" name="departure_time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estimasi Tiba</label>
                        <input type="datetime-local" name="estimated_arrival" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                </div>
                
                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Catatan untuk driver atau rumah sakit..."></textarea>
                </div>
                
                <!-- Packing List Preview -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700">Packing List</label>
                        <button type="button" onclick="previewPackingList()" class="text-sm text-red-600 hover:text-red-800">
                            <i class="fas fa-eye mr-1"></i> Preview
                        </button>
                    </div>
                    <div id="packingListPreview" class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="text-sm text-gray-600">Pilih permintaan terlebih dahulu</div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeNewDistributionModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    <i class="fas fa-paper-plane mr-2"></i> Buat Distribusi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Update Status Modal -->
<div id="updateStatusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Update Status Distribusi</h3>
            <button onclick="closeUpdateStatusModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="updateStatusForm" method="POST">
            @csrf
            <input type="hidden" name="distribution_id" id="update_distribution_id">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Baru</label>
                    <select name="status" id="status_select" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required onchange="toggleArrivalTime()">
                        <option value="">Pilih Status</option>
                        <option value="preparing">Dipersiapkan</option>
                        <option value="on_delivery">Dalam Perjalanan</option>
                        <option value="delivered">Terkirim</option>
                    </select>
                </div>
                
                <div id="arrivalTimeField" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Tiba Aktual</label>
                    <input type="datetime-local" name="actual_arrival" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Update</label>
                    <textarea name="update_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Catatan perubahan status..."></textarea>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeUpdateStatusModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Filter Functionality
    document.getElementById('statusFilter').addEventListener('change', function(e) {
        const status = e.target.value;
        const rows = document.querySelectorAll('.distribution-row');
        
        rows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            if(!status || rowStatus === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Modal Functions
    function openNewDistributionModal() {
        document.getElementById('newDistributionModal').classList.remove('hidden');
    }
    
    function closeNewDistributionModal() {
        document.getElementById('newDistributionModal').classList.add('hidden');
    }
    
    function updateStatusModal(distributionId) {
        document.getElementById('update_distribution_id').value = distributionId;
        document.getElementById('updateStatusForm').action = `/pmi/distribution/${distributionId}/update-status`;
        document.getElementById('updateStatusModal').classList.remove('hidden');
    }
    
    function closeUpdateStatusModal() {
        document.getElementById('updateStatusModal').classList.add('hidden');
    }
    
    function toggleArrivalTime() {
        const status = document.getElementById('status_select').value;
        const arrivalField = document.getElementById('arrivalTimeField');
        if(status === 'delivered') {
            arrivalField.classList.remove('hidden');
        } else {
            arrivalField.classList.add('hidden');
        }
    }
    
    function updateRequestDetails() {
        const select = document.getElementById('blood_request_select');
        const selectedOption = select.options[select.selectedIndex];
        const detailsDiv = document.getElementById('requestDetails');
        
        if(selectedOption.value) {
            const details = JSON.parse(selectedOption.getAttribute('data-details'));
            document.getElementById('detail-hospital').textContent = details.hospital.name;
            document.getElementById('detail-blood-type').textContent = details.blood_type + (details.rhesus == 'positive' ? '+' : '-');
            document.getElementById('detail-quantity').textContent = details.quantity + ' kantong';
            document.getElementById('detail-urgency').textContent = 
                details.urgency == 'emergency' ? 'EMERGENCY' : 
                details.urgency == 'urgent' ? 'Urgent' : 'Normal';
            detailsDiv.classList.remove('hidden');
            
            // Update packing list preview
            updatePackingListPreview(details);
        } else {
            detailsDiv.classList.add('hidden');
            document.getElementById('packingListPreview').innerHTML = 
                '<div class="text-sm text-gray-600">Pilih permintaan terlebih dahulu</div>';
        }
    }
    
    function updatePackingListPreview(details) {
        const packingListHTML = `
            <div class="space-y-3">
                <div class="flex justify-between items-center border-b pb-2">
                    <span class="font-medium">Item</span>
                    <span class="font-medium">Jumlah</span>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center mr-3">
                            <span class="text-red-600 font-bold">${details.blood_type}</span>
                        </div>
                        <div>
                            <div>Darah ${details.blood_type}${details.rhesus == 'positive' ? '+' : '-'}</div>
                            <div class="text-xs text-gray-600">${details.urgency == 'emergency' ? 'EMERGENCY' : details.urgency == 'urgent' ? 'Urgent' : 'Normal'}</div>
                        </div>
                    </div>
                    <span class="font-bold">${details.quantity} kantong</span>
                </div>
                <div class="pt-2 border-t text-sm text-gray-600">
                    <div>Rumah Sakit: ${details.hospital.name}</div>
                    <div>Alamat: ${details.hospital.address}</div>
                </div>
            </div>
        `;
        document.getElementById('packingListPreview').innerHTML = packingListHTML;
    }
    
    function previewPackingList() {
        const select = document.getElementById('blood_request_select');
        if(select.value) {
            alert('Preview packing list akan ditampilkan di jendela baru');
        } else {
            alert('Pilih permintaan terlebih dahulu');
        }
    }
    
    function viewDetails(distributionId) {
        window.location.href = `/pmi/distribution/${distributionId}`;
    }
    
    function printPackingList(distributionId) {
        window.open(`/pmi/distribution/${distributionId}/packing-list`, '_blank');
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        const newModal = document.getElementById('newDistributionModal');
        const updateModal = document.getElementById('updateStatusModal');
        
        if(event.target == newModal) {
            closeNewDistributionModal();
        }
        if(event.target == updateModal) {
            closeUpdateStatusModal();
        }
    }
</script>
@endsection