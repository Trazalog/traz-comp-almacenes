<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">ABM Tablas</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->

    <div class="box-body">
        <div>
            <div class="form-group">
                <label>Selecccionar Tabla:</label>
                <select id="tablas" class="form-control">
                    <option value="0" disabled selected>Seleccionar Tabla...</option>
                    <option value="new"> + Nueva Tabla</option>
                    <?php 
                        foreach ($list as $o) {
                            echo '<option value="'.$o['tabla'].'">'.$o['tabla'].'</option>';
                        }
                    ?>
                </select>
            </div>
        </div>
    </div>
</div>
<!-- /.box-body -->


<script>
$('#tablas').on('change',function(){
  if(this.value == "new") {$('.modal').modal('show');this.value=0; return;}
});
function new_tabla(){

}

</script>

<div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Default Modal</h4>
              </div>
              <div class="modal-body">
                 <div class="form-group">
                  <label for="tabla">Nombre Tabla</label>
                  <input type="email" class="form-control" id="tabla" placeholder="Nombre Tabla">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="new_tabla()">Guardar</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->