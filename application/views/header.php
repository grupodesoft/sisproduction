<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>/assets/images/icowoori.ico">
    <!-- Firefox, Opera  -->
    <link rel="icon" href="<?php echo base_url(); ?>/assets/images/icowoori.ico">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv='cache-control' content='no-cache'>
    <meta http-equiv='expires' content='0'>
    <meta http-equiv='pragma' content='no-cache'>
    <title>Sistema de Producción Woori</title>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>/assets/principal/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/assets/fonts/css/font-awesome.min.css" rel="stylesheet">

    <link href="<?php echo base_url(); ?>/assets/css/style.css" rel="stylesheet">


        <!-- Waves Effect Css -->
    <link href="<?php echo base_url(); ?>/assets/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="<?php echo base_url(); ?>/assets/plugins/animate-css/animate.css" rel="stylesheet" />



    <!-- Wait Me Css -->
    <link href="<?php echo base_url(); ?>/assets/plugins/waitme/waitMe.css" rel="stylesheet" />

    <!-- Bootstrap Select Css -->
    <link href="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />




    <!-- Custom styling plus plugins -->
    <link href="<?php echo base_url(); ?>/assets/css/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/assets/css/maps/jquery-jvectormap-2.0.3.css" />
    <link href="<?php echo base_url(); ?>/assets/css/icheck/flat/green.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/assets/css/floatexamples.css" rel="stylesheet" />
      <link href="<?php echo base_url(); ?>/assets/css/stylewoori.css" rel="stylesheet" />
    <!-- Scripts -->
    <script src="<?php echo base_url() ?>/assets/js/vue/vue.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/axios/axios.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/js/pagination/pagination.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/jquery/jquery-3.3.1.min.js"></script>
    <!-- SweetAlert -->
    <script src="<?php echo base_url(); ?>/assets/js/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/js/sweetalert2/dist/sweetalert2.min.css">
    <!-- select2 -->
    <link href="<?php echo base_url(); ?>/assets/css/select/select2.min.css" rel="stylesheet">


    <link href="<?php echo base_url(); ?>/assets//media/css/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>/assets/js/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>/assets/js/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>/assets/js/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>/assets/js/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/bootstrapvalidator/bootstrapValidator.min.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>/assets/js/bootstrapvalidator/bootstrapValidator.min.js"></script>



        <!--[if lt IE 9]>
              <script src="../assets/js/ie8-responsive-file-warning.js"></script>
          <![endif]-->
          <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
            <![endif]-->
            <style type="text/css">

                .modal-mask {
                    position: fixed;
                    z-index: 9998;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, .5);
                    display: table;
                    transition: opacity .3s ease;
                }
                .modal-wrapper {
                    display: table-cell;
                    vertical-align: middle;
                }


            </style>
            <script type="text/javascript">
            function onReady(callback) {
              var intervalId = window.setInterval(function() {
                if (document.getElementsByTagName('body')[0] !== undefined) {
                  window.clearInterval(intervalId);
                  callback.call(this);
                }
              }, 1000);
              }

              function setVisible(selector, visible) {
              document.querySelector(selector).style.display = visible ? 'block' : 'none';
              }

              onReady(function() {
              setVisible('body', true);
              setVisible('#loadingnew', false);
              });
            </script>

        </head>

        <body class="nav-md">
        <div id="loadingnew"></div>
        <div class="container body">
            <div class="main_container">
                <div class="col-md-3 left_col">
                    <div class="left_col scroll-view">
                        <div class="navbar nav_title" style="border: 0; padding-right:20px;"  align="center">
                            <a href="#" class="site_title"> <img class="imglogo" src="<?php echo base_url(); ?>/assets/images/woorilogo.png" alt=""> </a>

                        </div>
                        <div class="clearfix"></div>
                        <!-- menu prile quick info -->
                        <div class="profile">
                            <div class="profile_pic">
                                <img src="<?php echo base_url(); ?>/assets/images/user.png" alt="..." class="img-circle profile_img">
                            </div>
                            <div class="profile_info">
                                <span>Bienvenido,</span>
                                <h2 style="padding-top: 10px"><?php echo $this->session->usuario
                                ?></h2>
                            </div>
                        </div>
                        <!-- /menu prile quick info -->
                        <br />
                        <!-- sidebar menu -->
                        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                            <div class="menu_section">
                                <h3><?php echo $this->session->rol
                                ?></h3>
                                <ul class="nav side-menu">
                                    <li><a href="<?= base_url('/Admin/') ?>"><i class="fa fa-home"></i> Inicio</a> </li>
                                    <li><a href="<?= base_url('/Transferencia/') ?>"><i class="fa fa-archive" aria-hidden="true"></i> Packing</a> </li>
                                    <li><a href="<?= base_url('/Calidadp/') ?>"><i class="fa fa-thumbs-o-up"></i> Calidad</a> </li>
                                    <li><a href="<?= base_url('/Bodegap/') ?>"><i class="fa fa-home"></i> Almacen</a> </li>
                                    <li><a href="<?= base_url('/Salida/') ?>"><i class="fa fa-sign-out" aria-hidden="true"></i>Salida</a> </li>
                                    <li><a href="<?= base_url('/Orden/') ?>"><i class="fa fa-check-square-o" aria-hidden="true"></i>Orden</a> </li>
                                    <li><a href="<?= base_url('/Proceso/') ?>"><i class="fa fa-flag-o" aria-hidden="true"></i>Procesos</a> </li>
                                    <li><a href="<?= base_url('/Catalogo/') ?>"><i class="fa fa-folder-open" aria-hidden="true"></i>Catalogo</a> </li>
                                    <li><a><i class="fa fa-bar-chart" aria-hidden="true"></i> Reporte <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu" style="display: none">
                                              <!--<li><a href="<?php //echo base_url('/reporte/transferencia') ?>"> Transferencia</a> </li>-->
                                              <li><a href="<?= base_url('/Reporte/reportepacking') ?>"> Reporte packing</a> </li>
                                              <li><a href="<?= base_url('/Reporte/reportecalidad') ?>"> Reporte calidad</a> </li>
                                              <li><a href="<?= base_url('/Reporte/reportealmacen') ?>"> Reporte almacen</a> </li>
                                              <li><a href="<?= base_url('/Reporte/procesomaquina') ?>"> Por maquina</a> </li>
                                              <li><a href="<?= base_url('/Reporte/porprocesos') ?>"> Por proceso</a> </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- /sidebar menu -->

                    </div>
                </div>

                <!-- top navigation -->
                <div class="top_nav">
                    <div class="nav_menu">
                        <nav class="" role="navigation">
                            <div class="nav toggle">
                                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                            </div>

                            <ul class="nav navbar-nav navbar-right">

                                <li class="">
                                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <img src="<?php echo base_url(); ?>/assets/images/user.png" alt=""><strong><?php echo $this->session->name; ?></strong>
                                        <span class=" fa fa-angle-down"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                                        <li><a href="<?= base_url('/login/logout') ?>"><i class="fa fa-sign-out pull-right"></i> Salir</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- /top navigation -->
