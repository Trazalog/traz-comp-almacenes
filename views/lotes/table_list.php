  <table id="stock" class="table table-bordered table-hover">
    <thead>
        <th class="text-center">Acciones</th>
        <th class="text-center">N° Lote</th>
        <th>Código</th>
        <th>Producto</th>
        <th class="text-center">Stock</th>
        <th class="text-center">Unidad de Medida</th>
        <th class="text-center">Tipo de Articulo</th>
        <th class="text-center">Recipiente</th>
        <th class="text-center">Fecha Creación</th>
        <th>Establecimiento</th>
        <th>Depósito</th>
        <th class="text-center">Estado</th>
    </thead>
    <tbody>
        <?php
            foreach($list as $f){
            echo "<tr data-json='".json_encode($f)."'>";
            echo '<td class="text-center"><button type="button" title="Info" class="btn btn-primary btn-circle btnInfo" data-toggle="modal" data-target="#modalinfo" ><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></but</td>';
            echo '<td class="text-center">'.(($f->codigo == "1" || empty($f->codigo)) ? 'S/L' : $f->codigo).'</td>';
            echo '<td>'.$f->artbarcode.'</td>';
            echo '<td>'.$f->artdescription.'</td>';
            echo '<td class="text-center">'.((empty($f->cantidad)) ? '0' : $f->cantidad).'</td>';
            echo '<td class="text-center">'.$f->un_medida.'</td>';
            echo '<td class="text-center">'.((!empty($f->arttype)) ? str_replace('tipo_articulo', '', $f->arttype) : '').'</td>';
            echo '<td class="text-center">'.((empty($f->nom_reci)) ? "<b>No Aplica</b>" : $f->nom_reci).'</td>';
            echo '<td class="text-center">'.((empty($f->fec_alta)) ? "" : date('d/m/Y', strtotime($f->fec_alta))).'</td>';
            echo '<td>'.((empty($f->establecimiento)) ? "<b>No Aplica</b>" : $f->establecimiento).'</td>';
            echo '<td>'.((empty($f->depositodescrip)) ? "<b>No Aplica</b>" : $f->depositodescrip).'</td>';
            echo '<td class="text-center">'.estado($f->estado).'</td>';
            echo '</tr>';
            }
        ?>
    </tbody>
</table>
     
<!---///////--- MODAL EDICION E INFORMACION ---///////--->
<div class="modal fade bs-example-modal-lg" id="modalinfo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                <button type="button" class="close close_modal_edit" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color:white;">&times;</span>
                </button>
            </div>
            <div class="modal-body ">
                <form class="formEdicion" id="formEdicion">
                    <div class="form-horizontal">
                        <div class="row">
                            <form class="frm_stock_edit" id="frm_stock_edit">
                                <input type="text" class="form-control habilitar hidden" name="lote_id"id="lote_id_edit">
                                <div class="col-sm-6">
                                    <!--_____________ N° Lote _____________-->
                                    <div class="form-group">
                                        <label for="codigo_edit" class="col-sm-4 control-label">N° Lote:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control habilitar requerido" name="codigo"
                                                id="codigo_edit">
                                        </div>
                                    </div>
                                    <!--___________________________-->

                                    <!--_____________ Código _____________-->
                                    <div class="form-group">
                                        <label for="artBarCode_edit" class="col-sm-4 control-label">Código:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control habilitar" name="artBarCode"
                                                id="artBarCode_edit">
                                        </div>
                                    </div>
                                    <!--__________________________-->

                                    <!--_____________ Producto _____________-->
                                    <div class="form-group">
                                        <label for="artDescription_edit"
                                            class="col-sm-4 control-label">Producto:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control habilitar" name="artDescription"
                                                id="artDescription_edit">
                                        </div>
                                    </div>
                                    <!--__________________________-->

                                    <!--_____________ Stock _____________-->
                                    <div class="form-group">
                                        <label for="cantidad_edit" class="col-sm-4 control-label">Stock:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control habilitar" name="cantidad"
                                                id="cantidad_edit">
                                        </div>
                                    </div>
                                    <!--__________________________-->

                                    <!--_____________ Unidad de Medida _____________-->
                                    <div class="form-group">
                                        <label for="un_medida_edit" class="col-sm-4 control-label">Unidad de
                                            Medida:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control habilitar" name="un_medida"
                                                id="un_medida_edit">
                                        </div>
                                    </div>
                                    <!--__________________________-->

                                </div><!-- /.col-sm-6 -->

                                <div class="col-sm-6">

                                    <!--_____________ Recipiente _____________-->
                                    <div class="form-group">
                                        <label for="nom_reci_edit" class="col-sm-4 control-label">Recipiente:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control habilitar" name="nom_reci"
                                                id="nom_reci_edit">
                                        </div>
                                    </div>
                                    <!--__________________________-->

                                    <!--_____________ Fecha Creación _____________-->
                                    <div class="form-group">
                                        <label for="fec_alta_edit" class="col-sm-4 control-label">Fecha
                                            Creación:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control habilitar" name="fec_alta"
                                                id="fec_alta_edit">
                                        </div>
                                    </div>
                                    <!--__________________________-->

                                    <!--_____________ Depósito _____________-->
                                    <div class="form-group">
                                        <label for="depositodescrip_edit"
                                            class="col-sm-4 control-label">Depósito:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control habilitar" name="depositodescrip"
                                                id="depositodescrip_edit">
                                        </div>
                                    </div>
                                    <!--__________________________-->
                                </div><!-- /.col-sm-6 -->
                            </form>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="form-group text-right">
                    <!-- <button type="" class="btn btn-primary habilitar" data-dismiss="modal" id="btnsave_edit" onclick="guardar('editar')">Guardar</button> -->
                    <button type="" class="btn btn-default cerrarModalEdit" id="" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!---///////--- FIN MODAL EDICION E INFORMACION ---///////--->
