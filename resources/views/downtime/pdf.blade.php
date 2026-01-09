<!DOCTYPE html>
<html>
<head>
    <title>Laporan Downtime</title>
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
            margin-bottom: 20px;
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
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Downtime</h2>
        <p>Tanggal: {{ $date }}</p>
    </div>

    <h3>Ringkasan per Mesin</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Mesin</th>
                <th class="text-right">Total Downtime (menit)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($summary as $index => $row)
                <tr>
                    <td style="text-align: center; width: 5%;">{{ $index + 1 }}</td>
                    <td>{{ $machineNames[$row->machine_code] ?? $row->machine_code }}</td>
                    <td class="text-right">{{ $row->total_minutes }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Tidak ada data downtime.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Detail Downtime</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 20%">Operator</th>
                <th style="width: 20%">Mesin</th>
                <th style="width: 15%" class="text-right">Durasi (menit)</th>
                <th style="width: 40%">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($list as $index => $row)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $row->operator_code }}</td>
                    <td>{{ $machineNames[$row->machine_code] ?? $row->machine_code }}</td>
                    <td class="text-right">{{ $row->duration_minutes }}</td>
                    <td>{{ $row->note }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada detail downtime.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
