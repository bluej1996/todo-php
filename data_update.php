<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET,POST");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $host = 'localhost';
    $user = 'bluej1996';
    $pw = 'pwa407!!';
    $db = 'bluej1996';
    $conn = mysqli_connect($host, $user, $pw, $db);

    // 데이터를 삽입하는 SQL 구문
    $sql = "UPDATE todo SET title='HONG', body='BODY BODY' WHERE id='2'";
    $result = mysqli_query($conn, $sql);

    if($result) {
        echo 'Success';
    }else{
        echo 'Error';
    }

    mysqli_close($conn);

?>