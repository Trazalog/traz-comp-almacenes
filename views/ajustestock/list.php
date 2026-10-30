<section>
  <div class="row">
    <div class="col-xs-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Ajustes de Stock</h3>
        </div><!-- /.box-header -->

        <div class="box-body">
          <button class="btn btn-primary" style="margin-bottom: 15px;" onclick="nuevoAjusteModal()">
             Agregar
          </button>
          
          <table id="tbl-ajustes" class="table table-bordered table-hover">
            <thead>
              <tr>                
                <th width="10%">Acciones</th>
                <th>Comprobante</th>
                <th>Fecha / Hora</th>
                <th>Establecimiento</th>
                <th>Depósito</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section>

<!-- MODAL VER DETALLE AJUSTE -->
<div class="modal fade bs-example-modal" id="modalInfoAjuste" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div id="print-area">
        <div class="modal-header bg-blue">
          <button type="button" class="close close_modal_edit" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" style="color:white;">&times;</span>
          </button>
          <h4 class="modal-title" id="myModalLabel"><span class="fa fa-fw fa-search"></span> Detalle Stock</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-6">
              <label for="idAjuste">Id:</label>
              <input type="text" class="form-control habilitar" name="idAjuste" id="idAjuste" readonly>
            </div>
            <div class="col-sm-6">
              <label for="tipoAjuste" class="control-label">Tipo de Ajuste:</label>
              <input type="text" class="form-control habilitar" name="tipoAjuste" id="tipoAjuste" readonly>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <hr style="height:3px;border-width:0;color:gray;background-color:#dd4b39">
              <div class="col-sm-12">
                <label for="justificacion">Justificación:</label>
                <textarea style="resize:none" type="text" class="form-control input-sm" id="justificacion" name="justificacion" readonly></textarea>
              </div>
              <div class="col-sm-12" style="margin-top: 15px;">
                <table class="table table-bordered" id="tablaDetalleAjuste">
                  <thead>
                    <tr>
                      <th>Código</th>
                      <th>Descripción</th>
                      <th>Cantidad</th>
                      <th>Tipo Ajuste</th>
                      <th>U. Med</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="form-group text-right">
          <button type="button" class="btn btn-primary" onclick="printComprobante()">
            <i class="fa fa-print"></i> Imprimir
          </button>
          <button type="button" class="btn btn-default cerrarModalEdit" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- MODAL NUEVO AJUSTE -->
<div class="modal fade" id="modalNuevoAjuste" tabindex="-1" role="dialog" aria-labelledby="modalNuevoAjusteLabel">
  <div class="modal-dialog modal-lg" role="document" style="width: 90%; max-width: 1200px;">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" style="color:white;">&times;</span>
        </button>
        <h4 class="modal-title" id="modalNuevoAjusteLabel">Nuevo Ajuste de Stock</h4>
      </div>
      <div class="modal-body" id="modalNuevoAjusteBody">
      </div>
    </div>
  </div>
</div>

<style>
  #modalNuevoAjusteBody { padding: 5px; }
  /* La vista reutilizada trae su propio titulo de caja: en el modal es redundante */
  #modalNuevoAjusteBody > form > .box:first-child > .box-header { display: none; }
</style>

