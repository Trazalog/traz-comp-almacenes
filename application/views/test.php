 <div class="col-xs-5 input-group">
         <input list="articulos" id="inputtarea" class="form-control">
           <datalist id="articulos">
           <?php foreach($list as $o)
           {
             echo  '<option value="'.$o->id.'" data-json="'.json_encode($o).'">';
            }
            ?>
            </datalist>
            <span class="input-group-btn">
              <button class='btn btn-sm btn-primary' 
                  onclick='checkTabla("tabla_articulos","modalarticulos",`<?php echo json_encode($list);?>`,"Add")' data-toggle="modal" data-target="#modal_articulos">
                  <i class="glyphicon glyphicon-search"></i></button>
             </span> 
       </div>
       <div class="col-xs-6"></div>
 </div>


 <div class="modal" id="modal_articulos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span id="modalAction"> </span> Tareas</h4>
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

 
 
 function checkTabla(idtabla, idrecipiente, json, acciones)
{
  lenguaje = <?php echo json_encode($lang)?>;
  if(document.getElementById(idtabla)== null)
  {
    armaTabla(idtabla,idrecipiente,json,lenguaje,acciones);
  }

 
}
$(document).off('click', '.tabla_articulos_nuevo').on('click', '.tabla_articulos_nuevo',function(){
   alert($(this).closest('tr').data('json')[0]);
 });
 </script>