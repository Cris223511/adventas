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
        var field = $('#num_documento');

        $("#num_documento").val("");

        if (option == 'DNI') {
          field.attr('maxLength', 8);
        } else if (option == 'CEDULA') {
          field.attr('maxLength', 10);
        } else {
          field.attr('maxLength', 11);
        }
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
        ["#tbllistado", "#tbllistado2", "#tblarticulos", "#tbltrabajadores"].forEach(selector => {
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
        if ($(settings.nTable).is('#tbllistado') || $(settings.nTable).is('#tbllistado2') || $(settings.nTable).is('#tblarticulos') || $(settings.nTable).is('#tbltrabajadores')) {
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
      const thElements = document.querySelectorAll("#tblarticulos th, #tbllistado th, #tbllistado2 th, #tbltrabajadores th");

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
        var camposNumericos = document.querySelectorAll('input[type="number"]');
        camposNumericos.forEach(function(campo) {
          campo.addEventListener('keydown', function(event) {
            var teclasPermitidas = [46, 8, 9, 27, 13, 110, 190, 37, 38, 39, 40, 17, 82]; // ., delete, tab, escape, enter, flechas, Ctrl+R
            if ((event.ctrlKey || event.metaKey) && event.which === 65) return; // Permitir Ctrl+A o Command+A
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
                      !$("#lZonas").hasClass("active")
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

    </body>

    </html>