<section>

    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Pedido Materiales</h3>
            <button class="btn btn-block btn-primary" style="width: 100px; margin-top: 10px;"
                onclick="linkTo('almacen/Notapedido/crearPedido')">Agregar</button>
        </div><!-- /.box-header -->
        <div class="box-body">
            <table id="deposito" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th width="1%">Acciones</th>
                        <th width="10%">Pedido</th>
                        <th width="20%" class="text-center">Fecha</th>
                        <th>Detalle</th>
                        <th class="<?php echo(!viewOT?"hidden":null)?>" width="10%">Ord.Trabajo</th>
                        <th width="10%">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                            if($list) {
                                foreach($list as $z)
                                {
                                $id = $z['id_notaPedido'];
                                echo '<tr id="'.$id.'" class="'.$id.'" data-json=\''.json_encode($z).'\'>';
                                echo '<td class="text-center"><i onclick="ver(this)" class="fa fa-fw fa-search text-light-blue buscar" style="cursor: pointer;" title="Detalle Pedido Materiales"></i></td>';           
                                echo '<td class="text-center">'.bolita($z['id_notaPedido'],'blue').'</td>';
                                echo '<td class="text-center">'.fecha($z['fecha']).'</td>';
                                echo '<td>'.(viewOT?$z['descripcion']:$z['justificacion']).'</td>';
                                echo '<td class="text-center '.(!viewOT?"hidden":null).'">'.bolita('OT: '.$z['id_ordTrabajo'],'yellow','Orden de Trabajo N°'.$z['id_ordTrabajo']).'</td>';
                                echo '<td class="text-center">'.estadoPedido($z['estado']).'</td>';
                                echo '</tr>';
                                }
                            }
                            ?>
                </tbody>
            </table>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

</section><!-- /.content -->

<script>
function ver(e) {
    var tr = $(e).closest('tr')
    var id_nota = $(tr).attr('id');
    var json = JSON.parse(JSON.stringify($(tr).data('json')));

    if (id_nota == null) return;
    $.ajax({
        type: 'POST',
        data: {
            id: id_nota
        },
        url: 'index.php/almacen/Notapedido/getNotaPedidoId',
        success: function(data) {
            $('#tabladetalle').DataTable().destroy();
            $('#tabladetalle tbody').html('');
            for (var i = 0; i < data.length; i++) {
                var tr = "<tr style='color:'>" +
                    "<td>" + data[i]['barcode'] + "</td>" +
                    "<td>" + data[i]['artDescription'] + "</td>" +
                    "<td class='text-center' width='15%'><b>" + data[i]['cantidad'] + "</b></td>" +
                    "<td class='text-center' width='15%'><b>" + data[i]['entregado'] + "</b></td>" +
                    "</tr>";
                $('#tabladetalle tbody').append(tr);
            }
            DataTable('#tabladetalle');

            rellenarCabecera(json);

            $('#detalle_pedido').modal('show');
        },
        error: function(result) {

            console.log(result);
        },
        dataType: 'json'
    });

}
//Ver Orden
function rellenarCabecera(json) {
    $('#detalle_pedido .pedido').val(json.id_notaPedido );
    $('#detalle_pedido .descrip').val(json.justificacion);
    $('#detalle_pedido .fecha').val(json.fecha);
    $('#detalle_pedido .estado').val(json.estado);
    $('#detalle_pedido .orden').val(json.id_ordTrabajo);
}

DataTable('#tabladetalle');
DataTable('#deposito');
</script>


<!-- Modal ver nota pedido-->
<div class="modal fade" id="detalle_pedido" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><span id="modalAction"
                        class="fa fa-plus-square text-light-blue"></span> Detalle Pedido Materiales</h4>
                <br>

                <div class="row">
                    <div class="col-xs-12 col-sm-3 col-lg-3">
                        <label for="">Pedido:</label>
                        <input class="form-control pedido" type="text" value="???" readonly>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-lg-3 <?php echo (!viewOT ? "hidden" : null) ?>">
                        <label for="">Orden de Trabajo:</label>
                        <input class="form-control orden" type="text" value="???" readonly>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-lg-3">
                        <label for="">Fecha:</label>
                        <input class="form-control fecha" type="text" value="???" readonly>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-lg-3">
                        <label for="">Estado:</label>
                        <input class="form-control estado" type="text" value="???" readonly>
                    </div><br>
                    <div class="col-xs-12 col-sm-12 col-lg-12">
                        <label for="">Descripcion:</label>
                        <input class="form-control descrip" type="text" value="???" readonly>
                    </div>
                </div>

            </div> <!-- /.modal-header  -->

            <div class="modal-body table-responsive" id="modalBodyArticle">
                <div class="row">
                    <div class="col-xs-12">
                        <table id="tabladetalle" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Cod. Artículo</th>
                                    <th>Descripción</th>
                                    <th class="text-center">Pedido</th>
                                    <th class="text-center">Entregado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- -->
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