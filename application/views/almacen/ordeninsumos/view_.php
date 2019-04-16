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
    <div class="alert alert-danger alert-dismissable"  id="error1" style="display: none">
          <h4><i class="icon fa fa-ban"></i> Error!</h4>
          NO HAY INSUMOS SUFICIENTES
      </div>
  </div>
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="alert alert-success" id="error2" style="display: none">
          <h4></h4>
          HAY INSUMOS SUFICIENTES
      </div>
  </div>
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="alert alert-danger alert-dismissable" id="error3" style="display: none">
          <h4></h4>
          NO HAY INSUMOS
      </div>
  </div>
</div>

<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Orden de Insumo</h3> 
          <?php
          //if (strpos($permission,'Add') !== false) {
            echo '<button class="btn btn-block btn-primary" style="width: 100px; margin-top: 10px;" id="listado">Ver Listado</button>';
          //}
          ?>
        </div><!-- /.box-header -->
           
        <div class="box-body">
          <div class="row" >
            <div class="col-xs-12">

              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title">Datos de Orden</h4>
                </div>

                <div class="panel-body">
                  <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-4">
                      <label for="">Comprobante <strong style="color: #dd4b39">*</strong> :</label>
                      <input type="text" align=\"right\"  class="form-control" id="comprobante"  min="1" size="30" placeholder="Ingrese numero de comprobante..." >
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                      <label for="fecha">Fecha <strong style="color: #dd4b39">*</strong> :</label>
                      <input type="text" class="form-control" id="fecha" name="fecha">
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                      <label for="">Solicitante <strong style="color: #dd4b39">*</strong> :</label>
                      <input type="text"  id="solicitante" name="solicitante" class="form-control input" placeholder="Ingrese Nombre..." >
                      <!--<select id="solicitante" name="solicitante" class="form-control"   />-->
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-4">
                          <label for="">id Orden de Trabajo <strong style="color: #dd4b39">*</strong> :</label>
                          <input type="text" id="idOT" name="idOT" class="form-control" value="" placeholder="Ingrese id de OT"/>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4">
                          <label for="">Descripción de OT <strong style="color: #dd4b39">*</strong> :</label>
                          <textarea id="ot" name="ot" class="form-control" disabled></textarea>
                        </div>
                  </div><br>

                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                      <a href="#insum" aria-controls="home" role="tab" data-toggle="tab" class="fa fa-file-text-o icotitulo"> Insumos</a>
                    </li>
                  </ul>

                  <!-- Tab panes -->
                  <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="insum">
                      <div class="row">
                        <br>
                        <div class="col-xs-12 col-sm-6 col-md-3">
                          <label for="">Codigo <strong style="color: #dd4b39">*</strong> :</label>
                          <!--<select id="codigo" name="codigo" class="form-control" />-->
                          <input type="text" id="codigo" name="codigo" placeholder="Buscar código..." class="form-control">
                          <input type="hidden" id="id_herr" name="id_herr">
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3">
                          <label for="">Descripción <strong style="color: #dd4b39">*</strong> :</label>
                          <input type="text" id="descripcion" name="descripcion" class="form-control" disabled/>  
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3">
                          <label for="">Cantidad <strong style="color: #dd4b39">*</strong> :</label>
                          <input type="text" id="cantidad" name="cantidad" class="form-control">  
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3">
                          <label for="">Deposito <strong style="color: #dd4b39">*</strong> :</label>
                          <select  id="deposito" name="deposito" class="form-control"  />  
                        </div>

                        <div class="col-xs-12">
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
                                <th>Código</th>
                                <th>Descripcion</th>
                                <th>Cantidad</th>
                                <th>Deposito</th>
                                <th>OT</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                          </table>  
                        </div>
                      </div>

                    </div><!--div que cierra el panel-->
                  </div><!-- div que cierra el tab conte -->
                </div><!-- </div> CIERRE div del body-->
              </div>

            </div>
          </div>
        </div><!-- /.box-body -->
        
        <div class="modal-footer">
          <button type="button" class="btn btn-default delete" onclick="limpiar()">Limpiar</button>
          <button type="button" class="btn btn-primary" onclick="guardar()" >Guardar</button>
        </div>  <!-- /.modal footer -->

      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->

