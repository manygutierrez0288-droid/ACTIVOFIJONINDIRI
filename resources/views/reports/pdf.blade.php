<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $title ?? 'Reporte' }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
        }

        .header-table {
            width: 100%;
            border-bottom: 3px solid #1e3a8a;
            /* Azul Medianoche */
            padding-bottom: 15px;
            margin-bottom: 10px;
        }

        .header-logo {
            width: 100px;
            text-align: left;
        }

        .header-info {
            text-align: right;
            vertical-align: middle;
        }

        .header-info h1 {
            margin: 0;
            font-size: 18px;
            font-weight: 900;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-info h2 {
            margin: 2px 0;
            font-size: 12px;
            color: #64748b;
            /* Gris Pizarra */
            font-weight: bold;
            text-transform: uppercase;
        }

        .header-info h3 {
            margin: 5px 0 0 0;
            font-size: 11px;
            color: #1e3a8a;
            font-weight: bold;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 15px;
            font-size: 9px;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.data-table th {
            background-color: #1e3a8a;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            padding: 8px 4px;
            border: 1px solid #1e3a8a;
        }

        table.data-table td {
            border: 1px solid #e2e8f0;
            padding: 6px 4px;
            text-align: left;
        }

        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
        }

        .totals-row {
            background-color: #f1f5f9 !important;
            font-weight: bold;
            color: #1e3a8a;
        }
    </style>
</head>

<body>
    <table class="header-table">
        <tr>
            <td class="header-logo" style="width: 20%;">
                <img src="{{ public_path('images/logo_alcaldia_nindiri.png') }}" style="height: 85px;" alt="Logo">
            </td>
            <td class="header-info" style="width: 60%; text-align: center;">
                <h1>Alcaldía Municipal de Nindirí</h1>
                <h2>Departamento de Activos Fijos</h2>
                <h3>{{ $title }}</h3>
            </td>
            <td style="width: 20%;"></td> <!-- Espacio para balancear el centrado -->
        </tr>
    </table>

    <table class="meta-table">
        <tr>
            <td style="text-align: left;">EMITIDO EL: {{ date('d/m/Y H:i') }}</td>
            <td style="text-align: right;">USUARIO: {{ auth()->user()->name ?? 'SISTEMA' }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                @foreach ($headers as $header)
                    <th>{{ str_replace('_', ' ', $header) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    @foreach ($row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="totals-row">
                @php
                    $totals = [];
                    foreach ($headers as $index => $header) {
                        $sum = 0;
                        $isNumeric = true;
                        foreach ($data as $row) {
                            $value = array_values((array) $row)[$index] ?? 0;
                            $cleanValue = preg_replace('/[^0-9.-]/', '', $value);
                            if (is_numeric($cleanValue) && $cleanValue !== '') {
                                $sum += floatval($cleanValue);
                            } else if ($value !== '' && $value !== null) {
                                $isNumeric = false;
                                break;
                            }
                        }
                        $totals[$index] = $isNumeric && $sum != 0 ? number_format($sum, 2) : '';
                    }
                @endphp
                @foreach ($headers as $index => $header)
                    <td style="border: 1px solid #e2e8f0; padding: 8px 4px;">
                        @if ($index === 0)
                            <strong>TOTALES:</strong>
                        @else
                            {{ $totals[$index] }}
                        @endif
                    </td>
                @endforeach
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        {{ $settings['report_footer'] ?? 'SIAFNIN - Sistema de Gestión de Activos Fijos' }} | Página
        <script type="text/php">if (isset($pdf)) { echo $pdf->get_page_number(); }</script>
    </div>
</body>

</html>