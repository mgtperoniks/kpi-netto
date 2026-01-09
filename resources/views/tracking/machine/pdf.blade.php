<!DOCTYPE html>
<html>
<head>
    <title>Laporan KPI Harian Mesin</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
        }
        th {
            background-color: #f0f0f0;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .kpi-good {
            color: green;
            font-weight: bold;
        }
        .kpi-bad {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan KPI Harian Mesin</h2>
        <p>Tanggal: {{ $date }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 25%">Mesin</th>
                <th style="width: 15%">Jam Kerja</th>
                <th style="width: 15%">Target</th>
                <th style="width: 15%">Aktual</th>
                <th style="width: 15%">KPI (%)</th>
                <th style="width: 10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{ $machineNames[$row->machine_code] ?? $row->machine_code }}
                    </td>
                    <td class="text-right">
                        {{ number_format($row->total_work_hours, 2) }}
                    </td>
                    <td class="text-right">
                        {{ $row->total_target_qty }}
                    </td>
                    <td class="text-right">
                        {{ $row->total_actual_qty }}
                    </td>
                    <td class="text-right">
                        <span class="{{ $row->kpi_percent >= 100 ? 'kpi-good' : 'kpi-bad' }}">
                            {{ number_format($row->kpi_percent, 1) }}%
                        </span>
                    </td>
                    <td class="text-center">
                        @if ($row->kpi_percent >= 100)
                            <span style="background-color: #dcfce7; color: #166534; padding: 2px 6px; border-radius: 4px; font-size: 0.8em;">OK</span>
                        @elseif ($row->kpi_percent >= 90)
                            <span style="background-color: #fef9c3; color: #854d0e; padding: 2px 6px; border-radius: 4px; font-size: 0.8em;">WARNING</span>
                        @else
                            <span style="background-color: #fee2e2; color: #991b1b; padding: 2px 6px; border-radius: 4px; font-size: 0.8em;">BAD</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">
                        Data KPI mesin tidak ditemukan untuk tanggal ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
