<div class='modal fade' id='modalRemito' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>

    <div class='modal-dialog' role='document'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' onclick='cierraModalRemito()' aria-label='Close'><span
                        aria-hidden='true'>&times;</span></button>
                <h4 class='modal-title' id='myModalLabel'>Impresión de Remito</h4>
            </div>
            <div class='modal-body' id='modalBodyRemito'>
                <div class="container-fluid">
                    <div class="row">
                        <input type="hidden" name="nro_contador_remito" id="nro_contador_remito" >
                        <div class="col-xs-5 col-md-5">
                            <img src="" id='logo_remito'>
                            <h5 id="direccion_remito" style="margin: 0px"></h5>
                            <h5 id="telefono_remito" style="margin: 0px"></h5>
                            <h5 id="email_remito" style="margin: 0px"></h5>
                            
                        </div>
                        <div class="col-xs-7 col-md-7">
                            <h3 style="margin-top: 5px"><b>REMITO INTERNO</b></h3>
                            <div class="col-xs-offset-1 col-xs-10 col-md-offset-1 col-md-10">
                                <strong>N° <span id="nroRemito"></span></strong>
                                <p>FECHA <span id="fechaRemito"></span></p>
                                <p>Documento no válido como factura</p>
                                
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Primera columna -->
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <div class="row">
                               
                                <div class="col-md-12">
                                    <h5>Est. Destino: <span id="establecimiento_destino_remito"></span></h5>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6>Conductor: <span id="conductor_remito"></span></h6>
                                </div>
                                <div class="col-md-6">
                                    <h6>DNI: <span id="dni_conductor_remito"></span></h6>
                                </div>
                            </div>
                        </div>

                        <!-- Segunda columna -->
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <div class="row">
                                
                                <div class="col-md-12">
                                    <h5>Depo. Destino: <span id="depo_destino_remito"></span></h5>
                                </div>
                
                                <div class="col-md-6">
                                    <h6>Patente: <span id="patente_remito"></span></h6>
                                </div>
                                <div class="col-md-6">
                                    <h6>Acoplado: <span id="patente_acoplado_remito"></span></h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 centrar">
                        <div id="sec_productos">
                            <!-- ______ TABLA PRODUCTOS ______ -->
                            <table id="tabla_detalle" class="table table-bordered table-striped">
                                <thead class="thead-dark" bgcolor="#eeeeee">
                                    <th style="width: 5%;">Cantidad</th>
                                    <th>U. Med</th>
                                    <th>Descripción</th>
                                    <th>Depo. Orig</th>
                                    <th>Lote</th>
                                </thead>
                                <tbody>
                                 <!-- <tr>
                                    <td>300</td>
                                    <td>PIM800 - Pimenton Extra 100g</td>
                                    <td>500,00</td>
                                    <td>150000,00</td>
                                </tr>
                                <tr>
                                    <td>200</td>
                                    <td>PIM900 - Pimenton Extra 100g</td>
                                    <td>300,00</td>
                                    <td>60000,00</td>
                                </tr>
                                <tr>
                                    <td>500</td>
                                    <td>AJO330 - Ajo disecado 200g</td>
                                    <td>100,00</td>
                                    <td>50000,00</td>
                                </tr>  -->
                                </tbody>
                            </table>
                            <!-- <div class="row">
                                <div class="col-md-offset-6 col-md-6">
                                    <label class="control-label" for="footer_table">Total:</label>
                                    <div class="input-group" style="display:inline-flex;">
                                        <input id="footer_table2" name="footer_table2" type="text" value="260000,00" class="form-control input-md" readonly>
                                    </div>
                                </div>
                                _______ FIN TABLA ARTICULOS ______
                            </div> -->
                        </div>
                        <h3 id ="texto_pie_remito"></h3>
                        <p id="observaciones_remito"></p>
                    </div>
                    <!-- / Bloque de cotizacion -->
                </div>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-default' onclick='cierraModalRemito()'>Cancelar</button>
                <button type='button' class='btn btn-primary' onclick='imprimirRemito()'>Imprimir</button>
            </div>
        </div>
    </div>
</div>

<script>

function cierraModalRemito() {
    $('#modalRemito').modal('hide');
}

