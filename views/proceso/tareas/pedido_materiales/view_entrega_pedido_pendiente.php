<style>
.frm-save {
    display: none;
}
</style>
<input type="text" id="pema" value="<?php echo $pema_id ?>" class="hidden">

<h3>Entrega Materiales <small>Información</small></h3>

<?php echo getFormXEmpresa('Entrega Materiales', empresa()) ?>
<!-- <div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="comprobante">N° Comprobante <strong style="color: #dd4b39">*</strong>:</label>
            <input class="form-control required" type="text" placeholder="Comprobante" id="comprobante">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="entrega">Fecha Entrega<strong style="color: #dd4b39">*</strong>:</label>
            <input class="form-control required" type="text" placeholder="Fecha" id="fecha_entrega">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="solicitante">Recibe Nombre y Apellido <strong style="color: #dd4b39">*</strong>:</label>
            <input class="form-control required" type="text" placeholder="Ingresar Solcitante..." id="solicitante">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="dni">D.N.I<strong style="color: #dd4b39">*</strong>:</label>
            <input class="form-control required" type="text" placeholder="Ingresar Solcitante..." id="dni">
        </div>
    </div>
</div> -->

<hr>
<div class="table-responsive">
    <h3>Pedido Materiales <small>Detalles del Pedido</small></h3>
    <table class="table table-striped">
        <thead>
            <th>Código Articulo</th>
            <th>Descripción</th>
            <th>Cant. Pedida</th>
            <th>Cant. Entregada</th>
            <th>Cant. Stock</th>
            <th>Cant. a Entregar</th>
            <th>a Entregar</th>
        </thead>
        <tbody id="entregas">
            <?php

            foreach ($list_deta_pema as $o) {
                echo '<tr data-id="' . $o['arti_id'] . '">';
                echo '<td>' . $o['barcode'] . '</td>';
                echo '<td>' . $o['descripcion'] . '</td>';
                echo '<td class="pedido " style="text-align:center">' . $o['cant_pedida'] . '</td>';
                echo '<td class="entregado" style="text-align:center">' . $o['cant_entregada'] . '</td>';
                echo '<td class="disponible" style="text-align:center">'.($o['cant_disponible']<0?0:$o['cant_disponible']).'</td>';
                echo '<td class="extraer" style="text-align:center">-</td>';
                echo '<td style="text-align:center"><a href="#" class="' . ($o['cant_pedida'] <= $o['cant_entregada'] ||$o['cant_pedida'] == $o['cant_entregada'] || $o['cant_disponible'] == 0 ? 'hidden' : 'pendiente') . '" onclick="ver_info(this)"><i class="fa fa-fw fa-plus"></i></a></td>';
                echo '</tr>';
            }

?>
        </tbody>
    </table>
</div>



<script>
// $("#fecha_entrega").datetimepicker({
//     format: 'YYYY-MM-DD',
//     locale: 'es',
// });

var select_row = null;

function ver_info(e) {

    select_row = $(e).closest('tr');

    var id = $(select_row).data('id');

    $('#modal_view .view').empty();
    $('#modal_view .view').load("<?php echo ALM ?>Articulo/getLotes/" + id);
    $('#modal_view').modal('show');
}

function validar_campos_obligatorios() {
    var ban = true;
    $('.required').each(function() {
        ban = ban && ($(this).val() != '');
    });

    if (!ban) {

        Swal.fire({
            icon: 'error',
            title: 'Error...',
            text: 'Campos Obligatorios Incompletos (*)',
            footer: ''
        });
        return false;
    }

    return true;
}
</script>

<script>
$(function() {
    debugger;
    // tarea en view etrega pedido pendiente
    $(document).on('click', 'input[type="button"]', function(event) {
        let id = this.id;
        console.log("Se presionó el Boton con Id :" + id)
    });
});


function cerrarTarea() {
    debugger;
    //cerrar tarea en view etrega pedido pendiente
    if (!validar_campos_obligatorios()) return;

    var id = $('#taskId').val();

    var pema_id = $('#pema').val();

    var cantidades = [];

    var detalles = [];

    var completa = true;

    var parcial = false;

    $('#entregas tr').each(function() {
        const row = $(this).data('json');
        completa = completa && (parseInt($(this).find('.pedido').html()) == (parseInt($(this).find('.entregado')
            .html()) + parseInt($(this).find('.extraer').html() == '-' ? 0 : $(this).find(
            '.extraer').html())));

        if (row == null) return;
        row.forEach(element => {
            detalles.push(element);
        });

        cantidades.push({
            arti_id: $(this).data('id'),
            resto: $(this).attr('resto')
        });
    });

    if (detalles == null || detalles.length == 0) {
        Swal.fire(
            'Error...',
            'No se Registro Entrega!',
            'error'
        )
        return;
    }

    wbox('#view');
    $.ajax({
        type: 'POST',
        data: {
            completa,
            info_entrega: get_info_entrega(),
            detalles,
            cantidades,
            pema_id
        },
        url: '<?php echo BPM ?>Proceso/cerrarTarea/' + id,
        success: function(data) {
            if (!existFunction('actualizarEntrega')) {

                if ($('#miniView').length == 1) {
                    closeView();
                    Swal.fire(
                        'Guardado!',
                        'El Pedido N°:'+pema_id +'se Finalizo con Entrega Parcial Correctamente',
                        'success'
                    )
                    linkTo('<?php echo BPM ?>Proceso/');
                } else {

                    linkTo('<?php echo BPM ?>Proceso');
                }
            }
        },
        error: function(data) {
            alert("Error");
        },
        complete: function() {
            wbox();
        }
    });
}

