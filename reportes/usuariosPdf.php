<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    include("../conexion.php");
    include("../tcpdf/tcpdf.php");
    date_default_timezone_set("America/La_Paz");
    $fechaImpresion = date("d/m/Y H:i");
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];
    $area = $_POST['area'] ?? "";
    $sqlArea = "";
    $listaAreas = array();
    $sql = "SELECT idArea, area FROM tblArea ORDER BY area ASC";
    $query = sqlsrv_query($con, $sql);
    while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $listaAreas[$row['idArea']] = $row['area'];
    }
    if ($fechaInicio == "" || $fechaFin == "") {
        $fechaInicio = "1900-01-01";
        $fechaFin = date("Y-m-d");
        $fechaFormato = "";
    } else {
        $fechaFormato = "Fecha de creación: " . date("d/m/Y", strtotime($fechaInicio)) . " al " . date("d/m/Y", strtotime($fechaFin));
    }
    $areaFormato = "Área: TODAS";
    if ($area != "") {
        $areaFormato = "Área: " . $listaAreas[$area];
        $sqlArea = " AND tu.idArea = '$area'";
    }
    // para la consulta a la base
    $listaUsuarios = array();
    $sql = "SELECT tu.usuario, tu.nombre, tu.apellidoPaterno, tu.apellidoMaterno, tu.ci, tu.fechaCreacion, tr.rol, ta.area FROM tblUsuario tu LEFT JOIN tblRol tr ON tr.idRol = tu.idRol LEFT JOIN tblArea ta ON ta.idArea = tu.idArea WHERE tu.fechaCreacion BETWEEN '$fechaInicio' AND '$fechaFin' $sqlArea ORDER BY tu.apellidoPaterno;";
    $query = sqlsrv_query($con, $sql);
    while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $listaUsuarios[] = $row;
    }
    // para la generacion del pdf
    class MYPDF extends TCPDF
    {
        public function Header() {}
        public function Footer()
        {
            $this->SetY(-10);
            $this->SetFont('times', 'I', 8);
            $this->Cell(0, 10, 'Pag. ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
        }
    }
    $autor = "STIS-BOLIVIA";
    $width = 216;
    $height = 270.9;
    $pageLayout = array($width, $height);

    $pdf = new MYPDF('P', 'mm', $pageLayout, true, 'UTF-8', false);
    $pdf->SetCreator($autor);
    $pdf->SetAuthor($autor);
    $pdf->SetTitle("Reporte de usuarios");
    $pdf->SetMargins(10, 10, 10, true);
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->SetFont('times', '', 10);
    $pdf->AddPage();
    $table = '
    <table border="0" cellpadding="1" cellspacing="2">
    <tr>
    <td colspan="20" align="center"><b>REPORTE DE USUARIOS</b></td>
    </tr>
    <tr>
    <td colspan="5">' . $areaFormato . '</td>
    <td colspan="7">' . $fechaFormato . '</td>
    <td colspan="8" align="right">Fecha de impresión: ' . $fechaImpresion . '</td>
    </tr>
    </table>';
    $pdf->writeHTMLCell(0, 0, '', '', $table, 0, 1, 0, true, '', true);
    $pdf->SetFont('times', '', 9);
    $table = '
    <table border="0.5" cellpadding="2" cellspacing="0">
    <tr>
    <td colspan="2" align="center"><b>#</b></td>
    <td colspan="10" align="center"><b>Apellido paterno</b></td>
    <td colspan="10" align="center"><b>Apellido materno</b></td>
    <td colspan="10" align="center"><b>Nombre</b></td>
    <td colspan="6" align="center"><b>CI</b></td>
    <td colspan="10" align="center"><b>Rol</b></td>
    <td colspan="10" align="center"><b>Área</b></td>
    <td colspan="6" align="center"><b>Creación</b></td>
    </tr>';
    $nro = 1;
    if (count($listaUsuarios) == 0) {
        $table .= '
        <tr>
        <td colspan="64" align="center">No se encontraron resultados</td>
        </tr>';
    }
    foreach ($listaUsuarios as $key => $value) {
        $fechaCreacion = $value['fechaCreacion']->format("d/m/y");
        $table .= '
        <tr>
        <td colspan="2" align="center">' . $nro . '</td>
        <td colspan="10" align="left">' . $value['apellidoPaterno'] . '</td>
        <td colspan="10" align="left">' . $value['apellidoMaterno'] . '</td>
        <td colspan="10" align="left">' . $value['nombre'] . '</td>
        <td colspan="6" align="center">' . $value['ci'] . '</td>
        <td colspan="10" align="center">' . $value['rol'] . '</td>
        <td colspan="10" align="center">' . $value['area'] . '</td>
        <td colspan="6" align="center">' . $fechaCreacion . '</td>
        </tr>';
        $nro++;
    }
    $table .= '
    </table>';
    $pdf->writeHTMLCell(0, 0, '', '', $table, 0, 1, 0, true, '', true);
    $pdf->lastPage();
    // $pdf->Output("reporte_usuarios.pdf", "I");
    $content = $pdf->Output("", "S");
    $base64 = base64_encode($content);
    echo json_encode([
        "pdf" => $base64
    ]);
} else {
    header("Location: ../index.php");
}
