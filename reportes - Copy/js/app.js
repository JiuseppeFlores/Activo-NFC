function reportes(page) {
    remove();
    document.getElementById("nav_reportes").className += " active";
    document.getElementById("carpeta-activa").value = "reportes";
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
        url: "../reportes/reportes.php",
        type: "POST",
        data: {
            page: page
        },
        success: function(data) {
            $("#all-body").html(data);
            $("#shadow").fadeOut("normal");
            $("#spinner").html("");
        }
    });
}

function generarReporte() {
    const tipoReporte = $('#tipo_reporte').val();
    const fechaInicio = $('#fecha_inicio').val();
    const fechaFin = $('#fecha_fin').val();
    const estado = $('#estado').val();

    if (!tipoReporte) {
        Swal.fire({
            icon: 'warning',
            title: 'Advertencia',
            text: 'Debe seleccionar un tipo de reporte'
        });
        return;
    }

    switch (tipoReporte) {
        case 'area':
            url = '../reportes/areaPdf.php';
            break;
        case 'asignacion':
            url = '../reportes/asignacionesPdf.php';
            break;
        case 'inventario':
            url = '../reportes/inventarioPdf.php';
            break;
        case 'producto':
            url = '../reportes/productosPdf.php';
            break;
        case 'usuario':
            url = '../reportes/usuariosPdf.php';
            break;
        default:
            break;
    }

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            tipo_reporte: tipoReporte,
            fechaInicio: fechaInicio,
            fechaFin: fechaFin,
            estado: estado
        },
        success: function(response) {
            try {
                const data = JSON.parse(response);
                const base64 = data.pdf;
    
                // Convertir base64 a Uint8Array
                const byteCharacters = atob(base64);
                const byteNumbers = new Array(byteCharacters.length);
                for (let i = 0; i < byteCharacters.length; i++) {
                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                const byteArray = new Uint8Array(byteNumbers);
                const blob = new Blob([byteArray], { type: 'application/pdf' });
    
                // Usar PDF.js para mostrar el PDF
                const pdfUrl = URL.createObjectURL(blob);
                const btnDescargar = document.getElementById('btn-descargar');
                btnDescargar.style.display = 'inline-block';
                btnDescargar.onclick = function() {
                    const link = document.createElement('a');
                    link.href = pdfUrl;
                    link.download = 'reporte.pdf'; // nombre del archivo
                    link.click();
                };
    
                // Aquí lo mostramos en un canvas
                const loadingTask = pdfjsLib.getDocument(pdfUrl);
                loadingTask.promise.then(function(pdf) {
                    pdf.getPage(1).then(function(page) {
                        const scale = 1.5;
                        const viewport = page.getViewport({ scale: scale });
    
                        const canvas = document.getElementById('pdf-canvas');
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
    
                        const renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        page.render(renderContext);
                    });
                });
    
            } catch (e) {
                console.error("Error al procesar el PDF", e);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo visualizar el PDF'
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Error al generar el reporte'
            });
        }
    });
}

function limpiarFiltros() {
    $('#form-filtros')[0].reset();
    $('#reporte-resultados').html('');
}

function exportarReporte() {
    const tipoReporte = $('#tipo_reporte').val();
    const fechaInicio = $('#fecha_inicio').val();
    const fechaFin = $('#fecha_fin').val();
    const estado = $('#estado').val();

    window.location.href = '../reportes/exportar_reporte.php?tipo_reporte=' + tipoReporte +
        '&fecha_inicio=' + fechaInicio +
        '&fecha_fin=' + fechaFin +
        '&estado=' + estado;
}