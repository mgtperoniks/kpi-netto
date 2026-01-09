<!DOCTYPE html>
<html>
<head>
    <title>Laporan KPI Harian Operator</title>
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
        <h2>Laporan KPI Harian Operator</h2>
        <p>Tanggal: {{ $date }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 25%">Operator</th>
                <th style="width: 15%">Jam Kerja</th>
                <th style="width: 15%">Target</th>
                <th style="width: 15%">Aktual</th>
                <th style="width: 15%">KPI (%)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{ $operatorNames[$row->operator_code] ?? $row->operator_code }}
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
                            {{ $row->kpi_percent }}%
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">
                        Data tidak ditemukan untuk tanggal ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
