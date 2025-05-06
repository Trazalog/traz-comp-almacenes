<div class="row">
    <div class="col-md-12">
			<!-- SALIDA DE DEPOSITO -->
				<div class="box">
						<div class="box-header with-border">
								<h3 class="box-title">Salida de depósito</h3>
						</div>
						<div class="box-body">
								<div class="row">
										<!-- <div class="col-md-3">
												<div class="form-group">
														<label>Nro. Comprobante <?php hreq(); ?> :</label>
														<input type="number" id="nroCompr" class="form-control" placeholder="Ingrese Número de comprobante">
												</div>
										</div>
										<div class="col-md-3">
												<div class="form-group">
														<label>Fecha <?php hreq(); ?> :</label>
														<div class="input-group date">
																<div class="input-group-addon">
																		<i class="fa fa-calendar"></i>
																</div>
																<input type="date" class="form-control pull-right" value="<?php echo date('Y-m-d');?>" id="fecha" placeholder="Seleccione Fecha">
														</div>
												</div>
										</div> -->
										<input type="hidden" id="nroCompr" class="form-control" placeholder="" >
										<div class="col-md-3">
												<label>Establecimiento destino <?php hreq(); ?> :</label>
												<select onchange="seleccionesta(this)" class="form-control select2 select2-hidden-accesible" id="esta_dest_id" required>
													<option value="" disabled selected>-Seleccione opción-</option>
													<?php
													foreach ($establecimiento as $a) {
														echo '<option value="'.$a->esta_id.'">'.$a->nombre.'</option>';
													}
													?>
												</select>
										</div>
										<div class="col-md-3">
												<label>Depósito destino <?php hreq(); ?> :</label>
												<select  class="form-control select2 select2-hidden-accesible" id="depo_id" readonly>
													<option value="" disabled selected>-Seleccione opción-</option>
												</select>
										</div>
								</div>
						</div>
				</div>
			<!-- / SALIDA DE DEPOSITO -->
			<!-- DATOS DE TRANSPORTE -->
				<div class="box">
						<div class="box-header with-border">
								<h3 class="box-title">Datos del transporte</h3>
						</div>
						<div class="box-body">
								<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Conductor</label>
												<input id="conductor" class="form-control" placeholder="Ingrese Nombre">
												</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>DNI</label>
												<input id="dni" class="form-control" placeholder="Ingrese DNI del conductor">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Patente</label>
												<input id="patente" class="form-control" placeholder="Ingrese número de  patente de camión">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Acoplado</label>
												<input id="acoplado" class="form-control" placeholder="Ingrese número de patente de acoplado">
											</div>
										</div>

								</div>
								<br>
						</div>
				</div>
			<!-- / DATOS DE TRANSPORTE -->
			<!-- PRODUCTOS A CARGAR -->
				<div class="box">
						<div class="box-header with-border">
								<h3 class="box-title">Productos a cargar</h3>
						</div>
						<div class="box-body">
								<div class="row">
										<div class="col-md-4">
												<label>Depósito origen <?php hreq(); ?> :</label>
												<select  class="form-control select2 select2-hidden-accesible" id="depo_origen_id" required onchange="">
													<option value="" disabled selected>-Seleccione opción-</option>
														<?php
															foreach ($depositos as $b) {
																	echo '<option value="'.$b->depo_id.'">'.$b->descripcion.'</option>';
															}
														?>
												</select>
										</div>
										<div class="col-md-4 ba">
											<label>Artículos <?php hreq(); ?> :</label>
												<select style="width: 100%" class="form-control" id="inputarti">
													<option selected disabled data-foo=""> - Seleccione opción - </option>
												</select>
												<label id='detalle' class='select-detalle text-blue'></label>
											<br>
											<label id="info" class="text-blue"></label>
										</div>
										<div class="col-md-4 ba">
											<label>Lote Origen:</label>
											<select  class="form-control select2 select2-hidden-accesible" id="lote_id" disabled>
												<option value="" disabled selected>-Seleccione opción-</option>
											</select>
											<label id='detalle' class='select-detalle'></label>
											<br>
											<label id="info" class="text-blue"></label>
										</div>
								</div>
								<div class="row">
									<div class="col-md-4">
											<label>Cantidad <?php hreq(); ?> :</label>
											<input type="number" id="cant_id" class="form-control" placeholder="Ingrese cantidad">
									</div>
								</div>
								<br>
								<div class="row">
										<div class="col-md-3" style="float:right">
												<button class="btn btn-primary " style="float:right;" id='btnAgregar' onclick="agregarProducto()"><i
																class="fa fa-check"></i>Agregar</button>
										</div>
								</div>
								<div class="row">
										<div class="col-md-12">
												<br><br>
												<table class="table table-striped" id="tbl_productos">
														<!-- <thead class="thead-dark" bgcolor="#eeeeee"> -->
														<thead class="thead-dark">
														<th>Acciones</th>
														<th>Depósito Origen</th>
														<th style="display:none;">DepoIdorigen</th>
														<th>Lote</th>
														<th>Código Artículo</th>
														<th style="display:none;">arti_id</th>
														<th>Descripción Artículo</th>
														<th>Cantidad</th>
														<th>UM</th>
														</thead>
												
														<tbody>

														</tbody>
												</table>

										</div>
								</div>
								<br><br>
								<div class="row">
										<div class="col-md-9">
												<div class="form-group">
														<label>Observaciones</label>
														<input type="text" class="form-control" id="observaciones">
												</div>
										</div>
										<div class="col-md-3">
												<div class="form-group">
														<label>Productos recepcionados</label>
														<br>
														<label style=" font-size: xx-large; margin-left: 7rem;" id="total">0</label>
														<input type="number" id="totalCont" style="display:none;">
												</div>
										</div>
								</div>
								<div class="row">
										<div class="col-md-1" style="float:right">
												<button class="btn btn-primary" id="btn_guardar" style="float:right;" onclick="guardar()"><i
																class="fa fa-check"></i>Guardar</button>
										</div>
										<!-- <div class="col-md-1" style="float:right">
											 	<button class="btn btn-primary " style="float:right;" onclick="imprimir()"><i class="fa fa-print" style="cursor: pointer; margin: 3px;" title="Imprimir"></i>Imprimir</button> 
										</div>  -->
								</div>
						</div>
				</div>
			<!-- / PRODUCTOS A CARGAR -->
    </div>
