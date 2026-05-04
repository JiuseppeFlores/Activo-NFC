$(document).ready(function () {
  // area();
  // listar_area();
  dashboard();
});

var max_width = 1500;
var max_height = 720;

function prueba(pag) {
  var carpeta_activa = document.getElementById("carpeta-activa").value;

  if (carpeta_activa == "area") {
    listar_area(pag);
  }

  if (carpeta_activa == "rol") {
    listar_rol(pag);
  }

  if (carpeta_activa == "usuario") {
    listar_usuario(pag);
  }

  if (carpeta_activa == "producto") {
    listar_producto(pag);
  }

  if (carpeta_activa == "asignacion") {
    listar_asignacion(pag);
  }

  if (carpeta_activa == "inventario") {
    listar_inventario(pag);
  }

  if (carpeta_activa == "depreciacion") {
    listar_depreciacion(pag);
  }

  if (carpeta_activa == "reportes") {
    reportes(pag);
  }
}

function remove() {
  document.getElementById("pagina-activa").value = 1;

  if (document.getElementById("nav_area")) {
    document.getElementById("nav_area").className = "nav-link";
  }

  if (document.getElementById("nav_rol")) {
    document.getElementById("nav_rol").className = "nav-link";
  }

  if (document.getElementById("nav_usuario")) {
    document.getElementById("nav_usuario").className = "nav-link";
  }

  if (document.getElementById("nav_producto")) {
    document.getElementById("nav_producto").className = "nav-link";
  }

  if (document.getElementById("nav_asignacion")) {
    document.getElementById("nav_asignacion").className = "nav-link";
  }

  if (document.getElementById("nav_inventario")) {
    document.getElementById("nav_inventario").className = "nav-link";
  }

  if (document.getElementById("nav_depreciacion")) {
    document.getElementById("nav_depreciacion").className = "nav-link";
  }

  if (document.getElementById("nav_reportes")) {
    document.getElementById("nav_reportes").className = "nav-link";
  }
}

function previ(idimg, idbase, idinput) {
  var idFiles = document.getElementById(idinput);
  var archivo = idFiles.files[0];
  var xx = archivo.name;
  var ext = xx.split(".").pop();
  ext = ext.toLowerCase();
  if (ext == "jpg" || ext == "png" || ext == "jpeg") {
    processfile(archivo, idimg, idbase);
  } else {
    $("#file-previ").val("");
    alertify.error("Formato de archivo no valido");
  }
}

function processfile(file, idimg, idbase) {
  if (!/image/i.test(file.type)) {
    alert("File " + file.name + " is not an image.");
    return false;
  }
  // read the files
  var reader = new FileReader();
  reader.readAsArrayBuffer(file);
  reader.onload = function (event) {
    // blob stuff
    var blob = new Blob([event.target.result]); // create blob...
    window.URL = window.URL || window.webkitURL;
    var blobURL = window.URL.createObjectURL(blob);

    // helper Image object
    var image = new Image();
    image.src = blobURL;
    //preview.appendChild(image); // preview commented out, I am using the canvas instead
    image.onload = function () {
      // have to wait till it`s loaded
      var resized = resizeMe(image); // send it to canvas
      let image2 = document.createElement("img");
      let lugar = document.getElementById(idimg);
      image2.src = resized;
      image2.style.width = "200px";
      image2.style.height = "200px";
      image2.style.margin = "5px";
      image2.style.borderRadius = "10px";
      image2.name = idimg + idbase;
      image2.id = idimg + idbase;
      lugar.innerHTML = "";
      lugar.append(image2);
      var base64 = document.getElementById(idimg + idbase).src;
      document.getElementById(idbase).value = base64;
    };
  };
}

function resizeMe(img) {
  var canvas = document.createElement("canvas");
  var width = img.width;
  var height = img.height;

  if (width > height) {
    if (width > max_width) {
      height = Math.round((height *= max_width / width));
      width = max_width;
    }
  } else {
    if (height > max_height) {
      width = Math.round((width *= max_height / height));
      height = max_height;
    }
  }

  canvas.width = width;
  canvas.height = height;
  var ctx = canvas.getContext("2d");
  ctx.drawImage(img, 0, 0, width, height);
  return canvas.toDataURL("image/png", 0.7);
}

// Boton para imprimir zona div
function reporte_excel(areaImprimir) {
  columnas = [];
  var contenido = document.getElementById(areaImprimir).innerHTML;
  $("#" + areaImprimir)
    .find("input,select,textarea")
    .each(function (i, v) {
      columnas.push(v.value);
    });
  console.log(columnas);
  var contenidoOriginal = document.body.innerHTML;
  document.body.innerHTML = contenido;
  window.print();
  document.body.innerHTML = contenidoOriginal;
}

// Boton para imprimir zona div
function reporte_pdf(areaImprimir) {
  columnas = [];
  var contenido = document.getElementById(areaImprimir).innerHTML;
  $("#" + areaImprimir)
    .find("input,select,textarea")
    .each(function (i, v) {
      columnas.push(v.value);
    });
  console.log(columnas);
  var contenidoOriginal = document.body.innerHTML;
  document.body.innerHTML = contenido;
  window.print();
  document.body.innerHTML = contenidoOriginal;
}

$(function () {
  //Obtenemos todos los elementos con clase uk-icon (botones)
  $("a[uk-icon]").each(function () {
    //En cada uno hacemos el bind al evento click
    $(this).click(function () {
      if ($(this).attr("uk-icon") === "forward") {
        //Buscamos su contenedor más cercano (uk-margin) y los movemos a #areaImprimir
        $(this).closest(".uk-margin").appendTo("#areaImprimir");
        $(this).attr("uk-icon", "reply");
      } else if ($(this).attr("uk-icon") === "reply") {
        //Si el botón es un reply es que queremos deshacer y volver a añadir al div "areaAgenda"
        $(this).closest(".uk-margin").appendTo("#areaAgenda");
        //Restablecemos el icono
        $(this).attr("uk-icon", "forward");
      }
    });
  });
  //Si pulsamos borrar todo, obtenemos los .uk-margin y los ponemos en "areaAgenda" de nuevo con su icono correspondiente
  $("#btnBorrar").click(function () {
    $(".uk-margin").each(function () {
      $(this).appendTo("#areaAgenda");
      $($(this).find("a")).attr("uk-icon", "forward");
    });
  });
});

const toBase64 = (file) =>
  new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => resolve(reader.result);
    reader.onerror = (error) => reject(error);
  });

async function Main() {
  var ext = $("#files").val().split(".").pop();
  if ($("#files").val() != "") {
    if (ext == "pdf") {
      if ($("#files")[0].files[0].size > 10048576) {
        alert("El documento excede el tamaño máximo de 10 MB");
        $("#modal-title").text("¡Precaución!");
        $("#modal-msg").html(
          "Se solicita un archivo no mayor a 3MB. Por favor verifica."
        );
        $("#modal-gral").modal();
        $("#files").val("");
      } else {
        $("#modal-gral").hide();
        const file = document.getElementById("files").files[0];
        document.getElementById("for-file").value = await toBase64(file);
      }
    } else {
      $("#files").val("");
      alert("Tipo de documento no permitido: " + ext);
    }
  }
}
