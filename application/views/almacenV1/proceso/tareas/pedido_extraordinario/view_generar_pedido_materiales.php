
<button class="btn btn-primary" onclick="$('#pedidos').modal('show');">Generar Pedido</button>
<input id="pema_id" type="number" class="hidden" value="<?php echo $pema_id ?>">
<input id="peex_id" type="number" class="hidden" value="<?php echo $peex_id ?>">

<?php $this->load->view(CMP_ALM.'/proceso/tareas/componentes/pedido_materiales')?>


<script>

 load_view_insumos();
function load_view_insumos() {
    var emp_id = $('#empresa_id').val();
    var iort = $('#ot').val();
    $('#body-pedidos').empty();
    $("#body-pedidos").load("<?php base_url();?>index.php/almacen/Notapedido/editarPedido");
}

function cerrarTarea() {

var id = $('#idTarBonita').val();
var pema_id = $('#pema_id').val();
var peex_id = $('#peex_id').val();

$.ajax({
    type: 'POST',
    data:{pema_id, peex_id},
    url: '<?php base_url()?>index.php/almacen/Proceso/cerrarTarea/'+id,
    success: function (data) {

        linkTo('almacen/Proceso');

    },
    error: function (data) {
       alert("Error");
    }
});

}

</script>

<div class="modal" id="pedidos" tabindex="-1" role="dialog">
    <div class="modal-dialog" id="body-pedidos" role="document">

    </div>
</div>


