
<input class="hidden" type="number" id="peex_id" value="<?php echo $peex_id ?>">
<form id="generic_form">
    <div class="form-group">
        <center>
            <h4> ¿Se Aprueba o Rechaza el Pedido de Materiales? </h4>
            <label class="radio-inline">
                <input type="radio" name="result" value="true" onclick="$('#motivo').hide()"> Aprobar
            </label>
            <label class="radio-inline">
                <input type="radio" name="result" value="false" onclick="$('#motivo').show()"> Rechazar
            </label>
        </center>

    </div>

    <div id="motivo" class="form-group motivo">
        <textarea class="form-control" name="motivo_rechazo" placeholder="Motivo de Rechazo..."></textarea>
    </div>
</form>

<script>
    $('#motivo').hide();

    function cerrarTarea() {

        var id = $('#idTarBonita').val();

        var dataForm = new FormData($('#generic_form')[0]);

        dataForm.append('peex_id', $('#peex_id').val());
        
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