<script>
$( "#fecha" ).datetimepicker({
  format: 'YYYY-MM-DD',
  locale: 'es',
});

$('#listado').click( function cargarVista(){
  WaitingOpen();
  $('#content').empty();
  $("#content").load("<?php echo base_url(); ?>index.php/Ordeninsumo/index/<?php echo $permission; ?>");
  WaitingClose();
});
  
var idslote = new Array(); 
/*traer_codigo();
function traer_codigo(){
  $.ajax({
    type: 'POST',
    data: { },
    url: 'index.php/Ordeninsumo/getcodigo',
    success: function(data){
      var opcion  = "<option value='-1'>Seleccione...</option>" ; 
      $('#codigo').append(opcion);
      for(var i=0; i < data.length ; i++) 
      {   
          idslote[i] = data[i]['loteid'];
          var nombre = data[i]['artBarCode'];
          var opcion  = "<option value='"+data[i]['artId']+"'>" +nombre+ "</option>" ;
          $('#codigo').append(opcion); 
      }
    },
    error: function(result){       
      console.log(result);
    },
      dataType: 'json'
  });
}*/
// autocomplete para codigo
var dataF = function () {
  var tmp = null;
  $.ajax({
    'async': false,
    'type': "POST",
    'global': false,
    'dataType': 'json',
    'url': "index.php/Ordeninsumo/getcodigo",
    'success': function (data) {
      tmp = data;
      for(var i=0; i < data.length ; i++) 
      {   
          idslote[i] = data[i]['loteid'];
      }
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
    traer_deposito(ui.item.value);
    //console.log("id articulo de orden insumo: ") 
    //console.log(ui.item.value);                
  },
});

//trae_solicitante();
/*function trae_solicitante(){
  $.ajax({
    type: 'POST',
    data: { },
    url: 'index.php/Ordeninsumo/getsolicitante',
    success: function(data){
      var opcion  = "<option value='-1'>Seleccione...</option>" ; 
      $('#solicitante').append(opcion); 
      for(var i=0; i < data.length ; i++) 
      {
        var nombre = data[i]['solicitante'];
        var opcion = "<option value='"+data[i]['id_solicitud']+"'>" +nombre+ "</option>" ; 
        $('#solicitante').append(opcion);
      }
    },
    error: function(result){   
      console.log(result);
    },
    dataType: 'json'
  });
}*/

// autocomplete para solicitante
var dataF = function () {
  var tmp = null;
  $.ajax({
    'async': false,
    'type': "POST",
    'global': false,
    'dataType': 'json',
    'url': "index.php/Ordeninsumo/getsolicitante",
    'success': function (data) {
      tmp = data;
    }
  });
  return tmp;
}();
$("#solicitante").autocomplete({
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
  },
  select: function(event, ui) {
    // prevent autocomplete from updating the textbox
    event.preventDefault();
    // manually update the textbox and hidden field
    $(this).val(ui.item.value);//label
    $("#solicitante").val(ui.item.label); //value
    //console.log("id articulo de orden insumo: ") 
    //console.log(ui.item.value);                
  },
});

