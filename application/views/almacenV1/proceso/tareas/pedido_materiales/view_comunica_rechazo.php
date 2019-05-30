
<h4>Motivo Rechazo</h4>
<p1 id="motivo" class="text-danger"> <?php echo $motivo ?> </p1>


<script>
 function cerrarTarea() {

var id = $('#idTarBonita').val();

var dataForm = new FormData($('#generic_form')[0]);

dataForm.append('motivo', $('#motivo').html());

$.ajax({
    type: 'POST',
    data: dataForm,	
    cache: false,
    contentType: false,
    processData: false,
    url: '<?php base_url() ?>index.php/almacen/Proceso/cerrarTarea/'+id,
    success: function (data) {
       
        linkTo('almacen/Proceso');

    },
    error: function (data) {
       alert("Error");
    }
});

}
</script>