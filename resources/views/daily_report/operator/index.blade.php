@extends('layouts.app')

@section('title', 'Daftar Harian Operator')

@section('content')

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Harian Operator</h1>
        <p class="text-gray-500">Pilih tanggal untuk melihat detail laporan harian.</p>
    </div>

    @if($dates->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-10 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900">Belum ada data produksi</h3>
            <p class="text-gray-500 mt-1">Data produksi yang diinput akan muncul di sini berdasarkan tanggal.</p>
        </div>
    @else
        <div class="flex flex-col gap-3">
            @foreach($dates as $row)
                <a href="{{ route('daily_report.operator.show', $row->production_date) }}"
                    class="group bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md hover:border-blue-400 transition-all duration-200 flex items-center justify-between">

                    <div class="flex items-center gap-4">
                        <div
                            class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors shrink-0">
                            <span
                                class="text-xs font-bold uppercase">{{ \Carbon\Carbon::parse($row->production_date)->locale('id')->isoFormat('MMM') }}</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 group-hover:text-blue-700">
                                {{ \Carbon\Carbon::parse($row->production_date)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                            </h3>
                            <p class="text-xs text-gray-400 font-mono">
                                {{ \Carbon\Carbon::parse($row->production_date)->format('d-m-Y') }} • {{ $row->total_logs }} Input
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-6 text-right">
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Total Output</p>
                            <p class="text-sm font-bold text-gray-800">{{ number_format($row->total_qty) }} <span
                                    class="text-[10px] font-normal text-gray-500">pcs</span></p>
                        </div>
                        <div class="w-px h-8 bg-gray-100"></div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Rata-rata KPI</p>
                            @php
                                $kpiClass = 'text-red-600';
                                if ($row->avg_kpi >= 100)
                                    $kpiClass = 'text-green-600';
                                elseif ($row->avg_kpi >= 85)
                                    $kpiClass = 'text-orange-600';
                            @endphp
                            <p class="text-sm font-bold {{ $kpiClass }}">{{ number_format($row->avg_kpi, 2) }}%</p>
                        </div>
                        <div class="pl-4">
                            <svg class="w-5 h-5 text-gray-300 group-hover:text-blue-500 transition-colors" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>

                </a>
            @endforeach
        </div>
    @endif

@endsection