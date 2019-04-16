<input type="hidden" id="permission" value="<?php echo $permission;?>">
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Nota de Pedido</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="deposito" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Acciones</th>
                <th>Nota de Pedido</th>
                <th>Orden de Trabajo</th>
                <th>Detalle</th>
                <th>Fecha Nota</th>              
              </tr>
            </thead>
            <tbody>
              <?php
              if($list) {
                foreach($list as $z)
                {
                  $id = $z['id_notaPedido'];
                  echo '<tr id="'.$id.'" class="'.$id.'" >';
                  echo '<td>';
                  /*if (strpos($permission,'Edit') !== false) {
                    echo '<i class="fa fa-fw fa-pencil text-light-blue" style="cursor: pointer; margin-left: 15px;" title="Editar" data-toggle="modal" data-target="#modaleditar"></i>';
                  }
                  if (strpos($permission,'Del') !== false) {
                    echo '<i class="fa fa-fw fa-times-circle text-light-blue" style="cursor: pointer; margin-left: 15px;"></i>';
                  } */
                  //if (strpos($permission,'View') !== false) {
                    echo '<i class="fa fa-fw fa-search text-light-blue" style="cursor: pointer; margin-left: 15px;" title="Ver Nota Pedido" data-toggle="modal" data-target="#modaltarea"></i>';
                  //}                     
                  echo '</td>';
                  echo '<td>'.$z['id_notaPedido'].'</td>';
                  echo '<td>'.$z['id_ordTrabajo'].'</td>';
                  echo '<td>'.$z['descripcion'].'</td>';
                  echo '<td>'.$z['fecha'].'</td>';
                  // echo '<td>'.$z['marcadescrip'].'</td>';
                  // echo '<td>'.$z['depositodescrip'].'</td>';
                  // echo '<td style="text-align: center">'.($z['equip_estad']  == 'AC' ? '<small class="label pull-left bg-green" >Activa</small>' :'<small class="label pull-left bg-blue">Transito</small>').'</td>';
                  echo '</tr>';
                }
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
var ed="";

//Editar
$(".fa-pencil").click(function (e) { 
  console.log ("entre");
  var idh = $(this).parent('td').parent('tr').attr('id');
  console.log("ID de herramienta es ");
  console.log(idh);
  // alert(idh);
  ed=idh;
  $.ajax({
    type: 'GET',
    data: { idh:idh},
    url: 'index.php/Herramienta/getpencil', //index.php/
    success: function(data){
      console.log("Estoy editando");
      console.log(data);
      console.log(data[0]['modid']);             
      datos={
        'codigode':data[0]['herrcodigo'],
        'descripcionde':data[0]['herrdescrip'],
        'modid':data[0]['modid'],
        'depositoid':data[0]['depositoId'],
        'marcade':data[0]['herrmarca'], 
        'descrip': data[0]['depositodescrip'],
        'descripmarca' : data[0]['marcadescrip'],
        'descripdepo' : data[0]['depositodescrip'],
      }
      completarEdit(datos);
    },
    error: function(result){
      console.log(result);
    },
    dataType: 'json'
  });
});

//Eiminar
$(".fa-times-circle").click(function (e) {                 
  console.log("Esto eliminando"); 
  var id_herr = $(this).parent('td').parent('tr').attr('id');
  console.log(id_herr);
  $.ajax({
    type: 'POST',
    data: { id_herr: id_herr},
    url: 'index.php/Herramienta/baja_herramienta', //index.php/
    success: function(data){
      //var data = jQuery.parseJSON( data );
      console.log(data);
      //$(tr).remove();
      alert("HERRAMIENTA Eliminado");
      regresa();
    },
    error: function(result){
      console.log(result);
    },
    dataType: 'json'
  });
});

//Ver Orden
  // $(".fa-search").click(function (e) { 
      
  //     var id_nota = $(this).parent('td').parent('tr').attr('id');
  //     console.log(id_nota);
      
  //     $.ajax({
  //             type: 'POST',
  //             data: { id: id_nota},
  //             url: 'index.php/Notapedido/getNotaPedidoId', //index.php/
  //             success: function(data){
  //                     //$('tr.celdas').remove();
  //                     for (var i = 0; i < data.length; i++) {            
  //                        var tr = "<tr class='celdas'>"+
  //                                //"<td ></td>"+
  //                                "<td>"+data[i]['artDescription']+"</td>"+
  //                                "<td>"+data[i]['cantidad']+"</td>"+                               
  //                                "<td>"+data[i]['fecha']+"</td>"+
  //                                "<td>"+data[i]['fechaEntrega']+"</td>"+
  //                                "<td>"+data[i]['fechaEntredado']+"</td>"+
  //                                "<td>"+data[i]['estado']+"</td>"+                               
  //                                "</tr>";
  //                        $('#tabladetalle tbody').append(tr);
  //                      }
  //                   },
                
  //             error: function(result){
                    
  //                   console.log(result);
  //                 },
  //                 dataType: 'json'
  //     });
// });

//Ver Orden
$(".fa-search").click(function (e) { 
    
    var id_nota = $(this).parent('td').parent('tr').attr('id');
   
    $.ajax({
            type: 'POST',
            data: { id: id_nota},
            url: 'index.php/Notapedido/getNotaPedidoId',
            success: function(data){

                    $('tr.celdas').remove();
                    for (var i = 0; i < data.length; i++) {            
                       var tr = "<tr class='celdas'>"+
                               "<td>"+data[i]['artDescription']+"</td>"+
                               "<td>"+data[i]['cantidad']+"</td>"+
                               "<td>"+data[i]['fecha']+"</td>"+ 
                               "<td>"+data[i]['fechaEntrega']+"</td>"+ 
                               "<td>"+data[i]['fechaEntregado']+"</td>"+     
                               "<td>"+data[i]['provnombre']+"</td>"+                                 
                               "<td>"+data[i]['estado']+"</td>"+                             
                               "</tr>";
                       $('#tabladetalle tbody').append(tr);
                    }
                  },                
            error: function(result){
                  
                  console.log(result);
                },
                dataType: 'json'
    });
});

  
function regresa(){
  //WaitingOpen();
  //$('#modaldeposito').empty();
  //$('#modaleditar').empty(); 
  //$('#content').empty();
  $("#content").load("<?php echo base_url(); ?>index.php/Herramienta/index/<?php echo $permission; ?>");
  WaitingClose();
}

$('#deposito').DataTable({
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
</script>


<!-- Modal ver nota pedido-->
<div class="modal fade" id="modaltarea" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"  id="myModalLabel"><span id="modalAction" class="fa fa-plus-square text-light-blue"></span> Ver Nota de Pedido</h4>
      </div> <!-- /.modal-header  -->

      <div class="modal-body" id="modalBodyArticle">
        <div class="row" >
          <div class="col-xs-12">
             <table id="tabladetalle" class="table table-bordered table-hover">
               <thead>
                  <tr>
                    <th>Articulo</th>
                    <th>Cantidad</th>                    
                    <th>Fecha Nota</th>
                    <th>Fecha de Entrega</th>
                    <th>Fecha Entregado</th>
                    <th>Proveedor</th>
                    <th>Estado</th>                  
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
        <button type="button" class="btn btn-primary" id="btnSave" data-dismiss="modal">Ok</button>
      </div>  <!-- /.modal footer -->

    </div> <!-- /.modal-content -->
  </div>  <!-- /.modal-dialog modal-lg -->
</div>  <!-- /.modal fade -->
<!-- / Modal -->
