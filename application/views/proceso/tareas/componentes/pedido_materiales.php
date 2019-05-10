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
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->

<script>

  function del(e){
    var id = $(e).closest('tr').data('id');
    alert(id);
  }

  get_detalle();
  function get_detalle() {

    var id = $('#pema_id').val();

    $.ajax({
      type: 'POST',
      data: { id },
      url: 'index.php/almacen/Notapedido/getNotaPedidoId',
      success: function (data) {
            $('#tabladetalle').find('tbody').empty();
        $('tr.celdas').remove();
        for (var i = 0; i < data.length; i++) {
          var tr = "<tr class='celdas' data-id='"+data[i]['arti_id']+"'>" +
            "<td>" + data[i]['artDescription'] + "</td>" +
            "<td>" + data[i]['cantidad'] + "</td>" +
            "<td>" + data[i]['fecha'] + "</td>" +
            "<td>" + data[i]['fecha_entrega'] + "</td>" +
            "<td class='text-light-blue'><i class='fa fa-fw fa-times-circle' style='cursor: pointer; margin-left: 15px;' title='Eliminar' onclick='del(this);'></i></td>" +
            "</tr>";
          $('#tabladetalle tbody').append(tr);
        }
    
      },
      error: function (result) {

        console.log(result);
      },
      dataType: 'json'
    });
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
</script>