// autocomplete para OT
var dataOT = function () {
  var tmp = null;
  $.ajax({
    'async': false,
    'type': "POST",
    'global': false,
    'dataType': 'json',
    'url': "index.php/Ordeninsumo/getOT",
    'success': function (data) {
      tmp = data;
      //console.table(tmp);
    }
  });
  return tmp;
}();
$("#idOT").autocomplete({
  autoFocus: true,
  source: dataOT,
  delay: 500,
  minLength: 1,
  response: function(event, ui) {
    var noResult = { value:"",label:"No se encontraron resultados" };
    ui.content.push(noResult);
  },
  focus: function(event, ui) {
    // prevent autocomplete from updating the textbox
    event.preventDefault();
    // manually update the textbox
    $(this).val(ui.item.value);
    if(ui.item.value=="")
      $('#ot').html("");
    $('#ot').html(ui.item.info);
  },
  select: function(event, ui) {
    // prevent autocomplete from updating the textbox
    event.preventDefault();
    // manually update the textbox and hidden field
    $(this).val(ui.item.value);//label
    if(ui.item.value=="")
      $('#ot').html("");
    $("#ot").html(ui.item.info);
    //console.log("id articulo de orden insumo: ") 
    //console.log(ui.item.value);                
  },
  /*change: function (event, ui) {
    if (!ui.item) {
      this.value = '';
      $("#ot").val("");
    }
  }*/
}).autocomplete( "widget" ).addClass( "hidden" );
/*.autocomplete("instance")._renderItem = function(ul, item) {
  console.log('test');
  var item = $('<div class="list_item_container"><div class="label"><h3> Reputation:  ' + item.label + '</h3></div><div class="description">' + item.info + '</div></div>')
  return $("<li>").append(item).appendTo(ul);
};*/


