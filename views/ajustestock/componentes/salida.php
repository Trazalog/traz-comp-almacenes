<div class="box box-default tag-descarga" id="boxSalida" style="opacity: 0.5;">
    <div class="box-header with-border">
        <i class="fa fa-sign-out"></i>
        <h3 class="box-title"> SALIDA</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Articulo:</label>
                    <select class="form-control select2 select2-hidden-accesible articulos" id="articulosal"
                        name="articulosal" required>
                        <option value="" disabled selected>-Seleccione opción-</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Lote:</label>
                    <select class="form-control select2 select2-hidden-accesible" id="lotesal"
                        name="lotesal" required>
                        <option value="" disabled selected>-Seleccione opción-</option>
                    </select>
                    <label id="detallesal" class="select-detalle"></label>
                    <br>
                    <label id="infosal" class="text-blue"></label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Cantidad:</label>
                    <input name="cantidadsal" class="form-control inp-descarga" type="number" id="cantidadsal" min="0" step="0.01" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" onchange="if(this.value < 0) { Swal.fire({title: 'Error', text: 'Ingrese un numero mayor a 0', type: 'error', confirmButtonText: 'Aceptar'}); this.value = 0; }">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Unidad de Medida:</label>
                    <input name="unidadsal" readonly class="form-control inp-descarga" type="text" id="unidadsal">
                </div>
            </div>
        </div>
    </div>
    <!-- box-body -->
</div>
<!-- box -->