<script>
// extrae datos de la tabla
$(document).off("click", ".btnInfo").on("click", ".btnInfo", function(e) {
    $(".modal-header h4").remove();
    //guardo el tipo de operacion en el modal
    $("#operacion").val("Info");
    //pongo titulo al modal
    $(".modal-header").append(
        '<h4 class="modal-title"  id="myModalLabel"><span id="modalAction" class="fa fa-fw fa-search"></span> Detalle Stock </h4>'
    );
    data = $(this).parents("tr").attr("data-json");
    datajson = JSON.parse(data);
    blockEdicion();
    llenarModal(datajson);
});

// llena modal para edicion y muestra
function llenarModal(datajson) {
    $('#lote_id_edit').val(datajson.lote_id);
    if (datajson.codigo == 1) {
        $('#codigo_edit').val("S/L");
    } else {
        $('#codigo_edit').val(datajson.codigo);
    }
    $('#artBarCode_edit').val(datajson.artbarcode);
    $('#artDescription_edit').val(datajson.artdescription);
    $('#cantidad_edit').val(datajson.cantidad);
    $('#un_medida_edit').val(datajson.un_medida);
    $('#nom_reci_edit').val(datajson.nom_reci);
    var fecha = datajson.fec_alta.substring(0, 10);
    $('#fec_alta_edit').val(fecha);
    $('#depositodescrip_edit').val(datajson.depositodescrip);
    $('#recipiente_edit').val(datajson.recipiente);
}
// deshabilita botones, selects e inputs de modal
function blockEdicion() {
    $(".habilitar").attr("readonly", "readonly");
}
//Funcion de datatable para extencion de botones exportar
//excel, pdf, copiado portapapeles e impresion
$(document).ready(function() {
    $('#stock').DataTable({
        responsive: true,
        serverSide: true,
        processing: true,
        ajax: {
            url: '<?php echo base_url(ALM) ?>Lote/getDataTable',
            type: 'POST',
            data: function(d) {
                d.nom_reci = $("#nom_reci").val();
                d.depositodescrip = $("#depositodescrip").val();
                d.artDescription = $("#artDescription").val();
                d.artBarCode = $("#inputarti").val();
                d.fec_desde = $("#datepickerDesde").val();
                d.fec_hasta = $("#datepickerHasta").val();
                d.artType = $("#artType").val();
                d.establecimiento = $("#establecimiento").val();
                d.tipo_deposito = $('#tipo_deposito').val();
                d.stock0 = $('#stock0').prop('checked');
            }
        },
        columns: [
            { 
                data: null, 
                className: 'text-center', 
                orderable: false,
                render: function(data, type, row) {
                    return '<button type="button" title="Info" class="btn btn-primary btn-circle btnInfo" data-toggle="modal" data-target="#modalinfo" ><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button>';
                }
            },
            { 
                data: 'codigo', 
                className: 'text-center',
                render: function(data, type, row) {
                    return (data == "1" || !data) ? 'S/L' : data;
                }
            },
            { data: 'artbarcode' },
            { data: 'artdescription' },
            { 
                data: 'cantidad', 
                className: 'text-center',
                render: function(data, type, row) {
                    return data ? data : '0';
                }
            },
            { data: 'un_medida', className: 'text-center' },
            { data: 'arttype', className: 'text-center' },
            { 
                data: 'nom_reci', 
                className: 'text-center',
                render: function(data, type, row) {
                    return data ? data : '<b>No Aplica</b>';
                }
            },
            { 
                data: 'fecha_nueva', 
                className: 'text-center',
                render: function(data, type, row) {
                    return data ? data : '';
                }
            },
            { 
                data: 'establecimiento',
                render: function(data, type, row) {
                    return data ? data : '<b>No Aplica</b>';
                }
            },
            { 
                data: 'depositodescrip',
                render: function(data, type, row) {
                    return data ? data : '<b>No Aplica</b>';
                }
            },
            { 
                data: 'estado_label', 
                className: 'text-center'
            }
        ],
        iDisplayLength: 10,
        rowGroup: {
         enable: false
        },
        language: {
            url: '<?php base_url() ?>lib/bower_components/datatables.net/js/es-ar.json' //Ubicacion del archivo con el json del idioma.
        },
        dom: 'lBfrtip',
        destroy: true,
        buttons: [{
                //Botón para Excel
                extend: 'excel',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                },
                footer: true,
                title: 'Reporte Stock',
                filename: 'Reporte_Stock',
                className: 'btn btn-success btn-flat ml-1',
                text: 'Exportar a Excel <i class="fa fa-file-excel-o"></i>',
                messageTop: function () {
                    var f = new Date();
                    var fecha = (f.getDate() < 10 ? '0' : '') + f.getDate() + "/" + ((f.getMonth() + 1) < 10 ? '0' : '') + (f.getMonth() + 1) + "/" + f.getFullYear();
                    
                    var filtros = "Fecha de reporte: " + fecha + "\n";
                    filtros += " Filtros aplicados:\n";
                    filtros += "Desde: " + ($('#datepickerDesde').val() ? $('#datepickerDesde').val() : 'N/A') + " | Hasta: " + ($('#datepickerHasta').val() ? $('#datepickerHasta').val() : 'N/A') + " | ";
                    filtros += "Establecimiento: " + ($('#establecimiento option:selected').text() ? $('#establecimiento option:selected').text() : 'TODOS') + "\n";
                    filtros += "Tipo Depósito: " + ($('#tipo_deposito option:selected').text() ? $('#tipo_deposito option:selected').text() : 'TODOS') + " | ";
                    filtros += "Depósito: " + ($('#depositodescrip option:selected').text() ? $('#depositodescrip option:selected').text() : 'TODOS') + " | ";
                    filtros += "Recipiente: " + ($('#nom_reci option:selected').text() ? $('#nom_reci option:selected').text() : 'TODOS') + "\n";
                    filtros += "Tipo Artículo: " + ($('#artType option:selected').text() ? $('#artType option:selected').text() : 'TODOS') + " | ";
                    filtros += "Artículo: " + ($('#inputarti').val() ? $('#inputarti').val() : 'TODOS');
                    return filtros;
                }
            },
            //Botón para PDF
            {
                extend: 'pdf',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                },
                footer: true,
                title: 'Reporte Stock',
                filename: 'Reporte_Stock',
                className: 'btn btn-danger btn-flat ml-1',
                text: 'Exportar a PDF <i class="fa fa-file-pdf-o"></i>',
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
                    doc.content.splice(0, 0, {
                        columns: [
                            {
                                text: title,
                                fontSize: 22,
                                bold: true,
                                alignment: 'left',
                                margin: [0, 10, 0, 0]
                            },
                            {
                                image: '<?php echo $logo; ?>',
                                width: 100,
                                alignment: 'right',
                                margin: [0, 0, 0, 0]
                            }
                        ],
                        margin: [0, 0, 0, 20]
                    });

                    // Ajustar messageTop (Fecha) - Ahora está en index 1
                    doc.content[1].alignment = 'left';
                    doc.content[1].margin = [0, 0, 0, 10];

                    // Agregar Filtros con mejor formato
                    var filtros = "Filtros aplicados:\n";
                    filtros += "Desde: " + ($('#datepickerDesde').val() ? $('#datepickerDesde').val() : 'N/A') + " | Hasta: " + ($('#datepickerHasta').val() ? $('#datepickerHasta').val() : 'N/A') + " | ";
                    filtros += "Establecimiento: " + ($('#establecimiento option:selected').text() ? $('#establecimiento option:selected').text() : 'TODOS') + "\n";
                    filtros += "Tipo Depósito: " + ($('#tipo_deposito option:selected').text() ? $('#tipo_deposito option:selected').text() : 'TODOS') + " | ";
                    filtros += "Depósito: " + ($('#depositodescrip option:selected').text() ? $('#depositodescrip option:selected').text() : 'TODOS') + " | ";
                    filtros += "Recipiente: " + ($('#nom_reci option:selected').text() ? $('#nom_reci option:selected').text() : 'TODOS') + "\n";
                    filtros += "Tipo Artículo: " + ($('#artType option:selected').text() ? $('#artType option:selected').text() : 'TODOS') + " | ";
                    filtros += "Artículo: " + ($('#inputarti').val() ? $('#inputarti').val() : 'TODOS');

                    doc.content.splice(2, 0, {
                        text: filtros,
                        fontSize: 10,
                        alignment: 'left',
                        margin: [0, 0, 0, 15]
                    });

                    // Estilo general para que quepa todo
                    doc.defaultStyle.fontSize = 9;

                    // Estilo de la tabla
                    doc.styles.tableHeader.fillColor = '#dd4b39';
                    doc.styles.tableHeader.color = 'white';
                    doc.styles.tableHeader.alignment = 'center';
                    doc.styles.tableHeader.fontSize = 10;

                    // Hacer que la tabla ocupe todo el ancho con anchos proporcionales
                    var tableIndex = doc.content.length - 1;
                    doc.content[tableIndex].table.widths = ['8%', '8%', '15%', '6%', '8%', '10%', '10%', '10%', '10%', '10%', '5%'];
                }
            },
            {
                extend: 'copy',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                },
                footer: true,
                title: 'Reporte Stock',
                filename: 'Reporte_Stock',
                className: 'btn btn-primary btn-flat ml-1',
                text: 'Copiar <i class="fa fa-file-text-o"></i>'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                },
                footer: true,
                title: 'Reporte Stock',
                filename: 'Reporte_Stock',
                className: 'btn btn-default btn-flat ml-1',
                text: 'Imprimir <i class="fa fa-print"></i>',
                messageTop: function () {
                    var f = new Date();
                    var fecha = (f.getDate() < 10 ? '0' : '') + f.getDate() + "/" + ((f.getMonth() + 1) < 10 ? '0' : '') + (f.getMonth() + 1) + "/" + f.getFullYear();
                    
                    var filtros = "Fecha de reporte: " + fecha + "\n\n";
                    filtros += "Filtros aplicados:\n";
                    filtros += "Desde: " + ($('#datepickerDesde').val() ? $('#datepickerDesde').val() : 'N/A') + " | Hasta: " + ($('#datepickerHasta').val() ? $('#datepickerHasta').val() : 'N/A') + " | ";
                    filtros += "Establecimiento: " + ($('#establecimiento option:selected').text() ? $('#establecimiento option:selected').text() : 'TODOS') + "\n";
                    filtros += "Tipo Depósito: " + ($('#tipo_deposito option:selected').text() ? $('#tipo_deposito option:selected').text() : 'TODOS') + " | ";
                    filtros += "Depósito: " + ($('#depositodescrip option:selected').text() ? $('#depositodescrip option:selected').text() : 'TODOS') + " | ";
                    filtros += "Recipiente: " + ($('#nom_reci option:selected').text() ? $('#nom_reci option:selected').text() : 'TODOS') + "\n";
                    filtros += "Tipo Artículo: " + ($('#artType option:selected').text() ? $('#artType option:selected').text() : 'TODOS') + " | ";
                    filtros += "Artículo: " + ($('#inputarti').val() ? $('#inputarti').val() : 'TODOS');
                    return filtros;
                },
                customize: function (win) {
                    // Remover el título original H1
                    $(win.document.body).find('h1').remove();
                    
                    // Agregar Cabecera con Título y Logo
                    var cabecera = '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #dd4b39; padding-bottom: 10px;">' +
                                   '  <h1 style="margin: 0; font-size: 22pt; font-weight: bold; color: #333;">Reporte Stock</h1>' +
                                   '  <img src="<?php echo $logo; ?>" style="width: 100px; height: auto;" />' +
                                   '</div>';
                    $(win.document.body).prepend(cabecera);

                    // Estilizar el bloque de filtros (messageTop)
                    $(win.document.body).find('div').each(function() {
                        if ($(this).text().indexOf('Filtros aplicados:') !== -1) {
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
        ],
        drawCallback: function(settings) {
            // Actualizar contadores si es necesario
            var json = settings.json;
            if(json && json.recordsFiltered !== undefined) {
                if(_isset($("#depositodescrip").val())) {
                     $("#cantidadLotesDeposito").text(json.recordsFiltered);
                     $("#nombreLotesDeposito").text($('#depositodescrip option:selected').text());
                }
            }
        }
    }).on('processing.dt', function(e, settings, processing) {
        if (processing) {
            jsShowWindowLoad('Procesando...');
        } else {
            jsRemoveWindowLoad();
        }
    });
});
</script>