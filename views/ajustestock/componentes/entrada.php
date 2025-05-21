<div class="box box-default tag-descarga" id="boxEntrada" style="opacity: 0.5;">
    <div class="box-header with-border">
        <i class="fa fa-sign-in"></i>
        <h3 class="box-title"> ENTRADA</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Artículo:</label>
                    <select class="form-control select2 select2-hidden-accesible articulos" id="articuloent"
                        name="articuloent" required>
                        <option value="" disabled selected>-Seleccione opción-</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Lote:</label>
                    <select class="form-control select2 select2-hidden-accesible" id="loteent" name="loteent" required>
                        <option value="" disabled selected>-Seleccione opción-</option>
                    </select>
                    <label id="detalle" class="select-detalle"></label>
                    <br>
                    <label id="info" class="text-blue"></label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Cantidad:</label>
                    <input name="cantidadent" class="form-control inp-descarga" type="number" id="cantidadent" min="0" onchange="if(this.value < 0) { Swal.fire({title: 'Error', text: 'Ingrese un numero mayor a 0', type: 'error', confirmButtonText: 'Aceptar'}); this.value = 0; }">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Unidad de Medida:</label>
                    <input name="unidadesent" readonly class="form-control inp-descarga" type="text" id="unidadesent">
                </div>
            </div>
        </div>
    </div>
    <!-- box-body -->
</div>
<!-- box -->