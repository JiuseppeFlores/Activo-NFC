function generarUsuario() {
    var nombre = document.getElementById('nombre').value.trim();
    var apellidoPaterno = document.getElementById('apellidoPaterno').value.trim();
    
    if (nombre === '' || apellidoPaterno === '') {
        alertify.error('Por favor, complete el nombre y apellido paterno primero');
        return;
    }
    
    // Tomar la primera letra del nombre y las primeras letras del apellido
    var usuario = nombre.charAt(0).toLowerCase() + '-' + apellidoPaterno.toLowerCase();
    
    // Eliminar espacios y caracteres especiales
    usuario = usuario.replace(/\s+/g, '');
    usuario = usuario.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    
    document.getElementById('usuario').value = usuario;
    verificarUsuario(); // Verificar si el usuario generado ya existe
} 