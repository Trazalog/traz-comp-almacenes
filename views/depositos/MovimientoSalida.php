<div class="row">
    <div class="col-md-12">
			<!-- SALIDA DE DEPOSITO -->
				<div class="box">
						<div class="box-header with-border">
								<h3 class="box-title">Salida de deposito</h3>
						</div>
						<div class="box-body">
								<div class="row">
										<div class="col-md-3">
												<div class="form-group">
														<label>Nro. Comprobante <strong class="text-danger">*</strong> :</label>
														<input type="number" id="nroCompr" class="form-control" placeholder="Ingrese Numero de comprobante">
												</div>
										</div>
										<div class="col-md-3">
												<div class="form-group">
														<label>Fecha <strong class="text-danger">*</strong> :</label>
														<div class="input-group date">
																<div class="input-group-addon">
																		<i class="fa fa-calendar"></i>
																</div>
																<input type="date" class="form-control pull-right" value="<?php echo date('Y-m-d');?>" id="fecha" placeholder="Seleccione Fecha">
														</div>
												</div>
										</div>
										<div class="col-md-3">
												<label>Establecimiento destino <strong class="text-danger">*</strong> :</label>
												<select onchange="seleccionesta(this)" class="form-control select2 select2-hidden-accesible" id="esta_dest_id" required>
													<option value="" disabled selected>-Seleccione opcion-</option>
													<?php
													foreach ($establecimiento as $a) {
														echo '<option value="'.$a->esta_id.'">'.$a->nombre.'</option>';
													}
													?>
												</select>
										</div>
										<div class="col-md-3">
												<label>Deposito destino <strong class="text-danger">*</strong> :</label>
												<select  class="form-control select2 select2-hidden-accesible" id="depo_id" readonly>
													<option value="" disabled selected>-Seleccione opcion-</option>
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
												<label>Conductor</label>
												<input id="conductor" class="form-control" placeholder="Ingrese Nombre">
										</div>
										<div class="col-md-6">
												<label>DNI</label>
												<input id="dni" class="form-control" placeholder="Ingrese DNI del conductor">
										</div>
										<div class="col-md-6">
												<label>Patente</label>
												<input id="patente" class="form-control" placeholder="Ingrese numero de  patente de camión">
										</div>
										<div class="col-md-6">
												<label>Acoplado</label>
												<input id="acoplado" class="form-control" placeholder="Ingrese numero de patente de acoplado">
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
												<label>Deposito origen <strong class="text-danger">*</strong> :</label>
												<select  class="form-control select2 select2-hidden-accesible" id="depo_origen_id" required onchange="">
													<option value="" disabled selected>-Seleccione opcion-</option>
														<?php
															foreach ($depositos as $b) {
																	echo '<option value="'.$b->depo_id.'">'.$b->descripcion.'</option>';
															}
														?>
												</select>
										</div>
										<div class="col-md-4">
												<label>Codigo <strong class="text-danger">*</strong> :</label>
												<?php $this->load->view(ALM . '/articulo/componente');?>
										</div>
										<div class="col-md-4">
												<label>Lote Origen <strong class="text-danger">*</strong> :</label>
												<select  class="form-control select2 select2-hidden-accesible" id="lote_id" readonly>
													<option value="" disabled selected>-Seleccione opcion-</option>
												</select>
										</div>
								</div>
								<div class="row">
									<div class="col-md-4">
											<label>Cantidad <strong class="text-danger">*</strong> :</label>
											<input type="number" id="cant_id" class="form-control" placeholder="Ingrese cantidad">
									</div>
									<!-- <div class="col-md-4">
											<label>Unidad de Medida <strong class="text-danger">*</strong> :</label>
											<select  class="form-control select2 select2-hidden-accesible" id="id_un" required>
												<option value="" disabled selected>-Seleccione opcion-</option>
													<?php
														foreach ($unidades as $u) {
															echo '<option value="'.$u->tabl_id.'">'.$u->descripcion.'</option>';
														}
													?>

											</select>
									</div> -->
								</div>
								<br>
								<div class="row">
										<div class="col-md-3" style="float:right">
												<button class="btn btn-primary " style="float:right;" onclick="agregarProducto()"><i
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
														<th>Deposito Origen</th>
														<th style="display:none;">DepoIdorigen</th>
														<th>Lote</th>
														<th>Codigo Articulo</th>
														<th style="display:none;">arti_id</th>
														<th>Descripcion Articulo</th>
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
														<input type="text" class="form-control">
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
												<button class="btn btn-primary " style="float:right;" onclick="imprimir()"><i
																class="fa fa-clone"></i>Imprimir</button>
										</div> -->
								</div>
						</div>
				</div>
			<!-- / PRODUCTOS A CARGAR -->
    </div>
