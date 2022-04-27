<?php
    $host = 'localhost';
    $user = 'bluej1996';
    $pw = 'pwa407!!';
    $db = 'bluej1996';
    $conn = mysqli_connect($host, $user, $pw, $db);

    if(!$conn) {
        die('ERROR');
    }
?>