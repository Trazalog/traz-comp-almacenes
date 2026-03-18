<script>
  $(document).ready(function() {
            $('#grupos').DataTable({
                dom: 'Bfrtip',
                // buttons: [
                //     'copy', 'csv', 'excel', 'pdf', 'print'
                // ]
																buttons: [{
																	//Botón para Excel
																	extend: 'excel',
																	exportOptions: {
																									columns: ':visible' // columns: [1, 2, 3, 4]
																	},
																	footer: true,
																	title: 'Listado de Artículos',
																	filename: 'Listado de Artículos',

																	//Aquí es donde generas el botón personalizado
																	text: '<button class="btn btn-success ml-2 mb-2 mb-2 mt-3">Exportar a Excel <i class="fa fa-file-excel-o"></i></button>'
													},
													// //Botón para PDF
													{
																	extend: 'pdf',
																	exportOptions: {
																					// columns: [1, 2, 3, 4]
																					columns: ':visible'
																	},
																	footer: true,
																	title: 'Listado de Artículos',
																	filename: 'Listado de Artículos',
																	text: '<button class="btn btn-danger ml-2 mb-2 mb-2 mt-3">Exportar a PDF <i class="fa fa-file-pdf-o mr-1"></i></button>'
													},
													{
																	extend: 'copy',
																	exportOptions: {
																					// columns: [1, 2, 3, 4]
																					columns: ':visible'
																	},
																	footer: true,
																	title: 'Listado de Artículos',
																	filename: 'Listado de Artículos',
																	text: '<button class="btn btn-primary ml-2 mb-2 mb-2 mt-3">Copiar <i class="fa fa-file-text-o mr-1"></i></button>'
													},
													{
																	extend: 'print',
																	exportOptions: {
																					// columns: [1, 2, 3, 4]
																					columns: ':visible'
																	},
																	footer: true,
																	title: 'Listado de Artículos',
																	filename: 'Listado de Artículos',
																	text: '<button class="btn btn-default ml-2 mb-2 mb-2 mt-3">Imprimir <i class="fa fa-print mr-1"></i></button>'
													}
												],
												'lengthMenu':[[10,25,50,100,],[10,25,50,100]],
            });
        });
</script>
<table id="grupos" class="table table-bordered table-striped">
	<thead>
		<tr>
		  <th>Fecha</th>
		  <th>Codigo</th>
		  <th>Artículo</th>
		  <th>Lote</th>
				<th>C. Entregada</th>
				<th>U.Medida</th>
				<th>Dep. Desde</th>      
				<th>Obra</th>               
		</tr>
	</thead>
	<tbody>
	
	<?php foreach($data as $dato): ?>
    <tr>
        <td><?= $dato['to_char']       ?></td>
        <td><?= $dato['pema_id']       ?></td>
        <td><?= $dato['barcode']   ?></td>  <!-- artículo -->
        <td><?= $dato['lote']          ?></td>
        <td><?= $dato['cantidad']      ?></td>
        <td><?= $dato['unidad_medida'] ?></td>
        <td><?= $dato['descripcion']      ?></td>  
        <td><?= $dato['obra'] ?></td>  
    </tr>
<?php endforeach; ?>
	</tbody>
</table>


