<?php
  use \koolreport\widgets\koolphp\Table;
  use \koolreport\widgets\google\ColumnChart;
?>

<div id="reportContent" class="report-content">
    <div class="box box-primary">

        <div class="box-header with-border">
            <div class="box-tittle">
                <h4>Movimientos de Stock</h4>
            </div>
        </div>

        <div class="box-body">

            <!-- _____ GRUPO 1 _____ -->
            <div class="col-md-12">

                <div class="form-group">

                    <div class="col-md-4 col-md-6 mb-4 mb-lg-0">
                        <label>Desde (<strong style="color: #dd4b39">*</strong>): </label>
                        <div class="input-group date">
                            <a class="input-group-addon" id="daterange-btn" title="Más fechas">
                                <i class="fa fa-magic"></i>
                                <span></span>
                            </a>
                            <input type="date" class="form-control pull-right" id="datepickerDesde"
                                name="datepickerDesde" placeholder="Desde">
                        </div>
                    </div>


                    <div class="col-md-4 col-md-6 mb-4 mb-lg-0">
                        <label>Hasta (<strong style="color: #dd4b39">*</strong>): </label>
                        <div class="input-group date">
                            <input type="date" class="form-control" id="datepickerHasta" name="datepickerHasta"
                                placeholder="Hasta">
                            <a class="input-group-addon" style="cursor: pointer;" onclick="filtro()"
                                title="Más filtros">

                            </a>
                        </div>
                    </div>

                    <div class="col-md-4 col-md-6 mb-4 mb-lg-0">
                        <label for="tipoajuste" class="form-label">Tipo Movimiento: </label>
                        <select class="form-control select2 select2-hidden-accesible" id="tipoajuste" name="tipoajuste">
                            <option value="TODOS">Todos</option>
                            <option value="INGRESO">Recepción Materiales</option>
                            <option value="EGRESO">Entrega Materiales</option>
                            <option value="MOV.ENTRADA">Mov. Interno Ingreso</option>
                            <option value="MOV.SALIDA">Mov. Interno Egreso</option>
                            <option value="AJUSTE">Ajuste Stock</option>
                            <option value="INGRESOPRODUCTO">Consumo MP en Etapa Productiva</option> <!-- produccion caso 1 -->
                            <option value="ETAPAPRODINGRESO">Consumo Prod. Semi Term. en Etapa Productiva</option> <!-- produccion caso 2 -->
                            <option value="ETAPAPRODEGRESO">Salida Prod. de Etapa Productiva</option> <!-- produccion caso 3 -->
                        </select>

                    </div>

                </div>

            </div>
            <!-- _____ GRUPO 1 _____ -->

            <div class="col-md-12">
                <br>
            </div>

            <!-- _____ GRUPO 2 _____ -->
            <div class="col-md-12">

                <div class="form-group">

                    <div class="col-md-4 col-md-6 mb-4 mb-lg-0">
                        <label for="establecimiento" class="form-label">Establecimiento: </label>
                        <select onchange="seleccionesta(this)" class="form-control select2 select2-hidden-accesible"
                            id="establecimiento" name="establecimiento">
                            <option value="TODOS">Todos</option>
                            <?php
                                foreach ($establecimientos as $est) {
                                    echo '<option value="'.$est->esta_id.'">'.$est->nombre.'</option>';
                                }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4 col-md-6 mb-4 mb-lg-0 habilitado">
                        <label for="depo_id" class="form-label">Depósito:</label>
                        <select class="form-control select2 select2-hidden-accesible" id="depo_id" name="depo_id" />
                    </div>

                    <div class="col-md-4 col-md-6 mb-4 mb-lg-0 habilitado">
                        <label for="zona" class="form-label">Artículo:</label>
                        <div id="list_articulos"> </div>
                    </div>

                </div>
            </div>

            <div class="col-md-12">

                <div class="form-group">

                    <div class="col-md-4 col-md-6 mb-4 mb-lg-0 habilitado">
                        <label for="lote_id" class="form-label">Lote:</label>
                        <select class="form-control select2 select2-hidden-accesible" id="lote_id" name="lote_id"
                            disabled />
                    </div>

                </div>

            </div>
            <!-- _____ GRUPO 2 _____ -->

            <div class="col-md-12">
                <br>
            </div>


            <div class="form-group col-xs-12">
                <div class="form-group">
                    <button type="button" class="btn btn-default btn-sm btn-flat col-xs-12 col-sm-2 col-md-2 col-lg-2"
                        onclick="limpiar()" style="float: right !important;">Limpiar</button>
                    <button type="button" class="btn btn-success btn-sm btn-flat col-xs-12 col-sm-2 col-md-2 col-lg-2"
                        onclick="filtrar()" style="float: right !important; margin-right: 5px;">Filtrar</button>
                </div>
            </div>

            <!-- MODAL VER DETALLE AJUSTE -->
            <div class="modal fade bs-example-modal" id="modalInfoAjuste" tabindex="-1" role="dialog"
                aria-labelledby="myLargeModalLabel">

                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">

                        <div class="modal-header bg-blue">
                            <button type="button" class="close close_modal_edit" data-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true" style="color:white;">&times;</span>
                            </button>

                            <h4 class="modal-title" id="myModalLabel"><span class="fa fa-fw fa-search"></span> Detalle
                                Stock </h4>
                        </div>

                        <div class="modal-body ">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="idAjuste">Id: </label>
                                    <input type="text" class="form-control habilitar" name="idAjuste"
                                        id="idAjuste" readonly>
                                </div>
                                <div class="col-sm-6">
                                    <label for="tipoAjuste" class="control-label">Tipo de Ajuste:</label>
                                    <input type="text" class="form-control habilitar" name="tipoAjuste"
                                        id="tipoAjuste" readonly>
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
                                <button type="" class="btn btn-default cerrarModalEdit" 
                                    data-dismiss="modal">Cerrar</button>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
            <!-- FIN MODAL VER DETALLE AJUSTE -->

            <!-- MODAL VER DETALLE MOVIMIENTO INTERNO -->
            <div class="modal fade bs-example-modal" id="modalInfoMovimiento" tabindex="-1" role="dialog"
                aria-labelledby="myLargeModalLabel">

                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">

                        <div class="modal-header bg-blue">
                            <button type="button" class="close close_modal_edit" data-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true" style="color:white;">&times;</span>
                            </button>

                            <h4 class="modal-title" id="myModalLabel"><span class="fa fa-fw fa-paperclip"></span> Detalle
                                Movimiento Interno </h4>
                        </div>

                        <div class="modal-body ">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="demiIdMovimiento">ID: </label>
                                    <input type="text" class="form-control" name="demiIdMovimiento"
                                        id="demiIdMovimiento" readonly>
                                </div>
                                <div class="col-sm-4">
                                    <label for="cantidadCargada">Cantidad Cargada: </label>
                                    <input type="text" class="form-control" name="cantidadCargada"
                                        id="cantidadCargada" readonly>
                                </div>
                                <div class="col-sm-4">
                                    <label for="cantidadRecibida">Cantidad Recibida: </label>
                                    <input type="text" class="form-control" name="cantidadRecibida"
                                        id="cantidadRecibida" readonly>
                                </div>
                                <div class="col-sm-12" id="justificacionContainer" style="display: none;">
                                    <label for="justificacionMovimiento" class="control-label">Justificación:</label>
                                    <textarea style="resize:none" type="text" class="form-control input-sm" id="justificacionMovimiento" name="justificacionMovimiento" readonly></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">

                            <div class="form-group text-right">
                                <button type="" class="btn btn-default" 
                                    data-dismiss="modal">Cerrar</button>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
            <!-- FIN MODAL VER DETALLE MOVIMIENTO INTERNO -->

            <!-- Modal CONSULTA-->
            <div class="modal fade" id="modalIngreso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">

                        <div class="modal-header bg-blue">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color:white;">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel"><span class="fa fa-fw fa-search-plus"></span> Detalle Ingreso de Materiales</h4>
                        </div> <!-- /.modal-header  -->

                        <div class="modal-body" id="modalBodyArticle">
                            <div class="row" id="infoOI">
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <label for="comprobanteV">Nº de Comprobante:</label>
                                    <input type="text" class="form-control" id="comprobanteV" name="comprobanteV" disabled>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <label for="fechaV">Fecha:</label>
                                    <input type="text" class="form-control" id="fechaV" name="fechaV" disabled>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <label for="proveedorV">Proveedor:</label>
                                    <input type="text" class="form-control" id="proveedorV" name="proveedorV" disabled>
                                </div>
                            </div>
                            <br>
                            
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="col-sm-12" style="margin-top: 15px;">
                                        <table class="table table-bordered" id="tablaconsulta" style="width:100%"> 
                                            <thead>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Descripción</th>
                                                    <th>Cantidad</th>
                                                    <th>Depósito</th>
                                                    <th>Establecimiento</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> 
                        </div>  <!-- /.modal-body -->
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        </div>  <!-- /.modal footer -->

                    </div> <!-- /.modal-content -->
                </div>  <!-- /.modal-dialog modal-lg -->
            </div>  <!-- /.modal fade -->
            <!-- / Modal -->

            <!-- Modal EGRESO-->
            <div class="modal fade" id="modalEgreso" tabindex="-1" role="dialog" aria-labelledby="myModalLabelEgreso">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">

                        <div class="modal-header bg-blue">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color:white;">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabelEgreso"><span class="fa fa-fw fa-search-plus"></span> Detalle Entrega Materiales (Egreso)</h4>
                        </div> <!-- /.modal-header  -->

                        <div class="modal-body" id="modalBodyArticleEgreso">
                            <div class="row" id="infoOIEgreso">
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <label for="ordenEgreso">N° Entrega:</label>
                                    <input type="text" class="form-control" id="ordenEgreso" name="ordenEgreso" disabled>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <label for="fechaEgreso">Fecha:</label>
                                    <input type="text" class="form-control" id="fechaEgreso" name="fechaEgreso" disabled>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <label for="id_otEgreso">Orden de Trabajo:</label>
                                    <input type="text" class="form-control" id="id_otEgreso" name="id_otEgreso" disabled>
                                </div>
                            </div>
                            <br>
                            
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="col-sm-12" style="margin-top: 15px;">
                                        <table class="table table-bordered compact" id="tablaconsultaEgreso" style="width:100%"> 
                                            <thead>
                                                <tr>
                                                    <th>Artículo</th>
                                                    <th>Descripción</th>
                                                    <th>N° Lote</th>
                                                    <th>Depósito</th>
                                                    <th>Cantidad</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="totalEgreso">Total:</label>
                                    <input type="text" class="form-control" id="totalEgreso" name="totalEgreso" disabled>
                                </div>
                            </div>
                        </div>  <!-- /.modal-body -->
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        </div>  <!-- /.modal footer -->

                    </div> <!-- /.modal-content -->
                </div>  <!-- /.modal-dialog modal-lg -->
            </div>  <!-- /.modal fade -->
            <!-- / Modal Egreso -->

            <!-- Modal ver nota pedido-->
            <div class="modal fade" id="modal_detalle_entrega" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog " role="document">
                    <div class="modal-content">
                        
                            <div class="modal-header bg-blue">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color:white;">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabelEgreso"><span class="fa fa-fw fa-search-plus"></span> Detalle Entrega Materiales (Egreso)</h4>
                            </div> <!-- /.modal-header  -->

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-lg-4">
                                    <label for="">Entrega:</label>
                                    <input class="form-control enma_id" type="text" value="???" readonly>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-lg-4">
                                    <label for="">Pedido:</label>
                                    <input class="form-control pema_id" type="text" value="???" readonly>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-lg-4 <?php echo (!viewOT ? "hidden" : null) ?>">
                                    <label for="">Orden de Trabajo:</label>
                                    <input class="form-control orden" type="text" value="???" readonly>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-lg-4">
                                    <label for="">Comprobante:</label>
                                    <input class="form-control comprobante" type="text" value="???" readonly>
                                </div><br>
                                <div class="col-xs-12 col-sm-6 col-lg-4">
                                    <label for="">Fecha:</label>
                                    <input class="form-control fecha" type="text" value="???" readonly>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-lg-4">
                                    <label for="">Entregado a:</label>
                                    <input class="form-control entregado" type="text" value="???" readonly>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-lg-4">
                                    <label for="">Estado:</label>
                                    <input class="form-control estado" type="text" value="???" readonly>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Artículo</th>
                                                <th>Descripción</th>
                                                <th>N° Lote</th>
                                                <th>Depósito</th>
                                                <th>Cantidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!--TABLE BODY -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> <!-- /.modal-body -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="btnSave" data-dismiss="modal">Cerrar</button>
                        </div> <!-- /.modal footer -->
                    </div> <!-- /.modal-content -->
                </div> <!-- /.modal-dialog modal-lg -->
            </div> <!-- /.modal fade -->
            <!-- / Modal -->

            <!--_______ TABLA _______-->
            <div class="col-md-12 table-responsive">
                <table id="tabla_historico" class="table table-bordered table-striped table-hover display" style="width: 100%;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 80px;">Acciones</th>
                            <th>Referencia</th>
                            <th>Cod. Artículo</th>
                            <th>Descrip.</th>
                            <th>Lote</th>
                            <th>Cantidad</th>
                            <th>Depósito</th>
                            <th>Fecha</th>
                            <th>Tipo Movim.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- DataTable populated via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TRAZABILIDAD SALIDA ETAPA PRODUCTIVA -->
