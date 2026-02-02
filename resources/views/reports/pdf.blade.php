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
            <td style="text-align: left;">EMITIDO EL: {{ date('d/m/Y h:i A') }}</td>
            <td style="text-align: right;">USUARIO: {{ auth()->user()->name ?? 'SISTEMA' }}</td>
        </tr>
    </table>

    @if(str_contains(strtolower($title), 'usuarios'))
        @php
            $activeCount = 0;
            $inactiveCount = 0;
            $rolesList = [];
            foreach ($data as $row) {
                // Estado is at index 3 in the ReporteService map for users (Nombre, Correo, Roles, Estado, Fecha)
                $estado = (array_values((array) $row))[3] ?? '';
                if ($estado === 'ACTIVO')
                    $activeCount++;
                else if ($estado === 'INACTIVO')
                    $inactiveCount++;

                $roles = (array_values((array) $row))[2] ?? '';
                if ($roles !== 'Sin Rol') {
                    foreach (explode(', ', $roles) as $r) {
                        if ($r)
                            $rolesList[] = $r;
                    }
                }
            }
            $rolesCount = count(array_unique($rolesList));
        @endphp

        <table style="width: 100%; margin-bottom: 20px; border-collapse: collapse;">
            <tr>
                <td style="width: 25%; padding: 10px; border: 2px solid #1e3a8a; border-radius: 10px; text-align: center;">
                    <div style="font-size: 8px; font-weight: bold; color: #64748b; text-transform: uppercase;">Total
                        Usuarios</div>
                    <div style="font-size: 16px; font-weight: 900; color: #1e3a8a;">{{ count($data) }}</div>
                </td>
                <td style="width: 25%; padding: 10px; border: 2px solid #059669; border-radius: 10px; text-align: center;">
                    <div style="font-size: 8px; font-weight: bold; color: #059669; text-transform: uppercase;">Activos</div>
                    <div style="font-size: 16px; font-weight: 900; color: #059669;">{{ $activeCount }}</div>
                </td>
                <td style="width: 25%; padding: 10px; border: 2px solid #dc2626; border-radius: 10px; text-align: center;">
                    <div style="font-size: 8px; font-weight: bold; color: #dc2626; text-transform: uppercase;">Inactivos
                    </div>
                    <div style="font-size: 16px; font-weight: 900; color: #dc2626;">{{ $inactiveCount }}</div>
                </td>
                <td style="width: 25%; padding: 10px; border: 2px solid #d97706; border-radius: 10px; text-align: center;">
                    <div style="font-size: 8px; font-weight: bold; color: #d97706; text-transform: uppercase;">Roles</div>
                    <div style="font-size: 16px; font-weight: 900; color: #d97706;">{{ $rolesCount }}</div>
                </td>
            </tr>
        </table>
    @endif

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
                    $numericColumns = ['Valor', 'Valor Adquisicion', 'Valor Residual', 'Depreciacion Acumulada', 'Valor Neto', 'Total Valor', 'Deprec. Mes', 'Deprec. Acum.', 'Valor Bruto', 'Costo', 'Valor Original', 'Valor Catastral', 'Valor Libros'];

                    foreach ($headers as $index => $header) {
                        $sum = 0;
                        $isNumeric = true;

                        // Solo procesar si el encabezado está en la lista blanca
                        if (!in_array($header, $numericColumns)) {
                            $totals[$index] = '';
                            continue;
                        }

                        foreach ($data as $row) {
                            $value = array_values((array) $row)[$index] ?? 0;
                            // Limpiar el valor: quitar símbolos de moneda, comas de miles, etc.
                            $cleanValue = preg_replace('/[^0-9.-]/', '', (string) $value);

                            if (is_numeric($cleanValue) && $cleanValue !== '') {
                                $sum += floatval($cleanValue);
                            } else if ($value !== '' && $value !== null) {
                                // Si encontramos un valor no numérico en una columna que debería serlo, 
                                // detenemos la suma para esa columna
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