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
    
    if(!_isset($iddeposito)) return;
    
    wo('Buscando lotes activos...');
    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: '<?php echo ALM ?>Lote/listarPorArticulo?arti_id=' + $idarticulo + '&depo_id=' + $iddeposito,
        success: function(result) {
            if (!result || result.length === 0) {
                $('#lotesal').html('<option value="" disabled selected>Sin lotes</option>').select2();
            } else {
                var option_lote = '<option value="" disabled>-Seleccione opción-</option>';
                
                result.forEach(function(item) {
                    option_lote += `<option value="${item.lote_id}" 
                                    data-json='${JSON.stringify(item)}'
                                    data-foo='<small><cite>Proveedor: <span class="text-blue">${item.proveedor}</span></cite></small>'
                                    data-cantidad="${item.cantidad}">
                                    ${item.codigo}
                                  </option>`;
                });
                
                // Actualizar el select y configurar Select2
                $('#lotesal').html(option_lote).select2({
                    matcher: matchCustom,
                    templateResult: formatCustom
                });
                
                // Seleccionar el primer lote y disparar el evento
                if (result.length > 0) {
                    $('#lotesal').val(result[0].lote_id).trigger('change');
                }
            }
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
                $('#loteent').html(option_lote);
                $('#loteent').select2(); 
            } else {
                // Crear la opción por defecto sin el atributo 'selected'
                var option_lote = '<option value="" disabled>-Seleccione opción-</option>';

                    for (let index = 0; index < result.length; index++) {
                        // Convertir el objeto 'result[index]' a JSON
                        let json = JSON.stringify(result[index]);
                        option_lote += "<option value='" + result[index].lote_id + "' " + 
                                                    "data-json='" + json + "' " +
                                                    "data-foo='<small><cite>Proveedor: <span class=\"text-blue\">" + result[index].proveedor + "</span></cite></small>' " +
                                                    "data-cantidad='" + result[index].cantidad + "'>" + 
                                                    result[index].codigo + 
                                                    "</option>";
                    }
                   // Actualizar el select y configurar Select2
                    $('#loteent').html(option_lote).select2({
                        matcher: matchCustom,
                        templateResult: formatCustom
                    });
                    
                    // Seleccionar el primer lote y disparar el evento
                    if (result.length > 0) {
                        $('#loteent').val(result[0].lote_id).trigger('change');
                    }

            }
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
        limpiaForms();
    },
    error: function(rsp) {
        wc();
        alertify.error(rsp.data);
    },
    complete: function() {}
    });
}


/* limpia los formularios */
function limpiaForms(){

        //vacia datos cabecera
        $("#establecimiento").val('');
        $("#tipoajuste").val('');
        $("#deposito").val('');
        
        /* vacio inputs de entrada */
        $('#articuloent').val(null).trigger('change'); 
        $('#loteent').val(null).trigger('change'); 
        $('#cantidadent').val('');
        $('#unidadesent').val('');
        $('#detalle').html('');

        /* vacio inputs salida */
        $('#articulosal').val(null).trigger('change'); 
        $('#lotesal').val(null).trigger('change'); 
        $('#cantidadsal').val('');
        $('#unidadsal').val('');
        $('#detallesal').html('');

        /* vacio justificacion */
        $('#justificacion').val('');

        /* Oculta los box ENTRADA/SALIDA */
        $("#boxSalida :input").prop("disabled", true);
        $("#boxEntrada :input").prop("disabled", true);
        $("#boxEntrada").removeClass("box-primary");
        $("#boxSalida").removeClass("box-primary");
        $('#boxEntrada').css('opacity', '0.5');
        $('#boxSalida').css('opacity', '0.5');

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

    //validacion para que no permita guardar vacio el campo cantidad salida
    if($('#articulosal').val() != null && $('#articulosal').val() != '')
    {
        ban = $('#cantidadsal').val() != null && $('#cantidadsal').val() != ''

        if(!ban)
            Swal.fire(
                'Error...',
                'Debes seleccionar cantidad',
                'error'
            ); 
    }

    return ban;
}
//Habilito el select de tipo ajuste, luego de seleccionar deposito
$("#deposito").on('change', function (e) { 
    e.preventDefault();
    if(_isset($(e.target).val())){
        $("#tipoajuste").attr('disabled',false);
    }
});
//definicion de variable para controlar tiempo de ingreso de cantidad
var timeoutId;
var jsonLote;

$('#loteent').on('change', function (e) {

        var selectedOption = $(this).find('option:selected');
        var proveedor = selectedOption.data('foo');

        // Actualizar los labels con la información
        $('#detalle').html(proveedor);

        // Obtenemos el objeto JSON almacenado en 'data-json'
        jsonLote = selectedOption.data('json');

        // Verificamos si 'jsonLote' tiene la propiedad 'batch_id' para determinar si es materia prima
        if (jsonLote && jsonLote.batch_id) {
            error("Error", "El lote seleccionado no es materia prima");
        }

});


$('#lotesal').on('change', function (e) {

    var selectedOption = $(this).find('option:selected');
    var proveedor = selectedOption.data('foo');

    // Actualizar los labels con la información
    $('#detallesal').html(proveedor);
    
     jsonLote = selectedOption.data('json');

    // Verificamos si 'jsonLote' tiene la propiedad 'batch_id' para determinar si es materia prima
    if (jsonLote && jsonLote.batch_id) {
        error("Error", "El lote seleccionado no es materia prima");
    } 
});


//valida que la cantidad ingresada sea menor al stock
$('#cantidadsal').on('input', function (e) {
    // Solo permitir números y punto decimal
    this.value = this.value.replace(/[^0-9.]/g, '');
    
    // Evitar múltiples puntos decimales
    if ((this.value.match(/\./g) || []).length > 1) {
        this.value = this.value.replace(/\.+$/, '');
    }

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
            //vacio el input cantidad
            $('#cantidadsal').val('');
        }
    }, 300); // Esperar 300 ms después de la última entrada
});

//funcion para mostrar el proveedor en el select de lotes
function formatCustom(state) {
    // Si la opción no tiene un elemento HTML asociado o no tiene el atributo data-foo,
    // simplemente mostramos el texto de la opción.
    if (!state.element || !$(state.element).attr('data-foo')) {
        return $('<div>' + state.text + '</div>');
    }

    // Si la opción tiene data-foo, construimos el HTML completo con la información adicional.
    return $(
        '<div style="font-weight: bold;"><div>' + state.text + '</div><div class="foo text-black">' +
        $(state.element).attr('data-foo') +
        '</div></div>'
    );
}

</script>