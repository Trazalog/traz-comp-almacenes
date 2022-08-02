<style>
.cant_negativa {
    background-color: #f5d3d3 !important;
}
</style>
<input type="hidden" id="permission" value="<?php echo $permission;?>">
<section>

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Puntos de Pedido</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
            <table id="stock" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Código</th>
                        <th class="text-center">Punto Pedido</th>
                        <th class="text-center">Cant. Stock</th>
                        <th class="text-center">Cant. Disponible</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                           

                	foreach($list as $f)
      		        {
                    echo '<tr '.($f['cantidad_disponible']<0?"class=\"cant_negativa\"":"").'>';
                    echo '<td>'.$f['descripcion'].'</td>';
                    echo '<td>'.$f['barcode'].'</td>';
                    echo '<td class="text-center">'.$f['punto_pedido'].'</td>';
                    echo '<td class="text-center">'.$f['cantidad_stock'].'</td>';
                    echo '<td class="text-center '.($f['cantidad_disponible']<0?"text-danger":"").'">'.$f['cantidad_disponible'].'</td>';
  	                echo '</tr>';
      		        }
                
              ?>
                </tbody>
            </table>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

</section><!-- /.content -->

<script>
//DataTable($('#stock'),false);

//Funcion de datatable para extencion de botones exportar
//excel, pdf, copiado portapapeles e impresion

$(document).ready(function() {
    $('#stock').DataTable({
        responsive: true,
        language: {
            url: '<?php base_url() ?>lib/bower_components/datatables.net/js/es-ar.json' //Ubicacion del archivo con el json del idioma.
        },
        dom: 'lBfrtip',
        buttons: [{
                //Botón para Excel
                extend: 'excel',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                },
                footer: true,
                title: 'Punto de Pedido',
                filename: 'Punto de Pedido',

                //Aquí es donde generas el botón personalizado
                text: '<button class="btn btn-success ml-2 mb-2 mb-2 mt-3">Exportar a Excel <i class="fa fa-file-excel-o"></i></button>'
            },
            // //Botón para PDF
            {
                extend: 'pdf',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                },
                footer: true,
                title: 'Punto de Pedido',
                filename: 'Punto de Pedido',
                text: '<button class="btn btn-danger ml-2 mb-2 mb-2 mt-3">Exportar a PDF <i class="fa fa-file-pdf-o mr-1"></i></button>'
            },
            {
                extend: 'copy',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                },
                footer: true,
                title: 'Punto de Pedido',
                filename: 'Punto de Pedido',
                text: '<button class="btn btn-primary ml-2 mb-2 mb-2 mt-3">Copiar <i class="fa fa-file-text-o mr-1"></i></button>'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                },
                footer: true,
                title: 'Punto de Pedido',
                filename: 'Punto de Pedido',
                text: '<button class="btn btn-default ml-2 mb-2 mb-2 mt-3">Imprimir <i class="fa fa-print mr-1"></i></button>'
            }
        ]
    });
});
</script>