<div class="modal fade" id="modalTrazabilidadEtapaProd" tabindex="-1" role="dialog" aria-labelledby="modalTrazabilidadLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color:white;">&times;</span>
                </button>
                <h4 class="modal-title" id="modalTrazabilidadLabel">
                    <span class="fa fa-fw fa-sitemap"></span> Trazabilidad de Salida de Etapa Productiva
                </h4>
            </div>
            <div class="modal-body" id="modalTrazabilidadBody">
                <p class="text-center">
                    <i class="fa fa-spinner fa-spin"></i> Cargando...
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- FIN MODAL TRAZABILIDAD SALIDA ETAPA PRODUCTIVA -->

<!-- modal de reimpresion remito -->
<div id="modalContainer"></div>

<script>
//variables que van a mantener el estado para poder generar el excel
var fec1;
var fec2;
var tpoMov;
var esta;
var depo;
var artic;
var lote;

var tablaHistorico;

// carga select de Establecimientos, componente Articulos y llama configuracion selects de fecha
$(function() {
    $(".habilitado").hide();
    wo();
    $("#list_articulos").load("<?php echo base_url(ALM); ?>Reportes/cargaArticulos");
    getEstablecimientos();
    fechaMagic();
    //getTipoAjuste();
    
    // Inicializar DataTable con procesamiento del servidor
    tablaHistorico = $('#tabla_historico').DataTable({
        "responsive": true,
        "serverSide": true,
        "processing": true,
        "ordering": true,
        "searching": true,
        "ajax": {
            "url": "<?php echo base_url(ALM) ?>Reportes/getHistoricoPaginado",
            "type": "POST",
            "data": function(d) {
                d.desde = $("#datepickerDesde").val();
                d.hasta = $("#datepickerHasta").val();
                d.tipo_mov = $("#tipoajuste>option:selected").val();
                d.esta_id = $("#establecimiento").val();
                d.depo_id = $("#depo_id").val();
                d.lote_id = $("#lote_id>option:selected").val();
                var inputarti = $("#inputarti").val();
                if (inputarti && typeof selectItem !== 'undefined') {
                    d.arti_id = selectItem.arti_id;
                } else {
                    d.arti_id = 'TODOS';
                }
            }
        },
        "columns": [
            { 
                "data": "acciones", 
                "className": "text-center", 
                "orderable": false 
            },
            { "data": "referencia" },
            { "data": "codigo" },
            { "data": "descripcion" },
            { "data": "lote" },
            { 
                "data": "cantidad", 
                "className": "text-right",
                "render": function(data, type, row) {
                    var val = parseFloat(data);
                    if (!isNaN(val) && val < 0) {
                        return '<span class="text-danger" style="color: #dd4b39; font-weight: bold;">' + data + '</span>';
                    }
                    return data;
                }
            },
            { "data": "deposito" },
            { "data": "fecha" },
            { 
                "data": "tipo_mov",
                "render": function(data, type, row) {
                    var tipoMovMap = {
                        'TODOS':            'Todos',
                        'INGRESO':          'Recepción Materiales',
                        'EGRESO':           'Entrega Materiales',
                        'MOV.ENTRADA':      'Mov. Interno Ingreso',
                        'MOV.SALIDA':       'Mov. Interno Egreso',
                        'AJUSTE':           'Ajuste Stock',
                        'INGRESOPRODUCTO':  'Consumo MP en Etapa Productiva',
                        'ETAPAPRODINGRESO': 'Consumo Prod. Semi Term. en Etapa Productiva',
                        'ETAPAPRODEGRESO':  'Salida Prod. de Etapa Productiva'
                    };
                    var texto = tipoMovMap[data] || data;
                    // Equivalente JS de la función PHP bolita() usando el color 'light-blue'
                    return '<span data-toggle="tooltip" title="' + (row.descripcion || '') + '" class="badge bg-light-blue estado">' + texto + '</span>';
                }
            }
        ],
        "iDisplayLength": 10,
        "language": {
            "url": '<?php echo base_url() ?>lib/bower_components/datatables.net/js/es-ar.json'
        },
        "dom": 'lBfrtip',
        buttons: [
        {
            // Botón Excel
            extend: 'excel',
            exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8] },
            footer: true,
            title: 'Reporte Histórico Artículos',
            className: 'btn btn-success btn-flat ml-1',
            text: 'Exportar a Excel <i class="fa fa-file-excel-o"></i>',
            messageTop: function () {
                var filtros = "Filtros aplicados:\n";
                filtros += "Desde: " + ($('#datepickerDesde').val() || 'N/A') + " | Hasta: " + ($('#datepickerHasta').val() || 'N/A') + "\n";
                filtros += "Tipo Movimiento: " + ($('#tipoajuste option:selected').text() || 'TODOS') + "\n";
                filtros += "Establecimiento: " + ($('#establecimiento option:selected').text() || 'TODOS') + "\n";
                filtros += "Depósito: " + ($('#depo_id option:selected').text() || 'TODOS') + "\n";
                filtros += "Artículo: " + ($('#inputarti').val() || 'TODOS');
                return filtros;
            }
        },
        {
            // Botón PDF (Con filtros, sin logo)
            extend: 'pdf',
            orientation: 'landscape',
            pageSize: 'A4',
            exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8] },
            footer: true,
            title: 'Reporte Histórico Artículos',
            className: 'btn btn-danger btn-flat ml-1',
            text: 'Exportar a PDF <i class="fa fa-file-pdf-o"></i>',
            customize: function (doc) {
                // Construir texto de filtros
                var filtros = "Filtros aplicados: " +
                    "Desde: " + ($('#datepickerDesde').val() || 'N/A') + " | " +
                    "Hasta: " + ($('#datepickerHasta').val() || 'N/A') + " | " +
                    "Tipo: " + ($('#tipoajuste option:selected').text() || 'TODOS') + " | " +
                    "Establecimiento: " + ($('#establecimiento option:selected').text() || 'TODOS') + " | " +
                    "Depósito: " + ($('#depo_id option:selected').text() || 'TODOS');

                // Agregar filtros al PDF
                doc.content.splice(1, 0, {
                    text: filtros,
                    fontSize: 10,
                    margin: [0, 0, 0, 15]
                });

                doc.defaultStyle.fontSize = 9;
                doc.styles.tableHeader.fillColor = '#dd4b39';
                doc.styles.tableHeader.color = 'white';
            }
        },
        {
                extend: 'copy',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8]
                },
                footer: true,
                title: 'Reporte Histórico Artículos',
                filename: 'Reporte_Historico_Articulos',
                className: 'btn btn-primary btn-flat ml-1',
                text: 'Copiar <i class="fa fa-file-text-o"></i>'
        },
        {
            // Botón Imprimir (Con filtros, sin logo)
            extend: 'print',
            exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8] },
            className: 'btn btn-default btn-flat ml-1',
            text: 'Imprimir <i class="fa fa-print"></i>',
            customize: function (win) {
                var filtros = `
                    <div style="margin-bottom:20px; font-size:12px;">
                        <h2>Movimientos de Stock</h2>
                        <strong>Filtros aplicados:</strong><br>
                        Desde: ${$('#datepickerDesde').val() || 'N/A'} | Hasta: ${$('#datepickerHasta').val() || 'N/A'}<br>
                        Tipo Movimiento: ${$('#tipoajuste option:selected').text() || 'TODOS'}<br>
                        Establecimiento: ${$('#establecimiento option:selected').text() || 'TODOS'} | 
                        Depósito: ${$('#depo_id option:selected').text() || 'TODOS'}
                    </div>
                `;
                $(win.document.body).prepend(filtros);
                $(win.document.body).find('table').addClass('compact');
                $(win.document.body).find('th').css({'background-color': '#dd4b39', 'color': 'white'});
            }
        }
    ],     
    "destroy": true
    }).on('processing.dt', function(e, settings, processing) {
        if (processing) {
            wo();
        } else {
            wc();
        }
    });

    wc();
});

