<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    include("../conexion.php");
    include("../tcpdf/tcpdf.php");
    date_default_timezone_set("America/La_Paz");
    $fechaImpresion = date("d/m/Y H:i");
    // Función para convertir el mes a español
    function mesEnEspanol($mes)
    {
        $meses = array(
            'January' => 'Enero',
            'February' => 'Febrero',
            'March' => 'Marzo',
            'April' => 'Abril',
            'May' => 'Mayo',
            'June' => 'Junio',
            'July' => 'Julio',
            'August' => 'Agosto',
            'September' => 'Septiembre',
            'October' => 'Octubre',
            'November' => 'Noviembre',
            'December' => 'Diciembre'
        );
        return $meses[$mes];
    }
    // Obtener el ID de la asignación
    $idAsignacion = isset($_POST['idAsignacion']) ? $_POST['idAsignacion'] : 0;

    // Consulta para obtener los datos de la asignación
    $sql = "SELECT 
                tp.producto, 
                tp.codigoBarras,
                tu.nombre, 
                tu.apellidoPaterno, 
                tu.apellidoMaterno, 
                tu.ci,
                ta.area,
                tas.fechaInicial,
                tas.fechaFinal
            FROM tblAsignacion tas 
            LEFT JOIN tblProducto tp ON tp.idProducto = tas.idProducto 
            LEFT JOIN tblUsuario tu ON tu.idUsuario = tas.idUsuario
            LEFT JOIN tblArea ta ON ta.idArea = tu.idArea
            WHERE tas.idAsignacion = ?";

    $params = array($idAsignacion);
    $query = sqlsrv_query($con, $sql, $params);
    $asignacion = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);

    // Clase personalizada para el PDF
    class MYPDF extends TCPDF
    {
        public function Header()
        {
            // $this->SetFont('times', 'B', 12);
            // $this->Cell(0, 10, 'ACTA DE DEVOLUCIÓN DE ACTIVO', 0, false, 'C', 0, '', 0, false, 'M', 'M');
            // $this->Ln(10);
        }

        public function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('times', 'I', 8);
            $this->Cell(0, 10, 'Pag. ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
        }
    }

    // Crear el PDF
    $pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetCreator('STIS-BOLIVIA');
    $pdf->SetAuthor('STIS-BOLIVIA');
    $pdf->SetTitle('Acta de Devolución');
    $pdf->SetMargins(15, 25, 15);
    $pdf->SetAutoPageBreak(TRUE, 15);
    $pdf->SetFont('times', '', 12);
    $pdf->AddPage();

    $mesActual = date('F');
    $mesEspanol = mesEnEspanol($mesActual);

    // Contenido del acta
    $html = '
    <style>
        .titulo { font-size: 14px; font-weight: bold; }
        .texto { font-size: 12px; }
        .firma { margin-top: 50px; }
    </style>
    <table border="0">
    <tr>
    <td align="center"><b>ACTA DE DEVOLUCIÓN DE ACTIVO</b></td>
    </tr>
    </table>
    <div class="texto">
        <p>En la ciudad de La Paz, a ' . date('d') . ' de ' . $mesEspanol . ' de ' . date('Y') . ', se procede a la devolución del siguiente activo:</p>
        
        <table border="0" cellpadding="5">
            <tr>
                <td width="30%"><b>Producto:</b></td>
                <td width="70%">' . $asignacion['producto'] . '</td>
            </tr>
            <tr>
                <td><b>Código de Barras:</b></td>
                <td>' . $asignacion['codigoBarras'] . '</td>
            </tr>
            <tr>
                <td><b>Descripción:</b></td>
                <td></td>
            </tr>
            <tr>
                <td><b>Fecha de Entrega:</b></td>
                <td>' . $asignacion['fechaInicial']->format('d/m/Y') . '</td>
            </tr>
            <tr>
                <td><b>Fecha de Devolución:</b></td>
                <td>' . $asignacion['fechaFinal']->format('d/m/Y') . '</td>
            </tr>
            <tr>
                <td><b>Estado del Activo:</b></td>
                <td></td>
            </tr>
        </table>

        <p>El activo es devuelto por:</p>
        
        <table border="0" cellpadding="5">
            <tr>
                <td width="30%"><b>Nombre Completo:</b></td>
                <td width="70%">' . $asignacion['apellidoPaterno'] . ' ' . $asignacion['apellidoMaterno'] . ' ' . $asignacion['nombre'] . '</td>
            </tr>
            <tr>
                <td><b>C.I.:</b></td>
                <td>' . $asignacion['ci'] . '</td>
            </tr>
            <tr>
                <td><b>Área:</b></td>
                <td>' . $asignacion['area'] . '</td>
            </tr>
        </table>

        <p>Observaciones:</p>
        <p></p>

        <div class="firma">
            <table border="0" cellpadding="5">
                <tr>
                    <td width="50%" align="center">
                        <p>___________________________</p>
                        <p>DEVUELTO POR</p>
                        <p>' . $asignacion['apellidoPaterno'] . ' ' . $asignacion['apellidoMaterno'] . ' ' . $asignacion['nombre'] . '</p>
                    </td>
                    <td width="50%" align="center">
                        <p>___________________________</p>
                        <p>RECIBIDO POR</p>
                        <p>Nombre y Cargo</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('acta_devolucion.pdf', 'I');
} else {
    header("Location: ../index.php");
}
