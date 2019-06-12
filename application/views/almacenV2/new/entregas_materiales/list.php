<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Entrega Materiales</h3>
    </div><!-- /.box-header -->
    <div class="box-body table-responsive">
        <table id="entregas" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th width="5%">Acciones</th>
                    <th width="10%">N° Entrega</th>
                    <th width="10%">N° Pedido</th>
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
                        echo '<tr data-id='.$o['enma_id'].'>'; 
                        echo '<td>';
                        echo '<i class="fa fa-fw fa-print text-light-blue" style="cursor: pointer; margin-left: 5px;" title="Imprimir"></i> ';
                        echo '<i class="fa fa-fw fa-search text-light-blue" style="cursor: pointer; margin-left: 5px;" title="Consultar"></i> ';
                        echo '</td>';
                        echo '<td class="text-center">'.bolita($o['enma_id'],'aqua').'</td>';
                        echo '<td class="text-center">'.bolita($o['pema_id'],'blue').'</td>';
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

$('.estado').click(function() {

    var id = $(this).closest('tr').data('id');
    if (id == '' || id == null) return;
    $.ajax({
        type: 'POST',
        url: 'index.php/almacen/new/Pedido_Material/estado',
        data: {
            id
        },
        success: function(result) {
            var tabla = $('#detalle_pedido table');
            result.forEach(e => {
                $(tabla).append(
                    '<tr>' +
                    '<td>' + e.barcode + '</td>' +
                    '<td class="text-center">'+(e.cantidad-e.resto)+' / '+e.cantidad+'</td>' +
                    '</tr>'
                );
            });
            $('#detalle_pedido').modal('show');
        },
        error: function(result) {
            alert('Error');
        },
        dataType: 'json'
    });
});
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
                                    <th class="text-center">Cant. Entregada / Cant. Pedida</th>
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