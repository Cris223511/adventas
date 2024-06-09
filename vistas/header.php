<?php
if (strlen(session_id()) < 1)
  session_start();

$nombre_login = $_SESSION['nombre'];
$cargo_login = $_SESSION['cargo_detalle'];
$local_login = $_SESSION['local'];
?>

<style>
  .skin-blue-light .main-header .navbar .sidebar-toggle:hover {
    background: #1e1e1e !important;
  }

  .skin-blue-light .main-header .navbar .nav>li>a:hover {
    background: #1e1e1e !important;
  }

  .btn-default {
    background-color: #ffffff !important;
    transition: .3s ease all;
    border-color: #ccc;
  }

  .btn-default.disabled {
    background-color: #eeeeee !important;
    transition: .3s ease all;
    opacity: 1 !important;
  }

  .nowrap-cell {
    white-space: nowrap;
  }

  .two-row {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  body {
    padding-right: 0 !important
  }

  .table-responsive {
    border: none !important;
  }

  #total2,
  #total {
    font-weight: bold;
  }

  .box {
    box-shadow: none !important;
    border-top: 3px solid #d2d6de !important;
  }
</style>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Sistema De Inventario</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="../public/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../public/css/font-awesome.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
  <!-- Lightbox style -->
  <link href="../public/glightbox/css/glightbox.min.css" rel="stylesheet" asp-append-version="true">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../public/css/_all-skins.min.css">
  <link rel="apple-touch-icon" href="../public/img/apple-touch-icon.png">
  <link rel="shortcut icon" href="../public/img/favicon.ico">

  <!-- DATATABLES -->
  <link rel="stylesheet" type="text/css" href="../public/datatables/jquery.dataTables.min.css">
  <link href="../public/datatables/buttons.dataTables.min.css" rel="stylesheet" />
  <link href="../public/datatables/responsive.dataTables.min.css" rel="stylesheet" />

  <link rel="stylesheet" type="text/css" href="../public/css/bootstrap-select.min.css">

</head>

