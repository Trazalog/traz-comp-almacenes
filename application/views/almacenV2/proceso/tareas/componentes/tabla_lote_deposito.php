<div class="panel panel-default">
    <div class="panel-heading">
        Artículo: <?php echo $articulo['descripcion'] ?>
    </div>

    <div class="panel-body">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-4">
                <label for="comprobante">Cantidad Pedida:</label>
                <input id="tit_pedida" type="text" class="form-control" disabled>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <label for="fecha">Cantidad Entregada:</label>
                <input id="tit_entregada" type="text" class="form-control" disabled>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <label for="fecha">Cantidad en Stock:</label>
                <input id="tit_disponible" type="text" class="form-control" disabled>
            </div>
        </div>
        <hr>

        <table id="lotes" class="table table-striped">
            <thead>
                <tr>
                    <th>Lote</th>
                    <th class="text-center">Fec. Vencimiento</th>
                    <th>Depóstio</th>
                    <th>Cantidad</th>
                    <th width="20%">a Extraer</th>
                </tr>
            </thead>
            <tbody id="lotes_depositos">

                <?php
                    foreach ($list as $o) {
                        echo '<tr>';
                        echo '<td>' . $o['codigo'] . '<input class="lote_depo hidden" value=\'' . json_encode(['lote_id' => $o['lote_id'], 'depo_id' => $o['depo_id'], 'arti_id' => $o['arti_id'], 'prov_id' => $o['prov_id'], 'empr_id' => $o['empr_id']]) . '\'></td>';
                        echo '<td class="text-center">' . fecha($o['fec_vencimiento']) . '</td>';
                        echo '<td>' . $o['descripcion'] . '</td>';
                        echo '<td class="cant_lote">' . $o['cantidad'] . '</td>';
                        echo '<td><input min="0" step="1" pattern="\d*" class="form-control cantidad" placeholder="Ingrese Cantidad..." type="number" name="cantidad" ></td>';
                        echo '</tr>';
                    }
                    ?>

            </tbody>
        </table>
        <button class="btn btn-primary" style="float:right" onclick="guardar_entrega()">Guardar</button>
    </div>
</div>

<script>
index();
function index() {
    $('#tit_pedida').val($(select_row).find('.pedido').html());
    $('#tit_entregada').val($(select_row).find('.entregado').html());
    $('#tit_disponible').val($(select_row).find('.disponible').html());
}

function guardar_entrega() {

    if (!verificar_cantidad()) return;

    var array = [];

    var sum = 0;

    $('#lotes_depositos tr').each(function(i, e) {

        var input = $(e).find('.lote_depo').val();

        var aux = JSON.parse(input);

        var num = $(e).find('.cantidad').val();

        if (!isNaN(num) && num != 0) {
            aux.cantidad = Math.trunc(num);
            array.push(aux);
            sum += parseInt(num);
        }

    });

    $(select_row).find('.extraer').html(sum);
    //Calcular Restante
    $(select_row).attr('resto', parseInt($(select_row).find('.pedido').html()) - (parseInt($(select_row).find(
        '.entregado').html()) + sum));

    $(select_row).attr('data-json', JSON.stringify(array));

    $('.modal').modal('hide');

}

function actualizar_entregas() {
    $.ajax({
        url: 'almacen/Notapedido/getTablaDetalle/' + $('#pema').val(),
        type: 'POST',
        success: function(data) {
            $('#entregas').empty();
            $('#entregas').html(data);
        },
        error: function(error) {
            alert('Error');
        }
    });
}



function verificar_cantidad() {

    var disponible = parseInt($(select_row).find('.disponible').html());
    var pedido = parseInt($(select_row).find('.pedido').html());
    var entregado = parseInt($(select_row).find('.entregado').html());
    entregado = isNaN(entregado) ? 0 : entregado;

    var acum = 0;

    $('#lotes_depositos tr').each(function(i, e) {

        var cant_lote = parseInt($(e).find('.cant_lote').html());

        var cant_extraer = parseInt($(e).find('.cantidad').val());

        if (cant_extraer > cant_lote) {
            acum = -1;
            return;
        }

        acum = acum + (isNaN(cant_extraer) ? 0 : cant_extraer);

    });

    if (acum == -1) {
        alert('Supera la Cantidad del Lote');
        return false;
    }

    if (acum > disponible) {
        alert('Supera la Cantidad Disponible');
        return false;
    }

    if ((acum + entregado) > pedido) {
        alert('Supera la Cantidad Pedida');
        return false;
    }


    return true;
}

$('table#lotes').DataTable({
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
});

</script>