function cerrarTareaParcial() {
    debugger;


    //cerrar tarea en view etrega pedido pendiente
    if (!validar_campos_obligatorios()) return;

    var id = $('#taskId').val();

    var pema_id = $('#pema').val();

    var cantidades = [];

    var detalles = [];

    var completa = false;

    var parcial = true;

    $('#entregas tr').each(function() {
        const row = $(this).data('json');
        completa = completa && (parseInt($(this).find('.pedido').html()) == (parseInt($(this).find('.entregado')
            .html()) + parseInt($(this).find('.extraer').html() == '-' ? 0 : $(this).find(
            '.extraer').html())));

        if (row == null) return;
        row.forEach(element => {
            detalles.push(element);
        });

        cantidades.push({
            arti_id: $(this).data('id'),
            resto: $(this).attr('resto')
        });
    });

    if (detalles == null || detalles.length == 0) {
    
         const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: 'Estas Seguro de Finalizar el Pedido?',
            text: "Este paso no se puede revertir!",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'SI, Estoy Seguro!',
            cancelButtonText: 'No, Cancelar!',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                debugger;
        console.log('result trae:' + result.value)
              wbox('#view');
           
              $.ajax({
        type: 'POST',
        data: {
            completa,
            parcial,
            info_entrega: get_info_entrega(),
            detalles,
            cantidades,
            pema_id
        },
        url: '<?php echo BPM ?>Proceso/cerrarTarea/' + id,
        success: function(data) {
               if (!existFunction('actualizarEntrega')) {

                if ($('#miniView').length == 1) {
                    linkTo('<?php echo BPM ?>Proceso');
                    closeView();
                    Swal.fire(
                        'Guardado!',
                        'El Pedido se Finalizo con Entrega Parcial Correctamente',
                        'success'
                    )
                    linkTo('<?php echo BPM ?>Proceso/');

                } else {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Error Inesperado, contacta el Soporte Técnico',
                        footer: ''
                    });
                    linkTo('<?php echo BPM ?>Proceso');
                }
            }
        },
        error: function(data) {
            Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Error De Red',
                        footer: ''
                    });
        },
        complete: function() {
            wbox();
        }
    });
           
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'No se Finalizo Pedido',
                    'warning'
                )
            }
        })
     
    } else{
        debugger;
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: 'Estas Seguro de Finalizar el Pedido?',
            text: "Este paso no se puede revertir!",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'SI, Estoy Seguro!',
            cancelButtonText: 'No, Cancelar!',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                debugger;
        console.log('result trae:' + result.value)
              wbox('#view');
           
              $.ajax({
        type: 'POST',
        data: {
            completa,
            parcial,
            info_entrega: get_info_entrega(),
            detalles,
            cantidades,
            pema_id
        },
        url: '<?php echo BPM ?>Proceso/cerrarTarea/' + id,
        success: function(data) {
               if (!existFunction('actualizarEntrega')) {

                if ($('#miniView').length == 1) {
                    linkTo('<?php echo BPM ?>Proceso');
                    closeView();
                    Swal.fire(
                        'Guardado!',
                        'El Pedido se Finalizo con Entrega Parcial Correctamente',
                        'success'
                    )
                    linkTo('<?php echo BPM ?>Proceso/');

                } else {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Error Inesperado, contacta el Soporte Técnico',
                        footer: ''
                    });
                    linkTo('<?php echo BPM ?>Proceso');
                }
            }
        },
        error: function(data) {
            Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Error De Red',
                        footer: ''
                    });
        },
        complete: function() {
            wbox();
        }
    });
           
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'No se Finalizo Pedido',
                    'warning'
                )
            }
        })
    }

   
}



function get_info_entrega() {
    return JSON.stringify(obj = {
        comprobante: $('#comprobante').val(),
        fecha: $('#fecha_entrega').val(),
        solicitante: $('#solicitante').val(),
        dni: $('#dni').val(),
        pema_id: $('#pema').val()
    });
}
</script>


<div class="modal fade" id="modal_view" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg view" role="document">

    </div>
</div>