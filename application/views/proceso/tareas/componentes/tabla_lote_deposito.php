<div class="panel panel-default">
    <div class="panel-heading">
        Artículo: <?php echo $articulo['descripcion'] ?>
    </div>

    <div class="panel-body">

        <div class="row">
            <div class="col-sm-4 text-light-blue"><h3 id="tit_pedida">Cantidad Pedida: ???</h3></div>
            <div class="col-sm-4 text-light-blue"><h3 id="tit_entregada">Cantidad Entregada: ???</h3></div>
            <div class="col-sm-4 text-light-blue"><h3 id="tit_disponible">Cantidad Disponible: ???</h3></div>
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
                                echo '<td class="hidden"><input class="lote_depo" value=\''.json_encode(['lote_id'=>$o['lote_id'] , 'depo_id'=>$o['depo_id'],'arti_id'=>$o['arti_id'],'prov_id'=>$o['prov_id'],'empr_id'=>$o['empr_id']]).'\'></td>';
                                echo '<td>'.$o['codigo'].'</td>'; 
                                echo '<td>'.$o['descripcion'].'</td>'; 
                                echo '<td class="cant_lote">'.$o['cantidad'].'</td>'; 
                                echo '<td><input class="form-control cantidad" placeholder="Ingrese Cantidad..." type="number" name="cantidad"></td>';
                            echo'</tr>';
                        }
                    ?>

            </tbody>
        </table>
        <button class="btn btn-primary" style="float:right" onclick="guardar_entrega()">Guardar</button>
    </div>
</div>

<script>
    index();
    function index(){
        $('#tit_pedida').html('Cantidad Pedida: ' +$(select_row).find('.pedido').html());
        $('#tit_entregada').html('Cantidad Entregada: ' +$(select_row).find('.entregado').html());
        $('#tit_disponible').html('Cantidad Disponible: ' +$(select_row).find('.disponible').html());
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

        if (!verificar_cantidad()) return;

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