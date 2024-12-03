<style>
input[type=checkbox] {
    transform: scale(1.6);
    font-size: larger;
    margin: 12px;
}

.checkboxtext {
    /* Checkbox text */
    font-size: 130%;
    display: inline;
}
</style>
<input type="hidden" id="permission" value="<?php echo $permission;?>">

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Stock</h3>
            </div><!-- /.box-header -->
            <!--_________________FILTRO_________________-->
            <form id="frm-filtros">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top: 2%;">
                        <div class="form-group col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            <label>Establecimiento</label>
                            <!--primero seleciono luego tipo de deposito y luego cargo depositos los mantengo bloqueados-->
                            <div class="input-group">
                                <select id="establecimiento" name="establecimiento" class="form-control"
                                    onchange="getTipoDepositos(this)">
                                    <option value="" selected disabled> - Seleccionar - </option>
                                    <?php 
                                foreach ($establecimientos as $key => $o) {
                                    echo "<option value='$o->esta_id'>$o->nombre</option>";
                                }

                                ?>
                                </select>
                            </div>
                        </div>
                        <!-- /.form-group -->
                        <div class="form-group col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            <label>Tipo Depósito</label>
                            <div class="input-group">
                                <select id="tipo_deposito" name="tipo_deposito" class="form-control" disabled
                                    onchange="getDepositos(this)">
                                    <option value="" selected disabled> - Seleccionar - </option>
                                    <option value="productivo">Productivo</option>
                                    <option value="transporte">Transporte</option>
                                    <option value="almacen">Almacen</option>
                                </select>
                            </div>
                        </div>
                        <!-- /.form-group -->
                        <div class="form-group col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            <label>Depósito</label>
                            <div class="input-group">
                                <select id="depositodescrip" name="depositodescrip" class="form-control" disabled>
                                    <option value="" selected disabled> - Seleccionar - </option>
                                </select>
                            </div>
                        </div>
                        <!-- /.form-group -->
                        <div class="form-group col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            <label>Recipiente</label><!-- -->
                            <div class="input-group date">
                                <select id="nom_reci" name="nom_reci" class="form-control" disabled>
                                    <option value="" selected disabled> - Seleccionar - </option>
                                </select>
                            </div>
                        </div>
                        <!-- /.form-group -->
                        <div class="form-group col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            <label>Tipo de Artículo</label>
                            <div class="input-group">
                                <select id="artType" name="artType" class="form-control">
                                    <option value="" selected disabled> - Seleccionar - </option>
                                    <?php 
                                foreach ($tipoArticulos as $key => $o) {
                                    echo "<option value='$o->tabl_id'>$o->valor</option>";
                                }

                                ?>
                                </select>
                            </div>
                        </div>
                        <!-- /.form-group -->
                        <div class="form-group col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            <label>Fecha</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="fec_alta" name="fec_alta"
                                    placeholder="Fecha...">
                            </div>
                        </div>
                        <!-- /.form-group -->
                        <div class="form-group col-xs-12 col-sm-2 col-md-6 col-lg-6">
                            <label>Artículo</label>
                            <input list="articulos" id="inputarti" name="artBarCode" class="form-control"
                                placeholder="Seleccionar Articulo" onchange="getItem(this)" autocomplete="off">
                            <div class="input-group">
                                <datalist id="articulos">
                                    <?php foreach($items as $o)
                        {
                            echo  "<option value='".$o->codigo."' data-json='".$o->json."' class=form-control'>".$o->descripcion." | Stock: ".$o->stock."</option>";
                            unset($o->json);
                        }
                        ?>
                                </datalist>
                            </div>
                            <label id="info" class="text-blue"></label>
                        </div>
                        <!-- /.form-group -->
                        <div class="form-group col-xs-6 col-sm-6 col-md-6 col-lg-6">


                            <!-- Checked checkbox -->
                            <div class="form-check col-sm-6">

                                <label for="stock0" class="checkboxtext">Artículo con Stock en 0
                                    <input class="form-check-input ml-2 mb-2 mb-2 mt-3" type="checkbox" value="0"
                                        id="stock0" />
                                </label>
                            </div>

                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="form-group col-xs-12 col-sm-2 col-md-2 col-lg-2" style="float:right; margin-right: 1%">
                        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">&nbsp;</label>
                        <button type="button" class="btn btn-success btn-flat col-xs-12 col-sm-6 col-md-6 col-lg-6"
                            onclick="filtrar()">Filtrar</button>
                        <button type="button"
                            class="btn btn-danger btn-flat flt-clear col-xs-12 col-sm-6 col-md-6 col-lg-6"
                            onclick="limpiar()">Limpiar</button>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.row -->
                <br>
            </form>
            <!-- <br> -->
            <hr>
            <div class="box-body">
                <table id="stock" class="table table-bordered table-hover">
                    <thead>
                        <th class="text-center">Acciones</th>
                        <th class="text-center">N° Lote</th>
                        <th>Código</th>
                        <th>Producto</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Unidad de Medida</th>
                        <th class="text-center">Tipo de Articulo</th>
                        <th class="text-center">Recipiente</th>
                        <th class="text-center">Fecha Creacion</th>
                        <th>Depósito</th>
                        <th>Estado</th>
                    </thead>
                    <tbody>
                        <?php
               
                          foreach($list as $f)
                          {
                            if($f['cantidad'] === NULL ){
                                $stock = $f['cantidad'];
                                
                                $stock = 0 ;
                                
                            } else{
                                $stock = $f['cantidad'];
                         
                            }
                            if($f['artType']){

                                $tipo_articulo =  str_replace('tipo_articulo', '', $f['artType']);
                            }

                            if($f['nom_reci'] == ''){
                                $nombre_recipiente = "No Aplica";
                            }

                            echo "<tr data-json='".json_encode($f)."'>";
                            echo '<td class="text-center"><button type="button" title="Info" class="btn btn-primary btn-circle btnInfo" data-toggle="modal" data-target="#modalinfo" ><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></but</td>';
                            // echo '<td class="text-center">'.($f['codigo']==1?'S/L':$f['codigo']).'</td>';
                            echo '<td class="text-center">'.(($f['codigo'] != 1 && $f['codigo'] != '1') ? $f['codigo'] : 'S/L').'</td>';
                            echo '<td>'.$f['artBarCode'].'</td>';
                            echo '<td>'.$f['artDescription'].'</td>';
                            echo '<td class="text-center">'.$stock.'</td>';
                            echo '<td class="text-center">'.$f['un_medida'].'</td>';
                            echo '<td class="text-center">'.$tipo_articulo.'</td>';
                            echo '<td class="text-center">'.$nombre_recipiente.'</td>';
                            echo "<td class='text-center'>".fecha(substr($f['fec_alta'], 0, 10))."</td>";
                            echo '<td>'.$f['depositodescrip'].'</td>';
			                echo '<td class="text-center">'.estado($f['estado']).'</td>';
                            echo '</tr>';
                          }
                        
                      ?>
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
</div><!-- /.row -->
<!---///////--- MODAL EDICION E INFORMACION ---///////--->
<div class="modal fade bs-example-modal-lg" id="modalinfo" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header bg-blue">
                <button type="button" class="close close_modal_edit" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color:white;">&times;</span>
                </button>
            </div>

            <div class="modal-body ">
                <form class="formEdicion" id="formEdicion">
                    <div class="form-horizontal">
                        <div class="row">
                            <form class="frm_stock_edit" id="frm_stock_edit">

                                <input type="text" class="form-control habilitar hidden" name="lote_id"
                                    id="lote_id_edit">

                                <div class="col-sm-6">
                                    <!--_____________ N° Lote _____________-->
                                    <div class="form-group">
                                        <label for="codigo_edit" class="col-sm-4 control-label">N° Lote:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control habilitar requerido" name="codigo"
                                                id="codigo_edit">
                                        </div>
                                    </div>
                                    <!--___________________________-->

                                    <!--_____________ Código _____________-->
                                    <div class="form-group">
                                        <label for="artBarCode_edit" class="col-sm-4 control-label">Código:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control habilitar" name="artBarCode"
                                                id="artBarCode_edit">
                                        </div>
                                    </div>
                                    <!--__________________________-->

                                    <!--_____________ Producto _____________-->
                                    <div class="form-group">
                                        <label for="artDescription_edit"
                                            class="col-sm-4 control-label">Producto:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control habilitar" name="artDescription"
                                                id="artDescription_edit">
                                        </div>
                                    </div>
                                    <!--__________________________-->

                                    <!--_____________ Stock _____________-->
                                    <div class="form-group">
                                        <label for="cantidad_edit" class="col-sm-4 control-label">Stock:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control habilitar" name="cantidad"
                                                id="cantidad_edit">
                                        </div>
                                    </div>
                                    <!--__________________________-->

                                    <!--_____________ Unidad de Medida _____________-->
                                    <div class="form-group">
                                        <label for="un_medida_edit" class="col-sm-4 control-label">Unidad de
                                            Medida:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control habilitar" name="un_medida"
                                                id="un_medida_edit">
                                        </div>
                                    </div>
                                    <!--__________________________-->

                                </div><!-- /.col-sm-6 -->

                                <div class="col-sm-6">

                                    <!--_____________ Recipiente _____________-->
                                    <div class="form-group">
                                        <label for="nom_reci_edit" class="col-sm-4 control-label">Recipiente:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control habilitar" name="nom_reci"
                                                id="nom_reci_edit">
                                        </div>
                                    </div>
                                    <!--__________________________-->

                                    <!--_____________ Fecha Creación _____________-->
                                    <div class="form-group">
                                        <label for="fec_alta_edit" class="col-sm-4 control-label">Fecha
                                            Creación:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control habilitar" name="fec_alta"
                                                id="fec_alta_edit">
                                        </div>
                                    </div>
                                    <!--__________________________-->

                                    <!--_____________ Depósito _____________-->
                                    <div class="form-group">
                                        <label for="depositodescrip_edit"
                                            class="col-sm-4 control-label">Depósito:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control habilitar" name="depositodescrip"
                                                id="depositodescrip_edit">
                                        </div>
                                    </div>
                                    <!--__________________________-->

                                    <!--_____________ Recipiente _____________-->
                                    <!-- <div class="form-group">
                        <label for="recipiente_edit" class="col-sm-4 control-label">Recipiente:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control habilitar" name="recipiente" id="recipiente_edit">
                        </div>
                    </div> -->
                                    <!--__________________________-->

                                </div><!-- /.col-sm-6 -->

                            </form>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">

                <div class="form-group text-right">
                    <!-- <button type="" class="btn btn-primary habilitar" data-dismiss="modal" id="btnsave_edit" onclick="guardar('editar')">Guardar</button> -->
                    <button type="" class="btn btn-default cerrarModalEdit" id="" data-dismiss="modal">Cerrar</button>
                </div>

            </div>

        </div>
    </div>

