<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Detalle Pedido Materiales</h3>
        </div><!-- /.box-header -->
        <div class="box-body">

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

        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->

<script>

  get_detalle();
  function get_detalle() {

    var id_nota = 1;

    $.ajax({
      type: 'POST',
      data: { id: id_nota },
      url: 'index.php/almacen/Notapedido/getNotaPedidoId',
      success: function (data) {

        $('tr.celdas').remove();
        for (var i = 0; i < data.length; i++) {
          var tr = "<tr class='celdas'>" +
            "<td>" + data[i]['artDescription'] + "</td>" +
            "<td>" + data[i]['cantidad'] + "</td>" +
            "<td>" + data[i]['fecha'] + "</td>" +
            "<td>" + data[i]['fecha_entrega'] + "</td>" +
            "</tr>";
          $('#tabladetalle tbody').append(tr);
        }

        $('#tabladetalle').DataTable({
          "aLengthMenu": [10, 25, 50, 100],
          "columnDefs": [{
            "targets": [0],
            "searchable": false
          },
          {
            "targets": [0],
            "orderable": false
          }],
          "order": [[1, "asc"]],
        });
      },
      error: function (result) {

        console.log(result);
      },
      dataType: 'json'
    });
  }
</script>