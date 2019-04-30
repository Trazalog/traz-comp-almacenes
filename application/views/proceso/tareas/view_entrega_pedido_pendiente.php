<p>Permitir crear una entrega de materiales atada a la nota de pedido materiales anterior (Pantalla entrega materiales)

Insumo cantidad pedida/entregada/cantidad a entregar

entregada la cantidad de entregas acumuladas hasta el momento => no se puede pasar de la cantidad pedida

validar antes de entregar que la ot esta activa </p>


<button class="btn btn-primary" onclick="new_entrega_material();">
    Agregar Registro de Entrega Material
</button>


<br>
<h3>Pedido Materiales  <small>Detalle Entrega</small></h3>
<table class="table table-striped">
    <thead>
        <th>Código Barra</th>
        <th>Descripción</th>
        <th>Cant. Entregada</th>
        <th>Cant. Pedida</th>
        <th>Cant. Disponible</th>
        <th>Cant. A Entregar</th>
    </thead>
    <tbody>
        <?php 
       
            foreach($list_deta_pema as $o){
                echo '<tr>';
                    echo '<td>'.$o['barcode'].'</td>';
                    echo '<td>'.$o['artDescription'].'</td>';
                    echo '<td>???</td>';
                    echo '<td>'.$o['cantidad'].'</td>';
                    echo '<td>???</td>';
                    echo '<td><a href="#"><i class="fa fa-fw fa-plus"></i></a></td>';
                echo '</tr>';
            }

        ?>
    </tbody>
</table>

<script>

function new_entrega_material(){

    $('#ent_mat .view').empty();
    $('#ent_mat .view').load("index.php/almacen/Ordeninsumo/cargarlista");
    $('#ent_mat').modal('show');
}


</script>


<div class="modal fade" id="ent_mat"  tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg view" role="document">

    </div>
</div>

