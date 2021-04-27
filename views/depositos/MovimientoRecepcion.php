<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Recepción de Depósito</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        <label>Establecimiento receptor</label>
                        <!-- -- -->
                        <select onchange="seleccionesta(this)" class="form-control select2 select2-hidden-accesible" id="esta_dest_id" required>
												<option value="" disabled selected>-Seleccione Su establacimiento-</option>
													<?php
														foreach ($establecimiento as $a) {
														echo '<option value="'.$a->esta_id.'">'.$a->nombre.'</option>';
														}
													?>
												</select>
                        <!-- -- -->
                    </div>
                    <div class="col-md-4">
                        <label>Deposito receptor</label>
                        <!-- -- -->
                        <select  class="form-control select2 select2-hidden-accesible" id="depo_id" readonly>
													<option value="" disabled selected>-Seleccione Su deposito-</option>
												</select>
                        <!-- -- -->
                    </div>
                    <div class="col-md-1" style="margin-top:2.5rem;">
												<button class="btn btn-primary "  onclick="BucarRecepciones()">Buscar</button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped" id="tbl_recepciones" style="display:none">
                            <thead class="thead-dark" bgcolor="#eeeeee">
                            <th>Acciones</th>
                            <th>Deposito Origen</th>
                            <th>Nro Comprobante</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Datos del remito</h3>
            </div>
            <div class="box-body">
                <div class="row">
                <div class="col-md-4">
                        <div class="form-group">
                            <label>Remito enviado</label>
                            <input type="number" id="remito" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Fecha actual</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="date" value="<?php echo date('Y-m-d');?>" class="form-control pull-right" id="fecha_actual"  readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Fecha envío</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" id="fec_envio" class="form-control pull-right"  readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Datos del transporte</h3>
            </div>
            <div class="box-body">

								<input id="moin_id" class="form-control hidden" readonly>

                <div class="row">
                    <div class="col-md-4">
                        <label>Patente</label>
                        <input id="patente" class="form-control" readonly>
                    </div>
                    <div class="col-md-4">
                        <label>Acoplado</label>
                        <input id="acoplado" class="form-control" readonly>
                    </div>
                    <div class="col-md-4">
                        <label>Conductor</label>
                        <input id="conductor" class="form-control" readonly>
                    </div>
                </div>
                <br>
            </div>
        </div>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Productos a descargar</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- <br><br> -->
                        <table class="table table-striped" id="tbl_productos_recepcion">
														<thead class="thead-dark">
															<th>Cargar</th>
															<th>Codigo</th>
															<th>Lote Orig.</th>
															<th>Articulo</th>
															<th>C. Enviada</th>
															<th>C. Recepcion</th>
															<th>Dep. Descargar</th>
															<th class="hidden"></th>
															<th>Fecha Vto.</th>
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
                            <input type="text" class="form-control" id="observaciones_recepcion">
                        </div>
                    </div>
                    <!-- <div class="col-md-3">
                        <div class="form-group">
                            <label>Productos recepcionados</label>
                            <br>
                            <label style=" font-size: xx-large; margin-left: 7rem;" id="total">0</label>
                            <input type="number" id="totalCont" style="display:none;">
                        </div>
                    </div> -->
                </div>
                <div class="row">
                    <div class="col-md-1" style="float:right">
                        <button class="btn btn-primary " id="btn_guardar" style="float:right;" onclick="guardar()"><i
                                class="fa fa-check"></i>Guardar</button>
                    </div>
                    <!-- <div class="col-md-1" style="float:right">
                        <button class="btn btn-primary " style="float:right;" onclick="imprimir()"><i
                                class="fa fa-clone"></i>Imprimir</button>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Vista -->
<div class="modal fade" id="modalVista" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-lg-12">
									<h3>Detalle De Recepción</h3>
								</div>
							</div>
					</div> <!-- /.modal-header  -->
					<div class="modal-body table-responsive" id="modalBodyArticle">

						<div class="row">
							<div class="col-xs-12 col-sm-6 col-lg-6">
								<label>Numero de comprobante</label>
								<input type="text" class="form-control pull-right" value="" id="num_comprobante_v" readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-lg-6">
								<label>Fecha de Envío</label>
								<input type="text" class="form-control pull-right" value="" id="fec_envio_v" readonly>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12 col-sm-6 col-lg-6">
								<label>Conductor</label>
								<input type="text" class="form-control pull-right" value="" id="conductor_v" readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-lg-6">
								<label>Estado</label>
								<input type="text" class="form-control pull-right" value="" id="estado_v" readonly>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12 col-sm-6 col-lg-6">
								<label>Patente</label>
								<input type="text" class="form-control pull-right" value="" id="patente_v" readonly>
							</div>
							<div class="col-xs-12 col-sm-6 col-lg-6">
								<label>Acoplado</label>
								<input type="text" class="form-control pull-right" value="" id="acoplado_v" readonly>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12 col-sm-12 col-lg-12">
								<label>Observaciones </label>
								<input type="text" class="form-control pull-right" value="" id="observaciones_recepcion_v" readonly>
							</div>
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
					</div> <!-- /.modal footer -->
			</div><!-- /.tab-content -->
    </div> <!-- /.modal-dialog modal-lg -->
