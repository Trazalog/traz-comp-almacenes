<?php
  use \koolreport\widgets\koolphp\Table;
  use \koolreport\widgets\google\ColumnChart;
?>

<div id="reportContent" class="report-content">
    <div class="box box-primary">

        <div class="box-header with-border">
            <div class="box-tittle">
                <h4>Movimientos de Stock</h4>
            </div>
        </div>

        <div class="box-body">

            <!-- _____ GRUPO 1 _____ -->
            <div class="col-md-12">

                <div class="form-group">

                    <div class="col-md-4 col-md-6 mb-4 mb-lg-0">
                        <label style="padding-left: 20%;">Desde <strong class="text-danger">*</strong> :</label>
                        <div class="input-group date">
                            <a class="input-group-addon" id="daterange-btn" title="Más fechas">
                                <i class="fa fa-magic"></i>
                                <span></span>
                            </a>
                            <input type="date" class="form-control pull-right" id="datepickerDesde"
                                name="datepickerDesde" placeholder="Desde">
                        </div>
                    </div>


                    <div class="col-md-4 col-md-6 mb-4 mb-lg-0">
                        <label>Hasta <strong class="text-danger">*</strong> :</label>
                        <div class="input-group date">
                            <input type="date" class="form-control" id="datepickerHasta" name="datepickerHasta"
                                placeholder="Hasta">
                            <a class="input-group-addon" style="cursor: pointer;" onclick="filtro()"
                                title="Más filtros">

                            </a>
                        </div>
                    </div>

                    <div class="col-md-4 col-md-6 mb-4 mb-lg-0">
                        <label for="tipoajuste" class="form-label">Tipo Movimiento <strong
                                class="text-danger">*</strong> :</label>
                        <select class="form-control select2 select2-hidden-accesible" id="tipoajuste" name="tipoajuste">
                            <option value="-1" disabled selected>-Seleccione-</option>
                            <option value="TODOS">Todos</option>
                            <option value="INGRESO">Ingreso</option>
                            <option value="EGRESO">Egreso</option>
                            <option value="MOV.ENTRADA">Movimiento de Entrada</option>
                            <option value="MOV.SALIDA">Movimiento de Salida</option>
                            <option value="AJUSTE">Ajuste</option>
                            <option value="ETAPAPRODINGRESO">Etapa Prod Ingresos</option> <!-- produccion caso 1 -->
                            <option value="ENPROCESOENETAPA">Prod En Proceso Etapa</option> <!-- produccion caso 2 -->
                            <option value="DESCENPROCESO">Desc. en Proceso</option> <!-- produccion caso 3 -->
                        </select>

                    </div>

                </div>

            </div>
            <!-- _____ GRUPO 1 _____ -->

            <div class="col-md-12">
                <br>
            </div>

            <!-- _____ GRUPO 2 _____ -->
            <div class="col-md-12">

                <div class="form-group">

                    <div class="col-md-4 col-md-6 mb-4 mb-lg-0">
                        <label for="establecimiento" class="form-label">Establecimiento <strong
                                class="text-danger">*</strong> :</label>
                        <select onchange="seleccionesta(this)" class="form-control select2 select2-hidden-accesible"
                            id="establecimiento" name="establecimiento">
                            <?php
                                $first = true;
                                foreach ($establecimientos as $est) {
                                    $selected = $first ? 'selected' : '';
                                    echo '<option value="'.$est->esta_id.'" '.$selected.'>'.$est->nombre.'</option>';
                                    $first = false;
                                }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4 col-md-6 mb-4 mb-lg-0 habilitado">
                        <label for="depo_id" class="form-label">Depósito:</label>
                        <select class="form-control select2 select2-hidden-accesible" id="depo_id" name="depo_id" />
                    </div>

                    <div class="col-md-4 col-md-6 mb-4 mb-lg-0 habilitado">
                        <label for="zona" class="form-label">Artículo:</label>
                        <div id="list_articulos"> </div>
                    </div>

                </div>
            </div>

            <div class="col-md-12">

                <div class="form-group">

                    <div class="col-md-4 col-md-6 mb-4 mb-lg-0 habilitado">
                        <label for="lote_id" class="form-label">Lote:</label>
                        <select class="form-control select2 select2-hidden-accesible" id="lote_id" name="lote_id"
                            disabled />
                    </div>

                </div>

            </div>
            <!-- _____ GRUPO 2 _____ -->

            <div class="col-md-12">
                <br>
            </div>


            <div class="form-group col-xs-12">
                <div class="form-group">
                    <button type="button" class="btn btn-success btn-flat col-xs-12 col-sm-3 col-md-3 col-lg-3"
                        onclick="filtrar()" style="float: right !important;">Filtrar</button>
                </div>
            </div>

            <!-- MODAL VER DETALLE AJUSTE -->
            <div class="modal fade bs-example-modal" id="modalInfoAjuste" tabindex="-1" role="dialog"
                aria-labelledby="myLargeModalLabel">

                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">

                        <div class="modal-header bg-blue">
                            <button type="button" class="close close_modal_edit" data-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true" style="color:white;">&times;</span>
                            </button>

                            <h4 class="modal-title" id="myModalLabel"><span class="fa fa-fw fa-search"></span> Detalle
                                Stock </h4>
                        </div>

                        <div class="modal-body ">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="idAjuste">Id: </label>
                                    <input type="text" class="form-control habilitar" name="idAjuste"
                                        id="idAjuste" readonly>
                                </div>
                                <div class="col-sm-6">
                                    <label for="tipoAjuste" class="control-label">Tipo de Ajuste:</label>
                                    <input type="text" class="form-control habilitar" name="tipoAjuste"
                                        id="tipoAjuste" readonly>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                    <hr style="height:3px;border-width:0;color:gray;background-color:#dd4b39">
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                  <label for="justificacion">Justificación:</label>
                                  <textarea style="resize:none" type="text" class="form-control input-sm" id="justificacion" name="justificacion" readonly></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">

                            <div class="form-group text-right">
                                <button type="" class="btn btn-default cerrarModalEdit" 
                                    data-dismiss="modal">Cerrar</button>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
            <!-- FIN MODAL VER DETALLE AJUSTE -->

            <!-- MODAL VER DETALLE MOVIMIENTO INTERNO -->
            <div class="modal fade bs-example-modal" id="modalInfoMovimiento" tabindex="-1" role="dialog"
                aria-labelledby="myLargeModalLabel">

                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">

                        <div class="modal-header bg-blue">
                            <button type="button" class="close close_modal_edit" data-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true" style="color:white;">&times;</span>
                            </button>

                            <h4 class="modal-title" id="myModalLabel"><span class="fa fa-fw fa-paperclip"></span> Detalle
                                Movimiento Interno </h4>
                        </div>

                        <div class="modal-body ">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="demiIdMovimiento">ID: </label>
                                    <input type="text" class="form-control" name="demiIdMovimiento"
                                        id="demiIdMovimiento" readonly>
                                </div>
                                <div class="col-sm-4">
                                    <label for="cantidadCargada">Cantidad Cargada: </label>
                                    <input type="text" class="form-control" name="cantidadCargada"
                                        id="cantidadCargada" readonly>
                                </div>
                                <div class="col-sm-4">
                                    <label for="cantidadRecibida">Cantidad Recibida: </label>
                                    <input type="text" class="form-control" name="cantidadRecibida"
                                        id="cantidadRecibida" readonly>
                                </div>
                                <div class="col-sm-12" id="justificacionContainer" style="display: none;">
                                    <label for="justificacionMovimiento" class="control-label">Justificación:</label>
                                    <textarea style="resize:none" type="text" class="form-control input-sm" id="justificacionMovimiento" name="justificacionMovimiento" readonly></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">

                            <div class="form-group text-right">
                                <button type="" class="btn btn-default" 
                                    data-dismiss="modal">Cerrar</button>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
            <!-- FIN MODAL VER DETALLE MOVIMIENTO INTERNO -->

            <!--_______ TABLA _______-->
            <div class="col-md-12">
                <?php
        Table::create(array(
          "dataStore" => $this->dataStore('data_historico_table'),
          // "themeBase" => "bs4",
          // "showFooter" => true, // cambiar true por "top" para ubicarlo en la parte superior
          // "headers" => array(
          //   array(
          //     "Reporte de Producción" => array("colSpan" => 6),
          //     // "Other Information" => array("colSpan" => 2),
          //   )
          // ), // Para desactivar encabezado reemplazar "headers" por "showHeader"=>false
          // "showHeader" => false,

          "columns" => array(
            array(
              "label" => "Acciones",
              "value" => function($row) {
                if (isset($row['tipo_mov']) && $row['tipo_mov'] == 'MOV.SALIDA') {
                  return '<i class="fa fa-print" style="cursor: pointer; margin: 3px;" title="Imprimir Remito" onclick="modalReimpresion(this)"></i>';
                } elseif (isset($row['tipo_mov']) && $row['tipo_mov'] == 'AJUSTE') {
                  /* si es ajuste muestra la lupa */
                  return '<i class="fa fa-search" style="cursor: pointer; margin: 3px;" title="Ver Ajuste Stock" onclick="verAjuste(' . $row['referencia'] . ')"></i>';
                }
                elseif (isset($row['tipo_mov']) && $row['tipo_mov'] == 'MOV.ENTRADA') {
                    /* si es ajuste muestra la lupa */
                    return '<i class="fa fa-paperclip" style="cursor: pointer; margin: 3px;" title="Ver detalle movimiento" onclick="clipMovimiento(' . $row['referencia'] . ')"></i>';
                  }
                else{
                  return ''; // No mostrar nada si no es "MOV.SALIDA"
                }
              },
            "cssClass" => "text-center" // Centrar la columna de acciones
            ),
            "referencia" => array(
              "label" => "Referencia"
            ),
            "codigo" => array(
              "label" => "Cod. Artículo"
            ),
            "descripcion" => array(
              "label" => "Descrip."
            ),
            "lote" => array(
              "label" => "Lote"
            ),
            "cantidad" => array(
              "label" => "Cantidad"
            ),
            "stock_actual" => array(
              "label" => "Stock"
            ),
            "deposito" => array(
              "label" => "Depósito"
            ),
            array(
              "label" => "Fecha",
              "value" => function($row) {
                $aux = explode("T",$row["fec_alta_formatted"]);
                $row["fec_alta_formatted"] = date("d-m-Y",strtotime($aux[0]));
                return $row["fec_alta_formatted"];
              },
              "type" => "date"
            ),
            "tipo_mov" => array(
              "label" => "Tipo Movim."
            )
          ),
          "cssClass" => array(
            "table" => "table-scroll table-responsive dataTables_wrapper form-inline dt-bootstrap dataTable table table-bordered table-striped table-hover display",
            "th" => "sorting"
          ),
        ));
        ?>
            </div>
            <div id="acciones" class="" style="float: right !important;">
                <button type="button" class="btn btn-primary" onclick="exportarExcel()">Exportar</button>
            </div>
        </div>
    </div>
