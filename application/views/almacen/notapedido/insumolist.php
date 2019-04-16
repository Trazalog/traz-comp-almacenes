<input type="hidden" id="permission" value="<?php echo $permission;?>">
<div class="row">
  <div class="col-xs-12">
    <div class="alert alert-danger alert-dismissable" id="error" style="display: none">
          <h4><i class="icon fa fa-ban"></i> Error!</h4>
          Revise que todos los campos obligatorios esten seleccionados
      </div>
  </div>
</div>

<div class="row">
  <div class="col-xs-12">
    <div class="alert alert-success" id="error2" style="display: none">
          <h4></h4>
          EL EQUIPO POSEE COMPONENTES ASOCIADOS
      </div>
  </div>
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="alert alert-success" id="error3" style="display: none">
          <h4></h4>
          EL EQUIPO NO POSEE COMPONENTES ASOCIADOS
      </div>
  </div>
</div>
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h2 class="box-title">Insumos</h2>
            <?php
            //if (strpos($permission,'Add') !== false) {
            //echo '<button class="btn btn-block btn-primary" style="width: 250px; margin-top: 10px;" id="listadoOT">Volver a OTs</button>';
            /*echo '<button class="btn btn-block btn-primary" style="width: 250px; margin-top: 10px;" id="listado">Ver Listado de Notas de pedido</button>';*/
            //}
          ?>
        </div><!-- /.box-header -->
        <div class="box-body">
                    
            <div class="row" >
              <div class="col-xs-12">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h2 class="panel-title"><span class="fa fa-th-large"></span>  Detalle de Insumos</h2>
                  </div>
     
                  <div class="panel-body">
                    <!-- sacar u ocultar -->
                    <input type="text" id="id_ordTrabajo" name="id_ordTrabajo" class="form-control" value="<?php //echo $ot[0]['id_orden'] ?>" disabled>
                    <form id="form_insumos">  
                      <table id="tbl_insumos" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                              <th style="width: 2%;">Seleccionar</th>
                              <th style="width: 50%;">Insumo</th>
                              <th>Cantidad</th>                      
                            </tr>
                        </thead>
                        <tbody>
                          <?php                  
                            $i=0;  
                            if(count($plantilla) > 0) {
                              //dump_exit($plantilla);
                              foreach($plantilla as $p){
                                
                                echo '<tr id="" class="">' ;
                                  echo '<td>';
                                    //echo '<input type="text" name="orden_Id['.$i.']" class="celda ord_Id" id="ord_Id" value=" " placeholder="">';
                                    echo '<input class="check" type="checkbox" name="artId['.$i.']" value="'.$p['artId'].'" id="'.$p['artId'].'">';
                                    echo '</td>';
                                  echo '<td>';  
                                    echo '<input type="text" class="celda insum_Desc" id="insum_Desc" value=" '. $p['artDescription'] .' " placeholder="">';
                                  echo '</td>';
                                  echo '<td>';
                                    echo '<input type="text" name="cant_insumos['.$i.']" class="cant_insumos" id="cant_insumos" value="" placeholder="Ingrese cantidad...">';
                                  echo '</td>';

                                  // echo '<td>';
                                  //   echo '<input type="text" name="idOT" class="celda idOT" id="idOT" value="'.$iort.'" placeholder="">';
                                  // echo '</td>';

                                echo '</tr>';
                                $i++;
                                
                              } // fin foreach($list as $f)   
                            } // fin if(count($list) > 0)

                          ?>
                        </tbody>
                      </table>
                    </form>        
                      

                  </div><!-- /.panel-body -->
                </div><!-- /.panel -->
              </div>
            </div>

            <div class="modal-footer"> 
                <button type="button" class="botones btn btn-primary" onclick="enviarOrden()">Hacer Pedido</button>
                <button type="button" class="botones btn btn-primary" onclick="pedidoespecial()">Pedido Especial</button>
            </div>  <!-- /.modal footer -->            
         
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
<style>
  input.celda{border: none;}
</style>
</section><!-- /.content -->


<script>

$("#fechaEnt").datetimepicker({
  format: 'YYYY-MM-DD',
  locale: 'es',
});

/*/va listado de OTs
$("#listadoOT").click(function (e) {
  WaitingOpen();
  $('#content').empty();
  $("#content").load("<?php echo base_url(); ?>index.php/Otrabajo/listOrden/<?php echo $permission; ?>");
  WaitingClose();
});*/

//va a listado de nota de pedido
$("#listado").click(function (e) {
  WaitingOpen();
  $('#content').empty();
  $("#content").load("<?php echo base_url(); ?>index.php/Notapedido/index/<?php echo $permission; ?>");
  WaitingClose();
});



// Guarda Nota de Pedido
function enviarOrden(){
  /////  VALIDACIONES
  var hayError = false;
  $('#error').hide();

  var tabla = $('#tbl_insumos tbody tr');
  //console.table(tabla);
  var idinsumos = new Array();
  var cantidades = new Array();
  id = '';
  cant = '';
 
  $.each(tabla, function (index) {
    var check = $(this).find('input.check');
    var cant = $(this).find('input.cant_insumos');
    console.log(check);
    // checked y lleno cant
    if( check.prop('checked') && (cant!="")){
      id = check.attr('value');
      idinsumos.push(id);
      cant = check.parents("tr").find("input.cant_insumos").val();      
      cantidades.push(cant);
                       
    }
    // checked y vacio cant
    if(check.prop('checked') && (cant=="")){
      hayError = true;
    }
    // no checked y lleno cant
    if(!(check.prop('checked')) && (cant!="")){
      hayError = true;
    }
    // no checked y vacio cant
    if(!(check.prop('checked')) && (cant=="")){
      hayError = true;
    }
  });  
  //FIXME: DESHARDCODEAR !!!!
  var idOT= 44;

  if(hayError == true){
    $('#error').fadeIn('slow');
    return;
  }
  WaitingOpen("Guardando pedido...");
  $.ajax({
    data: {idinsumos,cantidades,idOT},
    type: 'POST',
    dataType: 'json',
    url: 'index.php/Notapedido/setNotaPedido',
    success: function(result){
      WaitingClose();
      setTimeout("cargarView('Tarea', 'index', '"+$('#permission').val()+"');",0);
    },
    error: function(result){
      WaitingClose();
      alert("Error en guardado...");
    },
  });
}

// Pedido especial
function pedidoespecial(){ 
  var iort = $('#ot').val();
	//var iort = 
  console.log("El id de OT es: " + iort);
   
  WaitingOpen();
  $('#content').empty();
  $("#content").load("<?php echo base_url(); ?>index.php/Notapedido/pedidoEspecial/");
  // $("#content").load("<?php echo base_url(); ?>index.php/Notapedido/pedidoEspecial/<?php echo $permission; ?>/"+iort);
  WaitingClose();  
}

$('#tabModInsum').DataTable({
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