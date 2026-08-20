<input type="hidden" class="form-control" id="pagina" value="1">
<div class="page-header d-print-none mb-2 pb-3">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">Gestión financiera</div>
            <h2 class="page-title page-title-lg">Depreciación de activos</h2>
            <div class="page-subtitle">Consulta los parámetros y detalles de depreciación de los activos.</div>
            <ol class="breadcrumb breadcrumb-arrows mt-2 mb-0">
                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Depreciación</li>
            </ol>
        </div>
    </div>
</div>

<div class="card card-body py-2 mb-2" id="buscador-general">
    <div class="d-flex align-items-center">
        <div class="input-icon">
            <input class="form-control" id="busqueda_depreciacion" onkeyup="listar_depreciacion(1)" type="search" placeholder="Buscar..." aria-label="Search">
            <span class="input-icon-addon">
                <i class="ti ti-search icon"></i>
            </span>
        </div>
    </div>
</div>

<div class="card" id="depreciacion-table">
    <div class="card-header">
        <div class="card-title">Parámetros de depreciación</div>
    </div>
    <div class="card-body p-0">
        <div id="depreciacion-result"></div>
    </div>
</div>
<div id="depreciacion-form" class="d-none"></div>