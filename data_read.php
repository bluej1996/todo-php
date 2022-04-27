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

    mysqli_set_charset($conn, "utf-8");

    $sql = "SELECT * FROM todo ORDER BY id DESC";
    $res = mysqli_query($conn, $sql);
    $result = array();
    
    while($row = mysqli_fetch_array($res)) {
        array_push($result, array('id' => $row[0], 'title' => $row[1], 'complete' => $row[2], 'body' => $row[3] ) );
    }
    echo json_encode(array("result" => $result), JSON_UNESCAPED_UNICODE);

    mysqli_close($conn);

?>