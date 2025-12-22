@extends('layouts.rs')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Rumah Sakit</h1>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-clock text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-600">Permintaan Pending</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $pendingRequests }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-600">Diterima Minggu Ini</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $weeklyReceipts->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-history text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-600">Total Permintaan</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $recentRequests->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Requests -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Permintaan Terbaru</h3>
            <div class="space-y-4">
                @foreach($recentRequests as $request)
                <div class="border-l-4 
                    {{ $request->status == 'approved' ? 'border-green-500' : 
                       ($request->status == 'rejected' ? 'border-red-500' : 
                       ($request->status == 'pending' ? 'border-yellow-500' : 'border-blue-500')) }} 
                    pl-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ $request->blood_type }}{{ $request->rhesus == 'positive' ? '+' : '-' }}
                                • {{ $request->quantity }} kantong
                            </p>
                            <p class="text-sm text-gray-600">
                                {{ $request->created_at->format('d M Y H:i') }}
                                • {{ ucfirst($request->urgency) }}
                            </p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $request->status == 'approved' ? 'bg-green-100 text-green-800' : 
                               ($request->status == 'rejected' ? 'bg-red-100 text-red-800' : 
                               ($request->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                            {{ ucfirst($request->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Weekly Receipts -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Penerimaan Minggu Ini</h3>
            <div class="space-y-4">
                @foreach($weeklyReceipts as $receipt)
                <div class="border-l-4 border-green-500 pl-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ $receipt->bloodRequest->blood_type }}{{ $receipt->bloodRequest->rhesus == 'positive' ? '+' : '-' }}
                                • {{ $receipt->bloodRequest->quantity }} kantong
                            </p>
                            <p class="text-sm text-gray-600">
                                Diterima: {{ $receipt->actual_arrival->format('d M Y H:i') }}
                            </p>
                        </div>
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                            Delivered
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection