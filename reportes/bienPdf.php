<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include("../conexion.php");
    include("../tcpdf/tcpdf.php");
    date_default_timezone_set("America/La_Paz");
    $fechaImpresion = date("d/m/Y H:i");
    $idBien = $_POST['idBien'];
    if ($idBien == 0) {
        $sql = "SELECT TOP 1 * FROM tblProducto tp LEFT JOIN tblDepreciacion td ON tp.idDepreciacion = td.idDepreciacion LEFT JOIN tblDepreciacionDetalle tdd ON tp.idDepreciacionDetalle = tdd.idDepreciacionDetalle ORDER BY idProducto DESC;";
    } else {
        $sql = "SELECT * FROM tblProducto tp LEFT JOIN tblDepreciacion td ON tp.idDepreciacion = td.idDepreciacion LEFT JOIN tblDepreciacionDetalle tdd ON tp.idDepreciacionDetalle = tdd.idDepreciacionDetalle WHERE tp.idProducto = $idBien;";
    }
    $query = sqlsrv_query($con, $sql);
    $row = sqlsrv_fetch_array($query);
    $marca = isset($row['marca']) ? $row['marca'] : '';
    $tipoAdquisicion = isset($row['tipoAdquisicion']) ? $row['tipoAdquisicion'] : '';
    class MYPDF extends TCPDF {
        public function Header() {
            $image_file = '../images/logoStisHorizontal.png';
            if (file_exists($image_file)) {
                // Ajustar los parámetros de la imagen
                $this->Image($image_file, 20, 13, 15, 15, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            } else {
                // Si el archivo no existe, agregar un mensaje de error
                $this->SetFont('times', 'B', 10);
                $this->Cell(0, 10, '¡Logo no encontrado!', 0, false, 'C', 0, '', 0, false, 'M', 'M');
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
    $pdf->setTitle('Reporte de Bien');
    $pdf->setSubject('Reporte de Bien');
    $pdf->setKeywords('Reporte, Bien');
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
    <td colspan="20" align="center"><b>ACTA DE REGISTRO</b></td>
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
    <td colspan="5"></td>
    <td colspan="5" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">LUGAR:</td>
    <td colspan="5" align="center" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">STIS</td>
    <td colspan="5"></td>
    </tr>
    <tr>
    <td colspan="5"></td>
    <td colspan="5" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">FECHA:</td>
    <td colspan="5" align="center" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">' . $row['fechaIngreso']->format('d/m/Y') . '</td>
    <td colspan="5"></td>
    </tr>
    <tr>
    <td colspan="5"></td>
    <td colspan="5" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">RESPONSABLE:</td>
    <td colspan="5" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;"></td>
    <td colspan="5"></td>
    </tr>
    <tr>
    <td colspan="20" style="font-size: 5px;"></td>
    </tr>
    <tr>
    <td colspan="20">En fecha ' . $row['fechaIngreso']->format('d/m/Y') . ', se realizó el ingreso de bienes para la empresa, ya culminando con el informa de conformidad del área solicitante.</td>
    </tr>
    <tr>
    <td colspan="20" style="font-size: 3px;"></td>
    </tr>
    <tr>
    <td colspan="20">Se realizó el ingreso del bien bajo el siguiente detalle:</td>
    </tr>
    <tr>
    <td colspan="20" style="font-size: 5px;"></td>
    </tr>
    <tr>
    <td colspan="3"></td>
    <td colspan="6" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">TIPO DE BIEN:</td>
    <td colspan="8" align="center" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">' . $row['bien'] . '</td>
    <td colspan="3"></td>
    </tr>
    <tr>
    <td colspan="3"></td>
    <td colspan="6" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000;">BIEN:</td>
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
    <td colspan="20" style="font-size: 20px;"></td>
    </tr>
    <tr>
    <td colspan="2"></td>
    <td colspan="8" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000; font-size: 80px;"></td>
    <td colspan="8" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000; font-size: 80px;"></td>
    <td colspan="2"></td>
    </tr>
    <tr>
    <td colspan="2"></td>
    <td colspan="8" align="center" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000; font-size: 10px;">Responsable de registro de activo</td>
    <td colspan="8" align="center" style="border-bottom: 0.3px solid #000; border-right: 0.3px solid #000; border-left: 0.3px solid #000; border-top: 0.3px solid #000; font-size: 10px;">Encargado de activos fijos</td>
    <td colspan="2"></td>
    </tr>
    <tr>
    <td colspan="20" style="font-size: 5px;" style="border-bottom: 0.3px dashed #000;"></td>
    </tr>
    </table>';
    // $html .='
    // <tr>
    // <td colspan="4" align="left"><b>Bien:</b></td>
    // <td colspan="16" align="left">' . $row['producto'] . '</td>
    // </tr>
    // <tr>
    // <td colspan="4" align="left"><b>Código:</b></td>
    // <td colspan="6" align="left">' . $row['codigoBarras'] . '</td>
    // <td colspan="6" align="left"><b>Fecha de Ingreso:</b></td>
    // <td colspan="4" align="left">' . $row['fechaIngreso']->format('d/m/Y') . '</td>
    // </tr>
    // <tr>
    // <td colspan="4" align="left"><b>Costo Adq.:</b></td>
    // <td colspan="16" align="left">' . $row['costoAdquisicion'] . '</td>
    // </tr>
    // </table>';
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    $pdf->write2DBarcode(
        $row['codigoBarras'],           // Contenido del QR
        'QRCODE,H',           // Tipo de código (QR con corrección H)
        $pdf->GetX() + 10,                   // Posición X
        $pdf->GetY() + 10,                   // Posición Y
        40,                   // Ancho
        40,                   // Alto
        array(),              // Estilo (array vacío = por defecto)
        'N'                   // Tipo de celda (N = no imprimir texto)
    );
    $pdf->multiCell(0, 10, 'Recortar desde la línea punteada y pegar el código QR en los sitios autorizados del bien.', 0, 'L', false, 1, $pdf->GetX() + 60, $pdf->GetY() - 30);
    $pdf->Output('bien.pdf', 'I');
    
} else {
    echo "No tienes permiso para acceder a esta página.";
}