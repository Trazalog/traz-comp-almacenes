<style>
.frm-save {
    display: none;
}
</style>


<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Entrega Materiales Directa</h3>
    </div>
    <div class="box-body">
    <input id="pema_id" type="number" class="hidden" value="">
    <input id="ortr_id" type="number" class="hidden" value="">


    <!-- Formulario Dinámico de Entrega (Visible Directamente) -->
    <div id="form-dinamico" class="frm-new" data-form="<?php echo $form_id ?>"></div>


    <div class="row  col-md-12">

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <label>Justificación<strong style="color: #dd4b39">*</strong>:</label>
                    <textarea id="just" type="text" class="form-control"
                        placeholder="Ingrese Justificación..."></textarea>
            </div>
        </div>
    </div>

    <div class="row  col-md-12">
        <div class="col-xs-4 col-sm-4 col-md-4">
            <div class="form-group">
                <label for="establecimiento">Establecimientos:</label>
                <select id="establecimiento" class="form-control">
                    <option value="false"> - Seleccionar - </option>
                        <?php 
                            $first = true;
                            foreach ($establecimientos as $o) {
                                $selected = $first ? 'selected' : '';
                                echo "<option value='$o->esta_id' $selected>$o->nombre</option>";
                                $first = false;
                            }
                        ?>
                </select>
            </div>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4">
            <div class="form-group">
                <label for="deposito">Depósito:</label>
                <select id="deposito" name="deposito" class="form-control" readonly>
                    <option value="" disabled selected> - Seleccionar - </option>
                </select>
            </div>
        </div>

    </div>
        
    <div class="row  col-md-12">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Seleccionar Artículo<strong style="color: #dd4b39">*</strong>:</label>
                    <?php $this->load->view(ALM.'articulo/componente'); ?>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                <label>Cantidad<strong style="color: #dd4b39">*</strong>:</label>
                    <input id="add_cantidad" type="number" min="0" step="1" class="form-control" placeholder="Cantidad">
                </div>
            </div>

            <div class="col-xs-3 col-sm-3 col-md-3" style="margin-top:25px">
                <button class="btn btn-primary" onclick="agregarArticulo()"><i class="fa fa-check"></i>Agregar</button>
            </div>

            <div class="table-responsive col-md-12">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <th>Acciones</th>
                        <th>Articulo</th>
                        <th>Descripción</th>
                        <th style="text-align:center">Cantidad</th>
                        <th style="text-align:center">Lote</th>
                    </thead>
                    <tbody id="entregas">
                        
                    </tbody>
                </table>
            </div>
    </div>

    <div class="box-footer">
        <button class="btn btn-primary pull-right" onclick="guardarTodo()">Entregar</button>
    </div>


</div>

<script>
var esta_anterior = '';
var depo_anterior = '';

function tieneLotesSeleccionados() {
    var hay = false;
    $('#entregas tr').each(function() {
        if ($(this).attr('data-json')) {
            hay = true;
            return false;
        }
    });
    return hay;
}

//limpia las cantidades de la tabla de lotes
function borrarCantidades() {
    $('#entregas tr').each(function() {
        $(this).removeAttr('data-json');
        $(this).find('.extraer').html('-');
        $(this).find('.entregado').html('0');
    });
}

$(document).ready(function() {
    wo();
    detectarForm();
    initForm();

    esta_anterior = $('#establecimiento').val();
    depo_anterior = $('#deposito').val();

    // Ejecutar seleccionesta cuando se carga la página
    if (esta_anterior !== 'false' && esta_anterior !== '') {
        seleccionesta();
    }


    // si cambia establecimiento o deposito tengo que vaciar la lista de lotes, y volver a cargar
    $('#establecimiento').on('change', function() {
        var $combo = $(this);
        var valor_nuevo = $combo.val();

        if (tieneLotesSeleccionados()) {
            Swal.fire({
                title: '¿Desea cambiar el establecimiento?',
                text: "Se borrarán las cantidades de lotes seleccionadas.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cambiar',
                cancelButtonText: 'Cancelar'
            }).then(function(result) {
                if (result.isConfirmed || result.value) {
                    borrarCantidades();
                    esta_anterior = valor_nuevo;
                    seleccionesta();
                } else {
                    $combo.val(esta_anterior);
                }
            });
        } else {
            esta_anterior = valor_nuevo;
            seleccionesta();
        }
    });

    $('#deposito').on('change', function() {
        var $combo = $(this);
        var valor_nuevo = $combo.val();

        if (tieneLotesSeleccionados()) {
            Swal.fire({
                title: '¿Desea cambiar el depósito?',
                text: "Se borrarán las cantidades de lotes seleccionadas.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cambiar',
                cancelButtonText: 'Cancelar'
            }).then(function(result) {
                if (result.isConfirmed || result.value) {
                    borrarCantidades();
                    depo_anterior = valor_nuevo;
                } else {
                    $combo.val(depo_anterior);
                }
            });
        } else {
            depo_anterior = valor_nuevo;
        }
    });

    wc();
}); 

