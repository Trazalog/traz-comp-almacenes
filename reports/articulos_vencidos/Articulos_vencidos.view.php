<?php
  use \koolreport\widgets\koolphp\Table;
  use \koolreport\widgets\google\ColumnChart;
?>

<div id="reportContent" class="report-content">
  <div class="box box-primary">

    <div class="box-header with-border">
        <div class="box-tittle">
            <h4>Reporte Articulos Vencidos</h4>
        </div>
    </div>

    <div class="box-body">

      <!-- _____ GRUPO 1 _____ -->
        <div class="col-md-12">

            <div class="form-group">

              <div class="col-md-4 col-md-6 mb-4 mb-lg-0">
                <label style="padding-left: 20%;">Desde</label>
                <div class="input-group date">
                  <a class="input-group-addon" id="daterange-btn" title="Más fechas">
                    <i class="fa fa-magic"></i>
                    <span></span>
                  </a>
                  <input type="date" class="form-control pull-right" id="datepickerDesde" name="datepickerDesde" placeholder="Desde">
                </div>
              </div>

              <div class="col-md-4 col-md-6 mb-4 mb-lg-0">
                <label>Hasta</label>
                <div class="input-group date">
                  <a class="input-group-addon" id="daterange-btn" title="Más fechas">
                    <i class="fa fa-magic"></i>
                    <span></span>
                  </a>
                  <input type="date" class="form-control" id="datepickerHasta" name="datepickerHasta" placeholder="Hasta">
                </div>
              </div>

            </div>

        </div>
      <!-- _____ /GRUPO 1 _____ -->

      <div class="col-md-12">
        <br>
      </div>

      <!-- _____ GRUPO 2 _____ -->
        <div class="col-md-12">

            <div class="form-group">

              <div class="col-md-4 col-md-6 mb-4 mb-lg-0">
                <label for="establecimiento" class="form-label">Establecimiento:</label>
                <select onchange="seleccionesta(this)" class="form-control select2 select2-hidden-accesible" id="establecimiento" name="establecimiento" />
              </div>

              <div class="col-md-4 col-md-6 mb-4 mb-lg-0">
                <label for="depo_id" class="form-label">Depósito:</label>
                <select class="form-control select2 select2-hidden-accesible" id="depo_id" name="depo_id" />
              </div>



            </div>
        </div>
      <!-- _____ /GRUPO 2 _____ -->


      <div class="col-md-12">
        <br>
      </div>

      <!-- _____ GRUPO 3 _____ -->
        <div class="col-md-12">

          <div class="form-group habilitado">

            <div class="col-md-4 col-md-6 mb-4 mb-lg-0" >
              <label for="zona" class="form-label">Artículo:</label>
            <div id="list_articulos"> </div>
          </div>

          <div class="col-md-4 col-md-6 mb-4 mb-lg-0">
              <label for="tipo" class="form-label">Tipo Artículo:</label>
              <select class="form-control select2 select2-hidden-accesible" id="tipo" name="tipo" />
          </div>

          <div class="col-md-4 col-md-6 mb-4 mb-lg-0">
            <label for="estado" class="form-label">Estado:</label>
            <select class="form-control select2 select2-hidden-accesible" id="estado" name="estado">
              <option value='TODOS'>Todos</option>
              <option value='CRITICO'>Crítico</option>
              <option value='VENCIDO'>Vencido</option>
            </select>
          </div>

            </div>

        </div>
      <!-- _____ /GRUPO 3 _____ -->

      <div class="col-md-12">
        <br>
      </div>

      <div class="form-group col-xs-12">
        <div class="form-group">
          <button type="button" class="btn btn-success btn-flat col-xs-12 col-sm-3 col-md-3 col-lg-3" onclick="filtrar()" style="float: right !important;">Filtrar</button>
        </div>
      </div>

      <!--_______ TABLA _______-->
      <div class="col-md-12">
        <?php
        Table::create(array(
          "dataStore" => $this->dataStore('data_historico_table'),
          // "themeBase" => "bs4",
          // "showFooter" => true, // cambiar true por "top" para ubicarlo en la parte superior
          // "headers" => array(
          //   array(
          //     "Reporte de Producción" => array("colSpan" => 6),
          //     // "Other Information" => array("colSpan" => 2),
          //   )
          // ), // Para desactivar encabezado reemplazar "headers" por "showHeader"=>false
          // "showHeader" => false,

          "columns" => array(
            "desc_tipo_articulo" => array(
              "label" => "Tipo de Artículo"
            ),
            "barcode" => array(
              "label" => "Código"
            ),
            "descripcion" => array(
              "label" => "Descripción"
            ),
            "cantidad" => array(
              "label" => "Cantidad Stock"
            ),
            "fec_vencimiento" => array(
              "label" => "Fecha Vto."
            ),
            "deposito" => array(
              "label" => "Depósito"
            ),
            "estado" => array(
              "label" => "Estado"
            )
          ),
          "cssClass" => array(
            "table" => "table-scroll table-responsive dataTables_wrapper form-inline dt-bootstrap dataTable table table-bordered table-striped table-hover display",
            "th" => "sorting"
          )
        ));
        ?>
      </div>
      <div id="acciones" class="" style="float: right !important;">
        <button type="button" class="btn btn-primary" onclick="exportarPDF()">Imprimir</button>
        <button type="button" class="btn btn-primary" onclick="exportarExcel()">Exportar</button>
      </div>
    </div>
  </div>
