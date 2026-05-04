<?php

include("../conexion.php");

$start_from = $_POST['start'];
$search_in_sql = "";
if (isset($_POST['texto']) && !empty($_POST['texto'])) {
    $texto = $_POST['texto'];
    $search_in_sql .= " WHERE (rol like '%" . $texto . "%' ) ";
}

// if(strlen(trim($search_in_sql)) == 0){
//     $search_in_sql .= " WHERE ".
// }else{
//     $search_in_sql .= " AND ".
// }

$sql = " SELECT * FROM tblRol $search_in_sql ORDER BY idRol ASC offset $start_from ROWS FETCH NEXT 10 ROWS ONLY;";
$query = sqlsrv_query($con, $sql);
$count_row = sqlsrv_has_rows($query);
if ($count_row === false) {
    echo "<div style='text-align:center'><h2>¡Lista de Rol vacía!</h2></div>";
} else {

    $resultado = '
    <div class="table-responsive">
    <table style="text-align:center" class="table table-hover">
    <tr>
    <th>
    Información
    </th>
    <th>
    Rol
    </th>
    <th>
    Opciones
    </th>
    </tr>';

    $t = time();
    while ($row = sqlsrv_fetch_array($query)) {

        $id = $row['idRol'];
        $expand = "expand";
        $sector = "sector" . $id;
        $url = "";
        $otro = "    
                                    <div id='sector" . $id . "' class='email' onclick='this.classList.add(\"$expand\")'>
                                        <div class='from'>
                                            <div class='from-contents'>
                                            <div class='avatar me' style='background-image: url($url)'></div>
                                            <div class='name'>" . $row['rol'] . "</div>
                                            </div>
                                        </div>
                                        <div class='to'>
                                            <div class='to-contents'>
                                            <div class='top'>
                                                <div class='avatar-large me' style='background-image: url()'></div>
                                                <div class='name-large'>" . $row['rol'] . "</div>
                                                <div class='x-touch' onclick='document.getElementById(\"$sector\").classList.remove(\"$expand\");event.stopPropagation();'>
                                                <div class='x'>
                                                    <div class='line1'></div>
                                                    <div class='line2'></div>
                                                </div>
                                                </div>
                                            </div>
                                            <div class='bottom'>
                                                <div class='row2'>

                                                        

                                                        <div class='table-responsive'>
                                                                <table style='margin:5px auto; width: 85%; border-collapse: separate;border:hidden;' class='table tdstyle' border='1' >  
                                                                                 
                                    <tr>
                                        <td >Rol</td>
                                        <td >" . $row["rol"] . "</td>
                                    </tr>
                                                                                                        
                                                                </table>  
                                                            </div> 
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                    ";


        $resultado .= ' <tr style="cursor:pointer">
                                            <td>
                                                ' . $otro . '
                                            </td>
                                            <td>' . $row['rol'] . '</td>
                                            <td>';
        // $resultado .= '
        //                                         <button class="btn btn-danger" data-toggle="modal" data-target="#modal_eliminar_rol" data-id="' . $row['idRol'] . '"> <i class="fas fa-trash"></i></button>';
        $resultado .= '
                                                <button class="btn btn-primary" onclick="edit_rol(\'' . $row['idRol'] . '\')"> <i class="fas fa-edit"></i></button>
                                            </td>
                                      </tr>
                            ';
    }

    $resultado .= "
                                </table>
                            </div>
                            
                    ";

    echo $resultado;
}
