<input class="hidden" type="text" id="origen-pema" data-json='<?php echo json_encode($origen)?>'> 
<div class="row">
    <div class="col-md-7">
        <div class="form-group">
            <label>Artículos:</label>
            <?php
                 echo selectBusquedaAvanzada('articulos', 'articulo', $articulos, 'arti_id', 'barcode',  array('descripcion','Stock:' => 'stock', 'Unidad Medida:'=>'unidad_medida'), true);
            ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Cantidad:</label>
            <input id="cantidad" type="number" class="form-control">
        </div>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary" style="margin-top:23px" onclick="agregar()"><i
                class="fa fa-plus mr-2"></i>Agregar</button>
    </div>
    <div class="col-md-12">
        <table id="tbl-npema" class="table table-striped">
            <thead>
                <th>Artículos</th>
                <th width="20%">Cantidad</th>
                <th width="5%"></th>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

<script>
function agregar() {
    var data = getJson($('#articulos'));
    var cantidad = $('#cantidad').val();
    $('#tbl-npema > tbody').append(
        `<tr data-arti="${data.arti_id}" data-cantidad="${cantidad}">
            <td><b>${data.barcode}</b><br><small>${data.descripcion}</small></td>
            <td class='text-center'>${cantidad}</td>
            <td><button class="btn btn-link" onclick="conf(eliminar, this)"><i class="fa fa-trash text-danger"></i></button></td>
        </tr>`
    )
    $('#cantidad').val('');
}
var eliminar = function(e) {
    $(e).closest('tr').remove();
}

function guardarPedido2() {
    var dataOrigen = getJson('#origen-pema');
    var data = {};
    $('#tbl-npema > tbody > tr').each(function(i) {
        data[i] = {
            arti_id: this.dataset.arti,
            cantidad: this.dataset.cantidad
        };
    });
    wo();
    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: `<?php echo base_url(ALM.'notapedido/nuevo/')?>${dataOrigen.origen}/${dataOrigen.orig_id}`,
        data,
        success: function(res) {
            hecho();
            reload('#pnl-pema', dataOrigen.orig_id);
            $('#tbl-npema > tbody').empty();
            $('#pema-list').click();
        },
        error: function(res) {
            error();
        },
        complete: function() {
            wc();
        }
    });
}
</script>