</div>


<script>
  //variables que van a mantener el estado para poder generar el excel
  var fec1;var fec2;var depo;var arti;var tpoArt;var estado;


  // carga select de Establecimientos, componente Articulos y llama configuracion selects de fecha
  $(function() {
    $(".habilitado").hide();
    wo();
    $("#list_articulos").load("<?php echo base_url(ALM); ?>Reportes/cargaArticulos");
    getEstablecimientos();
    fechaMagic();
    getTiposArticulos();
    wc();
  });

  // config de daterangepicker
  function fechaMagic() {
    $('#daterange-btn').daterangepicker({
        ranges: {
          'Hoy': [moment(), moment()],
          'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
          'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
          'Este mes': [moment().startOf('month'), moment().endOf('month')],
          'Último mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate: moment()
      },
      function(start, end) {
        $('#datepickerDesde').val(start.format('YYYY-MM-DD'));
        $('#datepickerHasta').val(end.format('YYYY-MM-DD'));
      }
    );
  }

  // llena select Establecimientos
  function getEstablecimientos(){

    $.ajax({
        type: 'POST',
        dataType: 'json',
        data: {},
        url: 'index.php/<?php echo ALM?>Reportes/getEstablecimientos',
        success: function(data) {

            $('#establecimiento').empty();
            $("#establecimiento").append("<option value='-1' disabled selected>-Seleccione Establecimiento...-</option");
            if(data != null){
                for(var i=0; i<data.length; i++)
                {
                  $('#establecimiento').append("<option value='" + data[i].esta_id + "'>" +data[i].nombre+"</option");
                }
                //$("#establecimiento").removeAttr('readonly');
            }else{
                $("#establecimiento").append("<option value=''>-Sin Establecimientos-</option");
            }
            WaitingClose();
        },
        error: function(data) {

            alert('Error');
        }
    });
  }

  // carga los depositos de acuerdo a establecimiento
	function seleccionesta(opcion){
    wo();
    var id_esta = $("#establecimiento").val();
    $.ajax({
        type: 'POST',
        data: {id_esta},
        url: 'index.php/<?php echo ALM?>Reportes/traerDepositos',
        success: function(data) {

            var resp = JSON.parse(data);
            $('#depo_id').empty();
            $("#depo_id").append("<option value='TODOS'>Todos</option>");
            if(data != null){
                for(var i=0; i<resp.length; i++)
                {
                  $('#depo_id').append("<option value='" + resp[i].depo_id + "'>" +resp[i].descripcion+"</option");
                }
                $("#depo_id").removeAttr('readonly');
            }else{
                $("#depo_id").append("<option value=''>-Sin Depósitos para este Establecimiento-</option");
            }

            $(".habilitado").show();
            wc();
        },
        error: function(data) {
          wc();
            alert('Error');
        }
    });
  }

  // llena select tipo ajuste
  function getTiposArticulos(){

    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: 'index.php/<?php echo ALM?>Reportes/getTiposArticulos',
        success: function(result) {

            if (result == null) {
                alert("fallo");
                return;
            }
            var option_tipo = '<option value="" disabled selected>-Seleccione opcion-</option>';
            for (let index = 0; index < result.length; index++) {
                option_tipo += '<option value="' + result[index].tabl_id + '">' + result[index].valor + '</option>';
            }
            $('#tipo').html(option_tipo);
        },
        error: function() {
            alert('Error');
        }
    });
  }

  // filtrado de datos
  function filtrar() {
    check = checkCampos();
    if(check == true){
      wo();
      var data = {};
      data.desde = $("#datepickerDesde").val();
      fec1 = $("#datepickerDesde").val();
      data.hasta = $("#datepickerHasta").val();
      fec2 = $("#datepickerHasta").val();
      //data.esta_id = $("#establecimiento").val();
      data.depo_id = $("#depo_id").val();
      depo = $("#depo_id").val();
      data.arti_id = selectItem.arti_id; // se completa en traz-comp-almacen/articulo/componente.php
      arti = selectItem.arti_id;
      data.tipo = $("#tipo>option:selected").val();
      tpoArt = $("#tipo>option:selected").val();
      data.estado = $("#estado>option:selected").val();
      estado = $("#estado>option:selected").val();

      $.ajax({
        type: 'POST',
        data: {data},
        url: '<?php echo base_url(ALM) ?>Reportes/articulosVencidos',
        success: function(result) {
                $('#reportContent').empty();
                $('#reportContent').html(result);
                wc();
        },
        error: function() {
          alert('Error');
          wc();
        },
        complete: function(result) {
          wc();
        }
      });
    }else{
      alert(check);
    }
  }

  function exportarExcel(){

    window.open("<?php echo base_url(ALM); ?>Reportes/excelTest?fec1="+fec1+"&fec2="+fec2+"&depo="+depo+"&arti="+arti+"&tpoArt="+tpoArt+"&estado="+estado);

  }

  function exportarPDF(){
    $(function(){    
      $('').printThis({
          debug: false,
          importCSS: true,
          importStyle: true,
          loadCSS: "<?php echo base_url('lib/bower_components/bootstrap/dist/css/bootstrap.min.css')?>",
          // loadCSS: "lib/bower_components/bootstrap/dist/css/bootstrap.min.css",
          copyTagClasses: true,
          pageTitle : "TRAZALOG TOOLS",
          header: "<h1 style='text-align: center;'>Reporte Articulos Vencidos</h1>",        
          footer: $("#reportContent").clone().children().find('table').css('display','block').get(0),
          beforePrint: function(){
            $("table.dataTable thead .sorting:after").attr('display','none');
          },
          afterPrint: function(){
            $("table.dataTable thead .sorting:after").attr('display','block');
          }
      });
    });
  }
  function checkCampos(){
    if($("#datepickerDesde").val() == ''){return "Debe seleccionar una fecha Desde!";}
    if($("#datepickerHasta").val() == ''){return "Debe seleccionar una fecha Hasta!";}
    if(!selectItem){return "Debe seleccionar un Artículo!";}
    if($("#tipo>option:selected").val() == ''){return "Debe seleccionar un Tipo de Artículo!";}
    return true;
  }
</script>