// config de daterangepicker
function fechaMagic() {
    $('#daterange-btn').daterangepicker({
            ranges: {
                'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                'Este mes': [moment().startOf('month'), moment().endOf('month')],
                'Último mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                    'month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment()
        },
        function(start, end) {
            $('#datepickerDesde').val(start.format('YYYY-MM-DD'));
            $('#datepickerHasta').val(end.format('YYYY-MM-DD'));
        }
    );
    // Esto asegura que al elegir un rango rápido, los inputs se actualicen
    $('#daterange-btn').on('apply.daterangepicker', function(ev, picker) {
        $('#datepickerDesde').val(picker.startDate.format('YYYY-MM-DD'));
        $('#datepickerHasta').val(picker.endDate.format('YYYY-MM-DD'));
    }); 
}

// llena select Establecimientos
function getEstablecimientos() {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        data: {},
        url: 'index.php/<?php echo ALM?>Reportes/getEstablecimientos',
        success: function(data) {
            $('#establecimiento').empty();
            if (data != null) {
                $('#establecimiento').append("<option value='TODOS' selected>Todos</option>");
                for (var i = 0; i < data.length; i++) {
                    $('#establecimiento').append("<option value='" + data[i].esta_id + "'>" + data[i].nombre + "</option>");
                }
                // Ejecutar seleccionesta con el primer elemento
                seleccionesta(document.getElementById('establecimiento'));
            } else {
                $("#establecimiento").append("<option value='TODOS' selected>Todos</option>");
            }
            WaitingClose();
        },
        error: function(data) {
            alert('Error');
        }
    });
}

