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
    <div class="alert alert-danger alert-dismissable"  id="error1" style="display: none">
      <h4><i class="icon fa fa-ban"></i> </h4>
      El artículo y depósito seleccionado no están en Lote.
    </div>
  </div>
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="alert alert-success" id="error2" style="display: none">
      <h4></h4>
      El artículo y depósito seleccionado están en Lote.
    </div>
  </div>
</div>

<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"> Remito</h3> 
        </div><!-- /.box-header -->
           
        <div class="box-body">
          <div class="row" >
            <div class="col-xs-12">
              <div class="panel panel-default">

                <div class="panel-body">
                  <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-4">
                      <label for="comprobante">Comprobante <strong style="color: #dd4b39">*</strong> :</label>
                      <input type="text" placeholder="Ingrese Numero..." class="form-control" id="comprobante" name="comprobante">
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                      <label for="fecha">Fecha <strong style="color: #dd4b39">*</strong> :</label>
                      <input type="text" class="form-control" id="fecha" name="fecha">
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                      <label for="proveedor">Proveedor <strong style="color: #dd4b39">*</strong> :</label>
                      <select  id="proveedor" name="proveedor" class="form-control"></select>  
                    </div>
                  </div><br>
                        
                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#insum" aria-controls="home" role="tab" data-toggle="tab" class="fa fa-file-text-o icotitulo"> Insumos</a></li>
                  </ul>

                  <!-- Tab panes -->
                  <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="insum">
                      <div class="row">
                        <br>
                        <div class="col-xs-12 col-sm-6 col-md-3">
                          <label for="lote">Lote <strong style="color: #dd4b39">*</strong> :</label>
                          <input type="text" id="lote" name="lote" placeholder="Lote" class="form-control">
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3">
                          <label for="codigo">Código <strong style="color: #dd4b39">*</strong> :</label>
                          <input type="text" id="codigo" name="codigo" placeholder="Buscar código..." class="form-control">
                          <input type="hidden" id="id_herr" name="id_herr">
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3">
                          <label for="descripcion">Descripción <strong style="color: #dd4b39">*</strong> :</label>
                          <input type="text" id="descripcion" name="descripcion" class="form-control" disabled/>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3">
                          <label for="cantidad">Cantidad <strong style="color: #dd4b39">*</strong> :</label>
                          <input type="text" id="cantidad" name="cantidad" placeholder="Ingrese Cantidad..." class="form-control">  
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3">
                          <label for="deposito">Depósito <strong style="color: #dd4b39">*</strong> :</label>
                          <select  id="deposito" name="deposito" class="form-control" />  
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3">
                          <label></label> 
                          <br>
                          <button type="button" class="btn btn-primary" id="agregar"><i class="fa fa-check">Agregar</i></button>
                        </div>
                      </div><br>

                      <div class="row">
                        <div class="col-xs-12">
                          <table class="table table-bordered" id="tablainsumo"> 
                            <thead>
                              <tr>                      
                                <th></th>
                                <th>Lote</th>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>Depósito</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                          </table>  
                        </div>
                      </div>
                    </div><!-- /. tab-pane -->
                  </div><!-- /.tab-content -->
                </div> <!-- /.panel-body -->

              </div>
            </div>
          </div>
        </div><!-- /.box-body -->

        <div class="modal-footer">
          <button type="button" class="btn btn-default delete" onclick="limpiar()">Cancelar</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="guardar()" >Guardar</button>
        </div>  <!-- /.modal footer -->

      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->
<script>
var idslote = {}; 
var j = 0;

$("#fecha").datetimepicker({
  format: 'YYYY-MM-DD HH:mm:ss',
  locale: 'es',
});


