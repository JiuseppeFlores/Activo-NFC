let selecciones = [];
let devoluciones = [];
function listar_asignacion(pag) {
  selecciones = [];
  $("#buscador-general").show().animate({ opacity: "1" }, 1000);

  var start = (pag - 1) * 10;
  var texto = $("#busqueda_asignacion").val();
  var area = $("#area_filter").val();
  var parametros = {
    start: start,
    texto: texto,
    area: area,
  };

  var result1 = "";
  $.ajax({
    data: parametros,
    url: "../asignacion/listaasignacion.php",
    type: "post",
    success: function (response) {
      result1 = response;

      jQuery.ajax({
        type: "POST",
        url: "../asignacion/generar_paginacion.php",
        data: parametros,
        dataType: "JSON",
        success: function (data) {
          $("#asignacion-result").html(response + data.tabla);
          $(".pagination").pagination({
            items: data.records,
            itemsOnPage: 10,
            cssStyle: "light-theme",
            currentPage: pag,
          });

          $("#pagina").val(pag);
        },
        beforeSend: function () {
          $("#asignacion-result").show();
          $("#asignacion-result").html(`<div style="text-align:center">
                                                                    <div class="d-flex justify-content-center">
                                                                        <div class="spinner-border" role="status">
                                                                                <span class="visually-hidden"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>`);
        },
      });
    },
    beforeSend: function () {
      $("#asignacion-result").show();
      $("#asignacion-result").html(`<div style="text-align:center">
                                                                <div class="d-flex justify-content-center">
                                                                    <div class="spinner-border" role="status">
                                                                            <span class="visually-hidden"></span>
                                                                    </div>
                                                                </div>
                                                            </div>`);
    },
  });
}

function asignacion() {
  remove();
  document.getElementById("nav_asignacion").className += " active";
  document.getElementById("carpeta-activa").value = "asignacion";
  $("#shadow").fadeIn("normal");
  $("#spinner").html(`<div class="container">
                                                    <div class="loader-container">
                                                    <div></div>
                                                    <div></div>
                                                    <div></div>
                                                    <div></div>
                                                    <div></div>
                                                    </div>
                                                </div>`);
  $.ajax({
    url: "../asignacion/asignacion.php",
    type: "post",
    success: function (response) {
      $("#shadow").fadeOut();
      $("#spinner").html(``);
      $("#all-body").html(response);
      listar_asignacion(1);
    },
  });
}

function add_asignacion() {
  $("#buscador-general").hide().animate({ opacity: "0" }, 0);
  $("#asignacion-result").hide().animate({ opacity: "0", bottom: "-80px" }, 0);
  $.ajax({
    url: "../asignacion/add.php",
    type: "post",
    success: function (response) {
      $("#asignacion-result")
        .show()
        .animate({ opacity: "1", bottom: "-80px" }, 1000);
      $("#asignacion-result").html(response);

      let btnGuardar = document.getElementById("btnGuardar");
      btnGuardar.addEventListener("click", function (event) {
        // vidaUtilRestante = vidaRestante();
        // if (vidaUtilRestante == 0) {
        //   Swal.fire({
        //     title: 'Advertencia',
        //     text: 'La vida útil restante del producto es de 0 años, ¿desea continuar?',
        //     icon: 'warning',
        //     confirmButtonText: 'Continuar',
        //     showCancelButton: true,
        //     cancelButtonText: 'Cancelar'
        //   }).then((result) => {
        //     if (result.isConfirmed) {
        //       event.preventDefault();
        //       send_data("asignacion", "Guardado", "add_insert", "add_asignacion");
        //     } else if (result.isDismissed) {
        //       event.preventDefault();
        //     }
        //   });
        // } else {
        //   event.preventDefault();
        //   send_data("asignacion", "Guardado", "add_insert", "add_asignacion");
        // }
        if (selecciones.length === 0) {
            Swal.fire({
                title: 'Advertencia',
                text: 'Por favor, seleccione al menos un bien',
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            });
            return;
        }
        event.preventDefault();
        send_data("asignacion", "Guardado", "add_insert", "add_asignacion", selecciones, "entrega");
        // generarActa2('entrega', selecciones);
      });
    },
    beforeSend: function () { },
  });
}

