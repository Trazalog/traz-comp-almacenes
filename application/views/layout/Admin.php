<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 2 | Dashboard</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?php base_url() ?>lib/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php base_url() ?>lib/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php base_url() ?>lib/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php base_url() ?>lib/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php base_url() ?>lib/dist/css/skins/_all-skins.min.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="<?php base_url() ?>lib/bower_components/morris.js/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="<?php base_url() ?>lib/bower_components/jvectormap/jquery-jvectormap.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="<?php base_url() ?>lib/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?php base_url() ?>lib/bower_components/bootstrap-daterangepicker/daterangepicker.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="<?php base_url() ?>lib/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

    <link rel="stylesheet" href="<?php base_url() ?>lib/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

          <!-- jQuery 3 -->
    <script src="<?php base_url() ?>lib/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="<?php base_url() ?>lib/bower_components/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <!-- <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script> -->
    <!-- Bootstrap 3.3.7 -->
    <script src="<?php base_url() ?>lib/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Morris.js charts -->
    <!-- <script src="<?php base_url() ?>lib/bower_components/raphael/raphael.min.js"></script>
    <script src="<?php base_url() ?>lib/bower_components/morris.js/morris.min.js"></script> -->
    <!-- Sparkline -->
    <script src="<?php base_url() ?>lib/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <!-- <script src="<?php base_url() ?>lib/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="<?php base_url() ?>lib/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script> -->
    <!-- jQuery Knob Chart -->
    <script src="<?php base_url() ?>lib/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="<?php base_url() ?>lib/bower_components/moment/min/moment.min.js"></script>
    <script src="<?php base_url() ?>lib/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="<?php base_url() ?>lib/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
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
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!-- <script src="<?php base_url() ?>lib/dist/js/pages/dashboard.js"></script> -->
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="<?php base_url() ?>lib/dist/js/demo.js"></script> -->

        <!-- Bootstrap datetimepicker -->
    <link rel="stylesheet" href="<?php base_url();?>lib/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css">
    <script src="<?php base_url();?>lib/plugins/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

    <script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>
</head>
<!-- indicador de carga -->
<div class="waiting" id="waiting">
    <div style="top: 45%; left: 45%; position: fixed;">
        <!--<div class="progress progress active">
            <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
        </div>-->
        <div class="box box-success" style="width: 200px; text-align: center;">
            <br>
            <br>
            <br>
            <div class="box-header">
                <h3 class="box-title" id="waitingText">Cargando...</h3>
            </div>
            <!-- Loading (remove the following to stop the loading)-->
            <div class="overlay">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
            <!-- end loading -->
        </div>
    </div>
</div>

<style>
.waiting {
    background: none;
    display: block;
    position: fixed;
    z-index: 50000;
    overflow: auto;
    bottom: 0;
    left: 0;
    right: 0;
    top: 0;
    display: none;
    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000,endColorstr=#99000000); /* AA, RR, GG, BB */
    /*CSS3*/
    background:rgba(0,0,0,0.5); /*0.5 De Transparencia*/
}
</style>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="index2.html" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>A</b>LT</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>Admin</b>LTE</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>

            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar-->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu menu" data-widget="tree">
                    <li class="header">MAIN NAVIGATION</li>
                    <li class="active treeview">
                        <a href="#">
                            <i class="fa fa-dashboard"></i> <span>Almancenes</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">        
                            <li><a href="#" data-link="almacen/Articulo"><i class="fa fa-circle-o"></i>Articulos</a></li>     
                            <li><a href="#" data-link="almacen/Lote"><i class="fa fa-circle-o"></i>Stock</a></li>
                            <li><a href="#" data-link="almacen/Ordeninsumo"><i class="fa fa-circle-o"></i>Orden de Insumos</a></li>
                            <li><a href="#" data-link="almacen/Remito"><i class="fa fa-circle-o"></i>Recepcion Materiales</a></li>
                            <li><a href="#" data-link="almacen/Lote/puntoPedList"><i class="fa fa-circle-o"></i>Punto de Pedido</a></li>
                            <li><a href="#" data-link="almacen/Notapedido"><i class="fa fa-circle-o"></i>Nota de Pedido</a></li>              
                        </ul>
                    </li>
                    <li><a href="#" data-link="general/Tabla"><i class="fa fa-circle-o"></i>ABM Tablas</a></li>              
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Main content -->
            <section class="content">


            </section>
            <!-- right col -->
        </div>
        <!-- /.row (main row) -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
 

    <!-- Control Sidebar -->
   

    <!-- ./wrapper -->
    <script>
        
        var link = 'almacen/Remito/cargarlista';
        linkTo();
        $('.menu a').on('click',function(){
            link = $(this).data('link');
            linkTo();
        });

        function linkTo(uri = ''){
            if(link == '' && uri == '') return;
            $('.content').empty();
            $('.content').load('<?php base_url() ?>'+(uri==''?link:uri));
        }
        

        /* Abre cuadro cargando ajax */
        function WaitingOpen(texto){
            if(texto == '' || texto == null){
                $('#waitingText').html('Cargando ...');
            }
            else{
                $('#waitingText').html(texto);
            }
            $('#waiting').fadeIn('slow');
        }
        /* Cierra cuadro cargando ajax */
        function WaitingClose(){
            $('#waiting').fadeOut('slow');
        }
    </script>
</body>

</html>