</div>

<!-- modal de reimpresion remito -->
<div id="modalContainer"></div>

<script>
//variables que van a mantener el estado para poder generar el excel
var fec1;
var fec2;
var tpoMov;
var esta;
var depo;
var artic;
var lote;

// carga select de Establecimientos, componente Articulos y llama configuracion selects de fecha
$(function() {
    $(".habilitado").hide();
    wo();
    $("#list_articulos").load("<?php echo base_url(ALM); ?>Reportes/cargaArticulos");
    getEstablecimientos();
    fechaMagic();
    //getTipoAjuste();
    wc();
});

// config de daterangepicker
function fechaMagic() {
    $('#daterange-btn').daterangepicker({
            ranges: {
                'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                'Este mes': [moment().startOf('month'), moment().endOf('month')],
                'Último mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                    'month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment()
        },
        function(start, end) {
            $('#datepickerDesde').val(start.format('YYYY-MM-DD'));
            $('#datepickerHasta').val(end.format('YYYY-MM-DD'));
        }
    );
}

// llena select Establecimientos
function getEstablecimientos() {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        data: {},
        url: 'index.php/<?php echo ALM?>Reportes/getEstablecimientos',
        success: function(data) {
            $('#establecimiento').empty();
            if (data != null) {
                for (var i = 0; i < data.length; i++) {
                    var selected = i === 0 ? 'selected' : '';
                    $('#establecimiento').append("<option value='" + data[i].esta_id + "' " + selected + ">" + data[i].nombre + "</option>");
                }
                // Ejecutar seleccionesta con el primer elemento
                seleccionesta(document.getElementById('establecimiento'));
            } else {
                $("#establecimiento").append("<option value=''>-Sin Establecimientos-</option>");
            }
            WaitingClose();
        },
        error: function(data) {
            alert('Error');
        }
    });
}

