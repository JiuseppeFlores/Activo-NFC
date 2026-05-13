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

let pdfDoc = null,
    pageNum = 1,
    pageRendering = false,
    pageNumPending = null,
    scale = 1.5,
    rotation = 0;


function renderPage(num) {
    pageRendering = true;
    pdfDoc.getPage(num).then(function(page) {
        const viewport = page.getViewport({ scale: scale, rotation: rotation });
        const canvas = document.getElementById('pdf-canvas');
        const context = canvas.getContext('2d');
        canvas.height = viewport.height;
        canvas.width = viewport.width;
        context.clearRect(0, 0, canvas.width, canvas.height);
        const renderContext = {
            canvasContext: context,
            viewport: viewport
        };
        
        page.render(renderContext).promise.then(function() {
            pageRendering = false;
            if (pageNumPending !== null) {
                renderPage(pageNumPending);
                pageNumPending = null;
            }
        });
    });
}

function queueRenderPage(num) {
    if (pageRendering) {
        pageNumPending = num;
    } else {
        renderPage(num);
    }
}

function onPrevPage() {
    if (pageNum <= 1) {
        return;
    }
    pageNum--;
    queueRenderPage(pageNum);
    document.getElementById('page-num').textContent = pageNum;
}

function onNextPage() {
    if (pageNum >= pdfDoc.numPages) {
        return;
    }
    pageNum++;
    queueRenderPage(pageNum);
    document.getElementById('page-num').textContent = pageNum;
}

function onZoomIn() {
    scale += 0.1;
    document.getElementById('zoom-level').textContent = Math.round(scale * 100) + '%';
    queueRenderPage(pageNum);
}

function onZoomOut() {
    if (scale <= 0.5) return;
    scale -= 0.1;
    document.getElementById('zoom-level').textContent = Math.round(scale * 100) + '%';
    queueRenderPage(pageNum);
}

