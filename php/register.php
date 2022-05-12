<?php
// Process and return if the registeration is success
try {
    if (!isset($_REQUEST['account']) || empty($_REQUEST['account']))
    {
        echo 'FAILED';
        exit();
    }

    $conn = require_once "../db_account/config.php";

    $username = $_REQUEST["name"];
    $phonenumber = $_REQUEST["phonenumber"];
    $account = $_REQUEST["account"];
    $password = $_REQUEST["password"];
    $latitude = $_REQUEST["latitude"];
    $longitude = $_REQUEST["longitude"];

    // search for same account
    $stmt = $conn->prepare("SELECT * FROM user WHERE account=:account");
    $stmt->execute(array('account' => $account));

    if ($stmt->rowCount() == 0){
        // Store data
        try {
            // hash password
            $hashpwd = hash('sha256', $password);

            $sql = "
                INSERT INTO user (account, password, name, phone, location)
                VALUES (:account, :password, :name, :phone, ST_GeomFromText(:value))";
            $stmt = $conn->prepare($sql);
            $datas = [':account'=>$account, ':password'=>$hashpwd, ':name'=>$username, ':phone'=>$phonenumber, ':value'=>"POINT($latitude $longitude)"];
            $stmt->execute($datas);
        }
        catch (PDOException $e){
            echo "Error: ".$e->getMessage();
            exit;
        }
        echo "Success";
    }
    else{
        echo "Exist";
    }
}
catch (Exception $e){
    echo "ERROR: ".$e->getMessage();
}
?>