<script>
$(document).ready(function() {
    var table = $('#tbl-ajustes').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [[1, "desc"]],
        "ajax": {
            "url": "<?php echo base_url(ALM); ?>Ajustestock/getAjustesList",
            "type": "POST"
        },
        "columns": [
            {
                "data": null,
                "orderable": false,
                "searchable": false,
                "render": function(data, type, row) {
                    var id = row.deaj_id || row.deaj_id || '';
                    return '<div class="text-center">' +
                           '<i class="fa fa-fw fa-search text-light-blue" style="cursor: pointer; font-size: 16px; margin: 0 5px;" title="Ver Detalle" onclick="verAjuste(\'' + id + '\')"></i>' +
                           '</div>';
                }
            },
            {
                "data": null,
                "render": function(data, type, row) {
                    return row.ajust_id || row.ajus_id || '';
                }
            },
            {
                "data": null,
                "render": function(data, type, row) {
                    return row.fec_alta_ajuste || row.fec_alta_ajuste || '';
                }
            },
            {
                "data": null,
                "render": function(data, type, row) {
                    return row.establecimiento || row.establecimiento_descrip || row.nomesta || '';
                }
            },
            {
                "data": null,
                "render": function(data, type, row) {
                    return row.deposito || row.deposito_descrip || row.depositodescrip || '';
                }
            }
        ],
        "dom": 'lBfrtip',
        "buttons": [
            {
                // Botón Excel
                extend: 'excel',
                exportOptions: { columns: [1, 2, 3, 4] },
                footer: true,
                title: 'Ajustes de Stock',
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
                }
            },
            {
                // Botón PDF
                extend: 'pdf',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: [1, 2, 3, 4] },
                footer: true,
                title: 'Ajustes de Stock',
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
                customize: function (doc) {
                    doc.defaultStyle.fontSize = 9;
                    doc.styles.tableHeader.fillColor = '#dd4b39';
                    doc.styles.tableHeader.color = 'white';
                }
            },
            {
                // Botón Copiar
                extend: 'copy',
                exportOptions: { columns: [1, 2, 3, 4] },
                footer: true,
                title: 'Ajustes de Stock',
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
                // Botón Imprimir
                extend: 'print',
                exportOptions: { columns: [1, 2, 3, 4] },
                className: 'btn btn-default btn-flat ml-1',
                text: 'Imprimir <i class="fa fa-print"></i>',
                action: function (e, dt, button, config) {
                    var self = this;
                    var oldLength = dt.page.len();
                    dt.page.len(1000000);
                    dt.one('draw', function () {
                        $.fn.dataTable.ext.buttons.print.action.call(self, e, dt, button, config);
                        setTimeout(function() {
                            dt.page.len(oldLength).draw();
                        }, 100);
                    });
                    dt.draw();
                },
                customize: function (win) {
                    $(win.document.body).find('table').addClass('compact');
                    $(win.document.body).find('th').css({'background-color': '#dd4b39', 'color': 'white'});
                }
            }
        ],
        "language": {
            "url": "<?php echo base_url(); ?>lib/bower_components/datatables.net/js/es-ar.json"
        },
        "responsive": true
    });

    // Evento Click para ver Detalles (usando el nuevo modalInfoAjuste)
    window.wo = function() {
        if (typeof WaitingOpen === 'function') {
            WaitingOpen('Cargando...');
        } else {
            console.log('WaitingOpen not defined');
        }
    };
    window.wc = function() {
        if (typeof WaitingClose === 'function') {
            WaitingClose();
        } else {
            console.log('WaitingClose not defined');
        }
    };

    // Abre el formulario de nuevo ajuste dentro de un modal (sin cambiar de pantalla)
    window.nuevoAjusteModal = function() {
        wo();
    
        $('#modalNuevoAjuste').modal('show');

        $.ajax({
            type: 'GET',
            dataType: 'html',
            url: '<?php echo base_url(ALM) ?>Ajustestock/nuevoAjuste',
            success: function(rsp) {
                $('#modalNuevoAjusteBody').html(rsp);
            },
            error: function() {
                $('#modalNuevoAjusteBody').html('<div class="alert alert-danger"><i class="fa fa-times-circle"></i> No se pudo cargar el formulario de ajuste.</div>');
            },
            complete: function() {
                wc();
            }
        });
    };

    // Al cerrar, limpio el contenido para que el proximo alta arranque de cero
    $('#modalNuevoAjuste').on('hidden.bs.modal', function() {
        $('#modalNuevoAjusteBody').empty();
    });

    window.verAjuste = function(deaj_id) {
        wo();
        $.ajax({
            type: 'POST',
            data: {
              deaj_id
            },
            url: '<?php echo base_url(ALM) ?>Ajustestock/getDataAjuste',
            success: function(result) {
                var resp = JSON.parse(result);
                console.log(resp);
                $("#idAjuste").val(deaj_id);
                $("#tipoAjuste").val(resp[0].tipo_ajuste.split("tipos_ajuste_stock")[1]);
                $("#justificacion").val(resp[0].justificacion);
              
                // Inicializar DataTable si no existe
                if (!$.fn.DataTable.isDataTable('#tablaDetalleAjuste')) {
                    $('#tablaDetalleAjuste').DataTable({
                        "aLengthMenu": [ 10, 25, 50, 100 ],
                        "order": [[0, "asc"]],
                        "language": {
                            "url": '<?php echo base_url() ?>lib/bower_components/datatables.net/js/es-ar.json'
                        }
                    });
                }

                var tabla = $('#tablaDetalleAjuste').DataTable();
                tabla.clear().draw();

                if (resp != null) {
                    resp.forEach(function(item) {
                        tabla.row.add([
                            item.barcode,
                            item.descripcion,
                            item.cantidad,
                            (item.tipo_ajuste_detalle ? item.tipo_ajuste_detalle.replace('tipos_ajuste_stock', '') : ''),
                            item.unidad_medida
                        ]);
                    });
                    tabla.draw();
                }

                $('#modalInfoAjuste').modal('show');
                wc();
            },
            error: function() {
                alert('Ha ocurrido un error, por favor comunicarse con su proveedor de servicio. Gracias!');
                wc();
            },
            complete: function(result) {
                wc();
            }
        });
    }

    // Evento Click para ver Comprobante
    $(document).on('click', '.btn-comprobante', function() {
        var rowData = $(this).data('json');
        var id = rowData.ajust_id || rowData.ajus_id || '';
        
        $('#comp-ajuste-id').text(id);
        $('#comp-fecha').text(rowData.fecha || rowData.fec_alta || '');
        $('#comp-establecimiento').text(rowData.establecimiento || rowData.nomesta || '');
        $('#comp-deposito').text(rowData.deposito || rowData.depositodescrip || '');
        $('#comp-justificacion').text(rowData.justificacion || '');
        
        var tbody = $('#comp-detalles');
        tbody.empty();
        
        wo()
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url(ALM); ?>Ajustestock/getDetalleAjuste',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                wc();
                if (response) {
                    var detalles = [];
                    if (Array.isArray(response)) {
                        detalles = response;
                    } else if (typeof response === 'object') {
                        detalles = [response];
                    }
                    
                    detalles.forEach(function(item) {
                        tbody.append(
                            '<tr>' +
                            '<td>' + (item.artdescription || item.articulo || '') + '</td>' +
                            '<td>' + (item.codigo || item.lote_id || '') + '</td>' +
                            '<td>' + (item.cantidad || '') + '</td>' +
                            '</tr>'
                        );
                    });
                }
                $('#modal-comprobante').modal('show');
            },
            error: function() {
                WaitingClose();
                alertify.error('Error al cargar los detalles del comprobante.');
            }
        });
    });
});

function printComprobante() {
    var base = "<?php echo base_url() ?>";
    $('#print-area').printThis({
        debug: false,
        importCSS: true,
        importStyle: true,
        pageTitle: "TRAZALOG TOOLS",
        printContainer: true,
        loadCSS: base + "lib/bower_components/bootstrap/dist/css/bootstrap.min.css",
        copyTagClasses: true,
        printDelay: 100,
        afterPrint: function () {
        },
        base: base
    });
}
</script>