</div>

<!-- MODAL ARTICULOS -->
<div class="modal" id="modal_articulos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                         aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title" id="myModalLabel"><span id="modalAction"> </span> Listado de Artículos</h4>
             </div>

             <div class="modal-body" id="modalBodyArticle">
    
                     <div class="table-responsive" id="modalarticulos">

                   
                 </div>
             </div>

             <div class="modal-footer">
                 <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
             </div>
         </div>
     </div>
 </div>
<!-- FIN MODAL ARTICULOS -->
 <!-- MODAL REMITO -->
 <?php $this->load->view(ALM. "depositos/modal_remito_salida") ?>
<!-- FIN MODAL REMITO -->
<script>
	$(document).ready(function() {
		$("#totalCont").val(0);
		$("#inputarti").attr("disabled", "")
	});
	// remueve registro de tabla temporal 
	$(document).on("click",".fa-minus",function() {
			
			var cantdel = $('#tbl_productos tr td')[7].innerText;
			if(parseInt(cantdel) > parseInt($("#totalCont").val()))
			{ var contauxx = parseInt(cantdel) -  parseInt($("#totalCont").val());
				$("#total").text(contauxx);
				$("#totalCont").val(contauxx);
			}
			else{
					var contauxx = parseInt($("#totalCont").val()) - parseInt(cantdel);
					$("#total").text(contauxx);
					$("#totalCont").val(contauxx);
			}
			
		$('#tbl_productos').DataTable().row( $(this).closest('tr') ).remove().draw();
						
	});

	// seleccionando deposito habilita combo articulos
	// Rellena combo con articulos para el deposito seleccionado
	$('#depo_origen_id').on("change", function() {
		var depo_id = $('#depo_origen_id option:selected').val();
		
		$("#inputarti").attr("disabled", false);
		$('#inputarti').html('');
		WaitingOpen('Buscando Artículos...');

		$.ajax({
			type: 'POST',
			data: {depo_id: depo_id},
			url: 'index.php/<?php echo ALM?>Movimientodeposalida/getArticulosDeposito',
			success: function(data) {

				$('#articulos').empty();
				var resp = JSON.parse(data);

				if (resp == null) {
					Swal.fire('Error',"Sin artículos disponibles para este depósito!",'warning');
					$("#inputarti").val('');
					$("#info").text('');
					$("#inputarti").attr("placeholder", "Sin artículos disponibles para este depósito");
					$("#lote_id").attr("readonly", true);
					$("#lote_id").val("");
					$("#inputarti").attr("disabled", true);
				} else {
					$("#inputarti").attr("placeholder", "Seleccionar Artículo");

					selectArticulos = '<option selected disabled data-foo=""> - Seleccione artículo -</option>';

					for(var i=0; i<resp.length; i++)
					{
						json = JSON.stringify(resp[i]);
						selectArticulos += "<option value='" + resp[i].barcode + "' data-json='"+ json +"'  data-foo='<small><cite> "+resp[i].descripcion+" </cite></small><label class=\"text-blue\"> ♦ </label><small><cite> "+resp[i].cantidad+" </cite></small><label class=\"text-blue\"> ♦ </label><small><cite> "+resp[i].unidad_medida+" </cite></small>' >"+ resp[i].barcode + "</option>";
					}
					$('#inputarti').html(selectArticulos);

          			$('#inputarti').select2({matcher: matchCustom,templateResult: formatCustom}).on('change', function() { selectEvent(this);});
					$("#articulos").removeAttr('readonly');
				}
				WaitingClose();
			},
			error: function(data) {
					alert('Error');
					WaitingClose();
			}
		});
	});

	// al seleccionar articulo trae los lotes existentes en el deposito seleccionado previamente
	$('#inputarti').on("change", function() {
		var depo_id = $('#depo_origen_id option:selected').val();
		var artiJson = JSON.parse($('#inputarti option:selected').attr('data-json'));
		arti_id = artiJson.arti_id;
		$("#cant_id").val(''); // Limpio cantidad al cambiar de artículo
		
		if(depo_id == ""){
			error('Error','Por favor seleccione depósito...');
			return;
		}

		wo();
		$.ajax({
			type: 'POST',
			data: {arti_id: arti_id, depo_id: depo_id},
			url: 'index.php/<?php echo ALM?>Movimientodeposalida/traerLotes',
			success: function(data) {
				$('#lote_id').empty();
				var resp = JSON.parse(data);
				if (resp == null) {
					$('#lote_id').append('<option value="" disabled selected>-Sin Lotes para este artículo-</option>');
				} else {
					var selectLotes = '<option selected disabled data-foo=""> - Seleccione lote -</option>';
					// $('#lote_id').append('<option value="" disabled selected>-Seleccione opción-</option>');

					for(var i=0; i < resp.length; i++){
						json = JSON.stringify(resp[i]);
						if(resp[i].codigo == '1'){
							selectLotes += "<option value='" + resp[i].lote_id + "' " + 
								"data-json='" + json + "' " +
								"data-foo='<small><cite>Proveedor: <span class=\"text-blue\">" + resp[i].proveedor + "</span></cite></small>' " +
								"data-cantidad='" + resp[i].cantidad + "'>" +
								"LOTE ÚNICO" + 
								"</option>";
						}else{
							selectLotes += "<option value='" + resp[i].lote_id + "' " + 
								"data-json='" + json + "' " +
								"data-foo='<small><cite>Proveedor: <span class=\"text-blue\">" + resp[i].proveedor + "</span></cite></small>' " +
								"data-cantidad='" + resp[i].cantidad + "'>" + 
								resp[i].codigo + 
								"</option>";
						}
					}
					$('#lote_id').html(selectLotes);
          			$('#lote_id').select2({matcher: matchCustom,templateResult: formatCustom}).on('change', function() { selectEvent(this);});
					$("#lote_id").attr('disabled',false);
				}
				wc();
			},
			error: function(data) {
				error('Error','Se produjo un error al obtener los Lotes del artículo');
				wc();
			}
		});
	});
	// Al seleccionar establecimiento, busca depositos
	function seleccionesta(opcion){

			WaitingOpen('Buscando Depositos...');
			var id_esta = $("#esta_dest_id").val();
			console.table(id_esta);
			$.ajax({
							type: 'POST',
							data: {id_esta},
							url: 'index.php/<?php echo ALM?>Movimientodeposalida/obtenerDepositosAll',
							success: function(data) {

									var resp = JSON.parse(data);
									WaitingClose();
									$('#depo_id').empty();
									$("#depo_id").removeAttr('readonly');
									if (resp == null) {
											$('#depo_id').append('<option value="" disabled selected>-Sin Depósitos para este Establecimiento-</option>');
									} else {
										$('#depo_id').append('<option value="" disabled selected>-Seleccione Depósito-</option>');
											for(var i=0; i<resp.length; i++)
											{
													$('#depo_id').append("<option value='" + resp[i].depo_id + "'>" +resp[i].descripcion+"</option");
											}
									}
							},
							error: function(data) {
									alert('Error');
									WaitingClose();
							}
			});
	}
	// agrega info a la tabla temporal
	function agregarProducto()
	{
		//Informamos el campo vacio 
		var reporte = validarCamposProducto("agregar");
								
		if(reporte == ''){
			var artiJson = JSON.parse($('#inputarti option:selected').attr('data-json'));
			var loteJson = JSON.parse($('#lote_id option:selected').attr('data-json'));
			var depoOrigen_id = $("#depo_origen_id").val();
			var descDepo = $("#depo_origen_id option:selected").text();
			var lote_id_origen = $('#lote_id').val();
			var lote_codigo = loteJson.codigo;
			var codigoArt = $("#inputarti").val();
			var cant = $("#cant_id").val();
			var idarti = artiJson.arti_id;
			idarti = idarti.toString();
			var um = artiJson.unidad_medida;
			var descArt = artiJson.descripcion;

			var datos = {};
			datos.codigo = lote_codigo;
			datos.cantidad = cant;
			datos.arti_id = idarti;
			datos.descDepo = descDepo;
			datos.lote_id_origen= lote_id_origen;
			datos.descArt=descArt;
			datos.um = um;
			
			if (lote_id_origen !=''){
				datos.lote_id_origen = lote_id_origen;
			}else{
				lote_id_origen = 'Sin Lote'
			}

			var table = $('#tbl_productos').DataTable();
			var row = `<tr data-json='${JSON.stringify(datos)}'>
							<td> <i class='fa fa-fw fa-minus text-light-blue' style='cursor: pointer; margin-left: 15px;'></i> </td>
							<td>${descDepo}</td>
							<td style='display:none;'>${depoOrigen_id}</td>
							<td>${lote_id_origen}</td>
							<td>${codigoArt}</td>
							<td style='display:none;'>${idarti}</td>
							<td>${descArt}</td>
							<td>${cant}</td>
							<td>${um}</td>
					</tr>`;
			table.row.add($(row)).draw();
			var contaux = parseInt(cant) +  parseInt($("#totalCont").val());
			$("#total").text(contaux);
			$("#totalCont").val(contaux);

		}else{
			error('Error', reporte);
		}
	}
	// guarda info de movimiento de salida
	async function guardar()
	{
			var reporte = validarCamposProducto("guardar");
			

		if (reporte == '') {
				WaitingOpen('Guardando...');
				try {
					var cabecera = await armarCabecera(); // Espera a que se complete la cabecera
					var detalle = armarDetalle();

					$.ajax({
						type: 'POST',
						data: { cabecera, detalle },
						dataType: 'json',
						url: 'index.php/<?php echo ALM?>Movimientodeposalida/guardar',
						success: function(result) {
							WaitingClose();
							$('#btn_guardar').attr("disabled", "");
							//alertify.success(result.data);
							imprimir();
						},
						error: function(result) {
							WaitingClose();
							alert(result.data);
							alertify.error(result.data);
						}
					});
				} catch (error) {
					WaitingClose();
					console.error('Error al armar la cabecera:', error);
					alert('Error al armar la cabecera');
				}
			}else{
				alert(reporte);
			}
	}
	// procesa datos para enviar a gardar cabecera
	async function armarCabecera() {
    var cabecera = {};

    var empr_id = $("#empr_id").val();

    return new Promise((resolve, reject) => {
        $.ajax({
            url: 'index.php/<?php echo ALM?>Movimientodeposalida/getNroComprobante',
            type: 'POST',
            data: {},
            dataType: 'json',
            success: function(response) {
                if (response[0].siguiente_comprobante) {
                    cabecera.num_comprobante = response[0].siguiente_comprobante;

					$('#nroCompr').val(response[0].siguiente_comprobante);
                    const fechaActual = new Date();
                    const dia = String(fechaActual.getDate()).padStart(2, '0');
                    const mes = String(fechaActual.getMonth() + 1).padStart(2, '0');
                    const anio = fechaActual.getFullYear();
                    const fechaFormateada = `${anio}-${mes}-${dia}`;

                    cabecera.fec_envio = fechaFormateada;
                    cabecera.patente = $("#patente").val();
                    cabecera.acoplado = $("#acoplado").val();
                    cabecera.conductor = $("#conductor").val();
                    cabecera.depo_id_origen = $("#depo_origen_id").val().toString();
                    cabecera.depo_id_destino = $("#depo_id").val().toString();
					cabecera.observaciones_recepcion = $("#observaciones").val();
                   
                    resolve(cabecera);
                } else {
                    console.error('Error al obtener el siguiente número de comprobante');
                    reject('Error al obtener el siguiente número de comprobante');
                }
            },
            error: function(error) {
                console.error('Error en la solicitud AJAX', error);
                reject(error);
            }
        });
    });
}
	// procesa datos para guardar detalle
	function armarDetalle(){

			var datos = [];
			var rows = $('#tbl_productos tbody tr');
			$.each(rows, function(i,e) {

					var datajson = $(this).attr("data-json");
					console.log(datajson);
					datos.push(datajson);
			});
			return datos;
	}



