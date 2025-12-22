@extends('layouts.pmi')

@section('title', 'Dashboard PMI')
@section('header', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Stok Darah -->
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg">
                    <i class="fas fa-tint text-red-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-600">Total Stok Darah</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $bloodStockSummary->sum('total') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Kantong tersedia</p>
                </div>
            </div>
        </div>

        <!-- Permintaan Hari Ini -->
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-calendar-day text-blue-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-600">Permintaan Hari Ini</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $todayRequests }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total permintaan</p>
                </div>
            </div>
        </div>

        <!-- Menunggu Verifikasi -->
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-600">Menunggu Verifikasi</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $pendingVerification }}</p>
                    <p class="text-xs text-gray-500 mt-1">Perlu tindakan</p>
                </div>
            </div>
        </div>

        <!-- Sedang Diproses -->
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-truck text-green-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-600">Sedang Diproses</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $inProcess }}</p>
                    <p class="text-xs text-gray-500 mt-1">Dalam distribusi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Stok Darah per Golongan -->
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Stok Darah per Golongan</h3>
                <span class="text-sm text-gray-500">Update Terakhir: {{ now()->format('H:i') }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Golongan Darah</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rhesus</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bloodStockSummary as $stock)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                        <span class="text-red-600 font-bold">{{ $stock->blood_type }}</span>
                                    </div>
                                    <span class="ml-3 font-medium">{{ $stock->blood_type }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $stock->rhesus == 'positive' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $stock->rhesus == 'positive' ? 'Positif (+)' : 'Negatif (-)' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        @php
                                            $percentage = min(100, ($stock->total / 100) * 100);
                                        @endphp
                                        <div class="bg-red-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="ml-3 font-bold">{{ $stock->total }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $stock->total > 20 ? 'bg-green-100 text-green-800' : ($stock->total > 10 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $stock->total > 20 ? 'Aman' : ($stock->total > 10 ? 'Waspada' : 'Kritis') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Distribusi Hari Ini -->
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Distribusi Hari Ini</h3>
                <a href="{{ route('pmi.distribution') }}" class="text-sm text-red-600 hover:text-red-800">Lihat Semua →</a>
            </div>
            <div class="space-y-4">
                @foreach($todayDistributions as $distribution)
                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center">
                                <i class="fas fa-hospital text-gray-400 mr-2"></i>
                                <span class="font-medium">{{ $distribution->bloodRequest->hospital->name }}</span>
                            </div>
                            <div class="mt-2 text-sm text-gray-600">
                                <div class="flex items-center mt-1">
                                    <i class="fas fa-tint text-red-400 mr-2"></i>
                                    <span>{{ $distribution->bloodRequest->blood_type }}{{ $distribution->bloodRequest->rhesus == 'positive' ? '+' : '-' }}</span>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-box text-gray-400 mr-2"></i>
                                    <span>{{ $distribution->bloodRequest->quantity }} kantong</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 text-xs rounded-full font-semibold 
                                {{ $distribution->status == 'delivered' ? 'bg-green-100 text-green-800' : 
                                   ($distribution->status == 'on_delivery' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                @if($distribution->status == 'delivered')
                                    <i class="fas fa-check mr-1"></i> Terkirim
                                @elseif($distribution->status == 'on_delivery')
                                    <i class="fas fa-truck mr-1"></i> Dalam Perjalanan
                                @else
                                    <i class="fas fa-box mr-1"></i> Dipersiapkan
                                @endif
                            </span>
                            <div class="mt-2 text-xs text-gray-500">
                                @if($distribution->estimated_arrival)
                                    ETA: {{ $distribution->estimated_arrival->format('H:i') }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-gray-100 text-xs text-gray-500">
                        <div class="flex justify-between">
                            <span>Driver: {{ $distribution->driver_name }}</span>
                            <span>{{ $distribution->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
                
                @if($todayDistributions->isEmpty())
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-truck fa-3x mb-4 opacity-20"></i>
                    <p>Tidak ada distribusi hari ini</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('pmi.blood-requests') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors text-center">
                <div class="p-3 bg-blue-100 rounded-lg inline-block">
                    <i class="fas fa-hand-paper text-blue-600 text-2xl"></i>
                </div>
                <h4 class="mt-3 font-medium text-gray-800">Verifikasi Permintaan</h4>
                <p class="text-sm text-gray-600 mt-1">{{ $pendingVerification }} menunggu</p>
            </a>
            
            <a href="{{ route('pmi.distribution') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors text-center">
                <div class="p-3 bg-green-100 rounded-lg inline-block">
                    <i class="fas fa-truck text-green-600 text-2xl"></i>
                </div>
                <h4 class="mt-3 font-medium text-gray-800">Kelola Distribusi</h4>
                <p class="text-sm text-gray-600 mt-1">{{ $inProcess }} sedang proses</p>
            </a>
            
            <a href="{{ route('pmi.blood-stock') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors text-center">
                <div class="p-3 bg-red-100 rounded-lg inline-block">
                    <i class="fas fa-tint text-red-600 text-2xl"></i>
                </div>
                <h4 class="mt-3 font-medium text-gray-800">Update Stok Darah</h4>
                <p class="text-sm text-gray-600 mt-1">Kelola inventaris darah</p>
            </a>
        </div>
    </div>
</div>
@endsection