<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    include("../conexion.php");
    include("../tcpdf/tcpdf.php");
    date_default_timezone_set("America/La_Paz");
    $fechaImpresion = date("d/m/Y H:i");
    $idUsuario = $_POST['idUsuario'];
    // para la consulta a la base de datos
    $listaAsignaciones = array();
    $sql = "SELECT tas.*, tp.producto, tp.codigoBarras, tdd.bienDetalle FROM tblAsignacion tas LEFT JOIN tblUsuario tu ON tu.idUsuario = tas.idUsuario LEFT JOIN tblProducto tp ON tas.idProducto = tp.idProducto LEFT JOIN tblDepreciacionDetalle tdd ON tdd.idDepreciacionDetalle = tp.idDepreciacionDetalle WHERE tu.idUsuario = $idUsuario ORDER BY tas.idAsignacion ASC;";
    $sqlUsuario = "SELECT tu.idUsuario, tu.nombre, tu.apellidoPaterno, tu.apellidoMaterno, tu.ci, tu.cargo FROM tblUsuario tu WHERE tu.idUsuario = $idUsuario;";
    $query = sqlsrv_query($con, $sql);
    $queryUsuario = sqlsrv_query($con, $sqlUsuario);
    while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $listaAsignaciones[] = $row;
    }
    $rowUsuario = sqlsrv_fetch_array($queryUsuario, SQLSRV_FETCH_ASSOC);
    $ciUsuario = $rowUsuario['ci'];
    $nombreUsuario = $rowUsuario['nombre'] . " " . $rowUsuario['apellidoPaterno'] . " " . $rowUsuario['apellidoMaterno'];
    $cargoUsuario = $rowUsuario['cargo'];
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

    $pdf = new MYPDF ('P', 'mm', $pageLayout, true, 'UTF-8', false);
    $pdf->SetCreator($autor);
    $pdf->SetAuthor($autor);
    $pdf->SetTitle("Reporte de asignaciones - $nombreUsuario");
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
    <td colspan="20" align="left">Nombre: ' . $nombreUsuario . '</td>
    </tr>
    <tr>
    <td colspan="20" align="left">CI: ' . $ciUsuario . '</td>
    </tr>
    <tr>
    <td colspan="20" align="left">Cargo: ' . $cargoUsuario . '</td>
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
    <td colspan="7" align="center"><b>Código</b></td>
    <td colspan="8" align="center"><b>Activo</b></td>
    <td colspan="11" align="center"><b>Descripción</b></td>
    <td colspan="5" align="center"><b>Inicio</b></td>
    <td colspan="5" align="center"><b>Fin</b></td>
    </tr>';
    $nro = 1;
    if (count($listaAsignaciones) == 0) {
        $table .= '<tr><td colspan="38" align="center">El usuario no tiene activos asignados.</td></tr>';
    } else {
        foreach ($listaAsignaciones as $key => $value) {
            $bienDetalle = $value['bienDetalle'] ?? "Sin definir";
            $fechaInicial = isset($value['fechaInicial']) ? $value['fechaInicial']->format("d/m/y") : "Sin definir";
            $fechaFinal = isset($value['fechaFinal']) ? $value['fechaFinal']->format("d/m/y") : "Sin definir";
            $table .= '
            <tr>
            <td colspan="2" align="center">' . $nro . '</td>
            <td colspan="7" align="center">' . $value['codigoBarras'] . '</td>
            <td colspan="8" align="left">' . $bienDetalle . '</td>
            <td colspan="11" align="left">' . $value['producto'] . '</td>
            <td colspan="5" align="center">' . $fechaInicial. '</td>
            <td colspan="5" align="center">' . $fechaFinal . '</td>
            </tr>';
            $nro++;
        }
    }
    $table .= '</table>';
    $pdf->writeHTMLCell(0, 0, '', '', $table, 0, 1, 0, true, '', true);
    $pdf->Output("reporte_asignaciones_$nombreUsuario.pdf", "I");
} else {
    header("Location: ../index.php");
}