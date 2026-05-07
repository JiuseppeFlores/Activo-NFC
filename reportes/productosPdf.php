<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    include("../conexion.php");
    include("../tcpdf/tcpdf.php");
    date_default_timezone_set("America/La_Paz");
    $fechaImpresion = date("d/m/Y H:i");
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];
    $tipoBien = $_POST['tipoBien'];
    $bien = $_POST['bien'];
    if ($fechaInicio == "" || $fechaFin == "") {
        $fechaInicio = "1900-01-01";
        $fechaFin = date("Y-m-d");
        $fechaFormato = "";
    } else {
        $fechaFormato = "Fecha de ingreso: " . date("d/m/Y", strtotime($fechaInicio)) . " al " . date("d/m/Y", strtotime($fechaFin));
    }
    $sql = "SELECT * FROM tblDepreciacion WHERE estado=1;";
    $query = sqlsrv_query($con, $sql);
    $listaTipoBien = array();
    while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $listaTipoBien[$row['idDepreciacion']] = $row['bien'];
    }
    $sql = "SELECT * FROM tblDepreciacionDetalle;";
    $query = sqlsrv_query($con, $sql);
    $listaBien = array();
    while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $listaBien[$row['idDepreciacionDetalle']] = $row['bienDetalle'];
    }
    $sqlTipoBien = "";
    $sqlBien = "";
    $tipoBienFormato = "Tipo de bien: Todos";
    $bienFormato = "Bien: Todos";
    if ($tipoBien != "") {
        $sqlTipoBien = " AND tp.idDepreciacion = '$tipoBien'";
        $tipoBienFormato = "Tipo de bien: " . $listaTipoBien[$tipoBien];
    }
    if ($bien != "") {
        $sqlBien = " AND tp.idDepreciacionDetalle = '$bien'";
        $bienFormato = "Bien: " . $listaBien[$bien];
    }
    // para la consulta a la base de datos
    $listaProductos = array();
    $sql = "SELECT tp.*,dp.bien, dp.coeficiente FROM tblProducto tp LEFT JOIN tblDepreciacion dp ON tp.idDepreciacion = dp.idDepreciacion WHERE tp.fechaIngreso BETWEEN '$fechaInicio' AND '$fechaFin' $sqlTipoBien $sqlBien ORDER BY fechaIngreso ASC;";
    $query = sqlsrv_query($con, $sql);
    while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $listaProductos[] = $row;
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
    $pdf->SetTitle("Reporte de productos");
    $pdf->SetMargins(10, 10, 10, true);
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->SetFont('times', '', 10);
    $pdf->AddPage();
    $table = '
    <table border="0" cellpadding="1" cellspacing="2">
    <tr>
    <td colspan="20" align="center"><b>REPORTE DE ACTIVOS</b></td>
    </tr>
    <tr>
    <td colspan="10">' . $fechaFormato . '</td>
    <td colspan="10" align="right">Fecha de impresión: ' . $fechaImpresion . '</td>
    </tr>
    <tr>
    <td colspan="8">' . $tipoBienFormato . '</td>
    <td colspan="12" align="left">' . $bienFormato . '</td>
    </tr>
    </table>';
    $pdf->writeHTMLCell(0, 0, '', '', $table, 0, 1, 0, true, '', true);
    $pdf->SetFont('times', '', 8);
    $table = '
    <table border="0.5" cellpadding="2" cellspacing="0">
    <thead>
    <tr>
    <th colspan="2" align="center"><b>#</b></th>
    <th colspan="8" align="center"><b>Código</b></th>
    <th colspan="10" align="center"><b>Tipo de Activo</b></th>
    <th colspan="10" align="center"><b>Activo</b></th>
    <th colspan="10" align="center"><b>Descripción</b></th>
    <th colspan="6" align="center"><b>Fecha de Ingreso</b></th>
    <th colspan="7" align="center"><b>Costo de Adquisición</b></th>
    <th colspan="7" align="center"><b>Depreciación Anual</b></th>
    <th colspan="7" align="center"><b>Costo Ajustado</b></th>
    </tr>
    </thead>
    <tbody>';
    $nro = 1;
    if (count($listaProductos) == 0) {
        $table .= '
        <tr>
        <td colspan="67" align="center">No se encontraron resultados</td>
        </tr>';
    }
    foreach ($listaProductos as $key => $value) {
        $tipoBien = $value['bien'] ?? "Sin definir";
        $bien = $listaBien[$value['idDepreciacionDetalle']] ?? "Sin definir";
        $coeficiente = $value['coeficiente'];
        $fechaIngreso = $value['fechaIngreso']->format("d/m/y");
        $anioIngreso = $value['fechaIngreso']->format("Y");
        $anioActual = date("Y");
        $diferenciaAnios = $anioActual - $anioIngreso + 1;
        $costoAjustado = $value['costoAdquisicion'] - ($value['costoAdquisicion'] * $coeficiente * $diferenciaAnios);
        if ($costoAjustado < 0 || $costoAjustado == 0) {
            $costoAjustado = 1;
        }
        $table .= '
        <tr>
        <td colspan="2" align="center">' . $nro . '</td>
        <td colspan="8" align="center">' . $value['codigoBarras'] . '</td>
        <td colspan="10" align="left">' . $tipoBien . '</td>
        <td colspan="10" align="left">' . $bien . '</td>
        <td colspan="10" align="left">' . $value['producto'] . '</td>
        <td colspan="6" align="center">' . $fechaIngreso . '</td>
        <td colspan="7" align="right">' . number_format($value['costoAdquisicion'], 2) . '</td>
        <td colspan="7" align="right">' . number_format($value['costoAdquisicion'] * $coeficiente, 2) . '</td>
        <td colspan="7" align="right">' . number_format($costoAjustado, 2) . '</td>
        </tr>';
        $nro++;
    }

    $table .= '</tbody></table>';
    $pdf->writeHTMLCell(0, 0, '', '', $table, 0, 1, 0, true, '', true);
    $pdf->lastPage();
    // $pdf->Output("reporte_productos.pdf", "I");
    $content = $pdf->Output("", "S");
    $base64 = base64_encode($content);
    echo json_encode([
        "pdf" => $base64
    ]);
} else {
    header("Location: ../index.php");
}
