<style>
/*ESTILOS DEL SLIDER */
/* Label */
.checkboxtext {
    width: 100%
}
/* Caja del slider */
.switch {
  position: relative;
  display: inline-block;
  width: 40px;
  height: 20px;
}
/* Oculto caract nativas */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}
/* El slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}
.slider:before {
  position: absolute;
  content: "";
  height: 14px;
  width: 14px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}
input:checked+.slider {
  background-color: #2196F3;
}
input:focus+.slider {
  box-shadow: 0 0 1px #2196F3;
}
input:checked+.slider:before {
  -webkit-transform: translateX(19px);
  -ms-transform: translateX(19px);
  transform: translateX(19px);
}
/* Redondeo slider */
.slider.round {
  border-radius: 34px;
}
.slider.round:before {
  border-radius: 50%;
}
/** FIN ESTILOS SLIDER */
</style>
<style>
    #WindowLoad {
        position: fixed;
        top: 0px;
        left: 0px;
        z-index: 3200;
        filter: alpha(opacity=65);
        -moz-opacity: 65;
        opacity: 0.65;
        background: #ffffff;
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
                <div class="row" style="width: 100%">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top: 10px;">
                        <!-- ESTABLECIMIENTO -->
                        <div class="form-group col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            <label>Establecimiento</label>
                            <!--primero seleciono luego tipo de deposito y luego cargo depositos los mantengo bloqueados-->
                            <div class="input-group">
                                <select id="establecimiento" name="establecimiento" class="form-control"
                                    onchange="getDepositos(this)">
                                    <option value="TODOS" selected>TODOS</option>
                                    <?php 
                                foreach ($establecimientos as $key => $o) {
                                    echo "<option value='$o->esta_id'>$o->nombre</option>";
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <!-- /.form-group -->
                        <!-- ESTABLECIMIENTO --> 
                        <!-- DEPOSITO -->
                        <div class="form-group col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            <label>Depósito</label>
                            <select id="depositodescrip" name="depositodescrip" class="form-control" disabled>
                                <option value="" selected>TODOS</option>
                            </select>
                        </div>
                        <!-- /.form-group -->
                        <!-- DEPOSITO -->
                    
                        <!-- TIPO ARTICULO -->
                        <div class="form-group col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            <label>Tipo de Artículo</label>
                            <select id="artType" name="artType" class="form-control">
                                <option value="TODOS" selected>TODOS</option>
                                <?php 
                                foreach ($tipoArticulos as $key => $o) {
                                    echo "<option value='$o->tabl_id'>$o->valor</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <!-- ARTICULO -->
                        <div class="form-group col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            <label>Artículo</label>
                            <input list="articulos" id="inputarti" name="artBarCode" class="form-control" placeholder="Seleccionar Articulo" onchange="getItem(this)" autocomplete="off">
                            <div class="input-group">
                                <datalist id="articulos">
                                    <?php 
                                    
                                    foreach($items as $o)
                                    {
                                        // Muestra el artículo sin stock
                                        echo "<option value='" . $o->codigo . "' data-json='" . $o->json . "' class='form-control'>" . $o->descripcion . "</option>";
                                        unset($o->json);
                                    }
                                    ?>
                                </datalist>
                            </div>
                            <?php 
                                $usuario = $this->session->userdata();
                                if ($usuario['groupBpm'] != "Tierras_de_Capayan") {
                                    ?>
                                    <label id="info" class="text-blue"></label>
                                    <?php
                                }
                            ?>
                        </div>
                        <!-- /.form-group -->
                        <!-- ARTICULO -->
                        <div class="form-group col-xs-12 col-sm-2 col-md-2 col-lg-2" style="text-align: center;">
                            <label for="stock0" style="margin-bottom: 5px; line-height: 1.1; font-size: 13px;">Incluir artículos con stock en 0</label>
                            <br>
                            <label class="switch">
                                <input id="stock0" type="checkbox" value="0" name="stock0">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <!-- /.form-group -->
                        <div class="form-group col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">&nbsp;</label>
                            <button type="button" class="btn btn-success btn-flat col-xs-12 col-sm-6 col-md-6 col-lg-6"
                                onclick="filtrar()" style="padding: 6px 2px;">Filtrar</button>
                            <button type="button"
                                class="btn btn-danger btn-flat flt-clear col-xs-12 col-sm-6 col-md-6 col-lg-6"
                                onclick="limpiar()" style="padding: 6px 2px;">Limpiar</button>
                        </div>
                        <!-- /.form-group -->
                    </div>
                </div>
                <!-- /.row -->
            </form>
            <hr style="margin-top: 5px; margin-bottom: 5px;">
            <div class="box-body">
              <!-- carga la tabla -->
              <div class="table table-responsive" id="cargar_tabla"></div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
</div><!-- /.row -->
<script>
var articulosOriginales = [];

function getArticuloTipoId(dataJson) {
    if (!dataJson) return '';

    if (typeof dataJson === 'string') {
        try {
            dataJson = JSON.parse(dataJson);
        } catch (e) {
            return '';
        }
    }

    return String(
        dataJson.tiar_id ||
        dataJson.artType ||
        dataJson.arttype ||
        dataJson.type_id ||
        dataJson.tabl_id ||
        ''
    );
}

function filtrarArticulosPorTipo() {
    var tipoSeleccionado = String($('#artType').val() || 'TODOS');
    var $datalist = $('#articulos');

    $datalist.empty();

    $.each(articulosOriginales, function(index, item) {
        if (tipoSeleccionado === 'TODOS' || getArticuloTipoId(item.dataJson) === tipoSeleccionado) {
            var opcion = $('<option></option>');
            opcion.attr('value', item.codigo);
            opcion.attr('data-json', item.rawJson);
            opcion.text(item.descripcion);
            $datalist.append(opcion);
        }
    });

    $('#inputarti').val('');
    $('label#info').html('');
}

fechaMagic();
$('#datepickerDesde').val('');
$('#datepickerHasta').val('');

function jsRemoveWindowLoad() {
    $('#WindowLoad').remove();
}

$(document).ready(function() {
    $('#WindowLoad').remove();
    $(this).click(jsShowWindowLoad('Se esta Generando la Información'));

    setTimeout(function() {
        jsRemoveWindowLoad();
    }, 3000);

    $.ajax({
        url: 'index.php/core/Establecimiento/verificarDepositos',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.tieneDeposito) {
                Swal.fire('Ops!', 'No posee depósitos asignados. Comunicarse con el administrador.', 'warning');
            }
        },
        error: function() {
            console.error('Error al verificar los depósitos.');
        }
    });

    $('#articulos option').each(function() {
        var $opcion = $(this);
        articulosOriginales.push({
            codigo: $opcion.val(),
            descripcion: $opcion.text(),
            dataJson: $opcion.data('json'),
            rawJson: $opcion.attr('data-json')
        });
    });

    $('#artType').on('change', filtrarArticulosPorTipo);
    filtrarArticulosPorTipo();
});
</script>
<script type="text/javascript">
   
    // $('#btnGenerarBoleta').click(jsShowWindowLoad('Se realiza una operación'));
    function jsRemoveWindowLoad() {
        // eliminamos el div que bloquea pantalla
        $("#WindowLoad").remove();

    }

    function jsShowWindowLoad(mensaje) {
        //eliminamos si existe un div ya bloqueando
        jsRemoveWindowLoad();

        //si no enviamos mensaje se pondra este por defecto
        if (mensaje === undefined) mensaje = "Procesando la información<br/>Espere por favor";

        //centrar imagen gif
        height = 20; //El div del titulo, para que se vea mas arriba (H)
        var ancho = 0;
        var alto = 0;

        //obtenemos el ancho y alto de la ventana de nuestro navegador, compatible con todos los navegadores
        if (window.innerWidth == undefined) ancho = window.screen.width;
        else ancho = window.innerWidth;
        if (window.innerHeight == undefined) alto = window.screen.height;
        else alto = window.innerHeight;

        //operación necesaria para centrar el div que muestra el mensaje
        var heightdivsito = alto / 2 - parseInt(height) / 2; //Se utiliza en el margen superior, para centrar
        var url_imagen = '<?php echo base_url() ?>imagenes/yudica/loader.gif';
        
        //imagen que aparece mientras nuestro div es mostrado y da apariencia de cargando
        imgCentro = "<div style='text-align:center;height:" + alto + "px;'><div  style='color:#000;margin-top:" + heightdivsito + "px; font-size:20px;font-weight:bold'>" + mensaje + "</div><img  src="+url_imagen+"></div>";

        //creamos el div que bloquea grande------------------------------------------
        div = document.createElement("div");
        div.id = "WindowLoad"
        div.style.width = ancho + "px";
        div.style.height = alto + "px";
        $("body").append(div);

        //creamos un input text para que el foco se plasme en este y el usuario no pueda escribir en nada de atras
        input = document.createElement("input");
        input.id = "focusInput";
        input.type = "text"

        //asignamos el div que bloquea
        $("#WindowLoad").append(input);

        //asignamos el foco y ocultamos el input text
        $("#focusInput").focus();
        $("#focusInput").hide();

        //centramos el div del texto
        $("#WindowLoad").html(imgCentro);

	}
	
