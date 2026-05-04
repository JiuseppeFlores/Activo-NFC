<?php
        $miuseragent = $_SERVER['HTTP_USER_AGENT'];
        $moviles = array("Mobile", "iPhone", "iPod", "BlackBerry", "Opera Mini", "Sony", "MOT", "Nokia", "samsung");
        $device = false;
        $numMoviles = count($moviles);
        for ($i = 0; $i < $numMoviles; $i++) {
        $comprobar = strpos($miuseragent, $moviles[$i]);
            if ($comprobar != "") {
                $device = true;
                break;
            }
        }
?>