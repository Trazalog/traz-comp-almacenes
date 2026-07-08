<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Movimientos internos</h3>
    </div><!-- /.box-header -->
    <div class="box-body">

    <div class="row">
        <div class="col-xs-12">

            <button class="btn btn-primary" style="min-width: 150px; margin: 10px;" onclick="nuevaSalida()">
                <i class="fa fa-arrow-up"></i> Nueva Salida
            </button>

            <button class="btn btn-primary" style="min-width: 150px; margin: 10px;" onclick="nuevaRecepcion()">
                <i class="fa fa-arrow-down"></i> Nueva Recepción
            </button>

        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
        <table id="movimientos" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th class="text-center">Acciones</th>
                    <th>Remito</th>
                    <th>Fecha y Hora</th>
                    <th>Deposito Origen</th>
                    <th>Deposito Destino</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                ?>
            </tbody>
        </table>
    </div><!-- /.box-body -->
</div><!-- /.box -->


<!-- MODAL RECEPCION DEPOSITO -->
<div class="modal fade" id="modalRecepcion" tabindex="-1" role="dialog" aria-labelledby="modalRecepcionLabel">
    <div class="modal-dialog modal-lg" role="document" style="width: 90%; max-width: 1200px;">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color:white;">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalRecepcionBody">
                <p class="text-center">
                    <i class="fa fa-spinner fa-spin"></i> Cargando...
                </p>
            </div>
            
        </div>
    </div>
</div>
<br>
<!-- MODAL SALIDA DEPOSITO -->
<div class="modal fade" id="modalSalida" tabindex="-1" role="dialog" aria-labelledby="modalSalidaLabel">
    <div class="modal-dialog modal-lg" role="document" style="width: 90%; max-width: 1200px;">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color:white;">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalSalidaBody">
                <p class="text-center">
                    <i class="fa fa-spinner fa-spin"></i> Cargando...
                </p>
            </div>
            
        </div>
    </div>
</div>

<div id="modalContainer"></div>

