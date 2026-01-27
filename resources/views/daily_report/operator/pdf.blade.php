<!DOCTYPE html>
<html>

<head>
    <title>Laporan Harian Operator</title>
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
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 5px;
            vertical-align: top;
            word-wrap: break-word;
        }

        th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .kpi-good {
            color: green;
            font-weight: bold;
        }

        .kpi-bad {
            color: red;
            font-weight: bold;
        }

        .kpi-mid {
            color: orange;
            font-weight: bold;
        }

        /* Column Widths */
        .col-shift {
            width: 25px;
            text-align: center;
        }

        .col-op {
            width: 15%;
        }

        .col-mc {
            width: 8%;
            text-align: center;
        }

        .col-item {
            width: 33%;
        }

        .col-time {
            width: 12%;
            text-align: center;
        }

        .col-num {
            width: 7%;
            text-align: right;
        }

        .col-kpi {
            width: 10%;
            text-align: center;
        }

        .signatures {
            margin-top: 40px;
            width: 100%;
            border: none;
        }

        .signatures td {
            border: none;
            text-align: center;
            vertical-align: top;
            padding-top: 60px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Laporan Harian Operator</h2>
        <p>{{ \Carbon\Carbon::parse($date)->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-shift">SF</th>
                <th class="col-op">Operator</th>
                <th class="col-mc">Mesin</th>
                <th class="col-item">Item & Heat No</th>
                <th class="col-time">Jam Kerja</th>
                <th class="col-num">Target</th>
                <th class="col-num">Aktual</th>
                <th class="col-kpi">KPI</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    <td class="text-center">{{ $row->shift }}</td>
                    <td>
                        <strong>{{ $row->operator->name ?? $row->operator_code }}</strong><br>
                        <small>{{ $row->operator_code }}</small>
                    </td>
                    <td class="col-mc">{{ $row->machine_code }}</td>
                    <td>
                        {{ $row->item->name ?? $row->item_code }}
                        @if($row->heat_number)
                            <br><small>HN: {{ $row->heat_number }}</small>
                        @endif
                        @if($row->remark)
                            <br><small style="color:red">{{ $row->remark }}</small>
                        @endif
                    </td>
                    <td class="col-time">
                        {{ number_format($row->work_hours, 2) }} Jam
                        <br><small>{{ \Carbon\Carbon::parse($row->time_start)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($row->time_end)->format('H:i') }}</small>
                    </td>
                    <td class="col-num">{{ $row->target_qty }}</td>
                    <td class="col-num">{{ $row->actual_qty }}</td>
                    <td class="col-kpi">
                        @php
                            $class = 'kpi-bad';
                            if ($row->achievement_percent >= 100)
                                $class = 'kpi-good';
                            elseif ($row->achievement_percent >= 85)
                                $class = 'kpi-mid';
                        @endphp
                        <span class="{{ $class }}">{{ $row->achievement_percent }}%</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="signatures">
        <tr>
            <td width="33%">
                Dibuat Oleh,<br><br><br>
                ( ....................... )<br>Admin
            </td>
            <td width="33%">
                Diperiksa Oleh,<br><br><br>
                ( ....................... )<br>SPV Shift
            </td>
            <td width="33%">
                Diketahui Oleh,<br><br><br>
                ( ....................... )<br>Manager
            </td>
        </tr>
    </table>

</body>

</html>