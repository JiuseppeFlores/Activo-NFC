function listar_usuario(pag) {
  $("#buscador-general").show().animate({ opacity: "1" }, 1000);

  var start = (pag - 1) * 10;
  var texto = $("#busqueda_usuario").val();
  var parametros = {
    start: start,
    texto: texto,
  };

  var result1 = "";
  $.ajax({
    data: parametros,
    url: "../usuario/listausuario.php",
    type: "post",
    success: function (response) {
      result1 = response;

      jQuery.ajax({
        type: "POST",
        url: "../usuario/generar_paginacion.php",
        data: parametros,
        dataType: "JSON",
        success: function (data) {
          $("#usuario-result").html(response + data.tabla);
          $(".pagination").pagination({
            items: data.records,
            itemsOnPage: 10,
            cssStyle: "light-theme",
            currentPage: pag,
          });

          $("#pagina").val(pag);
        },
        beforeSend: function () {
          $("#usuario-result").show();
          $("#usuario-result").html(`<div style="text-align:center">
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
      $("#usuario-result").show();
      $("#usuario-result").html(`<div style="text-align:center">
                                                                <div class="d-flex justify-content-center">
                                                                    <div class="spinner-border" role="status">
                                                                            <span class="visually-hidden"></span>
                                                                    </div>
                                                                </div>
                                                            </div>`);
    },
  });
}

function usuario() {
  remove();
  document.getElementById("nav_usuario").className += " active";
  document.getElementById("carpeta-activa").value = "usuario";
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
    url: "../usuario/usuario.php",
    type: "post",
    success: function (response) {
      $("#shadow").fadeOut();
      $("#spinner").html(``);
      $("#all-body").html(response);
      listar_usuario(1);
    },
  });
}

function add_usuario() {
  $("#buscador-general").hide().animate({ opacity: "0" }, 0);
  $("#usuario-result").hide().animate({ opacity: "0", bottom: "-80px" }, 0);
  $.ajax({
    url: "../usuario/add.php",
    type: "post",
    success: function (response) {
      $("#usuario-result")
        .show()
        .animate({ opacity: "1", bottom: "-80px" }, 1000);
      $("#usuario-result").html(response);

      let form = document.getElementById("add_usuario");
      form.addEventListener("submit", function (event) {
        event.preventDefault();
        send_data("usuario", "Guardado", "add_insert", "add_usuario");
      });
    },
    beforeSend: function () {},
  });
}

function edit_usuario(id) {
  $("#buscador-general").hide().animate({ opacity: "0" }, 0);
  $("#usuario-result").hide().animate({ opacity: "0", bottom: "-80px" }, 0);

  var parametros = {
    id: id,
  };

  $.ajax({
    data: parametros,
    url: "../usuario/edit.php",
    type: "post",
    success: function (response) {
      $("#usuario-result")
        .show()
        .animate({ opacity: "1", bottom: "-80px" }, 1000);
      $("#usuario-result").html(response);

      let form = document.getElementById("edit_usuario");
      form.addEventListener("submit", function (event) {
        event.preventDefault();
        send_data("usuario", "Actualizado", "edit_insert", "edit_usuario");
      });
    },
  });
}

$("#modal_eliminar_usuario").on("show.bs.modal", function (e) {
  var id = $(e.relatedTarget).data().id;
  $("#id_usuario").val(id);
});

function borrar_usuario(id) {
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
    url: "../usuario/eliminar.php",
    type: "post",
    success: function (response) {
      $("#shadow").fadeOut();
      $("#spinner").html(``);
      if (response == 1) {
        $("#modal_eliminar_usuario").modal("hide");
        alertify.success("Registro eliminado");
        listar_usuario(pag);
      } else if (response == 2) {
        alertify.error("Error");
      }
    },
  });
}

function verificarCi(id = 0) {
  var ci = document.getElementById("ci").value;
  // if (ci.length < 7) {
  //     alertify.error("Carnet de identidad no valido");
  //     document.getElementById("ci").value = "";
  // }
  $.ajax({
    data: { ci: ci.trim(), id: id },
    url: "../usuario/verificarCi.php",
    type: "post",
    success: function (response) {
      if (response == 2) {
        alertify.error("Carnet de identidad ya registrado");
        document.getElementById("ci").value = "";
      }
    },
  });
}

function verificarUsuario(id = 0) {
  console.log("estas en verificarUsuario");
  var usuario = document.getElementById("usuario").value;
  $.ajax({
    data: { usuario: usuario, id: id },
    url: "../usuario/verificarUsuario.php",
    type: "post",
    success: function (response) {
      if (response == 2) {
        alertify.error("El nombre de USUARIO ya se encuentra en uso.");
        document.getElementById("usuario").value = "";
      }
    },
  });
}

function reporteUsuarios(){
  let form = document.createElement("form");
  form.setAttribute("method", "post");
  form.setAttribute("action", "../reportes/usuariosPdf.php");
  form.setAttribute("target", "_blank");
  form.innerHTML = ``;
  document.body.appendChild(form);
  form.submit();
  form.remove();
}

function reporteProductos() {
  let form = document.createElement("form");
  form.setAttribute("method", "post");
  form.setAttribute("action", "../reportes/productosPdf.php");
  form.setAttribute("target", "_blank");
  form.innerHTML = ``;
  document.body.appendChild(form);
  form.submit();
  form.remove();
}

function reporteAsignaciones() {
  let form = document.createElement("form");
  form.setAttribute("method", "post");
  form.setAttribute("action", "../reportes/asignacionesPdf.php");
  form.setAttribute("target", "_blank");
  form.innerHTML = ``;
  document.body.appendChild(form);
  form.submit();
  form.remove();
}

function reporteInventario(){
  let form = document.createElement("form");
  form.setAttribute("method", "post");
  form.setAttribute("action", "../reportes/inventarioPdf.php");
  form.setAttribute("target", "_blank");
  form.innerHTML = ``;
  document.body.appendChild(form);
  form.submit();
  form.remove();
}

function asignaciones_usuario(idUsuario) {
  let form = document.createElement("form");
  form.setAttribute("method", "post");
  form.setAttribute("action", "../reportes/asignacionesUsuarioPdf.php");
  form.setAttribute("target", "_blank");
  form.innerHTML = `<input type="hidden" name="idUsuario" value="${idUsuario}">`;
  document.body.appendChild(form);
  form.submit();
  form.remove();
}