// autocomplete para codigo
var dataF = function () {
  var tmp = null;
  $.ajax({
    'async': false,
    'type': "POST",
    'global': false,
    'dataType': 'json',
    'url': "index.php/almacen/Remito/getcodigo",
    'success': function (data) {
      tmp = data;
    }
  });
  return tmp;
}();
$("#codigo").autocomplete({
  source: dataF,
  delay: 100,
  minLength: 1,
  /*response: function(event, ui) {
    var noResult = { value:"",label:"No se encontraron resultados" };
    ui.content.push(noResult);
  },*/
  focus: function(event, ui) {
    // prevent autocomplete from updating the textbox
    event.preventDefault();
    // manually update the textbox
    $(this).val(ui.item.label);
    $("#descripcion").val(ui.item.artDescription);
  },
  select: function(event, ui) {
    // prevent autocomplete from updating the textbox
    event.preventDefault();
    // manually update the textbox and hidden field
    //$(this).val(ui.item.value);//label
    $("#codigo").val(ui.item.label); //value
    $("#descripcion").val(ui.item.artDescription);
    $("#id_herr").val(ui.item.value);
    //traer_deposito(ui.item.value);
    //console.log("id articulo de orden insumo: ") 
    //console.log(ui.item.value);                
  },
});

traer_deposito();
function traer_deposito(){
  $('#deposito').html(""); 
  $.ajax({
    type: 'POST',
    data: {},
    url: 'index.php/almacen/Remito/getdeposito',
    success: function(data){
      console.log("exito en deposito");
      console.log(data);
      var opcion  = "<option value='-1'>Seleccione...</option>" ; 
      $('#deposito').append(opcion); 
      for(var i=0; i < data.length ; i++) 
      {    
        var nombre = data[i]['descripcion'];
        var opcion = "<option value='"+data[i]['depo_id']+"'>" +nombre+ "</option>" ; 
        $('#deposito').append(opcion); 
      }
    },
    error: function(result){
      console.log("entre por el error en deposito");
      //alert("este articulo no está en deposito");             
      console.log(result);
    },
    dataType: 'json'
  });
}

traer_proveedor();
function traer_proveedor(){ // Ok
  $('#proveedor').html(""); 
  $.ajax({
    type: 'POST',
    data: {},
    url: 'index.php/almacen/Remito/getproveedor',
    success: function(data){
      console.log("exito en proveedor");
      console.log(data);
      var opcion  = "<option value='-1'>Seleccione...</option>" ; 
      $('#proveedor').append(opcion); 
      for(var i=0; i < data.length ; i++) 
      {    
        var nombre = data[i]['nombre'];
        var opcion = "<option value='"+data[i]['prov_id']+"'>" +nombre+ "</option>" ; 
        $('#proveedor').append(opcion); 
      }
    },
    error: function(result){
      console.error("entre por el error en proveedor");
      console.table(result);
    },
    dataType: 'json'
  });
}

function traer_lote(id_her, id_deposito){
  console.log("ENTRE A LA FUNCION");
  console.log(id_her);
  console.log(id_deposito);
  $.ajax({
    type: 'POST',
    data: {id_her:id_her, id_deposito:id_deposito},
    url: 'index.php/almacen/Remito/getlote', //index.php/
    success: function(data){
      console.log("exito en lote");
      console.log(data);
      for(var i=0; i < data.length ; i++) 
      {   
        idslote[j] = data[i]['loteid'];
      }
    },
    error: function(result){
      console.log("entre por el error en lote ");
      console.log("No esta en el lote este articulo "); //no se q hacer aca  
      console.log(result);
    },
    dataType: 'json'
  });
}

function limpiar(){
  $("#comprobante").val("");
  $("#fecha").val("");
  $("#solicitante").val("");
  $("#destino").val("");
  $("#codigo").val("");
  $("#descripcion").val("");
  $("#cantidad").val("");
  $("#deposito").val("");
  $('#tablainsumo tbody tr').remove();
}