// Función para abrir el modal y cargar los datos sincrónicamente
function imprimir() {

			const swalWithBootstrapButtons = Swal.mixin({
			customClass: {
				confirmButton: 'btn btn-success',
				cancelButton: 'btn btn-danger'
			},
			buttonsStyling: false
		});

		swalWithBootstrapButtons.fire({
			title: '¡Éxito!',
			text: '¿Desea imprimir antes de salir?',
			icon: 'success',
			showCancelButton: true,
			confirmButtonText: 'Imprimir',
			cancelButtonText: 'Cerrar',
			reverseButtons: true
		}).then((result) => {
			if (result.value) {
				wo();
				generaRemito();
			} else if (
				result.dismiss === Swal.DismissReason.cancel
			) {
				swalWithBootstrapButtons.fire(
					'Cancelado',
					'Recuerda que puedes imprimir luego',
					'info'
				);
				$('#modalRemito').modal('hide'); 
			}
		});
}
	//Date picker
	$('#datepicker').datepicker({
			autoclose: true
	});

	//Valido los campos de productos a cargar
	function validarCamposProducto(accion){
		var valida = '';
		if($("#cant_id").val() == ""){
			valida = "COMPLETE EL CAMPO CANTIDAD!";
		}
		// if($("#lote_id").val() == null){
		// 	valida = "COMPLETE EL CAMPO LOTE ORIGEN!";
		// 	}
		if($("#inputarti").val() == ""){
			valida = "SELECCIONE UN ARTÍCULO!";
		}
		if($("#depo_origen_id").val() == null){
			valida = "SELECCIONE CAMPO DEPÓSITO ORIGEN!";
		}
		//El campo DEPÓSITO ORIGEN no debe ser el mismo que DEPÓSITO DESTINO. Validamos primero que se cargo deposito destino
		if($("#depo_id").val() == null){
			valida = "SELECCIONE CAMPO DEPÓSITO DESTINO!";
		}
		/* if ($("#nroCompr").val() == '') {
			valida = "COMPLETE CAMPO NÚMERO DE COMPROBANTE!";
		} */
		if (!$('#tbl_productos').DataTable().data().any() && accion != "agregar") {
			valida = "ATENCIÓN: NO SE CARGO NINGUN PRODUCTO!";
		}
		if($("#depo_origen_id").val() == $("#depo_id").val()){
			valida = "ERROR: DEPÓSITO DESTINO IGUAL A DEPÓSITO ORIGEN!";
		}
		return valida;
	}