</div>
<!---///////--- FIN MODAL EDICION E INFORMACION ---///////--->
<script>
//Funcion de datatable para extencion de botones exportar
//excel, pdf, copiado portapapeles e impresion

$(document).ready(function() {
    $('#stock').DataTable({
        responsive: true,
        language: {
            url: '<?php base_url() ?>lib/bower_components/datatables.net/js/es-ar.json' //Ubicacion del archivo con el json del idioma.
        },
        dom: 'lBfrtip',
        buttons: [{
                //Botón para Excel
                extend: 'excel',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                },
                footer: true,
                title: 'Excel Stock',
                filename: 'Excel_Stock',

                //Aquí es donde generas el botón personalizado
                text: '<button class="btn btn-success ml-2 mb-2 mb-2 mt-3">Exportar a Excel <i class="fa fa-file-excel-o"></i></button>'
            },
            // //Botón para PDF
            // {
            //     extend: 'pdf',
            //     exportOptions: {
            //         columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
            //     },
            //     footer: true,
            //     title: 'Excel Stock',
            //     filename: 'Excel_Stock',
            //     text: '<button class="btn btn-danger ml-2 mb-2 mb-2 mt-3">Exportar a PDF <i class="fa fa-file-pdf-o mr-1"></i></button>'
            // },
            {
                extend: 'copy',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                },
                footer: true,
                title: 'Excel Stock',
                filename: 'Excel_Stock',
                text: '<button class="btn btn-primary ml-2 mb-2 mb-2 mt-3">Copiar <i class="fa fa-file-text-o mr-1"></i></button>'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                },
                footer: true,
                title: 'Excel Stock',
                filename: 'Excel_Stock',
                text: '<button class="btn btn-default ml-2 mb-2 mb-2 mt-3">Imprimir <i class="fa fa-print mr-1"></i></button>'
            }
        ]
    });
});



