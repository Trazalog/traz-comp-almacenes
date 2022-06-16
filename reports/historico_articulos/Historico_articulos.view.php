<?php
  use \koolreport\widgets\koolphp\Table;
  use \koolreport\widgets\google\ColumnChart;
?>

<div id="reportContent" class="report-content">
  <div class="box box-primary">

    <div class="box-header with-border">
        <div class="box-tittle">
            <h4>Movimientos de Stock</h4>
        </div>
    </div>

    <div class="box-body">

      <!-- _____ GRUPO 1 _____ -->
        <div class="col-md-12">

            <div class="form-group">

              <div class="col-md-4 col-md-6 mb-4 mb-lg-0">
                <label style="padding-left: 20%;">Desde <strong class="text-danger">*</strong> :</label>
                <div class="input-group date">
                  <a class="input-group-addon" id="daterange-btn" title="Más fechas">
                    <i class="fa fa-magic"></i>
                    <span></span>
                  </a>
                  <input type="date" class="form-control pull-right" id="datepickerDesde" name="datepickerDesde" placeholder="Desde">
                </div>
              </div>


              <div class="col-md-4 col-md-6 mb-4 mb-lg-0">
                <label>Hasta  <strong class="text-danger">*</strong> :</label>
                <div class="input-group date">
                  <input type="date" class="form-control" id="datepickerHasta" name="datepickerHasta" placeholder="Hasta">
                  <a class="input-group-addon" style="cursor: pointer;" onclick="filtro()" title="Más filtros">

                  </a>
                </div>
              </div>

              <div class="col-md-4 col-md-6 mb-4 mb-lg-0">
                  <label for="tipoajuste" class="form-label">Tipo Movimiento  <strong class="text-danger">*</strong>  :</label>
                  <select class="form-control select2 select2-hidden-accesible" id="tipoajuste" name="tipoajuste">
                      <option value="-1" disabled selected>-Seleccione-</option>
                      <option value="TODOS">Todos</option>
                      <option value="INGRESO">Ingreso</option>
                      <option value="EGRESO">Egreso</option>
                      <option value="AJUSTE">Ajuste</option>
                      <option value="ETAPAPRODINGRESO">Etapa Prod Ingresos</option> <!-- produccion caso 1 -->
                      <option value="ENPROCESOENETAPA">Prod En Proceso Etapa</option> <!-- produccion caso 2 -->
                      <option value="DESCENPROCESO">Desc. en Proceso</option> <!-- produccion caso 3 -->
                  </select>

              </div>

            </div>

        </div>
      <!-- _____ GRUPO 1 _____ -->

      <div class="col-md-12">
        <br>
      </div>

      <!-- _____ GRUPO 2 _____ -->
        <div class="col-md-12">

            <div class="form-group">

              <div class="col-md-4 col-md-6 mb-4 mb-lg-0">
                <label for="establecimiento" class="form-label">Establecimiento  <strong class="text-danger">*</strong> :</label>
                <select onchange="seleccionesta(this)" class="form-control select2 select2-hidden-accesible" id="establecimiento" name="establecimiento" />
              </div>

              <div class="col-md-4 col-md-6 mb-4 mb-lg-0 habilitado">
                <label for="depo_id" class="form-label">Depósito:</label>
                <select class="form-control select2 select2-hidden-accesible" id="depo_id" name="depo_id" />
              </div>

              <div class="col-md-4 col-md-6 mb-4 mb-lg-0 habilitado" >
                  <label for="zona" class="form-label">Artículo  <strong class="text-danger">*</strong> :</label>
                <div id="list_articulos"> </div>
              </div>

            </div>
        </div>

        <div class="col-md-12">

            <div class="form-group">

              <div class="col-md-4 col-md-6 mb-4 mb-lg-0 habilitado">
                  <label for="lote_id" class="form-label">Lote:</label>
                  <select class="form-control select2 select2-hidden-accesible" id="lote_id" name="lote_id"  disabled />
              </div>

            </div>

        </div>
      <!-- _____ GRUPO 2 _____ -->

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
            "referencia" => array(
              "label" => "Referencia"
            ),
            "codigo" => array(
              "label" => "Cod. Artículo"
            ),
            "descripcion" => array(
              "label" => "Descrip."
            ),
            "lote" => array(
              "label" => "Lote"
            ),
            "cantidad" => array(
              "label" => "Cantidad"
            ),
            "stock_actual" => array(
              "label" => "Stock"
            ),
            "deposito" => array(
              "label" => "Depósito"
            ),
            array(
              "label" => "Fecha",
              "value" => function($row) {
                $aux = explode("T",$row["fec_alta"]);
                $row["fec_alta"] = date("d-m-Y",strtotime($aux[0]));
                return $row["fec_alta"];
              },
              "type" => "date"
            ),
            "tipo_mov" => array(
              "label" => "Tipo Movim."
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
        <button type="button" class="btn btn-primary" onclick="exportarExcel()">Exportar</button>
      </div>
    </div>

  </div>
</div>


<script>
  //variables que van a mantener el estado para poder generar el excel
  var fec1;var fec2;var tpoMov;var esta;var depo;var artic;var lote;

  // carga select de Establecimientos, componente Articulos y llama configuracion selects de fecha
  $(function() {
    $(".habilitado").hide();
    wo();
    $("#list_articulos").load("<?php echo base_url(ALM); ?>Reportes/cargaArticulos");
    getEstablecimientos();
    fechaMagic();
    //getTipoAjuste();
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

    $(".habilitado").show();

    wo();
    var id_esta = $("#establecimiento").val();
    $.ajax({
        type: 'POST',
        data: {id_esta},
        url: 'index.php/<?php echo ALM?>Reportes/traerDepositos',
        success: function(data) {

            var resp = JSON.parse(data);
            $('#depo_id').empty();
            $("#depo_id").append("<option value='TODOS'>Todos</option");
            if(data != null){
                for(var i=0; i<resp.length; i++)
                {
                  $('#depo_id').append("<option value='" + resp[i].depo_id + "'>" +resp[i].descripcion+"</option");
                }
                $("#depo_id").removeAttr('readonly');
            }else{
                $("#depo_id").append("<option value=''>-Sin Depósitos para este Establecimiento-</option");
            }
            wc();
        },
        error: function(data) {
          wc();
            alert('Error');
        }
    });
  }

  // trae lotes por id de deposito y de articulo
  $("body").on('change', '#inputarti', function(){

    var depo_id = $('#depo_id option:selected').val();
    var arti_id = selectItem.arti_id; // se completa en traz-comp-almacen/articulo/componente.php
    if(depo_id == ""){
      alert('Por favor seleccione deposito...');
      return;
    }
    wo();

    $.ajax({
        type: 'POST',
        data: {arti_id: arti_id, depo_id: depo_id},
        url: 'index.php/<?php echo ALM?>Reportes/traerLotes',
        success: function(data) {

            $('#lote_id').empty();
            var resp = JSON.parse(data);
            if (resp == null) {
              $('#lote_id').append('<option value="" disabled selected>-Sin Lotes para este artículo-</option>');
            } else {
              console.table(resp);
              console.table(resp[0].lote_id);
              // $('#lote_id').append('<option value="" disabled selected>-Seleccione opcion-</option>');
              $('#lote_id').append('<option value="TODOS">Todos</option>');
              for(var i=0; i<resp.length; i++)
              {
                  $('#lote_id').append("<option value='" + resp[i].lote_id + "'>" +resp[i].codigo+"</option");
              }
              $("#lote_id").removeAttr('disabled');
            }
            wc();
        },
        error: function(data) {
            alert('Error');
            wc();
        }
    });
  });

  // llena select tipo ajuste
  function getTipoAjuste(){

    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: 'index.php/<?php echo ALM?>general/Tipoajuste/obtenerAjuste',
        success: function(result) {

            if (!result.status) {
                alert("fallo");
                return;
            }
            result = result.data;
            var option_ajuste = '<option value="" disabled selected>-Seleccione opcion-</option>';
            for (let index = 0; index < result.length; index++) {
                option_ajuste += '<option value="' + result[index].nombre + '" data="' + result[index].tipo + '">' + result[index].nombre + '</option>';
            }
            $('#tipoajuste').html(option_ajuste);
        },
        error: function() {
            alert('Error');
        }
    });
  }

  // filtrado de datos
  function filtrar() {
 debugger;


   // wo();
    var data = {};
    data.desde = $("#datepickerDesde").val();
    fec1 = $("#datepickerDesde").val();
    data.hasta = $("#datepickerHasta").val();
    fec2 = $("#datepickerHasta").val();
    data.tipo_mov = $("#tipoajuste>option:selected").val();
    tpoMov = $("#tipoajuste>option:selected").val();
    data.esta_id = $("#establecimiento").val();
    data.depo_id = $("#depo_id").val();
    depo = $("#depo_id").val();
    data.lote_id = $("#lote_id>option:selected").val();
    lote = $("#lote_id>option:selected").val();

    inputarti = $("#inputarti").val();
    establecimiento = $("#establecimiento").val();
   
    data.arti_id = selectItem.arti_id; // se completa en traz-comp-almacen/articulo/componente.php
    artic = selectItem.arti_id;

    if (fec1 == ''|| fec2 == '' || tipoajuste == '' || establecimiento == '' || inputarti == '') { 
      Swal.fire(
              'Error...',
              'Debes completar los campos Obligatorios (*)',
              'error'
            );
      return;       
    }

    $.ajax({
      type: 'POST',
      data: {data},
      url: '<?php echo base_url(ALM) ?>Reportes/historicoArticulos',
      success: function(result) {
              $('#reportContent').empty();
              $('#reportContent').html(result);
           //   wc();
      },
      error: function() {
        alert('Error');
        wc();
      },
      complete: function(result) {
        wc();
      }
    });
  }

  function exportarExcel(){
    window.open("<?php echo base_url(ALM); ?>Reportes/exportarExcelHistorico?fec1="+fec1+"&fec2="+fec2+"&depo="+depo+"&arti="+artic+"&tpoMov="+tpoMov+"&lote="+lote);
}
</script>