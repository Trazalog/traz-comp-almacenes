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
                               
                                  $stock = 0 ;
                                //  $stock = $f['cantidad'];
                                
                            } else{
                                $stock = $f['cantidad'];
                         
                            }
                            if($f['arttype']){

                                $tipo_articulo =  str_replace('tipo_articulo', '', $f['arttype']);
                            }

                            if($f['nom_reci'] == ''){
                                $nombre_recipiente = "No Aplica";
                            }

                           
                              if ($f['codigo']== 1 ) {
                                $codigo = 'S/L';
                              }else if ($f['codigo'] === NULL) {
                                $codigo = 'S/L';
                              } else {
                                $codigo = $f['codigo'];
                              }

                              if ($f['depositodescrip'] ===NULL) {
                                $deposito = "No Aplica";    
                              } else {
                                $deposito = $f['depositodescrip'];
                              }
                              
                              $fecha_formateada =  date('d/m/Y', strtotime($f['fecha_nueva']));



                           
                            echo "<tr data-json='".json_encode($f)."'>";
                            echo '<td class="text-center"><button type="button" title="Info" class="btn btn-primary btn-circle btnInfo" data-toggle="modal" data-target="#modalinfo" ><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></but</td>';
                            echo '<td class="text-center">'.$codigo.'</td>';
                            echo '<td>'.$f['artbarcode'].'</td>';
                            echo '<td>'.$f['artdescription'].'</td>';
                            echo '<td class="text-center">'.$stock.'</td>';
                            echo '<td class="text-center">'.$f['un_medida'].'</td>';
                            echo '<td class="text-center">'.$tipo_articulo.'</td>';
                            echo '<td class="text-center">'.$nombre_recipiente.'</td>';
                            echo "<td class='text-center'>".$fecha_formateada."</td>";
                            echo '<td>'.$deposito.'</td>';
			                echo '<td class="text-center">'.estado($f['estado']).'</td>';
                            echo '</tr>';
                          }
                        
                      ?>
                    </tbody>
                </table>

     
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
    debugger;
    datajson = JSON.parse(data);
    blockEdicion();
    llenarModal(datajson);
});

// llena modal para edicion y muestra
function llenarModal(datajson) {
    $('#lote_id_edit').val(datajson.lote_id);
    if (datajson.codigo == 1) {
        $('#codigo_edit').val("S/L");
    } else {
        $('#codigo_edit').val(datajson.codigo);
    }
    $('#artBarCode_edit').val(datajson.artbarcode);
    $('#artDescription_edit').val(datajson.artdescription);
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
 



//Funcion de datatable para extencion de botones exportar
//excel, pdf, copiado portapapeles e impresion

$(document).ready(function() {
    $('#stock').DataTable({
        responsive: true,
        iDisplayLength: 200,
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
                </script>