<hr>
<input type="number" class="hidden" value="<?php echo $pema_id ?>" id="pemaId">
<h3>Pedido Materiales <small>Detalle</small></h3>
<div id="nota_pedido">
    <table id="tabladetalle" class="table table-striped table-hover table-bordered">
        <thead>
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th class="text-center">Cantidad</th>
                <th class="text-center">Fecha Nota</th>
                <th class="text-center">Stock</th>
            </tr>
        </thead>
        <tbody>
            <!--Detalle Pedido Materiales -->
        </tbody>
    </table>
</div>
<hr>

<form id="generic_form">
    <div class="form-group">
        <center>
            <h4 class="text-danger"> ¿Se Aprueba o Rechaza el Pedido de Materiales? </h4>
            <label class="radio-inline">
                <input type="radio" name="result" value="true"
                    onclick="$('#motivo').hide();$('#hecho').prop('disabled',false);"> Aprobar
            </label>
            <label class="radio-inline">
                <input id="rechazo" type="radio" name="result" value="false"
                    onclick="$('#motivo').show();$('#hecho').prop('disabled',false);"> Rechazar
            </label>
        </center>
    </div>

    <div id="motivo" class="form-group motivo">
        <textarea class="form-control" name="motivo_rechazo" placeholder="Motivo de Rechazo..."></textarea>
    </div>
</form>

<script>
$('#motivo').hide();
$('#hecho').prop('disabled', true);



cargarPedidos();

function cargarPedidos() {
    $.ajax({
        type: 'POST',
        data: {
            id: $('#pemaId').val()
        },
        url: 'index.php/almacen/new/Pedido_Material/obtener',
        success: function(data) {

            $('tr.celdas').remove();
            for (var i = 0; i < data.length; i++) {
                var tr = "<tr class='celdas'>" +
                    "<td>" + data[i]['barcode'] + "</td>" +
                    "<td>" + data[i]['descripcion'] + "</td>" +
                    "<td class='text-center'>" + data[i]['cantidad'] + "</td>" +
                    "<td class='text-center'>" + fecha(data[i]['fec_alta']) + "</td>" +
                    "<td class='text-center'>"+data[i]['stock']+"</td>" +
                    "</tr>";
                $('table#tabladetalle tbody').append(tr);
            }
            DataTable('table#tabladetalle',false);
        },
        error: function(result) {

            console.log(result);
        },
        dataType: 'json'
    });
}

function cerrarTarea() {

    if ($('#rechazo').prop('checked') && $('#motivo .form-control').val() == '') {
        alert('Completar Motivo de Rechazo');
        return;
    }

    var id = $('#idTarBonita').val();

    var dataForm = new FormData($('#generic_form')[0]);

    dataForm.append('pema_id', $('#pemaId').val());

    $.ajax({
        type: 'POST',
        data: dataForm,
        cache: false,
        contentType: false,
        processData: false,
        url: '<?php base_url() ?>index.php/almacen/Proceso/cerrarTarea/' + id,
        success: function(data) {
            //WaitingClose();
            linkTo('Tarea');

        },
        error: function(data) {
            alert("Error");
        }
    });

}
</script>