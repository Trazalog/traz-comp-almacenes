<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">ABM Tablas</h3>
	</div>

		<div class="box-body">
			<div class="row">
				<div class="col-sm-4	">
				<div class="form-group">
					<label>Selecccionar Tabla:</label>
					<select id="tablas" class="form-control">
						<option value="0" disabled selected>Seleccionar Tabla...</option>
						<option value="new"> + Nueva Tabla</option>
						<?php 
								foreach ($list as $o) {
										echo '<option value="'.$o['tabla'].'">'.$o['tabla'].'</option>';
								}
						?>
					</select>
				</div>
			</div>
			</div>
			<table class="table">
				<thead>
					<th>ID</th>
					<th>Valor</th>
					<th>Descripcion</th>
					<th>Acciones</th>
				</thead>
				<tbody>
					<?php 
							echo '<tr data_id="'.$o['tabl_id'].'">';
								echo '<td>'.$o['tabl_id'].'</td>';
								echo '<td>'.$o['valor'].'</td>';
								echo '<td>'.$o['descripcion'].'</td>';
								echo '<td><i class="fa fa-fw fa-pencil text-light-blue" style="cursor: pointer; margin-left: 15px;" title="Editar" data-toggle="modal" data-target="#modaleditar"></i><i class="fa fa-fw fa-times-circle text-light-blue" style="cursor: pointer; margin-left: 15px;" title="Eliminar" onclick="seleccionar(this)"></i></td>';
							echo '</tr>';
					?>		
				</tbody>
			</table>
		</div>
		<!-- box-body -->
	</div>
<!-- /.box-primary -->


<script>
	$('#tablas').on('change', function () {
		if (this.value == "new") { $('.modal').modal('show'); this.value = 0; return; }
	});
	function new_tabla() {

	}

</script>

<div class="modal fade " id="modal-default">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Default Modal</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="tabla">Nombre Tabla</label>
					<input type="email" class="form-control" id="tabla" placeholder="Nombre Tabla">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary" onclick="new_tabla()">Guardar</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->