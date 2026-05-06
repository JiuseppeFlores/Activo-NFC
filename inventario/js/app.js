function listar_inventario(pag) {
  $("#buscador-general").show().animate({ opacity: "1" }, 1000);

  var start = (pag - 1) * 10;
  var texto = $("#busqueda_inventario").val();
  var parametros = {
    start: start,
    texto: texto,
  };

  var result1 = "";
  $.ajax({
    data: parametros,
    url: "../inventario/listainventario.php",
    type: "post",
    success: function (response) {
      result1 = response;

      jQuery.ajax({
        type: "POST",
        url: "../inventario/generar_paginacion.php",
        data: parametros,
        dataType: "JSON",
        success: function (data) {
          $("#inventario-result").html(response + data.tabla);
          $(".pagination").pagination({
            items: data.records,
            itemsOnPage: 10,
            cssStyle: "light-theme",
            currentPage: pag,
          });

          $("#pagina").val(pag);
        },
        beforeSend: function () {
          $("#inventario-result").show();
          $("#inventario-result").html(`<div style="text-align:center">
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
      $("#inventario-result").show();
      $("#inventario-result").html(`<div style="text-align:center">
        <div class="d-flex justify-content-center">
        <div class="spinner-border" role="status">
        <span class="visually-hidden"></span>
        </div>
        </div>
        </div>`);
    },
  });
}

function inventario() {
  remove();
  document.getElementById("nav_inventario").className += " active";
  document.getElementById("carpeta-activa").value = "inventario";
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
    url: "../inventario/inventario.php",
    type: "post",
    success: function (response) {
      $("#shadow").fadeOut();
      $("#spinner").html(``);
      $("#all-body").html(response);
      listar_inventario(1);
    },
  });
}

function add_inventario() {
  $("#buscador-general").hide().animate({ opacity: "0" }, 0);
  $("#inventario-result").hide().animate({ opacity: "0", bottom: "-80px" }, 0);
  $.ajax({
    url: "../inventario/add.php",
    type: "post",
    success: function (response) {
      $("#inventario-result")
        .show()
        .animate({ opacity: "1", bottom: "-80px" }, 1000);
      $("#inventario-result").html(response);

      let form = document.getElementById("add_inventario");
      form.addEventListener("submit", function (event) {
        event.preventDefault();
        send_data("inventario", "Guardado", "add_insert", "add_inventario");
      });
    },
    beforeSend: function () {},
  });
}

function edit_inventario(id) {
  $("#buscador-general").hide().animate({ opacity: "0" }, 0);
  $("#inventario-result").hide().animate({ opacity: "0", bottom: "-80px" }, 0);

  var parametros = {
    id: id,
  };

  $.ajax({
    data: parametros,
    url: "../inventario/edit.php",
    type: "post",
    success: function (response) {
      $("#inventario-result")
        .show()
        .animate({ opacity: "1", bottom: "-80px" }, 1000);
      $("#inventario-result").html(response);

      let form = document.getElementById("edit_inventario");
      form.addEventListener("submit", function (event) {
        event.preventDefault();
        send_data(
          "inventario",
          "Actualizado",
          "edit_insert",
          "edit_inventario"
        );
      });
    },
  });
}

$("#modal_eliminar_inventario").on("show.bs.modal", function (e) {
  var id = $(e.relatedTarget).data().id;
  $("#id_inventario").val(id);
});

function borrar_inventario(id) {
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
    url: "../inventario/eliminar.php",
    type: "post",
    success: function (response) {
      $("#shadow").fadeOut();
      $("#spinner").html(``);
      if (response == 1) {
        alertify.success("Registro eliminado");
        listar_inventario(pag);
      } else if (response == 2) {
        alertify.error("Error");
      }
    },
  });
}

function getUsuario() {
  var idAsignacion = $("#idAsignacion").val();
  var parametros = {
    idAsignacion: idAsignacion,
  };

  $.ajax({
    data: parametros,
    url: "../inventario/get_usuario.php",
    type: "post",
    dataType: "json",
    success: function (response) {
      // console.log(response);
      $("#usuario").val(response.nombreCompleto + " - CI: " + response.ci);
    },
  });
}
