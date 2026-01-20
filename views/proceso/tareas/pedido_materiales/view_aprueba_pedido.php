<hr>
<input type="number" class="hidden" value="<?php echo $pema_id ?>" id="pemaId">
<h3>Pedido Materiales <small>Detalle</small></h3>
<div id="nota_pedido">
    <div class="row">
        <div class="col-xs-4 col-sm-4 col-md-4">
            <div class="form-group">
                <label for="establecimientos">Establecimientos(<strong style="color: #dd4b39">*</strong>):</label>
                <select onchange="seleccionesta(this)" id="establecimientos" class="form-control required" >
                    <option value="false"> - Seleccionar - </option>
                  
                </select>
            </div>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4">
            <div class="form-group">
                <label for="depositos">Depósito(<strong style="color: #dd4b39">*</strong>):</label>
                <select id="depositos" name="depositos" class="form-control  required" readonly>
                    <option value="" disabled selected> - Seleccionar - </option>
                </select>
            </div>
        </div>
    </div>
    <table id="tabladetalle" class="table table-striped table-hover">
        <thead>
            <tr>
                <!-- <th>Código</th> -->
                <th>Descripcion</th>
                <th class="text-center">Cantidad</th>
                <th class="text-center">Fecha Nota</th>
            </tr>
        </thead>
        <tbody>
            <!--Detalle Pedido Materiales -->
        </tbody>
    </table>
</div>
<hr>

<form id="generic_form">
    <div class="form-group">
        <center>
            <h4 class="text-danger"> ¿Se Aprueba o Rechaza el Pedido de Materiales? </h4>
            <label class="radio-inline">
                <input type="radio" name="result" value="true"
                    onclick="$('#motivo').hide();$('#hecho').prop('disabled',false);"> Aprobar
            </label>
            <label class="radio-inline">
                <input id="rechazo" type="radio" name="result" value="false"
                    onclick="$('#motivo').show();$('#hecho').prop('disabled',false);"> Rechazar
            </label>
        </center>
    </div>

    <div id="motivo" class="form-group motivo">
        <textarea class="form-control" name="motivo_rechazo" placeholder="Motivo de Rechazo..."></textarea>
    </div>
</form>

<script>
$('#motivo').hide();
$('#hecho').prop('disabled', true);



//cargarPedidos();
getEstablecimientos();

function validar_campos_obligatorios() {
    var ban = true;
    $('.required').each(function() {
        ban = ban && ($(this).val() != '');
    });

    if (!ban) {
 
        Swal.fire({
            type: 'error',
            title: 'Error...',
            text: 'Campos Obligatorios Incompletos (*)',
            footer: ''
        });
        return false;
    }

    return true;
}

function cargarPedidos() {
    var id = $('#pemaId').val();
    $.ajax({
        type: 'POST',
        url: 'index.php/<?php echo ALM ?>Notapedido/getNotaPedidoId?id_nota=' + id,
        success: function(data) {

            var deposito = (data[0].depo_id) ? data[0].depo_id : '' ;
            var establecimiento = (data[0].esta_id) ? data[0].esta_id : '' ; 

               // Guardar el depósito original
               depositoOriginal = deposito.toString();
            
            if (establecimiento != '' && deposito != '') {
                // Agregar opción de establecimiento si no existe
                if ($('#establecimientos option[value="' + establecimiento + '"]').length === 0) {
                    $('#establecimientos').append("<option value='" + data[0].esta_id + "'>" + data[0].estaDescripcion + "</option>");
                }
                // Eliminar la opción '-seleccionar-' si existe
                $('#establecimientos option[value=""]').remove();
                // Establecer el valor del establecimiento
                $('#establecimientos').val(establecimiento);

                //selecciona deposito por defecto
               $('#depositos').val(deposito); 
               $('#depositos').append("<option value='" + data[0].depo_id + "'>" +data[0].depoDescripcion+"</option");


            }
            $('tr.celdas').remove();
            for (var i = 0; i < data.length; i++) {
                var tr = "<tr class='celdas'>" +
                    "<td>" + data[i]['artDescription'] + "</td>" +
                    "<td class='text-center'>" + data[i]['cantidad'] + "</td>" +
                    "<td class='text-center'>" + data[i]['fecha'] + "</td>" +
                    "</tr>";
                $('table#tabladetalle tbody').append(tr);
            }
            $('.table').DataTable();
        },
        error: function(result) {

            console.log(result);
        },
        dataType: 'json'
    });
}

