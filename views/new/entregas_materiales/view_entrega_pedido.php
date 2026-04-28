<style>
.frm-save {
    display: none;
}
</style>


<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Entrega de Materiales</h3>
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
                <select  onchange="seleccionesta(this)" id="establecimiento" class="form-control">
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
                <hr>
                <h3>Pedido Materiales <small>Detalles del Pedido</small></h3>
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
        <button class="btn btn-primary pull-right" onclick="guardarTodo()">Guardar Entrega</button>
    </div>


</div>

<script>
$(document).ready(function() {
    wo();
    detectarForm();
    initForm();

    // Ejecutar seleccionesta cuando se carga la página
    var establecimiento = document.getElementById('establecimiento');
    if (establecimiento && establecimiento.value !== 'false') {
        seleccionesta(establecimiento);
    }
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

var select_row = null;
function ver_info(e) {
    wo();
    select_row = $(e).closest('tr');
    var id = $(select_row).data('id');
    $('#modal_view .view').empty();
    $('#modal_view .view').load("<?php echo base_url(ALM) ?>Articulo/getLotes/" + id, function() {
        // Ocultar el campo Cantidad Entregada en el modal de lotes
        $('#tit_entregada').closest('.col-xs-12').hide();
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
                        id: pema_id
                    },
                    success: function(res) {
                        if (res.status) {
                            // 4. Guardar Entrega
                            var info_entrega = JSON.stringify({
                                comprobante: 'S/C',
                                fecha: '<?php echo date('Y-m-d') ?>',
                                solicitante: '',
                                dni: '',
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
                                    pema_id: pema_id
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




function seleccionesta(opcion){
    var id_esta = $("#establecimiento").val();
    if (id_esta === 'false' || !id_esta) {
        return;
    }
    console.table(id_esta);
    $.ajax({
            type: 'POST',
            data: {id_esta},
            url: 'index.php/<?php echo ALM?>Deposito/getdepositosxestaid',
            success: function(data) {
                var resp = JSON.parse(data);
                console.table(resp);
                $('#deposito').empty();
                for(var i=0; i<resp.length; i++)
                {
                    $('#deposito').append("<option value='" + resp[i].depo_id + "'>" +resp[i].descripcion+"</option");
                }
                $("#deposito").removeAttr('readonly');
            },
            error: function(data) {
                alert('Error');
            }
        });
}
</script>

<div class="modal fade" id="modal_view" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg view" role="document"></div>
</div>