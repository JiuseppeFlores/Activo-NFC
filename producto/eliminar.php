<?php
include("../conexion.php");
$id=intval($_POST["id"]);
$sql="DELETE FROM tblProducto WHERE idProducto=".$id.";";
$query_delete = sqlsrv_query($con,$sql);
if ($query_delete){
        if(file_exists('../Images/producto/' . $id . ".png")){
                unlink('../Images/producto/'.$id.".png");
                if(!file_exists('../Images/producto/'.$id.".png")){
                        echo 1;
                }else{
                        echo 2;
                }
        }else{
                echo 1;
        } 
} else{
        echo 2;
}
?>
                            