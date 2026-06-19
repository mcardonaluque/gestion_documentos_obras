<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $titulo }}</title>
    <style>
        @page {
            margin: 105px 35px 70px 35px;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #1f2937;
            font-size: 12px;
        }

        header {
            position: fixed;
            top: -85px;
            left: 0;
            right: 0;
            height: 70px;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 8px;
        }

        footer {
            position: fixed;
            bottom: -55px;
            left: 0;
            right: 0;
            height: 45px;
            border-top: 1px solid #d1d5db;
            color: #6b7280;
            font-size: 11px;
            padding-top: 8px;
        }

        .titulo {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            color: #111827;
        }

        .meta {
            margin-top: 8px;
            display: flex;
            justify-content: space-between;
            color: #4b5563;
            font-size: 11px;
        }

        .grupo {
            margin-bottom: 18px;
            page-break-inside: avoid;
        }

        .grupo-header {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 8px 10px;
            font-size: 12px;
            display: flex;
            justify-content: space-between;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        th,
        td {
            border: 1px solid #e5e7eb;
            padding: 6px 8px;
            vertical-align: top;
        }

        th {
            background: #f9fafb;
            text-align: left;
            font-size: 11px;
        }

        .text-right {
            text-align: right;
        }

        .subtotal {
            background: #f9fafb;
            font-weight: 700;
        }

        .totales {
            margin-top: 18px;
            border: 1px solid #d1d5db;
            background: #f3f4f6;
            padding: 10px;
            font-weight: 700;
        }

        .pagina:after {
            content: counter(page);
        }
    </style>
</head>
<body>
<header>
    <p class="titulo">{{ $titulo }}</p>
    <div class="meta">
        <span>Fecha de generación: {{ $resultado->generadoEn->format('d/m/Y H:i') }}</span>
        <span>Agrupación: {{ \App\Enums\InformeExpedientesAgrupacion::options()[$resultado->filtros->agrupacion->value] }}</span>
    </div>
</header>

<footer>
    <div style="display: flex; justify-content: space-between;">
        <span>Aplicación de gestión de documentos y obras</span>
        <span>Página <span class="pagina"></span></span>
    </div>
</footer>

<main>
    @foreach ($resultado->grupos as $grupo)
        <section class="grupo">
            <div class="grupo-header">
                <span>{{ $grupo->etiqueta }}</span>
                <span>
                    Expedientes: {{ $grupo->totalExpedientes() }}
                    | Importe aprobado: {{ number_format($grupo->totalImporteAprobado(), 2, ',', '.') }} €
                </span>
            </div>

            <table>
                <thead>
                <tr>
                    <th>Expediente</th>
                    <th>Nombre obra</th>
                    <th>Estado</th>
                    <th>Municipio</th>
                    <th class="text-right">Año</th>
                    <th class="text-right">Importe aprobado</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($grupo->filas as $fila)
                    <tr>
                        <td>{{ $fila->expedienteId }}</td>
                        <td>{{ $fila->nombreObra }}</td>
                        <td>{{ $fila->estado }}</td>
                        <td>{{ $fila->municipio }}</td>
                        <td class="text-right">{{ $fila->anioEjecucion }}</td>
                        <td class="text-right">{{ number_format($fila->importeAprobado, 2, ',', '.') }} €</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr class="subtotal">
                    <td colspan="5" class="text-right">Subtotal {{ $grupo->etiqueta }}</td>
                    <td class="text-right">{{ number_format($grupo->totalImporteAprobado(), 2, ',', '.') }} €</td>
                </tr>
                </tfoot>
            </table>
        </section>
    @endforeach

    <div class="totales">
        Total general | Expedientes: {{ $resultado->totalExpedientes }}
        | Importe aprobado: {{ number_format($resultado->totalImporteAprobado, 2, ',', '.') }} €
    </div>
</main>
</body>
</html>