// carga los depositos de acuerdo a establecimiento
function seleccionesta(opcion) {
    $(".habilitado").show();
    var id_esta = $("#establecimiento").val();

    if (id_esta == 'TODOS') {
        $('#depo_id').empty();
        $("#depo_id").append("<option value='TODOS'>Todos</option>");
        $('#lote_id').empty();
        $('#lote_id').append('<option value="TODOS">Todos</option>');
        return;
    }

    wo();
    var depo_id = $("#depo_id").val();

    $('#lote_id').append('<option value="TODOS">Todos</option>'); // En caso que no seleccione articulo

    $.ajax({
        type: 'POST',
        data: {
            id_esta,
            depo_id
        },
        url: 'index.php/<?php echo ALM?>Reportes/traerDepositos',
        success: function(data) {
            var resp = JSON.parse(data);
            $('#depo_id').empty();
            $("#depo_id").append("<option value='TODOS'>Todos</option>");
            if (data != null) {
                for (var i = 0; i < resp.length; i++) {
                    $('#depo_id').append("<option value='" + resp[i].depo_id + "'>" + resp[i].descripcion + "</option>");
                }
                $("#depo_id").removeAttr('readonly');
            } else {
                $("#depo_id").append("<option value=''>-Sin Depósitos para este Establecimiento-</option>");
            }
            wc();
        },
        error: function(data) {
            wc();
            alert('Error');
        }
    });
}