// carga los depositos de acuerdo a establecimiento
function seleccionesta(opcion) {
    $(".habilitado").show();
    wo();
    var id_esta = $("#establecimiento").val();
    var depo_id = $("#depo_id").val();

    $('#lote_id').append('<option value="TODOS">Todos</option>'); // En caso que no seleccione articulo

    $.ajax({
        type: 'POST',
        data: {
            id_esta,
            depo_id
        },
        url: 'index.php/<?php echo ALM?>Reportes/traerDepositos',
        success: function(data) {
            var resp = JSON.parse(data);
            $('#depo_id').empty();
            $("#depo_id").append("<option value='TODOS'>Todos</option");
            if (data != null) {
                for (var i = 0; i < resp.length; i++) {
                    $('#depo_id').append("<option value='" + resp[i].depo_id + "'>" + resp[i].descripcion + "</option>");
                }
                $("#depo_id").removeAttr('readonly');
            } else {
                $("#depo_id").append("<option value=''>-Sin Depósitos para este Establecimiento-</option>");
            }
            wc();
        },
        error: function(data) {
            wc();
            alert('Error');
        }
    });
}

// trae lotes por id de deposito y de articulo
$("body").on('change', '#inputarti', function() {

    var depo_id = $('#depo_id option:selected').val();
    var arti_id = selectItem.arti_id; // se completa en traz-comp-almacen/articulo/componente.php
    if (depo_id == "") {
        alert('Por favor seleccione deposito...');
        return;
    }
    wo();

    $.ajax({
        type: 'POST',
        data: {
            arti_id: arti_id,
            depo_id: depo_id
        },
        url: 'index.php/<?php echo ALM?>Reportes/traerLotes',
        success: function(data) {

            $('#lote_id').empty();
            var resp = JSON.parse(data);
            if (resp == null) {
                $('#lote_id').append(
                    '<option value="" disabled selected>-Sin Lotes para este artículo-</option>'
                );
            } else {
                console.table(resp);
                console.table(resp[0].lote_id);
                // $('#lote_id').append('<option value="" disabled selected>-Seleccione opcion-</option>');
                $('#lote_id').append('<option value="TODOS">Todos</option>');
                for (var i = 0; i < resp.length; i++) {
                    $('#lote_id').append("<option value='" + resp[i].lote_id + "'>" + resp[i]
                        .codigo + "</option");
                }
                $("#lote_id").removeAttr('disabled');
            }
            wc();
        },
        error: function(data) {
            alert('Error');
            wc();
        }
    });
});