</div> <!-- /.modal fade -->
<!-- / Modal -->


<!-- Modal Carga -->
<div class="modal fade" id="depoDescarga" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-lg-12">
									<h3>Cargar en Depósito y Lote</h3>
								</div>
							</div>
					</div> <!-- /.modal-header  -->
					<div class="modal-body table-responsive" id="modalBodyArticle">
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-lg-6">
								<input class="form-control hidden" id="t_row"type="text">
								<input class="form-control hidden" id="arti_id"type="text">
								<label for="">Depósito Destino:</label>
								<!-- <select onchange="lotesPorArtyDeposito()" class="form-control" id="deposito_dest_id" required> -->
								<select class="form-control" id="deposito_dest_id" required>
									<option value="" disabled selected>-Seleccione depósito de entrada-</option>
										<?php
											foreach ($depositos as $depo) {
											echo '<option value="'.$depo->depo_id.'">'.$depo->descripcion.'</option>';
											}
										?>
								</select>
								<!-- -- -->
							</div>
							<div class="col-xs-12 col-sm-6 col-lg-6">
									<label for="">Cantidad a Ingresar:</label>
									<input class="form-control" id="cant_recibida"type="text">
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-lg-6">
								<label>Fecha de Vencimiento</label>
								<input type="date" class="form-control pull-right" value="<?php echo date('Y-m-d');?>" id="fec_vencimiento" placeholder="Seleccione Fecha">
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" onclick="agregaCantidadLote()" data-dismiss="modal">Aceptar</button>
						<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
					</div> <!-- /.modal footer -->
			</div><!-- /.tab-content -->
    </div> <!-- /.modal-dialog modal-lg -->
</div> <!-- /.modal fade -->
<!-- / Modal -->


