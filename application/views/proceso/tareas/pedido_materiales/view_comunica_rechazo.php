
<h4>Motivo Rechazo</h4>
<p1 id="motivo"> <?php echo $motivo ?> </p1>


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
    url: '<?php base_url() ?>index.php/general/Proceso/cerrarTarea/'+id,
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