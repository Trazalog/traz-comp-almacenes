<div class="row">
    <div class="col-xs-12">
        <div class="alert alert-danger alert-dismissable" id="error" style="display: none">
            <h4><i class="icon fa fa-ban"></i> Error!</h4>
            Revise que todos los campos obligatorios esten seleccionados
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="alert alert-danger alert-dismissable" id="error1" style="display: none">
            <h4><i class="icon fa fa-ban"></i> </h4>
            El artículo y depósito seleccionado no están en Lote.
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="alert alert-success" id="error2" style="display: none">
            <h4></h4>
            El artículo y depósito seleccionado están en Lote.
        </div>
    </div>
</div>

<section>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"> Recepción de Materiales</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default">

                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <label for="comprobante">Comprobante <strong
                                                    style="color: #dd4b39">*</strong> :</label>
                                            <input type="text" placeholder="Ingrese Numero..." class="form-control"
                                                id="comprobante" name="comprobante">
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <label for="fecha">Fecha <strong style="color: #dd4b39">*</strong> :</label>
                                            <input type="text" class="form-control fecha" id="fecha" name="fecha">
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <label for="proveedor">Proveedor <strong style="color: #dd4b39">*</strong>
                                                :</label>
                                            <select id="proveedor" name="proveedor" class="form-control"></select>
                                        </div>
                                    </div><br>

                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#insum" aria-controls="home"
                                                role="tab" data-toggle="tab" class="fa fa-file-text-o icotitulo">
                                                Insumos</a></li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="insum">
                                            <div class="row"><br>
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <label for="art_select">Seleccionar Artículo <strong
                                                            style="color: #dd4b39">*</strong> :</label>
                                                    <div class="input-group">
                                                        <div class="input-group-btn">
                                                            <button type="button" class="btn btn-primary"
                                                                onclick="$('#articulos').modal('show');">Artículos</button>
                                                        </div>
                                                        <input type="text" id="art_select" class="form-control"
                                                            disabled>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-3 col-md-3"><br>
                                                    <label for="lote">Lote <strong
                                                            style="color: #dd4b39">*</strong>:</label>
                                                    <input type="text" id="lote" name="lote" placeholder="Lote"
                                                        class="form-control">
                                                </div>
                                                <div class="col-xs-12 col-sm-3 col-md-3"><br>
                                                    <label for="vencimiento">Fecha Vencimiento <strong
                                                            style="color: #dd4b39">*</strong>:</label>
                                                    <input type="text" id="vencimiento" name="vencimiento"
                                                        placeholder="dd/mm/yyyy" class="form-control fecha">
                                                </div>
                                                <div class="col-xs-12 col-sm-3 col-md-3"><br>
                                                    <label for="cantidad">Cantidad <strong
                                                            style="color: #dd4b39">*</strong> :</label>
                                                    <input type="number" id="cantidad" name="cantidad"
                                                        placeholder="Ingrese Cantidad..." class="form-control">
                                                </div>
                                                <div class="col-xs-12 col-sm-3 col-md-3"><br>
                                                    <label for="deposito">Depósito <strong
                                                            style="color: #dd4b39">*</strong> :</label>
                                                    <select id="deposito" name="deposito" class="form-control"></select>
                                                </div>
                                                <div class="col-xs-12 col-sm-3 col-md-3"><br>
                                                    <br>
                                                    <button type="button" class="btn btn-primary" id="agregar"><i
                                                            class="fa fa-check">Agregar</i></button>
                                                </div>
                                            </div><br>

                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <table class="table table-bordered" id="tablainsumo">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Lote</th>
                                                                <th>Fec. Vencimiento</th>
                                                                <th>Código</th>
                                                                <th>Descripción</th>
                                                                <th>Cantidad</th>
                                                                <th>Depósito</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div><!-- /. tab-pane -->
                                    </div><!-- /.tab-content -->
                                </div> <!-- /.panel-body -->

                            </div>
                        </div>
                    </div>
                </div><!-- /.box-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-default delete" onclick="limpiar()">Cancelar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal"
                        onclick="guardar()">Guardar</button>
                </div> <!-- /.modal footer -->

            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->
<script>
var idslote = {};
var j = 0;

$("#fecha").datetimepicker({
    format: 'YYYY-MM-DD HH:mm:ss',
    locale: 'es',
});

$("#vencimiento").datetimepicker({
    format: 'YYYY-MM-DD',
    locale: 'es',
});


// autocomplete para codigo
var dataF = function() {
    var tmp = null;
    $.ajax({
        'async': false,
        'type': "POST",
        'global': false,
        'dataType': 'json',
        'url': "index.php/almacen/Remito/getcodigo",
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
        // manually update the textbox and hidden field
        //$(this).val(ui.item.value);//label
        $("#codigo").val(ui.item.label); //value
        $("#descripcion").val(ui.item.artDescription);
        $("#id_herr").val(ui.item.value);
        //traer_deposito(ui.item.value);
        //console.log("id articulo de orden insumo: ")
        //console.log(ui.item.value);
    },
});

traer_deposito();

