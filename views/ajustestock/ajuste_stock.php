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

    <div class="row">
        <div class="col-md-12 text-right">
            <button type="button" class="btn btn-primary" onclick="agregarArticulo()">Agregar <i class="fa fa-plus"></i></button>
        </div>
    </div>
    
    <br>

    <?php 
        $this->load->view(ALM.'ajustestock/componentes/list_articulos');
    ?>

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
    if (!validarForm()) return;

    // Armar cabecera (tipo_ajuste vacío, ahora va en el detalle)
    var cabecera = {
        establecimiento: $('#establecimiento').val(),
        deposito: $('#deposito').val(),
        justificacion: $('#justificacion').val(),
        tipoajuste: '' // vacío, el tipo va en cada detalle
    };

    // Armar detalle desde los hidden inputs de la tabla
    var detalle = [];
    $('#tablaArticulos tbody tr').each(function() {
        var fila = $(this);
        detalle.push({
            articulo_id: fila.find('input[name="articulos_id[]"]').val(),
            lote_id: fila.find('input[name="lotes_id[]"]').val(),
            cantidad: fila.find('input[name="cantidades[]"]').val(),
            tipo_ajuste: fila.find('input[name="tipos_ajuste[]"]').val(),
            tipo_ent_sal: fila.find('input[name="tipos_ent_sal[]"]').val(),
            unidad_medida: fila.find('input[name="unidades_medida[]"]').val()
        });
    });

    wo();
    $.ajax({
        type: 'POST',
        dataType: 'json',
        data: {
            cabecera: cabecera,
            detalle: detalle
        },
        url: '<?php echo ALM ?>Ajustestock/guardarAjuste',
        success: function(rsp) {
            wc();
            alertify.success(rsp.data);
            setTimeout(function() {
                linkTo('<?php echo ALM; ?>Ajustestock');
            }, 1000);
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
    && $('#deposito').val() != null && $('#deposito').val() != '');
    if (!ban) {
    	Swal.fire(
            'Error...',
            'Debes completar los campos Obligatorios (*)',
            'error'
        );
        return false;
    }

    // Validar que haya al menos un artículo en la tabla
    if ($('#tablaArticulos tbody tr').length === 0) {
        Swal.fire(
            'Error...',
            'Debe agregar al menos un artículo a la tabla',
            'error'
        );
        return false;
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

function confirmarCancelar() {
    Swal.fire({
        title: '¿Está seguro?',
        text: '¿Está seguro que desea cancelar el Ajuste de Stock?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cancelar',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.value) {
            linkTo('<?php echo ALM; ?>Ajustestock');
        }
    });
}

function agregarArticulo() {
    var tipoAjuste = $('#tipoajuste option:selected');
    var tipoData = tipoAjuste.attr('data');
    var tipoTexto = tipoAjuste.text();

    if (!tipoAjuste.val()) {
        Swal.fire({title: 'Error', text: 'Debe seleccionar un tipo de ajuste', type: 'error', confirmButtonText: 'Aceptar'});
        return;
    }

    var itemsToAdd = [];

    if (tipoData == 'ENTRADA') {
        var articuloId = $('#articuloent option:selected').val();
        var cantidad = $('#cantidadent').val();
        if (!articuloId) {
            Swal.fire({title: 'Error', text: 'Debe seleccionar un artículo', type: 'error', confirmButtonText: 'Aceptar'});
            return;
        }
        if (!cantidad || parseFloat(cantidad) <= 0) {
            Swal.fire({title: 'Error', text: 'Debe ingresar una cantidad válida', type: 'error', confirmButtonText: 'Aceptar'});
            return;
        }
        itemsToAdd.push({
            articuloId: articuloId,
            articulo: $('#articuloent option:selected').text().trim(),
            loteId: $('#loteent option:selected').val(),
            lote: $('#loteent option:selected').text().trim(),
            unidadMedida: $('#unidadesent').val(),
            cantidad: cantidad,
            tipoEntSal: 'ENTRADA',
            tipoTexto: tipoTexto
        });
    } else if (tipoData == 'SALIDA') {
        var articuloId = $('#articulosal option:selected').val();
        var cantidad = $('#cantidadsal').val();
        if (!articuloId) {
            Swal.fire({title: 'Error', text: 'Debe seleccionar un artículo', type: 'error', confirmButtonText: 'Aceptar'});
            return;
        }
        if (!cantidad || parseFloat(cantidad) <= 0) {
            Swal.fire({title: 'Error', text: 'Debe ingresar una cantidad válida', type: 'error', confirmButtonText: 'Aceptar'});
            return;
        }
        var cantNum = parseFloat(cantidad);
        var cantFinal = (cantNum > 0 ? -cantNum : cantNum).toString();
        itemsToAdd.push({
            articuloId: articuloId,
            articulo: $('#articulosal option:selected').text().trim(),
            loteId: $('#lotesal option:selected').val(),
            lote: $('#lotesal option:selected').text().trim(),
            unidadMedida: $('#unidadsal').val(),
            cantidad: cantFinal,
            tipoEntSal: 'SALIDA',
            tipoTexto: tipoTexto
        });
    } else if (tipoData == 'E/S') {
        var entCompleto = $('#articuloent option:selected').val() && $('#cantidadent').val();
        var salCompleto = $('#articulosal option:selected').val() && $('#cantidadsal').val();

        if (!entCompleto && !salCompleto) {
            Swal.fire({title: 'Error', text: 'Debe completar los datos de al menos un artículo (Entrada o Salida)', type: 'error', confirmButtonText: 'Aceptar'});
            return;
        }

        if (salCompleto) {
            var cantSal = parseFloat($('#cantidadsal').val());
            if (isNaN(cantSal) || cantSal <= 0) {
                Swal.fire({title: 'Error', text: 'Debe ingresar una cantidad válida para Salida', type: 'error', confirmButtonText: 'Aceptar'});
                return;
            }
            itemsToAdd.push({
                articuloId: $('#articulosal option:selected').val(),
                articulo: $('#articulosal option:selected').text().trim(),
                loteId: $('#lotesal option:selected').val(),
                lote: $('#lotesal option:selected').text().trim(),
                unidadMedida: $('#unidadsal').val(),
                cantidad: (-cantSal).toString(),
                tipoEntSal: 'SALIDA',
                tipoTexto: tipoTexto + ' (Salida)'
            });
        }

        if (entCompleto) {
            var cantEnt = parseFloat($('#cantidadent').val());
            if (isNaN(cantEnt) || cantEnt <= 0) {
                Swal.fire({title: 'Error', text: 'Debe ingresar una cantidad válida para Entrada', type: 'error', confirmButtonText: 'Aceptar'});
                return;
            }
            itemsToAdd.push({
                articuloId: $('#articuloent option:selected').val(),
                articulo: $('#articuloent option:selected').text().trim(),
                loteId: $('#loteent option:selected').val(),
                lote: $('#loteent option:selected').text().trim(),
                unidadMedida: $('#unidadesent').val(),
                cantidad: $('#cantidadent').val(),
                tipoEntSal: 'ENTRADA',
                tipoTexto: tipoTexto + ' (Entrada)'
            });
        }
    }

    itemsToAdd.forEach(function(item) {
        var fila = '<tr>' +
            '<td class="text-center"><button type="button" class="btn btn-danger btn-xs" onclick="eliminarArticulo(this)"><i class="fa fa-trash"></i></button></td>' +
            '<td>' + item.tipoTexto + '</td>' +
            '<td>' + item.articulo + '</td>' +
            '<td>' + (item.lote || '-') + '</td>' +
            '<td>' + (item.unidadMedida || '-') + '</td>' +
            '<td>' + item.cantidad + '</td>' +
            '<input type="hidden" name="articulos_id[]" value="' + item.articuloId + '">' +
            '<input type="hidden" name="lotes_id[]" value="' + (item.loteId || '') + '">' +
            '<input type="hidden" name="cantidades[]" value="' + item.cantidad + '">' +
            '<input type="hidden" name="tipos_ajuste[]" value="' + tipoAjuste.val() + '">' +
            '<input type="hidden" name="tipos_ent_sal[]" value="' + item.tipoEntSal + '">' +
            '<input type="hidden" name="unidades_medida[]" value="' + (item.unidadMedida || '') + '">' +
            '</tr>';

        $('#tablaArticulos tbody').append(fila);
    });

    // Deshabilitar Establecimiento y Depósito
    $('#establecimiento').prop('disabled', true);
    $('#deposito').prop('disabled', true);

    // Limpiar campos después de agregar
    if (tipoData == 'ENTRADA' || (tipoData == 'E/S' && $('#articuloent option:selected').val())) {
        $('#articuloent').val('').trigger('change');
        $('#loteent').val('').trigger('change');
        $('#unidadesent').val('');
        $('#cantidadent').val('');
    }
    if (tipoData == 'SALIDA' || (tipoData == 'E/S' && $('#articulosal option:selected').val())) {
        $('#articulosal').val('').trigger('change');
        $('#lotesal').val('').trigger('change');
        $('#unidadsal').val('');
        $('#cantidadsal').val('');
    }
}

function eliminarArticulo(btn) {
    $(btn).closest('tr').remove();

    // Si la tabla queda vacía, habilitar de nuevo Establecimiento y Depósito
    if ($('#tablaArticulos tbody tr').length === 0) {
        $('#establecimiento').prop('disabled', false);
        $('#deposito').prop('disabled', false);
    }
}
</script>