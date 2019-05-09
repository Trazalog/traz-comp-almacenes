
<button class="btn btn-primary" onclick="$('#pedidos').modal('show')">Generar Pedido</button>

<?php  $this->load->view('proceso/tareas/componentes/pedido_materiales') ?>


<script> 

 load_view_insumos();
function load_view_insumos() {
    var emp_id = $('#empresa_id').val();
    var iort = $('#ot').val();
    $('#body-pedidos').empty();
    $("#body-pedidos").load("<?php base_url(); ?>index.php/almacen/Notapedido/agregarListInsumos/"+iort);
}

function cerrarTarea() {

var id = $('#idTarBonita').val();

$.ajax({
    type: 'POST',
    cache: false,
    contentType: false,
    processData: false,
    url: '<?php base_url() ?>index.php/general/Proceso/cerrarTarea/'+id,
    success: function (data) {
       
        linkTo('general/Proceso');

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
