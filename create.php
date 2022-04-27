<?php
    $host = 'localhost';
    $user = 'bluej1996';
    $pw = 'pwa407!!';
    $db = 'bluej1996';
    $conn = mysqli_connect($host, $user, $pw, $db);

    $sql = "CREATE TABLE todo (
        ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        TITLE VARCHAR(30) NOT NULL,
        COMPLETE BOOLEAN NOT NULL DEFAULT 0,
        BODY TEXT NOT NULL
    )";

    if(mysqli_query($conn, $sql)){
        echo 'Success';
    }else {
        echo 'Error';
    }
    mysqli_close($conn);
?>