function edit_asignacion(id, estadoAsignacion = 'VIGENTE') {
  $("#buscador-general").hide().animate({ opacity: "0" }, 0);
  $("#asignacion-result").hide().animate({ opacity: "0", bottom: "-80px" }, 0);

  var parametros = {
    id: id,
    estadoAsignacion: estadoAsignacion
  };

  $.ajax({
    data: parametros,
    url: "../asignacion/edit.php",
    type: "post",
    success: function (response) {
      $("#asignacion-result")
        .show()
        .animate({ opacity: "1", bottom: "-80px" }, 1000);
      $("#asignacion-result").html(response);

      let btnActualizar = document.getElementById("btnActualizar");
      btnActualizar.addEventListener("click", function (event) {
        vidaUtilRestante = vidaRestante();
        if (vidaUtilRestante == 0) {
          Swal.fire({
            title: 'Advertencia',
            text: 'La vida útil restante del producto es de 0 años, ¿desea continuar?',
            icon: 'warning',
            confirmButtonText: 'Continuar',
            showCancelButton: true,
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.isConfirmed) {
              event.preventDefault();
              send_data("asignacion", "Actualizado", "edit_insert", "edit_asignacion");
            } else if (result.isDismissed) {
              event.preventDefault();
            }
          });
        } else {
          event.preventDefault();
          send_data("asignacion", "Actualizado", "edit_insert", "edit_asignacion");
        }
      });
    },
  });
}

$("#modal_eliminar_asignacion").on("show.bs.modal", function (e) {
  var id = $(e.relatedTarget).data().id;
  $("#id_asignacion").val(id);
});

function borrar_asignacion(id) {
  $("#shadow").fadeIn("normal");
  $("#spinner").html(`<div class="spinner-container">
                                                    <div class="spinner-path">
                                                    <div></div>
                                                    <div></div>
                                                    <div></div>
                                                    <div></div>
                                                    </div>
                                                </div>`);

  var parametros = {
    id: id,
  };

  pag = parseInt($("#pagina").val());
  if (pag == 0) {
    pag = 1;
  }

  $.ajax({
    data: parametros,
    url: "../asignacion/eliminar.php",
    type: "post",
    success: function (response) {
      $("#shadow").fadeOut();
      $("#spinner").html(``);
      if (response == 1) {
        $("#modal_eliminar_asignacion").modal("hide");
        alertify.success("Registro eliminado");
        listar_asignacion(pag);
      } else if (response == 2) {
        alertify.error("Error");
      }
    },
  });
}

function verProducto() {
  let idProducto = $("#selectProducto").val();
  if (idProducto) {
    if (idProducto == '-1') {
      return;
    }
    let url = "../images/producto/" + idProducto + ".png";
    let contenedorImagen = document.getElementById("contenedorImagen");
    contenedorImagen.innerHTML = `<img src="${url}" alt="Producto" class="img-fluid rounded">`;
  }
}

// Funcionalidad del modal de documentos
$(document).ready(function () {
  $('#modal_documento').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var idAsignacion = button.data('id');
    $('#modal_documento').data('id', idAsignacion);
  });
});

function vidaRestante() {
  vidaUtilRestante = $("#selectProducto").find('option:selected').data('vida');
  $("#vidaUtilRestante").val("Vida útil restante: " + vidaUtilRestante + " años");
  $("#vidaUtilRestante").addClass("text-center");
  if (vidaUtilRestante < 1) {
    $("#vidaUtilRestante").removeClass("bg-info");
    $("#vidaUtilRestante").addClass("bg-danger");
  } else {
    $("#vidaUtilRestante").removeClass("bg-danger");
    $("#vidaUtilRestante").addClass("bg-info");
  }
  return vidaUtilRestante;
}

function generarActa(tipo) {
  var idAsignacion = $('#modal_documento').data('id');
  if (!idAsignacion) {
    alertify.error('No se ha seleccionado una asignación');
    return;
  }
  var form = document.createElement('form');
  form.method = 'POST';
  form.target = '_blank';

  if (tipo === 'entrega') {
    form.action = '../reportes/actaEntregaPdf.php';
  } else {
    form.action = '../reportes/actaDevolucionPdf.php';
  }
  var input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'idAsignacion';
  input.value = idAsignacion;
  form.appendChild(input);
  document.body.appendChild(form);
  form.submit();
  document.body.removeChild(form);
  $('#modal_documento').modal('hide');
}

