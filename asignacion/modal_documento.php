<div class="modal fade" id="modal_documento" tabindex="-1" role="dialog" aria-labelledby="modalDocumentoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="modalDocumentoLabel">Documentación de Asignación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-success btn-lg mr-3" onclick="generarActa('entrega')">
                            <i class="fas fa-file-signature"></i> Acta de Entrega
                        </button>
                        <button type="button" class="btn btn-info btn-lg" onclick="generarActa('devolucion')">
                            <i class="fas fa-file-export"></i> Acta de Devolución
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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