@extends('layouts.app')

@section('title', 'Tracking Downtime')

@section('content')

<x-card title="Ringkasan Downtime per Mesin">

    {{-- FILTER & ACTIONS --}}
    <div class="flex flex-wrap gap-2 mb-4 items-center">
        {{-- FILTER FORM --}}
        <form method="GET" class="flex gap-2">
            <input type="date" name="date"
                value="{{ request('date', $date) }}"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
            >

            <button class="bg-blue-600 text-white px-6 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Filter
            </button>
        </form>

        <div class="w-px h-8 bg-gray-300 mx-2"></div> {{-- Divider --}}

        {{-- ACTIONS --}}
        <a href="{{ route('downtime.tracking.pdf', $date) }}"
           style="background-color: #dc2626; color: white;"
           class="inline-flex items-center justify-center px-6 py-2 rounded-md text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            Download PDF
        </a>

        <a href="{{ url('/export/downtime/'.$date) }}"
           style="background-color: #16a34a; color: white;"
           class="inline-flex items-center justify-center px-6 py-2 rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            Download Excel
        </a>
    </div>

<table>
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2">Tanggal</th>
            <th class="border p-2">Mesin</th>
            <th class="border p-2 text-right">Total Downtime (menit)</th>
        </tr>
    </thead>
    <tbody>
    @foreach($summary as $row)
        <tr>
            <td class="border p-2">{{ $row->downtime_date }}</td>
            <td class="border p-2">{{ $row->machine_code }}</td>
            <td class="border p-2 text-right">{{ $row->total_minutes }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</x-card>

<x-card title="Detail Downtime">



<table>
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2">Tanggal</th>
            <th class="border p-2">Operator</th>
            <th class="border p-2">Mesin</th>
            <th class="border p-2 text-right">Durasi (menit)</th>
            <th class="border p-2">Catatan</th>
        </tr>
    </thead>
    <tbody>
    @foreach($list as $row)
        <tr>
            <td class="border p-2">{{ $row->downtime_date }}</td>
            <td class="border p-2">{{ $row->operator_code }}</td>
            <td class="border p-2">{{ $row->machine_code }}</td>
            <td class="border p-2 text-right">{{ $row->duration_minutes }}</td>
            <td class="border p-2">{{ $row->note }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</x-card>

@endsection
