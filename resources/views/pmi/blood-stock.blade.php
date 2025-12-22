@extends('layouts.pmi')

@section('title', 'Stok Darah')
@section('header', 'Manajemen Stok Darah')

@section('content')
<div class="space-y-6">
    <!-- Header dengan Filter dan Tombol -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Data Stok Darah</h3>
            <p class="text-sm text-gray-600">Kelola stok darah per golongan dan rhesus</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex bg-white border border-gray-300 rounded-lg">
                <button class="px-4 py-2 text-sm font-medium text-gray-700 border-r hover:bg-gray-50">Semua</button>
                <button class="px-4 py-2 text-sm font-medium text-gray-700 border-r hover:bg-gray-50">A</button>
                <button class="px-4 py-2 text-sm font-medium text-gray-700 border-r hover:bg-gray-50">B</button>
                <button class="px-4 py-2 text-sm font-medium text-gray-700 border-r hover:bg-gray-50">AB</button>
                <button class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">O</button>
            </div>
            <button onclick="openAddStockModal()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Stok
            </button>
        </div>
    </div>

    <!-- Cards Summary -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
            $bloodTypes = ['A', 'B', 'AB', 'O'];
            $colors = ['red', 'blue', 'purple', 'orange'];
        @endphp
        
        @foreach($bloodTypes as $index => $type)
            @php
                $total = $bloodStocks->where('blood_type', $type)->where('status', 'available')->sum('quantity');
            @endphp
            <div class="bg-white rounded-xl shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-600">Golongan {{ $type }}</div>
                        <div class="text-2xl font-bold mt-1">{{ $total }}</div>
                        <div class="text-xs text-gray-500">kantong tersedia</div>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-{{ $colors[$index] }}-100 flex items-center justify-center">
                        <span class="text-{{ $colors[$index] }}-600 font-bold text-xl">{{ $type }}</span>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        @php
                            $percentage = min(100, ($total / 100) * 100);
                        @endphp
                        <div class="bg-{{ $colors[$index] }}-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Table Stok Darah -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Golongan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rhesus</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kadaluarsa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sumber</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($bloodStocks as $stock)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ str_pad($stock->id, 5, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                    <span class="text-red-600 font-bold">{{ $stock->blood_type }}</span>
                                </div>
                                <span class="ml-3 font-medium">{{ $stock->blood_type }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs rounded-full {{ $stock->rhesus == 'positive' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $stock->rhesus == 'positive' ? 'Positif (+)' : 'Negatif (-)' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $stock->quantity }}</div>
                            <div class="text-xs text-gray-500">kantong</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $stock->expiry_date ? \Carbon\Carbon::parse($stock->expiry_date)->format('d/m/Y') : '-' }}
                            </div>
                            @if($stock->expiry_date && \Carbon\Carbon::parse($stock->expiry_date)->diffInDays(now()) < 7)
                                <div class="text-xs text-red-600 font-semibold">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    {{ \Carbon\Carbon::parse($stock->expiry_date)->diffInDays(now()) }} hari lagi
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">
                                @if($stock->source == 'donor')
                                    <i class="fas fa-user text-blue-500 mr-1"></i> Donor
                                @else
                                    <i class="fas fa-bus text-green-500 mr-1"></i> Mobile Unit
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($stock->status == 'available')
                                <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800 font-medium">
                                    <i class="fas fa-check mr-1"></i> Tersedia
                                </span>
                            @elseif($stock->status == 'expired')
                                <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-800 font-medium">
                                    <i class="fas fa-times mr-1"></i> Kadaluarsa
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 font-medium">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Rusak
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="openAdjustStockModal({{ $stock->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="confirmDelete({{ $stock->id }})" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($bloodStocks->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-tint text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500">Belum ada data stok darah</p>
            <button onclick="openAddStockModal()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                <i class="fas fa-plus mr-2"></i> Tambah Stok Pertama
            </button>
        </div>
        @endif
    </div>

    <!-- Modal Tambah Stok -->
    <div id="addStockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Tambah Stok Darah</h3>
                <button onclick="closeAddStockModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="addStockForm" action="{{ route('pmi.blood-stock') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Golongan Darah</label>
                        <select name="blood_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                            <option value="">Pilih Golongan</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O">O</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rhesus</label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="rhesus" value="positive" class="form-radio text-red-600" required>
                                <span class="ml-2">Positif (+)</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="rhesus" value="negative" class="form-radio text-red-600">
                                <span class="ml-2">Negatif (-)</span>
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (kantong)</label>
                        <input type="number" name="quantity" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kadaluarsa</label>
                        <input type="date" name="expiry_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sumber</label>
                        <select name="source" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                            <option value="">Pilih Sumber</option>
                            <option value="donor">Donor Tetap</option>
                            <option value="mobile_unit">Mobile Unit</option>
                        </select>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeAddStockModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Penyesuaian Stok -->
    <div id="adjustStockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Penyesuaian Stok</h3>
                <button onclick="closeAdjustStockModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="adjustStockForm" action="{{ route('pmi.blood-stock.adjust') }}" method="POST">
                @csrf
                <input type="hidden" name="stock_id" id="stock_id">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Penyesuaian</label>
                        <select name="adjustment_type" id="adjustment_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                            <option value="">Pilih Jenis</option>
                            <option value="kadaluarsa">Kadaluarsa</option>
                            <option value="kerusakan">Kerusakan</option>
                            <option value="return">Return</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                        <input type="number" name="adjustment_quantity" id="adjustment_quantity" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                        <div class="text-xs text-gray-500 mt-1">Stok saat ini: <span id="current_quantity">0</span> kantong</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea name="adjustment_note" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Alasan penyesuaian..."></textarea>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeAdjustStockModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Simpan Penyesuaian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Modal Functions
    function openAddStockModal() {
        document.getElementById('addStockModal').classList.remove('hidden');
    }
    
    function closeAddStockModal() {
        document.getElementById('addStockModal').classList.add('hidden');
    }
    
    function openAdjustStockModal(stockId) {
        document.getElementById('stock_id').value = stockId;
        document.getElementById('adjustStockModal').classList.remove('hidden');
    }
    
    function closeAdjustStockModal() {
        document.getElementById('adjustStockModal').classList.add('hidden');
    }
    
    function confirmDelete(stockId) {
        if(confirm('Apakah Anda yakin ingin menghapus stok ini?')) {
            fetch(`/pmi/blood-stock/${stockId}`, {
                method: 'DELETE',
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
    
    window.onclick = function(event) {
        const addModal = document.getElementById('addStockModal');
        const adjustModal = document.getElementById('adjustStockModal');
        
        if(event.target == addModal) {
            closeAddStockModal();
        }
        if(event.target == adjustModal) {
            closeAdjustStockModal();
        }
    }
</script>
@endsection