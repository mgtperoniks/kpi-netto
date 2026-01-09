@extends('layouts.app')

@section('title', 'Tracking KPI Operator')

@section('content')

<x-card title="KPI Harian Operator">

    <div class="mb-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Data KPI Operator</h3>
        <p class="mt-1 text-sm text-gray-500">
            Rekapitulasi data harian per tanggal {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
        </p>
    </div>

    {{-- FILTER & ACTIONS --}}
    <div class="flex flex-wrap gap-2 mb-4 items-center">
        {{-- FILTER FORM --}}
        <form method="GET" class="flex gap-2">
            <input
                type="date"
                name="date"
                value="{{ request('date', $date) }}"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
            >

            <button class="bg-blue-600 text-white px-6 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Filter
            </button>
        </form>

        <div class="w-px h-8 bg-gray-300 mx-2"></div> {{-- Divider --}}

        {{-- ACTIONS --}}
        <a href="{{ route('tracking.operator.pdf', $date) }}"
           style="background-color: #dc2626; color: white;"
           class="inline-flex items-center justify-center px-6 py-2 rounded-md text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            Download PDF
        </a>

        <a href="{{ url('/export/operator/'.$date) }}"
           style="background-color: #16a34a; color: white;"
           class="inline-flex items-center justify-center px-6 py-2 rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            Download Excel
        </a>
    </div>

    <x-table>
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-2">Tanggal</th>
                <th class="border p-2">Operator</th>
                <th class="border p-2 text-right">Jam Kerja</th>
                <th class="border p-2 text-right">Target</th>
                <th class="border p-2 text-right">Aktual</th>
                <th class="border p-2 text-right">KPI (%)</th>
                <th class="border p-2">Detail</th>
            </tr>
        </thead>

        <tbody>
        @forelse ($rows as $row)
            <tr>
                <td class="border p-2">
                    {{ $row->kpi_date }}
                </td>

                {{-- OPERATOR (MAPPING DARI MASTER) --}}
                <td class="border p-2">
                    {{ $operatorNames[$row->operator_code] ?? $row->operator_code }}
                </td>

                <td class="border p-2 text-right">
                    {{ number_format($row->total_work_hours, 2) }}
                </td>

                <td class="border p-2 text-right">
                    {{ $row->total_target_qty }}
                </td>

                <td class="border p-2 text-right">
                    {{ $row->total_actual_qty }}
                </td>

                <td class="border p-2 text-right">
                    <span class="{{ $row->kpi_percent >= 100 ? 'kpi-good' : 'kpi-bad' }}">
                        {{ $row->kpi_percent }}%
                    </span>
                </td>

                <td class="border p-2 text-center">
                    <a href="{{ url('/tracking/operator/'.$row->operator_code.'/'.$row->kpi_date) }}"
                       class="text-blue-600 hover:underline">
                        Lihat
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="border p-4 text-center text-gray-500">
                    Data KPI tidak ditemukan untuk tanggal ini
                </td>
            </tr>
        @endforelse
        </tbody>
    </x-table>

</x-card>

@endsection
