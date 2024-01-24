<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';

  if ($_SESSION['reporte'] == 1) {
    require_once "../modelos/Consultas.php";
    $consulta = new Consultas();

    /* ============= cabecera ============= */

    //Mostramos el producto más vendido junto su cantidad.
    $articuloVendido = $consulta->cantidadDelArticuloMasVendido();
    $articulo = "";
    $cantidad = "";

    while ($regArticulo = $articuloVendido->fetch_object()) {
      $articulo = $articulo . '' . $regArticulo->nombre;
      $cantidad = $cantidad . '' . $regArticulo->cantidad;
    }

    //Mostramos la cantidad de ventas que existen.
    $rsptaCantVent = $consulta->cantidadDeTotalDeVentas();
    $cantTotalVent = $rsptaCantVent->num_rows;

    /* ============= Gráficos ============= */

    //Datos para mostrar el gráfico de barras de las ventas por usuario
    $articulos = $consulta->articuoMasVendidoGrafico();
    $articulos = mysqli_fetch_all($articulos, MYSQLI_ASSOC);

    $nombreArr = '';
    $cantidadArr = '';

    while ($regArtVent = array_pop($articulos)) {
      $nombreArr = $nombreArr . '"' . $regArtVent['nombre'] . '",';
      $cantidadArr = $cantidadArr . $regArtVent['cantidad'] . ',';
    }


    //Quitamos la última coma
    $nombreArr = substr($nombreArr, 0, -1);
    $cantidadArr = substr($cantidadArr, 0, -1);

    //Datos para mostrar el gráfico de barras de las ventas (3 meses)
    $ventas12 = $consulta->ventasultimos_3meses();
    $fechasv = '';
    $nombrev = '';
    $totalesv = '';
    while ($regfechav = $ventas12->fetch_object()) {
      $fechasv = $fechasv . '"' . $regfechav->fecha . '",';
      $nombrev = $nombrev . '"' . $regfechav->nombre . '",';
      $totalesv = $totalesv . $regfechav->total . ',';
    }

    //Quitamos la última coma
    $fechasv = substr($fechasv, 0, -1);
    $totalesv = substr($totalesv, 0, -1);

    //Datos para mostrar el gráfico de barras de las ventas (1 mes)
    $ventas13 = $consulta->ventasultimos_1mes();
    $fechasv2 = '';
    $nombrev2 = '';
    $totalesv2 = '';
    while ($regfechav2 = $ventas13->fetch_object()) {
      $fechasv2 = $fechasv2 . '"' . $regfechav2->fecha . '",';
      $nombrev2 = $nombrev2 . '"' . $regfechav2->nombre . '",';
      $totalesv2 = $totalesv2 . $regfechav2->total . ',';
    }

    //Quitamos la última coma
    $fechasv2 = substr($fechasv2, 0, -1);
    $totalesv2 = substr($totalesv2, 0, -1);
?>
    <!--Contenido-->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                <h1 class="box-title">Gráficos de estadísticas</h1>
                <div class="box-tools pull-right">
                </div>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="small-box bg-blue">
                    <div class="inner">
                      <h4 style="font-size:17px;">
                        <p>Nombre del artículo más vendido: <strong><?php echo $articulo; ?></strong></p>
                      </h4>
                      <h4 style="font-size:17px;">
                        <p>Veces que se vendió el artículo: <strong><?php echo $cantidad; ?></strong></p>
                      </h4>
                    </div>
                    <div class="icon">
                      <i class="ion ion-bag"></i>
                    </div>
                    <a href="productosmasvendido.php" class="small-box-footer">Productos más vendidos <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
              </div>
              <div class="panel-body mx-auto">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      Estadística de los productos más vendidos
                    </div>
                    <div class="box-body mx-auto">
                      <canvas id="articulos" width="100" height="30"></canvas>
                    </div>
                  </div>
                </div>
                <div class="panel-body">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="small-box bg-green">
                      <div class="inner">
                        <h4 style="font-size:17px;">
                          <p>Cantidad de ventas: <strong><?php echo $cantTotalVent; ?></strong></p>
                        </h4>
                        <h4 style="font-size:17px;">
                          <p>&nbsp;</p>
                        </h4>
                      </div>
                      <div class="icon">
                        <i class="ion ion-bag"></i>
                      </div>
                      <a href="ventasfechausuario.php" class="small-box-footer">Consulta Venta por Usuario <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                </div>
                <div class="panel-body">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="box box-primary">
                      <div class="box-header with-border">
                        Ventas por usuario en el último mes
                      </div>
                      <div class="box-body">
                        <canvas id="ventas1" width="100" height="30"></canvas>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="box box-primary">
                      <div class="box-header with-border">
                        Ventas por usuario en los últimos 3 meses
                      </div>
                      <div class="box-body">
                        <canvas id="ventas2" width="100" height="30"></canvas>
                      </div>
                    </div>
                  </div>
                </div>
                <!--Fin centro -->
              </div>
            </div>
          </div>
      </section>

    </div>
    <div style="display: none;" id="cantidadArr"><?php echo $cantidadArr; ?></div>
    <div style="display: none;" id="totalesv2"><?php echo $totalesv2; ?></div>
    <div style="display: none;" id="totalesv"><?php echo $totalesv; ?></div>
  <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/graficoconsultasvp.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
  <script type="text/javascript">
    Chart.register(ChartDataLabels);

    let data = document.getElementById("cantidadArr").textContent;
    let dataArr = data.split(',').map(Number);
    let max = Math.max(...dataArr) + 1;

    let data2 = document.getElementById("totalesv2").textContent;
    let dataArr2 = data2.split(',').map(Number);
    let max2 = Math.max(...dataArr2) + 1;

    let data3 = document.getElementById("totalesv").textContent;
    let dataArr3 = data3.split(',').map(Number);
    let max3 = Math.max(...dataArr3) + 1;

    console.log(max, max2, max3);

    var ctx = document.getElementById("articulos").getContext('2d');
    var ventas = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: [<?php echo $nombreArr; ?>],
        datasets: [{
          barPercentage: 0.2,
          label: 'Artículos más vendidos',
          data: [<?php echo $cantidadArr; ?>],
          backgroundColor: [
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
          ],
          borderColor: [
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
          ],
          borderWidth: 1,
          borderRadius: {
            topLeft: 10,
            topRight: 10
          }
        }]
      },
      options: {
        scales: {
          y: {
            suggestedMax: max
          }
        },
        plugins: {
          datalabels: { //esta es la configuración de pluggin datalabels
            anchor: 'end',
            align: 'top',
            formatter: Math.round,
            font: {
              weight: 'bold'
            }
          }
        }
      }
    });

    var ctx = document.getElementById("ventas1").getContext('2d');
    var ventas = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: [<?php echo $fechasv2; ?>],
        datasets: [{
          barPercentage: 0.2,
          label: 'Ventas en S/ del último mes',
          data: [<?php echo $totalesv2; ?>],
          backgroundColor: [
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
          ],
          borderColor: [
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
          ],
          borderWidth: 1,
          borderRadius: {
            topLeft: 10,
            topRight: 10
          }
        }]
      },
      options: {
        scales: {
          y: {
            suggestedMax: max2
          }
        },
        plugins: {
          datalabels: { //esta es la configuración de pluggin datalabels
            anchor: 'end',
            align: 'top',
            formatter: Math.round,
            font: {
              weight: 'bold'
            }
          }
        }
      }
    });

    var ctx = document.getElementById("ventas2").getContext('2d');
    var ventas = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: [<?php echo $fechasv; ?>],
        datasets: [{
          barPercentage: 0.2,
          label: 'Ventas en S/ de los últimos 3 meses',
          data: [<?php echo $totalesv; ?>],
          backgroundColor: [
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
          ],
          borderColor: [
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
            'rgba(0,166,149,255)', // Verde
          ],
          borderWidth: 1,
          borderRadius: {
            topLeft: 10,
            topRight: 10
          },
        }]
      },
      options: {
        scales: {
          y: {
            suggestedMax: max3
          }
        },
        plugins: {
          datalabels: { //esta es la configuración de pluggin datalabels
            anchor: 'end',
            align: 'top',
            formatter: Math.round,
            font: {
              weight: 'bold'
            }
          }
        }
      }
    });
  </script>


  </script>


<?php
}
ob_end_flush();
?>