function agregarArticulo() {
    var cant = $('#add_cantidad').val();
    if (!selectItem || cant <= 0) {
        Swal.fire('Error...', 'Seleccione un artículo y cantidad válida', 'error');
        return;
    }

    var tr = "<tr data-id='" + selectItem.arti_id + "' data-barcode='" + selectItem.barcode + "' data-desc='" + selectItem.descripcion + "'>" +
        "<td><i class='fa fa-fw fa-trash text-red' style='cursor: pointer;' title='Eliminar' onclick='$(this).closest(\"tr\").remove();'></i></td>" +
        "<td>" + selectItem.barcode + "</td>" +
        "<td>" + selectItem.descripcion + "</td>" +
        "<td class='pedido text-center'>" + cant + "</td>" +
        "<td class='entregado hidden'>0</td>" +
        "<td class='disponible hidden'>" + (selectItem.stock ? selectItem.stock : 0) + "</td>" +
        "<td class='extraer hidden'>-</td>" +
        "<td style='text-align:center'><a href='#' class='btnEntrega' onclick='ver_info(this)'><i class='fa fa-fw fa-plus'></i></a></td>" +
        "</tr>";

    $('#entregas').append(tr);
    
    // Limpiar campos
    $('#inputarti').val('');
    $('#add_cantidad').val('');
    selectItem = null;
}


/* completa modal de lotes */
var select_row = null;
function ver_info(e) {
    wo();
    select_row = $(e).closest('tr');
    var id = $(select_row).data('id');
    var esta_id = $('#establecimiento').val();
    var depo_id = $('#deposito').val();
    var json_guardado = $(select_row).attr('data-json'); // Recuperamos lo que ya se ingresó

    $('#modal_view .view').empty();
    $('#modal_view .view').load("<?php echo base_url(ALM) ?>Articulo/getLotes/" + id + "?directo=true&esta_id=" + esta_id + "&depo_id=" + depo_id, function() {
        $('#tit_entregada').closest('.col-xs-12').hide();
        
        // Si ya había lotes cargados para esta fila, los reponemos en los inputs
        if(json_guardado) {
            var lotes_previos = JSON.parse(json_guardado);
            $('#lotes_depositos tr').each(function() {
                var fila_modal = $(this);
                var datos_fila_modal = JSON.parse(fila_modal.find('.lote_depo').val());
                
                // Buscamos si este lote/deposito estaba en lo guardado anteriormente
                var coincidencia = lotes_previos.find(function(l) {
                    return l.lote_id == datos_fila_modal.lote_id && l.depo_id == datos_fila_modal.depo_id;
                });
                
                if(coincidencia) {
                    fila_modal.find('.cantidad').val(coincidencia.cantidad);
                }
            });
            // Actualizamos los totales del modal
            if(typeof verificar_cantidad === 'function') verificar_cantidad();
        }

        $('#modal_view').modal('show');
        wc();
    });
}

function validar_campos_obligatorios() {
    var ban = true;
    $('.required').each(function() {
        if(!$(this).val()) ban = false;
    });
    if (!ban) {
        Swal.fire('Error...', 'Campos Obligatorios Incompletos (*)', 'error');
        return false;
    }
    if($('#entregas tr').length == 0) {
        Swal.fire('Error...', 'Debe añadir al menos un artículo', 'error');
        return false;
    }
    return true;
}