</div>

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
	$('#depo_origen_id').on("change", function() {
		$("#inputarti").attr("disabled", false);
	})
	// al seleccionar articulo trae los lotes existentes en el deposito seleccionado previamente
	$('#inputarti').on("change", function() {

			var depo_id = $('#depo_origen_id option:selected').val();
			var arti_id = selectItem.arti_id; // se completa en traz-comp-almacen/articulo/componente.php

			if(depo_id == ""){
				alert('Por favor seleccione deposito...');
				return;
			}

			WaitingOpen('Buscando Lotes...');

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
								console.table(resp);
								console.table(resp[0].lote_id);
								$('#lote_id').append('<option value="" disabled selected>-Seleccione opcion-</option>');
								for(var i=0; i<resp.length; i++)
								{
										$('#lote_id').append("<option value='" + resp[i].lote_id + "'>" +resp[i].codigo+"</option");
								}
								$("#lote_id").removeAttr('readonly');
							}
							WaitingClose();
					},
					error: function(data) {
							alert('Error');
							WaitingClose();
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
							url: 'index.php/<?php echo ALM?>Movimientodeposalida/traerDepositos',
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
			var aux = 0;
			//Informamos el campo vacio 
			var reporte = validarCamposProducto("agregar");
									
			if(reporte == '')
			{
					var depoOrigen_id = $("#depo_origen_id").val();
					var descDepo = $("#depo_origen_id option:selected").text();
					var lote_id_origen = $('#lote_id').val();
					var lote_codigo = $('#lote_id option:selected').text();
					var codigoArt = $("#inputarti").val();
					var idarti = selectItem.arti_id;// se completa en traz-comp-almacen/articulo/componente.php
					idarti = idarti.toString();
					var um = selectItem.unidad_medida;// se completa en traz-comp-almacen/articulo/componente.php
					var aux = $("#info").text();
					var descArt = "";
					//le saco el stock para mostrar solo el nombre del articulo
					var len = aux.length;
					for(var j=0; j<aux.length; j++)
					{
							if(aux[j] != "|")
							{descArt = descArt + aux[j];}
							else{
									j = aux.length;
							}
					}
					var cant = $("#cant_id").val();
					// var um = $("#id_un option:selected").text(); debe utilizar la cargada en el articulo

					var datos = {};
					datos.codigo = lote_codigo;
					datos.cantidad = cant;
					datos.arti_id = idarti;
					datos.lote_id_origen = lote_id_origen;

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
					alert(reporte);
			}
	}
	// guarda info de movimiento de salida
	function guardar()
	{
			var reporte = validarCamposProducto("guardar");
			
			if(reporte == '')
			{
				WaitingOpen('Guardando...');
				var cabecera = armarCabecera();
				var detalle = armarDetalle();

					$.ajax({
							type: 'POST',
							data:{cabecera, detalle},
							dataType: 'json',
							url: 'index.php/<?php echo ALM?>Movimientodeposalida/guardar',
							success: function(result) {
								WaitingClose();
								$('#btn_guardar').attr("disabled", "");
								alertify.success(result.data);
							},
							error: function(result){
								WaitingClose();
								alert(result.data);
								alertify.error(result.data);
							}
					});

			}else{
				alert(reporte);
			}
	}
	// procesa datos para enviar a gardar cabecera
	function armarCabecera(){

			var cabecera = {};
			cabecera.num_comprobante = $("#nroCompr").val();
			cabecera.fec_envio = $("#fecha").val();
			cabecera.patente = $("#patente").val();
			cabecera.acoplado = $("#acoplado").val();
			cabecera.conductor = $("#conductor").val();
			cabecera.depo_id_origen = $("#depo_origen_id").val().toString();
			cabecera.depo_id_destino = $("#depo_id").val().toString();
			return cabecera;
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
	// imprime movimiento
	function imprimir(){
		debugger;
			var cabecera = armarCabecera();
			var detalle = armarDetalle();

			$.ajax({
					type: 'POST',
					data:{cabecera, detalle},
					dataType: 'json',
					url: 'index.php/<?php echo ALM?>Movimientodeposalida/imprimir',
					success: function(data) {
						WaitingClose();
						debugger;
						//texto = data;
							var win = window.open('', 'Imprimir', 'height=700,width=900');
							win.document.write('<html><head><title></title>');
							win.document.write('</head><body onload="window.print();">');
							//win.document.write(texto);
							win.document.write('</body></html>');
							win.document.close(); // necessary for IE >= 10
							win.focus(); // necessary for IE >= 10
						alert('listoooo');

					},
					error: function(result){
						WaitingClose();
						alert(result);
						//alertify.error('Hubo un error guardando Mpvimiento de Salida');
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
		if($("#lote_id").val() == null){
			valida = "COMPLETE EL CAMPO LOTE ORIGEN!";
			}
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
		if ($("#nroCompr").val() == '') {
			valida = "COMPLETE CAMPO NÚMERO DE COMPROBANTE!";
		}
		if (!$('#tbl_productos').DataTable().data().any() && accion != "agregar") {
			valida = "ATENCIÓN: NO SE CARGO NINGUN PRODUCTO!";
		}
		if($("#depo_origen_id").val() == $("#depo_id").val()){
			valida = "ERROR: DEPÓSITO DESTINO IGUAL A DEPÓSITO ORIGEN!";
		}
		return valida;
	}
</script>