<body class="hold-transition skin-blue-light sidebar-mini" style="padding: 0 !important;">
  <div class="wrapper">

    <header class="main-header" style="box-shadow: 0px 0px 15px -7px; position: sticky !important; width: 100%">
      <!-- Logo -->
      <a href="escritorio.php" class="logo" style="color: white !important; background-color: #3d3f3f !important;">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>S.I.</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg" style="font-size: 15px;"><b>Sistema De Inventario</b></span>
      </a>

      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar" role="navigation" style="background-color: #3d3f3f !important;">
        <div style="display: flex; align-items: center; float: left;">
          <a href="#" class="sidebar-toggle" style="background: #3d3f3f; color: white !important;" data-toggle="offcanvas" role="button">
            <span class="sr-only">Navegación</span>
          </a>
        </div>
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li class="dropdown user user-menu" style="background: #3d3f3f !important; display: inline-flex; align-items: center; height: 50px;">
              <span style="color: white !important;" class="hidden-xs user-info local"><?php echo '<strong> Local: ' . $local_login . '</strong>' ?></span>
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: white !important; height: 50px;">
                <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="user-image" alt="User Image">
                <span class="hidden-xs user-info user"><?php echo $nombre_login; ?> - <?php echo '<strong> Rol: ' . $cargo_login . '</strong>' ?></span>
              </a>
              <ul class="dropdown-menu">
                <li class="user-header" style="background: #3d3f3f !important;">
                  <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="img-circle" alt="User Image">
                  <p style="color: white !important;">
                    Sistema de Inventario
                    <small>nuestro contacto: +51 937 075 845</small>
                  </p>
                </li>
                <li class="user-footer">
                  <div class="pull-right">
                    <a onclick="destruirSession()" href="../ajax/usuario.php?op=salir">
                      <button type="button" id="mostrarClave" class="btn btn-secondary" style="display: flex; align-items: center; height: 35px; color: #726f6a !important;">
                        Cerrar sesión
                      </button>
                    </a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
          <li class="header"></li>
          <?php
          if ($_SESSION['escritorio'] == 1) {
            echo '<li id="mEscritorio">
              <a href="escritorio.php">
                <i class="fa fa-tasks"></i> <span>Escritorio</span>
              </a>
            </li>';
          }
          ?>

          <?php
          if ($_SESSION['almacen'] == 1) {
            echo '<li id="mAlmacen" class="treeview">
              <a href="#">
                <i class="fa fa-laptop"></i>
                <span>Almacén</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="lArticulos"><a href="articulo.php"><i class="fa fa-circle-o"></i> Artículos</a></li>
                ';
            if ($_SESSION['cargo'] == "superadmin") {
              echo '<li id="lArticulosExternos"><a href="articuloExterno.php"><i class="fa fa-circle-o"></i> Artículos externos</a></li>';
            }
            echo '
                <li id="lLocales"><a href="locales.php"><i class="fa fa-circle-o"></i> Mi local</a></li>
                <li id="lMarcas"><a href="marcas.php"><i class="fa fa-circle-o"></i> Marcas</a></li>
                <li id="lCategorias"><a href="categoria.php"><i class="fa fa-circle-o"></i> Categorías</a></li>
                <li id="lMedidas"><a href="medidas.php"><i class="fa fa-circle-o"></i> Unidades de medida</a></li>
                </ul>
            </li>';
          }
          ?>

          <?php
          if ($_SESSION['servicios'] == 1) {
            echo '<li id="mServicios" class="treeview">
              <a href="#">
                <i class="fa fa-cogs"></i>
                <span>Servicios</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="lServicios"><a href="servicio.php"><i class="fa fa-circle-o"></i> Servicios</a></li>
                ';
            if ($_SESSION['cargo'] == "superadmin") {
              echo '<li id="lServiciosExternos"><a href="servicioExterno.php"><i class="fa fa-circle-o"></i> Servicios externos</a></li>';
            }
            echo '
                </ul>
            </li>';
          }
          ?>

          <?php
          if ($_SESSION['pagos'] == 1) {
            echo '<li id="mPagos" class="treeview">
              <a href="metodo_pago.php">
                <i class="fa fa-credit-card"></i>
                <span>Métodos de pago</span>
              </a>
            </li>';
          }
          ?>

          <?php
          if ($_SESSION['compras'] == 1) {
            echo '<li id="mCompras" class="treeview">
              <a href="#">
                <i class="fa fa-th"></i>
                <span>Compras</span>
                 <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="lIngresos"><a href="ingreso.php"><i class="fa fa-circle-o"></i> Ingresos</a></li>
                <li id="lProveedores"><a href="proveedor.php"><i class="fa fa-circle-o"></i> Proveedores</a></li>
              </ul>
            </li>';
          }
          ?>

          <?php
          if ($_SESSION['ventas'] == 1) {
            echo '<li id="mVentas" class="treeview">
              <a href="#">
                <i class="fa fa-shopping-cart"></i>
                <span>Ventas</span>
                 <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="lVentas"><a href="venta.php"><i class="fa fa-circle-o"></i> Ventas al contado</a></li>
                <li id="lVentasServicio"><a href="ventaServicio.php"><i class="fa fa-circle-o"></i> Ventas de servicio</a></li>
                <li id="lClientes"><a href="cliente.php"><i class="fa fa-circle-o"></i> Clientes</a></li>
              </ul>
            </li>';
          }
          ?>

          <?php
          if ($_SESSION['cuotas'] == 1) {
            echo '<li id="mCuotas" class="treeview">
              <a href="#">
                <i class="fa fa-hourglass"></i>
                <span>Ventas a crédito</span>
                 <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="lCuotas"><a href="cuotas.php"><i class="fa fa-circle-o"></i> Pagos por crédito</a></li>
                <li id="lZonas"><a href="zonas.php"><i class="fa fa-circle-o"></i> Zona por ubicación</a></li>
              </ul>
            </li>';
          }
          ?>

          <?php
          if ($_SESSION['proforma'] == 1) {
            echo '<li id="mProformas" class="treeview">
              <a href="proformas.php">
                <i class="fa fa-file-text-o"></i> <span>Proformas</span>
              </a>
            </li>';
          }
          ?>

          <?php
          if ($_SESSION['acceso'] == 1) {
            echo '<li id="mAcceso" class="treeview">
              <a href="#">
                <i class="fa fa-folder"></i> <span>Acceso</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="lUsuarios"><a href="usuario.php"><i class="fa fa-circle-o"></i> Usuarios</a></li>
                <li id="lPermisos"><a href="permiso.php"><i class="fa fa-circle-o"></i> Permisos</a></li>
                
              </ul>
            </li>';
          }
          ?>

          <?php
          if ($_SESSION['perfilu'] == 1) {
            echo '
          <li id="mPerfilUsuario" class="treeview">
            <a href="#">
              <i class="fa fa-user"></i> <span>Perfil de usuario</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li id="lConfUsuario"><a href="confUsuario.php"><i class="fa fa-circle-o"></i> Configuración de perfil</a></li>
              ';
            if ($_SESSION['cargo'] == "superadmin" || $_SESSION['cargo'] == "admin") {
              echo '
                <li id="lConfPortada"><a href="confPortada.php"><i class="fa fa-circle-o"></i> Configuración de portada</a></li>
                <li id="lConfBoleta"><a href="confBoleta.php"><i class="fa fa-circle-o"></i> Configuración de boletas</a></li>
                <li id="lLocalesExternos"><a href="localesExternos.php"><i class="fa fa-circle-o"></i> Locales externos</a></li>
                <li id="lLocalesDisponibles"><a href="localesDisponibles.php"><i class="fa fa-circle-o"></i> Crear locales disponibles</a></li>
              ';
            }
            echo '
            </ul>
          </li>';
          }
          ?>

          <?php
          if ($_SESSION['reporte'] == 1) {
            echo '<li id="mReporte" class="treeview">
              <a href="#">
                <i class="fa fa-bar-chart"></i> <span>Reportes</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="lConsulasC"><a href="comprasfecha.php"><i class="fa fa-circle-o"></i> Reporte Compras</a></li>
                <li id="lConsulasV"><a href="ventasfechacliente.php"><i class="fa fa-circle-o"></i> Reporte Ventas por Cliente</a></li>  
                <li id="lConsultaU"><a href="ventasfechausuario.php"><i class="fa fa-circle-o"></i> Reporte Ventas por Usuario</a></li>
                <li id="lConsultaVP"><a href="ventasporproducto.php"><i class="fa fa-circle-o"></i> Reporte Ventas por Producto</a></li>
              </ul>
            </li>';
          }
          ?>

          <?php
          if ($_SESSION['reporteP'] == 1) {
            echo '<li id="mReporteP" class="treeview">
              <a href="#">
                <i class="fa fa-bar-chart"></i> <span>Reportes de Productos</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="lReportePV"><a href="ventasyproductos.php"><i class="fa fa-circle-o"></i> Reporte Ventas y Productos</a></li>  
                <li id="lConsultaP"><a href="productosmasvendido.php"><i class="fa fa-circle-o"></i> Productos más Vendidos</a></li>
                <li id="lGraficoVP"><a href="graficoconsultasvp.php"><i class="fa fa-circle-o"></i> Gráfico Ventas y Productos</a></li>
                <!-- <li id="lConsultaD"><a href="productosmasdevuelto.php"><i class="fa fa-circle-o"></i> Productos más Devueltos</a></li> -->
              </ul>
            </li>';
          }
          ?>

          <?php
          if ($_SESSION['solicitud'] == 1) {
            echo '<li id="mSolicitud" class="treeview">
              <a href="solicitudes.php">
                <i class="fa fa-paper-plane"></i> <span>Solicitudes</span>
              </a>
            </li>';
          }
          ?>

          <?php
          if ($_SESSION['devolucion'] == 1) {
            echo '<li id="mDevolucion" class="treeview">
              <a href="devoluciones.php">
                <i class="fa fa-truck"></i> <span>Devoluciones</span>
              </a>
            </li>';
          }
          ?>

          <li>
            <a href="ayuda.php">
              <i class="fa fa-plus-square"></i> <span>Ayuda</span>
              <small class="label pull-right bg-red">PDF</small>
            </a>
          </li>
          <!-- <li id="sql_export">
            <a>
              <?php
              // if ($_POST) {
              //   if ($_POST["backup"]) {
              //     require("backup/backup.php");
              //     $backupdb = new backupdb();
              //   }
              // }
              ?>
              <form method="post" style="margin: 0 !important;">
                <input type="hidden" value="true" name="backup">
                <i class="fa fa-file"></i>
                <input id="sql" type="submit" value="Exportar DB." style="border: none; background-color: transparent; outline: none;">
              </form>
              <small class="label pull-right bg-green">SQL</small>
            </a>
          </li> -->

          <div style="display: none;" id="rolUsuario"><?php echo $_SESSION['cargo'] ?></div>

        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>

    <script>
      // si no queremos que se actualice cada cierto tiempo, se tendrá que comentar todo este script.
      // var timeout_time = 3200; // 1 hora => 3200 seg || media hora => 1800 seg
      // var time_remaining = 0;
      // var rolusuario = document.getElementById("rolUsuario").innerHTML;

      // console.log(rolusuario);

      // if (sessionStorage.getItem('timeout_time') == null) {
      //   run_timeout(timeout_time);
      // } else {
      //   run_timeout(sessionStorage.getItem('timeout_time'))
      // }

      // if (rolusuario == "administrador") {
      //   setInterval(function() {
      //     time_remaining = sessionStorage.getItem('timeout_time');
      //     if (time_remaining > 1 || time_remaining != null) {
      //       sessionStorage.setItem('timeout_time', time_remaining - 1);
      //     }
      //   }, 1000);
      //   document.getElementById("sql_export").style.display = "block";
      // } else {
      //   document.getElementById("sql_export").style.display = "none";
      // }

      // function run_timeout(time) {
      //   setTimeout(function() {
      //     document.getElementById("sql").click();
      //     sessionStorage.removeItem('timeout_time');
      //   }, time * 1000);
      //   sessionStorage.setItem('timeout_time', time);
      //   sessionStorage.setItem('rol_usuario', rolusuario);
      // }
      // comentar hasta aquí.

      function destruirSession() {
        sessionStorage.clear();
      }
    </script>