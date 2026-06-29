<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Movimientos internos</h3>
    </div><!-- /.box-header -->
    <div class="box-body">

    <div class="row">
        <div class="col-xs-12">

            <button class="btn btn-primary" style="min-width: 150px; margin: 10px;" onclick="nuevaSalida()">
                <i class="fa fa-arrow-up"></i> Nueva Salida
            </button>

            <button class="btn btn-primary" style="min-width: 150px; margin: 10px;" onclick="nuevaRecepcion()">
                <i class="fa fa-arrow-down"></i> Nueva Recepción
            </button>

        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
        <table id="movimientos" class="table table-bordered table-hover">
            <thead>
                <tr>

                </tr>
            </thead>
            <tbody>
                <?php
                ?>
            </tbody>
        </table>
    </div><!-- /.box-body -->
</div><!-- /.box -->


<!-- MODAL RECEPCION DEPOSITO -->
<div class="modal fade" id="modalRecepcion" tabindex="-1" role="dialog" aria-labelledby="modalRecepcionLabel">
    <div class="modal-dialog modal-lg" role="document" style="width: 90%; max-width: 1200px;">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color:white;">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalRecepcionBody">
                <p class="text-center">
                    <i class="fa fa-spinner fa-spin"></i> Cargando...
                </p>
            </div>
            
        </div>
    </div>
</div>

<!-- MODAL SALIDA DEPOSITO -->
<div class="modal fade" id="modalSalida" tabindex="-1" role="dialog" aria-labelledby="modalSalidaLabel">
    <div class="modal-dialog modal-lg" role="document" style="width: 90%; max-width: 1200px;">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color:white;">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalSalidaBody">
                <p class="text-center">
                    <i class="fa fa-spinner fa-spin"></i> Cargando...
                </p>
            </div>
            
        </div>
    </div>
</div>

<script>

function nuevaRecepcion(e) {
    wo();

    // Limpiar y abrir el modal
    $('#modalRecepcionBody').html('<p class="text-center"><i class="fa fa-spinner fa-spin"></i> Cargando...</p>');
    $('#modalRecepcion').modal('show');

    wo();
    $.ajax({
        type: 'GET',
        data: {},
        url: '<?php echo base_url(ALM) ?>Movimientointerno/movimientoRecepcion',
        success: function(rsp) {

            // 1. Inyectamos la vista en el modal
            $('#modalRecepcionBody').html(rsp);
            
        },
        error: function() {
            $('#modalRecepcionBody').html('<div class="alert alert-danger"><i class="fa fa-times-circle"></i> Error al cargar los datos de trazabilidad.</div>');
        },
        complete: function() {
            wc();
        }
    });
}


function nuevaSalida(e) {
    wo();

    // Limpiar y abrir el modal
    $('#modalSalidaBody').html('<p class="text-center"><i class="fa fa-spinner fa-spin"></i> Cargando...</p>');
    $('#modalSalida').modal('show');

    wo();
    $.ajax({
        type: 'GET',
        data: {},
        url: '<?php echo base_url(ALM) ?>Movimientointerno/movimientoSalida',
        success: function(rsp) {

            // 1. Inyectamos la vista en el modal
            $('#modalSalidaBody').html(rsp);
            
        },
        error: function() {
            $('#modalSalidaBody').html('<div class="alert alert-danger"><i class="fa fa-times-circle"></i> Error al cargar los datos de trazabilidad.</div>');
        },
        complete: function() {
            wc();
        }
    });
}

// Solución para restablecer el scroll cuando se cierran modales anidados
$(document).on('hidden.bs.modal', '.modal', function () {
    if ($('.modal:visible').length > 0) {
        $('body').addClass('modal-open');
    }
});

</script>