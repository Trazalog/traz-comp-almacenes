<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>TRAZALOG | TOOLS</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?php echo base_url(); ?>lib/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>lib/bower_components/font-awesome/css/font-awesome.min.css">
    <style>
        body {
            background: #f4f4f4;
            color: #333;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
        .remito-container {
            max-width: 960px;
        }
        .remito-acciones {
            max-width: 900px;
            margin: 20px auto 0;
        }
        .remito-hoja {
            background: #fff;
            color: #333;
            max-width: 900px;
            margin: 0 auto 24px;
            padding: 24px 28px;
            border: 1px solid #ddd;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .08);
            /* contiene los floats de las columnas */
            overflow: hidden;
        }
        .remito-cabecera {
            border-bottom: 2px solid #333;
            padding-bottom: 14px;
            margin-bottom: 16px;
        }
        .remito-logo {
            display: block;
            max-width: 170px;
            max-height: 70px;
            margin-bottom: 10px;
        }
        .remito-empresa-datos {
            font-size: 13px;
            line-height: 1.55;
        }
        .remito-titulo-bloque {
            text-align: right;
        }
        .remito-titulo {
            margin: 0 0 8px;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .remito-nro {
            font-size: 15px;
            font-weight: 600;
        }
        .remito-leyenda {
            margin-top: 6px;
            font-size: 11px;
            color: #666;
        }
        .remito-datos {
            margin-bottom: 16px;
        }
        .remito-dato {
            margin-bottom: 6px;
            font-size: 14px;
        }
        .remito-label {
            font-weight: 600;
        }
        .remito-tabla {
            margin-bottom: 0;
        }
        .remito-tabla > thead > tr > th {
            background: #eeeeee;
            color: #333;
            font-size: 12px;
            vertical-align: middle;
        }
        .remito-tabla > tbody > tr > td {
            font-size: 12px;
            vertical-align: top;
        }
        .remito-pie {
            margin-top: 14px;
            font-size: 18px;
            font-weight: 600;
        }
        .remito-observaciones {
            margin-top: 6px;
        }
        .remito-transportistas {
            margin-top: 22px;
            text-align: left;
        }
        .remito-transportistas h4 {
            margin-top: 0;
            font-size: 15px;
            font-weight: 700;
        }
        @media (max-width: 767px) {
            .remito-titulo-bloque {
                margin-top: 14px;
                text-align: left;
            }
        }
        @media print {
            body {
                background: #fff;
            }
            .remito-container {
                max-width: 100%;
            }
            .remito-hoja {
                max-width: 100%;
                margin: 0;
                padding: 0;
                border: 0;
                box-shadow: none;
            }
            .remito-acciones,
            .hidden-print {
                display: none !important;
            }
            .remito-tabla > thead > tr > th {
                background: #eeeeee !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
<?php
    $movimiento = isset($movimiento) && is_array($movimiento) ? $movimiento : array();
    $empresa    = isset($empresa) && is_array($empresa) ? $empresa : array();
    $demi_id    = isset($demi_id) ? $demi_id : '';

    $val = function ($v) {
        return (isset($v) && $v !== null && $v !== '') ? htmlspecialchars($v, ENT_QUOTES, 'UTF-8') : '-';
    };

    $r0 = !empty($movimiento) ? $movimiento[0] : null;

    $logo       = isset($empresa['logo']) ? $empresa['logo'] : '';
    $direccion  = isset($empresa['direccion']) ? $empresa['direccion'] : '';
    $telefono   = isset($empresa['telefono']) ? $empresa['telefono'] : '';
    $email      = isset($empresa['email']) ? $empresa['email'] : '';
    $pie        = isset($empresa['texto_pie_remito']) ? $empresa['texto_pie_remito'] : '';

    $fecha = date('d/m/Y');

    $nroRemito   = $r0 ? (isset($r0->num_comprobante) ? $r0->num_comprobante : '') : '';
    $conductor   = $r0 ? (isset($r0->conductor) ? $r0->conductor : '') : '';
    $patente     = $r0 ? (isset($r0->patente) ? $r0->patente : '') : '';
    $acoplado    = $r0 ? (isset($r0->acoplado) ? $r0->acoplado : '') : '';
    $observ      = $r0 ? (isset($r0->observaciones_recepcion) ? $r0->observaciones_recepcion : '') : '';
    $depoDestino = $r0 ? (isset($r0->descr_depo_origen) ? $r0->descr_depo_origen : '') : '';
    $estDestino  = $r0 ? (isset($r0->desc_lote_destino) ? $r0->desc_lote_destino : '') : '';

    $transportistas = isset($transportistas) && is_array($transportistas) ? $transportistas : array();
    $moin_id_remito = $r0 && isset($r0->moin_id) ? $r0->moin_id : '';

    // Justificaciones: mismo criterio que mostrarJustificaciones() del remito
    $justificaciones = array();
    $idx = 1;
    foreach ($movimiento as $row) {
        $cc = isset($row->cantidad_cargada) ? (float) $row->cantidad_cargada : null;
        $cr = isset($row->cantidad_recibida) ? (float) $row->cantidad_recibida : null;
        $just = isset($row->justificacion) ? trim((string) $row->justificacion) : '';
        if ($cc !== null && $cr !== null && $cr < $cc && $just !== '') {
            $justificaciones[] = $idx . '. ' . $just;
        }
        $idx++;
    }
?>
<div class="container-fluid remito-container">

    <?php if (!$r0): ?>
        <div class="remito-hoja">
            <div class="alert alert-warning" style="margin: 0;">
                <i class="fa fa-exclamation-triangle"></i>
                No se encontró el movimiento interno solicitado<?php echo ($demi_id !== '') ? ' (ID ' . $val($demi_id) . ')' : ''; ?>.
            </div>
        </div>
    <?php else: ?>

    <div class="remito-hoja">

        <!-- Cabecera: empresa + titulo del remito -->
        <div class="row remito-cabecera">
            <div class="col-sm-7 col-xs-12">
                <?php if ($logo !== ''): ?>
                    <img src="<?php echo htmlspecialchars($logo, ENT_QUOTES, 'UTF-8'); ?>" id="logo_remito" class="remito-logo" alt="Logo">
                <?php endif; ?>
                <div class="remito-empresa-datos">
                    <div><i class="fa fa-map-marker"></i> <?php echo $val($direccion); ?></div>
                    <div><i class="fa fa-phone"></i> <?php echo $val($telefono); ?></div>
                    <div><i class="fa fa-envelope"></i> <?php echo $val($email); ?></div>
                </div>
            </div>
            <div class="col-sm-5 col-xs-12 remito-titulo-bloque">
                <h3 class="remito-titulo">REMITO INTERNO</h3>
                <div class="remito-nro">N° <?php echo $val($nroRemito); ?></div>
                <div>FECHA <?php echo $val($fecha); ?></div>
                <div class="remito-leyenda">Documento no válido como factura</div>
            </div>
        </div>

        <!-- Datos del movimiento -->
        <div class="row remito-datos">
            <div class="col-sm-6 col-xs-12">
                <div class="remito-dato"><span class="remito-label">Est. Destino:</span> <?php echo $val($estDestino); ?></div>
                <div class="remito-dato"><span class="remito-label">Conductor:</span> <?php echo $val($conductor); ?></div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div class="remito-dato"><span class="remito-label">Depo. Destino:</span> <?php echo $val($depoDestino); ?></div>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="remito-dato"><span class="remito-label">Patente:</span> <?php echo $val($patente); ?></div>
                    </div>
                    <div class="col-xs-6">
                        <div class="remito-dato"><span class="remito-label">Acoplado:</span> <?php echo $val($acoplado); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detalle, justificaciones, pie y transportistas -->
        <div class="row">
            <div class="col-xs-12">

                <table class="table table-bordered table-striped remito-tabla">
                    <thead>
                        <tr>
                            <th style="width: 11%;">Cantidad Enviada</th>
                            <th style="width: 11%;">Cantidad Recibida</th>
                            <th style="width: 7%;">U. Med</th>
                            <th>Descripción</th>
                            <th style="width: 15%;">Depo. Orig</th>
                            <th style="width: 8%;">Lote</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($movimiento as $row): ?>
                            <?php
                                $cantCargada  = isset($row->cantidad_cargada) ? $row->cantidad_cargada : '-';
                                $cantRecibida = (isset($row->cantidad_recibida) && $row->cantidad_recibida !== null && $row->cantidad_recibida !== '') ? $row->cantidad_recibida : '-';
                                $um           = isset($row->unidad_medida) ? $row->unidad_medida : '-';
                                $desc         = isset($row->descripcion_articulo) ? $row->descripcion_articulo : '-';
                                $depoOrig     = isset($row->descr_depo_origen) ? $row->descr_depo_origen : '-';
                                $lote         = isset($row->lote_id_origen) ? $row->lote_id_origen : '-';
                            ?>
                            <tr>
                                <td><?php echo $val($cantCargada); ?></td>
                                <td><?php echo $val($cantRecibida); ?></td>
                                <td><?php echo $val($um); ?></td>
                                <td><?php echo $val($desc); ?></td>
                                <td><?php echo $val($depoOrig); ?></td>
                                <td><?php echo $val($lote); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php if (!empty($justificaciones)): ?>
                    <div style="margin-top: 15px;">
                        <strong>Justificación:</strong><br>
                        <div><?php echo implode('<br>', array_map(function ($j) {
                            return htmlspecialchars($j, ENT_QUOTES, 'UTF-8');
                        }, $justificaciones)); ?></div>
                    </div>
                <?php endif; ?>

                <div class="remito-pie"><?php echo $val($pie); ?></div>
                <?php if ($observ !== '' && $observ !== null): ?>
                    <div class="remito-observaciones"><?php echo $val($observ); ?></div>
                <?php endif; ?>

                <!-- Transportistas del remito -->
                <div id="seccionTransportistasRemito" class="remito-transportistas">
                    <h4>Transportistas</h4>
                    <table id="tablaTransportistasRemito" class="table table-bordered table-striped" style="margin-bottom: 10px;">
                        <thead>
                            <tr>
                                <th style="width: 70%;">Nombre Transportista</th>
                                <th style="width: 30%;">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($transportistas)): ?>
                                <tr class="fila-sin-transportistas">
                                    <td colspan="2">Sin transportistas registrados.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($transportistas as $t): ?>
                                    <?php
                                        $fecTxt = isset($t->fec_alta_txt) ? $t->fec_alta_txt : '';
                                        if ($fecTxt === '' && !empty($t->fec_alta)) {
                                            $fecTxt = date('d/m/Y H:i', strtotime($t->fec_alta));
                                        }
                                    ?>
                                    <tr>
                                        <td><?php echo $val(isset($t->transportista) ? $t->transportista : ''); ?></td>
                                        <td><?php echo $val($fecTxt); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div class="hidden-print" style="margin-top: 10px;">
                        <div class="row">
                            <div class="col-sm-8" style="margin-bottom: 8px;">
                                <input type="text" id="inputNombreTransportista" class="form-control"
                                       placeholder="Ingrese nombre Transportista"
                                       onkeydown="if(event.key==='Enter'){event.preventDefault();agregarTransportistaRemito();}">
                            </div>
                            <div class="col-sm-4">
                                <button type="button" class="btn btn-success" id="btnAgregarTransportista" onclick="agregarTransportistaRemito()">
                                    Agregar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="hidden-print remito-acciones text-right">
        <button type="button" class="btn btn-primary" onclick="window.print()">
            <i class="fa fa-print"></i> Imprimir
        </button>
    </div>

    <?php endif; ?>
</div>
<script>
var MOIN_ID_REMITO = <?php echo json_encode((string) $moin_id_remito); ?>;
var URL_GUARDAR_TRANSPORTISTA = <?php echo json_encode(base_url() . 'index.php/' . ALM . 'Movimientointerno/guardarTransportistaMovimiento'); ?>;

function agregarTransportistaRemito() {
    var input = document.getElementById('inputNombreTransportista');
    var btn = document.getElementById('btnAgregarTransportista');
    var nombre = (input.value || '').trim();

    if (nombre === '') {
        input.focus();
        return;
    }

    if (!MOIN_ID_REMITO) {
        alert('No se pudo identificar el movimiento para guardar el transportista.');
        return;
    }

    btn.disabled = true;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', URL_GUARDAR_TRANSPORTISTA, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState !== 4) {
            return;
        }

        btn.disabled = false;

        if (xhr.status < 200 || xhr.status >= 300) {
            alert('No se pudo guardar el transportista.');
            return;
        }

        var rsp = null;
        try {
            rsp = JSON.parse(xhr.responseText);
        } catch (e) {
            rsp = null;
        }

        if (!rsp || !rsp.status) {
            alert((rsp && rsp.msg) ? rsp.msg : 'No se pudo guardar el transportista.');
            return;
        }

        var tbody = document.querySelector('#tablaTransportistasRemito tbody');
        var placeholder = tbody.querySelector('.fila-sin-transportistas');
        if (placeholder) {
            placeholder.remove();
        }

        var tr = document.createElement('tr');

        var tdNombre = document.createElement('td');
        tdNombre.textContent = nombre;

        var tdFecha = document.createElement('td');
        tdFecha.textContent = rsp.fec_alta_txt || '';

        tr.appendChild(tdNombre);
        tr.appendChild(tdFecha);
        tbody.appendChild(tr);

        input.value = '';
        input.focus();
    };

    xhr.send(
        'moin_id=' + encodeURIComponent(MOIN_ID_REMITO) +
        '&transportista=' + encodeURIComponent(nombre)
    );
}
</script>
</body>
</html>
