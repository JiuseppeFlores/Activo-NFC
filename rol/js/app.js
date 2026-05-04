function listar_rol(pag) {
  $("#buscador-general").show().animate({ opacity: "1" }, 1000);

  var start = (pag - 1) * 10;
  var texto = $("#busqueda_rol").val();
  var parametros = {
    start: start,
    texto: texto,
  };

  var result1 = "";
  $.ajax({
    data: parametros,
    url: "../rol/listarol.php",
    type: "post",
    success: function (response) {
      result1 = response;

      jQuery.ajax({
        type: "POST",
        url: "../rol/generar_paginacion.php",
        data: parametros,
        dataType: "JSON",
        success: function (data) {
          $("#rol-result").html(response + data.tabla);
          $(".pagination").pagination({
            items: data.records,
            itemsOnPage: 10,
            cssStyle: "light-theme",
            currentPage: pag,
          });

          $("#pagina").val(pag);
        },
        beforeSend: function () {
          $("#rol-result").show();
          $("#rol-result").html(`<div style="text-align:center">
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
      $("#rol-result").show();
      $("#rol-result").html(`<div style="text-align:center">
                                                                <div class="d-flex justify-content-center">
                                                                    <div class="spinner-border" role="status">
                                                                            <span class="visually-hidden"></span>
                                                                    </div>
                                                                </div>
                                                            </div>`);
    },
  });
}

function rol() {
  remove();
  document.getElementById("nav_rol").className += " active";
  document.getElementById("carpeta-activa").value = "rol";
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
    url: "../rol/rol.php",
    type: "post",
    success: function (response) {
      $("#shadow").fadeOut();
      $("#spinner").html(``);
      $("#all-body").html(response);
      listar_rol(1);
    },
  });
}

function add_rol() {
  $("#buscador-general").hide().animate({ opacity: "0" }, 0);
  $("#rol-result").hide().animate({ opacity: "0", bottom: "-80px" }, 0);
  $.ajax({
    url: "../rol/add.php",
    type: "post",
    success: function (response) {
      $("#rol-result").show().animate({ opacity: "1", bottom: "-80px" }, 1000);
      $("#rol-result").html(response);

      let form = document.getElementById("add_rol");
      form.addEventListener("submit", function (event) {
        event.preventDefault();
        send_data("rol", "Guardado", "add_insert", "add_rol");
      });
    },
    beforeSend: function () {},
  });
}

function edit_rol(id) {
  $("#buscador-general").hide().animate({ opacity: "0" }, 0);
  $("#rol-result").hide().animate({ opacity: "0", bottom: "-80px" }, 0);

  var parametros = {
    id: id,
  };

  $.ajax({
    data: parametros,
    url: "../rol/edit.php",
    type: "post",
    success: function (response) {
      $("#rol-result").show().animate({ opacity: "1", bottom: "-80px" }, 1000);
      $("#rol-result").html(response);

      let form = document.getElementById("edit_rol");
      form.addEventListener("submit", function (event) {
        event.preventDefault();
        send_data("rol", "Actualizado", "edit_insert", "edit_rol");
      });
    },
  });
}

$("#modal_eliminar_rol").on("show.bs.modal", function (e) {
  var id = $(e.relatedTarget).data().id;
  $("#id_rol").val(id);
});

function borrar_rol(id) {
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
    url: "../rol/eliminar.php",
    type: "post",
    success: function (response) {
      $("#shadow").fadeOut();
      $("#spinner").html(``);
      if (response == 1) {
        $("#modal_eliminar_rol").modal("hide");
        alertify.success("Registro eliminado");
        listar_rol(pag);
      } else if (response == 2) {
        alertify.error("Error");
      } else if (response == 3) {
        alertify.error("No se pudo eliminar el ROL porque tiene usuarios dependientes.");
      }
    },
  });
}
