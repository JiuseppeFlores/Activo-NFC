<?php
include("../conexion.php");
$id = intval($_POST["id"]);
$sql = "DELETE FROM tblUsuario WHERE idUsuario=" . $id . ";";
$query_delete = sqlsrv_query($con, $sql);
if ($query_delete) {
        if (file_exists('../Images/usuario/' . $id . ".png")) {
                unlink('../Images/usuario/' . $id . ".png");
                if (!file_exists('../Images/usuario/' . $id . ".png")) {
                        echo 1;
                } else {
                        echo 2;
                }
        } else {
                echo 1;
        }
} else {
        echo 2;
}