function traer_deposito() {
    $('#deposito').empty();
    $.ajax({
        type: 'POST',
        data: {},
        url: 'index.php/almacen/Remito/getdeposito',
        success: function(data) {
            var opcion = "<option value='-1'>Seleccione...</option>";
            $('#deposito').append(opcion);
            for (var i = 0; i < data.length; i++) {
                var nombre = data[i]['descripcion'];
                var opcion = "<option value='" + data[i]['depo_id'] + "'>" + nombre + "</option>";
                $('#deposito').append(opcion);
            }
        },
        error: function(result) {
            console.log("entre por el error en deposito");
            //alert("este articulo no está en deposito");
            console.log(result);
        },
        dataType: 'json'
    });
}

traer_proveedor();

function traer_proveedor() { // Ok
    $('#proveedor').empty();
    $.ajax({
        type: 'POST',
        data: {},
        url: 'index.php/almacen/Remito/getproveedor',
        success: function(data) {
            var opcion = "<option value='-1'>Seleccione...</option>";
            $('#proveedor').append(opcion);
            for (var i = 0; i < data.length; i++) {
                var nombre = data[i]['nombre'];
                var opcion = "<option value='" + data[i]['prov_id'] + "'>" + nombre + "</option>";
                $('#proveedor').append(opcion);
            }
        },
        error: function(result) {
            console.error("entre por el error en proveedor");

        },
        dataType: 'json'
    });
}

function traer_lote(id_her, id_deposito) {

    $.ajax({
        type: 'POST',
        data: {
            id_her: id_her,
            id_deposito: id_deposito
        },
        url: 'index.php/almacen/Remito/getlote', //index.php/
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
    $("#deposito").val("");
    $('#tablainsumo tbody tr').remove();
}




//agrega insumos a la tabla detainsumos
var i = 1;
$('#agregar').click(function(e) {

    if(!validar_campos()){ alert('Campos Obligatorios(*) Incompletos'); return;}

    var lote = $('#lote').val();
    var vencimiento = $('#vencimiento').val();
    var codigo = seleccion_art.label;
    var id_her = seleccion_art.value;
    var descripcion = seleccion_art.artDescription;
    var cantidad = $('#cantidad').val();
    var deposito = $("select#deposito option:selected").html();
    var id_deposito = $('#deposito').val();

    if(id_her == '' || lote == '' || vencimiento=='' || cantidad=='' || id_deposito==-1){alert('Campos Obligatorios(*) Incompletos'); return;}

    var json = {
       // lote_id: (seleccion_art.es_loteado == 0 ? 1 : lote),
        fec_vencimiento: vencimiento,
        arti_id: id_her,
        loteado: seleccion_art.es_loteado,
        codigo: seleccion_art.es_loteado == 0 ? 1 : lote,
        cantidad: (cantidad * (seleccion_art.es_caja == 1 ? seleccion_art.cantidad_caja : 1)),
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

        if (seleccion_art.es_loteado == 0) {
            $('#lote').prop('disabled', true);
            $('#lote').val('S/L');
        }
    }
});

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

    $.ajax({
        type: 'POST',
        data: {
            info,
            detalles
        },
        url: 'index.php/almacen/Remito/guardar_mejor', //index.php/
        success: function(data) {

            regresa();
        },
        error: function(result) {
            alert('Error');
        }
    });
}

function regresa() {
    WaitingOpen();
    $('#content').empty();
    $("#content").load("<?php echo base_url(); ?>index.php/almacen/Remito/index/<?php echo $permission; ?>");
    WaitingClose();
}

var seleccion_art = '';

function seleccion_articulo(e) {
    traer_deposito($(e).data('id'));
    seleccion_art = JSON.parse(JSON.stringify($(e).data('json')));
    $('#art_select').val($(e).find('a').html());
    $('#articulos').modal('hide');
}

function get_info_remito() {

    if ($('#fecha').val() == '' || $('#comprobante').val() == '' || $('#proveedor').val() == -1) {
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
    return !($('#fecha').val() == '' || $('#comprobante').val() == '' || $('#proveedor').val() == -1) 
}
</script>

<div class="modal" id="articulos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <div class="input-group input-group-sm">
                    <input id="myInput" type="text" class="form-control" onkeyup="filtrar()"
                        placeholder="Buscar Artículos...">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-primary btn-flat"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </div> <!-- /.modal-header  -->

            <div class="modal-body" id="modalBodyArticle">

                <ul id="myUL" class="nav nav-pills nav-stacked">

                    <?php

                    foreach ($list as $o) {
                        echo '<li onclick="seleccion_articulo(this)" data-id="' . $o['value'] . '" data-json=\'' . json_encode($o) . '\'><a href="#">' . $o['label'] . ' | ' . $o['artDescription'] . '</a></li>';
                    }

                    ?>

                </ul>
            </div> <!-- /.modal-body -->

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="$('#articulos').modal('hide');">Listo</button>
            </div> <!-- /.modal footer -->

        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog modal-lg -->
</div> <!-- /.modal fade -->

<script>
function seleccion_articulo(e) {
    seleccion_art = JSON.parse(JSON.stringify($(e).data('json')));
    $('#art_select').val($(e).find('a').html());
    $('#articulos').modal('hide');
    if (seleccion_art.es_loteado == 0) {
        $('#lote').prop('disabled', true);
        $('#lote').val('S/L');
    }else{
        $('#lote').prop('disabled', false);
        $('#lote').val('');
    }
    traer_deposito($(e).data('id'));
}
</script>