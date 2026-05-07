<?php 
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    include("../conexion.php");
    include("../tcpdf/tcpdf.php");
    date_default_timezone_set("America/La_Paz");
    $fechaImpresion = date("d/m/Y H:i");
    $anios = isset($_POST['anios']) ? $_POST['anios'] : 1;
    $fechaActual = new DateTime();
    $fechaActual->modify("+$anios years");
    $fechaActualFormato = $fechaActual->format('Y-m-d H:i:s');
    $fechaEstimacion = $fechaActual->format('d/m/Y');
    $gestionEstimacion = $fechaActual->format('Y');

    // ahroa para la constula sql
    $sql = "SELECT tp.idProducto, tp.producto, tp.codigoBarras, YEAR(tp.fechaIngreso) gestion, tp.costoAdquisicion, td.coeficiente, td.vidaUtil, (td.vidaUtil - DATEDIFF(YEAR, tp.fechaIngreso, '$fechaActualFormato')) as tiempoRestante, tdd.bienDetalle FROM tblProducto tp LEFT JOIN tblDepreciacion td ON td.idDepreciacion = tp.idDepreciacion LEFT JOIN tblDepreciacionDetalle tdd ON tp.idDepreciacionDetalle = tdd.idDepreciacionDetalle WHERE tp.idProducto IS NOT NULL AND tp.idDepreciacion IS NOT NULL AND tp.idDepreciacionDetalle IS NOT NULL ORDER BY gestion ASC;";
    // echo $sql;
    $query = sqlsrv_query($con, $sql);
    $listaDepreciacion = array();
    while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $listaDepreciacion[] = $row;
    }
    $listaDepreciacionFiltrada = array();
    foreach ($listaDepreciacion as $key => $value) {
        if ($value['tiempoRestante'] < 1) {
            $listaDepreciacionFiltrada[] = $value;
        }
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
    $pdf->SetTitle('Reporte de Depreciación');
    $pdf->SetMargins(10, 15, 10, true);
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->SetFont('times', '', 10);
    $pdf->AddPage();
    $table = '
    <table border="0" cellpadding="1" cellspacing="2">
    <tr>
    <td colspan="20" align="center"><b>REPORTE DE DEPRECIACIÓN</b></td>
    </tr>
    <tr>
    <td colspan="10" align="left">Gestión de estimación: ' . $gestionEstimacion . '</td>
    <td colspan="10" align="right">Fecha de impresión: ' . $fechaImpresion . '</td>
    </tr>
    </table>';
    $pdf->writeHTMLCell(0, 0, '', '', $table, 0, 1, 0, true, '', true);
    $pdf->SetFont('times', '', 9);
    $table = '
    <table border="0.5" cellpadding="2" cellspacing="0">
    <tr>
    <td colspan="2" align="center"><b>#</b></td>
    <td colspan="7" align="center"><b>Código</b></td>
    <td colspan="8" align="center"><b>Activo</b></td>
    <td colspan="11" align="center"><b>Descripción</b></td>
    <td colspan="5" align="center"><b>Costo Adq.</b></td>
    <td colspan="5" align="center"><b>Año Ingreso</b></td>
    <td colspan="5" align="center"><b>Vida Útil</b></td>
    </tr>';
    if (count($listaDepreciacionFiltrada) == 0) {
        $table .= '<tr><td colspan="38 align="center">No se encontraron resultados</td></tr>';
    }
    $nro = 1;
    foreach($listaDepreciacionFiltrada as $key => $value) {
        $table .= '
        <tr>
        <td colspan="2" align="center">' . $nro . '</td>
        <td colspan="7" align="center">' . $value['codigoBarras'] . '</td>
        <td colspan="8" align="left">' . $value['bienDetalle'] . '</td>
        <td colspan="11" align="left">' . $value['producto'] . '</td>
        <td colspan="5" align="center">' . number_format($value['costoAdquisicion'], 2) . '</td>
        <td colspan="5" align="center">' . $value['gestion'] . '</td>
        <td colspan="5" align="center">' . $value['vidaUtil'] . ' años</td>
        </tr>';
        $nro++;
    }
    $table .= '</table>';
    $pdf->writeHTMLCell(0, 0, '', '', $table, 0, 1, 0, true, '', true);
    $content = $pdf->Output("", "S");
    $base64 = base64_encode($content);
    echo json_encode([
        "pdf" => $base64
    ]);
} else {
    echo "No se puede acceder directamente a este archivo.";
    exit;
}