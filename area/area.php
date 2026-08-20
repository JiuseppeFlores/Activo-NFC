<input type="hidden" class="form-control" id="pagina" value="1">
<div class="page-header d-print-none mb-2 pb-3">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">Administración del sistema</div>
            <h2 class="page-title page-title-lg">Áreas operativas</h2>
            <div class="page-subtitle">Organiza los activos y usuarios por área operativa.</div>
            <ol class="breadcrumb breadcrumb-arrows mt-2 mb-0">
                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Áreas</li>
            </ol>
        </div>
        <div class="col-auto ms-auto page-header-actions d-print-none">
            <button class="btn btn-primary" onclick="add_area()">
                <i class="ti ti-plus icon me-1"></i> Añadir área
            </button>
        </div>
    </div>
</div>
<div class="card card-body py-2 mb-2" id="buscador-general">
    <div class="d-flex align-items-center">
        <div class="input-icon">
            <input class="form-control" id="busqueda_area" onkeyup="listar_area(1)" type="search" placeholder="Buscar..." aria-label="Search">
            <span class="input-icon-addon">
                <i class="ti ti-search icon"></i>
            </span>
        </div>
    </div>
</div>

<div class="card" id="area-table">
    <div class="card-header">
        <div class="card-title">Listado de áreas</div>
    </div>
    <div class="card-body p-0">
        <div id="area-result"></div>
    </div>
</div>
<div id="area-form" class="d-none"></div>