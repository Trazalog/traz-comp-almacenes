       <!-- jQuery 3 -->
       <script src="<?php base_url() ?>lib/bower_components/jquery/dist/jquery.min.js"></script>
       <!-- jQuery UI 1.11.4 -->
       <script src="<?php base_url() ?>lib/bower_components/jquery-ui/jquery-ui.min.js"></script>
       <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
       <script>
$.widget.bridge('uibutton', $.ui.button);
       </script>
       <!-- Bootstrap 3.3.7 -->
       <script src="<?php base_url() ?>lib/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

       <!-- jQuery Knob Chart -->
       <script src="<?php base_url() ?>lib/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
       <!-- daterangepicker -->
       <script src="<?php base_url() ?>lib/bower_components/moment/min/moment.min.js"></script>

       <script src="<?php base_url() ?>lib/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
       <!-- datepicker -->
       <script src="<?php base_url() ?>lib/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js">
       </script>
       <!-- Bootstrap WYSIHTML5 -->
       <script src="<?php base_url() ?>lib/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
       <!-- Slimscroll -->
       <script src="<?php base_url() ?>lib/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
       <!-- FastClick -->
       <script src="<?php base_url() ?>lib/bower_components/fastclick/lib/fastclick.js"></script>

       <script src="<?php base_url() ?>lib/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>

       <script src="<?php base_url() ?>lib/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

       <!-- AdminLTE App -->
       <script src="<?php base_url() ?>lib/dist/js/adminlte.min.js"></script>

       <script src="<?php base_url();?>lib/plugins/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

       <!--Arma Tablas -->
       <script src="<?php echo base_url('lib/tabla.js');?>"></script>


       <script>
function DataTable(tabla) {
    $(tabla).DataTable({
  "aLengthMenu": [ 10, 25, 50, 100 ],
  "columnDefs": [ {
    "targets": [ 0 ], 
    "searchable": false
  },
  {
    "targets": [ 0 ], 
    "orderable": false
  } ],
  "order": [[1, "asc"]],
  "language":{
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ registros",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar:",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
    }
});

}

function existFunction(nombre){
		var fn = window[nombre]; 
		return typeof fn === 'function';
  }
       </script>