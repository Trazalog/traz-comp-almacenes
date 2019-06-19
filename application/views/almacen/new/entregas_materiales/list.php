<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Entrega Materiales</h3>
    </div><!-- /.box-header -->
    <div class="box-body table-responsive">
        <table id="entregas" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th width="10%">Acciones</th>
                    <th width="10%">Pedido</th>
                    <th width="10%">Entrega</th>
                    <th class="<?php echo (!viewOT ? "hidden" : null) ?>">Ord.Trabajo</th>
                    <th class="text-center" width="10%">Fecha</th>
                    <th>N° Comprobante</th>
                    <th>Entregado</th>
                    <th width="12%">Estado Ped.</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($list as $o) {
                        echo '<tr data-id='.$o['enma_id'].' data-pema="'.$o['pema_id'].'" data-json=\''.json_encode($o).'\'>'; 
                        echo '<td>';
                        echo '<i class="fa fa-fw fa-print text-light-blue" style="cursor: pointer;" title="Imprimir"></i> ';
                        echo '<i class="fa fa-fw fa-search text-light-blue btn-buscar" style="cursor: pointer;" title="Consultar"></i> ';
                        echo '<i class="fa fa-fw fa-meh-o text-light-blue btn-estado" style="cursor: pointer;" title="Estado Pedido"></i> ';
                        echo '</td>';
                        echo '<td class="text-center">'.bolita($o['pema_id'],'blue').'</td>';
                        echo '<td class="text-center">'.bolita($o['enma_id'],'aqua').'</td>';
                        echo '<td class="text-center '.(!viewOT ? "hidden" : null).'">'.bolita($o['ortr_id'],'orange').'</td>';
                        echo '<td class="text-center">'.fecha($o['fecha']).'</td>';
                        echo '<td>'.$o['comprobante'].'</td>';
                        echo '<td>'.$o['solicitante'].'</td>';
                        echo '<td class="text-center" style="cursor: pointer;">'.estadoPedido($o['estado']).'</td>';
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>
    </div><!-- /.box-body -->
</div><!-- /.box -->

<script>
DataTable('#entregas');

$('.btn-estado').click(function() {

    var id = $(this).closest('tr').data('pema');
    if (id == '' || id == null) return;
    $.ajax({
        type: 'POST',
        url: 'index.php/almacen/new/Pedido_Material/estado',
        data: {
            id
        },
        success: function(result) {
            var tabla = $('#detalle_pedido table');
            $(tabla).DataTable().destroy();
            $(tabla).find('tbody').empty();
            result.forEach(e => {
                $(tabla).append(
                    '<tr>' +
                    '<td>' + e.barcode + '</td>' +
                    '<td class="text-center"><b>'+ e.cantidad + '</b></td>' +
                    '<td class="text-center"><b>'+ (e.cantidad - e.resto) +'</b></td>' +
                    '</tr>'
                );
            });

            DataTable(tabla);
            $('#detalle_pedido').modal('show');
        },
        error: function(result) {
            alert('Error');
        },
        dataType: 'json'
    });
});

$('.btn-buscar').click(function() {
    var tr = $(this).closest('tr');
    var id = $(tr).data('id');
    var json = JSON.parse(JSON.stringify($(tr).data('json')));

    $.ajax({
        type: 'POST',
        url: 'index.php/almacen/new/Entrega_Material/detalle',
        data: {
            id
        },
        success: function(result) {
            var tabla = $('#detalle_entrega table');
            $(tabla).DataTable().destroy();
            $(tabla).find('tbody').empty();
            result.forEach(e => {
                $(tabla).append(
                    '<tr>' +
                    '<td>' + e.barcode + '</td>' +
                    '<td>' + e.descripcion + '</td>' +
                    '<td>' + e.lote + '</td>' +
                    '<td>' + e.deposito + '</td>' +
                    '<td class="text-center">' + e.cantidad + '</td>' +
                    '</tr>'
                );
            });
            DataTable(tabla);

            rellenarCabecera(json);
            $('#detalle_entrega').modal('show');
        },
        error: function(result) {
            alert('Error');
        },
        dataType: 'json'
    });
});

function rellenarCabecera(json) {
    $('#detalle_entrega .enma_id').val(json.enma_id);
    $('#detalle_entrega .pema_id').val(json.pema_id);
    $('#detalle_entrega .orden').val(json.ortr_id);
    $('#detalle_entrega .comprobante').val(json.comprobante);
    $('#detalle_entrega .fecha').val(json.fecha);
    $('#detalle_entrega .entregado').val(json.solicitante);
    $('#detalle_entrega .estado').val(json.estado);
}
</script>

<!-- Modal ver nota pedido-->
<div class="modal fade" id="detalle_pedido" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><span id="modalAction"
                        class="fa fa-plus-square text-light-blue"></span> Estado Pedido Materiales</h4>
            </div> <!-- /.modal-header  -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Cod. Artículo</th>
                                    <th class="text-center">Cant. Pedida</th>
                                    <th class="text-center">Cant. Pedida</th>
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

<!-- Modal ver nota pedido-->
<div class="modal fade" id="detalle_entrega" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><span id="modalAction"
                        class="fa fa-search text-light-blue"></span> Detalle Pedido Materiales</h4>
                <br>
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
            </div> <!-- /.modal-header  -->
            <div class="modal-body">
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