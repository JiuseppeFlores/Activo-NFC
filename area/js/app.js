function listar_area(pag) {
  $("#buscador-general").show().animate({ opacity: "1" }, 1000);

  var start = (pag - 1) * 10;
  var texto = $("#busqueda_area").val();
  var parametros = {
    start: start,
    texto: texto,
  };

  var result1 = "";
  $.ajax({
    data: parametros,
    url: "../area/listaarea.php",
    type: "post",
    success: function (response) {
      result1 = response;

      jQuery.ajax({
        type: "POST",
        url: "../area/generar_paginacion.php",
        data: parametros,
        dataType: "JSON",
        success: function (data) {
          $("#area-result").html(response + data.tabla);
          $(".pagination").pagination({
            items: data.records,
            itemsOnPage: 10,
            cssStyle: "light-theme",
            currentPage: pag,
          });

          $("#pagina").val(pag);
        },
        beforeSend: function () {
          $("#area-result").show();
          $("#area-result").html(`<div style="text-align:center">
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
      $("#area-result").show();
      $("#area-result").html(`<div style="text-align:center">
                                                                <div class="d-flex justify-content-center">
                                                                    <div class="spinner-border" role="status">
                                                                            <span class="visually-hidden"></span>
                                                                    </div>
                                                                </div>
                                                            </div>`);
    },
  });
}

function area() {
  remove();
  document.getElementById("nav_area").className += " active";
  document.getElementById("carpeta-activa").value = "area";
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
    url: "../area/area.php",
    type: "post",
    success: function (response) {
      $("#shadow").fadeOut();
      $("#spinner").html(``);
      $("#all-body").html(response);
      listar_area(1);
    },
  });
}

function add_area() {
  $("#buscador-general").hide().animate({ opacity: "0" }, 0);
  $("#area-result").hide().animate({ opacity: "0", bottom: "-80px" }, 0);
  $.ajax({
    url: "../area/add.php",
    type: "post",
    success: function (response) {
      $("#area-result").show().animate({ opacity: "1", bottom: "-80px" }, 1000);
      $("#area-result").html(response);

      let form = document.getElementById("add_area");
      form.addEventListener("submit", function (event) {
        event.preventDefault();
        send_data("area", "Guardado", "add_insert", "add_area");
      });
    },
    beforeSend: function () {},
  });
}

function edit_area(id) {
  $("#buscador-general").hide().animate({ opacity: "0" }, 0);
  $("#area-result").hide().animate({ opacity: "0", bottom: "-80px" }, 0);

  var parametros = {
    id: id,
  };

  $.ajax({
    data: parametros,
    url: "../area/edit.php",
    type: "post",
    success: function (response) {
      $("#area-result").show().animate({ opacity: "1", bottom: "-80px" }, 1000);
      $("#area-result").html(response);

      let form = document.getElementById("edit_area");
      form.addEventListener("submit", function (event) {
        event.preventDefault();
        send_data("area", "Actualizado", "edit_insert", "edit_area");
      });
    },
  });
}

$("#modal_eliminar_area").on("show.bs.modal", function (e) {
  var id = $(e.relatedTarget).data().id;
  $("#id_area").val(id);
});

function borrar_area(id) {
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
    url: "../area/eliminar.php",
    type: "post",
    success: function (response) {
      $("#shadow").fadeOut();
      $("#spinner").html(``);
      if (response == 1) {
        $("#modal_eliminar_area").modal("hide");
        alertify.success("Registro eliminado");
        listar_area(pag);
      } else if (response == 2) {
        alertify.error("No se pudo eliminar el registro.");
      } else if (response == 3) {
        alertify.error(
          "No se pudo eliminar el AREA porque tiene usuarios dependientes."
        );
      }
    },
  });
}