<script>

 var table = $('#movimientos').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "<?php echo base_url(ALM) ?>Movimientointerno/getMovimientoInternoPaginado",
        "type": "POST"
    },
    "columns": [
        {
            "data": null,
            "orderable": false,
            "className": "text-center",
            "render": function(data, type, row) {
                return '<i class="fa fa-print" style="cursor: pointer; margin: 3px;" title="Imprimir" data-demiid="' + row.demi_id + '" onclick="modalReimpresion(this)"></i>';
            }
        },
        { 
            "data": null,
            "render": function(data, type, row) {
                return row.num_comprobante;
            }
        },
        { 
            "data": null,
            "render": function(data, type, row) {
                return row.fec_alta;
            }
        },
        { 
            "data": null,
            "render": function(data, type, row) {
                return row.nombre_depo_origen;
            }
        },
        { 
            "data": null,
            "render": function(data, type, row) {
                return row.nombre_depo_destino;
            }
        },
        { 
            "data": null,
            "render": function(data, type, row) {
                return row.estado;
            }
        }
    ],
     lengthMenu: [
        [10, 25, 50, 100, 500, 1000],
        [10, 25, 50, 100, 500, 1000]
    ],
    pageLength: 10,
    language: {
        url: '<?php echo base_url() ?>lib/bower_components/datatables.net/js/es-ar.json' //Ubicacion del archivo con el json del idioma.
    },
    dom: 'lBfrtip',
    destroy: true,
    searchDelay: 700,
    initComplete: function() {
        var api = this.api();
        $('.dataTables_filter input')
            .off('.DT')
            .on('keyup.DT input.DT', function(e) {
                var value = this.value;
                if (e.type === 'keyup' && e.keyCode === 13) {
                    clearTimeout(window.searchTimeout);
                    api.search(value).draw();
                    return;
                }
                clearTimeout(window.searchTimeout);
                window.searchTimeout = setTimeout(function() {
                    if (value.length >= 2 || value.length === 0) {
                        api.search(value).draw();
                    }
                }, 700);
            });
    },
    buttons: [{
            //Botón para Excel
            extend: 'excel',
            exportOptions: {
                columns: [1, 2, 3, 4, 5]
            },
            footer: true,
            title: 'Reporte Movimientos Internos',
            filename: 'Reporte_Movimientos_Internos',
            className: 'btn btn-success btn-flat ml-1',
            text: 'Exportar a Excel <i class="fa fa-file-excel-o"></i>',
            action: function (e, dt, button, config) {
                var self = this;
                var oldLength = dt.page.len();
                dt.page.len(1000000);
                dt.one('draw', function () {
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
                    setTimeout(function() {
                        dt.page.len(oldLength).draw();
                    }, 100);
                });
                dt.draw();
            },
            messageTop: function () {
                var f = new Date();
                var fecha = (f.getDate() < 10 ? '0' : '') + f.getDate() + "/" + ((f.getMonth() + 1) < 10 ? '0' : '') + (f.getMonth() + 1) + "/" + f.getFullYear();
                return "Fecha de reporte: " + fecha;
            }
        },
        //Botón para PDF
        {
            extend: 'pdf',
            orientation: 'landscape',
            pageSize: 'A4',
            exportOptions: {
                columns: [1, 2, 3, 4, 5]
            },
            footer: true,
            title: 'Reporte Movimientos Internos',
            filename: 'Reporte_Movimientos_Internos',
            className: 'btn btn-danger btn-flat ml-1',
            text: 'Exportar a PDF <i class="fa fa-file-pdf-o"></i>',
            action: function (e, dt, button, config) {
                var self = this;
                var oldLength = dt.page.len();
                dt.page.len(1000000);
                dt.one('draw', function () {
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config);
                    setTimeout(function() {
                        dt.page.len(oldLength).draw();
                    }, 100);
                });
                dt.draw();
            },
            messageTop: function () {
                var f = new Date();
                var fecha = (f.getDate() < 10 ? '0' : '') + f.getDate() + "/" + ((f.getMonth() + 1) < 10 ? '0' : '') + (f.getMonth() + 1) + "/" + f.getFullYear();
                return "Fecha de reporte: " + fecha;
            },
            customize: function (doc) {
                // Remover el título original
                var title = doc.content[0].text;
                doc.content.splice(0, 1);

                // Agregar Cabecera: Título Izquierda, Logo Derecha
                var headerColumns = [
                    {
                        text: title,
                        fontSize: 22,
                        bold: true,
                        alignment: 'left',
                        margin: [0, 10, 0, 0]
                    }
                ];

                <?php if (!empty($logo)) { ?>
                headerColumns.push({
                    image: '<?php echo $logo; ?>',
                    width: 100,
                    alignment: 'right',
                    margin: [0, 0, 0, 0]
                });
                <?php } ?>

                doc.content.splice(0, 0, {
                    columns: headerColumns,
                    margin: [0, 0, 0, 20]
                });

                // Ajustar messageTop (Fecha) - Ahora está en index 1
                doc.content[1].alignment = 'left';
                doc.content[1].margin = [0, 0, 0, 10];

                // Estilo general para que quepa todo
                doc.defaultStyle.fontSize = 9;

                // Estilo de la tabla
                doc.styles.tableHeader.fillColor = '#dd4b39';
                doc.styles.tableHeader.color = 'white';
                doc.styles.tableHeader.alignment = 'center';
                doc.styles.tableHeader.fontSize = 10;

                // Hacer que la tabla ocupe todo el ancho con anchos proporcionales
                var tableIndex = doc.content.length - 1;
                doc.content[tableIndex].table.widths = ['15%', '20%', '25%', '25%', '15%'];
            }
        },
        {
            extend: 'copy',
            exportOptions: {
                columns: [1, 2, 3, 4, 5]
            },
            footer: true,
            title: 'Reporte Movimientos Internos',
            filename: 'Reporte_Movimientos_Internos',
            className: 'btn btn-primary btn-flat ml-1',
            text: 'Copiar <i class="fa fa-file-text-o"></i>',
            action: function (e, dt, button, config) {
                var self = this;
                var oldLength = dt.page.len();
                dt.page.len(1000000);
                dt.one('draw', function () {
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                    setTimeout(function() {
                        dt.page.len(oldLength).draw();
                    }, 100);
                });
                dt.draw();
            }
        },
                
        {
            extend: 'print',
            exportOptions: {
                columns: [1, 2, 3, 4, 5]
            },
            footer: true,
            title: 'Reporte Movimientos Internos',
            filename: 'Reporte_Movimientos_Internos',
            className: 'btn btn-default btn-flat ml-1',
            text: 'Imprimir <i class="fa fa-print"></i>',
            action: function (e, dt, button, config) {
                var self = this;
                // 1. Guardar la paginación actual
                var oldLength = dt.page.len();
                
                // 2. Cambiar la longitud a un número grande para traer todos los registros del servidor
                dt.page.len(1000000);
                
                // 3. Listener por única vez al terminar de renderizar los datos
                dt.one('draw', function () {
                    // Invocar la acción original de impresión
                    $.fn.dataTable.ext.buttons.print.action.call(self, e, dt, button, config);
                    
                    // 4. Restaurar la longitud de página original tras un pequeño delay
                    setTimeout(function() {
                        dt.page.len(oldLength).draw();
                    }, 100);
                });
                
                // 5. Disparar el dibujado con la nueva longitud
                dt.draw();
            },
            messageTop: function () {
                var f = new Date();
                var fecha = (f.getDate() < 10 ? '0' : '') + f.getDate() + "/" + ((f.getMonth() + 1) < 10 ? '0' : '') + (f.getMonth() + 1) + "/" + f.getFullYear();
                return "Fecha de reporte: " + fecha;
            },
            customize: function (win) {
                // Remover links y scripts vacíos o rotos que causan error 404 /index en CodeIgniter
                $(win.document.head).find('link[href=""], link[href="#"], link:not([href])').remove();
                $(win.document.head).find('script[src=""], script[src="#"]').remove();

                $(win.document.body).find('tr[data-json]').removeAttr('data-json');

                // Remover el título original H1
                $(win.document.body).find('h1').remove();
                
                // Cabecera solo de texto
                var cabecera = '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #dd4b39; padding-bottom: 10px;">' +
                               '  <h1 style="margin: 0; font-size: 22pt; font-weight: bold; color: #333;">Reporte Movimientos Internos</h1>' +
                               '</div>';
                $(win.document.body).prepend(cabecera);

                // Estilizar el bloque de filtros (messageTop)
                $(win.document.body).find('div').each(function() {
                    if ($(this).text().indexOf('Fecha de reporte:') !== -1) {
                        $(this).css({
                            'white-space': 'pre-line',
                            'font-size': '10pt',
                            'margin-bottom': '15px',
                            'line-height': '1.5',
                            'background-color': '#f9f9f9',
                            'padding': '10px',
                            'border': '1px solid #ddd',
                            'border-radius': '4px'
                        });
                    }
                });

                // Estilo general de la página
                $(win.document.body).css('font-size', '9pt');

                // Estilo de la tabla
                $(win.document.body).find('table')
                    .addClass('compact')
                    .css('font-size', '9pt')
                    .css('width', '100%');

                // Estilo de los encabezados para coincidir con el PDF
                $(win.document.body).find('th').css({
                    'background-color': '#dd4b39',
                    'color': 'white',
                    'text-align': 'center',
                    'font-size': '10pt',
                    'padding': '8px'
                });
            }
        } 
    ]
 });

 function verDetalle(movimiento) {
     console.log("Detalle del movimiento:", movimiento);
     // Aquí se puede implementar la lógica para abrir un modal con los detalles del movimiento si es necesario
 }

