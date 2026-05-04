function togglePasswordVisibility() {
  var passwordInput = $("#password");
  var toggleButton = $(".password-toggle i");

  if (passwordInput.attr("type") === "password") {
    passwordInput.attr("type", "text");
    toggleButton.removeClass("fa-eye").addClass("fa-eye-slash");
  } else {
    passwordInput.attr("type", "password");
    toggleButton.removeClass("fa-eye-slash").addClass("fa-eye");
  }
}

$(".password-toggle").click(function (e) {
  e.preventDefault();
  togglePasswordVisibility();
});

function validarCampo(valor, minCaracteres, tipoCampo) {
  if (!valor || valor.length < minCaracteres) {
      return `El ${tipoCampo} debe tener al menos ${minCaracteres} caracteres.`;
  }
  return '';
}

function mostrarMensaje(mensaje, tipo) {
  const $mensaje = $("#mensaje");
  $mensaje.removeClass('mensaje-success mensaje-error mensaje-loading');
  $mensaje.addClass(`mensaje-${tipo} show`);
  $mensaje.html(mensaje);
  
  $mensaje.css({
      opacity: 0,
      transform: 'translateY(20px)'
  }).animate({
      opacity: 1,
      transform: 'translateY(0)'
  }, 300);
  
  setTimeout(() => {
      $mensaje.animate({
          opacity: 0,
          transform: 'translateY(20px)'
      }, 300, () => {
          $mensaje.removeClass('show');
      });
  }, 3000);
}

$(".login").submit(function (e) {
  e.preventDefault();
  
  const username = $("#user_name").val();
  const password = $("#password").val();
  
  const errores = [];
  const errorUsuario = false;
  const errorPassword = false;
  
  if (errorUsuario) {
      errores.push(errorUsuario);
  }
  if (errorPassword) {
      errores.push(errorPassword);
  }
  
  if (errores.length > 0) {
      mostrarMensaje(errores.join('<br>'), 'error');
      return;
  }

  const parametros = {
      name: username,
      pwd: password,
  };

  const $submitButton = $(".submit");
  const originalText = $submitButton.html();
  $submitButton.html('<i class="fas fa-spinner fa-spin"></i> Verificando...');
  $submitButton.prop('disabled', true);

  $.ajax({
      type: "POST",
      url: "login.php",
      data: parametros,
      timeout: 10000, // 10 segundos
      success: function (html) {
          try {
              const respuesta = JSON.parse(html);
              console.log("Respuesta del servidor:", respuesta);

              switch (respuesta.status) {
                  case 1:
                      mostrarMensaje('¡Bienvenido!', 'success');
                      setTimeout(() => {
                          window.location.href = "../main-page/";
                      }, 1000);
                      break;

                  case 2:
                  case 3:
                      mostrarMensaje(respuesta.message, 'error');
                      break;

                  default:
                      mostrarMensaje('Error desconocido. Por favor, inténtelo de nuevo.', 'error');
              }
          } catch (error) {
              console.error("Error al procesar la respuesta:", error);
              mostrarMensaje('Error al procesar la respuesta del servidor.', 'error');
          }
      },
      error: function (jqXHR, textStatus, errorThrown) {
          console.error("Error en la petición AJAX:", textStatus, errorThrown);
          let mensajeError = 'Error en la conexión con el servidor.';
          if (textStatus === 'timeout') {
              mensajeError = 'El servidor está tardando demasiado en responder. Por favor, inténtelo de nuevo.';
          }
          mostrarMensaje(mensajeError, 'error');
      },
      complete: function () {
          $submitButton.html(originalText);
          $submitButton.prop('disabled', false);
      }
  });
});