//impresion del remito
function imprimirRemito() {
        var base = "<?php echo base_url()?>";
        $('#modalBodyRemito').printThis({
            debug: false,
            importCSS: true,
            importStyle: true,
            pageTitle: "TRAZALOG TOOLS",
            printContainer: true,
            loadCSS: base + "lib/bower_components/bootstrap/dist/css/bootstrap.min.css",
            copyTagClasses: true,
            printDelay: 4000,
            afterPrint: function() {
            },
            base: base
        });
}


//trae datos de la empresa que van en la cabecera del remito
async function DatoscabeceraRemito() {
    try {
        // Realizar la llamada AJAX de manera sincrónica usando fetch
        const response = await $.ajax({
            type: 'POST',
            data: {},
            url: 'index.php/<?php echo ALM?>Movimientodeposalida/getDatosCabeceraRemito'
        });

        // Parsear los datos obtenidos en la respuesta
        const resp = JSON.parse(response);

        // Actualizar los elementos del DOM con los datos obtenidos
        document.getElementById('logo_remito').src = resp.logo.valor;
        $('#direccion_remito').html('<small>' + resp.direccion.valor + '</small>');
        $('#telefono_remito').html('<small>' + resp.telefono.valor + '</small>');
        $('#email_remito').html('<small>' + resp.email.valor + '</small>');
        $('#texto_pie_remito').html('<strong>' + resp.texto_pie_remito.valor + '</strong>');
        wc();

    } catch (error) {
        // Manejar el error
        wc();
        alert('Error al obtener los datos de la cabecera');
        WaitingClose();
    }
}


async function generaRemito() {
    
    var fecha = new Date(); // Obtiene la fecha actual
    var dia = String(fecha.getDate()).padStart(2, '0'); // Día
    var mes = String(fecha.getMonth() + 1).padStart(2, '0'); // Mes 
    var anio = fecha.getFullYear(); // Año

    // Formato de fecha: "dd/mm/yyyy"
    var fechaFormateada = dia + '/' + mes + '/' + anio;

    $('#fechaRemito').text(fechaFormateada);

    // Cargo Datos del cliente
    await DatoscabeceraRemito();
   
    $('#modalRemito').modal('show');

    // Datos antes de armar la tabla
    esta_dest_id = $('#esta_dest_id option:selected').text();
    $('#establecimiento_destino_remito').text(esta_dest_id);

    depo_destino = $('#depo_origen_id option:selected').text();
    $('#depo_destino_remito').text(depo_destino);

    conductor =  $("#conductor").val();
    $('#conductor_remito').text(conductor);

    acoplado = $("#acoplado").val();
    $('#patente_acoplado_remito').text(acoplado);
   
    dni = $("#dni").val();
    $('#dni_conductor_remito').text(dni);

    patente = $("#patente").val();
    $("#patente_remito").text(patente);

    observaciones = $("#observaciones").val();
    $("#observaciones_remito").text(observaciones);

    nroRemito = $('#nroCompr').val();
    $("#nroRemito").text(nroRemito);
    
    // fin antes de armar la tabla

    // Recupero datos de la tabla
    var productos = [];
    var table = $('#tbl_productos').DataTable();
    
    // Obtener todas las filas de todas las páginas
    table.rows().every(function() {
        // Obtener el valor del atributo 'data-json'
        var jsonData = $(this.node()).attr('data-json');
        
        if (jsonData) {
            // Parsear el JSON para obtener el objeto
            var datos = JSON.parse(jsonData);
            
            // Mostrar en consola los datos obtenidos
            console.log("Datos de la fila: ", datos);
            console.log("Código de artículo: ", datos.codigoArt);
            console.log("Cantidad: ", datos.cant);

            productos.push(datos);
        }
    });

    
    var tablaDetalle = $('#tabla_detalle tbody');

    // Limpiar la tabla antes de agregar nuevas filas
    tablaDetalle.empty();

    // Recorrer el arreglo de datos y agregar cada fila a la tabla
    productos.forEach(function(datos) {
        // Crear una nueva fila con los datos y añadirla a la tabla
        var fila = `
                    <tr>
                        <td style="text-align: left;">${datos.cantidad}</td>  <!-- Alineado a la derecha -->
                        <td style="text-align: left;">${datos.um}</td>
                        <td style="text-align: left;">${datos.descArt}</td>    <!-- Alineado a la izquierda -->
                        <td style="text-align: left;">${datos.descDepo}</td>
                        <td style="text-align: left;">${datos.lote_id_origen}</td>
                    </tr>
                `;

        
        // Agregar la fila a la tabla
        tablaDetalle.append(fila);

    });

}

</script>