<?php
session_start();
$idRol = $_SESSION['idRol'];
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
                <input type="hidden" class="form-control" id="pagina" value="1">
                <h1 class="m-0" style="display:inline-block">Usuario</h1>
                <?php if ($idRol == 1) { ?>
                <button style="display:inline-block;margin-left:100px" class="btn btn-primary btn-lg" onclick="add_usuario()"> <i class="fas fa-plus"></i> Añadir usuario</button>
                <!-- <button style="display:inline-block; margin-left:100px;" class="btn btn-primary btn-lg" onclick="reporteUsuarios()"><i class="fas fa-file"></i> Generar reporte</button> -->
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">

        </div>
        <div class="row">

            <section class="col-lg-12 connectedSortable" style="overflo">
                <div class="card direct-chat direct-chat-primary">
                    <div class="card-header" id="buscador-general">

                        <div class="form-inline" style="float: left;">
                            <div class="input-group" data-widget="sidebar-search">
                                <?php if ($idRol != 3) { ?>
                                <input class="form-control" id="busqueda_usuario" onkeyup="listar_usuario(1)" type="search" placeholder="Buscar" aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-sidebar">
                                        <i class="fas fa-search fa-fw"></i>
                                    </button>
                                </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="for-pagination1" style="text-align:center"></div>
                        <div id="usuario-result"></div>
                        <div id="for-pagination2" style="text-align:center"></div>
                    </div>
                    <div class="card-footer">
                    </div>
                </div>

            </section>

        </div>
        <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
</section>