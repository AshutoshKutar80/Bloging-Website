<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "social_network";

    $conn = new mysqli($host, $user, $password, $database);
    if($conn == false)
    {
        echo "not estabalish";
    }
   

?>