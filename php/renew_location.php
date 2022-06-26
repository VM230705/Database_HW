<?php
session_start();

if (!isset($_SESSION['account']) || $_SESSION['logged'] != true){
    echo 'FAILED ON AUTHENTICATION';
    exit();
}

// update database
try{
    $latitude = $_REQUEST["latitude"];
    $longitude = $_REQUEST["longitude"];
    $account = $_SESSION["account"];

    $conn = require_once "../db_account/config.php";

    // update database value
    $sql = "UPDATE user SET location=ST_GeomFromText(:value) where account=:account";
    $stmt = $conn->prepare($sql);
    $data = [':account'=>$account, ':value'=>"POINT($longitude $latitude)"];
    $stmt->execute($data);

    // update session value
    $sql = "SELECT ST_X(location) as longitude, ST_Y(location) as latitude FROM user WHERE account = :account";
    $stmt = $conn->prepare($sql);
    $data = [':account'=>$account];
    $stmt->execute($data);
    $row = $stmt->fetch();
    $_SESSION['latitude'] = $row['latitude'];
    $_SESSION['longitude'] = $row['longitude'];

    // fetch data
    $name = $_SESSION['name'];
    $phone = $_SESSION['phone'];
    $latitude = $_SESSION['latitude'];
    $longitude = $_SESSION['longitude'];

    echo "Account: $account, Name: $name, Phone: $phone, Location: (LONGITUDE: $longitude, LATITUDE: $latitude)";
}
catch (Exception $e){
    echo "$e->getMessage()";
}


?>