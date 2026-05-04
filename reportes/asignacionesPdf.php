<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    include("../conexion.php");
    include("../tcpdf/tcpdf.php");
    date_default_timezone_set("America/La_Paz");
    $fechaImpresion = date("d/m/Y H:i");
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];
    $area = $_POST['area'];
    $tituloArea = "Todas";
    if ($fechaInicio == "" || $fechaFin == "") {
        $fechaInicio = "1900-01-01 00:00:00";
        $fechaFin = date("Y-m-d H:i:s");
        $fechaFormato = "";
    } else {
        $fechaFormato = "Fecha de asignación: " . date("d/m/Y", strtotime($fechaInicio . " 00:00:00")) . " al " . date("d/m/Y", strtotime($fechaFin . " 23:59:59"));
    }
    if ($area == "") {
        $sqlArea = "";
    } else {
        $sqlArea = " AND tu.idArea = '$area'";
        $sqlAreaTitulo = "SELECT ta.area FROM tblArea ta WHERE ta.idArea = '$area'";
        $queryAreaTitulo = sqlsrv_query($con, $sqlAreaTitulo);
        $rowAreaTitulo = sqlsrv_fetch_array($queryAreaTitulo, SQLSRV_FETCH_ASSOC);
        $tituloArea = $rowAreaTitulo['area'];
    }
    // para la consulta a la base de datos
    $listaAsignaciones = array();
    $sql = "SELECT tdd.bienDetalle, tp.producto, tp.codigoBarras, tu.nombre, tu.apellidoPaterno, tu.apellidoMaterno, tu.ci, tas.* FROM tblAsignacion tas LEFT JOIN tblProducto tp ON tp.idProducto = tas.idProducto LEFT JOIN tblDepreciacionDetalle tdd ON tdd.idDepreciacionDetalle = tp.idDepreciacionDetalle LEFT JOIN tblUsuario tu ON tu.idUsuario = tas.idUsuario WHERE tas.fechaInicial BETWEEN '$fechaInicio' AND '$fechaFin' $sqlArea ORDER BY tas.idAsignacion ASC;";
    // echo $sql;
    $query = sqlsrv_query($con, $sql);
    while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $listaAsignaciones[] = $row;
    }
    // para la generacion del pdf
    class MYPDF extends TCPDF
    {
        public function Header() {

        }
        public function Footer() {
            $this->SetY(-10);
            $this->SetFont('times', 'I', 8);
            $this->Cell(0, 10, 'Pag. ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
        }
    }
    $autor = "STIS-BOLIVIA";
    $width = 216;
    $height = 270.9;
    $pageLayout = array($width, $height);

    $pdf = new MYPDF ('L', 'mm', $pageLayout, true, 'UTF-8', false);
    $pdf->SetCreator($autor);
    $pdf->SetAuthor($autor);
    $pdf->SetTitle("Reporte de asignaciones");
    $pdf->SetMargins(10, 10, 10, true);
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->SetFont('times', '', 10);
    $pdf->AddPage();
    $table = '
    <table border="0" cellpadding="1" cellspacing="2">
    <tr>
    <td colspan="20" align="center"><b>REPORTE DE ASIGNACIONES</b></td>
    </tr>
    <tr>
    <td colspan="6">Área: ' . $tituloArea . '</td>
    <td colspan="7">' . $fechaFormato . '</td>
    <td colspan="7" align="right">Fecha de impresión: ' . $fechaImpresion . '</td>
    </tr>
    </table>';
    $pdf->writeHTMLCell(0, 0, '', '', $table, 0, 1, 0, true, '', true);
    $pdf->SetFont('times', '', 9);
    $table = '
    <table border="0.5" cellpadding="2" cellspacing="0">
    <tr>
    <td colspan="2" align="center"><b>#</b></td>
    <td colspan="8" align="center"><b>Bien</b></td>
    <td colspan="11" align="center"><b>Descripción</b></td>
    <td colspan="7" align="center"><b>Código</b></td>
    <td colspan="11" align="center"><b>Usuario</b></td>
    <td colspan="5" align="center"><b>CI</b></td>
    <td colspan="5" align="center"><b>Inicio</b></td>
    <td colspan="5" align="center"><b>Fin</b></td>
    <td colspan="5" align="center"><b>Estado</b></td>
    </tr>';
    $nro = 1;
    if (count($listaAsignaciones) == 0) {
        $table .= '
        <tr>
        <td colspan="59" align="center">No se encontraron resultados</td>
        </tr>';
    }
    foreach ($listaAsignaciones as $key => $value) {
        $bienDetalle = isset($value['bienDetalle']) ? $value['bienDetalle'] : "Sin definir";
        $fechaInicial = isset($value['fechaInicial']) ? $value['fechaInicial']->format("d/m/y") : "Sin definir";
        $fechaFinal = isset($value['fechaFinal']) ? $value['fechaFinal']->format("d/m/y") : "Sin definir";
        $table .= '
        <tr>
        <td colspan="2" align="center">' . $nro . '</td>
        <td colspan="8" align="left">' . $bienDetalle . '</td>
        <td colspan="11" align="left">' . $value['producto'] . '</td>
        <td colspan="7" align="center">' . $value['codigoBarras'] . '</td>
        <td colspan="11" align="left">' . $value['apellidoPaterno'] . ' ' . $value['apellidoMaterno'] . ' ' . $value['nombre'] . '</td>
        <td colspan="5" align="center">' . $value['ci'] . '</td>
        <td colspan="5" align="center">' . $fechaInicial. '</td>
        <td colspan="5" align="center">' . $fechaFinal . '</td>
        <td colspan="5" align="center">' . $value['estado'] . '</td>
        </tr>';
        $nro++;
    }
    $table .= '</table>';
    $pdf->writeHTMLCell(0, 0, '', '', $table, 0, 1, 0, true, '', true);
    // $pdf->Output("reporte_asignaciones.pdf", "I");
    $content = $pdf->Output("", "S");
    $base64 = base64_encode($content);
    echo json_encode([
        "pdf" => $base64
    ]);
} else {
    header("Location: ../index.php");
}
