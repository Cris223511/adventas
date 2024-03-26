    <footer class="main-footer">
      <div class="pull-right hidden-xs">
        <b>Version</b> 3.0.0
      </div>
      <strong>Copyright &copy; 2024 <a href="#">Sistema De Inventario</a>.</strong> Todos los derechos reservados.
    </footer>
    <!-- jQuery -->
    <script src="../public/js/jquery-3.1.1.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="../public/js/bootstrap.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../public/js/app.min.js"></script>
    <!-- Quagga JS -->
    <script src="../public/js/quagga.min.js"></script>
    <!-- Lightbox JS -->
    <script src="../public/glightbox/js/glightbox.min.js"></script>

    <!-- DATATABLES -->
    <script src="../public/datatables/jquery.dataTables.min.js"></script>
    <script src="../public/datatables/dataTables.buttons.min.js"></script>
    <script src="../public/datatables/buttons.html5.min.js"></script>
    <script src="../public/datatables/buttons.colVis.min.js"></script>
    <script src="../public/datatables/jszip.min.js"></script>
    <script src="../public/datatables/pdfmake.min.js"></script>
    <script src="../public/datatables/vfs_fonts.js"></script>

    <script src="../public/js/bootbox.min.js"></script>
    <script src="../public/js/bootstrap-select.min.js"></script>

    <script>
      function inicializeGLightbox() {
        const glightbox = GLightbox({
          selector: '.glightbox'
        });

        const galelryLightbox = GLightbox({
          selector: ".galleria-lightbox",
        });
      }
    </script>
    <script>
      function changeValue(dropdown) {
        var option = dropdown.options[dropdown.selectedIndex].value;

        console.log(option);

        $("#num_documento").val("");

        if (option == 'DNI') {
          setMaxLength('#num_documento', 8);
          setMaxLength('#num_documento2', 8);
        } else if (option == 'CEDULA') {
          setMaxLength('#num_documento', 10);
          setMaxLength('#num_documento2', 10);
        } else if (option == 'CARNET DE EXTRANJERIA') {
          setMaxLength('#num_documento', 20);
          setMaxLength('#num_documento2', 20);
        } else {
          setMaxLength('#num_documento', 11);
          setMaxLength('#num_documento2', 11);
        }
      }

      function setMaxLength(fieldSelector, maxLength) {
        $(fieldSelector).attr('maxLength', maxLength);
      }

      function mostrarClave() {
        console.log("di click =)");
        var claveInput = $('#clave');
        var ojitoIcon = $('#mostrarClave i');

        if (claveInput.attr('type') === 'password') {
          claveInput.attr('type', 'text');
          ojitoIcon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
          claveInput.attr('type', 'password');
          ojitoIcon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
      }
    </script>

    <script>
      $('.selectpicker').selectpicker({
        noneResultsText: 'No se encontraron resultados.'
      });
    </script>

    <script>
      // Evento click en el documento
      $(document).on('click', function(e) {
        // Comprobar si el clic fue fuera del popover
        if ($(e.target).closest('[data-toggle="popover"]').length === 0) {
          // Cerrar el popover
          $('[data-toggle="popover"]').popover('hide');
        }
      });

      // Evitar que el popover se cierre al hacer clic dentro de él
      $(document).on('click', '.popover', function(e) {
        e.stopPropagation();
      });

      function evitarNumerosNegativos(e) {
        if (e.key === "-")
          e.preventDefault();
      }

      function nowrapCell() {
        ["#tbllistado", "#detalles", "#tbllistado2", "#tbllistado3", "#tblarticulos", "#tbltrabajadores"].forEach(selector => {
          addClassToCells(selector, "nowrap-cell");
        });
      }

      function addClassToCells(selector, className) {
        var table = document.querySelector(selector);

        if (!table) return;

        var columnIndex = Array.from(table.querySelectorAll("th")).findIndex(th => th.innerText.trim() === "DESCRIPCIÓN DEL LOCAL" || th.innerText.trim() === "DESCRIPCIÓN");

        table.querySelectorAll("td, th").forEach(function(cell, index) {
          if (index % table.rows[0].cells.length !== columnIndex) {
            cell.classList.add(className);
          }
        });
      }

      $(document).on('draw.dt', function(e, settings) {
        if ($(settings.nTable).is('#tbllistado') || $(settings.nTable).is('#tbllistado2') || $(settings.nTable).is('#tbllistado3') || $(settings.nTable).is('#tblarticulos') || $(settings.nTable).is('#tbltrabajadores')) {
          const table = $(settings.nTable).DataTable();
          if (table.rows({
              page: 'current'
            }).count() > 0) {
            inicializeGLightbox();
            nowrapCell();
          }
        }
      });

      $(document).ajaxSuccess(function(event, xhr, settings) {
        if (settings.url.includes("op=listar") || settings.url.includes("op=listarDetalle") || settings.url.includes("op=listarDetalleproductoventa") || settings.url.includes("op=listarArticulosVenta")) {
          nowrapCell();
        }
      });
    </script>

    <script>
      const thElements = document.querySelectorAll("#tblarticulos th, #tbllistado th, #tbllistado2 th, #tbllistado3 th, #tbltrabajadores th");

      thElements.forEach((e) => {
        e.textContent = e.textContent.toUpperCase();
      });

      const boxTitle = document.querySelectorAll(".box-title, .infotitulo");

      boxTitle.forEach((e) => {
        e.childNodes.forEach((node) => {
          if (node.nodeType === Node.TEXT_NODE) {
            node.textContent = node.textContent.toUpperCase();
          }
        });
      });
    </script>

    <script>
      $('.selectpicker').selectpicker({
        noneResultsText: 'No se encontraron resultados.'
      });
    </script>

    <script>
      function convertirMayus(inputElement) {
        if (typeof inputElement.value === 'string') {
          inputElement.value = inputElement.value.toUpperCase();
        }
      }

      function onlyNumbersAndMaxLenght(input) {
        let newValue = "";

        if (input.value.length > input.maxLength)
          newValue = input.value.slice(0, input.maxLength);

        newValue = input.value.replace(/\D/g, '');
        input.value = newValue;
      }

      function onlyNumbers(input) {
        let newValue = "";
        newValue = input.value.replace(/\D/g, '');
        input.value = newValue;
      }

      function evitarNegativo(e) {
        if (e.key === "-")
          e.preventDefault();
      }

      function validarNumeroDecimal(input, maxLength) {
        input.value = input.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');

        if (input.value.length > maxLength) {
          input.value = input.value.slice(0, maxLength);
        }
      }
    </script>

    <script>
      function evitarCaracteresEspecialesCamposNumericos() {
        var camposNumericos = document.querySelectorAll('input[type="number"]:not(#ganancia)');

        camposNumericos.forEach(function(campo) {
          campo.addEventListener('keydown', function(event) {
            var teclasPermitidas = [46, 8, 9, 27, 13, 110, 190, 37, 38, 39, 40, 17, 82]; // ., delete, tab, escape, enter, flechas, Ctrl+R

            // Permitir Ctrl+C, Ctrl+V, Ctrl+X y Ctrl+A
            if ((event.ctrlKey || event.metaKey) && (event.which === 67 || event.which === 86 || event.which === 88 || event.which === 65)) {
              return;
            }

            // Permitir Ctrl+Z y Ctrl+Alt+Z
            if ((event.ctrlKey || event.metaKey) && event.which === 90) {
              if (!event.altKey) {
                // Permitir Ctrl+Z
                return;
              } else if (event.altKey) {
                // Permitir Ctrl+Alt+Z
                return;
              }
            }

            if (teclasPermitidas.includes(event.which) || (event.which >= 48 && event.which <= 57) || (event.which >= 96 && event.which <= 105) || event.which === 190 || event.which === 110) {
              // Si es una tecla permitida o numérica, no hacer nada
              return;
            } else {
              event.preventDefault(); // Prevenir cualquier otra tecla no permitida
            }
          });
        });
      }

      evitarCaracteresEspecialesCamposNumericos();
    </script>

    <script>
      function restrict(input) {
        var prev = input.getAttribute("data-prev");
        prev = (prev != '') ? prev : '';
        if (Math.round(input.value * 100) / 100 != input.value) {
          input.value = prev;
        }
        input.setAttribute("data-prev", input.value);
      }

      function aplicarRestrictATodosLosInputs() {
        var camposNumericos = document.querySelectorAll('input[type="number"]');
        camposNumericos.forEach(function(campo) {
          campo.addEventListener('input', function(event) {
            restrict(event.target);
          });
        });
      }

      aplicarRestrictATodosLosInputs();
    </script>

    <script>
      function generarSiguienteCorrelativo(numero) {
        console.log("Número recibido por el servidor: ", numero);

        let numFormat = numero.trim();
        let num = isNaN(parseInt(numFormat, 10)) ? 0 : parseInt(numFormat, 10);

        console.log("Número a incrementar: ", num);
        num++;

        let siguienteCorrelativo = num < 10000 ? num.toString().padStart(4, '0') : num.toString();

        console.log("Número incrementado a setear: ", siguienteCorrelativo);
        return siguienteCorrelativo;
      }

      function limpiarCadena(cadena) {
        console.log("cadena a limpiar =) =>", cadena);
        let cadenaLimpia = cadena.trim();
        cadenaLimpia = cadenaLimpia.replace(/^[\n\r]+/, '');
        console.log("cadena limpia =) =>", cadenaLimpia);
        return cadenaLimpia;
      }

      function formatearNumero() {
        var campos = ["#num_comprobante", "#num_proforma", "#codigo_pedido"];

        campos.forEach(function(campo) {
          let numValor = $(campo).val();
          if (typeof numValor !== 'undefined') {
            numValor = numValor.trim();
            let num = parseInt(numValor, 10);
            let numFormateado = num < 10000 ? num.toString().padStart(4, '0') : num.toString();
            $(campo).val(numFormateado);
          }
        });
      }
    </script>

    <?php
    if ($_SESSION["cargo"] == "almacenero") {
      echo '<script>
            $(document).ajaxSuccess(function(event, xhr, settings) {
                if (!$("#mAlmacen").hasClass("active") &&
                    !$("#mCompras").hasClass("active")) {
                    $(".dt-buttons").hide();
                }
            });
          </script>';
    } elseif ($_SESSION["cargo"] == "vendedor") {
      echo '<script>
              $(document).ajaxSuccess(function(event, xhr, settings) {
                  if (!$("#mPagos").hasClass("active") &&
                      !$("#lMarcas").hasClass("active") &&
                      !$("#lMedidas").hasClass("active") &&
                      !$("#lCategorias").hasClass("active") &&
                      !$("#lProveedores").hasClass("active") &&
                      !$("#lVentas").hasClass("active") &&
                      !$("#lVentasServicio").hasClass("active") &&
                      !$("#lCuotas").hasClass("active") &&
                      !$("#lZonas").hasClass("active") &&
                      !$("#lConsulasV").hasClass("active") &&
                      !$("#lConsultaU").hasClass("active") &&
                      !$("#lConsultaVP").hasClass("active") &&
                      !$("#lReportePV").hasClass("active")
                  ) {
                      $(".dt-buttons").hide();
                  }
              });
            </script>';
    } elseif ($_SESSION["cargo"] != "superadmin" && $_SESSION["cargo"] != "admin" && $_SESSION["cargo"] != "encargado") {
      echo '<script>
              $(document).ajaxSuccess(function(event, xhr, settings) {
                $(".dt-buttons").hide();
              });
            </script>';
    }
    ?>

    <script>
      function bloquearPrecioCompraVenta() {
        <?php
        if ($_SESSION["cargo"] == "vendedor" || $_SESSION["cargo"] == "almacenero") {
          echo '
            $("#precio_compra").prop("disabled", true);
            $("#precio_venta").prop("disabled", true);
          ';
        }
        ?>
      }

      function desbloquearPrecioCompraVenta() {
        <?php
        if ($_SESSION["cargo"] == "vendedor" || $_SESSION["cargo"] == "almacenero") {
          echo '
            $("#precio_compra").prop("disabled", false);
            $("#precio_venta").prop("disabled", false);
          ';
        }
        ?>
      }

      function bloquearPrecios() {
        <?php
        if ($_SESSION["cargo"] == "vendedor" || $_SESSION["cargo"] == "almacenero") {
          echo '
            $(".precios").prop("disabled", true);
          ';
        }
        ?>
      }

      function desbloquearPrecios() {
        <?php
        if ($_SESSION["cargo"] == "vendedor" || $_SESSION["cargo"] == "almacenero") {
          echo '
            $(".precios").prop("disabled", false);
          ';
        }
        ?>
      }

      function ocultarPrecioCompra() {
        <?php
        if ($_SESSION["cargo"] == "vendedor" || $_SESSION["cargo"] == "almacenero") {
          echo '
              $(".precio_compra").css("display", "none");
              $("#detalles th:contains(\'Precio compra\')").css("display", "none");

              $("#tblarticulos th:contains(\'PRECIO DE COMPRA\')").css("display", "none");
              var columnIndexPrecioCompra = $("#tblarticulos th:contains(\'PRECIO DE COMPRA\')").index() + 1;
              $("#tblarticulos td:nth-child(" + columnIndexPrecioCompra + ")").css("display", "none");

              $("#tblarticulos th:contains(\'GANANCIA\')").css("display", "none");
              var columnIndexGanancia = $("#tblarticulos th:contains(\'GANANCIA\')").index() + 1;
              $("#tblarticulos td:nth-child(" + columnIndexGanancia + ")").css("display", "none");
          ';
        }
        ?>
      }
    </script>

    <script>
      $(document).on('show.bs.modal', function(event) {
        const modal = $(event.target);

        if (modal.hasClass('bootbox') && modal.hasClass('bootbox-confirm')) {
          modal.find('.modal-footer .btn-default').text('Cancelar');
          modal.find('.modal-footer .btn-primary').text('Aceptar');
        }
      });
    </script>
    </body>

    </html>