<script>

	// llena modal para ver detalle de movimiento
	$(document).on("click",".fa-search",function() {
			event.preventDefault();
			event.stopImmediatePropagation();
			var dataJson = JSON.parse($(this).closest('tr').attr("data-json"));
			$("#num_comprobante_v").val(dataJson.num_comprobante);
			$("#fec_envio_v").val(dataJson.fec_envio);
			$("#conductor_v").val(dataJson.conductor);
			$("#estado_v").val(dataJson.estado);
			$("#patente_v").val(dataJson.patente);
			$("#acoplado_v").val(dataJson.acoplado);
			$("#observaciones_recepcion_v").val(dataJson.observaciones_recepcion);
			$("#modalVista").modal("show");
	});

	// completa los datos del remito y manda a armar tabla temporal del movimiento
	$(document).on("click",".fa-level-down",function(event) {

			event.preventDefault();
			event.stopImmediatePropagation();
			WaitingOpen('Cargando...');
			var dataJson = $(this).closest('tr').attr("data-json");
			var data = JSON.parse(dataJson);
			$("#moin_id").val(data.moin_id);
			$("#remito").val(data.num_comprobante);
			$("#patente").val(data.patente);
			$("#acoplado").val(data.acoplado);
			$("#conductor").val(data.conductor);
			tablaProductos(data.detallesMovimientosInternos.detalleMovimientoInterno);
	});

	//levanta modal para carga de cantidaddes y depositos
	$(document).on("click",".fa-pencil-square-o",function(event) {

			event.preventDefault();
			//guardo id de fila en modal
			var row = $(this).closest("tr").attr("id");
			$("#t_row").val(row);
			//guardo art_id en modal
			var dataArticulo = JSON.parse( $(this).closest("tr").attr("data-articulo") );
			$("#arti_id").val(dataArticulo.arti_id);
			//levanta modal de carga
			$("#depoDescarga").modal("show");
	});

	// agregar cantidad  y lote destino a la tabla temporal (trae del modal la info)
	function agregaCantidadLote(){

			var t_row = $("#t_row").val();

			$($("#tbl_productos_recepcion tbody tr")).each(function () {

					//busca id de fila y al encontrarlo, guarda en inputs la info elegida en modal
					if ($(this).attr("id") == t_row) {

							var cant_recibida = $("#cant_recibida").val();
							var deposito_dest_id = $("#deposito_dest_id option:selected").val();
							var deposito_dest_nomb = $("#deposito_dest_id option:selected").text();
							var fec_vencimiento = $("#fec_vencimiento").val();
							$(this).find("input.cantidad").val(cant_recibida);
							$(this).find("input.depo_id_modal").val(deposito_dest_id);
							$(this).find("input.depo_desc_nomb").val(deposito_dest_nomb);
							$(this).find("input.fec_vencimiento").val(fec_vencimiento);
					}else{
						console.log('error en id de tabla...!!!');
					}
			});
	}

	// buscar lote por deposito e id de articulo para el modal
	function lotesPorArtyDeposito(){

		var arti_id = $("#arti_id").val();
		var depo_id = $("#deposito_dest_id option:selected").val();
		WaitingOpen("Buscando Depósitos...");
		$.ajax({
				type: 'POST',
				data: {arti_id:arti_id, depo_id:depo_id},
				url: 'index.php/<?php echo ALM?>Movimientodeporecepcion/traerLotes',
				success: function(data) {

							$('#cod_lote').empty();
							var resp = JSON.parse(data);
							if (resp == null) {
								$('#cod_lote').append('<option value="" disabled selected>-Sin Lotes para este artículo-</option>');
							} else {
								console.table(resp);
								console.table(resp[0].lote_id);
								$('#cod_lote').append('<option value="" disabled selected>-Seleccione opcion-</option>');
								for(var i=0; i<resp.length; i++)
								{
										$('#cod_lote').append("<option value='" + resp[i].lote_id + "'>" +resp[i].codigo+"</option");
								}
								$("#cod_lote").removeAttr('readonly');
							}
							WaitingClose();
				},
				error: function(data) {
						alert('Error');
				}
		});
	}

	// arma tabla temporal de cada movimiento de recepcion
	function tablaProductos(detalle){

			// $("#tbl_productos_recepcion").removeAttr('style');
			$("#tbl_productos_recepcion").show();
			var table = $('#tbl_productos_recepcion').DataTable();
			table.rows().remove().draw();
			var datos = {};
			var articulo = {};

			$.each(detalle, function(key, value){

					datos.demi_id = value.demi_id;
					datos.cantidad_cargada = value.cantidad_cargada;
					datos.prov_id = value.prov_id;
					articulo.arti_id = value.arti_id;
					articulo.cod_lote = value.cod_lote;
					var row = `<tr data-json='${JSON.stringify(datos)}' data-articulo='${JSON.stringify(articulo)}' id='${key}'>
											<td><i class='fa fa-pencil-square-o text-light-blue' style='cursor: pointer; margin-left: 15px;'></i></td>
											<td class="barcode">${value.barcode}</td>
											<td class="">${value.cod_lote}</td>
											<td>${value.descripcion}</td>
											<td class="">${value.cantidad_cargada}</td>
											<td><input class='cantidad' style='border: 0;' readonly></input></td>
											<td><input class='depo_desc_nomb' style='border: 0;' readonly></input></td>
											<td><input class='depo_id_modal hidden'></input></td>
											<td><input class='fec_vencimiento' style='border: 0;' readonly></input></td>
										</tr>`;
					table.row.add($(row)).draw();
			});
			WaitingClose();
	}

	//trae recepciones y arma tabla temmporal
	function BucarRecepciones()
	{
		WaitingOpen('Buscando Recepciones...');
		var id_esta_dest = $("#esta_dest_id").val();
		var id_depo_dest = $("#depo_id").val();
		$.ajax({
				type: 'POST',
				data: {id_esta_dest, id_depo_dest},
				url: 'index.php/<?php echo ALM?>Movimientodeporecepcion/traerRecepciones',
				success: function(data) {

								WaitingClose();
								$("#tbl_recepciones").removeAttr('style');
								var table = $('#tbl_recepciones').DataTable();
								table.rows().remove().draw();
								if (data != null) {
									var resp = JSON.parse(data);
									for(var i=0; i<resp.length; i++){
										var movimCabecera = resp[i];
										var row = `<tr data-json='${JSON.stringify(movimCabecera)}'>
																<td><span>
																	<i class='fa fa-fw fa-search text-light-blue' style='cursor: pointer; margin-left: 15px;'></i></span>
																	&nbsp		<span><i class='fa fa-level-down text-light-blue' style='cursor: pointer; margin-left: 15px;'></i></span>
																</td>
																<td>${resp[i].depo_id_origen}</td>
																<td>${resp[i].num_comprobante}</td>
															</tr>`;
										table.row.add($(row)).draw();
										movimDetalle = "";
									}
								}
				},
				error: function(data) {
								WaitingClose();
								alert('Error al traer Datos de Envios de Depositos');
				}
		});
	}

	// guarda movimiento de rececpcion
	function guardar()
	{
			var auxe = 0;
			//VALIDADOR DE SI ESTA VACIO LA TABLA DE PRODUCTOS A CARGAR
			if(  $('#tbl_productos_recepcion').DataTable().data().any() )
			{
							//VALIDADOR DE VACIO DE ULTIMAS 3 COLUMNAS DE TABLA PRODUCTOS A CARGAR
							var a = $('#tbl_productos_recepcion tr').length;
							var idcant="";
							var iddepodes="";
							var idestado="";
							for(var i=0; i<a-1; i++)
							{
									idcant="#cant"+i;
									iddepodes="#depodesc"+i;
									idestado="#estado"+i;
									if($(idcant).val()!="")
									{
											if($(iddepodes).val()!="")
											{
													if($(idestado).val()!="")
													{
															auxe=auxe+1;
													}
											}
									}

							}
							//FIN VALIDADOR
			}

			if((auxe!=0) && (auxe==a-1))
			{
					var cabecera = armarCabecera();
					var detalle = armarDetalle();

					$.ajax({
							type: 'POST',
							data:{cabecera, detalle},
							dataType: 'json',
							url: '<?php echo ALM ?>Movimientodeporecepcion/guardar',
							success: function(result) {

								$('#btn_guardar').attr("disabled", "");
								alertify.success(result.data);
							},
							error: function(result){
								alertify.error(result.data);
							},
							complete: function(){
												
							}
					});
			}else{
					alert("ATENCION REVISE QUE HAYA COMPLETATDO cantidad de recepcion/ deposito a descargar/ estado");
			}
	}

	// arma cabecera movim recepcion
	function armarCabecera(){

		var cabecera = {};
		cabecera.estado = "RECIBIDO";
		cabecera.fec_recepcion = $("#fecha_actual").val();
		cabecera.observaciones_recepcion= $("#observaciones_recepcion").val();
		cabecera.moin_id = $("#moin_id").val();
		return cabecera;
	}

	// arma detalle movimiento recepcion
	function armarDetalle(){

		var datos = [];
		var item = {};
		var rows = $('#tbl_productos_recepcion tbody tr');
		$.each(rows, function(i,e) {

				var datajson = $(this).attr("data-json");
				var data = JSON.parse( datajson );
				item.demi_id = data.demi_id;
				item.cantidad_cargada = data.cantidad_cargada;
				item.prov_id = data.prov_id;
				item.arti_id = JSON.parse($(this).attr("data-articulo")).arti_id;
				item.cod_lote = JSON.parse($(this).attr("data-articulo")).cod_lote;// codigo de lote origen
				item.depo_id = $(this).find("input.depo_id_modal").val();
				item.fec_vencimiento   = $(this).find("input.fec_vencimiento").val();
				item.cantidad_recibida = $(this).find("input.cantidad").val();
				datos.push(item);
		});
		return datos;
	}

	// carga los depositos de acuerdo a establecimiento
	function seleccionesta(opcion){

			WaitingOpen("Buscando Depósitos...");
			var id_esta = $("#esta_dest_id").val();
			$.ajax({
					type: 'POST',
					data: {id_esta},
					url: 'index.php/<?php echo ALM?>Movimientodeporecepcion/traerDepositos',
					success: function(data) {

							var resp = JSON.parse(data);
							$('#depo_id').empty();
							if(data != null){
									for(var i=0; i<resp.length; i++)
									{
										$('#depo_id').append("<option value='" + resp[i].depo_id + "'>" +resp[i].descripcion+"</option");
									}
									$("#depo_id").removeAttr('readonly');
							}else{
									$("#depo_id").append("<option value=''>-Sin Depósitos para este Establecimiento-</option");
							}
							WaitingClose();
					},
					error: function(data) {

							alert('Error');
					}
			});
	}

	//Date picker
	$('#datepicker').datepicker({
			autoclose: true
	});

</script>