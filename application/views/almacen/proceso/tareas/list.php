<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h1 class="box-title">Mis Tareas</h1>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="datagrid">
                    <table id="sector" class="table table-hover table-striped">
                        <thead>
                            <tr>

                                <?php  

                  echo '<th width="7%"'.($device == 'android' ? 'class= "hidden"' :'class= ""').' >Estado</td>';

                  echo '<th>Tarea</th>';

                  echo '<th>Descripción</th>';

                  #echo '<th width="7%"'.($device == 'android' ? 'class= "hidden"' :'class= ""').' >Id S.S</td>';    
                  if(viewOT) echo '<th width="7%"'.($device == 'android' ? 'class= "hidden"' :'class= ""').' >Id OT</td>';          
                                  

                  echo '<th '.($device == 'android' ? 'class= "hidden"' :'class= ""').' >Fecha Asignación</td>';                 
                                  

                  echo '<th '.($device == 'android' ? 'class= "hidden"' :'class= ""').' >Fecha Vto.</td>';
                  
                //   echo '<th>caseId</td>';

                  // echo '<th '.($device == 'android' ? 'class= "hidden"' :'class= ""').' >Prioridad</td>';
                                  
                 
              
              ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php                
                foreach($list as $f){

                  
                  $id=$f["id"];
                  $asig = $f['assigned_id'];

                  echo '<tr id="'.$id.'" class="'.$id.'" style="cursor: pointer;">';                   

                  if ( $asig != "")  {
                    echo '<td '.($device == 'android' ? 'class= "celda nomTarea hidden"' :'class= "celda nomTarea text-center"').'><i class="fa fa-user" style="color: #5c99bc ; cursor: pointer;"" title="Asignado" data-toggle="modal" data-target="#modalSale"></i></td>';
                  }else{
                    echo '<td '.($device == 'android' ? 'class= "celda nomTarea hidden"' :'class= "celda nomTarea text-center"').'><i class="fa fa-user" style="color: #d6d9db ; cursor: pointer;"" title="Sin Asignar" data-toggle="modal" data-target="#modalSale"></i></td>';
                  }
                  
                    echo '</td>';

                    echo '<td class="celda nomTarea" style="text-align: left">'.$f['displayName'].'</td>';  
                     
                    echo '<td class="celda tareaDesc" style="text-align: left">'.substr($f['displayDescription'],0,500).'</td>';                
                      
                    #echo '<td '.($device == 'android' ? 'class= "celda nomTarea hidden"' :'class= "celda nomTarea"').' style="text-align: left"><span data-toggle="tooltip" class="badge bg-blue" >'.$f['ss'].'</span></td>';   
                    
                    if(viewOT) echo '<td>???</td>';#echo '<td '.($device == 'android' ? 'class= "celda nomTarea hidden"' :'class= "celda nomTarea"').' style="text-align: left"><span data-toggle="tooltip" class="badge bg-orange" >'.$f['ot'].'</span></td>';                  
              
                    echo '<td '.($device == 'android' ? 'class= "celda nomTarea hidden tddate"' :'class= "celda nomTarea tddate"').' style="text-align: left">'.formato_fecha_hora($f['assigned_date']).'</td>'; 

                    echo '<td '.($device == 'android' ? 'class= "celda nomTarea hidden tddate"' :'class= "celda nomTarea tddate"').' style="text-align: left">'.formato_fecha_hora($f['dueDate']).'</td>';
                  
                    // echo '<td>'.$f['caseId'].'</td>';


                    echo '</tr>';

              //  }
            }
              ?>

                        </tbody>
                    </table>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
</div><!-- /.row -->

<script>
//Tomo valor de la celda y carga detalle de la tarea
$('tbody tr').click(function() {
    var id = $(this).attr('id');
    linkTo("almacen/Proceso/detalleTarea/" + id);
});
</script>