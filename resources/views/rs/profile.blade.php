@extends('layouts.rs')

@section('title', 'Profil RS')
@section('header', 'Profil Rumah Sakit')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Hospital Profile Card -->
    <div class="bg-white rounded-xl shadow-lg mb-6 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-8 text-white">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-2">{{ $hospital->name }}</h2>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>{{ $hospital->address }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone mr-2"></i>
                            <span>{{ $hospital->phone }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 md:mt-0">
                    <span class="px-4 py-2 bg-white text-blue-600 rounded-lg font-semibold">
                        <i class="fas fa-hospital mr-2"></i> Rumah Sakit
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Tabs -->
        <div class="border-b border-gray-200">
            <nav class="flex">
                <button onclick="showTab('info')" id="tab-info" class="py-4 px-6 border-b-2 border-blue-500 text-blue-600 font-medium text-sm">
                    <i class="fas fa-info-circle mr-2"></i> Informasi RS
                </button>
                <button onclick="showTab('staff')" id="tab-staff" class="py-4 px-6 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                    <i class="fas fa-users mr-2"></i> Staff
                </button>
                <button onclick="showTab('activity')" id="tab-activity" class="py-4 px-6 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                    <i class="fas fa-history mr-2"></i> Aktivitas
                </button>
                <!-- Pengaturan tab removed -->
            </nav>
        </div>
    </div>

    <!-- Tab Content -->
    <div id="tab-content">
        <!-- Info Tab -->
        <div id="content-info" class="space-y-6">
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">Informasi Rumah Sakit</h3>
                
                <form action="{{ route('rs.profile') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Rumah Sakit</label>
                            <input type="text" name="name" value="{{ $hospital->name }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ $hospital->email }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                            <input type="text" name="phone" value="{{ $hospital->phone }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" disabled>
                                <option value="active" {{ $hospital->status == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ $hospital->status == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                            <textarea name="address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>{{ $hospital->address }}</textarea>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Hospital Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg mr-4">
                            <i class="fas fa-tint text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Total Permintaan</div>
                            <div class="text-2xl font-bold">42</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg mr-4">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Disetujui</div>
                            <div class="text-2xl font-bold">38</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-lg mr-4">
                            <i class="fas fa-truck text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Diterima</div>
                            <div class="text-2xl font-bold">35</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Tab -->
        <div id="content-staff" class="hidden">
            <div class="bg-white rounded-xl shadow">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">Staff Rumah Sakit</h3>
                        <button onclick="openAddStaffModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm flex items-center">
                            <i class="fas fa-user-plus mr-2"></i> Tambah Staff
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Login</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($staff as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">ID: {{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                        Staff RS
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs rounded-full {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $user->email_verified_at ? 'Aktif' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->updated_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="editStaff({{ $user->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if($user->id != auth()->id())
                                    <button onclick="deleteStaff({{ $user->id }})" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Activity Tab -->
        <div id="content-activity" class="hidden">
            <div class="bg-white rounded-xl shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Log Aktivitas</h3>
                    <p class="text-sm text-gray-600 mt-1">Riwayat aktivitas rumah sakit</p>
                </div>
                
                <div class="p-6">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            <li>
                                <div class="relative pb-8">
                                    <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    <div class="relative flex items-start space-x-3">
                                        <div class="relative">
                                            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center ring-8 ring-white">
                                                <i class="fas fa-check text-green-600"></i>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div>
                                                <div class="text-sm">
                                                    <span class="font-medium text-gray-900">Dr. Ahmad Wijaya</span>
                                                    <span class="text-gray-500">membuat permintaan darah</span>
                                                </div>
                                                <p class="mt-0.5 text-sm text-gray-500">
                                                    Golongan A+ • 5 kantong • Status: Disetujui
                                                </p>
                                            </div>
                                            <div class="mt-2 text-sm text-gray-700">
                                                <p>Permintaan untuk pasien operasi jantung</p>
                                            </div>
                                            <div class="mt-1 text-xs text-gray-500">15 menit yang lalu</div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            
                            <li>
                                <div class="relative pb-8">
                                    <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    <div class="relative flex items-start space-x-3">
                                        <div class="relative">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center ring-8 ring-white">
                                                <i class="fas fa-clipboard-check text-blue-600"></i>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div>
                                                <div class="text-sm">
                                                    <span class="font-medium text-gray-900">Siti Rahayu</span>
                                                    <span class="text-gray-500">mengkonfirmasi penerimaan</span>
                                                </div>
                                                <p class="mt-0.5 text-sm text-gray-500">
                                                    Pengiriman #00123 • 5 kantong A+
                                                </p>
                                            </div>
                                            <div class="mt-2 text-sm text-gray-700">
                                                <p>Darah telah diterima dan disimpan dengan baik</p>
                                            </div>
                                            <div class="mt-1 text-xs text-gray-500">2 jam yang lalu</div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            
                            <li>
                                <div class="relative pb-8">
                                    <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    <div class="relative flex items-start space-x-3">
                                        <div class="relative">
                                            <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center ring-8 ring-white">
                                                <i class="fas fa-clock text-yellow-600"></i>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div>
                                                <div class="text-sm">
                                                    <span class="font-medium text-gray-900">Budi Santoso</span>
                                                    <span class="text-gray-500">membuat permintaan emergency</span>
                                                </div>
                                                <p class="mt-0.5 text-sm text-gray-500">
                                                    Golongan O- • 3 kantong • Status: Menunggu
                                                </p>
                                            </div>
                                            <div class="mt-2 text-sm text-gray-700">
                                                <p>Emergency: Kecelakaan lalu lintas, multiple trauma</p>
                                            </div>
                                            <div class="mt-1 text-xs text-gray-500">5 jam yang lalu</div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings removed -->
    </div>
</div>

<!-- Add Staff Modal -->
<div id="addStaffModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Tambah Staff Baru</h3>
            <button onclick="closeAddStaffModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="addStaffForm" method="POST" action="{{ route('rs.profile.staff.add') }}">
            @csrf
            <input type="hidden" name="_form" value="add_staff">
            <div class="space-y-4">
                @if($errors->any() && old('_form') === 'add_staff')
                    <div class="p-3 bg-red-50 text-red-700 rounded">
                        <ul class="list-disc pl-5 text-sm">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Role</option>
                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff RS</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin RS</option>
                        <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>Dokter</option>
                        <option value="nurse" {{ old('role') == 'nurse' ? 'selected' : '' }}>Perawat</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <div class="text-xs text-gray-500 mt-2">Minimal 8 karakter</div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeAddStaffModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Tambah Staff
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Tab Functions
    function showTab(tabName) {
        // Hide all tab contents
        document.getElementById('content-info').classList.add('hidden');
        document.getElementById('content-staff').classList.add('hidden');
        document.getElementById('content-activity').classList.add('hidden');
        
        // Remove active state from all tabs
        document.getElementById('tab-info').classList.remove('border-blue-500', 'text-blue-600');
        document.getElementById('tab-staff').classList.remove('border-blue-500', 'text-blue-600');
        document.getElementById('tab-activity').classList.remove('border-blue-500', 'text-blue-600');
        
        // Add active state to clicked tab
        document.getElementById('tab-info').classList.add('border-transparent', 'text-gray-500');
        document.getElementById('tab-staff').classList.add('border-transparent', 'text-gray-500');
        document.getElementById('tab-activity').classList.add('border-transparent', 'text-gray-500');
        
        // Show selected tab content
        document.getElementById('content-' + tabName).classList.remove('hidden');
        document.getElementById('tab-' + tabName).classList.remove('border-transparent', 'text-gray-500');
        document.getElementById('tab-' + tabName).classList.add('border-blue-500', 'text-blue-600');
    }

    // Staff Modal Functions
    function openAddStaffModal() {
        document.getElementById('addStaffModal').classList.remove('hidden');
    }
    
    function closeAddStaffModal() {
        document.getElementById('addStaffModal').classList.add('hidden');
    }
    
    function editStaff(userId) {
        alert(`Edit staff with ID: ${userId}`);
    }
    
    function deleteStaff(userId) {
        if(!confirm('Apakah Anda yakin ingin menghapus staff ini?')) return;
        fetch(`/rs/profile/staff/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(async res => {
            if(res.ok) {
                location.reload();
            } else {
                const body = await res.json().catch(() => ({}));
                alert(body.message || 'Gagal menghapus staff.');
            }
        }).catch(() => alert('Gagal menghapus staff.'));
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('addStaffModal');
        if(event.target == modal) {
            closeAddStaffModal();
        }
    }

    // Auto-open Add Staff modal when validation fails
    @if($errors->any() && old('_form') === 'add_staff')
        openAddStaffModal();
    @endif

    // Initialize first tab
    showTab('info');
</script>
@endsection