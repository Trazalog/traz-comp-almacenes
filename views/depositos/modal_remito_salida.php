<div class='modal fade' id='modalRemito' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' style="z-index: 1060;">

    <div class='modal-dialog' role='document'>
        <div class='modal-content'>
            <div class='modal-header bg-blue'>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span
                        aria-hidden='true' style='color:white;'>&times;</span></button>
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
                                    <th style="width: 5%;">Cantidad Enviada</th>
                                    <th style="width: 5%;">Cantidad Recibida</th>
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
                        <div id="justificaciones_remito_seccion" style="margin-top: 15px; display: none; text-align: left;">
                            <strong>Justificación:</strong><br>
                            <div id="justificaciones_remito_list"></div>
                        </div>
                        <h3 id ="texto_pie_remito"></h3>
                        <p id="observaciones_remito"></p>

                        <!-- QR del remito (se completa al abrir la reimpresion) -->
                        <div id="contenedorQrRemito" style="display: none; text-align: center; margin-top: 20px; padding-top: 12px; border-top: 1px dashed #ccc;">
                            <div id="qrRemitoImagen" style="display: inline-block; min-height: 20px;"></div>
                            <div style="font-size: 10px; color: #666; margin-top: 4px;">
                                Escanee para ver el remito
                            </div>
                        </div>

                        <!-- Transportistas del remito -->
                        <div id="seccionTransportistasRemito" style="margin-top: 20px; text-align: left;">
                            <h4 style="margin-top: 0;">Transportistas</h4>
                            <table id="tablaTransportistasRemito" class="table table-bordered table-striped" style="margin-bottom: 10px;">
                                <thead bgcolor="#eeeeee">
                                    <th style="width: 70%;">Nombre Transportista</th>
                                    <th style="width: 30%;">Fecha</th>
                                </thead>
                                <tbody>
                                    <!-- Filas agregadas desde el input -->
                                </tbody>
                            </table>
                            <div class="hidden-print" style="margin-top: 10px;">
                                <div class="row">
                                    <div class="col-sm-8" style="margin-bottom: 8px;">
                                        <input type="text" id="inputNombreTransportista" class="form-control"
                                               placeholder="Ingrese nombre Transportista"
                                               onkeydown="if(event.key==='Enter'){event.preventDefault();agregarTransportistaRemito();}">
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="button" class="btn btn-success" id="btnAgregarTransportista" onclick="agregarTransportistaRemito()">
                                            Agregar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Bloque de cotizacion -->
                </div>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-default' data-dismiss='modal'>Cancelar</button>
                <button type='button' class='btn btn-primary' onclick='imprimirRemito()'>Imprimir</button>
            </div>
        </div>
    </div>
</div>

<script>

// -------------------------------------------------------
// QR del remito (metodologia traz-comp-codigos)
// Estas funciones ya existen cuando el modal se carga desde
// movimientosinternos/list.php. Aca se definen para el flujo
// de Nueva Salida, donde el modal se incluye directo.
// -------------------------------------------------------
window.__qrMovimientoConfig = window.__qrMovimientoConfig || {
    pixel: '7',
    level: 'L',
    framSize: '2'
};

if (typeof window.crearUrlQrMovimiento !== 'function') {
    window.crearUrlQrMovimiento = function(demiId) {
        return new Promise(function(resolve, reject) {
            $.ajax({
                type: 'POST',
                data: {
                    id: demiId,
                    funcion: 'ALM.movimientointerno'
                },
                url: '<?php echo COD ?>Url/generarLink',
                success: function(data) {
                    try {
                        var url = JSON.parse(data);
                        resolve(url.url);
                    } catch (e) {
                        reject(e);
                    }
                },
                error: function(err) {
                    reject(err);
                }
            });
        });
    };
}

