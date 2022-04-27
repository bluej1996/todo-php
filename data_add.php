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

    $title = $_GET["title"];
    $body = $_GET["body"];

    // 데이터를 삽입하는 SQL 구문
    $sql = "INSERT INTO todo(title, body) VALUES('{$title}', '{$body}')";
    if( mysqli_query($conn, $sql) ) {
        echo json_encode(["result" => 1]);
    }else{
        echo json_encode(["result" => 0]);
    }

    mysqli_close($conn);

?>