<form autocomplete="off" method="POST" id="formTotal">
    <?php 
        $this->load->view(ALM.'ajustestock/componentes/cabecera');
    ?>

    <div class="row">
        <div class="col-md-6">
            <?php
                $this->load->view(ALM.'ajustestock/componentes/entrada');
            ?>
        </div>
        <div class="col-md-6">
            <?php
            $this->load->view(ALM.'ajustestock/componentes/salida');
            ?>
        </div>
    </div>

    <?php 
        $this->load->view(ALM.'ajustestock/componentes/justificacion');
    ?>
</form>

<script>
obtenerArticulos();

function obtenerArticulos() {
    $.ajax({
        type: 'GET',
        dataType: 'JSON',
        url: 'index.php/<?php echo ALM ?>Articulo/obtener',
        success: function(rsp) {
            if (!rsp.status) {
                error('Error','No hay artículos disponibles!');
                return;
						}
            rsp.data.forEach(function(e, i) {
                $('.articulos').append(
                    `<option data-json='${JSON.stringify(e)}' value="${e.arti_id}" data="${e.unidad_medida}">${e.barcode} | ${e.titulo}</option>`
                );
            });
        },
        error: function(rsp) {
            error('Error!', rsp.msj);
            console.log(rsp.msj);
        }
    });
}

$(".select2").select2();

$("#articuloent").on('change', function() {
    $("#unidadesent").val($("#articuloent>option:selected").attr("data"));
});
$("#articulosal").on('change', function() {
    $("#unidadsal").val($("#articulosal>option:selected").attr("data"));
});

// Trae lotes por articulo de Salida
$("#articulosal").on('change', function() {
    var dato = $("#unidadsal").val();
    var $idarticulo = $("#articulosal option:selected").val();
    var $iddeposito = $("#deposito option:selected").val();    
    if(! _isset($iddeposito)) return;
    wo('Buscando lotes activos...');
    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: '<?php echo ALM ?>Lote/listarPorArticulo?arti_id=' + $idarticulo + '&depo_id=' +
            $iddeposito,
        success: function(result) {
            if (result == null) {
                var option_lote = '<option value="" disabled selected>-Sin lotes-</option>';
                console.log("Sin lotes");
            } else {
                var option_lote = '<option value="" disabled selected>-Seleccione opción-</option>';
                $.each(result, function (i, val) { 
                    option_lote += '<option data-json='+ JSON.stringify(val) +' value="' + val.lote_id + '">' + val.codigo +'</option>';
                });
            }
            $('#lotesal').html(option_lote);
            wc();
        },
        error: function() {
						wc();
            alert('Error');
        }
    });
});

$("#articuloent").on('change', function() {    
    $idarticulo = $("#articuloent>option:selected").val();
    $iddeposito = $("#deposito>option:selected").val();    
    if(! _isset($iddeposito)) return;
    wo('Buscando lotes activos...');
    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: '<?php echo ALM ?>Lote/listarPorArticulo?arti_id=' + $idarticulo + '&depo_id=' +
            $iddeposito,
        success: function(result) {
            if (result == null) {
                var option_lote = '<option value="" disabled selected>Sin lotes</option>';
            } else {
                var option_lote = '<option value="" disabled selected>-Seleccione opción-</option>';
                for (let index = 0; index < result.length; index++) {
                    option_lote += '<option data-json='+ JSON.stringify(result[index]) +' value="' + result[index].lote_id + '">' + result[index]
                        .codigo +
                        '</option>';
                }
            }
            $('#loteent').html(option_lote);
            wc();
        },
        error: function() {
						wc();
            alert('Error');
        }
    });
});

function guardar(){
    var formdata = new FormData($("#formTotal")[0]);
    if (!validarForm()) return;
    var formobj = formToObject(formdata);
    formobj.tipo_ent_sal  = $("#tipoajuste>option:selected").attr("data");
    wo();
    $.ajax({
        type: 'POST',
        dataType: 'json',
    data: {
        data: formobj
    },
    url: '<?php echo ALM ?>Ajustestock/guardarAjuste',
    success: function(rsp) {
        wc();
        // debugger;
        alertify.success(rsp.data);
    },
    error: function(rsp) {
        wc();
        alertify.error(rsp.data);
    },
    complete: function() {}
    });
}

function validarForm() {
    console.log('Validando');
    var ban = ($('#establecimiento').val() != null && $('#establecimiento').val() != '' 
    && $('#deposito').val() != null && $('#deposito').val() != ''
    && $('#tipoajuste').val() != null && $('#tipoajuste').val() != '');
    if (!ban) 
    	Swal.fire(
            'Error...',
            'Debes completar los campos Obligatorios (*)',
            'error'
        );
    return ban;
}
//Habilito el select de tipo ajuste, luego de seleccionar deposito
$("#deposito").on('change', function (e) { 
    e.preventDefault();
    if(_isset($(e.target).val())){
        $("#tipoajuste").attr('disabled',false);
    }
});
$('#loteent').on('change', function (e) {
    jsonLote = getJson($("option:selected", this));
    if(_isset(jsonLote.batch_id)){
        error("Error","El lote seleccionado no es materia prima");
        $('#loteent').val(null).trigger('change');
    }
});

//definicion de variable para controlar tiempo de ingreso de cantidad
var timeoutId;

$('#lotesal').on('change', function (e) {
    jsonLote = getJson($("option:selected", this));
    //debugger;
    if(_isset(jsonLote.batch_id)){
        error("Error","El lote seleccionado no es materia prima");
        $('#lotesal').val(null).trigger('change');
    }
});


//valida que la cantidad ingresada sea menor al stock
$('#cantidadsal').on('input', function (e) {
    clearTimeout(timeoutId); // Limpiar el timeout anterior

    timeoutId = setTimeout(() => {
        // Obtener la cantidad ingresada
        var cantidadIngresada = parseFloat($(this).val());

        // Verificar si la cantidad es un número válido
        if (isNaN(cantidadIngresada)) {
            return;
        }

        // Obtener el stock disponible del lote seleccionado
        var stockDisponible = parseFloat(jsonLote.cantidad);

        // Validar si la cantidad ingresada es mayor al stock disponible
        if (cantidadIngresada > stockDisponible) {
            Swal.fire({
                title: 'Información', 
                html: `La cantidad ingresada es mayor al stock disponible.<br><b>Stock actual:</b> ${stockDisponible}`,
                type: 'info', 
                confirmButtonText: 'Aceptar', 
                confirmButtonColor: '#3085d6',
            });
        }
    }, 300); // Esperar 300 ms después de la última entrada
});

</script>