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

    $id = $_GET['id'];
    // 데이터를 삽입하는 SQL 구문
    $sql = "DELETE FROM todo WHERE id=$id";
    $result = mysqli_query($conn, $sql);

    if($result) {
        echo json_encode(["result" => 1]);
    }else{
        echo json_encode(["result" => 0]);
    }


    mysqli_close($conn);

?>