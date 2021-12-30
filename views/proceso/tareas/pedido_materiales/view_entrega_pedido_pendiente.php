<style>
.frm-save {
    display: none;
}
</style>
<?php $hoy = date("Y-m-d H:i:s"); ?>
<input type="text" id="pema" value="<?php echo $pema_id ?>" fecha="<?php echo $hoy?>" class="hidden">

<h3>Entrega Materiales <small>Información</small></h3>
<br><br>

 <div class="row">
    <div class="col-sm-3">
        <div class="form-group">
      <button type="button" class="btn btn-primary ml-2 mb-2 mb-2 mt-3" id="cerrarTareaSinEntregra" style="display:block;" onclick="cerrarTareaSinEntrega()">Finalizar Pedido Sin Entrega</button>

        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
    
      <button type="button" class="btn btn-success ml-2 mb-2 mb-2 mt-3" id="realizarEntrega" style="display:block;" onclick="realizarEntrega()">Realizar Entrega</button>
        </div>
    </div>
</div> 

<div id="form-dinamico" class="frm-new" data-form="14" style="display:none;"></div>

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
                echo '<td style="text-align:center"><a style="display:none" href="#" class="' . ($o['cant_pedida'] <= $o['cant_entregada'] ||$o['cant_pedida'] == $o['cant_entregada'] || $o['cant_disponible'] == 0 ? 'hidden' : 'pendiente') . ' btnEntrega" onclick="ver_info(this)"><i class="fa fa-fw fa-plus"></i></a></td>';
                echo '</tr>';
            }

?>
        </tbody>
    </table>
</div>



<script>

$('.btnEntrega').attr({'style': 'display:none'});



function  realizarEntrega(){
    wo();
    $('#cerrarTareaSinEntregra').attr({'style': 'display:none'});
    $('#form-dinamico').attr({'style': 'display:block'});
   
    detectarForm();
    initForm(); 

    setTimeout(function(){ 
        $('.btnEntrega').attr({'style': 'display:block'});
        $("#btncerrarTarea").removeAttr("style");
        wc();
    }, 4000);
}



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
            type: 'error',
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
                        'El Pedido N°:'+pema_id +'se Finalizo Correctamente',
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
            type: 'info',
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
                        'El Pedido N°:'+pema_id +'se Finalizo con Entrega Parcial Correctamente',
                        'success'
                    )
                    linkTo('<?php echo BPM ?>Proceso/');

                } else {

                    Swal.fire({
                        type: 'error',
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
                        type: 'error',
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
            type: 'info',
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
                        type: 'error',
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
                        type: 'error',
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

function cerrarTareaSinEntrega() {
    debugger;


    //cerrar tarea en view etrega pedido pendiente
   // if (!validar_campos_obligatorios()) return;

    var id = $('#taskId').val();

    var pema_id = $('#pema').val();

    var cantidades = [];

    var detalles = [];

    var completa = false;

    var parcial = false;
    
    var sinEntrega = true;

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
            type: 'info',
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
            sinEntrega,
            info_entrega: get_info_Sinentrega(),
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
                        'El Pedido se Finalizo Sin Entrega Correctamente',
                        'success'
                    )
                    linkTo('<?php echo BPM ?>Proceso/');

                } else {

                    Swal.fire({
                        type: 'error',
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
                        type: 'error',
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
            type: 'info',
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
                        type: 'error',
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
                        type: 'error',
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

function get_info_Sinentrega() {
    debugger;
    return JSON.stringify(obj = {
        comprobante: 'Sin Comprobante',
        fecha: $('#pema').attr('fecha'),
        solicitante: '-',
        dni: '-',
        pema_id: $('#pema').val()
    });
}
</script>


<div class="modal fade" id="modal_view" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg view" role="document">

    </div>
</div>