if (typeof window.obtenerLinkQrMovimiento !== 'function') {
    window.__qrMovimientoLinks = window.__qrMovimientoLinks || {};
    window.__qrMovimientoPendientes = window.__qrMovimientoPendientes || {};

    window.obtenerLinkQrMovimiento = function(demiId) {
        if (window.__qrMovimientoLinks[demiId]) {
            return Promise.resolve(window.__qrMovimientoLinks[demiId]);
        }
        if (window.__qrMovimientoPendientes[demiId]) {
            return window.__qrMovimientoPendientes[demiId];
        }

        window.__qrMovimientoPendientes[demiId] = window.crearUrlQrMovimiento(demiId).then(function(link) {
            window.__qrMovimientoLinks[demiId] = link;
            delete window.__qrMovimientoPendientes[demiId];
            return link;
        }).catch(function(err) {
            delete window.__qrMovimientoPendientes[demiId];
            throw err;
        });

        return window.__qrMovimientoPendientes[demiId];
    };
}

if (typeof window.generarQrMovimiento !== 'function') {
    window.generarQrMovimiento = function(demiId, selectorContenedor) {
        return window.obtenerLinkQrMovimiento(demiId).then(function(link) {
            return new Promise(function(resolve, reject) {
                var titulo = 'MovimientoInterno_' + String(demiId).replace(/[^a-zA-Z0-9_-]/g, '_');
                var config = {
                    titulo: titulo,
                    pixel: window.__qrMovimientoConfig.pixel,
                    level: window.__qrMovimientoConfig.level,
                    framSize: window.__qrMovimientoConfig.framSize
                };

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        config: config,
                        data: { link: link },
                        direccion: 'codigosQR/traz-comp-almacenes/movimientos-internos'
                    },
                    url: 'index.php/<?php echo COD ?>Codigo/generarQRlite',
                    success: function(result) {
                        if (result && result.filename) {
                            $(selectorContenedor).html('<img src="' + result.filename + '" alt="Código QR" style="max-width: 140px;">');
                            resolve(result.filename);
                        } else {
                            reject('No se pudo generar el QR');
                        }
                    },
                    error: function(err) {
                        reject(err);
                    }
                });
            });
        });
    };
}

if (typeof window.generarQrRemitoMovimiento !== 'function') {
    window.generarQrRemitoMovimiento = function(demiId) {
        var $seccion = $('#contenedorQrRemito');
        var $contenedor = $('#qrRemitoImagen');
        if (!$seccion.length || !$contenedor.length) {
            return Promise.resolve();
        }
        if (!demiId) {
            $seccion.hide();
            return Promise.resolve();
        }

        $contenedor.html('<i class="fa fa-spinner fa-spin"></i> Generando QR...');
        $seccion.show();

        return window.generarQrMovimiento(demiId, '#qrRemitoImagen').then(function(filename) {
            $seccion.show();
            return filename;
        }).catch(function(err) {
            console.error('Error al generar el QR del remito:', err);
            $seccion.hide();
            return null;
        });
    };
}

function mostrarJustificaciones(datos) {
    var list = $('#justificaciones_remito_list');
    list.empty();
    $('#justificaciones_remito_seccion').hide();

    if (!datos || !Array.isArray(datos)) return;

    var justificadas = [];
    var index = 1;
    datos.forEach(function(d) {
        var cc = parseFloat(d.cantidad_cargada || d.cantidad || d.cant);
        var cr = parseFloat(d.cantidad_recibida);
        var justif = d.justificacion || d.justifica;
        if (!isNaN(cc) && !isNaN(cr) && cr < cc && justif && justif.trim() !== '') {
            justificadas.push(index + '. ' + justif.trim());
        }
        index++;
    });

    if (justificadas.length > 0) {
        justificadas.forEach(function(j) {
            list.append('<span>' + "Línea "+ j + '</span><br>');
        });
        $('#justificaciones_remito_seccion').show();
    }
}

// -------------------------
// Transportistas del remito
// -------------------------
function limpiarTransportistasRemito() {
    $('#tablaTransportistasRemito tbody').empty();
    $('#inputNombreTransportista').val('');
}