function onRotate() {
    rotation = (rotation + 90) % 360;
    queueRenderPage(pageNum);
}
function generarReporte() {
    const tipoReporte = $('#tipo_reporte').val();
    const fechaInicio = $('#fecha_inicio').val();
    const fechaFin = $('#fecha_fin').val();
    const estado = $('#estado').val();
    const area = $('#area').val();
    const tipoBien = $('#tipoProducto').val();
    const bien = $('#bien').val();
    const usuario = $('#usuario').val();
    const fecha = $('#fecha').val();
    const anios = $('#anios').val();
    const disponibilidad = $('#disponibilidad').val();

    if (!tipoReporte) {
        Swal.fire({
            icon: 'warning',
            title: 'Advertencia',
            text: 'Debe seleccionar un tipo de reporte'
        });
        return;
    }

    let url = '';
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
        case 'actaEntrega':
            url = '../reportes/actaEntregaPdf.php';
            break;
        case 'actaDevolucion':
            url = '../reportes/actaDevolucionPdf.php';
            break;
        case 'depreciacion':
            url = '../reportes/depreciacionPdf.php';
            break;
        default:
            break;
    }

    document.getElementById('loading').style.display = 'block';

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            tipo_reporte: tipoReporte,
            fechaInicio: fechaInicio,
            fechaFin: fechaFin,
            estado: estado,
            area: area,
            tipoBien: tipoBien,
            bien: bien,
            usuario: usuario,
            fecha: fecha,
            preview: 'SI',
            anios: anios,
            disponibilidad: disponibilidad
        },
        success: function(response) {
            try {
                const data = JSON.parse(response);
                const base64 = data.pdf;
                const byteCharacters = atob(base64);
                const byteNumbers = new Array(byteCharacters.length);
                for (let i = 0; i < byteCharacters.length; i++) {
                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                const byteArray = new Uint8Array(byteNumbers);
                const blob = new Blob([byteArray], { type: 'application/pdf' });

                const pdfUrl = URL.createObjectURL(blob);
                
                const btnDescargar = document.getElementById('btn-descargar');
                btnDescargar.style.display = 'inline-block';
                btnDescargar.onclick = function() {
                    const link = document.createElement('a');
                    link.href = pdfUrl;
                    link.download = 'reporte_' + tipoReporte + '.pdf';
                    link.click();
                };

                pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
                    pdfDoc = pdf;
                    document.getElementById('page-count').textContent = pdf.numPages;
                    document.getElementById('page-num').textContent = pageNum;
                    document.getElementById('zoom-level').textContent = Math.round(scale * 100) + '%';
                    
                    renderPage(pageNum);
                    
                    document.getElementById('prev-page').onclick = onPrevPage;
                    document.getElementById('next-page').onclick = onNextPage;
                    document.getElementById('zoom-in').onclick = onZoomIn;
                    document.getElementById('zoom-out').onclick = onZoomOut;
                    document.getElementById('rotate').onclick = onRotate;
                    
                    document.getElementById('loading').style.display = 'none';
                });

            } catch (e) {
                console.error("Error al procesar el PDF", e);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo visualizar el PDF'
                });
                document.getElementById('loading').style.display = 'none';
            }
        },
        error: function() {
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Error al generar el reporte'
            });
            document.getElementById('loading').style.display = 'none';
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
function cambiarFiltros() {
    const tipoReporte = $('#tipo_reporte').val();
    switch (tipoReporte) {
        case 'usuario':
            $('#divArea').show();
            $('#divEstado').hide();
            $('#divTipoBien').hide();
            $('#divBien').hide();
            $('#divUsuario').hide();
            $('#divFecha').hide();
            $('#divAnios').hide();
            $('#divDisponibilidad').hide();
            $('#divFechaInicio').show();
            $('#divFechaFinal').show();
            break;
        case 'producto':
            $('#divTipoBien').show();
            $('#divBien').show();
            $('#divArea').hide();
            $('#divEstado').hide();
            $('#divUsuario').hide();
            $('#divFecha').hide();
            $('#divAnios').hide();
            $('#divFechaInicio').show();
            $('#divFechaFinal').show();
            $('#divDisponibilidad').show();
            break;
        case 'actaEntrega':
            $('#divArea').hide();
            $('#divEstado').hide();
            $('#divTipoBien').hide();
            $('#divBien').hide();
            $('#divFechaInicio').hide();
            $('#divFechaFinal').hide();
            $('#divAnios').hide();
            $('#divDisponibilidad').hide();
            $('#divUsuario').show();
            $('#divFecha').show();
            break;
        case 'actaDevolucion':
            $('#divArea').hide();
            $('#divEstado').hide();
            $('#divTipoBien').hide();
            $('#divBien').hide();
            $('#divFechaInicio').hide();
            $('#divFechaFinal').hide();
            $('#divAnios').hide();
            $('#divDisponibilidad').hide();
            $('#divUsuario').show();
            $('#divFecha').show();
            break;
        case 'depreciacion':
            $('#divArea').hide();
            $('#divEstado').hide();
            $('#divTipoBien').hide();
            $('#divBien').hide();
            $('#divFechaInicio').hide();
            $('#divFechaFinal').hide();
            $('#divUsuario').hide();
            $('#divDisponibilidad').hide();
            $('#divFecha').hide();
            $('#divAnios').show();
            break;
        case 'asignacion':
            $('#divEstado').hide();
            $('#divTipoBien').hide();
            $('#divBien').hide();
            $('#divAnios').hide();
            $('#divUsuario').hide();
            $('#divFecha').hide();
            $('#divDisponibilidad').hide();
            $('#divArea').show();
            $('#divFechaInicio').show();
            $('#divFechaFinal').show();
            break;
        default:
            $('#divTipoBien').hide();
            $('#divBien').hide();
            $('#divArea').hide();
            $('#divEstado').hide();
            $('#divUsuario').hide();
            $('#divFecha').hide();
            $('#divAnios').hide();
            $('#divDisponibilidad').hide();
            $('#divFechaInicio').show();
            $('#divFechaFinal').show();
            break;
    }
}