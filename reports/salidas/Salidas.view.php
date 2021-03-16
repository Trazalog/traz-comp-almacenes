<?php

use \koolreport\widgets\koolphp\Table;
// use \koolreport\widgets\google\BarChart;
use \koolreport\widgets\google\ColumnChart;
// use \koolreport\widgets\google\PieChart;
// use \koolreport\inputs\Select2;
// use \koolreport\widgets\koolphp\Card;
?>



  <!--_________________BODY REPORTE___________________________-->
  <div id="reportContent" class="report-content">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-solid">
          <div class="box box-primary">

            <div class="box-header with-border">
              <h3 class="box-title">
                Reporte Histórico de Artículos
              </h3>
            </div>
            <br><br>
            <!--_________________FILTRO_________________-->
            <form id="frm-filtros">
              <div class="row">


                <div class="form-group">
                  <div class="col-md-4">
                    <label style="padding-left: 20%;">Desde</label>
                    <div class="input-group date">
                      <a class="input-group-addon" id="daterange-btn" title="Más fechas">
                        <i class="fa fa-magic"></i>
                        <span></span>
                      </a>
                      <input type="date" class="form-control pull-right" id="datepickerDesde" name="datepickerDesde" placeholder="Desde">
                    </div>
                  </div>
                </div>






                <div class="form-group">
                  <div class="col-md-4">
                    <label>Hasta</label>
                    <div class="input-group">
                      <input type="date" class="form-control" id="datepickerHasta" name="datepickerHasta" placeholder="Hasta">
                      <a class="input-group-addon" style="cursor: pointer;" onclick="filtro()" title="Más filtros">
                        <i class="fa fa-filter"></i>
                      </a>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-md-4">
                    <label>Hasta</label>
                    <div class="input-group">
                      <input type="test" class="form-control" id="datepickerHasta" name="datepickerHasta" placeholder="Hasta">
                      <a class="input-group-addon" style="cursor: pointer;" onclick="filtro()" title="Más filtros">
                        <i class="fa fa-filter"></i>
                      </a>
                    </div>
                  </div>

                </div>
              </div>

              <br>


            </form>
            <!-- <br> -->
            <hr>
            <!--_________________TABLA_________________-->
            <div class="box-body">
              <div class="col-md-12">
                <?php
                Table::create(array(
                  "dataStore" => $this->dataStore('data_salidas_table'),
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
                    "boleta" => array(
                      "label" => "Nº bol."
                    ),
                    "fecha" => array(
                      "label" => "Fecha"
                    ),
                    "nombre" => array(
                      "label" => "Cliente"
                    ),
                    "razon_social" => array(
                      "label" => "Transporte"
                    ),
                    "patente" => array(
                      "label" => "Dominio"
                    ),
                    "neto" => array(
                      "label" => "Neto"
                    ),
                    "descripcion" => array(
                      "label" => "Producto"
                    ),
                    "cantidad" => array(
                      "label" => "Cantidad"
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


              <div class="col-md-12">

                <div class="col-md-12">
                    <hr>
                </div>


                <div class="form-group">
                    <div class="col-md-4">
                        <label for="Domicilio" name="Domicilio">Label:</label>
                        <input type="text" class="form-control" id="Domicilio">
                    </div>
                </div>
                <!--_____________________________________________-->


                <div class="form-group">
                    <div class="col-md-4">
                        <label for="Dpto" name="Departamento">label:</label>
                        <select class="form-control select2 select2-hidden-accesible" id="Dpto">
                            <option value="" disabled selected>-Seleccione opcion-</option>
                            <?php
                        foreach ($Dpto as $i) {
                            echo '<option>'.$i->nombre.'</option>';
                        }
                        ?>
                        </select>
                    </div>
                </div>

                <!--_____________________________________________-->


                <div class="form-group">
                    <div class="col-md-4">
                        <label for="Numero de registro" name="Numero_registro">Label:</label>
                        <input type="text" class="form-control" id="Numero de registro">
                    </div>
                </div>
                <!--_____________________________________________-->


            </div>



















            </div>
            <!-- _________________FIN TABLA_________________-->

          </div>
        </div>
        <!--_________________ FIN CHARTS_________________-->
      </div>
    </div>
    <!--_________________ FIN BODY REPORTE ____________________________-->
  </div>

  <script>
    filtroIngresos();
    cantidadIngresos();
    fechaMagic();

    $('tr > td').each(function() {
      if ($(this).text() == 0) {
        $(this).text('-');
        $(this).css('text-align', 'center');
      }
    });

    // DataTable($('.dataTable'));
    $('.dataTable').dataTable();

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

    function filtrar() {
      var data = new FormData($('#frm-filtros')[0]);
      data = formToObject(data);
      wo();
      var url = 'salidas';
      $.ajax({
        type: 'POST',

        data: {
          data
        },
        url: '<?php echo base_url(PRD) ?>Reportes/' + url,
        success: function(result) {
          $('#reportContent').empty();
          $('#reportContent').html(result);
        },
        error: function() {
          alert('Error tremendo');
        },
        complete: function(result) {
          wc();
        }
      });
    }

    function filtro() {
      var filtrosExt = $('#filtrosExt').attr('data');
      if (filtrosExt == "false") {
        $('#filtrosExt').removeAttr('hidden');
        $('#filtrosExt').attr('data', "true");
      } else {
        $('#filtrosExt').attr('hidden', '');
        $('#filtrosExt').attr('data', "false");
      }
    }

    function filtroIngresos() {
      wo();
      $.ajax({
        type: "GET",
        dataType: "JSON",
        url: "<?php echo base_url(PRD) ?>Reportes/filtroSalidas",
        success: function(rsp) {
          var html_trans = '<option selected disabled>Seleccione transportista</option>';
          // debugger;
          if (_isset(rsp.transportista)) {
            rsp.transportista.forEach(element => {
              html_trans += "<option value=" + element.cuit + ">" + element.razon_social + "</option>";
            });
          }
          $('#tran_id').html(html_trans);

          var html_clie = '<option selected disabled>Seleccione cliente</option>';
          // debugger;
          if (_isset(rsp.clientes)) {
            rsp.clientes.forEach(element => {
              html_clie += "<option value=" + element.clie_id + ">" + element.clie_nombre + "</option>";
            });
          }
          $('#clie_id').html(html_clie);
        },
        error: function(rsp) {
          alert('Error tremendo');
        },
        complete: function() {
          wc();
        }
      })
    }

    function cantidadIngresos() {
      wo();
      $('#cant_salidas').text('0');
      if ($('.dataTable tbody tr').find('td').text() == "No data available in table") {
        $('#cant_salidas').text(0);
        return;
      }
      var count = 0;
      $('.dataTable tbody').children('tr').each(function() {
        count++;
        var estado = $(this).find('td:eq(8)').text();
        var color = '';
        // debugger;
        switch (estado.trim()) {
          case 'CARGADO':
            estado = 'Cargado';
            color = 'blue';
            break;
          case 'EN CURSO':
            estado = 'En Curso';
            color = 'green';
            break;
          case 'FINALIZADO':
            estado = 'Finalizado';
            color = 'yellow';
            break;

          default:
            estado = 'S/E';
            color = '';
            break;
        }
        $(this).find('td:eq(8)').html(bolita(estado, color));
      })
      $('#cant_salidas').text(count);
    }

    $('.flt-clear').click(function() {
      $('#frm-filtros')[0].reset();
    });
  </script>
