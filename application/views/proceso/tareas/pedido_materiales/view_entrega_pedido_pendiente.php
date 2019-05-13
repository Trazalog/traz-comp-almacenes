<input type="text" id="pema" value="<?php echo $pema_id ?>" class="hidden">

<input type="text" id="enma_id" value="" class="hidden">

<h3>Entrega Materiales <small>Información</small></h3>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="comprobante">Comprobante <strong style="color: #dd4b39">*</strong>:</label>
            <input class="form-control required" type="text" placeholder="Comprobante" id="comprobante">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="solicitante">Solicitante<strong style="color: #dd4b39">*</strong>:</label>
            <input class="form-control required" type="text" placeholder="Ingresar Solcitante..." id="solicitante">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="destino">Destino<strong style="color: #dd4b39">*</strong>:</label>
            <input class="form-control required" type="text" placeholder="Destino..." id="destino">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="entrega">Fecha Entrega<strong style="color: #dd4b39">*</strong>:</label>
            <input class="form-control required" type="text" placeholder="Fecha" id="fecha_entrega">
        </div>
    </div>
</div>

<hr>
<h3>Pedido Materiales <small>Detalles del Pedido</small></h3>
<table class="table table-striped">
    <thead>
        <th>Código Articulo</th>
        <th>Descripción</th>
        <th>Cant. Pedida</th>
        <th>Cant. Entregada</th>
        <th>Cant. Disponible</th>
        <th>Cant. a Entregar</th>
    </thead>
    <tbody id="entregas">
        <?php

            foreach ($list_deta_pema as $o) {
                echo '<tr data-id="' . $o['arti_id'] . '">';
                echo '<td>' . $o['barcode'] . '</td>';
                echo '<td>' . $o['descripcion'] . '</td>';
                echo '<td class="pedido " style="text-align:center">' . $o['cant_pedida'] . '</td>';
                echo '<td class="entregado" style="text-align:center">' . $o['cant_entregada'] . '</td>';
                echo '<td class="disponible" style="text-align:center">'.($o['cant_disponible']<0?0:$o['cant_disponible']).'</td>';
                echo '<td style="text-align:center"><a href="#" class="' . ($o['cant_pedida'] <= $o['cant_entregada'] || $o['cant_disponible'] == 0 ? 'hidden' : 'pendiente') . '" onclick="ver_info(this)"><i class="fa fa-fw fa-plus"></i></a></td>';
                echo '</tr>';
            }

?>
    </tbody>
</table>




<script>
    $("#fecha_entrega").datetimepicker({
        format: 'YYYY-MM-DD',
        locale: 'es',
    });

    var select_row = null;
    function ver_info(e) {

        if (!validar_campos_obligatorios()) return;
        select_row = $(e).closest('tr');

        var id = $(select_row).data('id');

        $('#modal_view .view').empty();
        $('#modal_view .view').load("almacen/Articulo/getLotes/" + id);
        $('#modal_view').modal('show');
    }

    function validar_campos_obligatorios() {
        var ban = true;
        $('.required').each(function () {
            ban = ban && ($(this).val() != '');
        });

        if (!ban) { alert('Campos Obligatorios Incompletos (*)'); return false; }

        return true;
    }


</script>

<script>
    function cerrarTarea() {

        var id = $('#idTarBonita').val();

        $.ajax({
            type: 'POST',
            data: {completa:$('.pendiente').length == 0},
            url: '<?php base_url() ?>index.php/general/Proceso/cerrarTarea/' + id,
            success: function (data) {
                //WaitingClose();
                linkTo('general/Proceso');

            },
            error: function (data) {
                alert("Error");
            }
        });

    }
</script>


<div class="modal fade" id="modal_view" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg view" role="document">

    </div>
</div>