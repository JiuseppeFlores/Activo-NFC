function depreciacion() {
    remove();
    document.getElementById("nav_depreciacion").className += " active";
    document.getElementById("carpeta-activa").value = "depreciacion";
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
        url: "../depreciacionTabla/depreciacionTabla.php",
        type: "post",
        success: function (response) {
            $("#shadow").fadeOut();
            $("#spinner").html(``);
            $("#all-body").html(response);
            listar_depreciacion(1);
        },
    });
}
function listar_depreciacion(pag) {
    $("buscador-general").show().animate({ opacity: "1" }, 1000);    
    var start = (pag - 1) * 10;
    var texto = $("#busqueda_depreciacion").val();
    var parametros = {
        start: start,
        texto: texto,
    };

    var result1 = "";
    $.ajax({
        data: parametros,
        url: "../depreciacionTabla/listaDepreciacion.php",
        type: "post",
        success: function (response) {
            result1 = response;

            jQuery.ajax({
                type: "POST",
                url: "../depreciacionTabla/generar_paginacion.php",
                data: parametros,
                dataType: "JSON",
                success: function (data) {
                    $("#depreciacion-result").html(response + data.tabla);
                    $(".pagination").pagination({
                        items: data.records,
                        itemsOnPage: 10,
                        cssStyle: "light-theme",
                        currentPage: pag,
                    });

                    $("#pagina").val(pag);
                },
                beforeSend: function () {
                    $("#depreciacion-result").show();
                    $("#depreciacion-result").html(`<div style="text-align:center">
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
            $("#depreciacion-result").show();
            $("#depreciacion-result").html(`<div style="text-align:center">
                                                                <div class="d-flex justify-content-center">
                                                                    <div class="spinner-border" role="status">
                                                                            <span class="visually-hidden"></span>
                                                                    </div>
                                                                </div>
                                                            </div>`);
        },
    });
}

function edit_depreciacion(id) {
  $("#buscador-general").hide().animate({ opacity: "0" }, 0);
  $("#depreciacion-result").hide().animate({ opacity: "0", bottom: "-80px" }, 0);

  var parametros = {
    id: id,
  };

  $.ajax({
    data: parametros,
    url: "../depreciacionTabla/edit.php",
    type: "post",
    success: function (response) {
      $("#depreciacion-result").show().animate({ opacity: "1", bottom: "-80px" }, 1000);
      $("#depreciacion-result").html(response);

      let form = document.getElementById("edit_depreciacion");
      form.addEventListener("submit", function (event) {
        event.preventDefault();
        send_data("depreciacionTabla", "Actualizado", "edit_insert", "edit_depreciacion");
      });
    },
  });
}

// function listarDetalleDepreciacion(id) {

// }

function listarDetalleDepreciacion(id) {
    const detailsRow = document.getElementById('details-' + id);
    if (detailsRow.style.display === 'none') {
        detailsRow.style.display = '';
    } else {
        detailsRow.style.display = 'none';
    }
}