// extrae datos de la tabla
$(".btnInfo").on("click", function(e) {
    $(".modal-header h4").remove();
    //guardo el tipo de operacion en el modal
    $("#operacion").val("Info");
    //pongo titulo al modal
    $(".modal-header").append(
        '<h4 class="modal-title"  id="myModalLabel"><span id="modalAction" class="fa fa-fw fa-search"></span> Detalle Stock </h4>'
    );
    data = $(this).parents("tr").attr("data-json");
    datajson = JSON.parse(data);
    blockEdicion();
    llenarModal(datajson);
});

// llena modal para edicion y muestra
function llenarModal(datajson) {
    $('#lote_id_edit').val(datajson.lote_id);
    if (datajson.codigo != 1 && datajson.codigo != '1') {
        $('#codigo_edit').val(datajson.codigo);
    } else {
        $('#codigo_edit').val("S/L");
    }
    $('#artBarCode_edit').val(datajson.artBarCode);
    $('#artDescription_edit').val(datajson.artDescription);
    $('#cantidad_edit').val(datajson.cantidad);
    $('#un_medida_edit').val(datajson.un_medida);
    $('#nom_reci_edit').val(datajson.nom_reci);
    var fecha = datajson.fec_alta.substring(0, 10);
    $('#fec_alta_edit').val(fecha);
    $('#depositodescrip_edit').val(datajson.depositodescrip);
    $('#recipiente_edit').val(datajson.recipiente);
}
// deshabilita botones, selects e inputs de modal
function blockEdicion() {
    $(".habilitar").attr("readonly", "readonly");
}

