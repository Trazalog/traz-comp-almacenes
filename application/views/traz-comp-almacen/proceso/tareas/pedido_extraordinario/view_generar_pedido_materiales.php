<input id="pema_id" type="number" class="hidden" value="<?php echo $pema_id ?>">
<input id="peex_id" type="number" class="hidden" value="<?php echo $peex_id ?>">

<?php $this->load->view(CMP_ALM.'Proceso/tareas/componentes/pedido_materiales')?>

<script>

function cerrarTarea() {

    var id = $('#idTarBonita').val();
    var pema_id = $('#pema_id').val();
    var peex_id = $('#peex_id').val();

    $.ajax({
        type: 'POST',   
        data: {
            pema_id,
            peex_id
        },
        url: '<?php echo base_url(CMP_ALM) ?>Proceso/cerrarTarea/' + id,
        success: function(data) {

            linkTo('<?php echo CMP_ALM ?>Proceso');

        },
        error: function(data) {
            alert("Error");
        }
    });

}

</script>