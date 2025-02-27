<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Listado de Artículos</h3>
    </div><!-- /.box-header -->
    <div class="box-body">
		<button class="btn btn-block btn-primary" style="width: 100px; margin: 10px;" data-toggle="modal"
            data-target="#new_articulo" data->Agregar</button>
        <table id="articles" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="15%">Acciones</th>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Tipo de Producto</th>
                    <th>Unidad de Medida</th>
                    <th width="10%">Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                ?>
            </tbody>
        </table>
    </div><!-- /.box-body -->
</div><!-- /.box -->

<script>

function guardarArticulo() {

    var formData = new FormData($('#frm-articulo')[0]);

    if (!validarForm()) return;
    wo();

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'index.php/<?php echo ALM ?>Articulo/guardar',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(rsp) {

            mdlClose('new_articulo');
            hecho();
            linkTo();
        },
        error: function(rsp) {
            alert('Error: No se pudo Guardar Artículo');
            console.log(rsp.msj);
        },
        complete: function() {
            wc();
        }
    });
}

function editarArticulo() {

    var formData = getForm('#frm-articulo');

    if (!validarForm()) return;
    wo();

    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'index.php/<?php echo ALM ?>Articulo/editar',
        data: formData,
        success: function(rsp) {
            mdlClose('new_articulo');
            hecho();
            linkTo();
        },
        error: function(rsp) {
            alert('Error: No se pudo Editar Artículo');
            console.log(rsp.msj);
        },
        complete: function() {
            wc();
        }
    });
}
//Valida el codigo del articulo antes de editar o agregar articulos
function validarArticulo(){
    var barcode = $("#artBarCode").val();
    $.ajax({
        type: "POST",
        url: "<?php echo ALM; ?>Articulo/validarArticulo",
        data: {barcode},
        dataType: "JSON",
        success: function (rsp) {
            if(rsp != null){
                if(rsp.existe == 'true'){
                    error("Error","El código ingresado ya se encuentra utilizado!");
                }else{
                    guardarArticulo();
                }
            }else{
                error("Error","Se produjo un error validando el código ingresado!");
            }
        }
    });
}
function ver(e) {
    var json = JSON.parse(JSON.stringify($(e).closest('tr').data('json')));
    Object.keys(json).forEach(function(key, index) {
        $('[name="' + key + '"]').val(json[key]);
    });
    $('#btn-accion').hide();
    $('#read-only').prop('disabled', true);
    $('#mdl-titulo').html('Detalle Artículo');
    $('#new_articulo').modal('show');
}

function editar(e) {
    // $('#arti_id').prop('disabled', false);
    var json = JSON.parse(JSON.stringify($(e).closest('tr').data('json')));
    Object.keys(json).forEach(function(key, index) {
        $('[name="' + key + '"]').val(json[key]);
    });
    $('#mdl-titulo').html('Editar Artículo');
    $('#btn-accion').attr('onclick', 'editarArticulo()');
    $('#new_articulo').modal('show');
}



// verifica stock de un articulo antes de eliminarlo.
//si retorna False se puede eliminar articulo.
//sino retorna cantidad de stock del articulo.
function verificarStock(e) {
    var json = $(e).closest('tr').data('json');
    selected = json.arti_id;
    console.log(selected);
    $.ajax({
        type: 'POST',
        data: {
            idelim: selected
        },
        url: 'index.php/<?php echo ALM ?>Articulo/verificar_articulo',
        success: function(data) {

        datos = JSON.parse(data);
        console.log('datos trae: ' + datos);
       // if (datos == true) {
           if (datos == null || datos == '0' ){

            console.log("Articulo Sin Stock");
            console.log(selected);
          
            eliminar_articulo(selected);

            } else {
         
            Swal.fire(
                'Error!',
                'No puedes Eliminar un Articulo con Stock!',
                'error'
            )
                return true;
           

            }
         
        },
        error: function(result) {
            console.log(result);
        }

    });
}