</script>



<script>
    $("#cargar_tabla").load("<?php echo base_url(ALM) ?>Lote/Listar_tabla_stock");
//Filtra la tabla y la redibuja
//Cada campo esta validado en caso de vacios o NULL no se muestren en la tabla


$(document).ready(function(){
    $('#stock0').click(function () {    
        // Ya no bloqueamos los filtros al marcar "Incluir artículos con stock en 0"
        // Se mantiene la lógica de visualización del checkbox
    });
});

function filtrar() {
    var depositodescrip =   _isset($("#depositodescrip").val()) ? $("#depositodescrip").val() : '';
    
    $("#WindowLoad").remove();
    jsShowWindowLoad('Se está Generando la Información');

    if ($.fn.DataTable.isDataTable('#stock')) {
        $('#stock').DataTable().ajax.reload(function(json) {
            if(_isset(depositodescrip)){
                $("#cantidadLotesDeposito").text(json.recordsFiltered);
                $("#nombreLotesDeposito").text($('#depositodescrip option:selected').text());
            }
            jsRemoveWindowLoad();
        });
    } else {
        $("#cargar_tabla").load("<?php echo base_url(ALM) ?>Lote/Listar_tabla", function() {
            jsRemoveWindowLoad();
        });
    }
};

function limpiar() {
    // Eliminamos la lógica de habilitar campos que estaban bloqueados por el checkbox de stock 0

    $("#depositodescrip").val('');
    $("#artDescription").val('');
    $("#artBarCode").val('');
    $("#fec_alta").val('');
    $("#artType").val('TODOS');
    $("#inputarti").val('');
    $("label#info").html('');
    $("#establecimiento").val('TODOS');
    $('#datepickerDesde').val('');
    $('#datepickerHasta').val('');
    if ($('#stock0').prop("checked", true)) {
        console.log("Checkbox stock0 limpiado");
        $('#stock0').prop("checked", false);   
    }

    filtrarArticulosPorTipo();

    //Deshabilito deposito
    $("#depositodescrip").prop('disabled', 'disabled');

    // Vaciar tabla
    $("#cargar_tabla").empty();
    $("#cantidadLotesDeposito").text("-");
    $("#nombreLotesDeposito").text("");
}

function getItem(item) {
    if (item == null) return;
    var option = $('#articulos').find("[value='" + item.value + "']");
    var json = JSON.stringify($(option).data('json'));
    selectItem = JSON.parse(json);
    $('label#info').html($(option).html());
    if (existFunction('eventSelect')) eventSelect();
}

function getDepositos(item) {
    $("#depositodescrip").empty().append('<option value="TODOS" selected>TODOS</option>');
    if (item == null || item.value == 'TODOS') {
        $("#depositodescrip").prop('disabled', 'disabled');
        return;
    }

    var esta_id = item.value;
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
                $.each(resp, function(index, value) {
                    var opc = document.createElement('option');
                    opc.value = value.depo_id;
                    opc.innerHTML = value.descripcion;
                    $("#depositodescrip").append(opc);
                });
            }
            $("#depositodescrip").prop('disabled', '');
            wc();
        },
        complete: function() {
            wc();
        }
    });
}


$(document).ready(function() {
    // Ejecutar getDepositos cuando se carga la página
    var establecimiento = document.getElementById('establecimiento');
    if (establecimiento) {
        getDepositos(establecimiento);
    }
});
</script>
