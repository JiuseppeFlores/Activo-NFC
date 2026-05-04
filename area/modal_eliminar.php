<div class="modal fade" id="modal_eliminar_area" tabindex="-1" role="dialog" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Encabezado -->
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" style="color: white !important;" id="modalEliminarLabel">Eliminar Registro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Cuerpo -->
            <div class="modal-body text-center">
                <p class="lead">¿Está seguro de que desea eliminar este registro?</p>
                <input type="hidden" id="id_area">
            </div>
            <!-- Pie de página -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="borrar_area($('#id_area').val())">Aceptar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