function eliminar_articulo() {

    Swal.fire({
  title: '¿Realmente desea ELIMINAR ARTICULO?',
  text: "No podras revertir la acción!",
  type: 'question',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'SI, Eliminar Articulo!'
}).then((result) => {
    console.log(result);
	if (result.value) {
    console.log('sale por verdadero');
					
                    //Ajax
                    $.ajax({
                            type: 'POST',
                            data: {
                                idelim: selected
                            },
                            url: 'index.php/<?php echo ALM ?>Articulo/baja_articulo',
                            success: function(data) {
                               
                                Swal.fire('Hecho!', 'Articulo Eliminado!', 'success')
                                linkTo();
                            },
                            error: function(result) {
                                console.log(result);
                            }

                        });
                        //Fin Ajax

  } else if (result.dismiss) {
                    console.log('sale por falso');
                    Swal.fire('Cancelado', '', 'error')
                }
})



  

}


$("#new_articulo").on("hide.bs.modal", function() {
    $('#mdl-titulo').html('Nuevo Artículo');
    $('#btn-accion').attr('onclick', 'validarArticulo()');
    $('#btn-accion').show();
    $('#frm-articulo')[0].reset();
    $('#read-only').prop('disabled', false);
    // $('#arti_id').prop('disabled', true);
});

function validarForm() {
    console.log('Validando');

    var ban = ($('#unidmed').val() != 'false' && $('#unidmed').val() != '' 
    && $('#artBarCode').val() != null && $('#artBarCode').val() != ''
     && $('#artDescription').val() != null && $('#artDescription').val() != ''
     && $('#tipo').val() != null && $('#tipo').val() != '');
    if (!ban) 
    	Swal.fire(
						'Error...',
						'Debes completar los campos Obligatorios (*)',
						'error'
					);
    return ban;
}

//DataTable($('table'));

//Funcion de datatable para extencion de botones exportar
//excel, pdf, copiado portapapeles e impresion

$(document).ready(function() {
$('#articles').DataTable({
    responsive: true,
    language: {
        url: '<?php base_url() ?>lib/bower_components/datatables.net/js/es-ar.json' //Ubicacion del archivo con el json del idioma.
    },
    dom: 'lBfrtip',
    buttons: [{
            //Botón para Excel
            extend: 'excel',
            exportOptions: {
                columns: [1, 2, 3, 4]
            },
            footer: true,
            title: 'Listado de Artículos',
            filename: 'Listado de Artículos',

            //Aquí es donde generas el botón personalizado
            text: '<button class="btn btn-success ml-2 mb-2 mb-2 mt-3">Exportar a Excel <i class="fa fa-file-excel-o"></i></button>'
        },
        // //Botón para PDF
        {
            extend: 'pdf',
            exportOptions: {
                columns: [1, 2, 3, 4]
            },
            footer: true,
            title: 'Listado de Artículos',
            filename: 'Listado de Artículos',
            text: '<button class="btn btn-danger ml-2 mb-2 mb-2 mt-3">Exportar a PDF <i class="fa fa-file-pdf-o mr-1"></i></button>'
        },
        {
            extend: 'copy',
            exportOptions: {
                columns: [1, 2, 3, 4]
            },
            footer: true,
            title: 'Listado de Artículos',
            filename: 'Listado de Artículos',
            text: '<button class="btn btn-primary ml-2 mb-2 mb-2 mt-3">Copiar <i class="fa fa-file-text-o mr-1"></i></button>'
        },
        {
            extend: 'print',
            exportOptions: {
                columns: [1, 2, 3, 4]
            },
            footer: true,
            title: 'Listado de Artículos',
            filename: 'Listado de Artículos',
            text: '<button class="btn btn-default ml-2 mb-2 mb-2 mt-3">Imprimir <i class="fa fa-print mr-1"></i></button>'
        }
    ],
    'lengthMenu':[[10,25,50,100,],[10,25,50,100]],
    'paging' : true,
    'processing':true,
    'serverSide': true,
    "order": [[2, "asc"]],
    'ajax':{
        type: 'POST',
        url: '<?php echo ALM; ?>/Articulo/paginado'
    },
    'columnDefs':[
            {
                //Agregado para que funcione cabecera de imprimir,descargar excel o pdf.   
                "defaultContent": "-",
                "targets": "_all",
            },
            {
                'targets':[0],
                 //agregado de class con el estilo de las acciones
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('class', 'text-center text-light-blue'); 
                },
                'data':'acciones',
                'render':function(data,type,row){
                    var id= row['arti_id'];
                    json = JSON.stringify(row); 
                    var r = `<tr><td>
                            <i class="fa fa-search" style="cursor: pointer;margin: 3px;" title="Ver Detalles" onclick='ver(this);'></i>`;
                    r += `<i class="fa fa-fw fa-pencil" style="cursor: pointer; margin: 3px;" title="Editar" onclick="editar(this)"></i>`;
                    r += `<i class="fa fa-fw fa-times-circle" style="cursor: pointer;margin: 3px;" title="Eliminar" onclick="verificarStock(this)"></i>`; 
                    r += `</td>`;
                    return r;
                }
            },
            {
                'targets':[1],
                'data':'barcode',
                'orderable': true,
                'render': function(data, type, row){
                    return `<td class="barcode">${row['barcode']}</td>`;
                }
            },
            {
                'targets':[2],
                'data':'descripcion',
                'orderable': true,
                'render': function(data, type, row){
                    return `<td>${row['descripcion']}</td>`;
                }
            },
            {
                'targets':[3],
                'data':'valor',
                'orderable': true,
                'render': function(data, type, row){
                    return `<td>${row['valor']}</td>`;
                }
            },
            {
                'targets':[4],
                'data':'unidad_medida',
                'orderable': true,
                'render': function(data, type, row){
                    return `<td>${row['unidad_medida'] == null ? '-' : row['unidad_medida']}</td>`;
                }
            },
            {
                'targets':[5],
                'data':'estado',
                'render': function(data, type, row){
                    var valorEstado = (row['estado'] == 'AC') ? 'Activo' : '';
                    var estado = bolita(valorEstado,'green');
                    return `<td class="text-center">${estado}</td> </tr>`;
                }
            }
        ],
        //agregado de data-json al tr de la tabla
        createdRow:function( row, data, dataIndex ) {
                    json = JSON.stringify(data);
                    $(row).attr('data-json', json);
        }, 
    });
});
</script>