// llena select tipo ajuste
function getTipoAjuste() {

    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: 'index.php/<?php echo ALM?>general/Tipoajuste/obtenerAjuste',
        success: function(result) {

            if (!result.status) {
                alert("fallo");
                return;
            }
            result = result.data;
            var option_ajuste = '<option value="" disabled selected>-Seleccione opcion-</option>';
            for (let index = 0; index < result.length; index++) {
                option_ajuste += '<option value="' + result[index].nombre + '" data="' + result[index]
                    .tipo + '">' + result[index].nombre + '</option>';
            }
            $('#tipoajuste').html(option_ajuste);
        },
        error: function() {
            alert('Error');
        }
    });
}

// filtrado de datos
function filtrar() {


    // wo();
    var data = {};
    data.desde = $("#datepickerDesde").val();
    fec1 = $("#datepickerDesde").val();
    data.hasta = $("#datepickerHasta").val();
    fec2 = $("#datepickerHasta").val();
    data.tipo_mov = $("#tipoajuste>option:selected").val();
    tpoMov = $("#tipoajuste>option:selected").val();
    data.esta_id = $("#establecimiento").val();
    data.depo_id = $("#depo_id").val();
    depo = $("#depo_id").val();
    data.lote_id = $("#lote_id>option:selected").val();
    lote = $("#lote_id>option:selected").val();

    inputarti = $("#inputarti").val();
    establecimiento = $("#establecimiento").val();
    if (inputarti) {
        data.arti_id = selectItem.arti_id; // se completa en traz-comp-almacen/articulo/componente.php
        artic = selectItem.arti_id;
    } else {
        data.arti_id = 'TODOS';
    }

    if (fec1 == '' || fec2 == '' || tipoajuste == '' || establecimiento == '') {
        Swal.fire(
            'Error...',
            'Debes completar los campos Obligatorios (*)',
            'error'
        );
        return;
    }
    wo();
    $.ajax({
        type: 'POST',
        data: {
            data
        },
        url: '<?php echo base_url(ALM) ?>Reportes/historicoArticulos',
        success: function(result) {
            wc();
            debugger;
            $('#reportContent').empty();
            $('#reportContent').html(result);

            // Verificar si el texto "No data available" está en el <tbody> 
            let isEmpty = $('#reportContent table tbody').text().trim() === "No data available in table";

            if (isEmpty) {
                Swal.fire('Aviso', 'No hay resultados para mostrar con los filtros aplicados.', 'info');
            }
            //   wc();
        },
        error: function() {
            alert('Ha ocurrido un error, por favor comunicarse con su proveedor de servicio. Gracias!');
            wc();
        },
        complete: function(result) {
            wc();
        }
    });
}