// trae lotes por id de deposito y de articulo
$("body").on('change', '#inputarti', function() {

    var depo_id = $('#depo_id option:selected').val();
    var arti_id = selectItem.arti_id; // se completa en traz-comp-almacen/articulo/componente.php
    if (depo_id == "") {
        alert('Por favor seleccione deposito...');
        return;
    }
    wo();

    $.ajax({
        type: 'POST',
        data: {
            arti_id: arti_id,
            depo_id: depo_id
        },
        url: 'index.php/<?php echo ALM?>Reportes/traerLotes',
        success: function(data) {

            $('#lote_id').empty();
            var resp = JSON.parse(data);
            if (resp == null) {
                $('#lote_id').append(
                    '<option value="" disabled selected>-Sin Lotes para este artículo-</option>'
                );
            } else {
                console.table(resp);
                console.table(resp[0].lote_id);
                // $('#lote_id').append('<option value="" disabled selected>-Seleccione opcion-</option>');
                $('#lote_id').append('<option value="TODOS">Todos</option>');
                for (var i = 0; i < resp.length; i++) {
                    $('#lote_id').append("<option value='" + resp[i].lote_id + "'>" + resp[i]
                        .codigo + "</option");
                }
                $("#lote_id").removeAttr('disabled');
            }
            wc();
        },
        error: function(data) {
            alert('Error');
            wc();
        }
    });
});

// llena select tipo ajuste
function getTipoAjuste() {

    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: 'index.php/<?php echo ALM?>general/Tipoajuste/obtenerAjuste',
        success: function(result) {

            if (!result.status) {
                alert("fallo");
                return;
            }
            result = result.data;
            var option_ajuste = '<option value="" disabled selected>-Seleccione opcion-</option>';
            for (let index = 0; index < result.length; index++) {
                option_ajuste += '<option value="' + result[index].nombre + '" data="' + result[index]
                    .tipo + '">' + result[index].nombre + '</option>';
            }
            $('#tipoajuste').html(option_ajuste);
        },
        error: function() {
            alert('Error');
        }
    });
}

// filtrado de datos
function filtrar() {
    // Actualizar variables de estado para el Excel
    fec1 = $("#datepickerDesde").val();
    fec2 = $("#datepickerHasta").val();

    if (!fec1 || !fec2) {
        Swal.fire('Atención', 'Por favor, ingrese el rango de fechas (Desde y Hasta) obligatorio.', 'warning');
        return;
    }

    tpoMov = $("#tipoajuste>option:selected").val();
    esta = $("#establecimiento").val();
    depo = $("#depo_id").val();
    lote = $("#lote_id>option:selected").val();

    var inputarti = $("#inputarti").val();
    if (inputarti && typeof selectItem !== 'undefined') {
        artic = selectItem.arti_id;
    } else {
        artic = 'TODOS';
    }

    // Recargar el DataTable con los nuevos parámetros por AJAX
    if (tablaHistorico) {
        tablaHistorico.ajax.reload(function(json) {
            if (json && json.recordsFiltered === 0) {
                Swal.fire('Aviso', 'No hay resultados para mostrar con los filtros aplicados.', 'info');
            }
        });
    }
}

