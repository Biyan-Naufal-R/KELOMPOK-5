@extends('layouts.rs')

@section('title', 'Penerimaan Darah')
@section('header', 'Penerimaan Kantong Darah')

@section('content')
<div class="space-y-6">
    <!-- Tabs -->
    <div class="border-b border-gray-200">
        <nav class="flex space-x-8">
            <button onclick="filterReceipts('all')" class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') == null ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Semua Penerimaan
            </button>
            <button onclick="filterReceipts('pending')" class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') == 'pending' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Menunggu Konfirmasi
            </button>
            <button onclick="filterReceipts('delivered')" class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') == 'delivered' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Sudah Diterima
            </button>
        </nav>
    </div>

    <!-- Distribution Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($distributions as $distribution)
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition-shadow">
            <div class="p-6">
                <!-- Header -->
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="flex items-center">
                            <i class="fas fa-truck text-gray-400 mr-2"></i>
                            <span class="font-semibold">Pengiriman #{{ str_pad($distribution->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="text-sm text-gray-600 mt-1">
                            Permintaan #{{ str_pad($distribution->bloodRequest->id, 6, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>
                    <div>
                        <span class="px-3 py-1 text-xs rounded-full font-semibold
                            {{ $distribution->status == 'delivered' ? 'bg-green-100 text-green-800' : 
                               ($distribution->status == 'on_delivery' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                            @if($distribution->status == 'delivered')
                                <i class="fas fa-check mr-1"></i> Diterima
                            @elseif($distribution->status == 'on_delivery')
                                <i class="fas fa-truck mr-1"></i> Dalam Perjalanan
                            @else
                                <i class="fas fa-box mr-1"></i> Dipersiapkan
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Blood Info -->
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                <span class="text-red-600 font-bold text-lg">{{ $distribution->bloodRequest->blood_type }}</span>
                            </div>
                            <div class="ml-3">
                                <div class="font-medium">{{ $distribution->bloodRequest->blood_type }}{{ $distribution->bloodRequest->rhesus == 'positive' ? '+' : '-' }}</div>
                                <div class="text-sm text-gray-600">{{ $distribution->bloodRequest->quantity }} kantong</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-600">Estimasi:</div>
                            <div class="font-medium">
                                @if($distribution->estimated_arrival)
                                    {{ $distribution->estimated_arrival->format('d/m H:i') }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Info -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <div class="text-xs text-gray-600">Driver</div>
                        <div class="font-medium">{{ $distribution->driver_name }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-600">Kendaraan</div>
                        <div class="font-medium truncate">{{ $distribution->vehicle_info }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-600">Berangkat</div>
                        <div class="font-medium">
                            @if($distribution->departure_time)
                                {{ $distribution->departure_time->format('H:i') }}
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-600">Tiba</div>
                        <div class="font-medium">
                            @if($distribution->actual_arrival)
                                {{ $distribution->actual_arrival->format('H:i') }}
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                @if($distribution->notes)
                <div class="mb-4">
                    <div class="text-xs text-gray-600 mb-1">Catatan Driver:</div>
                    <div class="text-sm text-gray-700 bg-yellow-50 p-3 rounded-lg">{{ $distribution->notes }}</div>
                </div>
                @endif

                <!-- Actions -->
                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-calendar mr-1"></i>
                        {{ $distribution->created_at->format('d F Y') }}
                    </div>
                    
                    <div class="flex space-x-2">
                        @if($distribution->status == 'on_delivery')
                        <button onclick="openConfirmModal({{ $distribution->id }})" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm flex items-center">
                            <i class="fas fa-clipboard-check mr-2"></i> Konfirmasi Penerimaan
                        </button>
                        @endif
                        
                        {{-- Receipt view removed per request --}}
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        
        @if($distributions->isEmpty())
        <div class="col-span-2 text-center py-12">
            <i class="fas fa-truck fa-3x text-gray-300 mb-4"></i>
            <p class="text-gray-500">Belum ada pengiriman</p>
        </div>
        @endif
    </div>
</div>

<!-- Confirm Receipt Modal -->
<div id="confirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Konfirmasi Penerimaan</h3>
            <button onclick="closeConfirmModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="confirmForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="distribution_id" id="distribution_id">
            
            <div class="space-y-4">
                <div>
                    <div class="text-sm text-gray-600 mb-2">Pastikan Anda telah menerima:</div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div id="deliveryDetails"></div>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Serah Terima</label>
                    <div class="text-xs text-gray-500 mb-2">Format: JPG, PNG, PDF (max 2MB)</div>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                    <span>Upload file</span>
                                    <input id="file-upload" name="receipt_proof" type="file" class="sr-only" accept=".jpg,.jpeg,.png,.pdf" required>
                                </label>
                                <p class="pl-1">atau drag & drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, PDF up to 2MB</p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea name="receipt_notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Catatan tambahan..."></textarea>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeConfirmModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Konfirmasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function filterReceipts(status) {
        if(status === 'all') {
            window.location.href = '{{ route("rs.blood-receipt") }}';
        } else {
            window.location.href = '{{ route("rs.blood-receipt") }}?status=' + status;
        }
    }

    function openConfirmModal(distributionId) {
        document.getElementById('distribution_id').value = distributionId;
        document.getElementById('confirmForm').action = `/rs/blood-receipt/${distributionId}/confirm`;
        
        document.getElementById('deliveryDetails').innerHTML = `
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center mr-3">
                    <span class="text-red-600 font-bold">A</span>
                </div>
                <div>
                    <div class="font-medium">Golongan A+ • 5 kantong</div>
                    <div class="text-sm text-gray-600">Driver: Joko Susilo</div>
                </div>
            </div>
        `;
        
        document.getElementById('confirmModal').classList.remove('hidden');
    }
    
    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.add('hidden');
    }
    

    // File upload preview
    document.getElementById('file-upload').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        if(fileName) {
            const label = document.querySelector('label[for="file-upload"] span');
            label.textContent = fileName;
        }
    });

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('confirmModal');
        if(event.target == modal) {
            closeConfirmModal();
        }
    }
</script>
@endsection