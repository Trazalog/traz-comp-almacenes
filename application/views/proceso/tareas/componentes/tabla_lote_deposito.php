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
                <label for="fecha">Cantidad Disponible:</label>
                <input id="tit_disponible" type="text" class="form-control" disabled>
            </div>
        </div>
        <hr>

        <table class="table table-striped">
            <thead>
                <th>Lote</th>
                <th>Depóstio</th>
                <th>Cantidad</th>
                <th width="20%">a Extraer</th>
            </thead>
            <tbody id="lotes_depositos">

                <?php
                    foreach ($list as $o) {
                        echo '<tr>';
                        echo '<td class="hidden"><input class="lote_depo" value=\'' . json_encode(['lote_id' => $o['lote_id'], 'depo_id' => $o['depo_id'], 'arti_id' => $o['arti_id'], 'prov_id' => $o['prov_id'], 'empr_id' => $o['empr_id']]) . '\'></td>';
                        echo '<td>' . $o['codigo'] . '</td>';
                        echo '<td>' . $o['descripcion'] . '</td>';
                        echo '<td class="cant_lote">' . $o['cantidad'] . '</td>';
                        echo '<td><input class="form-control cantidad" placeholder="Ingrese Cantidad..." type="number" name="cantidad"></td>';
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

    function get_info_entrega() {
        return JSON.stringify(obj = {
            comprobante: $('#comprobante').val(),
            fecha: $('#fecha_entrega').val(),
            destino: $('#destino').val(),
            solicitante: $('#solicitante').val(),
            pema_id: $('#pema').val()
        });
    }

    function guardar_entrega() {

        if (!verificar_cantidad() || !validar_campos_obligatorios()) return;

        var array = [];

        $('#lotes_depositos tr').each(function (i, e) {

            var input = $(e).find('.lote_depo').val();

            var aux = JSON.parse(input);

            var num = $(e).find('.cantidad').val();

            if (num != '' && num != 0) { aux.cantidad = num; array.push(aux); }

        });

        $.ajax({
            url: 'almacen/Articulo/nuevaEntregaMaterial',
            type: 'POST',
            data: { info_entrega: get_info_entrega(), detalle: JSON.stringify(array) },
            success: function (data) {
                linkTo('general/Proceso/detalleTarea/' + $('#idTarBonita').val());
            },
            error: function (error) {
                alert('Error');
            }
        });

        $('.modal').modal('hide');
    }

    function validar_campos_obligatorios() {
        var ban = true;
        $('.required').each(function () {
            ban = ban && ($(this).val() != '');;
        });

        if (!ban) { alert('Campos Obligatorios Incompletos (*)'); return false; }

        return true;
    }

    function verificar_cantidad() {

        var disponible = parseInt($(select_row).find('.disponible').html());
        var pedido = parseInt($(select_row).find('.pedido').html());
        var entregado = parseInt($(select_row).find('.entregado').html());

        var acum = 0;

        $('#lotes_depositos tr').each(function (i, e) {

            var cant_lote = parseInt($(e).find('.cant_lote').html());

            var cant_extraer = parseInt($(e).find('.cantidad').val());

            if (cant_extraer > cant_lote) { acum = -1; return; }

            acum = acum + (isNaN(cant_extraer) ? 0 : cant_extraer);

        });

        if (acum == -1) { alert('Supera la Cantidad del Lote'); return false; }

        if (acum > disponible) { alert('Supera la Cantidad Disponible'); return false; }

        if ((acum + entregado) > pedido) { alert('Supera la Cantidad Pedida'); return false; }


        return true;
    }

</script>