<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 2 | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?php echo base_url('lib')?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url('lib')?>/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url('lib')?>/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url('lib')?>/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo base_url('lib')?>/plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Tools-T</b> | Almacenes </a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Inicio de Sesión</p>
            <div class="row">
                <div class="col-xs-12">
                    <div class="alert alert-danger alert-dismissable" id="error" style="display: none">
                        <h4><i class="icon fa fa-ban"></i> Error!</h4>
                        Revise los datos de acceso ingresados
                    </div>
                </div>
            </div>


            <div class="form-group has-feedback">
                <input id="nick" type="text" class="form-control" placeholder="Nick" name="nick">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input id="pass" type="password" class="form-control" placeholder="Password" name="pass">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">

                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button onclick="login()" class="btn btn-primary btn-block btn-flat">Ingresar</button>
                </div>
                <!-- /.col -->
            </div>


        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

    <script>
    function login() {
        var nick = $('#nick').val();
        var pass = $('#pass').val();
        $.ajax({
            data: {
                nick,
                pass
            },
            dataType: 'json',
            type: 'POST',
            url: '<?php echo base_url() ?>Login/validarUsuario',
            success: function(data) {
                if (data.status) {
                    window.location.replace('<?php echo base_url() ?>Dash', );
                } else {
                    $('#error').fadeIn('slow');
                }
            },
            error: function(result) {
                $('#error').fadeIn('slow');
            }
        });
    }
    </script>
    <!-- jQuery 3 -->
    <script src="<?php echo base_url('lib')?>/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="<?php echo base_url('lib')?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="<?php echo base_url('lib')?>/plugins/iCheck/icheck.min.js"></script>


</body>

</html>