function nuevaRecepcion(e) {
    wo();

    // Limpiar y abrir el modal
    $('#modalRecepcionBody').html('<p class="text-center"><i class="fa fa-spinner fa-spin"></i> Cargando...</p>');
    $('#modalRecepcion').modal('show');

    wo();
    $.ajax({
        type: 'GET',
        data: {},
        url: '<?php echo base_url(ALM) ?>Movimientointerno/movimientoRecepcion',
        success: function(rsp) {

            // 1. Inyectamos la vista en el modal
            $('#modalRecepcionBody').html(rsp);
            
        },
        error: function() {
            $('#modalRecepcionBody').html('<div class="alert alert-danger"><i class="fa fa-times-circle"></i> Error al cargar los datos de trazabilidad.</div>');
        },
        complete: function() {
            wc();
        }
    });
}


function nuevaSalida(e) {
    wo();

    // Limpiar y abrir el modal
    $('#modalSalidaBody').html('<p class="text-center"><i class="fa fa-spinner fa-spin"></i> Cargando...</p>');
    $('#modalSalida').modal('show');

    wo();
    $.ajax({
        type: 'GET',
        data: {},
        url: '<?php echo base_url(ALM) ?>Movimientointerno/movimientoSalida',
        success: function(rsp) {

            // 1. Inyectamos la vista en el modal
            $('#modalSalidaBody').html(rsp);
            
        },
        error: function() {
            $('#modalSalidaBody').html('<div class="alert alert-danger"><i class="fa fa-times-circle"></i> Error al cargar los datos de trazabilidad.</div>');
        },
        complete: function() {
            wc();
        }
    });
}

