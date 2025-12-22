@extends('layouts.rs')

@section('title', 'Buat Permintaan')
@section('header', 'Buat Permintaan Darah Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Progress Steps -->
        <div class="px-8 py-6 border-b">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                        1
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900">Data Permintaan</div>
                        <div class="text-xs text-gray-600">Informasi dasar</div>
                    </div>
                </div>
                <div class="text-gray-300 mx-4">
                    <i class="fas fa-chevron-right"></i>
                </div>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold">
                        2
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-gray-600">Review</div>
                        <div class="text-xs text-gray-600">Konfirmasi data</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form id="bloodRequestForm" action="{{ route('rs.create-request') }}" method="POST">
            @csrf
            
            <div class="p-8">
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Data Darah yang Dibutuhkan</h3>
                    
                    <!-- Blood Type Selection -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        @foreach(['A', 'B', 'AB', 'O'] as $type)
                        <div>
                            <input type="radio" name="blood_type" value="{{ $type }}" id="type_{{ $type }}" class="hidden peer" required>
                            <label for="type_{{ $type }}" class="block p-4 border-2 border-gray-200 rounded-lg text-center cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50">
                                <div class="text-2xl font-bold text-gray-800">{{ $type }}</div>
                                <div class="text-sm text-gray-600 mt-1">Golongan {{ $type }}</div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Rhesus Selection -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <input type="radio" name="rhesus" value="positive" id="rhesus_positive" class="hidden peer" required>
                            <label for="rhesus_positive" class="block p-4 border-2 border-gray-200 rounded-lg text-center cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 hover:bg-gray-50">
                                <div class="flex items-center justify-center">
                                    <i class="fas fa-plus text-green-600 text-xl mr-2"></i>
                                    <div>
                                        <div class="font-bold text-gray-800">Positif (+)</div>
                                        <div class="text-sm text-gray-600">Rhesus Positif</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div>
                            <input type="radio" name="rhesus" value="negative" id="rhesus_negative" class="hidden peer">
                            <label for="rhesus_negative" class="block p-4 border-2 border-gray-200 rounded-lg text-center cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50">
                                <div class="flex items-center justify-center">
                                    <i class="fas fa-minus text-blue-600 text-xl mr-2"></i>
                                    <div>
                                        <div class="font-bold text-gray-800">Negatif (-)</div>
                                        <div class="text-sm text-gray-600">Rhesus Negatif</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Quantity -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Kantong Darah</label>
                        <div class="relative">
                            <input type="number" name="quantity" min="1" max="50" value="1" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <div class="absolute right-3 top-3 text-gray-500">kantong</div>
                        </div>
                        <div class="text-xs text-gray-500 mt-2">Maksimal 50 kantong per permintaan</div>
                    </div>
                </div>
                
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Permintaan</h3>
                    
                    <!-- Urgency -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <input type="radio" name="urgency" value="normal" id="urgency_normal" class="hidden peer" checked>
                            <label for="urgency_normal" class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-check text-blue-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800">Normal</div>
                                        <div class="text-sm text-gray-600">Stok rutin</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div>
                            <input type="radio" name="urgency" value="urgent" id="urgency_urgent" class="hidden peer">
                            <label for="urgency_urgent" class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-orange-500 peer-checked:bg-orange-50 hover:bg-gray-50">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-clock text-orange-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800">Urgent</div>
                                        <div class="text-sm text-gray-600">Prioritas tinggi</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div>
                            <input type="radio" name="urgency" value="emergency" id="urgency_emergency" class="hidden peer">
                            <label for="urgency_emergency" class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-red-500 peer-checked:bg-red-50 hover:bg-gray-50">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800">Emergency</div>
                                        <div class="text-sm text-gray-600">Keadaan darurat</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Patient Info -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Informasi Pasien (Opsional)</label>
                        <div class="text-xs text-gray-500 mb-2">
                            <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                            Informasi ini akan membantu PMI memprioritaskan permintaan
                        </div>
                        <textarea name="patient_info" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Pasien operasi jantung, usia 45 tahun, kondisi kritis..."></textarea>
                    </div>
                </div>
                
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pengajuan</h3>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm text-gray-600">Rumah Sakit</div>
                                <div class="font-medium">
                                    @if(auth()->user()->hospital)
                                        {{ auth()->user()->hospital->name }}
                                    @endif
                                </div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Pengaju</div>
                                <div class="font-medium">{{ auth()->user()->name }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Tanggal</div>
                                <div class="font-medium">{{ now()->format('d F Y') }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Waktu</div>
                                <div class="font-medium">{{ now()->format('H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="px-8 py-6 bg-gray-50 border-t">
                <div class="flex justify-between items-center">
                    <a href="{{ route('rs.dashboard') }}" class="px-6 py-3 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-100 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <div class="flex space-x-3">
                        <button type="button" onclick="previewRequest()" class="px-6 py-3 text-blue-700 border border-blue-300 rounded-lg hover:bg-blue-50 font-medium">
                            <i class="fas fa-eye mr-2"></i> Preview
                        </button>
                        <!-- Tombol kirim dihapus sesuai permintaan -->
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold text-gray-800">Preview Permintaan Darah</h3>
            <button onclick="closePreview()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="space-y-6">
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-500 text-xl mr-3"></i>
                    <div>
                        <div class="font-medium text-blue-800">Periksa kembali data permintaan Anda</div>
                        <div class="text-sm text-blue-700 mt-1">Pastikan semua informasi sudah benar sebelum mengirim</div>
                    </div>
                </div>
            </div>
            
            <!-- Preview Content -->
            <div id="previewContent">
                <!-- Will be populated by JavaScript -->
            </div>
            
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button onclick="closePreview()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Edit Kembali
                </button>
                <button type="button" onclick="submitForm()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" id="previewConfirmBtn">
                    <i class="fas fa-paper-plane mr-2"></i> Konfirmasi Kirim
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Preview Function
    function previewRequest() {
        const form = document.getElementById('bloodRequestForm');
        const formData = new FormData(form);
        
        let previewHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-gray-600 mb-2">Data Darah</div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Golongan:</span>
                            <span class="font-bold" id="preview-blood-type">${formData.get('blood_type') || '-'}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Rhesus:</span>
                            <span class="font-bold" id="preview-rhesus">${formData.get('rhesus') == 'positive' ? 'Positif (+)' : 'Negatif (-)'}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jumlah:</span>
                            <span class="font-bold" id="preview-quantity">${formData.get('quantity')} kantong</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-gray-600 mb-2">Informasi Permintaan</div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Urgensi:</span>
                            <span class="font-bold">
                                ${formData.get('urgency') == 'emergency' ? 
                                    '<span class="text-red-600">EMERGENCY</span>' : 
                                    formData.get('urgency') == 'urgent' ? 
                                    '<span class="text-orange-600">URGENT</span>' : 
                                    '<span class="text-blue-600">Normal</span>'}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Rumah Sakit:</span>
                            <span class="font-bold">${'{{ auth()->user()->hospital ? auth()->user()->hospital->name : "" }}'}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Pengaju:</span>
                            <span class="font-bold">${'{{ auth()->user()->name }}'}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        if(formData.get('patient_info')) {
            previewHTML += `
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-gray-600 mb-2">Informasi Pasien</div>
                    <div class="text-gray-700">${formData.get('patient_info')}</div>
                </div>
            `;
        }
        
        document.getElementById('previewContent').innerHTML = previewHTML;
        document.getElementById('previewModal').classList.remove('hidden');
    }
    
    function closePreview() {
        document.getElementById('previewModal').classList.add('hidden');
    }
    
    function submitForm() {
        const form = document.getElementById('bloodRequestForm');
        const btn = document.getElementById('previewConfirmBtn');
        if (btn) {
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
        }
        form.submit();
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('previewModal');
        if(event.target == modal) {
            closePreview();
        }
    }
</script>
@endsection