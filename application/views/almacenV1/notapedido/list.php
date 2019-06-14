<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Pedido Materiales</h3>
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
                 
                  echo '<i class="fa fa-fw fa-search text-light-blue" style="cursor: pointer; margin-left: 15px;" title="Ver Nota Pedido"></i>';
                                  
                  echo '</td>';
                  echo '<td>'.$z['id_notaPedido'].'</td>';
                  echo '<td>'.$z['id_ordTrabajo'].'</td>';
                  echo '<td>'.$z['descripcion'].'</td>';
                  echo '<td>'.$z['fecha'].'</td>';
                
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

//Ver Orden
$(".fa-search").click(function (e) { 
    
    var id_nota = $(this).parent('td').parent('tr').attr('id');
   
    $.ajax({
            type: 'POST',
            data: { id: id_nota},
            url: 'index.php/almacen/Notapedido/getNotaPedidoId',
            success: function(data){

                    $('tr.celdas').remove();
                    for (var i = 0; i < data.length; i++) {            
                       var tr = "<tr class='celdas'>"+
                               "<td>"+data[i]['artDescription']+"</td>"+
                               "<td>"+data[i]['cantidad']+"</td>"+
                               "<td>"+data[i]['fecha']+"</td>"+ 
                               "<td>"+data[i]['fecha_entrega']+"</td>"+ 
                               "</tr>";
                       $('#tabladetalle tbody').append(tr);
                    }

                    $('#detalle_pedido').modal('show');
                  },                
            error: function(result){
                  
                  console.log(result);
                },
                dataType: 'json'
    });
});

  

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
<div class="modal fade" id="detalle_pedido" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"  id="myModalLabel"><span id="modalAction" class="fa fa-plus-square text-light-blue"></span> Ver Nota de Pedido</h4>
      </div> <!-- /.modal-header  -->

      <div class="modal-body" id="modalBodyArticle">
        <div class="row" >
          <div class="col-xs-12">
             <table id="tabladetalle" class="table table-bordered table-striped table-hover">
               <thead>
                  <tr>
                    <th>Articulo</th>
                    <th>Cantidad</th>                    
                    <th>Fecha Nota</th>
                    <th>Fecha de Entrega</th>
                              
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
