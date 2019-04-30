<?php info_header('Cabecera Informacion 1',''); ?>
<?php info_header('Cabecera Informacion 2',''); ?>
<?php info_header('Cabecera Informacion 3',''); ?>
<input id="tarea" data-info="">
<input type="text" class="form-control hidden" id="asignado" value="<?php echo $tarea->assigned_id?>">

<div class="nav-tabs-custom ">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Información</a></li>
		<li class="privado"><a href="#tab_2" data-toggle="tab" aria-expanded="false">Comentarios</a></li>
		<li class="privado"><a href="#tab_3" data-toggle="tab" aria-expanded="false">Vista Global</a></li>
		<li class="privado"><a href="#tab_4" data-toggle="tab" aria-expanded="false">Acciones</a></li>
		
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
				Dropdown <span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Action</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Another action</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Vista Global</a></li>
				<li role="presentation" class="divider"></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Separated link</a></li>
			</ul>
		</li>
		<li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
		
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_1">
			
			<?php
				echo "<button class='btn btn-block btn-success btn-tomar' onclick='tomarTarea()'>Tomar tarea</button>";

				echo "<button class='btn btn-block btn-danger grupNoasignado btn-soltar' onclick='soltarTarea()'>Soltar tarea</button>";													

				var_dump($tarea);
			?>
		</div>
		<!-- /.tab-pane -->
		<div class="tab-pane" id="tab_2">
			<h2>Comentarios</h2>
		</div>
		<!-- /.tab-pane -->
		<div class="tab-pane" id="tab_3">
			<h2>Vista Global</h2>
		</div>

		<div class="tab-pane" id="tab_4">
			<?php echo $view ?>
		</div>
		<!-- /.tab-pane -->
	</div>
	<!-- /.tab-content -->
</div>

<?php $this->load->view('bpm/scripts/tarea_std'); ?>

