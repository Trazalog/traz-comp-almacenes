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
                                    
                                 $table = $o->tabl_id;
                               
                                  $table_str = str_replace(" ", "%20", $table);
                                
                                echo "<option value='$table_str'>$o->valor</option>";
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
                                        id="stock0" name="stock0" />
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
              <!-- carga la tabla -->
              <div class="table table-responsive" id="cargar_tabla"></div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
</div><!-- /.row -->
<script>


function jsRemoveWindowLoad() {
        // eliminamos el div que bloquea pantalla
        $("#WindowLoad").remove();

    }
$( document ).ready(function() {
    $("#WindowLoad").remove();
    $(this).click(jsShowWindowLoad('Se esta Generando la Información'));
    setTimeout(() => {
        jsRemoveWindowLoad();
    }, 3000);
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
        console.log(url_imagen);
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
    $("#cargar_tabla").load("<?php echo base_url(ALM) ?>Lote/Listar_tabla");
//Filtra la tabla y la redibuja
//Cada campo esta validado en caso de vacios o NULL no se muestren en la tabla


$(document).ready(function()
{
         $('#stock0').click(function () {    
           
 if ($('#stock0').prop('checked') ) {
 
    $("#establecimiento").prop('disabled',true);
    $("#depositodescrip").prop('disabled',true);
    $("#fec_alta").prop('disabled',true);
      $("#artType").prop('disabled',true);
      $("#inputarti").prop('disabled',true);
      

} else{

    $("#establecimiento").prop('disabled',false);
    $("#artType").prop('disabled',false);
    $("#fec_alta").prop('disabled',false);
    $("#inputarti").prop('disabled',false);

}
         });
});

if ($('#stock0').prop('checked') ) {
    $("#establecimiento").prop('disabled',true);
    
    $("#depositodescrip").prop('disabled', );
}else{
    var stock0 = '';  
}

function filtrar() {
    debugger;

    var nom_reci =  $("#nom_reci").val();
    var depositodescrip =   $("#depositodescrip").val();
    var artDescription =  $("#artDescription").val();
    var fec_alta =  $("#fec_alta").val();

    var artType = $("#artType").val();

    var artBarCode =   $("#inputarti").val();
    var establecimiento = $("#establecimiento").val();
    var tipo_deposito = $('#tipo_deposito').val();

    if ($('#stock0').prop('checked') ) {
        console.log("Checkbox stock0 seleccionado");
        var stock0 = $('#stock0').val();   
    }else{
        var stock0 = '';  
    }


$("#WindowLoad").remove();
$(this).click(jsShowWindowLoad('Se está Generando la Información'));

    var url1 = "<?php echo base_url(ALM) ?>Lote/filtrarListado?nom_reci="+nom_reci+"&depositodescrip="+depositodescrip+"&artDescription="+artDescription+"&artBarCode="+artBarCode+"&fec_alta="+fec_alta+"&artType="+artType+"&establecimiento="+establecimiento+"&tipo_deposito="+tipo_deposito+"&stock0="+stock0;
    $.ajax({
        type: 'POST',

        data: stock0,

        url: '<?php echo base_url(ALM) ?>Lote/buscador',
        success: function(data) {
      
           
            $("#cargar_tabla").load(url1);

           
        },
        error: function() {
            alert('Ha ocurrido un error');
        },
        complete : function(data) {
                   
                   setTimeout(() => {
                       jsRemoveWindowLoad();
                   }, 5000);
                   
               }
       
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
if( $("#establecimiento").prop('disabled',true) || $("#artType").prop('disabled',true) ||  $("#fec_alta").prop('disabled',true ) ||  $("#inputarti").prop('disabled',true)){
    $("#establecimiento").prop('disabled',false);
    $("#artType").prop('disabled',false);
    $("#fec_alta").prop('disabled',false);
    $("#inputarti").prop('disabled',false);
}

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
    if ($('#stock0').prop("checked", true)) {
    console.log("Checkbox stock0 limpiado");
     $('#stock0').prop("checked", false);   
}
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