// Función asíncrona para mostrar el modal de reimpresión de Remito
async function modalReimpresion(element) {
    try {
        wo();
        var fila = $(element).closest('tr');
        var celdas = fila.find('td');

        // Esperar a que los datos de la empresa se carguen
        var dataEmpresa = await DatosEmpresaRemito();

        // referencia es el demi_id de movimientos_internos
        var referencia = $(element).data('demiid');

        // Llamar a la función que obtiene los datos del movimiento de remito
        var dataRemito = await datosMovimientoRemito(referencia);
        // Realizar la llamada AJAX para cargar el modal
        $.ajax({
            url: '<?php echo base_url(ALM); ?>Movimientointerno/modalReImpresion',
            type: 'GET',
            success: function(response) {

                console.log(dataRemito);
                $('#modalContainer').html(response); // Cargar el modal en el contenedor
                $('#modalRemito').modal('show');

                // Asignar los datos de la empresa al modal
                var logoUrl = (dataEmpresa && dataEmpresa.logo && dataEmpresa.logo.valor) ? dataEmpresa.logo.valor : '';
                var direccionVal = (dataEmpresa && dataEmpresa.direccion && dataEmpresa.direccion.valor) ? dataEmpresa.direccion.valor : '-';
                var telefonoVal = (dataEmpresa && dataEmpresa.telefono && dataEmpresa.telefono.valor) ? dataEmpresa.telefono.valor : '-';
                var emailVal = (dataEmpresa && dataEmpresa.email && dataEmpresa.email.valor) ? dataEmpresa.email.valor : '-';
                var pieVal = (dataEmpresa && dataEmpresa.texto_pie_remito && dataEmpresa.texto_pie_remito.valor) ? dataEmpresa.texto_pie_remito.valor : '-';

                document.getElementById('logo_remito').src = logoUrl;
                $('#direccion_remito').html('<small>' + direccionVal + '</small>');
                $('#telefono_remito').html('<small>' + telefonoVal + '</small>');
                $('#email_remito').html('<small>' + emailVal + '</small>');
                $('#texto_pie_remito').html('<strong>' + pieVal + '</strong>');

                // Asignar los datos del movimiento de remito al modal
                var r0 = (dataRemito && dataRemito.length > 0) ? dataRemito[0] : {};

                $('#conductor_remito').text(r0.conductor || '-');
                $('#patente_acoplado_remito').text(r0.acoplado || '-');
                $('#patente_remito').text(r0.patente || '-');
                $('#observaciones_remito').text(r0.observaciones_recepciones || '-');
                $('#nroRemito').text(r0.num_comprobante || '-');


                $('#depo_destino_remito').text(r0.descr_depo_origen || '-');
                $('#establecimiento_destino_remito').text(r0.desc_lote_destino || '-');

                $("#observaciones_remito").text(r0.observaciones_recepcion || '-');

                // Limpiar la tabla antes de agregar nuevas filas
                var tablaDetalle = $('#tabla_detalle tbody');
                tablaDetalle.empty();

                // Verifica si dataRemito es un arreglo
                if (Array.isArray(dataRemito)) {
                    // Recorrer el arreglo de datos y agregar cada fila a la tabla
                    dataRemito.forEach(function(datos) {
                        var cantCargada = (datos.cantidad_cargada !== undefined && datos.cantidad_cargada !== null) ? datos.cantidad_cargada : '-';
                        var cantRecibida = (datos.cantidad_recibida !== undefined && datos.cantidad_recibida !== null && datos.cantidad_recibida !== '') ? datos.cantidad_recibida : '-';
                        var um = datos.unidad_medida || '-';
                        var desc = datos.descripcion_articulo || '-';
                        var depoOrig = datos.descr_depo_origen || '-';
                        var lote = datos.lote_id_origen || '-';

                        var fila = `
                            <tr>
                                <td style="text-align: left;">${cantCargada}</td>  <!-- Alineado a la derecha -->
                                <td style="text-align: left;">${cantRecibida}</td>  <!-- Alineado a la derecha -->
                                <td style="text-align: left;">${um}</td>
                                <td style="text-align: left;">${desc}</td>    <!-- Alineado a la izquierda -->
                                <td style="text-align: left;">${depoOrig}</td>
                                <td style="text-align: left;">${lote}</td>
                            </tr>
                        `;

                        // Agregar la fila a la tabla
                        tablaDetalle.append(fila);
                    });

                    // Mostrar justificaciones si existen
                    if (typeof mostrarJustificaciones === 'function') {
                        mostrarJustificaciones(dataRemito);
                    }
                    wc();
                } else {
                    console.error('dataRemito no es un arreglo:', dataRemito);
                    wc();
                }

            },
            error: function(xhr, status, error) {
                console.error('Error al cargar el modal:', error);
                wc();
            }
        });
    } catch (error) {
        console.error('Error en modalReimpresion:', error);
        wc();
    }
}