function limpiar() {
    $("#datepickerDesde").val('');
    $("#datepickerHasta").val('');
    $("#tipoajuste").val('TODOS').trigger('change');
    $("#establecimiento").val('TODOS').trigger('change');
    if ($("#inputarti").length) {
        $("#inputarti").val('');
    }
    $("#lote_id").val('TODOS').trigger('change');

    // Limpiar variables de estado para el Excel
    fec1 = '';
    fec2 = '';
    tpoMov = 'TODOS';
    esta = 'TODOS';
    depo = 'TODOS';
    artic = 'TODOS';
    lote = 'TODOS';

    // Recargar el DataTable con filtros vaciados
    if (tablaHistorico) {
        tablaHistorico.clear().draw();
    }
}

/* Funciones para reimprimir Remito de movimiento interno */

// Función asíncrona para mostrar el modal de reimpresión de Remito
async function modalReimpresion(element) {
    try {
        wo();
        var fila = $(element).closest('tr');
        var celdas = fila.find('td');

        // Esperar a que los datos de la empresa se carguen
        var dataEmpresa = await DatosEmpresaRemito();

        // referencia es el demi_id de movimientos_internos
        var referencia = celdas.eq(1).text().trim();

        // Llamar a la función que obtiene los datos del movimiento de remito
        var dataRemito = await datosMovimientoRemito(referencia);
        // Realizar la llamada AJAX para cargar el modal
        $.ajax({
            url: '<?php echo base_url(ALM); ?>Reportes/modalReImpresion',
            type: 'GET',
            success: function(response) {

                console.log(dataRemito);
                $('#modalContainer').html(response); // Cargar el modal en el contenedor
                $('#modalRemito').modal('show');

                // Asignar los datos de la empresa al modal
                document.getElementById('logo_remito').src = dataEmpresa.logo.valor;
                $('#direccion_remito').html('<small>' + dataEmpresa.direccion.valor + '</small>');
                $('#telefono_remito').html('<small>' + dataEmpresa.telefono.valor + '</small>');
                $('#email_remito').html('<small>' + dataEmpresa.email.valor + '</small>');
                $('#texto_pie_remito').html('<strong>' + dataEmpresa.texto_pie_remito.valor +
                    '</strong>');

                // Asignar los datos del movimiento de remito al modal
                $('#conductor_remito').text(dataRemito[0].conductor);
                $('#patente_acoplado_remito').text(dataRemito[0].acoplado);
                $('#patente_remito').text(dataRemito[0].patente);
                $('#observaciones_remito').text(dataRemito[0].observaciones_recepciones);
                $('#nroRemito').text(dataRemito[0].num_comprobante);


                $('#depo_destino_remito').text(dataRemito[0].descr_depo_origen);
                $('#establecimiento_destino_remito').text(dataRemito[0].desc_lote_destino);

                $("#observaciones_remito").text(dataRemito[0].observaciones_recepcion);

                // Limpiar la tabla antes de agregar nuevas filas
                var tablaDetalle = $('#tabla_detalle tbody');
                tablaDetalle.empty();

                // Verifica si dataEmpresa es un arreglo
                if (Array.isArray(dataRemito)) {
                    // Recorrer el arreglo de datos y agregar cada fila a la tabla
                    dataRemito.forEach(function(datos) {
                        // Asegúrate de que cada objeto tenga las propiedades que necesitas
                        if (datos.cantidad_cargada && datos.unidad_medida && datos
                            .descripcion_articulo && datos.descr_depo_origen && datos
                            .lote_id_origen) {

                            cantidad_recibida = datos.cantidad_recibida ? datos.cantidad_recibida : ' '
                            // Crear una nueva fila con los datos y añadirla a la tabla
                            var fila = `
                        <tr>
                            <td style="text-align: left;">${datos.cantidad_cargada}</td>  <!-- Alineado a la derecha -->
                            <td style="text-align: left;">${cantidad_recibida}</td>  <!-- Alineado a la derecha -->
                            <td style="text-align: left;">${datos.unidad_medida}</td>
                            <td style="text-align: left;">${datos.descripcion_articulo}</td>    <!-- Alineado a la izquierda -->
                            <td style="text-align: left;">${datos.descr_depo_origen}</td>
                            <td style="text-align: left;">${datos.lote_id_origen}</td>
                        </tr>
                    `;

                            // Agregar la fila a la tabla
                            tablaDetalle.append(fila);
                        } else {
                            console.warn('Falta alguna propiedad en el objeto datos:', datos);
                        }
                    });
                    wc();
                } else {
                    console.error('dataRemito no es un arreglo:', dataRemito);
                }

            },
            error: function(xhr, status, error) {
                console.error('Error al cargar el modal:', error);
            }
        });
    } catch (error) {
        console.error('Error en modalReimpresion:', error);
    }
}

// Función que obtiene los datos del movimiento del remito (retorna una promesa)
function datosMovimientoRemito(data) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: '<?php echo base_url(ALM); ?>Reportes/datosMovimientoRemito',
            data: {
                data
            },
            type: 'POST',
            success: function(response) {
                try {
                    var resp = JSON.parse(response);
                    resolve(resp);
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
            url: 'index.php/<?php echo ALM?>Reportes/getDatosCabeceraRemito'
        });

        const resp = JSON.parse(response);

        // Imprimir los datos parseados
        console.log('Datos parseados:', resp);

        if (resp && resp.logo && resp.direccion && resp.telefono && resp.email && resp.texto_pie_remito) {
            return resp;
        } else {
            throw new Error('Estructura de datos inesperada en la respuesta');
        }

    } catch (error) {
        console.error('Error en DatosEmpresaRemito:', error);
        alert('Error al obtener los datos de la cabecera');
        throw error;
    }
}

