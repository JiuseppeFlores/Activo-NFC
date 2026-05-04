function send_data(carpeta, msg, tipo, nombre_form, selecciones = [], accion = "") {
  $("#shadow").fadeIn("normal");
  $("#spinner").html(`<div class='spinner-container'>
                                        <div class="spinner-path">
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        </div>
                                    </div>`);
  const peticion = new XMLHttpRequest();
  var data = new FormData();
  data = getFormData(nombre_form, data);
  for (var value of data.values()) {
    console.log(value);
  }
  if (selecciones.length > 0) {
    data.append("selecciones", JSON.stringify(selecciones));
  }
  peticion.open("POST", "../" + carpeta + "/" + tipo + ".php");
  peticion.send(data);
    /**
     * Handles the response from the AJAX request.
     * Based on the response, it performs operations such as displaying success/error messages,
     * updating the UI, and calling specific functions related to the active folder.
     * 
     * Response codes:
     * - 1: Success, refreshes content based on the active folder.
     * - 2: Error, displays an error message.
     * - 7: Duplicate entry, displays a duplicate record error message.
     */
  peticion.onload = function () {
    console.log("respuesta:" + this.responseText);
    if (this.responseText == 1) {
      alertify.success(msg);
      var carpeta_activa = document.getElementById("carpeta-activa").value;

      if (carpeta_activa == "area") {
        listar_area(1);
      } else if (carpeta_activa == "rol") {
        listar_rol(1);
      } else if (carpeta_activa == "usuario") {
        listar_usuario(1);
      } else if (carpeta_activa == "producto") {
        listar_producto(1);
        generarReporteBien();
      } else if (carpeta_activa == "asignacion") {
        if (accion == "entrega") {
          generarActa2('entrega', selecciones);
        } else if (accion == "devolucion") {
          generarActa2('devolucion', selecciones);
        }
        listar_asignacion(1);
      } else if (carpeta_activa == "inventario") {
        listar_inventario(1);
      } else if (carpeta_activa == "depreciacion") {
        listar_depreciacion(1);
      } else if (carpeta_activa == "reportes") {
        reportes(1);
      }

      $("#buscador-general").show().animate({ opacity: "1" }, 1000);
    } else if (this.responseText == 2) {
      alertify.error("Error");
    } else if (this.responseText == 7) {
      alertify.error("Registro repetido");
    }

    $("#shadow").fadeOut();
    $("#spinner").html(``);
  };
}

function getFormData(id, data) {
  $("#" + id)
    .find("input,select,textarea")
    .each(function (i, v) {
      if (v.type !== "file") {
        if (v.type === "checkbox" && v.checked === true) {
          data.append(v.name, "on");
        } else {
          console.log("nombre:" + v.name + "-- valor:" + v.value);
          data.append(v.name, v.value);
        }
      }
    });

  for (let index = 1; index < 40; index++) {
    var testData = !!document.getElementById("textarea" + index);
    // console.log("Is Not null?",testData);
    if (testData) {
      var campo = document
        .getElementById("textarea" + index)
        .getAttribute("name");
      var texto = $("#textarea" + index).html();
      data.append(campo, texto);
    }
  }

  return data;
}
