<?php
session_start();

if (!isset($_SESSION['account']) || $_SESSION['logged'] != true){
    echo 'FAILED ON AUTHENTICATION';
    exit();
}

try{
    $shop_name = $_REQUEST['shopName'];
    $account = $_SESSION["account"];

    $conn = require_once "../db_account/config.php";

    // Start Transaction
    $conn->beginTransaction();

    // Get user logitude and latitude
    $sql = "SELECT ST_X(location) as longitude, ST_Y(location) as latitude FROM user WHERE account = :account";
    $stmt = $conn->prepare($sql);
    $data = [':account'=>$account];
    $stmt->execute($data);
    $row = $stmt->fetch();
    $latitude = $row['latitude'];
    $longitude = $row['longitude']; 

    // Get the distance
    $stmt = $conn->prepare("select *, ST_Distance_Sphere(POINT(:longitude,:latitude),location) as distance from shop where shopname = :shopname");
    $stmt->execute(array('longitude'=>$longitude, 'latitude'=>$latitude, 'shopname'=>$shop_name));
    $row = $stmt->fetch();
    $distance = $row['distance'];

    // Calculate fee
    $dilivery_fee = $distance / 100;
    echo "$dilivery_fee";
}
catch (Exception $e){
    if ($conn->inTransaction())
        $conn->rollback();
    $msg = $e->getMessage();
    echo "$msg";
}
?>