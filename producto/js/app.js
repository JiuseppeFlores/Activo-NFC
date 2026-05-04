function listar_producto(pag) {
  $("#buscador-general").show().animate({ opacity: "1" }, 1000);

  var start = (pag - 1) * 10;
  var texto = $("#busqueda_producto").val();
  var parametros = {
    start: start,
    texto: texto,
  };

  var result1 = "";
  $.ajax({
    data: parametros,
    url: "../producto/listaproducto.php",
    type: "post",
    success: function (response) {
      result1 = response;

      jQuery.ajax({
        type: "POST",
        url: "../producto/generar_paginacion.php",
        data: parametros,
        dataType: "JSON",
        success: function (data) {
          $("#producto-result").html(response + data.tabla);
          $(".pagination").pagination({
            items: data.records,
            itemsOnPage: 10,
            cssStyle: "light-theme",
            currentPage: pag,
          });

          $("#pagina").val(pag);
        },
        beforeSend: function () {
          $("#producto-result").show();
          $("#producto-result").html(`<div style="text-align:center">
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
      $("#producto-result").show();
      $("#producto-result").html(`<div style="text-align:center">
                                                                <div class="d-flex justify-content-center">
                                                                    <div class="spinner-border" role="status">
                                                                            <span class="visually-hidden"></span>
                                                                    </div>
                                                                </div>
                                                            </div>`);
    },
  });
}

function producto() {
  remove();
  document.getElementById("nav_producto").className += " active";
  document.getElementById("carpeta-activa").value = "producto";
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
    url: "../producto/producto.php",
    type: "post",
    success: function (response) {
      $("#shadow").fadeOut();
      $("#spinner").html(``);
      $("#all-body").html(response);
      listar_producto(1);
    },
  });
}

function add_producto() {
  $("#buscador-general").hide().animate({ opacity: "0" }, 0);
  $("#producto-result").hide().animate({ opacity: "0", bottom: "-80px" }, 0);
  $.ajax({
    url: "../producto/add.php",
    type: "post",
    success: function (response) {
      $("#producto-result")
        .show()
        .animate({ opacity: "1", bottom: "-80px" }, 1000);
      $("#producto-result").html(response);

      let form = document.getElementById("add_producto");
      form.addEventListener("submit", function (event) {
        event.preventDefault();
        send_data("producto", "Guardado", "add_insert", "add_producto");
      });
    },
    beforeSend: function () {},
  });
}

function edit_producto(id) {
  $("#buscador-general").hide().animate({ opacity: "0" }, 0);
  $("#producto-result").hide().animate({ opacity: "0", bottom: "-80px" }, 0);

  var parametros = {
    id: id,
  };

  $.ajax({
    data: parametros,
    url: "../producto/edit.php",
    type: "post",
    success: function (response) {
      $("#producto-result")
        .show()
        .animate({ opacity: "1", bottom: "-80px" }, 1000);
      $("#producto-result").html(response);

      let form = document.getElementById("edit_producto");
      form.addEventListener("submit", function (event) {
        event.preventDefault();
        send_data("producto", "Actualizado", "edit_insert", "edit_producto");
      });
    },
  });
}

$("#modal_eliminar_producto").on("show.bs.modal", function (e) {
  var id = $(e.relatedTarget).data().id;
  $("#id_producto").val(id);
});

function borrar_producto(id) {
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
    url: "../producto/eliminar.php",
    type: "post",
    success: function (response) {
      $("#shadow").fadeOut();
      $("#spinner").html(``);
      if (response == 1) {
        $("#modal_eliminar_producto").modal("hide");
        alertify.success("Registro eliminado");
        listar_producto(pag);
      } else if (response == 2) {
        alertify.error("Error");
      }
    },
  });
}

function getBien() {
  var idDepreciacion = $('#tipoProducto').val();
  var bien = $('#bien');
  bien.empty();
  $.ajax({
      url: '../producto/get_bien_depreciacion.php',
      type: 'POST',
      data: { idDepreciacion: idDepreciacion },
      success: function(data) {
          var bienes = JSON.parse(data);
          if (bienes.length > 0) {
            $.each(bienes, function(index, elemento) {
                bien.append('<option value="' + elemento.idDepreciacionDetalle + '">' + elemento.bienDetalle + '</option>');
            });
            countBien();
          } else {
            bien.append('<option value="">No hay bienes</option>');
            $('#codigoBarras').val('');
          }
      }
  });
}

function countBien() {
  let idDepreciacion = parseInt($('#tipoProducto').val());
  let idDepreciacionDetalle = parseInt($("#bien").val());
  if (idDepreciacionDetalle != '') {
      $.ajax({
        url: '../producto/count_bien.php',
        type: 'POST',
        data: { idDepreciacionDetalle: idDepreciacionDetalle },
        success: function(data) {
          let count = parseInt(data) + 1;
          if (parseInt(idDepreciacion/10) == 0) {
            idDepreciacion = '0' + idDepreciacion + '';
          }
          if (parseInt(idDepreciacionDetalle/10) == 0) {
            idDepreciacionDetalle = '0' + idDepreciacionDetalle + '';
          }
          if (count < 10) {
            count = '000' + count + '';
          } else {
            count = '00' + count + '';
          }
          $('#codigoBarras').val('STS-' + idDepreciacion + '-' + idDepreciacionDetalle + '-' + count);
        }
      })
  }
}

function generarReporteBien(idBien = 0) {
  var form = document.createElement('form');
  form.method = 'POST';
  form.target = '_blank';
  form.action = '../reportes/bienPdf.php';
  var input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'idBien';
  input.value = idBien;
  form.appendChild(input);
  document.body.appendChild(form);
  form.submit();
  document.body.removeChild(form);
}

function cambiarEstado(idBien, estado) {
  $.ajax({
    url: '../producto/verificarAsignacion.php',
    type: 'post',
    dataType: 'json',
    data: {
      idBien: idBien
    },
    success: function (respuesta) {
      if (respuesta.success == true) {
        Swal.fire({
          title: 'Advertencia',
          text: 'El bien se encuentra asignado a un usuario, no se puede cambiar su estado',
          icon: 'warning'
        })
        return;
      } else {
        Swal.fire({
          title: '¿Cambiar estado?',
          text: '¿Deseas cambiar el estado de este bien?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sí, cambiar estado',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: '../producto/cambiarEstado.php',
              type: 'post',
              dataType: 'json',
              data: {
                idBien: idBien,
                estado: estado
              },
              success: function (response) {
                if (response.success == true) {
                  Swal.fire({
                    title: 'Éxito',
                    text: 'Estado cambiado correctamente',
                    icon: 'success',
                  })
                  listar_producto(1);
                } else {
                  Swal.fire({
                    title: 'Ocurrió un problema',
                    text: 'Intente nuevamente o contacte a servicio técnico por favor',
                    icon: 'warning'
                  })
                }
              },
              error: function (xhr, status, error) {
                Swal.fire({
                  title: 'Ocurrió un problema',
                  text: 'Intente nuevamente o contacte a servicio técnico por favor',
                  icon: 'warning'
                })
                console.log('vemos el error');
                console.log(xhr);
                console.log(status);
                console.log(error);
              }
            })
          }
        });
      }
    }
  })
}