//agrega insumos a la tabla detainsumos
var i = 1;
$('#agregar').click(function (e) {
  var lote = $('#lote').val();
  var $codigo     = $("#codigo").val(); 
  var id_her      = $('#id_herr').val(); //id de articulo
  var descripcion = $('#descripcion').val();
  var cantidad    = $('#cantidad').val();
  var deposito    = $("select#deposito option:selected").html(); 
  var id_deposito = $('#deposito').val();
  var tr          = "<tr id='"+i+"'>"+
    "<td ><i class='fa fa-ban elirow text-light-blue' style='cursor: 'pointer'></i></td>"+
    "<td>"+lote+"</td>"+
    "<td>"+$codigo+"</td>"+
    "<td class='hidden' id='"+id_her+"'>"+id_her+"</td>"+
    "<td>"+descripcion+"</td>"+
    "<td>"+cantidad+"</td>"+
    "<td>"+deposito+"</td>"+
    "<td class='hidden' id='"+id_deposito+"'>"+id_deposito+"</td>"+
    "</tr>";
  i++;  
   
  /* mando el codigo y el id _ deposito entonces traigo esa cantidad de lote*/
  var hayError = false;
  var Error1   = false;
  var Error2   = false;

  if(Error1== true){
    $('#error1').fadeOut('slow'); // lo levanto
  }
  if(Error2== true){
    $('#error2').fadeOut('slow'); // lo levanto
  }
  if($codigo !=0 && cantidad >0 && id_deposito>0  ){
    $('#tablainsumo tbody').append(tr);
    

    $(document).on("click",".elirow",function(){
      var parent = $(this).closest('tr');
      $(parent).remove();
    });

    $('#lote').val('');
    $('#codigo').val('');
    $('#descripcion').val(''); 
    $('#cantidad').val(''); 
    $('#deposito').val('');
  }
});

function guardar(){ 
  console.log("estoy guardando");
  var parametros = {
    'fecha': $('#fecha').val(),
    'provid': $('#proveedor').val(),
    'comprobante': $('#comprobante').val(),        
  };

  var idsinsumo = new Array();     
  $("#tablainsumo tbody tr").each(function (index){
    var i = $(this).attr('id');
    idsinsumo.push(i);            
  }); 

  lote = {};
  comp = {};
  depo = {};
  art  = {};

  $("#tablainsumo tbody tr").each(function (index) 
  {
    var campo1, campo2, campo3, campo4, campo5, campo6, campo7, campo8;
    var i = $(this).attr('id'); 
    

    $(this).children("td").each(function (index2) 
    {
      switch (index2) 
      {
        case 0: campo1 =$(this).text();
          //alert("campo1: "+campo1);
          break;
        case 1: campo2 = $(this).text();
          lote[i] = campo2;
          //alert("campo2: "+$(this).text());
          break;
        case 2: campo3 = $(this).text();
          //alert("campo3: "+campo3);
          break;
        case 3: campo4 = $(this).text();
          art[i] = campo4;
          //alert("campo4: "+campo4);
          break;
        case 4: campo5 = $(this).text(); 
          //alert("campo5: "+campo5);
                     
          break;
        case 5: campo6 = $(this).text();
        comp[i] = campo6;   
          //alert("campo6: "+campo6);                
          break;
        case 6: campo7 = $(this).text();  
          //alert("campo7: "+campo7);
          
          break;
        case 7: campo8 = $(this).text();
           depo[i]=campo8; 
           //alert("campo8: "+campo8);
          break;
      }
    });
  });

  var hayError = false;

  if(parametros !=0 && idsinsumo !=0){
    //&& depo !=0 && idsinsumo >0 && comp >0
    $.ajax({
      type: 'POST',
      data: {data:parametros,lote:lote, comp:comp, depo:depo, idsinsumo: idsinsumo, art:art,prov_id:$('#proveedor').val()},
      url: 'index.php/almacen/Remito/guardar',  //index.php/
      success: function(data){
        console.log("exito");
        console.log(data);
        regresa();
      },
      error: function(result){
        console.log("entro por el error");
        console.log(result);
      },
      // dataType: 'json'
    });
  //  limpiar();
  }
  else {
    var hayError = true;
    $('#error').fadeIn('slow');
    return;
  }

  if(hayError == false){
    $('#error').fadeOut('slow');
  }

}

function regresa(){
  WaitingOpen();
  $('#content').empty();
  $("#content").load("<?php echo base_url(); ?>index.php/almacen/Remito/index/<?php echo $permission; ?>");
  WaitingClose();
}
</script>