function escaparHtmlRemito(v) {
    return $('<div>').text(v == null ? '' : String(v)).html();
}

function formatearFechaRemito(v) {
    if (!v) {
        return '-';
    }
    // Ya viene formateada (dd/mm/yyyy hh:mm)
    if (/^\d{2}\/\d{2}\/\d{4}/.test(String(v))) {
        return v;
    }

    var d = new Date(String(v).replace(' ', 'T'));
    if (isNaN(d.getTime())) {
        return v;
    }

    var dd = String(d.getDate()).padStart(2, '0');
    var mm = String(d.getMonth() + 1).padStart(2, '0');
    var yy = d.getFullYear();
    var hh = String(d.getHours()).padStart(2, '0');
    var mi = String(d.getMinutes()).padStart(2, '0');

    return dd + '/' + mm + '/' + yy + ' ' + hh + ':' + mi;
}

function renderTransportistaRemito(nombre, fecha) {
    $('#tablaTransportistasRemito tbody').append(
        '<tr>' +
            '<td>' + escaparHtmlRemito(nombre) + '</td>' +
            '<td>' + escaparHtmlRemito(fecha) + '</td>' +
        '</tr>'
    );
}

function cargarTransportistasRemito(moinId) {
    window.__moinRemitoActual = moinId || null;
    limpiarTransportistasRemito();

    if (!moinId) {
        return;
    }

    $.ajax({
        type: 'POST',
        dataType: 'json',
        data: { moin_id: moinId },
        url: 'index.php/<?php echo ALM ?>Movimientointerno/getTransportistasMovimiento',
        success: function(rows) {
            if (!Array.isArray(rows)) {
                return;
            }
            rows.forEach(function(row) {
                renderTransportistaRemito(row.transportista, row.fec_alta_txt || formatearFechaRemito(row.fec_alta));
            });
        },
        error: function(err) {
            console.error('Error al cargar transportistas:', err);
        }
    });
}

function agregarTransportistaRemito() {
    var $input = $('#inputNombreTransportista');
    var $btn = $('#btnAgregarTransportista');
    var nombre = ($input.val() || '').trim();
    var moinId = window.__moinRemitoActual;

    if (nombre === '') {
        $input.focus();
        return;
    }

    if (!moinId) {
        alert('No se pudo identificar el movimiento para guardar el transportista.');
        return;
    }

    $btn.prop('disabled', true);

    $.ajax({
        type: 'POST',
        dataType: 'json',
        data: {
            moin_id: moinId,
            transportista: nombre
        },
        url: 'index.php/<?php echo ALM ?>Movimientointerno/guardarTransportistaMovimiento',
        success: function(rsp) {
            $btn.prop('disabled', false);
            if (rsp && rsp.status) {
                renderTransportistaRemito(nombre, rsp.fec_alta_txt || '');
                $input.val('').focus();
            } else {
                alert((rsp && rsp.msg) ? rsp.msg : 'No se pudo guardar el transportista.');
            }
        },
        error: function(err) {
            $btn.prop('disabled', false);
            console.error('Error al guardar transportista:', err);
            alert('No se pudo guardar el transportista.');
        }
    });
}

function cierraModalRemito() {
    // El overlay de carga queda por encima del modal (z-index 50000) y puede
    // interceptar el clic. Lo cerramos sin animacion antes de ocultar.
    if ($('#waiting').length) {
        $('#waiting').stop(true, true).hide();
    }
    // Se oculta la instancia visible por si el HTML fue inyectado mas de una vez
    $('#modalRemito:visible').modal('hide');
}

// Al cerrar (boton Cancelar, X o clic en el fondo) limpiamos el overlay de carga
$(document).on('hide.bs.modal', '#modalRemito', function () {
    if ($('#waiting').length) {
        $('#waiting').stop(true, true).hide();
    }
});

