  <table id="stockValorizado" class="table table-bordered table-hover">
    <thead>    
        <th>Código</th>
        <th>Descripción</th>
        <th class="text-center">Cantidad</th>
        <th class="text-center">Precio Un. $  </th>
        <th class="text-center">Precio Un. s$d  </th>
        <th class="text-center">Total En $  </th>
        <th class="text-center">Total En u$d  </th>
        
    </thead>
    <tbody>
        <?php 
            foreach($list as $f){
            echo "<tr data-json='".json_encode($f)."'>";            
            echo '<td>'.((empty($f->barcode) && $f->barcode == 1) ? 'S/L' : $f->barcode).'</td>';            
            echo '<td>'.$f->descripcion.'</td>';
            echo '<td class="text-center">'.((empty($f->cantidad)) ? '0' : $f->cantidad).'</td>';            
            echo '<td class="text-center">'.((empty($f->p_pesos)) ? '0' : $f->p_pesos).'</td>';            
            echo '<td class="text-center">'.((empty($f->p_dolar)) ? '0' : $f->p_dolar).'</td>';            
            echo '<td class="text-center">'.((empty($f->t_pesos)) ? '0' : $f->t_pesos).'</td>';    
            echo '<td class="text-center">'.((empty($f->t_dolar)) ? '0' : $f->t_dolar).'</td>';    
            echo '</tr>';
            }
        ?>
    </tbody>
</table>
     
<script>
// extrae datos de la tabla
$(".btnInfo").on("click", function(e) {
    $(".modal-header h4").remove();
    //guardo el tipo de operacion en el modal
    $("#operacion").val("Info");
    //pongo titulo al modal
    $(".modal-header").append(
        '<h4 class="modal-title"  id="myModalLabel"><span id="modalAction" class="fa fa-fw fa-search"></span> Detalle Stock Valorizado</h4>'
    );
    data = $(this).parents("tr").attr("data-json");
    datajson = JSON.parse(data);
    blockEdicion();
    llenarModal(datajson);
});

//Funcion de datatable para extencion de botones exportar
//excel, pdf, copiado portapapeles e impresion
$(document).ready(function() {
    $('#stockValorizado').DataTable({
        responsive: true,
        iDisplayLength: 10,
        rowGroup: {
         enable: false
        },
        language: {
            url: '<?php base_url() ?>lib/bower_components/datatables.net/js/es-ar.json' //Ubicacion del archivo con el json del idioma.
        },
        dom: 'lBfrtip',
        destroy: true,
        buttons: [{
                //Botón para Excel
                extend: 'excel',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                },
                footer: true,
                title: 'Excel Stock Valorizado',
                filename: 'Excel_Stock',

                //Aquí es donde generas el botón personalizado
                text: '<button class="btn btn-success ml-2 mb-2 mb-2 mt-3">Exportar a Excel <i class="fa fa-file-excel-o"></i></button>'
            },
            // //Botón para PDF
             {
                 extend: 'pdf',
                 exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                 },
                 footer: true,
                 title: 'Excel Stock Valorizado',
                 filename: 'Excel_Stock',
                 text: '<button class="btn btn-danger ml-2 mb-2 mb-2 mt-3">Exportar a PDF <i class="fa fa-file-pdf-o mr-1"></i></button>'
             },
            {
                extend: 'copy',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                },
                footer: true,
                title: 'Excel Stock Valorizado',
                filename: 'Excel_Stock',
                text: '<button class="btn btn-primary ml-2 mb-2 mb-2 mt-3">Copiar <i class="fa fa-file-text-o mr-1"></i></button>'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                },
                footer: true,
                title: 'Excel Stock Valorizado',
                filename: 'Excel_Stock',
                text: '<button class="btn btn-default ml-2 mb-2 mb-2 mt-3">Imprimir <i class="fa fa-print mr-1"></i></button>'
            }
        ]
    });
});
</script>