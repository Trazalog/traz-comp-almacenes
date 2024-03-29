<section >
  <div class="row">
    <div class="col-xs-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Recepción de Materiales</h3>

        </div><!-- /.box-header -->

        <div class="box-body">
          <?php
            if (strpos($permission,'Add') !== false) {
              echo '<button class="btn btn-block btn-primary" style="width: 100px; margin: 10px;" onclick="linkTo(\''.ALM.'Remito/cargarlista\')">Agregar</button>';
            }
          ?>
          <table id="tbl-remitos" class="table table-bordered table-hover">
            <thead>
              <tr>                
                <th width="1%">Acciones</th>
                <th>Nº de Comprobante</th>
                <th>Fecha</th>
                <th>Proveedor</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if(isset($list['data'])){
             
              foreach($list['data'] as $remito)
              { 
                $id=$remito['remitoId'];
                echo '<tr id="'.$id.'">';
                  echo '<td class="text-center">';
                  echo '<i class="fa fa-fw fa-search text-light-blue" style="cursor: pointer;" title="Consultar"></i>';
                  echo '</td>';
                  echo '<td>'.$remito['comprobante'].'</td>';
                  echo '<td>'.$remito['fecha'].'</td>';
                  echo '<td>'.$remito['provnombre'].'</td>';
                echo '</tr>';
              }}
              ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->

<script>
$('#modalvista').on('shown.bs.modal', function (e) {
  $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
});

var comglob = "";
var ide     = "";
var edit    = 0;
var datos   = Array();

$(".fa-search").click(function (e) { 
   // console.log("Estoy Consultando"); 
    var idremito = $(this).parent('td').parent('tr').attr('id');
   // console.log("id de remito: "+idremito);
    wo();
    $.ajax({
      data: { idremito: idremito},
      dataType: 'json',
      type: 'POST',
      url: 'index.php/<?php echo ALM ?>Remito/consultar',
      success: function(data){

        $('#comprobanteV').val(data['datosRemito'][0]['comprobante']);
        $('#fechaV').val(data['datosRemito'][0]['fecha']);
        $('#proveedorV').val(data['datosRemito'][0]['provnombre']);

        tabla = $('#tablaconsulta').DataTable(); 
        tabla.clear().draw();
        if(data['datosDetaRemitos'] == null) return;
        for (var i = 0; i < data['datosDetaRemitos'].length; i++) { 
          $('#tablaconsulta').DataTable().row.add( [
            data['datosDetaRemitos'][i]['codigo'],
            data['datosDetaRemitos'][i]['artdescription'],
            data['datosDetaRemitos'][i]['cantidad'],
            data['datosDetaRemitos'][i]['depositodescrip'],
            data['datosDetaRemitos'][i]['nomesta'],
          ]).draw();
        }

        $('#modalvista').modal('show');
        
      },
      error: function(result){
        alert("Error al traer datos de remito")
        console.table(result);
      },complete: function(){
        wc();
      }
    });   
});

//DataTable('#tbl-remitos');

//Funcion de datatable para extencion de botones exportar
//excel, pdf, copiado portapapeles e impresion


    $('#tbl-remitos').DataTable({
        responsive: true,
        language: {
            url: '<?php base_url() ?>lib/bower_components/datatables.net/js/es-ar.json' //Ubicacion del archivo con el json del idioma.
        },
        dom: 'lBfrtip',
        buttons: [{
                //Botón para Excel
                extend: 'excel',
                exportOptions: {
                    columns: [1, 2, 3]
                },
                footer: true,
                title: 'Recepción de Materiales',
                filename: 'Recepción de Materiales',

                //Aquí es donde generas el botón personalizado
                text: '<button class="btn btn-success ml-2 mb-2 mb-2 mt-3">Exportar a Excel <i class="fa fa-file-excel-o"></i></button>'
            },
            // //Botón para PDF
            {
                extend: 'pdf',
                exportOptions: {
                  columns: [1, 2, 3]
                },
                footer: true,
                title: 'Recepción de Materiales',
                filename: 'Recepción de Materiales',
                text: '<button class="btn btn-danger ml-2 mb-2 mb-2 mt-3">Exportar a PDF <i class="fa fa-file-pdf-o mr-1"></i></button>'
            },
            {
                extend: 'copy',
                exportOptions: {
                  columns: [1, 2, 3]
                },
                footer: true,
                title: 'Recepción de Materiales',
                filename: 'Recepción de Materiales',
                text: '<button class="btn btn-primary ml-2 mb-2 mb-2 mt-3">Copiar <i class="fa fa-file-text-o mr-1"></i></button>'
            },
            {
                extend: 'print',
                exportOptions: {
                  columns: [1, 2, 3]
                },
                footer: true,
                title: 'Recepción de Materiales',
                filename: 'Recepción de Materiales',
                text: '<button class="btn btn-default ml-2 mb-2 mb-2 mt-3">Imprimir <i class="fa fa-print mr-1"></i></button>'
            }
        ]
    });

var table = $('#tablaconsulta').DataTable( {
    "aLengthMenu": [ 10, 25, 50, 100 ],
    "columnDefs": [ {
      "targets": [ 0 ], 
      "searchable": true
    },
    {
      "targets": [ 0 ], 
      "orderable": true
    } ],
    "order": [[0, "asc"]],
    "language":{
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ registros",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar:",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
    }

  });
</script>


<!-- Modal CONSULTA-->
<div class="modal" id="modalvista" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"  id="myModalLabel"><span id="modalAction" class="fa fa-fw fa-search-plus text-light-blue"></span> Detalle Recepción de Materiales</h4>
      </div> <!-- /.modal-header  -->

      <div class="modal-body" id="modalBodyArticle">
        
          <div class="row" id="infoOI">
            <div class="col-xs-12 col-sm-6 col-md-4">
              <label for="comprobanteV">Nº de Comprobante:</label>
              <input type="text" class="form-control" id="comprobanteV" name="comprobanteV" disabled>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <label for="fechaV">Fecha:</label>
              <input type="text" class="form-control" id="fechaV" name="fechaV" disabled>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <label for="proveedorV">Proveedor:</label>
              <input type="text" class="form-control" id="proveedorV" name="proveedorV" disabled>
            </div>
          </div><br>
      
        <div class="row">
          <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-12" id="btn-datatables"></div>
            </div><br>
            <table class="table table-bordered" id="tablaconsulta"> 
              <thead>
                <tr>
                  <th>Código</th>
                  <th>Descripción</th>
                  <th>Cantidad</th>
                  <th>Depósito</th>
                  <th>Establecimiento</th>
                </tr>
              </thead>
              <tbody>
                <!-- -->
              </tbody>
            </table>
          </div>
        </div> 
      </div>  <!-- /.modal-body -->
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>  <!-- /.modal footer -->

    </div> <!-- /.modal-content -->
  </div>  <!-- /.modal-dialog modal-lg -->
</div>  <!-- /.modal fade -->
<!-- / Modal -->