//Filtra la tabla y la redibuja
//Cada campo esta validado en caso de vacios o NULL no se muestren en la tabla
function filtrar() {
     var data = new FormData($('#frm-filtros')[0]);
     data = formToObject(data);
    wo();
    var url = "<?php echo base_url(ALM) ?>Lote/filtrarListado";
    $.ajax({
        type: 'POST',

        data: data,

        url: url,
        success: function(data) {
            WaitingClose();
            // $("#tbl_recepciones").removeAttr('style');
            var table = $('table#stock').DataTable();
            table.rows().remove().draw();
            if (data != null) {
                var resp = JSON.parse(data);
                for (var i = 0; i < resp.length; i++) {
                    var movimCabecera = resp[i];
                    var row =
                        `<tr data-json='${JSON.stringify(movimCabecera)}'>
                                    <td class="text-center"><button type="button" title="Info" class="btn btn-primary btn-circle btnInfo" data-toggle="modal" data-target="#modalinfo" ><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></but</td>`;
                    //N° LOTE
                    if (resp[i].codigo != 1 || resp[i].codigo != '1') {
                        row += `<td>${resp[i].codigo}</td>`;
                    } else {
                        row += `<td>S/L</td>`;
                    }
                    //Codigo Articulo
                    if (resp[i].artBarCode) {
                        row += `<td>${resp[i].artBarCode}</td>`;
                    } else {
                        row += `<td></td>`;
                    }
                    //Descripcion Articulo
                    if (resp[i].artDescription) {
                        row += `<td>${resp[i].artDescription}</td>`;
                    } else {
                        row += `<td></td>`
                    }
                    //Stock
                    if (resp[i].cantidad) {
                        row += `<td>${resp[i].cantidad}</td>`;
                    } else {
                        row += `<td></td>`
                    }
                    //Unidad de medida
                    if (resp[i].un_medida) {
                        row += `<td>${resp[i].un_medida}</td>`;
                    } else {
                        row += `<td></td>`
                    }
                    //Recipiente
                    if (resp[i].nom_reci) {
                        row += `<td>${resp[i].nom_reci}</td>`;
                    } else {
                        row += `<td></td>`
                    }
                    //formateo de la fecha
                    if (resp[i].fec_alta) {
                        var fecha_alta = resp[i].fec_alta.slice(0, 10);
                        Date.prototype.toDateInputValue = (function() {
                            var local = new Date(fecha_alta);
                            return local.toJSON().slice(0, 10);
                        });
                        fecha = new Date().toDateInputValue();

                        row += `<td>` + fecha + `</td>`;
                    } else {
                        row += `<td></td>`
                    }
                    //Deposito
                    if (resp[i].depositodescrip) {
                        row += `<td>${resp[i].depositodescrip}</td>`;
                    } else {
                        row += `<td></td>`
                    }
                    //Estado
                    if (resp[i].estado) {
                        var span_estado = estado(resp[i].estado);
                        row += `<td>` + span_estado + `</td>`;
                    } else {
                        row += `<td></td>`;
                    }
                    row += `</tr>`;
                    table.row.add($(row)).draw();
                    movimDetalle = "";
                }
            }

            wc();
        },
        error: function() {
            alert('Ha ocurrido un error');
        },
        complete: function(result) {
            wc();
        },
        beforeSend: function() {
            // $("table#stock").empty();
        },
    });
}

function estado($estado) {
    // #   $estado =  trim($estado);

    switch ($estado) {

        //Estado Generales
        case 'AC':
            return bolita('Activo', 'green');
            break;
        case 'IN':
            return bolita('Inactivo', 'red');
            break;

            //Estado Camiones
        case 'CARGADO':
            return bolita('Cargado', 'yellow');
            break;
        case 'EN CURSO':
            return bolita('En Curso', 'green');
            break;
        case 'DESCARGADO':
            return bolita('Descargado', 'yellow');
            break;
        case 'TRANSITO':
            return bolita('En Transito', 'orange');
            break;
        case 'FINALIZADO':
            return bolita('Finalizado', 'red');
            break;

            //Estado Etapas
        case 'En Curso':
            return bolita('En Curso', 'green');
            break;

        case 'PLANIFICADO':
            return bolita('Planificado', 'blue');
            break;
            //Estado por Defecto
        default:
            return bolita('S/E', '');
            break;
    }
}

