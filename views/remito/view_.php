<section>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header  with-border">
                    <h3 class="box-title"> Recepción de Materiales</h3>
                </div><!-- /.box-header -->

                <div class="box-body">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Datos del Comprobante</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <label for="comprobante">Comprobante <strong style="color: #dd4b39">*</strong>
                                        :</label>
                                    <input type="text" placeholder="Ingrese Numero..." class="form-control"
                                        id="comprobante" name="comprobante">
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <label for="fecha">Fecha <strong style="color: #dd4b39">*</strong> :</label>
                                    <input type="text" class="form-control datetime" id="fecha" name="fecha">
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <label for="proveedor">Proveedor <strong style="color: #dd4b39">*</strong>
                                        :</label>
                                    <select id="proveedor" name="proveedor" class="form-control">
                                        <option value="false"> - Seleccionar - </option>
                                        <?php 
                                                    foreach ($proveedores as $o) {
                                                        echo "<option value='$o->prov_id'>$o->nombre</option>";
                                                    }
                                                    ?>
                                    </select>
                                </div>
                            </div>
                        </div> <!-- /.panel-body -->
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"> Detalle de Recepción</h3>
                        </div>

                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12"><br>
                                    <label for="lote">Seleccionar Articulo <strong
                                            style="color: #dd4b39">*</strong>:</label>
                                    <?php $this->load->view(ALM.'articulo/componente'); ?>
                                </div>

                                <div class="col-xs-12 col-sm-3 col-md-3"><br>
                                    <label for="lote">Lote <strong style="color: #dd4b39">*</strong>:</label>
                                    <input type="text" id="lote" name="lote" placeholder="Lote" class="form-control">
                                </div>
                                <div class="col-xs-12 col-sm-3 col-md-3"><br>
                                    <label for="vencimiento">Fecha Vencimiento:</label>
                                    <input type="text" id="vencimiento" name="vencimiento" placeholder="dd/mm/yyyy" data-date-format="YYYY-MM-DD"
                                        class="form-control date">
                                </div>
                                <div class="col-xs-12 col-sm-3 col-md-3"><br>
                                    <label for="cantidad">Cantidad <strong style="color: #dd4b39">*</strong> :</label>
                                    <input type="number" id="cantidad" name="cantidad" onchange="montoTotal(3,this.value);" placeholder="Ingrese Cantidad..." class="form-control">
                                </div>
                                <div class="col-xs-12 col-sm-3 col-md-3"><br>
                                    <label for="establecimiento">Establecimientos <strong style="color: #dd4b39">*</strong>
                                        :</label>
                                    <select onchange="seleccionesta(this)" id="establecimiento" class="form-control">
                                        <option value="false"> - Seleccionar - </option>
                                        <?php 
                                            foreach ($establecimientos as $o) {
                                                echo "<option value='$o->esta_id'>$o->nombre</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-xs-12 col-sm-3 col-md-3"><br>
                                    <label for="deposito">Depósito <strong style="color: #dd4b39">*</strong>
                                        :</label>
                                    <select id="deposito" name="deposito" class="form-control" readonly>
                                        <option value="" disabled selected> - Seleccionar - </option>
                                    </select>
                                </div>
                                <!--SECCION PRECIOS-->
                                
                                <?php 
                                    foreach ($alm_config as $config) {  
                                        echo '<input type="hidden" id="p_valor" name="p_valor" value="'. $config->valor2.'"  class="form-control">';
                                
                                        if($config->valor2){
                                            echo '<div class="col-xs-12 col-sm-3 col-md-3"><br>
                                                <label for="p_pesos">Precio Unit. (Pesos) <strong style="color: #dd4b39">*</strong>:</label>
                                                <input type="number" id="p_pesos" name="p_pesos" placeholder="Ingrese Precio..."  onchange="montoTotal(1,this.value);"  class="form-control">
                                            </div>';

                                            echo '<div class="col-xs-12 col-sm-3 col-md-3"><br>
                                                <label for="pt_pesos">P. Total (Pesos):</label>
                                                <input type="text" id="pt_pesos" name="pt_pesos" placeholder="Precio Total Pesos"  class="form-control" disabled>
                                            </div>';
                                            
                                            echo ' <div class="col-xs-12 col-sm-3 col-md-3"><br>
                                                <label for="p_dolar">Precio Unit. (Dolar)<strong style="color: #dd4b39">*</strong> :</label>
                                                <input type="number" id="p_dolar" name="p_dolar" placeholder="Ingrese Precio..." onchange="montoTotal(2,this.value);" class="form-control">
                                            </div>';
                                            echo '<div class="col-xs-12 col-sm-3 col-md-3"><br>
                                                <label for="pt_dolar">P. Total (Dolar):</label>
                                                <input type="text" id="pt_dolar" value="" name="pt_dolar" placeholder="Precio Total Dolar" class="form-control" disabled>
                                            </div>';

                                        }
                                        break;
                                    }                                    
                                ?>                                                                
                                <!--SECCION PRECIOS-->
                                <div class="col-xs-12 col-sm-12 col-md-12"><br>
                                    <br>
                                    <button class="btn btn-primary " style="float:right;"
                                        onclick="verificarExistenciaLote()"><i class="fa fa-check"></i>Agregar</button>
                                </div>
                            </div><br>
                        </div>
                    </div>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="insum">
                            <div class="row">
                                <div class="col-xs-12 table-responsive">
                                    <table class="table table-bordered table-striped" id="tablainsumo">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Lote</th>
                                                <th>Fec. Vencimiento</th>
                                                <th>Código</th>
                                                <th>Descripción</th>
                                                <th>Cantidad</th>
                                                <th class="hidden">P Pesos</th>
                                                <th class="hidden">P Doloar</th>
                                                <th>Depósito</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!-- /. tab-pane -->
                    </div><!-- /.tab-content -->
                </div><!-- /.box-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal"
                        onclick="guardar()">Guardar</button>
                </div> <!-- /.modal footer -->

            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->
<script>

function montoTotal(cod_mone,value){
   
   var cantidad = $('#cantidad').val();
   /* Se ha escogido switch porque quizas a futuro se incluya otra moneda*/    
   if(cantidad == '') {
       alert('Revisar Campo Cantidad y Campos Obligatorios(*) Incompletos');
       return;
   }else{
       switch (cod_mone) {
           case 1: /* Pesos*/               
               var montoTotP = value*cantidad;
               console.log("Caso 1: "+cod_mone+" "+value+" "+cantidad+" "+montoTotP);
               $('#pt_pesos').val(montoTotP);
               break;
           case 2:  /* Dolar*/               
               var montoTotD = value*cantidad;
               console.log("Caso 2: "+cod_mone+" "+value+" "+cantidad+" "+montoTotD);
               $('#pt_dolar').val(montoTotD);
               break;    
           case 3:  /* Por si sucedea la inversa*/               
               var p_dolar = $('#p_dolar').val();               
               var p_pesos = $('#p_pesos').val();
               console.log("Caso 3: "+cod_mone+" "+value+" "+cantidad+" Dolar "+p_dolar+" Pesos "+p_pesos);
               if(p_dolar !== ''){
                   console.log(cod_mone+" "+value+" "+cantidad);
                   var montoTotD = p_dolar*value;
                   $('#pt_dolar').val(montoTotD);                    
               }
               if(p_pesos !== ''){
                   console.log(cod_mone+" "+value+" "+cantidad);                    
                   var montoTotP = p_pesos*value;
                   $('#pt_pesos').val(montoTotP);
               }
               break;    
           default:
               break;
       }
   } 
}

function eventSelect() {

    if (!selectItem.es_loteado ) {
        $('#lote').prop('disabled', true);
        $('#lote').val('S/L');
    } else {
        $('#lote').prop('disabled', false);
        $('#lote').val('');
    }
}
function seleccionesta(opcion){
    // alert("dentro");
    var id_esta = $("#establecimiento").val();
    console.table(id_esta);
    $.ajax({
            type: 'POST',
            data: {id_esta},
            url: 'index.php/<?php echo ALM?>Deposito/getdepositosxestaid',
            success: function(data) {
                var resp = JSON.parse(data);
                console.table(resp);
                console.table(resp[0].depo_id);
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
function verificarExistenciaLote() {

    if (!validar_campos()) {
        alert('Campos Obligatorios(*) Incompletos');
        return;
    }
    if (!selectItem.es_loteado ) {
        agregar();
        return;
    }
    var lote = $('#lote').val();
    var depo = $('#deposito').val();
    var arti = selectItem.arti_id;

    if (lote == null || lote == '') return;
    if (depo == null || depo == '') return;
    if (arti == null || arti == '') return;

    
    $.ajax({
        type: 'POST',
        url: 'index.php/<?php echo ALM ?>Lote/verificarExistencia',
        data: {
            lote,
            depo,
            arti
        },
        success: function(result) {
            if (result == 1) {
                $('#acumular').modal('show');
            } else {
                agregar();
                

            }
        },
        error: function(result) {
            alert('Error');
        }
    });
}


var idslote = {};
var j = 0;

$(".datetime").datetimepicker({
    format: 'YYYY-MM-DD HH:mm',
    locale: 'es',
    date: new Date()
});

$(".date").datetimepicker({
    format: 'YYYY-MM-DD',
    locale: 'ES'
});


// autocomplete para codigo
var dataF = function() {
    var tmp = null;
    $.ajax({
        'async': false,
        'type': "POST",
        'global': false,
        'dataType': 'json',
        'url': "index.php/<?php echo ALM ?>Remito/getcodigo",
        'success': function(data) {
            tmp = data;
        }
    });
    return tmp;
}();
$("#codigo").autocomplete({
    source: dataF,
    delay: 100,
    minLength: 1,
    /*response: function(event, ui) {
      var noResult = { value:"",label:"No se encontraron resultados" };
      ui.content.push(noResult);
    },*/
    focus: function(event, ui) {
        // prevent autocomplete from updating the textbox
        event.preventDefault();
        // manually update the textbox
        $(this).val(ui.item.label);
        $("#descripcion").val(ui.item.artDescription);
    },
    select: function(event, ui) {
        // prevent autocomplete from updating the textbox
        event.preventDefault();
 
        $("#codigo").val(ui.item.label); //value
        $("#descripcion").val(ui.item.artDescription);
        $("#id_herr").val(ui.item.value);
    
    },
});

function traer_lote(id_her, id_deposito) {

    $.ajax({
        type: 'POST',
        data: {
            id_her: id_her,
            id_deposito: id_deposito
        },
        url: 'index.php/<?php echo ALM ?>Remito/getlote', //index.php/
        success: function(data) {

            for (var i = 0; i < data.length; i++) {
                idslote[j] = data[i]['loteid'];
            }
        },
        error: function(result) {
            console.log(result);
        },
        dataType: 'json'
    });
}

function limpiar() {
    $("#comprobante").val("");
    $('#vencimiento').val('');
    $("#fecha").val("");
    $("#solicitante").val("");
    $("#destino").val("");
    $("#codigo").val("");
    $("#descripcion").val("");
    $("#cantidad").val("");
    $('#p_pesos').val('');
    $('#p_dolar').val('');
    $('#pt_pesos').val('');
    $('#pt_dolar').val('');
    $("#deposito").val("");
    $('#tablainsumo tbody tr').remove();
}




//agrega insumos a la tabla detainsumos
var i = 1;

function agregar() {
    //debugger;
    var lote = $('#lote').val();
    var vencimiento = $('#vencimiento').val();
    var codigo = selectItem.barcode;
    var id_her = selectItem.arti_id;
    var descripcion = selectItem.descripcion;
    var cantidad = $('#cantidad').val();
    if($('#p_valor').val()){
        var p_pesos = $('#p_pesos').val();
        var p_dolar = $('#p_dolar').val();
    }    
    var deposito = $("select#deposito option:selected").html();
    var id_deposito = $('#deposito').val();

    if (id_her == '' || lote == '' || cantidad == '' || p_pesos == '' || p_dolar == '' || id_deposito == false) {
        alert('Campos Obligatorios(*) Incompletos');
        return;
    }

    if($('#p_valor').val()){
        if (p_pesos == '' || p_dolar == '') {
            alert('Campos Obligatorios(*) Incompletos');
            return;
        }
    }

    if($('#p_valor').val()){

        var json = {
            // lote_id: (selectItem.es_loteado == 0 ? 1 : lote),
            fec_vencimiento: vencimiento,
            arti_id: id_her,
            loteado: (selectItem.es_loteado?1:0),
            codigo: (!selectItem.es_loteado ? 1 : lote),
            cantidad: (cantidad * (selectItem.es_caja == 1 ? selectItem.cantidad_caja : 1)),
            p_pesos : p_pesos,
            p_dolar : p_dolar,
            depo_id: id_deposito,
            prov_id: $('#proveedor').val()
        }

        var tr = "<tr id='" + i + "'  data-json=\'" + JSON.stringify(json) + "\'>" +
            "<td ><i class='fa fa-ban elirow text-light-blue' style='cursor: 'pointer'></i></td>" +
            "<td>" + lote + "</td>" +
            "<td>" + vencimiento + "</td>" +
            "<td>" + codigo + "</td>" +
            "<td class='hidden' id='" + id_her + "'>" + id_her + "</td>" +
            "<td>" + descripcion + "</td>" +
            "<td>" + cantidad + "</td>" +
            "<td class='hidden'>" + p_pesos + "</td>" +
            "<td class='hidden'>" + p_dolar + "</td>" +
            "<td>" + deposito + "</td>" +
            "<td class='hidden' id='" + id_deposito + "'>" + id_deposito + "</td>" +
            "</tr>";
        i++;

        /* mando el codigo y el id _ deposito entonces traigo esa cantidad de lote*/
        var hayError = false;
        var Error1 = false;
        var Error2 = false;

        if (Error1 == true) {
            $('#error1').fadeOut('slow'); // lo levanto
        }
        if (Error2 == true) {
            $('#error2').fadeOut('slow'); // lo levanto
        }
        if (codigo != 0 && cantidad > 0 && id_deposito > 0) {
            $('#tablainsumo tbody').append(tr);


            $(document).on("click", ".elirow", function() {
                var parent = $(this).closest('tr');
                $(parent).remove();
            });

            $('#lote').val('');
            $('#codigo').val('');
            $('#descripcion').val('');
            $('#cantidad').val('');
            $('#p_pesos').val('');
            $('#p_dolar').val('');
            $('#pt_pesos').val('');
            $('#pt_dolar').val('');
            $('#deposito').val('');
            $('#vencimiento').val('');
            $('#lote').prop('disabled', false);


        }
        clearSelect();

    }else{

        var json = {
            // lote_id: (selectItem.es_loteado == 0 ? 1 : lote),
            fec_vencimiento: vencimiento,
            arti_id: id_her,
            loteado: (selectItem.es_loteado?1:0),
            codigo: (!selectItem.es_loteado ? 1 : lote),
            cantidad: (cantidad * (selectItem.es_caja == 1 ? selectItem.cantidad_caja : 1)),    
            depo_id: id_deposito,
            prov_id: $('#proveedor').val()
        }

        var tr = "<tr id='" + i + "'  data-json=\'" + JSON.stringify(json) + "\'>" +
            "<td ><i class='fa fa-ban elirow text-light-blue' style='cursor: 'pointer'></i></td>" +
            "<td>" + lote + "</td>" +
            "<td>" + vencimiento + "</td>" +
            "<td>" + codigo + "</td>" +
            "<td class='hidden' id='" + id_her + "'>" + id_her + "</td>" +
            "<td>" + descripcion + "</td>" +
            "<td>" + cantidad + "</td>" +            
            "<td>" + deposito + "</td>" +
            "<td class='hidden' id='" + id_deposito + "'>" + id_deposito + "</td>" +
            "</tr>";
        i++;

        /* mando el codigo y el id _ deposito entonces traigo esa cantidad de lote*/
        var hayError = false;
        var Error1 = false;
        var Error2 = false;

        if (Error1 == true) {
            $('#error1').fadeOut('slow'); // lo levanto
        }
        if (Error2 == true) {
            $('#error2').fadeOut('slow'); // lo levanto
        }
        if (codigo != 0 && cantidad > 0 && id_deposito > 0) {
            $('#tablainsumo tbody').append(tr);


            $(document).on("click", ".elirow", function() {
                var parent = $(this).closest('tr');
                $(parent).remove();
            });

            $('#lote').val('');
            $('#codigo').val('');
            $('#descripcion').val('');
            $('#cantidad').val('');            
            $('#deposito').val('');
            $('#vencimiento').val('');
            $('#lote').prop('disabled', false);


        }
        clearSelect();
    }        
};

function guardar() {

    var info = get_info_remito();

    if (info == null) return;

    var detalles = [];

    $("#tablainsumo tbody tr").each(function(index) {

        detalles.push($(this).data('json'));

    });

    if (detalles.lenght == 0) {
        alert('No hay datos cargados');
    }
    wo();
    $.ajax({
        type: 'POST',
        data: {
            info,
            detalles
        },
        url: 'index.php/<?php echo ALM ?>Remito/guardar_mejor', //index.php/
        success: function(data) {

            linkTo('<?php echo ALM ?>Remito');
        },
        error: function(result) {
            alert('Error');
        },complete:function() {
            wc();
        }
    });
}



function get_info_remito() {

    if ($('#fecha').val() == '' || $('#comprobante').val() == '' || $('#proveedor').val() == 'false') {
        alert('Campos Obligatorios(*) Incompletos');
        return null;
    }
    return {
        'fecha': $('#fecha').val(),
        'provid': $('#proveedor').val(),
        'comprobante': $('#comprobante').val(),
    };

}

function validar_campos() {
    
    if($('#p_valor').val()){
        return !($('#p_pesos').val() == '' ||  $('#p_dolar').val() == '' || $('#fecha').val() == '' || $('#comprobante').val() == '' || $('#proveedor').val() == 'false' || $('#lote').val() == '' || $('#cantidad').val() == '' || $('#establecimiento').val() == 'false' || $('#deposito').val() == 'false')
    }else{
        return !($('#fecha').val() == '' || $('#comprobante').val() == '' || $('#proveedor').val() == 'false' || $('#lote').val() == '' || $('#cantidad').val() == '' || $('#establecimiento').val() == 'false' || $('#deposito').val() == 'false')
    }
}
</script>


<script>
function selectItemiculo(e) {
    selectItem = JSON.parse(JSON.stringify($(e).data('json')));
    $('#art_select').val($(e).find('a').html());
    $('#articulos').modal('hide');
    if (!selectItem.es_loteado) {
        $('#lote').prop('disabled', true);
        $('#lote').val('S/L');
    } else {
        $('#lote').prop('disabled', false);
        $('#lote').val('');
    }
    //  traer_deposito($(e).data('id'));
}
</script>

<!-- Modal -->
<div class="modal fade" id="acumular" tabindex="false" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h4>El Nro de Lote ya existe en el Deposito Seleccionado</h4>
                <h4>¿Desea acumular las cantidades?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="agregar()">Si</button>
            </div>
        </div>
    </div>
</div>