function exportarExcel() {
    window.open("<?php echo base_url(ALM); ?>Reportes/exportarExcelHistorico?fec1=" + fec1 + "&fec2=" + fec2 +
        "&depo=" + depo + "&arti=" + artic + "&tpoMov=" + tpoMov + "&lote=" + lote);
}

/* Funciones para reimprimir Remito de movimiento interno */

// Función asíncrona para mostrar el modal de reimpresión de Remito
async function modalReimpresion(element) {
    try {
        wo();
        var fila = $(element).closest('tr');
        var celdas = fila.find('td');

        // Esperar a que los datos de la empresa se carguen
        var dataEmpresa = await DatosEmpresaRemito();

        // referencia es el demi_id de movimientos_internos
        var referencia = celdas.eq(1).text().trim();

        // Llamar a la función que obtiene los datos del movimiento de remito
        var dataRemito = await datosMovimientoRemito(referencia);

        // Realizar la llamada AJAX para cargar el modal
        $.ajax({
            url: '<?php echo base_url(ALM); ?>Reportes/modalReImpresion',
            type: 'GET',
            success: function(response) {

                console.log(dataRemito);
                $('#modalContainer').html(response); // Cargar el modal en el contenedor
                $('#modalRemito').modal('show');

                // Asignar los datos de la empresa al modal
                document.getElementById('logo_remito').src = dataEmpresa.logo.valor;
                $('#direccion_remito').html('<small>' + dataEmpresa.direccion.valor + '</small>');
                $('#telefono_remito').html('<small>' + dataEmpresa.telefono.valor + '</small>');
                $('#email_remito').html('<small>' + dataEmpresa.email.valor + '</small>');
                $('#texto_pie_remito').html('<strong>' + dataEmpresa.texto_pie_remito.valor +
                    '</strong>');

                // Asignar los datos del movimiento de remito al modal
                $('#conductor_remito').text(dataRemito[0].conductor);
                $('#patente_acoplado_remito').text(dataRemito[0].acoplado);
                $('#patente_remito').text(dataRemito[0].patente);
                $('#observaciones_remito').text(dataRemito[0].observaciones_recepciones);
                $('#nroRemito').text(dataRemito[0].num_comprobante);


                $('#depo_destino_remito').text(dataRemito[0].descr_depo_origen);
                $('#establecimiento_destino_remito').text(dataRemito[0].desc_lote_destino);

                $("#observaciones_remito").text(dataRemito[0].observaciones_recepcion);

                // Limpiar la tabla antes de agregar nuevas filas
                var tablaDetalle = $('#tabla_detalle tbody');
                tablaDetalle.empty();

                // Verifica si dataEmpresa es un arreglo
                if (Array.isArray(dataRemito)) {
                    // Recorrer el arreglo de datos y agregar cada fila a la tabla
                    dataRemito.forEach(function(datos) {
                        // Asegúrate de que cada objeto tenga las propiedades que necesitas
                        if (datos.cantidad_cargada && datos.unidad_medida && datos
                            .descripcion_articulo && datos.descr_depo_origen && datos
                            .lote_id_origen) {
                            // Crear una nueva fila con los datos y añadirla a la tabla
                            var fila = `
                        <tr>
                            <td style="text-align: left;">${datos.cantidad_cargada}</td>  <!-- Alineado a la derecha -->
                            <td style="text-align: left;">${datos.unidad_medida}</td>
                            <td style="text-align: left;">${datos.descripcion_articulo}</td>    <!-- Alineado a la izquierda -->
                            <td style="text-align: left;">${datos.descr_depo_origen}</td>
                            <td style="text-align: left;">${datos.lote_id_origen}</td>
                        </tr>
                    `;

                            // Agregar la fila a la tabla
                            tablaDetalle.append(fila);
                        } else {
                            console.warn('Falta alguna propiedad en el objeto datos:', datos);
                        }
                    });
                    wc();
                } else {
                    console.error('dataRemito no es un arreglo:', dataRemito);
                }

            },
            error: function(xhr, status, error) {
                console.error('Error al cargar el modal:', error);
            }
        });
    } catch (error) {
        console.error('Error en modalReimpresion:', error);
    }
}

