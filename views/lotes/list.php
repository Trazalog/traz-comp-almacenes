<input type="hidden" id="permission" value="<?php echo $permission;?>">

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Stock</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                <table id="stock" class="table table-bordered table-hover">
                    <thead>
                        <th class="text-center">N° Lote</th>
                        <th>Código</th>
                        <th>Producto</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Unidad de Medida</th>
                        <th class="text-center">Recipiente</th>
                        <th class="text-center">Fecha Creacion</th>
                        <th>Depósito</th>
                       <th>Recipiente</th>
			 <th>Estado</th>
                    </thead>
                    <tbody>
                        <?php
               
                          foreach($list as $f)
                          {
                            echo '<tr>';
                            echo '<td class="text-center">'.($f['codigo']==1?'S/L':$f['codigo']).'</td>';
                            echo '<td>'.$f['artBarCode'].'</td>';
                            echo '<td>'.$f['artDescription'].'</td>';
                            echo '<td class="text-center">'.$f['cantidad'].'</td>';
                            echo '<td class="text-center">'.$f['un_medida'].'</td>';
                            echo '<td class="text-center">'.$f['nom_reci'].'</td>';
                            echo "<td class='text-center'>".fecha(substr($f['fec_alta'], 0, 10))."</td>";
                            echo '<td>'.$f['depositodescrip'].'</td>';
                           echo '<td>'.$f['recipiente'].'</td>';
			    echo '<td class="text-center">'.estado($f['estado']).'</td>';
                            echo '</tr>';
                          }
                        
                      ?>
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
</div><!-- /.row -->
<script>
DataTable($('table#stock'),false,1);
</script>

