<input id="pema_id" type="number" class="hidden" value="<?php echo (isset($info->pema_id)?$info->pema_id:null)?>">
<input id="ortr_id" type="number" class="hidden" value="<?php echo (isset($info->ortr_id)?$info->ortr_id:null)?>">

<div class="row">
    <div
        class="<?php echo (isset($info->pema_id)?'hidden':null)?>  col-xs-12 col-sm-12 col-md-12 <?php echo(viewOT?'hidden':null)?>">
        <div class="form-group">
            <label>Justificacíon:</label>
            <textarea id="just" type="text" class="form-control <?php echo (isset($info->pema_id)?'hidden':null)?>"
                placeholder="Ingrese Justificación..."></textarea>
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <label>Seleccionar Artículo:</label>
            <?php $this->load->view(CMP_ALM.'/articulo/componente'); ?>
        </div>
    </div>
    <div class="col-xs-3 col-sm-3 col-md-3">
        <div class="form-group">
            <label>Cantidad:</label>
            <input id="add_cantidad" type="number" min="0" step="1" class="form-control" placeholder="Cantidad">
        </div>
    </div>
    <div class="col-xs-3 col-sm-3 col-md-3" style="margin-top:25px">
        <button class="btn btn-primary" onclick="guardar_pedido();"><i class="fa fa-check"></i>Agregar</button>
    </div>
</div>
<table id="tabladetalle" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th width="1%">Acciones</th>
            <th>Artículo</th>
            <th class="text-center">Cantidad</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<div class="modal-footer <?php echo (isset($info->pema_id)?'hidden':null)?>">
    <button class="btn" onclick="linkTo('almacen/Notapedido')">Cerrar</button>
    <button class="btn btn-primary <?php echo(viewOT&&$hecho==false?'hidden':null)?>" style="float:right;"
        onclick="lanzarPedido()">Hecho</button>
</div>
<script>
var selectRow = null;

function del(e) {
    selectRow = $(e).closest('tr');
    $('#eliminar').modal('show');
}

function del_detalle() {
    if (selectRow.hasClass('new')) $(selectRow).remove();
    else {
        $.ajax({
            type: 'POST',
            data: {
                id: $(selectRow).data('id')
            },
            url: 'index.php/almacen/Notapedido/eliminarDetalle',
            success: function(data) {
                $('#eliminar').modal('hide');
                get_detalle();
            },
            error: function(data) {
                alert('Error');
            }
        });
    }
}

function edit() {

    var id = $(selectRow).closest('tr').data('id');
    var cantidad = $('#set_cantidad #cantidad').val();
    $.ajax({
        type: 'POST',
        data: {
            id,
            cantidad
        },
        url: 'index.php/almacen/Notapedido/editarDetalle',
        success: function(data) {
            get_detalle();
            selectRow = null;
            $('#set_cantidad').modal('hide');
        },
        error: function(data) {
            alert('Error');
        }
    });

}

function edit_cantidad(e) {
    selectRow = $(e).closest('tr');
    $('#set_cantidad input').val($(selectRow).find('.cantidad').html());
    $('#set_cantidad h5').html($(selectRow).find('.articulo').html());
    $('#set_cantidad').modal('show');
}

get_detalle();

function get_detalle() {

    var id = $('#pema_id').val();
    if (id == null || id == '') {
        DataTable('#tabladetalle');
        return;
    }
    $.ajax({
        type: 'POST',
        data: {
            id
        },
        url: 'index.php/almacen/Notapedido/getNotaPedidoId',
        success: function(data) {
            $('#tabladetalle').DataTable().destroy();
            $('#tabladetalle').find('tbody').empty();

            for (var i = 0; i < data.length; i++) {
                var tr = "<tr class='celdas' data-id='" + data[i]['depe_id'] + "'data-id='" + data[i]['arti_id'] + "'>" +
                    "<td class='text-light-blue'>" +
                    "<i class='fa fa-fw fa-pencil' style='cursor: pointer;' title='Editar' onclick='edit_cantidad(this)'></i>" +
                    "<i class='fa fa-fw fa-times-circle' style='cursor: pointer;' title='Eliminar' onclick='del(this);'></i></td>" +
                    "<td class='articulo'>" + data[i]['barcode'] + "</td>" +
                    "<td class='cantidad text-center'>" + data[i]['cantidad'] + "</td></tr>";
                $('#tabladetalle tbody').append(tr);
            }
            DataTable('#tabladetalle');


        },
        error: function(result) {

            console.log(result);
        },
        dataType: 'json'
    });
}
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
                <button type="button" class="btn btn-primary btn-accion" data-dismiss="modal"
                    onclick="edit()">Guardar</button>
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
                <button type="button" class="btn btn-primary" onclick="del_detalle()">Eliminar</button>
            </div> <!-- /.modal footer -->

        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog modal-lg -->
</div> <!-- /.modal fade -->
<!-- / Modal -->


<script>
function guardar_pedido() {

    if (!validarCampos()) {
        alert('Completar Campos');
        return;
    }

    if ($('#pema_id').val() == '' || $('#pema_id').val() == 0) {

        set_pedido();

    } else {

        edit_pedido();

    }
}

function set_pedido() {

    var idinsumos = new Array();
    var cantidades = new Array();

    id = selectItem.arti_id;
    idinsumos.push(id);
    cant = $('#add_cantidad').val();
    cantidades.push(cant);

    var idOT = $('#ortr_id').val();

    if (idinsumos.length == 0) {
        alert('Error');
        return;
    }

    var peex_id = $('#peex_id').val();
    var justificacion = $('#just').val();

    WaitingOpen("Guardando pedido...");

    $.ajax({
        data: {
            idinsumos,
            cantidades,
            idOT,
            peex_id,
            justificacion
        },
        type: 'POST',
        dataType: 'json',
        url: 'index.php/almacen/Notapedido/setNotaPedido',
        success: function(result) {
            $('#pema_id').val(result.pema_id);
            WaitingClose();
            get_detalle();
            clear();
        },
        error: function(result) {
            WaitingClose();
            alert("Error en guardado...");
        },
    });
}

function lanzarPedido() {
    $.ajax({
        data: {
            id: $('#pema_id').val()
        },
        type: 'POST',
        url: 'index.php/almacen/new/Pedido_Material/pedidoNormal',
        success: function(result) {
            linkTo('almacen/Notapedido');
        },
        error: function(result) {
            WaitingClose();
            alert("Error al Lanzar Pedido");
        }
    });
}

function edit_pedido() {

    var idinsumos = new Array();
    var cantidades = new Array();

    id = selectItem.arti_id;
    idinsumos.push(id);
    cant = $('#add_cantidad').val();
    cantidades.push(cant);

    var idOT = $('#ortr_id').val();

    if (idinsumos.length == 0) {
        alert('Error');
        return;
    }

    WaitingOpen("Guardando pedido...");

    $.ajax({
        data: {
            idinsumos,
            cantidades,
            idOT,
            pema: $('#pema_id').val()
        },
        type: 'POST',
        dataType: 'json',
        url: 'index.php/almacen/Notapedido/editPedido',
        success: function(result) {
            WaitingClose();
            get_detalle();
            clear();
        },
        error: function(result) {
            WaitingClose();
            alert("Error en guardado...");
        }
    });
}

function validarCampos() {

    if ($('#inputarti').val() == null) return false;

    if ($('#add_cantidad') == null) return false;

    return true;
}

function clear() {
    $('#inputarti').val(null);
    $('#add_cantidad').val(null);
}
</script>