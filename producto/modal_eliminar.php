<div class="modal modal-blur fade" id="modal_eliminar_producto" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Cerrar"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
                <i class="ti ti-alert-triangle icon mb-2 text-danger" style="font-size: 2.5rem;"></i>
                <h3>Eliminar Bien</h3>
                <div class="text-secondary">¿Está seguro de que desea ELIMINAR este BIEN?</div>
                <input type="hidden" id="id_producto">
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col"><button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal" data-dismiss="modal">Cancelar</button></div>
                        <div class="col"><button type="button" class="btn btn-danger w-100" onclick="borrar_producto($('#id_producto').val())">Aceptar</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
