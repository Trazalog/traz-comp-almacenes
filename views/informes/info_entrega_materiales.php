<div id="reportContent" class="report-content">
    <div class="box box-primary">

        <div class="box-header with-border">
            <div class="box-tittle">
                <h4>Entrega Materiales Detallada</h4>
            </div>
        </div>

        <div class="box-body">

            <!-- _____ GRUPO 1 _____ -->
            <div class="col-md-12">
                <div class="form-group">

                    <div class="col-md-4 col-md-6 mb-4 mb-lg-0">
                        <label>Desde <strong class="text-danger">*</strong> :</label>
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
																						<label for="obra" class="form-label">Obras<strong
																														class="text-danger">*</strong> :</label>
																						<select class="form-control select2 select2-hidden-accesible"
																										id="obra" name="obra">
																										<option value="TODOS"> - TODOS - </option>
																										<?php
																														foreach ($obras as $ob) {
																																		$selected = $first ? 'selected' : '';
																																		echo '<option value="'.$ob->tabl_id.'">'.$ob->descripcion.'</option>';
																																		$first = false;
																														}
																										?>
																							</select>
		              						</div>
                </div>
            </div>

												<div class="col-md-12">
                <br>
            </div>

            <!-- _____ GRUPO 2 _____ -->
												<div class="col-md-12">

																<div class="form-group">		

																						<div class="col-md-6 col-md-6 mb-6 mb-lg-0">
                        <label>Establecimiento<strong class="text-danger">*</strong>:</label>
																								<select class="form-control" id="establecimiento"
																												name="establecimiento" onchange="seleccionesta(this)" required>
																												<option value="TODOS" disabled selected>- TODOS -</option>
																												<?php																										
																												foreach ($establecimientos as $i) {																														
																																echo '<option value="'.$i->nombre.'" class="emp" data-json=\''.json_encode($i).'\' >'.$i->nombre.'</option>';																																
																												}
																												?>
																								</select>
		              						</div>

																						<div class="col-md-6 col-md-6 mb-6 mb-lg-0">
                        <label>Depósito<strong class="text-danger">*</strong>:</label>
                    				<select class="form-control" id="deposito" name="deposito" 
                        required>
                        <option value="TODOS" disabled selected>- TODOS -</option>
                    				</select>
		              						</div>
																</div>				

												</div>

            <div class="col-md-12">
                <br>
            </div>

            <div class="form-group col-xs-12">
                <div class="form-group">
                    <button type="button" class="btn btn-success btn-flat col-xs-12 col-sm-3 col-md-3 col-lg-3"
                        onclick="filtrar()" style="float: right !important;">Filtrar</button>
                </div>
            </div>

            <!--_______ TABLA _______-->
            <div class="col-md-12" id="tabla">
       
            </div>
            <!-- <div id="acciones" class="" style="float: right !important;">
                <button type="button" class="btn btn-primary" onclick="exportarExcel()">Exportar</button>
            </div> -->
        </div>
    </div>
</div>

<script>


    $('#tabla').empty();
    $("#tabla").load("<?php echo base_url(ALM); ?>Informe/cargaTabla", function() {
       
    });

// filtrado de datos
function filtrar() {
    
    // wo();
    var data = {};
    var desde = $("#datepickerDesde").val();
    var hasta = $("#datepickerHasta").val();
    var obra = $("#obra").val();
				var deposito = $('#deposito option:selected').val();

				// if (desde == '' || hasta == '' || deposito == '') {
					//     Swal.fire(
					//         'Error...',
					//         'Debes completar los campos Obligatorios (*)',
					//         'error'
					//     );
					//     return;
    // }
				
    wo();
    $.ajax({
        type: 'POST',
        data: {
            desde: desde,
            hasta: hasta,
            obra: obra,
            depo: deposito
        },
        url: '<?php echo base_url(ALM) ?>Informe/cargaTabla',
        success: function(result) {
            wc();
           
            $('#tabla').empty();
            $('#tabla').html(result);

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
    // Esto asegura que al elegir un rango rápido, los inputs se actualicen
    $('#daterange-btn').on('apply.daterangepicker', function(ev, picker) {
        $('#datepickerDesde').val(picker.startDate.format('YYYY-MM-DD'));
        $('#datepickerHasta').val(picker.endDate.format('YYYY-MM-DD'));
    }); 
}

// Al seleccionar establecimiento, busca depositos
function seleccionesta(opcion){
    WaitingOpen('Buscando Depositos...');
    var id_esta = $("#establecimiento").val();
    json = JSON.parse($("#establecimiento>option:selected").attr("data-json"));
    id_esta = json.esta_id;
    $.ajax({
        type: 'POST',
        data: {id_esta},
        url: 'index.php/<?php echo ALM?>Movimientodeposalida/traerDepositos',
        success: function(data) {
            var resp = JSON.parse(data);
            WaitingClose();
            $('#deposito').empty();
            $("#deposito").removeAttr('readonly');
            if (resp == null) {
                    $('#deposito').append('<option value="" disabled selected>-Sin Depósitos para este Establecimiento-</option>');
                    //reseteo select tipo de ajuste
                    $("#tipoajuste").val('');
                    $("#tipoajuste").attr('disabled','disabled');
            } else {
                $('#deposito').append('<option value="" disabled selected>-Seleccione Depósito-</option>');
                for(var i=0; i<resp.length; i++)
                {
                    $('#deposito').append("<option value='" + resp[i].depo_id + "'>" +resp[i].descripcion+"</option");
                }
            }
        },
        error: function(data) {
            alert('Error');
            WaitingClose();
        }
    });
}


</script>