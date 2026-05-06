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
    // $idAsignacion = isset($_POST['idAsignacion']) ? $_POST['idAsignacion'] : 0;
    $idSeleccionados = isset($_POST['idSeleccionados']) ? json_decode($_POST['idSeleccionados']) : [];
    $preview = isset($_POST['preview']) ? $_POST['preview'] : 'NO';
    $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
    $listaProductos = array();
    if ($preview == 'NO') {
        foreach ($idSeleccionados as $idSeleccionado) {
            $idAsignacion = $idSeleccionado->idAsignacion;
            $sql = "SELECT 
                tp.producto, 
                tp.codigoBarras,
                tdd.bienDetalle,
                tu.nombre, 
                tu.apellidoPaterno, 
                tu.apellidoMaterno, 
                tu.ci,
                ta.area,
                tas.fechaInicial,
                tas.fechaFinal
            FROM tblAsignacion tas 
            LEFT JOIN tblProducto tp ON tp.idProducto = tas.idProducto 
            LEFT JOIN tblDepreciacionDetalle tdd ON tdd.idDepreciacionDetalle = tp.idDepreciacionDetalle
            LEFT JOIN tblUsuario tu ON tu.idUsuario = tas.idUsuario
            LEFT JOIN tblArea ta ON ta.idArea = tu.idArea
            WHERE tas.idAsignacion = ?";

            $params = array($idAsignacion);
            $query = sqlsrv_query($con, $sql, $params);
            $asignacion = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
            $fechaFinalFormato = isset($asignacion['fechaFinal']) ? $asignacion['fechaFinal']->format('d/m/Y') : "Sin definir";
            $asignacion['fechaFinal'] = $fechaFinalFormato;
            $listaProductos[] = $asignacion;
    }
    } else {
        $sql = "SELECT 
                tp.producto, 
                tp.codigoBarras,
                tdd.bienDetalle,
                tu.nombre, 
                tu.apellidoPaterno, 
                tu.apellidoMaterno, 
                tu.ci,
                ta.area,
                tas.fechaInicial,
                tas.fechaFinal
            FROM tblAsignacion tas 
            LEFT JOIN tblProducto tp ON tp.idProducto = tas.idProducto 
            LEFT JOIN tblDepreciacionDetalle tdd ON tdd.idDepreciacionDetalle = tp.idDepreciacionDetalle
            LEFT JOIN tblUsuario tu ON tu.idUsuario = tas.idUsuario
            LEFT JOIN tblArea ta ON ta.idArea = tu.idArea
            WHERE tas.estado = 'DEVUELTO' AND tas.fechaFinal BETWEEN ? AND ? AND tas.idUsuario = ?;";
            $params = array($fecha . ' 00:00:00', $fecha . ' 23:59:59', $usuario);
            $query = sqlsrv_query($con, $sql, $params);
            while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
                $fechaFinalFormato = isset($row['fechaFinal']) ? $row['fechaFinal']->format('d/m/Y') : "Sin definir";
                $row['fechaFinal'] = $fechaFinalFormato;
                $listaProductos[] = $row;
            }
    }
    if (count($listaProductos) > 1) {
        $mensaje1 = "de los siguientes activos";
        $mensaje2 = "Los activos";
    } else {
        $mensaje1 = "del siguiente activo";
        $mensaje2 = "El activo";
    }
    // Clase personalizada para el PDF
    class MYPDF extends TCPDF
    {
        public function Header()
        {
            $image_file = '../images/logoStisHorizontal.png';
            if (file_exists($image_file)) {
                // Ajustar los parámetros de la imagen
                // $this->Image($image_file, 15, 13, 23, 23, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            } else {
                // Si el archivo no existe, agregar un mensaje de error
                // $this->SetFont('times', 'B', 10);
                // $this->Cell(0, 10, '¡Logo no encontrado!', 0, false, 'C', 0, '', 0, false, 'M', 'M');
            }
        }

        public function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('times', 'I', 8);
            // $this->Cell(0, 10, 'Pag. ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
            // $this->Cell(0, 10, 'Oficina Central STIS, El Prado, edificio "16 de Julio", piso 17 oficina 1707, La Paz - Bolivia.', 'T', 1, 'C', 0, '', 0, true, 'T', 'M');
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
    if (count($listaProductos) > 0) {
        foreach ($listaProductos as $producto) {
            $apellidoPaterno = $producto['apellidoPaterno'];
            $apellidoMaterno = $producto['apellidoMaterno'];
            $nombre = $producto['nombre'];
            $ci = $producto['ci'];
            $area = $producto['area'];
        }
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
        <p>En la ciudad de La Paz, a ' . date('d') . ' de ' . $mesEspanol . ' de ' . date('Y') . ', se procede a la devolución de activos efectuada por:</p>
        <table border="0" cellpadding="5">
            <tr>
                <td width="30%"><b>Nombre Completo:</b></td>
                <td width="70%">' . $apellidoPaterno . ' ' . $apellidoMaterno . ' ' . $nombre . '</td>
            </tr>
            <tr>
                <td><b>C.I.:</b></td>
                <td>' . $ci . '</td>
            </tr>
            <tr>
                <td><b>Área:</b></td>
                <td>' . $area . '</td>
            </tr>
        </table>
        ';
    $html .= '
        <p>Se devolvieron los siguientes activos:</p>';

        $cont = 1;
        $html .= '
        <table border="0" cellpadding="3">
        <tr>
        <th style="text-align: left; border-bottom: 0.2px solid black;">#</th>
        <th colspan="3" style="text-align: left; border-bottom: 0.2px solid black;"><b>Bien</b></th>
        <th colspan="4" style="text-align: left; border-bottom: 0.2px solid black;"><b>Código</b></th>
        <th colspan="4" style="text-align: left; border-bottom: 0.2px solid black;"><b>Descripción</b></th>
        <th colspan="3" style="text-align: left; border-bottom: 0.2px solid black;"><b>F. Entrega</b></th>
        <th colspan="3" style="text-align: left; border-bottom: 0.2px solid black;"><b>F. Devolución</b></th>
        </tr>';
        foreach ($listaProductos as $asignacion) {
            // $fechaFinalFormato = isset($asignacion['fechaFinal']) ? $asignacion['fechaFinal']->format('d/m/Y') : "Sin definir";
            $apellidoPaterno = $asignacion['apellidoPaterno'];
            $apellidoMaterno = $asignacion['apellidoMaterno'];
            $nombre = $asignacion['nombre'];
            $ci = $asignacion['ci'];
            $area = $asignacion['area'];
            $html .= '
            <tr>
            <td>' . $cont . '</td>
            <td colspan="3">' . $asignacion['bienDetalle'] . '</td>
            <td colspan="4">' . $asignacion['codigoBarras'] . '</td>
            <td colspan="4">' . $asignacion['producto'] . '</td>
            <td colspan="3">' . $asignacion['fechaInicial']->format('d/m/Y') . '</td>
            <td colspan="3">' . $asignacion['fechaFinal'] . '</td>
            </tr>';
            $cont++;
        }
        $html .= '</table>';
// $html .= '
//         <p>Observaciones:</p>';
        $html .= '<p></p>

        <div class="firma">
            <table border="0" cellpadding="5">
                <tr>
                    <td width="50%" align="center">
                        <p>___________________________</p>
                        <p>DEVUELTO POR</p>
                        <p>' . $apellidoPaterno . ' ' . $apellidoMaterno . ' ' . $nombre . '</p>
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
    } else {
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
            <p>No se encontraron resultados.</p>
        </div>';
    }

    $pdf->writeHTML($html, true, false, true, false, '');
    if ($preview == 'NO') {
        $pdf->Output('acta_devolucion.pdf', 'I');
    } else {
        $content = $pdf->Output("", "S");
        $base64 = base64_encode($content);
        echo json_encode([
            "pdf" => $base64
        ]);
    }
} else {
    header("Location: ../index.php");
}
