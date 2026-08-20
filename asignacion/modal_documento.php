<div class="modal modal-blur fade" id="modal_documento" tabindex="-1" role="dialog" aria-labelledby="modalDocumentoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDocumentoLabel">Documentación de Asignación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="row g-3 justify-content-center">
                    <div class="col-auto">
                        <button type="button" class="btn btn-success btn-lg" onclick="generarActa('entrega')">
                            <i class="ti ti-file-pencil me-2"></i> Acta de Entrega
                        </button>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-info btn-lg" onclick="generarActa('devolucion')">
                            <i class="ti ti-file-export me-2"></i> Acta de Devolución
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#modal_documento').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var idAsignacion = button.data('id');
        $('#modal_documento').data('id', idAsignacion);
    });
});
</script> 