// Validaciones de cantidad ingresada con respecto al lote 

var timeout;

var inputCantidad = document.getElementById("cant_id");

inputCantidad.addEventListener("input", () => {
    // Reinicia el temporizador cada vez que el usuario escribe
    clearTimeout(timeout);

    // Configura el temporizador para ejecutar la validación
    timeout = setTimeout(() => {
        validarCantidad(inputCantidad.value);
    }, 800);
});

function validarCantidad(valor) {

	//trae el lote seleccionado 
    var selectLote = document.getElementById("lote_id");
    var selectedOption = selectLote.options[selectLote.selectedIndex];
    // obtengo el valor de data-cantidad de la opción seleccionada
    var dataCantidad = selectedOption ? selectedOption.dataset.cantidad : null;

	//console.log('cantidad lote:', dataCantidad);

	//convertir valor a entero
	let enteroCantidad = Math.floor(dataCantidad);

    // Lógica de validación
    if (valor < 0 || enteroCantidad < valor) {

		var artiJson = JSON.parse($('#inputarti option:selected').attr('data-json'));
		var descArt = artiJson.descripcion;
		
		//console.log('articulo', descArt);

		const swalWithBootstrapButtons = Swal.mixin({
			customClass: {
				confirmButton: 'btn btn-success',
				cancelButton: 'btn btn-danger'
			},
			buttonsStyling: false
		});

		swalWithBootstrapButtons.fire(
			'El lote seleccionado contiene ' + enteroCantidad + ' ' + descArt,                 
			'Por favor, ingrese un valor menor o igual a: '+ enteroCantidad,  
			'info'                 
		);
       // console.log("Cantidad inválida:", valor);
		$('#btnAgregar').prop('disabled', true);
    } else {
        //console.log("Cantidad válida:", valor);
		$('#btnAgregar').prop('disabled', false);
    }
}



</script>