<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Entrega Materiales</h3>
  
        </div><!-- /.box-header -->

        <div class="box-body">
          <table id="insumo" class="table table-bordered table-hover">
            <thead>
              <tr>                
                <th>Acciones</th>
                <th>Orden de Insumo</th>
                <th>Orden de Trabajo</th>
                <th>N° Comprobante</th>
                <th>Solicitante</th>
                <th>Fecha Entrega</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if(isset($list['data'])){
              foreach($list['data'] as $a)
              { 
                $id=$a['id_orden'];
                echo '<tr id="'.$id.'">';
                echo '<td>';
                //echo '<i class="fa fa-fw fa-print text-light-blue" style="cursor: pointer; margin-left: 15px;" title="Imprimir"  ></i> ';
                echo '<i class="fa fa-fw fa-search-plus text-light-blue" style="cursor: pointer; margin-left: 15px;" data-toggle="modal" data-target="#modalvista" title="Consultar"></i> ';
                echo '</td>';
                echo '<td>'.$a['id_orden'].'</td>';
                echo '<td>'.$a['id_ot'].'</td>';
                //echo '<td>'.$a['descripcion'].'</td>';
                echo '<td>'.$a['comprobante'].'</td>';
                echo '<td>'.$a['solicitante'].'</td>';
                echo '<td>'.date_format(date_create($a['fecha']), 'd-m-Y').'</td>';
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
  $($.fn.dataTable.tables(true)).DataTable()
    .columns.adjust();
});
//  var isOpenWindow = false;
var comglob = "";
var ide     = "";
var edit    = 0;
var datos   = Array();

  datos=Array();


  $(".fa-search-plus").click(function (e) { 
    $('#total').val(''); 
    //$("#modalvista tbody tr").remove(); 
    console.log("Esto Consultando"); 
    var idor = $(this).parent('td').parent('tr').attr('id');
    console.log("id orden de insumo: "+idor);
    $.ajax({
      type: 'POST',
      data: { idor: idor},
      url: 'index.php/almacen/Ordeninsumo/consultar', //index.php/
      success: function(data){
        //var data = jQuery.parseJSON( data );
        

        datos={
          'id':data['datos'][0]['enma_id'],
          'fecha':data['datos'][0]['fecha'],
          'solicitante':data['datos'][0]['solicitante'],
          'comprobante':data['datos'][0]['comprobante'],
          'id_ot':data['datos'][0]['ortr_id'],
        }
                    
        $('#total').val(data['total'][0]['cantidad']);
        $('#orden').val(data['datos'][0]['enma_id']);
        $('#fecha').val(data['datos'][0]['fecha']);
        $('#id_ot').val(data['datos'][0]['ortr_id']);

        tabla = $('#tablaconsulta').DataTable(); 
        tabla.clear().draw();
        for (var i = 0; i < data['equipos'].length; i++) { 
          $('#tablaconsulta').DataTable().row.add( [
            data['equipos'][i]['artBarCode'],
            data['equipos'][i]['artDescription'],
            data['equipos'][i]['codigo'],
            data['equipos'][i]['depositodescrip'],
            data['equipos'][i]['cantidad']
          ]).draw();
        }
      },
      error: function(result){
        console.log(result);
      },
      dataType: 'json'
    });   
  });

  $('#insumo').DataTable({
    "aLengthMenu": [ 10, 25, 50, 100 ],
    "columnDefs": [ {
      "targets": [ 0 ], 
      "searchable": false
    },
    {
      "targets": [ 0 ], 
      "orderable": false
    } ],
    "order": [[1, "asc"]],
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
  });


// imprime consulta de insumos
$(".imprimir").click(function (e) {        
    $('.acciones, .dataTables_filter, .dataTables_length, .dataTables_info, .dataTables_paginate paging_full_numbers, #modalAction').addClass('no-print');  
    $('a[href]').addClass('no-print');     
    $(".impresion").printArea();   
  });
</script>


<!-- Modal CONSULTA-->
<div class="modal" id="modalvista" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content impresion">

      <div class="modal-header">
        <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"  id="myModalLabel"><span id="modalAction" class="fa fa-fw fa-search-plus text-light-blue"></span> Consulta Orden de Insumos</h4>
      </div> <!-- /.modal-header  -->

      <div class="modal-body" id="modalBodyArticle">
        <div class="row" id="infoOI">
          <div class="col-xs-12 col-sm-6 col-md-4">
            <label for="orden">Orden de insumo:</label>
            <input type="text" class="form-control" id="orden" name="orden" disabled>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-4">
            <label for="fecha">Fecha:</label>
            <input type="text" class="form-control" id="fecha" name="fecha" disabled>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-4">
            <label for="fecha">Orden de trabajo:</label>
            <input type="text" class="form-control" id="id_ot" name="id_ot" disabled>
          </div>
          <div class="clearfix"></div>
        </div><br>
      
        <div class="row">
          <div class="col-xs-12">
            <hr>
            <div class="row">
              <div class="col-xs-12" id="btn-datatables"></div>
            </div><br>
            <table class="table table-bordered compact" id="tablaconsulta"> 
              <thead>
                <tr>
                  <th>Articulo</th>
                  <th>Descripción</th>
                  <th>N° Lote</th>
                  <th>Deposito</th>
                  <th>Cantidad</th>
                </tr>
              </thead>
              <tbody>
                <!-- -->
              </tbody>
            </table>
          </div>
        </div> 

        <div class="row">
          <div class="col-xs-12">
            <label for="total">Total:</label>
            <input type="text" class="form-control" id="total" name="total">
          </div>
        </div>
      </div>  <!-- /.modal-body -->
      
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-default imprimir no-print" data-dismiss="modal">Imprimir</button> -->
        <button type="button" class="btn btn-default no-print" data-dismiss="modal">Cerrar</button>
      </div>  <!-- /.modal footer -->

    </div> <!-- /.modal-content -->
  </div>  <!-- /.modal-dialog modal-lg -->
</div>  <!-- /.modal fade -->
<!-- / Modal -->