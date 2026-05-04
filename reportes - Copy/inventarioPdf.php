<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    include("../conexion.php");
    include("../tcpdf/tcpdf.php");
    date_default_timezone_set("America/La_Paz");
    $fechaImpresion = date("d/m/Y H:i");
    // para la consulta a la base de datos
    $listaInventario = array();
    $sql = "SELECT tu.apellidoPaterno, tu.apellidoMaterno, tu.nombre, tu.ci, tp.producto, tp.codigoBarras, tu2.apellidoPaterno AS apellidoPaternoRevisor, tu2.apellidoMaterno as apellidoMaternoRevisor, tu2.nombre AS nombreRevisor, tu2.ci AS ciRevisor, ti.observacion, ti.fecha FROM tblInventario ti LEFT JOIN tblAsignacion ta ON ta.idAsignacion = ti.idAsignacion LEFT JOIN tblUsuario tu ON tu.idUsuario = ta.idUsuario LEFT JOIN tblProducto tp ON tp.idProducto = ta.idProducto LEFT JOIN tblUsuario tu2 ON tu2.idUsuario = ti.idUsuarioCreador ORDER BY ti.fecha DESC;";
    $query = sqlsrv_query($con, $sql);
    while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $listaInventario[] = $row;
    }
    // para la generacion del pdf
    class MYPDF extends TCPDF {
        public function Header() {}
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

    $pdf = new MYPDF('P', 'mm', $pageLayout, true, 'UTF-8', false);
    $pdf->SetCreator($autor);
    $pdf->setAuthor($autor);
    $pdf->SetTitle("Reporte de inventario");
    $pdf->SetMargins(10, 10, 10, true);
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->SetFont('times', '', 10);
    $pdf->AddPage();
    $table = '
    <table border="0" cellpadding="1" cellspacing="2">
    <tr>
    <td colspan="20" align="center"><b>REPORTE DE INVENTARIO</b></td>
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
    <td colspan="10" align="center"><b>Usuario</b></td>
    <td colspan="6" align="center"><b>CI</b></td>
    <td colspan="10" align="center"><b>Producto</b></td>
    <td colspan="7" align="center"><b>Cód. Barras</b></td>
    <td colspan="10" align="center"><b>Revisor</b></td>
    <td colspan="6" align="center"><b>CI</b></td>
    <td colspan="12" align="center"><b>Observación</b></td>
    <td colspan="5" align="center"><b>Fecha</b></td>
    </tr>';
    $nro = 1;
    foreach ($listaInventario as $key => $value) {
        $fechaInventario = $value['fecha']->format("d/m/y");
        $table .= '
        <tr>
        <td colspan="2" align="center">' . $nro . '</td>
        <td colspan="10" align="left">' . $value['apellidoPaterno'] . ' ' . $value['apellidoMaterno'] . ' ' . $value['nombre'] . '</td>
        <td colspan="6" align="center">' . $value['ci'] . '</td>
        <td colspan="10" align="left">' . $value['producto'] . '</td>
        <td colspan="7" align="center">' . $value['codigoBarras'] . '</td>
        <td colspan="10" align="left">' . $value['apellidoPaternoRevisor'] . ' ' . $value['apellidoMaternoRevisor'] . ' ' . $value['nombreRevisor'] . '</td>
        <td colspan="6" align="center">' . $value['ciRevisor'] . '</td>
        <td colspan="12" align="left">' . $value['observacion'] . '</td>
        <td colspan="5" align="center">' . $fechaInventario . '</td>
        </tr>';
    }
    $table .= '</table>';
    $pdf->writeHTMLCell(0, 0, '', '', $table, 0, 1, 0, true, '', true);
    $pdf->Output("reporte_inventario.pdf", "I");
} else {
    header("Location: ../index.php");
}