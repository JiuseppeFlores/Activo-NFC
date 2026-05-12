<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include("../conexion.php");
    include("../tcpdf/tcpdf.php");
    date_default_timezone_set("America/La_Paz");
    $fechaImpresion = date("d/m/Y H:i");
    $idBien = $_POST['idBien'];
    if ($idBien == 0) {
        $sql = "SELECT TOP 1 * FROM tblProducto tp LEFT JOIN tblDepreciacion td ON tp.idDepreciacion = td.idDepreciacion LEFT JOIN tblDepreciacionDetalle tdd ON tp.idDepreciacionDetalle = tdd.idDepreciacionDetalle LEFT JOIN tblUsuario tu ON tu.idUsuario = tp.idUsuarioResponsable ORDER BY idProducto DESC;";
    } else {
        $sql = "SELECT * FROM tblProducto tp LEFT JOIN tblDepreciacion td ON tp.idDepreciacion = td.idDepreciacion LEFT JOIN tblDepreciacionDetalle tdd ON tp.idDepreciacionDetalle = tdd.idDepreciacionDetalle LEFT JOIN tblUsuario tu ON tu.idUsuario = tp.idUsuarioResponsable WHERE tp.idProducto = $idBien;";
    }
    $query = sqlsrv_query($con, $sql);
    $row = sqlsrv_fetch_array($query);
    $marca = isset($row['marca']) ? $row['marca'] : '';
    $fechaModificacion = isset($row['fechaModificacion']) ? $row['fechaModificacion']->format('d/m/Y') : '-';
    $tipoAdquisicion = isset($row['tipoAdquisicion']) ? $row['tipoAdquisicion'] : '';
    $usuarioResponsable = isset($row['nombre']) ? strtoupper($row['nombre']) . ' ' . strtoupper($row['apellidoPaterno']) . ' ' . strtoupper($row['apellidoMaterno']) . '<br>CI: ' . $row['ci'] : '';
    class MYPDF extends TCPDF {
        public function Header() {
            $image_file = '../images/logoStisHorizontal.png';
            if (file_exists($image_file)) {
                // Ajustar los parámetros de la imagen
                // $this->Image($image_file, 20, 13, 15, 15, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            } else {
                // Si el archivo no existe, agregar un mensaje de error
                // $this->SetFont('times', 'B', 10);
                // $this->Cell(0, 10, '¡Logo no encontrado!', 0, false, 'C', 0, '', 0, false, 'M', 'M');
            }
        }

        public function Footer() {
            $this->SetY(-15);
            $this->SetFont('times', 'I', 8);
            $this->Cell(0, 10, 'Pag. ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
        }
    }
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->setCreator(PDF_CREATOR);
    $pdf->setAuthor('Activo Empresa');
    $pdf->setTitle('Reporte de Disponibilidad');
    $pdf->setSubject('Reporte de Disponibilidad');
    $pdf->setKeywords('Reporte, Disponibilidad');
    $pdf->setPrintHeader(true);
    $pdf->setPrintFooter(false);
    $pdf->SetMargins(20, 20, 20);
    $pdf->AddPage();
    $pdf->SetFont('times', '', 12);
    // $pdf->Cell(0, 10, 'Reporte de Bien', 0, 1, 'C');
    // $pdf->Cell(0, 10, 'Fecha de Impresión: ' . $fechaImpresion, 0, 1, 'C');
    // $pdf->Cell(0, 10, 'Bien: ' . $row['producto'], 0, 1, 'C');
    $html = '
    <table border="0" cellpadding="1" cellspacing="2">
    <tr>
    <td colspan="20" align="center"><b>REPORTE DE DISPONIBILIDAD DE ACTIVO</b></td>
    </tr>
    <tr>
    <td colspan="20" style="border-bottom: 0.3px solid #000; font-size: 5px;"></td>
    </tr>
    <tr>
    <td colspan="20"></td>
    </tr>
    </table>';
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    $pdf->setFont('times', '', 10);
    $html = '
    <table border="0" cellpadding="3">
    <tr>
    <td colspan="4"></td>
    <td colspan="5" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">LUGAR:</td>
    <td colspan="6" align="center" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">T.S.J.M.</td>
    <td colspan="4"></td>
    </tr>
    <tr>
    <td colspan="4"></td>
    <td colspan="5" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">FECHA INGRESO:</td>
    <td colspan="6" align="center" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">' . $row['fechaIngreso']->format('d/m/Y') . '</td>
    <td colspan="4"></td>
    </tr>
    <tr>
    <td colspan="4"></td>
    <td colspan="5" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">RESPONSABLE:</td>
    <td colspan="6" align="center" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">' . $usuarioResponsable . '</td>
    <td colspan="4"></td>
    </tr>
    <tr>
    <td colspan="20" style="font-size: 5px;"></td>
    </tr>
    <tr>
    <td colspan="20">En fecha ' . $fechaModificacion . ', se realizó el cambio de disponibilidad del activo a "INACTIVO".</td>
    </tr>
    <tr>
    <td colspan="20" style="font-size: 3px;"></td>
    </tr>
    <tr>
    <td colspan="20">Se realizó el cambio de disponibilidad del activo bajo el siguiente detalle:</td>
    </tr>
    <tr>
    <td colspan="20" style="font-size: 5px;"></td>
    </tr>
    <tr>
    <td colspan="3"></td>
    <td colspan="6" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">TIPO DE ACTIVO:</td>
    <td colspan="8" align="center" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">' . $row['bien'] . '</td>
    <td colspan="3"></td>
    </tr>
    <tr>
    <td colspan="3"></td>
    <td colspan="6" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">ACTIVO:</td>
    <td colspan="8" align="center" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">' . $row['bienDetalle'] . '</td>
    <td colspan="3"></td>
    </tr>
    <tr>
    <td colspan="3"></td>
    <td colspan="6" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">MARCA:</td>
    <td colspan="8" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;" align="center">' . $marca . '</td>
    <td colspan="3"></td>
    </tr>
    <tr>
    <td colspan="3"></td>
    <td colspan="6" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">DESCRIPCIÓN:</td>
    <td colspan="8" align="center" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">' . $row['producto'] . '</td>
    <td colspan="3"></td>
    </tr>
    <tr>
    <td colspan="3"></td>
    <td colspan="6" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">TIPO DE ADQUISICIÓN:</td>
    <td colspan="8" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;" align="center">' . $tipoAdquisicion . '</td>
    <td colspan="3"></td>
    </tr>
    <tr>
    <td colspan="3"></td>
    <td colspan="6" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">FECHA DE ADQUISICIÓN:</td>
    <td colspan="8" align="center" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">' . $row['fechaIngreso']->format('d/m/Y') . '</td>
    <td colspan="3"></td>
    </tr>
    <tr>
    <td colspan="3"></td>
    <td colspan="6" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">COSTO ADQUISICIÓN:</td>
    <td colspan="8" align="center" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">' . $row['costoAdquisicion'] . ' Bs.</td>
    <td colspan="3"></td>
    </tr>
    <tr>
    <td colspan="3"></td>
    <td colspan="6" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">OBSERVACIÓN:</td>
    <td colspan="8" align="center" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">' . $row['observacion'] . '</td>
    <td colspan="3"></td>
    </tr>
    <tr>
    <td colspan="20" style="font-size: 20px;"></td>
    </tr>
    </table>';
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    $pdf->Output('bien.pdf', 'I');
    
} else {
    echo "No tienes permiso para acceder a esta página.";
}