// Función que obtiene los datos del movimiento del remito (retorna una promesa)
function datosMovimientoRemito(data) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: '<?php echo base_url(ALM); ?>Movimientointerno/datosMovimientoRemito',
            data: {
                data
            },
            type: 'POST',
            success: function(response) {
                try {
                    var resp = JSON.parse(response);
                    resolve(resp);
                    debugger;
                } catch (error) {
                    reject('Error al parsear la respuesta: ' + error);
                }
            },
            error: function(xhr, status, error) {
                reject('Error en la llamada AJAX: ' + error);
            }
        });
    });
}

// Función que trae los datos de la empresa para la cabecera del remito
async function DatosEmpresaRemito() {
    try {
        // Realizar la llamada AJAX de manera sincrónica usando await
        const response = await $.ajax({
            type: 'POST',
            data: {},
            url: 'index.php/<?php echo ALM?>Movimientointerno/getDatosCabeceraRemito'
        });

        const resp = JSON.parse(response);

        // Imprimir los datos parseados
        console.log('Datos parseados:', resp);

        return resp || {};

    } catch (error) {
        console.error('Error en DatosEmpresaRemito:', error);
        return {};
    }
}

// Solución para restablecer el scroll cuando se cierran modales anidados
$(document).on('hidden.bs.modal', '.modal', function () {
    if ($('.modal:visible').length > 0) {
        $('body').addClass('modal-open');
    }
});

// Interceptar guardado de nueva salida o nueva recepción para cerrar modal y recargar tabla
$(document).ajaxSuccess(function(event, xhr, settings) {
    if (settings.url && settings.url.indexOf('Movimientodeposalida/guardar') !== -1) {
        $('#modalSalida').modal('hide');
        if ($.fn.DataTable.isDataTable('#movimientos')) {
            $('#movimientos').DataTable().ajax.reload();
        }
    }
    if (settings.url && settings.url.indexOf('Movimientodeporecepcion/guardar') !== -1) {
        $('#modalRecepcion').modal('hide');
        if ($.fn.DataTable.isDataTable('#movimientos')) {
            $('#movimientos').DataTable().ajax.reload();
        }
    }
});

</script>