async function cerrarTarea() {
    if ($('#rechazo').prop('checked') && $('#motivo .form-control').val() == '') {
        alert('Completar Motivo de Rechazo');
        return;
    }

    if (!validar_campos_obligatorios()) return;

    var id = $('#taskId').val();
    var pema_id = $('#pemaId').val();
    var dataForm = new FormData($('#generic_form')[0]);
    dataForm.append('pema_id', $('#pemaId').val());

    var depositoSeleccionado = $('#depositos').val();

    $.ajax({
        type: 'POST',
        data: dataForm,
        cache: false,
        contentType: false,
        processData: false,
        url: '<?php base_url() ?>index.php/<?php echo BPM ?>Proceso/cerrarTarea/' + id,
        success: function(data) {
        var resp = JSON.parse(data);
        if (resp.status == true) {
            selectedValue = $('input[name="result"]:checked').val();
            if (selectedValue == 'true') {
                //evalua si cambio el deposito original
                if (depositoSeleccionado !== depositoOriginal) {
                    guardaDeposito().then(() => {
                        confRefresh(linkTo, '<?php echo BPM ?>Proceso/', 'Se aprobó el pedido N°: ' + pema_id + ' correctamente!');
                    }).catch(error => {
                        console.error(error);
                    });
                } else {
                    confRefresh(linkTo, '<?php echo BPM ?>Proceso/', 'Se aprobó el pedido N°: ' + pema_id + ' correctamente!');
                }
            } else {
                confRefresh(linkTo, '<?php echo BPM ?>Proceso/', 'Se canceló pedido N°: ' + pema_id + ' Correctamente!');
            }
        } else {
            error("Error", "Se produjo un error al cerrar la tarea");
        }
    }
    });
}

function getEstablecimientos() {
    const selectEstablecimiento = document.getElementById('establecimientos');
    $.ajax({
        type: 'POST',
        url: 'index.php/<?php echo ALM ?>Deposito/obtenerEstablecimientos',
        success: function(data) {
            // Parseamos la respuesta
            var resp = JSON.parse(data);

            // Limpiamos las opciones existentes
            selectEstablecimiento.innerHTML = '<option value="false"> - Seleccionar - </option>';

           
            resp.forEach(option => {
                const opt = document.createElement('option');
                opt.value = option.esta_id;
                opt.textContent = option.nombre; 
                selectEstablecimiento.appendChild(opt);
            });
            cargarPedidos();
        },
        error: function(data) {
            console.error("Error al obtener los establecimientos:", data);
            alert("Se produjo un error al obtener los establecimientos.");
        }
    });
}

//trae depositos asociados al establecimiento
function seleccionesta(opcion){
    var id_esta = $("#establecimientos").val();
    console.table(id_esta);
    wo();
    $.ajax({
            type: 'POST',
            data: {id_esta},
            url: 'index.php/<?php echo ALM?>Deposito/getDepositoxEncargado',
            success: function(data) {
                var resp = JSON.parse(data);
                if(resp.length === 0){
                    $('#depositos').empty();
                    $('#depositos').append("<option value=''>" + '-Sin Depósitos asignados-' +"</option");
                }
               else{
                console.table(resp);
                    console.table(resp[0].depo_id);
              
                    $('#depositos').empty();
                    for(var i=0; i<resp.length; i++)
                    {
                        $('#depositos').append("<option value='" + resp[i].depo_id + "'>" +resp[i].descripcion+"</option");
                    }
                    $("#depositos").removeAttr('readonly');
               }
               wc();
            },
            error: function(data) {
                alert('Error');
            }
        });
} 

//guarda depositos asociados al pedido
function guardaDeposito() {
    return new Promise((resolve, reject) => {
        var pema_id = $('#pemaId').val();
        var depo_id = $("#depositos").val();

        $.ajax({
            type: 'POST',
            url: 'index.php/<?php echo ALM ?>Notapedido/editaDeposito', 
            data: {
                'pema_id': pema_id,
                'depo_id': depo_id
            },
            cache: false,
            success: function(response) {
                if (response) {
                    console.log("Depósito guardado exitosamente.");
                    resolve(); // Resolvemos la promesa
                } else {
                    console.log("No se pudo guardar el depósito.");
                    reject("No se pudo guardar el depósito."); // Rechazamos la promesa
                }
            },
            error: function() {
                console.log("Error al guardar el depósito.");
                reject("Error al guardar el depósito."); // Rechazamos la promesa
            }
        });
    });
}

</script>