function generarActa2(tipo, seleccionados) {
  if (seleccionados.length === 0) {
    alertify.error('No se ha seleccionado ningún bien');
    return;
  }
  var form = document.createElement('form');
  form.method = 'POST';
  form.target = '_blank';

  if (tipo === 'entrega') {
    form.action = '../reportes/actaEntregaPdf.php';
  } else {
    form.action = '../reportes/actaDevolucionPdf.php';
  }
  idSeleccionados = JSON.stringify(seleccionados);
  var input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'idSeleccionados';
  input.value = idSeleccionados;
  form.appendChild(input);
  document.body.appendChild(form);
  form.submit();
  document.body.removeChild(form);
  // $('#modal_documento').modal('hide');
}
// ===================================
function agregarSeleccion() {
  const selectProducto = document.getElementById('selectProducto');
  const vidaUtilRestante = document.getElementById('vidaUtilRestante').value;
  const vidaUtilRestanteNum = vidaRestante();
  const productoSeleccionado = selectProducto.options[selectProducto.selectedIndex];
  const codigo = productoSeleccionado.dataset.codigo;
  
  if (selectProducto.value === '-1') {
      // alert('Por favor, seleccione un producto válido');
      Swal.fire({
        title: 'Advertencia',
        text: 'Por favor, seleccione un bien válido',
        icon: 'warning',
        confirmButtonText: 'Aceptar'
      });
      return;
  }

  // Verificar si el producto ya está en la lista
  if (selecciones.some(s => s.idProducto === selectProducto.value)) {
      // alertify.error('Este producto ya está en la lista de selecciones');
      Swal.fire({
        title: 'Advertencia',
        text: 'Este producto ya está en la lista de selecciones',
        icon: 'warning',
        confirmButtonText: 'Aceptar'
      });
      return;
  }

  if (vidaUtilRestanteNum == 0) {
    Swal.fire({
      title: 'Advertencia',
      text: 'La vida útil restante del producto es de 0 años, ¿desea continuar?',
      icon: 'warning',
      confirmButtonText: 'Continuar',
      showCancelButton: true,
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        // Agregar a la lista
        selecciones.push({
            idProducto: selectProducto.value,
            nombre: productoSeleccionado.text,
            codigo: codigo,
            vidaUtilRestante: vidaUtilRestante
        });
        actualizarTablaSeleccionados();
      } else if (result.isDismissed) {
        return;
      }
    });
    return;
  }

  // Agregar a la lista
  selecciones.push({
      idProducto: selectProducto.value,
      nombre: productoSeleccionado.text,
      codigo: codigo,
      vidaUtilRestante: vidaUtilRestante
  });

  actualizarTablaSeleccionados();
}

function actualizarTablaSeleccionados() {
  const cuerpoTabla = document.getElementById('cuerpoTablaSeleccionados');
  cuerpoTabla.innerHTML = '';
  let nro = 1;
  selecciones.forEach((seleccion, index) => {
    seleccion.vidaUtilRestante = seleccion.vidaUtilRestante.replace('Vida útil restante: ', '');
      const fila = document.createElement('tr');
      fila.innerHTML = `
          <td>${nro}</td>
          <td>${seleccion.nombre}</td>
          <td>${seleccion.vidaUtilRestante}</td>
          <td>
              <button type="button" class="btn btn-sm btn-danger" onclick="eliminarSeleccion(${index})">
                  <i class="fas fa-trash"></i>
              </button>
          </td>
      `;
      cuerpoTabla.appendChild(fila);
      nro++;
  });
}

function eliminarSeleccion(index) {
  selecciones.splice(index, 1);
  actualizarTablaSeleccionados();
}

function toggleAllCheckboxes(checkbox) {
  const selectAll = checkbox.checked;
  const checkboxes = document.querySelectorAll('.selectItem');
  
  devoluciones = [];
  if (!selectAll) {
    checkboxes.forEach(cb => {
      cb.checked = false;
    });
  } else {
    checkboxes.forEach(cb => {
        cb.checked = true;
        devoluciones.push({
          idAsignacion: cb.value,
        })
    });
  }
}

function updateSelectedCount() {
  const selectedCount = document.querySelectorAll('.selectItem:checked');
  devoluciones = [];
  selectedCount.forEach(cb => {
    devoluciones.push({
      idAsignacion: cb.value,
    })
  });
}

function devolucion() {
  if (devoluciones.length === 0) {
    Swal.fire({
      title: 'Advertencia',
      text: 'Por favor, seleccione al menos una asignación para realizar la devolución',
      icon: 'warning',
      confirmButtonText: 'Aceptar'
    });
    return;
  } else {
    $.ajax({
      url: '../asignacion/verificarUsuario.php',
      type: 'POST',
      data: { devoluciones: devoluciones },
      dataType: 'JSON',
      success: function(response) {
        if (response.cantidad > 1) {
          Swal.fire({
            title: 'Advertencia',
            text: 'No se puede realizar la devolución de asignaciones de diferentes usuarios',
            icon: 'warning',
            confirmButtonText: 'Aceptar'
          });
          return;
        }
      },
      error: function(xhr, status, error) {
        Swal.fire({
          title: 'Advertencia',
          text: 'Error al verificar el usuario',
          icon: 'warning',
          confirmButtonText: 'Aceptar'
        });
        return;
      }
    });
    Swal.fire({
      title: 'Advertencia',
      text: '¿Desea realizar la devolución de las asignaciones seleccionadas?',
      icon: 'warning',
      confirmButtonText: 'Aceptar',
      showCancelButton: true,
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        send_data("asignacion", "Devuelto", "devolucion", "devolucion", devoluciones, "devolucion");
      } else if (result.isDismissed) {
        return;
      }
    });
  }
}