// Función que obtiene los datos del movimiento del remito (retorna una promesa)
function datosMovimientoRemito(data) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: '<?php echo base_url(ALM); ?>Reportes/datosMovimientoRemito',
            data: {
                data
            },
            type: 'POST',
            success: function(response) {
                try {
                    var resp = JSON.parse(response);
                    resolve(resp);
                } catch (error) {
                    reject('Error al parsear la respuesta: ' + error);
                }
            },
            error: function(xhr, status, error) {
                reject('Error en la llamada AJAX: ' + error);
            }
        });
    });
}

// Función que trae los datos de la empresa para la cabecera del remito
async function DatosEmpresaRemito() {
    try {
        // Realizar la llamada AJAX de manera sincrónica usando await
        const response = await $.ajax({
            type: 'POST',
            data: {},
            url: 'index.php/<?php echo ALM?>Reportes/getDatosCabeceraRemito'
        });

        const resp = JSON.parse(response);

        // Imprimir los datos parseados
        console.log('Datos parseados:', resp);

        if (resp && resp.logo && resp.direccion && resp.telefono && resp.email && resp.texto_pie_remito) {
            return resp;
        } else {
            throw new Error('Estructura de datos inesperada en la respuesta');
        }

    } catch (error) {
        console.error('Error en DatosEmpresaRemito:', error);
        alert('Error al obtener los datos de la cabecera');
        throw error;
    }
}

function verAjuste(deaj_id) {

  $.ajax({
        type: 'POST',
        data: {
          deaj_id
        },
        url: '<?php echo base_url(ALM) ?>Reportes/getDataAjuste',
        success: function(result) {
          var resp = JSON.parse(result);
          console.log(resp);
          $("#idAjuste").val(deaj_id);
          $("#tipoAjuste").val(resp[0].tipo_ajuste.split("tipos_ajuste_stock")[1]);
          $("#justificacion").val(resp[0].justificacion);
            
          $('#modalInfoAjuste').modal('show');
          
        },
        error: function() {
            alert('Ha ocurrido un error, por favor comunicarse con su proveedor de servicio. Gracias!');
            wc();
        },
        complete: function(result) {
            wc();
        }
    });


}

$(document).ready(function() {
    // Ejecutar seleccionesta cuando se carga la página
    var establecimiento = document.getElementById('establecimiento');
    if (establecimiento && establecimiento.value) {
        seleccionesta(establecimiento);
    }
});

function clipMovimiento(demi_id){
    wo();
    console.log('Llamando a getDataMovimientoInterno con demi_id:', demi_id);
    $.ajax({
        type: 'POST',
        data: {
            demi_id
        },
        url: '<?php echo base_url(ALM) ?>Reportes/getDataMovimientoInterno',
        success: function(result) {     
            wc();       
            var parsedResult = JSON.parse(result);
            console.log(parsedResult);
            console.log('Resultado parseado:', parsedResult);
            // Asegurarse de que parsedResult es un array y tiene al menos un elemento
            if(Array.isArray(parsedResult) && parsedResult.length > 0) {
                
                let justificacion = parsedResult[0].justificacion;
                let demiId = parsedResult[0].demi_id;
                let cantidadCargada = parsedResult[0].cantidad_cargada;
                let cantidadRecibida = parsedResult[0].cantidad_recibida;

                // Mostrar el modal y llenar los campos
                $('#modalInfoMovimiento').modal('show');
                $('#demiIdMovimiento').val(demiId);
                $('#cantidadCargada').val(cantidadCargada);
                $('#cantidadRecibida').val(cantidadRecibida);

                // Mostrar u ocultar la justificación según corresponda
                if (justificacion && justificacion.trim() !== '') {
                    $('#justificacionContainer').show();
                    $('#justificacionMovimiento').val(justificacion);
                } else {
                    $('#justificacionContainer').hide();
                }

            } else {
                error('No se encontraron detalles para este movimiento.');
            }
        },
        error: function(xhr, status, error) {
             console.error('Error en la llamada AJAX:', status, error);
            alert('Ha ocurrido un error, por favor comunicarse con su proveedor de servicio. Gracias!');
        }
    });
}
</script>