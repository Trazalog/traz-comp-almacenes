 <div class="input-group">
     <input list="articulos" id="inputarti" class="form-control" placeholder="Seleccionar Articulo" onchange="select_list()">
     <datalist id="articulos">
         <?php foreach($items as $o)
           {
             echo  "<option value='".$o->codigo."' data-json='".$o->json."'>";
             unset($o->json);
            }
            ?>
     </datalist>
     <span class="input-group-btn">
         <button class='btn btn-sm btn-primary' data-toggle="modal" data-target="#modal_articulos">
             <i class="glyphicon glyphicon-search"></i></button>
     </span>
 </div>


 <div class="modal" id="modal_articulos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                         aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title" id="myModalLabel"><span id="modalAction"> </span> Listado de Articulos</h4>
             </div>

             <div class="modal-body" id="modalBodyArticle">
                 <div class="row">
                     <div class="col-xs-12" id="modalarticulos">

                     </div>
                 </div>
             </div>

             <div class="modal-footer">
                 <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
             </div>
         </div>
     </div>
 </div>



 <script>
checkTabla("tabla_articulos", "modalarticulos", `<?php echo json_encode($items);?>`, "Add")

function checkTabla(idtabla, idrecipiente, json, acciones) {
    lenguaje = <?php echo json_encode($lang)?>;
    if (document.getElementById(idtabla) == null) {
        armaTabla(idtabla, idrecipiente, json, lenguaje, acciones);
    }

    $('#modal_articulos').on('shown.bs.modal', function() {
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });

}
$(document).off('click', '.tabla_articulos_nuevo').on('click', '.tabla_articulos_nuevo', function() {
    $('.modal').modal('hide');
    var item = $(this).closest('tr').data('json')[0];
    $('#inputarti').val(item.Codigo);
    var json = JSON.stringify($('#articulos').find("[value='" + item.Codigo + "']").data('json'));
    select(json);
});
 </script>