// Crea el pedido, el formulario dinamico y realiza la entrega de materiales
async function guardarTodo() {
    if (!validar_campos_obligatorios()) return;

    var cantidades = [];
    var detalles_entrega = [];
    var articulos_pedido = [];
    var hay_lotes = true;

    $('#entregas tr').each(function() {
        var row_id = $(this).data('id');
        var cant = $(this).find('.pedido').html();
        var extraer = $(this).find('.extraer').html();
        var json_lotes = $(this).attr('data-json');

        if (!json_lotes || extraer == '-') {
            Swal.fire('Error...', 'Debe seleccionar lotes para el artículo: ' + $(this).data('barcode'), 'error');
            hay_lotes = false;
            return false;
        }

        if (parseInt(cant) != parseInt(extraer)) {
            Swal.fire('Error...', 'La cantidad seleccionada de lotes (' + extraer + ') debe ser igual a la cantidad pedida (' + cant + ') para el artículo: ' + $(this).data('barcode'), 'error');
            hay_lotes = false;
            return false;
        }

        articulos_pedido.push({
            arti_id: row_id,
            cantidadPedida: cant,
            deposito: $('#deposito').val()
        });

        var lotes = JSON.parse(json_lotes);
        lotes.forEach(function(l) {
            detalles_entrega.push(l);
        });

        cantidades.push({
            arti_id: row_id,
            resto: 0
        });
    });

    if (!hay_lotes) return;

    wo();

    // 1. Guardar Formulario Dinámico
    var info_id = null;
    var idFormDinamico = "#" + $('.frm-new').find('form').attr('id');
    if (idFormDinamico != "#undefined") {
        if (!frm_validar(idFormDinamico)) {
            wc();
            alertify.error("Por favor, complete los campos obligatorios del formulario dinámico");
            return;
        }

        info_id = await frmGuardarConPromesa($(idFormDinamico));
        if (!info_id) {
            wc();
            alertify.error("Error al guardar el formulario dinámico");
            return;
        }
    }

    // 2. Crear Nota de Pedido
    var detalles_pema = {
        justificacion: $('#just').val() ? $('#just').val() : 'Entrega Directa ' + (($('#ortr_id').val()) ? '- OT: ' + $('#ortr_id').val() : ''),
        ortr_id: $('#ortr_id').val(),
        peex_id: '',
        articulos: articulos_pedido
    };

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '<?php echo base_url(ALM) ?>Notapedido/crearNotaPedido',
        data: {
            detalles: detalles_pema
        },
        success: function(resp) {
            if (resp.status) {
                var pema_id = resp.pema_id;

                // 3. Lanzar Proceso de Pedido (nivel proceso)
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '<?php echo base_url(ALM) ?>new/Pedido_Material/pedidoNormal',
                    data: {
                        id: pema_id,
                        directo: true
                    },
                    success: function(res) {
                        if (res.status) {
                            // 4. Guardar Entrega
                            var info_entrega = JSON.stringify({
                                comprobante: $('#form-dinamico [name="comprobante"]').val() || 'S/C',
                                fecha: '<?php echo date('Y-m-d') ?>',
                                solicitante: $('#form-dinamico [name="solicitante"]').val() || '',
                                dni: $('#form-dinamico [name="dni"]').val() || '',
                                pema_id: pema_id,
                                info_id: info_id
                            });

                            $.ajax({
                                type: 'POST',
                                dataType: 'json',
                                url: '<?php echo base_url(ALM) ?>new/Entrega_Material/guardarEntrega',
                                data: {
                                    completa: true,
                                    info_entrega: info_entrega,
                                    detalles: detalles_entrega,
                                    cantidades: cantidades,
                                    pema_id: pema_id,
                                    directo: true
                                },
                                success: function() {
                                    Swal.fire('Guardado!', 'La entrega se registró con éxito.', 'success');
                                    linkTo('<?php echo base_url(ALM) ?>new/Entrega_Material');
                                },
                                error: function() {
                                    Swal.fire('Error', 'Error al registrar la entrega.', 'error');
                                },
                                complete: function() {
                                    wc();
                                }
                            });
                        } else {
                            wc();
                            Swal.fire('Error', 'Error al lanzar el proceso de pedido: ' + res.msj, 'error');
                        }
                    },
                    error: function() {
                        wc();
                        Swal.fire('Error', 'Error de conexión al lanzar el proceso', 'error');
                    }
                });
            } else {
                wc();
                Swal.fire('Error', 'Error al crear el pedido interno: ' + resp.msj, 'error');
            }
        },
        error: function() {
            wc();
            Swal.fire('Error', 'Error de conexión al crear el pedido', 'error');
        }
    });
}




function seleccionesta() {
    var id_esta = $("#establecimiento").val();
    if (id_esta === 'false' || !id_esta) {
        $('#deposito').empty().append('<option value="" disabled selected> - Seleccionar - </option>').attr('readonly', true);
        return;
    }
    $.ajax({
        type: 'POST',
        data: {
            id_esta
        },
        url: 'index.php/<?php echo ALM?>Deposito/getdepositosxestaid',
        success: function(data) {
            var resp = JSON.parse(data);
            $('#deposito').empty();
            for (var i = 0; i < resp.length; i++) {
                $('#deposito').append("<option value='" + resp[i].depo_id + "'>" + resp[i].descripcion + "</option>");
            }
            $("#deposito").removeAttr('readonly');
            depo_anterior = $("#deposito").val();
        },
        error: function(data) {
            Swal.fire('Error', 'Error al obtener depósitos', 'error');
        }
    });
}
</script>

<div class="modal fade" id="modal_view" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg view" role="document"></div>
</div>