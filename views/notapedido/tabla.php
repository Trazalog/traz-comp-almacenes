<table id="tbl-pedidos" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>Acciones</th>
            <th>Pedido</th>
            <th class="<?php echo(!viewOT?"hidden":null)?>">Ord.Trabajo</th>
            <th class="text-center">Fecha</th>
            <th>Detalle</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if($list) {
                foreach($list as $z)
                {
                    $z = toArray($z);
                    $id = $z['id_notaPedido'];
                    echo '<tr id="'.$id.'" class="'.$id.'" data-json=\''.json_encode($z).'\'>';
                    echo '<td class="text-center">';
                    echo '<i onclick="ver(this)" class="fa fa-fw fa-search text-light-blue buscar" style="cursor: pointer;margin:5px;" title="Detalle Pedido Materiales"></i>';
                    echo '</td>';           
                    echo '<td class="text-center">'.bolita($z['id_notaPedido'],'blue').'</td>';
                    echo '<td class="text-center '.(!viewOT?"hidden":null).'">'.bolita($z['id_ordTrabajo'],'yellow','Orden de Trabajo N°'.$z['id_ordTrabajo']).'</td>';
                    
                    echo '<td class="text-center">'.date('d-m-Y',strtotime($z['fecha'])).'</td>';
                    echo '<td>'.(viewOT?$z['descripcion']:$z['justificacion']).'</td>';
                    echo '<td class="text-center">'.estadoPedido($z['estado']).'</td>';
                    echo '</tr>';
                }
            }
            ?>
    </tbody>
</table>

<script>
function ver(e) {
    var tr = $(e).closest('tr')
    var id_nota = $(tr).attr('id');
    var json = JSON.parse(JSON.stringify($(tr).data('json')));
    rellenarCabecera(json);
    getEntregasPedidoOffline(id_nota);
    if (id_nota == null) {
        alert('PEMA_ID: ' + id_nota);
        return;
    }

    $.ajax({
        type: 'GET',
        url: 'index.php/<?php echo ALM ?>Notapedido/getNotaPedidoId?id_nota=' + id_nota,
        success: function(data) {

            tablaDetalle.clear().draw();
            for (var i = 0; i < data.length; i++) {
                var tr = "<tr style='color:'>" +
                    "<td>" + data[i]['barcode'] + "</td>" +
                    "<td>" + data[i]['artDescription'] + "</td>" +
                    "<td class='text-center' width='15%'><b>" + data[i]['cantidad'] + "</b></td>" +
                    "<td class='text-center' width='15%'><b>" + data[i]['entregado'] + "</b></td>" +
                    "</tr>";
                tablaDetalle.row.add($(tr)).draw();
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

function rellenarCabecera(json) {
    $('#detalle_pedido .pedido').val(json.id_notaPedido);
    $('#detalle_pedido .descrip').val(json.justificacion);
    $('#detalle_pedido .fecha').val(json.fecha);
    $('#detalle_pedido .estado').val(json.estado);
    $('#detalle_pedido .orden').val(json.id_ordTrabajo);
}

function getEntregasPedidoOffline(pema) {
    $.ajax({
        type: 'GET',
        url: 'index.php/<?php echo ALM ?>new/Entrega_Material/getEntregasPedidoOffline?pema=' + pema,
        success: function(data) {

            document.getElementById('tab_2').innerHTML = data;
            DataTable('#entregas');
        }
    });
}
</script>