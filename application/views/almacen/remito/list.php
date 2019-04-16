<input type="hidden" id="permission" value="<?php echo $permission;?>">
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Remitos</h3>
          <?php
          if (strpos($permission,'Add') !== false) {
            echo '<button class="btn btn-block btn-primary" style="width: 100px; margin-top: 10px;" id="btnAgre">Agregar</button>';
          }
          ?>
        </div><!-- /.box-header -->

        <div class="box-body">
          <table id="tbl-remitos" class="table table-bordered table-hover">
            <thead>
              <tr>                
                <th>Acciones</th>
                <!--<th>Id de Remito</th>-->
                <th>Nº de Comprobante</th>
                <th>Fecha</th>
                <th>Proveedor</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if(isset($list))
              foreach($list['data'] as $remito)
              { 
                $id=$remito['remitoId'];
                echo '<tr id="'.$id.'">';
                  echo '<td>';
                    //echo '<i class="fa fa-fw fa-print text-light-blue" style="cursor: pointer; margin-left: 15px;" title="Imprimir"  ></i> ';
                    echo '<i class="fa fa-fw fa-search-plus text-light-blue" style="cursor: pointer; margin-left: 15px;" data-toggle="modal" data-target="#modalvista" title="Consultar"></i>';
                  echo '</td>';
                  //echo '<td>'.$remito['remitoId'].'</td>';
                  echo '<td>'.$remito['comprobante'].'</td>';
                  //echo '<td>'.date_format(date_create($remito['fecha']), 'd-m-Y').'</td>';
                  echo '<td>'.$remito['fecha'].'</td>';
                  echo '<td>'.$remito['provnombre'].'</td>';
                echo '</tr>';
              }
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

var comglob = "";
var ide     = "";
var edit    = 0;
var datos   = Array();
//datos=Array();
  
$('#btnAgre').click( function cargarVista(){
  WaitingOpen();
  $('#content').empty();
  $("#content").load("<?php echo base_url(); ?>index.php/Remito/cargarlista/<?php echo $permission; ?>");
  WaitingClose();
});

$(".fa-search-plus").click(function (e) { 
    console.log("Estoy Consultando"); 
    var idremito = $(this).parent('td').parent('tr').attr('id');
    console.log("id de remito: "+idremito);
    $.ajax({
      data: { idremito: idremito},
      dataType: 'json',
      type: 'POST',
      url: 'index.php/Remito/consultar',
      success: function(data){
        console.table(data);

        $('#comprobanteV').val(data['datosRemito'][0]['comprobante']);
        $('#fechaV').val(data['datosRemito'][0]['fecha']);
        $('#proveedorV').val(data['datosRemito'][0]['provnombre']);

        tabla = $('#tablaconsulta').DataTable(); 
        tabla.clear().draw();
        for (var i = 0; i < data['datosDetaRemitos'].length; i++) { 
          $('#tablaconsulta').DataTable().row.add( [
            data['datosDetaRemitos'][i]['artBarCode'],
            data['datosDetaRemitos'][i]['artDescription'],
            data['datosDetaRemitos'][i]['cantidad'],
            data['datosDetaRemitos'][i]['depositodescrip'],
          ]).draw();
        }
        /*
        console.log(data['datos'][0]['codigo']);
        console.log(data['datos'][0]['descripcion']);
        console.log(data['total'][0]['cantidad']);
        console.log(data['equipos'][0]['deposito']);
        */



        // console.log(data.datos.abonodescrip);
        //console.log(data['datos']['id_ordeninsumo']);
        //console.log(data['datos'][1]['abonodescrip']);

        /*datos={
          'id':data['datos'][0]['id_orden'],
          'fecha':data['datos'][0]['fecha'],
          'solicitante':data['datos'][0]['solicitante'],
          'comprobante':data['datos'][0]['comprobante'],
          'id_ot':data['datos'][0]['id_ot'],
        }
                    

        tabla = $('#tablaconsulta').DataTable(); 
        tabla.clear().draw();
        for (var i = 0; i < data['equipos'].length; i++) { 
          $('#tablaconsulta').DataTable().row.add( [
            '',
            data['equipos'][i]['artBarCode'],
            data['equipos'][i]['artDescription'],
            data['equipos'][i]['depositodescrip'],
            data['equipos'][i]['cantidad']
          ]).draw();
        }*/
      },
      error: function(result){
        console.error("Error al traer datos de remito")
        console.table(result);
      },
    });   
});

$('#tbl-remitos').DataTable({
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
var buttons = new $.fn.dataTable.Buttons(table, {
    buttons: [
    {
      extend: 'print',
      text: 'Imprimir',
      className: "btn btn-primary",
      title: '',
      //messageTop: '<strong>Mensaje entre el titulo y la tabla..</strong>',
      init: function(api, node, config) {
        $(node).removeClass('btn-default')
      },

      customize: function ( win ) {
        $(win.document.body)
          .css('font-size', '10pt')
          /*.prepend(
          '<img src="http://datatables.net/media/images/logo-fade.png" style="position:absolute; top:0; left:0;" />'
          )*/;
        
        $(win.document.body).find( 'table' )
          .addClass( 'table-condensed' )
          .css( 'font-size', 'inherit' );

        $('#infoOI').clone().prependTo( win.document.body );
        $(win.document.body).prepend('<h1>Remito</h1>');
      } 
    }
    ]
  }).container().appendTo($('#btn-datatables'));



</script>


<!-- Modal CONSULTA-->
<div class="modal" id="modalvista" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"  id="myModalLabel"><span id="modalAction" class="fa fa-fw fa-search-plus text-light-blue"></span> Consulta de Remito</h4>
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
                </tr>
              </thead>
              <tbody>
                <!-- -->
              </tbody>
            </table>
          </div>
        </div> 

        <!--<div class="row">
          <div class="col-xs-12">
            <label for="total">Total:</label>
            <input type="text" class="form-control" id="total" name="total">
          </div>
        </div>-->
      </div>  <!-- /.modal-body -->
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>  <!-- /.modal footer -->

    </div> <!-- /.modal-content -->
  </div>  <!-- /.modal-dialog modal-lg -->
</div>  <!-- /.modal fade -->
<!-- / Modal -->