function verAjuste(deaj_id) {
    wo();
    $.ajax({
        type: 'POST',
        data: {
          deaj_id
        },
        url: '<?php echo base_url(ALM) ?>Reportes/getDataAjuste',
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

function verIngreso(idremito) {
    wo();
    $.ajax({
        data: { idremito: idremito },
        dataType: 'json',
        type: 'POST',
        url: 'index.php/<?php echo ALM ?>Remito/consultar',
        success: function(data) {
            $('#comprobanteV').val(data['datosRemito'][0]['comprobante']);
            $('#fechaV').val(data['datosRemito'][0]['fecha']);
            $('#proveedorV').val(data['datosRemito'][0]['provnombre']);

            // Inicializar DataTable si no existe
            if (!$.fn.DataTable.isDataTable('#tablaconsulta')) {
                $('#tablaconsulta').DataTable({
                    "aLengthMenu": [ 10, 25, 50, 100 ],
                    "order": [[0, "asc"]],
                    "language": {
                        "url": '<?php echo base_url() ?>lib/bower_components/datatables.net/js/es-ar.json'
                    }
                });
            }

            var tabla = $('#tablaconsulta').DataTable(); 
            tabla.clear().draw();
            
            if (data['datosDetaRemitos'] != null) {
                for (var i = 0; i < data['datosDetaRemitos'].length; i++) { 
                    tabla.row.add([
                        data['datosDetaRemitos'][i]['codigo'],
                        data['datosDetaRemitos'][i]['artdescription'],
                        data['datosDetaRemitos'][i]['cantidad'],
                        data['datosDetaRemitos'][i]['depositodescrip'],
                        data['datosDetaRemitos'][i]['nomesta']
                    ]);
                }
                tabla.draw();
            }

            $('#modalIngreso').modal('show');
            wc();
        },
        error: function(result) {
            wc();
            alert("Error al traer datos de remito");
            console.table(result);
        }
    });   
}


function verEgreso(e) {
    wo();
    
    var id;
    if (e && typeof e === 'object') {
        var tr = $(e).closest('tr');
        id = $(tr).data('id') || (tr.data('json') ? tr.data('json').referencia : null);
    } else {
        id = e;
    }

    $.ajax({
        type: 'GET',
        url: 'index.php/<?php echo ALM ?>new/Entrega_Material/detalle?id=' + id,
        success: function (result) {
            if (result.cabecera) {
                $('#modal_detalle_entrega .enma_id').val(result.cabecera.enma_id);
                $('#modal_detalle_entrega .pema_id').val(result.cabecera.pema_id);
                $('#modal_detalle_entrega .orden').val(result.cabecera.ortr_id);
                $('#modal_detalle_entrega .comprobante').val(result.cabecera.comprobante);
                $('#modal_detalle_entrega .fecha').val(result.cabecera.fecha);
                $('#modal_detalle_entrega .entregado').val(result.cabecera.solicitante);
                $('#modal_detalle_entrega .estado').val(result.cabecera.estado);
            } else {
                $('#modal_detalle_entrega .enma_id').val(id);
                $('#modal_detalle_entrega .pema_id').val('');
                $('#modal_detalle_entrega .orden').val('');
                $('#modal_detalle_entrega .comprobante').val('');
                $('#modal_detalle_entrega .fecha').val('');
                $('#modal_detalle_entrega .entregado').val('');
                $('#modal_detalle_entrega .estado').val('');
            }

            var tabla = $('#modal_detalle_entrega table');
            $(tabla).find('tbody').html('');
            if (result.detalles) {
                result.detalles.forEach(d => {
                    $(tabla).find('tbody').append(
                        '<tr>' +
                        '<td>' + d.barcode + '</td>' +
                        '<td>' + d.descripcion + '</td>' +
                        '<td>' + d.lote + '</td>' +
                        '<td>' + d.deposito + '</td>' +
                        '<td class="text-center">' + d.cantidad + '</td>' +
                        '</tr>'
                    );
                });
            }

            $('#modal_detalle_entrega').modal('show');
            wc();
        },
        error: function (result) {
            wc();
            alert('Error al traer datos de egreso');
        },
        dataType: 'json'
    });
}

function verEgresoConsumoMP(e) {
    wo();
    
    var batch_id;
    if (e && typeof e === 'object') {
        var tr = $(e).closest('tr');
        batch_id = $(tr).data('id') || (tr.data('json') ? tr.data('json').referencia : null);
    } else {
        batch_id = e;
    }

    $.ajax({
        type: 'GET',
        url: 'index.php/<?php echo ALM ?>new/Entrega_Material/getEntregaXbatch?batch_id=' + batch_id,
        success: function (result) {
            debugger;
            console.log('litro');
            var tabla = $('#modal_detalle_entrega table');
            $(tabla).find('tbody').html('');

            if (result && result.list && result.list.length > 0) {
                // Datos del encabezado: tomados del primer elemento
                var header = result.list[0];
                $('#modal_detalle_entrega .enma_id').val(header.enma_id);
                $('#modal_detalle_entrega .pema_id').val(header.pema_id);
                $('#modal_detalle_entrega .comprobante').val(header.comprobante);
                $('#modal_detalle_entrega .fecha').val(header.fec_alta);
                $('#modal_detalle_entrega .entregado').val(header.solicitante);
                $('#modal_detalle_entrega .estado').val(header.estado);

                // Filas de la tabla: iterar sobre result.list
                result.list.forEach(function(d) {
                    $(tabla).find('tbody').append(
                        '<tr>' +
                        '<td>' + d.barcode + '</td>' +
                        '<td>' + d.articulo + '</td>' +
                        '<td>' + d.lote + '</td>' +
                        '<td>' + d.deposito + '</td>' +
                        '<td class="text-center">' + d.cantidad + '</td>' +
                        '</tr>'
                    );
                });
            } else {
                // Limpiar campos si no hay resultado
                $('#modal_detalle_entrega .enma_id').val('');
                $('#modal_detalle_entrega .pema_id').val('');
                $('#modal_detalle_entrega .comprobante').val('');
                $('#modal_detalle_entrega .fecha').val('');
                $('#modal_detalle_entrega .entregado').val('');
                $('#modal_detalle_entrega .estado').val('');
            }


            $('#modal_detalle_entrega').modal('show'); 
            wc();
        },
        error: function (result) {
            wc();
            alert('Error al traer datos de egreso');
        },
        dataType: 'json'
    });
}

/* function verSalidaEtapaProd(e) {
    // e puede ser un batch_id (número) o un elemento del DOM
    var batch_id;
    if (e && typeof e === 'object') {
        var tr = $(e).closest('tr');
        batch_id = $(tr).data('id') || (tr.data('json') ? tr.data('json').referencia : null);
    } else {
        batch_id = e;
    }

    // Limpiar y abrir el modal
    $('#modalTrazabilidadBody').html('<p class="text-center"><i class="fa fa-spinner fa-spin"></i> Cargando...</p>');
    $('#modalTrazabilidadEtapaProd').modal('show');

    wo();
    $.ajax({
        type: 'GET',
        data: { batch_id: batch_id },
        url: '<?php echo base_url(ALM) ?>Reportes/verSalidaEtapaProd',
        success: function(rsp) {
            
            $('#modalTrazabilidadBody').html(rsp);
        },
        error: function() {
            $('#modalTrazabilidadBody').html('<div class="alert alert-danger"><i class="fa fa-times-circle"></i> Error al cargar los datos de trazabilidad.</div>');
        },
        complete: function() {
            wc();
        }
    });
}
 */
function verSalidaEtapaProd(e) {
    wo();
    var batch_id;
    if (e && typeof e === 'object') {
        var tr = $(e).closest('tr');
        batch_id = $(tr).data('id') || (tr.data('json') ? tr.data('json').referencia : null);
    } else {
        batch_id = e;
    }

    // Limpiar y abrir el modal
    $('#modalTrazabilidadBody').html('<p class="text-center"><i class="fa fa-spinner fa-spin"></i> Cargando...</p>');
    $('#modalTrazabilidadEtapaProd').modal('show');

    wo();
    $.ajax({
        type: 'GET',
        data: { batch_id: batch_id },
        url: '<?php echo base_url(ALM) ?>Reportes/verSalidaEtapaProd',
        success: function(rsp) {
            // 1. Inyectamos la vista en el modal
            $('#modalTrazabilidadBody').html(rsp);
            
            // 2. Si viene un batch_id válido, autocompletamos la búsqueda
            if (batch_id) {
                // Selecciona el radio button de Batch ID
                $('#modalTrazabilidadBody input[name=tipoCodigo][value=batch]').prop('checked', true);
                // Llena el campo de texto con el batch_id
                $('#modalTrazabilidadBody #batch').val(batch_id);
                // Ejecuta la búsqueda automática si la función existe
                if (typeof buscarBatch === 'function') {
                    buscarBatch();
                     wc();
                }
            }
        },
        error: function() {
            $('#modalTrazabilidadBody').html('<div class="alert alert-danger"><i class="fa fa-times-circle"></i> Error al cargar los datos de trazabilidad.</div>');
        },
        complete: function() {
            wc();
        }
    });
}

$(document).ready(function() {
    // Ejecutar seleccionesta cuando se carga la página
    var establecimiento = document.getElementById('establecimiento');
    if (establecimiento && establecimiento.value) {
        seleccionesta(establecimiento);
    }
});

function clipMovimiento(demi_id){
    wo();
    console.log('Llamando a getDataMovimientoInterno con demi_id:', demi_id);
    $.ajax({
        type: 'POST',
        data: {
            demi_id
        },
        url: '<?php echo base_url(ALM) ?>Reportes/getDataMovimientoInterno',
        success: function(result) {     
            wc();       
            var parsedResult = JSON.parse(result);
            console.log(parsedResult);
            console.log('Resultado parseado:', parsedResult);
            // Asegurarse de que parsedResult es un array y tiene al menos un elemento
            if(Array.isArray(parsedResult) && parsedResult.length > 0) {
                
                let justificacion = parsedResult[0].justificacion;
                let demiId = parsedResult[0].demi_id;
                let cantidadCargada = parsedResult[0].cantidad_cargada;
                let cantidadRecibida = parsedResult[0].cantidad_recibida;

                // Mostrar el modal y llenar los campos
                $('#modalInfoMovimiento').modal('show');
                $('#demiIdMovimiento').val(demiId);
                $('#cantidadCargada').val(cantidadCargada);
                $('#cantidadRecibida').val(cantidadRecibida);

                // Mostrar u ocultar la justificación según corresponda
                if (justificacion && justificacion.trim() !== '') {
                    $('#justificacionContainer').show();
                    $('#justificacionMovimiento').val(justificacion);
                } else {
                    $('#justificacionContainer').hide();
                }

            } else {
                error('No se encontraron detalles para este movimiento.');
            }
        },
        error: function(xhr, status, error) {
             console.error('Error en la llamada AJAX:', status, error);
            alert('Ha ocurrido un error, por favor comunicarse con su proveedor de servicio. Gracias!');
        }
    });
}
</script>