// Limpia restos de backdrop/scroll cuando no quedo ningun modal abierto
$(document).on('hidden.bs.modal', '#modalRemito', function () {
    if ($('.modal:visible').length === 0) {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
    }
});

$(document).on('show.bs.modal', '#modalRemito', function () {
    // Si está anidado dentro de otro contenedor de modal, lo adjuntamos al body
    if (!$(this).parent().is('body')) {
        $(this).appendTo('body');
    }
    var zIndex = 1060 + (10 * $('.modal:visible').length);
    $(this).css('z-index', zIndex);
    setTimeout(function() {
        $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
    }, 0);
});

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
        if (resp && resp.logo && resp.logo.valor) {
            document.getElementById('logo_remito').src = resp.logo.valor;
        }
        var direccionVal = (resp && resp.direccion && resp.direccion.valor) ? resp.direccion.valor : '-';
        var telefonoVal = (resp && resp.telefono && resp.telefono.valor) ? resp.telefono.valor : '-';
        var emailVal = (resp && resp.email && resp.email.valor) ? resp.email.valor : '-';
        var pieVal = (resp && resp.texto_pie_remito && resp.texto_pie_remito.valor) ? resp.texto_pie_remito.valor : '-';

        $('#direccion_remito').html('<small>' + direccionVal + '</small>');
        $('#telefono_remito').html('<small>' + telefonoVal + '</small>');
        $('#email_remito').html('<small>' + emailVal + '</small>');
        $('#texto_pie_remito').html('<strong>' + pieVal + '</strong>');

    } catch (error) {
        console.error('Error al obtener los datos de la cabecera', error);
    } finally {
        wc();
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

    // Genero el QR antes de abrir la vista previa para que ya este visible
    try {
        if (typeof window.generarQrRemitoMovimiento === 'function') {
            await window.generarQrRemitoMovimiento(window.__ultimoMoinId);
        }
    } catch (e) {
        console.error('Error al generar el QR del remito:', e);
    }

    // Cargo los transportistas ya guardados para este movimiento
    if (typeof cargarTransportistasRemito === 'function') {
        cargarTransportistasRemito(window.__ultimoMoinId);
    }

    // El overlay de carga tiene z-index superior al modal y puede bloquear
    // los clics de Cancelar/Imprimir. Me aseguro de ocultarlo antes de abrir.
    if ($('#waiting').length) {
        $('#waiting').stop(true, true).hide();
    }

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
            console.log("Código de artículo: ", datos.codigo || datos.codigoArt);
            console.log("Cantidad: ", datos.cantidad || datos.cant);

            productos.push(datos);
        }
    });

    
    var tablaDetalle = $('#tabla_detalle tbody');

    // Limpiar la tabla antes de agregar nuevas filas
    tablaDetalle.empty();

    // Recorrer el arreglo de datos y agregar cada fila a la tabla
    productos.forEach(function(datos) {
        var cant = (datos.cantidad !== undefined) ? datos.cantidad : (datos.cant || '-');
        var um = datos.um || '-';
        var desc = datos.descArt || datos.descripcion || '-';
        var depo = datos.descDepo || '-';
        var lote = datos.lote_id_origen || '-';

        // Crear una nueva fila con los datos y añadirla a la tabla
        var fila = `
                    <tr>
                        <td style="text-align: left;">${cant}</td>  <!-- Alineado a la derecha -->
                        <td style="text-align: left;"></td>  <!-- Alineado a la derecha -->
                        <td style="text-align: left;">${um}</td>
                        <td style="text-align: left;">${desc}</td>    <!-- Alineado a la izquierda -->
                        <td style="text-align: left;">${depo}</td>
                        <td style="text-align: left;">${lote}</td>
                    </tr>
                `;

        
        // Agregar la fila a la tabla
        tablaDetalle.append(fila);

    });

    mostrarJustificaciones(productos);

    wc();
}

</script>