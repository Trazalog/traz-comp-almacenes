<section class="content">
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Pedido Materiales</h3>
            <button class="btn btn-block btn-primary <?php echo (isset($autoLanzar)&&$autoLanzar==true?'hidden':null)?>" style="width: 100px; margin-top: 10px;"
                onclick="linkTo('<?php echo base_url(CMP_ALM) ?>Notapedido/crearPedido<?php echo'/'.(isset($ot)?$ot:null) ?>')">Agregar</button>

                <button class="btn btn-block btn-primary <?php echo(isset($autoLanzar)&&$autoLanzar==true?null:'hidden')?>" style="width: 100px; margin-top: 10px;"
                onclick="linkTo('<?php echo base_url(CMP_ALM) ?>Notapedido/crearPedido2<?php echo'/'.(isset($ot)?$ot:null) ?>')">Agregar</button>
                
        </div><!-- /.box-header -->
        <div class="box-body">
            <table id="deposito" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th width="1%">Acciones</th>
                        <th width="10%">Pedido</th>
                        <th width="10%" class="<?php echo(!viewOT?"hidden":null)?>">Ord.Trabajo</th>
                        <th width="10%" class="text-center">Fecha</th>
                        <th>Detalle</th>
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
                                echo '<td class="text-center">';
                                echo '<i onclick="ver(this)" class="fa fa-fw fa-search text-light-blue buscar" style="cursor: pointer;margin:5px;" title="Detalle Pedido Materiales"></i>';
                                echo '</td>';           
                                echo '<td class="text-center">'.bolita($z['id_notaPedido'],'blue').'</td>';
                                echo '<td class="text-center '.(!viewOT?"hidden":null).'">'.bolita($z['id_ordTrabajo'],'yellow','Orden de Trabajo N°'.$z['id_ordTrabajo']).'</td>';
                               
                                echo '<td class="text-center">'.fecha($z['fecha']).'</td>';
                                echo '<td>'.(viewOT?$z['descripcion']:$z['justificacion']).'</td>';
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
    rellenarCabecera(json);
    getEntregasPedido(id_nota);

    if (id_nota == null) return;
    $.ajax({
        type: 'POST',
        data: {
            id: id_nota
        },
        url: '<?php echo base_url(CMP_ALM) ?>Notapedido/getNotaPedidoId',
        success: function(data) {
         //   $('#tabladetalle').DataTable().destroy();
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
            //DataTable('#tabladetalle');

            

            $('#detalle_pedido').modal('show');
        },
        error: function(result) {

            console.log(result);
        },
        dataType: 'json'
    });

}

function getEntregasPedido(pema) {
    $('#tab_2').load('<?php echo base_url(CMP_ALM) ?>new/Entrega_Material/getEntregasPedido/'+pema);
}
//Ver Orden
function rellenarCabecera(json) {
    $('#detalle_pedido .pedido').val(json.id_notaPedido);
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
    <div class="modal-dialog modal-lg" >
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">
                        <h4><span class="fa fa-search text-light-blue"></span> Detalle Pedido Material</h4>
                    </a></li>
                <li><a href="#tab_2" data-toggle="tab">
                        <h4><span class="fa fa-check text-light-blue"></span>Entrega Asociadas al Pedido</h4>
                    </a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="modal-content">

                        <div class="modal-header">
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
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                        </div> <!-- /.modal footer -->

                    </div> <!-- /.modal-content -->
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">

                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>

    </div> <!-- /.modal-dialog modal-lg -->
</div> <!-- /.modal fade -->
<!-- / Modal -->