//traer_deposito();
function traer_deposito(artId){
  $('#deposito').html(""); 
  $.ajax({
    type: 'POST',
    data: {artId:artId },
    url: 'index.php/Ordeninsumo/getdeposito',
    success: function(data){
      var opcion  = "<option value='-1'>Seleccione...</option>" ; 
      $('#deposito').append(opcion); 
      for(var i=0; i < data.length ; i++) 
      {    
        var nombre = data[i]['depositodescrip'];
        var opcion  = "<option value='"+data[i]['depositoId']+"'>" +nombre+ "</option>" ; 
        $('#deposito').append(opcion);                        
      }
    },
    error: function(result){  
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

function guardar(){    
  console.log("estoy guardando");
  var parametros = {
    'fecha'      : $('#fecha').val(),
    'solicitante': $('#solicitante').val(),
    'comprobante': $('#comprobante').val(), 
    'destino'    : $('#deposito').val(),  
    'id_ot'      : $('#idOT').val(),      
  };

  var idsinsumo = new Array();     
  $("#tablainsumo tbody tr").each(function (index) 
  {
    var i = $(this).attr('id');
    idsinsumo.push(i);            
  }); 

  comp = {};
  depo = {};
  art = {};
    var campo1, campo2, campo3, campo4,campo5, campo6, campo7, campo8, campo9;
    var i = 0;//$(this).attr('id'); 

  $("#tablainsumo tbody tr").each(function (index) 
  {
    $(this).children("td").each(function (index2) 
    {
      console.log("i: "+i);
      switch (index2) 
      {
        case 0: campo1 = $(this).text();
          break;
        case 1: campo2 = $(this).text();
          break;
        case 2: campo3 = $(this).text();
          break;
        case 3: campo4 = $(this).text();
          comp[i]=campo4;
          break;
        case 4: campo5 = $(this).text();               
          break;
        case 5: campo6 = $(this).text();               
          break;
        case 6: campo7 = $(this).text();               
          break;
        case 7: campo8 = $(this).text();
          depo[i]=campo8;                   
          break;
        case 8: campo9 = $(this).text();
          art[i]=campo9;                   
          break;
      }
    });
    i++;
    console.log('cantidad: '+comp);
    console.log('articulo: '+art);
  });
     
  console.log("parametros de Orden");
  console.log(parametros);
  console.log("insumos id");
  console.log(idsinsumo);
  console.log("cantidad");
  console.log(comp);
  console.log("lotes");
  console.log(idslote);
  console.log("depo");
  console.log(depo);
    console.log("art");
  console.log(art);
  var hayError = false;

  if(parametros !=0 && idsinsumo !=0 && $('#comprobante').val()!="" && $('#fecha').val()!="" && $('#solicitante').val()!=""){
    //&& depo !=0 && idsinsumo >0 && comp >0
    $.ajax({
      type: 'POST',
      data: {data:parametros, comp:comp, idslote:idslote, depo:depo, art:art},
      url: 'index.php/Ordeninsumo/guardar',  //index.php/
      success: function(data){
        console.log("exito");
        console.log(data);
        regresa();                   
      },
      error: function(result){
        console.error("Error al guardar orden de insumo");
        console.table(result);
      },
           // dataType: 'json'
    });
    limpiar();
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
/*
$('#codigo').change(function(){
  var artId = $(this).val();
  console.log(artId);
  $.ajax({
    type: 'POST',
    data: {artId:artId }, 
    url: 'index.php/Ordeninsumo/getdescrip',
    success: function(data){
      console.log(data);
      var descrip = data[0]['artDescription']; 
      $('#descripcion').val(descrip);       
    },
    error: function(result){
      console.log(result);
    },
    dataType: 'json'
  });
  traer_deposito(artId);
});*/

//agrega insumos a la tabla detainsumos
var i = 1;
$('#agregar').click(function (e) { 
  var $codigo     = $("#codigo").val(); 
  var id_her      = $('#id_herr').val();
  var descripcion = $('#descripcion').val();
  var cantidad    = $('#cantidad').val();
  var deposito    = $("select#deposito option:selected").html(); 
  var id_deposito = $('#deposito').val();
  var idOT        = $('#idOT').val();
  var ot          = $('#ot').html();
  var tr          = "<tr id='"+i+"'>"+
    "<td ><i class='fa fa-ban elirow text-light-blue' style='cursor: 'pointer'></i></td>"+
    "<td>"+$codigo+"</td>"+
    "<td>"+descripcion+"</td>"+
    "<td>"+cantidad+"</td>"+
    "<td>"+deposito+"</td>"+
    "<td>"+ot+"</td>"+
    "<td class='hidden' id='"+idOT+"'>"+idOT+"</td>"+
    "<td class='hidden' id='"+id_deposito+"'>"+id_deposito+"</td>"+
    "<td class='hidden' id='"+id_her+"'>"+id_her+"</td>"+
    "</tr>";
  i++;    
  console.log(tr);
  console.log("El id de deposito es :"+id_deposito);
  console.log("El codigo es: "+$codigo);
  console.log("El id de articulo: "+id_her);
  console.log("La cantidad es: "+cantidad);
  /* mando el codigo y el id _ deposito entonces traigo esa cantidad de lote*/
  var hayError = false;
  var Error1   = false;
  var Error2   = false;
  var Error3   = false;
  if ($codigo !=0 && cantidad >0 && id_deposito>0  ) {
    $.ajax({
      type: 'POST',
      data: { id_her:id_her, id_deposito:id_deposito}, 
      url: 'index.php/Ordeninsumo/alerta',
      success: function(data){
        console.log("exito en la alerta");
        console.log(data);
        var datos = parseInt(data);
        console.log(datos);
        $('#error3').fadeOut('slow');
        if( cantidad<=datos ){
          if(Error1 == false){
            $('#error1').fadeOut('slow');
          }
          $('#error2').fadeIn('slow');
          $('#tablainsumo tbody').append(tr);
          $('#error2').delay(1000).fadeOut('slow');
        }
        else {
          // alert("No hay insumos suficientes,la cantidad de insumos disponibles es: " + data); 
          var Error1 = true;
          $('#error1').fadeIn('slow');
          return; 
        }           
      },
      error: function(result){
        $('#error3').fadeIn('slow');
        console.log(result);
      },
      dataType: 'json'
    });
  }
  else {
    var hayError = true;
    $('#error').fadeIn('slow');
    return;
  }

  if(hayError == false){
    $('#error').fadeOut('slow');
  }

  if(Error1 == false){
    $('#error1').fadeOut('slow');
  }

  $(document).on("click",".elirow",function(){
    var parent = $(this).closest('tr');
    $(parent).remove();
  });

  $('#codigo').val('');
  $('#descripcion').val(''); 
  $('#cantidad').val(''); 
  $('#deposito').val('');
});

function regresa(){
  WaitingOpen();
  $('#content').empty();
  $("#content").load("<?php echo base_url(); ?>index.php/Ordeninsumo/index/<?php echo $permission; ?>");
  WaitingClose();
}

</script>