<?php
// session_start();
include("../conexion.php");
$idRol = $_SESSION['idRol'];
$sqlArea = "SELECT * FROM tblArea ORDER BY area";
$stmt = sqlsrv_query($con, $sqlArea);
$listaArea = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $listaArea[] = $row;
}
?>
<input type="hidden" class="form-control" id="pagina" value="1">
<div class="page-header d-print-none mb-2 pb-3">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">Gestión operativa</div>
            <h2 class="page-title page-title-lg">Asignaciones</h2>
            <div class="page-subtitle">Administra la entrega, devolución y vigencia de los activos asignados.</div>
            <ol class="breadcrumb breadcrumb-arrows mt-2 mb-0">
                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Asignaciones</li>
            </ol>
        </div>
        <?php if ($idRol == 1) { ?>
        <div class="col-auto ms-auto page-header-actions d-print-none d-flex gap-2">
            <button class="btn btn-primary" onclick="add_asignacion()">
                <i class="ti ti-plus icon me-1"></i> Añadir asignación
            </button>
            <button class="btn btn-info" onclick="devolucion()">
                <i class="ti ti-arrow-back-up icon me-1"></i> Realizar Devolución
            </button>
        </div>
        <?php } ?>
    </div>
</div>
<div id="asignacion-form" class="d-none"></div>

<div class="card card-body py-2 mb-2" id="buscador-general">
    <div class="d-flex align-items-center w-100">
        <?php if ($idRol != 3) { ?>
        <div class="row g-2 align-items-center w-100">
            <div class="col-md-5 col-lg-4">
                <div class="input-icon">
                    <input class="form-control" id="busqueda_asignacion" onkeyup="listar_asignacion(1)" type="search" placeholder="Buscar por usuario, bien o código..." aria-label="Search">
                    <span class="input-icon-addon">
                        <i class="ti ti-search icon"></i>
                    </span>
                </div>
            </div>
            <div class="col-md-5 col-lg-4">
                <div class="d-flex align-items-center">
                    <label class="form-label mb-0 me-2 text-nowrap">Área:</label>
                    <select id="area_filter" class="form-select" onchange="listar_asignacion(1)">
                        <option value="">Todas las áreas</option>
                        <?php foreach ($listaArea as $area) { ?>
                            <option value="<?php echo $area['idArea']; ?>"><?php echo $area['area']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<div class="card" id="asignacion-table">
    <div class="card-header">
        <div class="card-title">Listado de asignaciones</div>
    </div>
    <div class="card-body p-0">
        <div id="asignacion-result"></div>
    </div>
</div>
