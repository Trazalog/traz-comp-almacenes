<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Detalle Pedido Materiales</h3>
                </div><!-- /.box-header -->
                <div class="box-body">

                    <table id="tabladetalle" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Articulo</th>
                                <th>Cantidad</th>
                                <th class="hidden">Fecha Nota</th>
                                <th class="hidden">Fecha de Entrega</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->

<script>
var select_id  = null;
function del(e) {
    select_id = $(e).closest('tr').data('depe');
    $('#eliminar').modal('show');
}

function del_detalle(){
    $.ajax({
        type: 'POST',
        data: {
            id: select_id
        },
        url: 'index.php/almacen/Notapedido/eliminarDetalle',
        success: function(data) {
            get_detalle();
        },
        error: function(data) {
            alert('Error');
        }
    });
}

function edit() {
    var id = $(select).closest('tr').data('depe');
    var cantidad = $('#cantidad').val();
    $.ajax({
        type: 'POST',
        data: {
            id,
            cantidad
        },
        url: 'index.php/almacen/Notapedido/editarDetalle',
        success: function(data) {
            get_detalle();
            select = null;
            $('.modal').modal('hide');
        },
        error: function(data) {
            alert('Error');
        }
    });
}

get_detalle();

function get_detalle() {

    var id = $('#pema_id').val();

    $.ajax({
        type: 'POST',
        data: {
            id
        },
        url: 'index.php/almacen/Notapedido/getNotaPedidoId',
        success: function(data) {
            $('#tabladetalle').find('tbody').empty();
            $('tr.celdas').remove();
            for (var i = 0; i < data.length; i++) {
                var tr = "<tr class='celdas' data-depe='" + data[i]['depe_id'] + "'data-id='" + data[i][
                        'arti_id'
                    ] + "'>" +
                    "<td class='articulo'>" + data[i]['artDescription'] + "</td>" +
                    "<td class='cantidad'>" + data[i]['cantidad'] + "</td>" +
                    "<td class='hidden'>" + data[i]['fecha'] + "</td>" +
                    "<td class='hidden'>" + data[i]['fecha_entrega'] + "</td>" +
                    "<td class='text-light-blue'>" +
                    "<i class='fa fa-fw fa-pencil' style='cursor: pointer; margin-left: 15px;' title='Editar' onclick='edit_cantidad(this)'></i>" +
                    "<i class='fa fa-fw fa-times-circle' style='cursor: pointer; margin-left: 15px;' title='Eliminar' onclick='del(this);'></i></td></tr>";
                $('#tabladetalle tbody').append(tr);
            }

        },
        error: function(result) {

            console.log(result);
        },
        dataType: 'json'
    });
}
var select = null;
function edit_cantidad(e) {
    select = $(e).closest('tr');
    $('#set_cantidad input').val($(select).find('.cantidad').html());
    $('#set_cantidad h5').html($(select).find('.articulo').html());
    $('#set_cantidad').modal('show');
}



$('#tabladetalle').DataTable({
    "aLengthMenu": [25, 10, 25, 50, 100]
});
</script>


<div class="modal fade" id="set_cantidad">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Ingresar Cantidad</h4>
            </div>
            <div class="modal-body">
                <h5 class="text-center"></h5>
                <input id="cantidad" class="form-control text-center" type="number" placeholder="Cantidad">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary btn-accion" onclick="edit()">Guardar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- Modal eliminar-->
<div class="modal" id="eliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><span id="modalAction"
                        class="fa fa-fw fa-times-circle text-light-blue"></span> Eliminar Artículo</h4>
            </div> <!-- /.modal-header  -->

            <div class="modal-body" id="modalBodyArticle">
                <h4 class="text-center">¿Confirmar Operación? </h4>
            </div> <!-- /.modal-body -->

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnSave" data-dismiss="modal"
                    onclick="del_detalle()">Eliminar</button>
            </div> <!-- /.modal footer -->

        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog modal-lg -->
</div> <!-- /.modal fade -->
<!-- / Modal -->