<!-- Modal -->
<div class="modal" id="new_articulo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="mdl-titulo">Nuevo Artículo</h4>
            </div>

            <div class="modal-body" id="modalBodyArticle">

                <div class="alert alert-danger alert-dismissable" id="error" style="display: none">
                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                    Revise que todos los campos esten completos
                </div>

                <form id="frm-articulo">
                    <input id="arti_id" name="arti_id" type="text" class="hidden">
                    <fieldset id="read-only">
                        <div class="row">
                            <!-- Código de Articulo -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Código <strong class="text-danger">*</strong>: </label>
                                    <input type="text" class="form-control" id="artBarCode" name="barcode">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tipo <strong class="text-danger">*</strong>: </label>
                                    <select name="tiar_id" id="tipo" class="form-control">
                                        <option value="" selected disabled> - Seleccionar - </option>
                                        <?php 
                                            foreach ($tipoArticulos as $key => $o) {
                                                echo "<option value='$o->tabl_id'>$o->valor</option>";
                                            }

                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <!-- Código del Artículo -->
                                <div class="form-group">
                                    <label>Descripción <strong class="text-danger">*</strong>: </label>
                                    <input type="text" class="form-control" id="artDescription" name="descripcion">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Unidad de medida <strong class="text-danger">*</strong>:</label>
                                    <select class="form-control" id="unidmed" name="unme_id">
                                        <option value="false"> - Seleccionar -</option>
                                        <?php
                                    foreach ($unidades_medida as $o) {
                                        echo  "<option value='$o->tabl_id'>$o->descripcion</option>";
                                    }
                                ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Unidades por Caja:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <input type="checkbox"
                                                onclick="$('#cant_caja').prop('disabled', !this.checked)">
                                        </span>
                                        <input id="cant_caja" type="number" class="form-control" name="cantidad_caja"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Punto de pedido:</label>
                                    <input type="number" name="punto_pedido" id="puntped" min="0"
                                        class="form-control text-center" value="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" style="margin-top:25px">
                                    <label>¿Lotear Artículo?</label>
                                    <input type="checkbox" name="es_loteado" id="es_loteado" value="true">
                                </div>
                            </div>
                        </div>
                </form>
            </div> <!-- /.modal-body -->


            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btn-accion" class="btn btn-primary btn-guardar"
                    onclick="validarArticulo()">Guardar</button>
            </div>
        </div>
    </div>
</div>