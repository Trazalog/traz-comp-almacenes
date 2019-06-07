<section>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Pedido Materiales</h3>
                <button class="btn btn-block btn-primary" style="width: 100px; margin-top: 10px;" onclick="">Agregar</button>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table id="deposito" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="1%">Acciones</th>
                                <th width="5%">N° Pedido</th>
                                <th width="5%">Ord. Trabajo</th>
                                <th>Detalle</th>
                                <th width="20%">Fecha Nota</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
              if($list) {
                foreach($list as $z)
                {
                  $id = $z['id_notaPedido'];
                  echo '<tr id="'.$id.'" class="'.$id.'">';
            
                  echo '<td><i onclick="ver(this)" class="fa fa-fw fa-search text-light-blue buscar" style="cursor: pointer; margin-left: 15px;" title="Detalle Pedido Materiales"></i></td>';
                              
                  echo '<td class="text-center">'.bolita($z['id_notaPedido'],null,'blue').'</td>';
                  echo '<td class="text-center">'.bolita('OT: '.$z['id_ordTrabajo'],'Orden de Trabajo N°'.$z['id_ordTrabajo'],'yellow').'</td>';
                  echo '<td>'.$z['descripcion'].'</td>';
                  echo '<td>'.fecha($z['fecha']).'</td>';
                
                  echo '</tr>';
                }
              }
              ?>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->

<script>

function ver(e) {
     var id_nota = $(e).closest('tr').attr('id');

    if(id_nota == null)return;
    $.ajax({
        type: 'POST',
        data: {
            id: id_nota
        },
        url: 'index.php/almacen/Notapedido/getNotaPedidoId',
        success: function(data) {
           $('#tabladetalle').DataTable().destroy();
            $('tr.celdas').remove();
            for (var i = 0; i < data.length; i++) {
                var tr = "<tr class='celdas'>" +
                    "<td>" + data[i]['barcode'] + "</td>" +
                    "<td>" + data[i]['artDescription'] + "</td>" +
                    "<td class='text-center' width='5%'>" + data[i]['cantidad'] + "</td>" +
                    "<td class='text-center' width='20%'>" + data[i]['fecha'] + "</td>" +
                    "</tr>";
                $('#tabladetalle tbody').append(tr);
            }

         
            DataTable('#tabladetalle');

            $('#detalle_pedido').modal('show');
        },
        error: function(result) {

            console.log(result);
        },
        dataType: 'json'
    });

}
//Ver Orden


DataTable('#tabladetalle');
DataTable('#deposito');

function DataTable(tabla) {
    $(tabla).DataTable({
        "aLengthMenu": [10, 25, 50, 100],
        "columnDefs": [{
                "targets": [0],
                "searchable": false
            },
            {
                "targets": [0],
                "orderable": false
            }
        ],
        "order": [
            [1, "asc"]
        ],
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    });
}
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
            </div> <!-- /.modal-header  -->

            <div class="modal-body" id="modalBodyArticle">
                <div class="row">
                    <div class="col-xs-12">
                        <table id="tabladetalle" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Cod. Artículo</th>
                                    <th>Descripción</th>
                                    <th>Cant. Solicitada</th>
                                    <th>Fecha Pedido</th>
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