@extends('layouts.rs')

@section('title', 'Riwayat Permintaan')
@section('header', 'Riwayat Permintaan Darah')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Golongan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Distribusi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($requests as $request)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">#{{ str_pad($request->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $request->blood_type }}{{ $request->rhesus == 'positive' ? '+' : '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $request->quantity }} kantong</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm">
                                {{ ucfirst($request->status) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            @if($request->distributions && $request->distributions->isNotEmpty())
                                <ul class="list-disc pl-5">
                                    @foreach($request->distributions as $d)
                                        <li>#{{ str_pad($d->id,5,'0',STR_PAD_LEFT) }} — {{ $d->status }} @if($d->actual_arrival) ({{ $d->actual_arrival->format('d/m H:i') }}) @endif</li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-gray-400">Belum ada distribusi</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $request->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @endforeach

                    @if($requests->isEmpty())
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-history fa-3x mb-4 opacity-20"></i>
                                <p>Belum ada riwayat permintaan</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
