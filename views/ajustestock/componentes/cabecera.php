<div class="box box-primary tag-descarga">
    <div class="box-header with-border">
        <h3 class="box-title">Ajuste de Stock</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Establecimiento<strong class="text-danger">*</strong>:</label>
                    <select class="form-control" id="establecimiento"
                        name="establecimiento" onchange="seleccionesta(this)" required>
                        <option value="" disabled selected>-Seleccione opción-</option>
                        <?php
                        foreach ($establecimientos as $i) {
                            echo '<option value="'.$i->nombre.'" class="emp" data-json=\''.json_encode($i).'\'>'.$i->nombre.'</option>';                            
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Depósito<strong class="text-danger">*</strong>:</label>
                    <select class="form-control" id="deposito" name="deposito"
                        required>
                        <option value="" disabled selected>-Seleccione opción-</option>

                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Tipo de ajuste<strong class="text-danger">*</strong>:</label>
                    <select class="form-control" id="tipoajuste" name="tipoajuste"
                        required>
                        <!-- si no me equivoco le falta asignar el atributo data-->
                        <option value="" disabled selected>-Seleccione opción-</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$.ajax({
    type: 'GET',
    dataType: 'json',
    url: 'index.php/<?php echo ALM?>general/Tipoajuste/obtenerAjuste',
    success: function(result) {
        if (!result.status) {
            error("Error","Se produjo un error al obtener los tipos de ajustes");
            return;
        }
        result = result.data;
        // console.log(result);
        var option_ajuste = '<option value="" disabled selected>-Seleccione opción-</option>';
        for (let index = 0; index < result.length; index++) {
            option_ajuste += '<option value="' + result[index].id + '" data="' + result[index].tipo + '">' + result[index].nombre + '</option>';
        }
        $('#tipoajuste').html(option_ajuste);
    },
    error: function() {
        alert('Error');
    }
});

// Al seleccionar establecimiento, busca depositos
function seleccionesta(opcion){
    WaitingOpen('Buscando Depositos...');
    var id_esta = $("#establecimiento").val();
    json = JSON.parse($("#establecimiento>option:selected").attr("data-json"));
    id_esta = json.esta_id;
    $.ajax({
        type: 'POST',
        data: {id_esta},
        url: 'index.php/<?php echo ALM?>Movimientodeposalida/traerDepositos',
        success: function(data) {
            var resp = JSON.parse(data);
            WaitingClose();
            $('#deposito').empty();
            $("#deposito").removeAttr('readonly');
            if (resp == null) {
                    $('#deposito').append('<option value="" disabled selected>-Sin Depósitos para este Establecimiento-</option>');
            } else {
                $('#deposito').append('<option value="" disabled selected>-Seleccione Depósito-</option>');
                for(var i=0; i<resp.length; i++)
                {
                    $('#deposito').append("<option value='" + resp[i].depo_id + "'>" +resp[i].descripcion+"</option");
                }
            }
        },
        error: function(data) {
            alert('Error');
            WaitingClose();
        }
    });
}

$("#boxEntrada :input").prop("disabled", true);
$("#boxSalida :input").prop("disabled", true);
$("#tipoajuste").on('change', function() {
    //console.log($("#tipoajuste>option:selected").val());
    if($("#tipoajuste>option:selected").attr("data") == "ENTRADA"){
        //console.log("entro a entrada");
        $("#boxEntrada :input").prop("disabled", false);
        $("#boxEntrada").removeClass("box-default");
        $("#boxEntrada").addClass("box-primary");
        $('#boxEntrada').css('opacity', '');
        $('#boxSalida').css('opacity', '0.5');

        $("#boxSalida :input").prop("disabled", true);
        $("#boxSalida").removeClass("box-primary");
    }
    if($("#tipoajuste>option:selected").attr("data") == "SALIDA"){
        //console.log("entro a salida");
        //codigo referido a la vista
        $("#boxSalida :input").prop("disabled", false);
        $("#boxSalida").removeClass("box-default");
        $("#boxSalida").addClass("box-primary");

        $("#boxEntrada :input").prop("disabled", true);
        $("#boxEntrada").removeClass("box-primary");
        $('#boxSalida').css('opacity', '');
        $('#boxEntrada').css('opacity', '0.5'); 
        //------------------------------------------------
    }
    if($("#tipoajuste>option:selected").attr("data") == "E/S"){
        //console.log("entro a entrada/salida");
        $("#boxSalida :input").prop("disabled", false);
        $("#boxEntrada :input").prop("disabled", false);
        $("#boxEntrada").removeClass("box-default");
        $("#boxEntrada").addClass("box-primary");
        $("#boxSalida").removeClass("box-default");
        $("#boxSalida").addClass("box-primary");
        $('#boxEntrada').css('opacity', '');
        $('#boxSalida').css('opacity', '');
    }
});

</script>