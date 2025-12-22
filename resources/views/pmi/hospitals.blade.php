@extends('layouts.pmi')

@section('title', 'Data Rumah Sakit')
@section('header', 'Data Rumah Sakit Terdaftar')

@section('content')
<div class="space-y-6">
    <!-- Header dengan Search -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Rumah Sakit Terdaftar</h3>
            <p class="text-sm text-gray-600">Kelola data rumah sakit mitra PMI</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" id="searchInput" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500" placeholder="Cari rumah sakit...">
            </div>
            <button onclick="openAddHospitalModal()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah RS
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600">Total RS</div>
                    <div class="text-2xl font-bold text-gray-900 mt-2">{{ $hospitals->count() }}</div>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-hospital text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600">Aktif</div>
                    <div class="text-2xl font-bold text-gray-900 mt-2">{{ $hospitals->where('status', 'active')->count() }}</div>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600">Non-Aktif</div>
                    <div class="text-2xl font-bold text-gray-900 mt-2">{{ $hospitals->where('status', 'inactive')->count() }}</div>
                </div>
                <div class="p-3 bg-red-100 rounded-lg">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-600">Permintaan Bulan Ini</div>
                    <div class="text-2xl font-bold text-gray-900 mt-2">{{ $totalRequestsThisMonth }}</div>
                </div>
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-tint text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Hospitals Table -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama RS</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statistik</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivitas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($hospitals as $hospital)
                    <tr class="hover:bg-gray-50 hospital-row">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-hospital text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $hospital->name }}</div>
                                    <div class="text-xs text-gray-500 truncate max-w-xs">{{ $hospital->address }}</div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        Terdaftar: {{ $hospital->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <div class="text-gray-900">{{ $hospital->email }}</div>
                                <div class="text-gray-600">{{ $hospital->phone }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs rounded-full {{ $hospital->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} font-medium">
                                {{ $hospital->status == 'active' ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                            <div class="text-xs text-gray-600 mt-1">
                                {{ $hospital->users->count() }} staff
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Total Permintaan:</span>
                                    <span class="font-medium">{{ $hospital->bloodRequests->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Bulan Ini:</span>
                                    <span class="font-medium">{{ $hospital->bloodRequests->where('created_at', '>=', now()->startOfMonth())->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Disetujui:</span>
                                    <span class="font-medium">{{ $hospital->bloodRequests->where('status', 'approved')->count() }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                @if($hospital->bloodRequests->count() > 0)
                                @php
                                    $latestRequest = $hospital->bloodRequests->sortByDesc('created_at')->first();
                                @endphp
                                <div class="text-xs">
                                    <div class="text-gray-600">Terakhir Request:</div>
                                    <div class="font-medium">{{ $latestRequest->created_at->format('d/m H:i') }}</div>
                                </div>
                                @endif
                                @if($hospital->updated_at)
                                <div class="text-xs">
                                    <div class="text-gray-600">Update Terakhir:</div>
                                    <div>{{ $hospital->updated_at->format('d/m H:i') }}</div>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="viewHospital({{ $hospital->id }})" class="text-blue-600 hover:text-blue-900 mr-3" title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="editHospital({{ $hospital->id }})" class="text-green-600 hover:text-green-900 mr-3" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="toggleStatus({{ $hospital->id }}, '{{ $hospital->status }}')" class="{{ $hospital->status == 'active' ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}" title="{{ $hospital->status == 'active' ? 'Non-Aktifkan' : 'Aktifkan' }}">
                                @if($hospital->status == 'active')
                                <i class="fas fa-times"></i>
                                @else
                                <i class="fas fa-check"></i>
                                @endif
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    
                    @if($hospitals->isEmpty())
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-hospital fa-3x mb-4 opacity-20"></i>
                                <p>Belum ada rumah sakit terdaftar</p>
                                <button onclick="openAddHospitalModal()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                    <i class="fas fa-plus mr-2"></i> Tambah Rumah Sakit
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Hospital Detail Modal -->
    <div id="hospitalDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Detail Rumah Sakit</h3>
                <button onclick="closeHospitalDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="hospitalDetailContent">
                <!-- Will be populated by JavaScript -->
            </div>
        </div>
    </div>

    <!-- Add/Edit Hospital Modal -->
    <div id="hospitalFormModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800" id="hospitalModalTitle">Tambah Rumah Sakit</h3>
                <button onclick="closeHospitalFormModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="hospitalForm" action="{{ route('pmi.hospitals.store') }}" method="POST">
                @csrf
                <input type="hidden" name="hospital_id" id="hospital_id">
                <input type="hidden" name="_method" id="_method" value="POST">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Rumah Sakit</label>
                        <input type="text" name="name" id="hospital_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" id="hospital_email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                            <input type="text" name="phone" id="hospital_phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                        <textarea name="address" id="hospital_address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="hospital_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="active">Aktif</option>
                            <option value="inactive">Non-Aktif</option>
                        </select>
                    </div>
                    
                    <!-- Admin User for New Hospital -->
                    <div id="adminUserFields" class="space-y-4 border-t pt-4">
                        <div class="text-sm font-medium text-gray-700 mb-2">Data Admin RS</div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-2">Nama Admin</label>
                            <input type="text" name="admin_name" id="admin_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-2">Email Admin</label>
                            <input type="email" name="admin_email" id="admin_email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeHospitalFormModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Search Functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.hospital-row');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Hospital Functions
    function openAddHospitalModal() {
        document.getElementById('hospitalModalTitle').textContent = 'Tambah Rumah Sakit';
        document.getElementById('adminUserFields').classList.remove('hidden');
        // reset form and set to create
        const form = document.getElementById('hospitalForm');
        form.action = '{{ route('pmi.hospitals.store') }}';
        document.getElementById('_method').value = 'POST';
        form.reset();
        document.getElementById('hospitalFormModal').classList.remove('hidden');
    }
    
    function closeHospitalFormModal() {
        document.getElementById('hospitalFormModal').classList.add('hidden');
    }
    
    function editHospital(hospitalId) {
        fetch(`/pmi/hospitals/${hospitalId}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('hospitalModalTitle').textContent = 'Edit Rumah Sakit';
                document.getElementById('adminUserFields').classList.add('hidden');

                document.getElementById('hospital_id').value = hospitalId;
                document.getElementById('hospital_name').value = data.name || '';
                document.getElementById('hospital_email').value = data.email || '';
                document.getElementById('hospital_phone').value = data.phone || '';
                document.getElementById('hospital_address').value = data.address || '';
                document.getElementById('hospital_status').value = data.status || 'active';

                // set form to update
                const form = document.getElementById('hospitalForm');
                form.action = `/pmi/hospitals/${hospitalId}`;
                document.getElementById('_method').value = 'PUT';

                document.getElementById('hospitalFormModal').classList.remove('hidden');
            }).catch(err => {
                // fallback to sample data if fetch fails
                document.getElementById('hospitalModalTitle').textContent = 'Edit Rumah Sakit';
                document.getElementById('adminUserFields').classList.add('hidden');
                document.getElementById('hospital_id').value = hospitalId;
                document.getElementById('hospitalFormModal').classList.remove('hidden');
            });
    }
    
    function viewHospital(hospitalId) {
        const detailHTML = `
            <div class="space-y-6">
                <!-- Hospital Info -->
                <div class="bg-blue-50 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                            <i class="fas fa-hospital text-blue-600 text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-900">RSUD Dr. Soetomo</h4>
                            <div class="text-gray-600">Jl. Mayjen Prof. Dr. Moestopo No.6-8, Surabaya</div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <div class="text-sm text-gray-600">Email</div>
                            <div class="font-medium">info@rsudsoetomo.go.id</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Telepon</div>
                            <div class="font-medium">031-5501076</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Status</div>
                            <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800 font-medium">
                                Aktif
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white border rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-gray-900">42</div>
                        <div class="text-sm text-gray-600">Total Permintaan</div>
                    </div>
                    <div class="bg-white border rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-gray-900">38</div>
                        <div class="text-sm text-gray-600">Disetujui</div>
                    </div>
                    <div class="bg-white border rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-gray-900">35</div>
                        <div class="text-sm text-gray-600">Diterima</div>
                    </div>
                    <div class="bg-white border rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-gray-900">3</div>
                        <div class="text-sm text-gray-600">Staff</div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="border-t pt-6">
                    <h5 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terakhir</h5>
                    <div class="space-y-4">
                        <div class="flex items-start border-b pb-4">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                <i class="fas fa-tint text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium">Permintaan Darah #000123</div>
                                <div class="text-sm text-gray-600">Golongan A+ • 5 kantong • Disetujui</div>
                                <div class="text-xs text-gray-500 mt-1">15/03/2024 14:30</div>
                            </div>
                        </div>
                        <div class="flex items-start border-b pb-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-truck text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium">Pengiriman #00123</div>
                                <div class="text-sm text-gray-600">5 kantong A+ • Diterima</div>
                                <div class="text-xs text-gray-500 mt-1">14/03/2024 10:15</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Staff List -->
                <div class="border-t pt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h5 class="text-lg font-semibold text-gray-800">Staff Terdaftar</h5>
                        <button class="text-sm text-red-600 hover:text-red-800">
                            <i class="fas fa-user-plus mr-1"></i> Tambah Staff
                        </button>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Dr. Ahmad Wijaya</div>
                                    <div class="text-sm text-gray-600">ahmad@rsudsoetomo.go.id</div>
                                </div>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                Admin
                            </span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Siti Rahayu, S.Kep</div>
                                    <div class="text-sm text-gray-600">siti@rsudsoetomo.go.id</div>
                                </div>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                Staff
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t flex justify-end">
                <button onclick="closeHospitalDetailModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Tutup
                </button>
            </div>
        `;
        
        document.getElementById('hospitalDetailContent').innerHTML = detailHTML;
        document.getElementById('hospitalDetailModal').classList.remove('hidden');
    }
    
    function closeHospitalDetailModal() {
        document.getElementById('hospitalDetailModal').classList.add('hidden');
    }
    
    function toggleStatus(hospitalId, currentStatus) {
        const newStatus = currentStatus == 'active' ? 'inactive' : 'active';
        const action = currentStatus == 'active' ? 'non-aktifkan' : 'aktifkan';
        
        if(confirm(`Apakah Anda yakin ingin ${action} rumah sakit ini?`)) {
            fetch(`/pmi/hospitals/${hospitalId}/status`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            }).then(response => {
                if(response.ok) {
                    location.reload();
                }
            });
        }
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        const detailModal = document.getElementById('hospitalDetailModal');
        const formModal = document.getElementById('hospitalFormModal');
        
        if(event.target == detailModal) {
            closeHospitalDetailModal();
        }
        if(event.target == formModal) {
            closeHospitalFormModal();
        }
    }
</script>
@endsection