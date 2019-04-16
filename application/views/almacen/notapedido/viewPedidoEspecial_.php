<!-- <input type="hidden" id="permission" value="<?php //echo $permission;?>"> -->
<div class="row">
  <div class="col-xs-12">
    <div class="alert alert-danger alert-dismissable" id="error" style="display: none">
          <h4><i class="icon fa fa-ban"></i> Error!</h4>
          Revise que todos los campos obligatorios esten completos
      </div>
  </div>
</div>

<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h2 class="box-title">Pedido Especial</h2>            
        </div><!-- /.box-header -->
        <div class="box-body">
                    
            <div class="row" >
              <div class="col-xs-12">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h2 class="panel-title"><span class="fa fa-th-large"></span>  Detalle</h2>
                  </div>
     
                  <div class="panel-body">
                    
                    <!-- <input type="text" id="id_ordTrabajo" name="id_ordTrabajo" class="form-control" value="<?php //echo $ot[0]['id_orden'] ?>" disabled> -->
                    <form id="form_insumos">  
																						
											<div class="form-group">
												<div class="col-sm-12 col-md-12">
													<label for="pedido">Pedido</label>
													<textarea class="form-control" id="pedido" rows="3"></textarea>
												</div>
											</div>
											
											<div class="form-group">
												<div class="col-sm-12 col-md-12">
													<label for="justificacion">Justificación</label>
													<textarea class="form-control" id="justificacion" rows="3"></textarea>
												</div>
											</div>

                    </form>        
                      

                  </div><!-- /.panel-body -->
                </div><!-- /.panel -->
              </div>
            </div>

            <div class="modal-footer">                
              <button type="button" class="botones btn btn-primary" onclick="guardar()">Guardar Pedido Especial</button>
            </div>  <!-- /.modal footer -->            
         
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->

</section><!-- /.content -->

<!-- estilos de linea de tiempo -->


<script>


	
	/* Funciones BPM */
	
	function guardar(){
    
    var pedido = $('#pedido').val();
    var justif = $('#justificacion').val();

    $.ajax({
			type: 'POST',
			data: {pedido: pedido,
             justif: justif
			      },
			url: 'index.php/Notapedido/setPedidoEspecial', 
			success: function (data) {
				console.log(data);
				//alert("Se Finalizando la SUBTAREA");
				refresca(id);
			},
			error: function (result) {
				console.log(result);
				alert("NO se Finalizo la SUBTAREA");
				refresca(id);
			}
		});
  }
	

	$('.fecha').datepicker({
		autoclose: true
	}).on('change', function(e) {
       // $('#genericForm').bootstrapValidator('revalidateField',$(this).attr('name'));
	   console.log('Validando Campo...'+$(this).attr('name'));
	   $('#genericForm').data('bootstrapValidator').resetField($(this),false);
	   $('#genericForm').data('bootstrapValidator').validateField($(this));
  });
	

/* pedido de insumos */
function pedirInsumos(){ 
  var iort = $('#ot').val();
	//var iort = 
  console.log("El id de OT es: " + iort);
   
  WaitingOpen();
  $('#content').empty();
  $("#content").load("<?php echo base_url(); ?>index.php/Notapedido/agregarListInsumos/<?php echo $permission; ?>/"+iort);
  WaitingClose();  
}

// Volver al atras
$('#cerrar').click(function cargarVista() {
		WaitingOpen();
		$('#content').empty();
		$("#content").load("<?php echo base_url(); ?>index.php/Tarea/index/<?php echo $permission; ?>");
		WaitingClose();
	});

/* pedido de insumos */

</script>