function bolita($texto, $color, $detalle = '') {
    return "<span data-toggle='tooltip' title='" + $detalle + "' class='badge bg-" + $color + " estado'>" + $texto +
        " </span>";
}

function limpiar() {
    $("#nom_reci").val('');
    $("#depositodescrip").val('');
    $("#tpo_depo").val('');
    $("#artDescription").val('');
    $("#artBarCode").val('');
    $("#fec_alta").val('');
    $("#artType").val('');
    $("#inputarti").val('');
    $("label#info").html('');
    $("#establecimiento").val('');
    $('#tipo_deposito').val('');
    //Deshabilito tipo y deposito
    $('#tipo_deposito').prop('disabled', 'disabled');
    $("#depositodescrip").prop('disabled', 'disabled');
    //Deshabilito recipiente
    $('#nom_reci').prop('disabled', 'disabled');
}

function getItem(item) {
    if (item == null) return;
    var option = $('#articulos').find("[value='" + item.value + "']");
    var json = JSON.stringify($(option).data('json'));
    selectItem = JSON.parse(json);
    $('label#info').html($(option).html());
    if (existFunction('eventSelect')) eventSelect();
}
//En el success armo el select de depositos dependiendo el tipo de deposito que se selecciono
//Si es productivo solo tomo los id's < 1000
//Si es transporte solo tomo los id's > 1000 y < 2000
//Si es productivo solo tomo los id's > 2000
function getDepositos(item) {
    $("#depositodescrip").empty();
    if (item == null) return;

    //Utilizo el tipo para definir que depositos mostrar
    tipoDeposito = item.value;
    //Utilizo para buscar los depositos por establecimiento
    esta_id = $('#establecimiento').val();
    var data = {
        esta_id: esta_id
    };
    wo();
    var url = "<?php echo base_url(ALM) ?>Lote/getDepositos";
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        success: function(response) {

            if (response != null) {
                var resp = JSON.parse(response);
                var opc = document.createElement('option');
                opc.value = '';
                opc.innerHTML = "-Seleccionar-";

                $("#depositodescrip").append(opc);
                $.each(resp, function(index, value) {
                    switch (tipoDeposito) {
                        case 'productivo':
                            if (value.depo_id < 1000) {
                                var opc = document.createElement('option');
                                opc.value = value.depo_id;
                                opc.innerHTML = value.descripcion;
                            }
                            break;

                        case 'transporte':
                            if (value.depo_id >= 1000 && value.depo_id < 2000) {
                                var opc = document.createElement('option');
                                opc.value = value.depo_id;
                                opc.innerHTML = value.descripcion;
                            }
                            break;

                        case 'almacen':
                            if (value.depo_id >= 2000) {
                                var opc = document.createElement('option');
                                opc.value = value.depo_id;
                                opc.innerHTML = value.descripcion;
                            }
                            break;

                        default:
                            break;
                    }

                    $("#depositodescrip").append(opc);
                });
            }
            $("#depositodescrip").prop('disabled', '');
            // $("#tipo_deposito").prop('disabled','');
            wc();
        },
        complete: function() {
            wc();
        }
    });
}
//Limpio los depositos y lo deshabilito hasta que seleccione un tipo de deposito
function getTipoDepositos(item) {

    $("#depositodescrip").empty();
    $("#nom_reci").empty();
    if (item == null) return;
    $('#tipo_deposito').prop('disabled', '');
    $('#tipo_deposito').val('');
    $("#depositodescrip").prop('disabled', 'disabled');

    //Traigo los recipientes por establecimiento
    var data = {
        esta_id: item.value
    };
    var url = "<?php echo base_url(ALM) ?>Lote/getRecipientesPorEstablecimiento";
    wo();
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        success: function(response) {

            if (response != null) {
                var resp = JSON.parse(response);
                var opc = document.createElement('option');
                opc.value = '';
                opc.innerHTML = "-Seleccionar-";

                $("#nom_reci").append(opc);

                $.each(resp, function(index, value) {
                    var opc = document.createElement('option');
                    opc.value = value.id;
                    opc.innerHTML = value.titulo;
                    $("#nom_reci").append(opc);
                });
            }
            $("#nom_reci").prop('disabled', '');

            wc();
        },
        complete: function() {
            wc();
        }
    });
}
</script>