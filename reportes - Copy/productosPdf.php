<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    include("../conexion.php");
    include("../tcpdf/tcpdf.php");
    date_default_timezone_set("America/La_Paz");
    $fechaImpresion = date("d/m/Y H:i");
    // para la consulta a la base de datos
    $listaProductos = array();
    $sql = "SELECT tp.*,dp.bien, dp.coeficiente FROM tblProducto tp LEFT JOIN tblDepreciacion dp ON tp.idDepreciacion = dp.idDepreciacion ORDER BY fechaIngreso ASC;";
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
    <td colspan="20" align="center"><b>REPORTE DE PRODUCTOS</b></td>
    </tr>
    <tr>
    <td colspan="20" align="left">Fecha de impresión: ' . $fechaImpresion . '</td>
    </tr>
    </table>';
    $pdf->writeHTMLCell(0, 0, '', '', $table, 0, 1, 0, true, '', true);
    $pdf->SetFont('times', '', 9);
    $table = '
    <table border="0.5" cellpadding="2" cellspacing="0">
    <tr>
    <td colspan="2" align="center"><b>#</b></td>
    <td colspan="10" align="center"><b>Producto</b></td>
    <td colspan="8" align="center"><b>Cód. Barras</b></td>
    <td colspan="10" align="center"><b>Tipo de Producto</b></td>
    <td colspan="6" align="center"><b>Fecha de Ingreso</b></td>
    <td colspan="7" align="center"><b>Costo de Adquisición</b></td>
    <td colspan="7" align="center"><b>Depreciación Anual</b></td>
    <td colspan="7" align="center"><b>Costo Ajustado</b></td>
    </tr>';
    $nro = 1;

    foreach ($listaProductos as $key => $value) {
        $bien = $value['bien'] ?? "Sin definir";
        $coeficiente = $value['coeficiente'];
        $fechaIngreso = $value['fechaIngreso']->format("d/m/y");
        $anioIngreso = $value['fechaIngreso']->format("Y");
        $anioActual = date("Y");
        $diferenciaAnios = $anioActual - $anioIngreso + 1;
        $costoAjustado = $value['costoAdquisicion'] - ($value['costoAdquisicion'] * $coeficiente * $diferenciaAnios);
        if ($costoAjustado < 0) {
            $costoAjustado = 0;
        }
        $table .= '
        <tr>
        <td colspan="2" align="center">' . $nro . '</td>
        <td colspan="10" align="left">' . $value['producto'] . '</td>
        <td colspan="8" align="center">' . $value['codigoBarras'] . '</td>
        <td colspan="10" align="center">' . $bien . '</td>
        <td colspan="6" align="center">' . $fechaIngreso . '</td>
        <td colspan="7" align="right">' . number_format($value['costoAdquisicion'], 2) . '</td>
        <td colspan="7" align="right">' . number_format($value['costoAdquisicion'] * $coeficiente, 2) . '</td>
        <td colspan="7" align="right">' . number_format($costoAjustado, 2) . '</td>
        </tr>';
        $nro++;
    }

    $table .= '</table>';
    $pdf->writeHTMLCell(0, 0, '', '', $table, 0, 1, 0, true, '', true);
    $pdf->lastPage();
    $pdf->Output("reporte_productos.pdf", "I");
} else {
    header("Location: ../index.php");
}
